<?php

namespace Webkul\Marketplace\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\Communication;

class CommunicationRepository extends Repository
{
    /**
     * The date ranges for the message history.
     */
    public const DATE_RANGES = [
        'prev-seven-days'   => 7,
        'prev-fifteen-days' => 15,
        'prev-thirty-days'  => 30,
        'prev-six-months'   => 180,
    ];

    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return Communication::class;
    }

    /**
     * Method to create or update the communication.
     */
    public function updateOrCreateCommunication(array $data, int $sellerId, string $senderType)
    {
        DB::beginTransaction();

        try {
            $communication = $this->findOneByField('marketplace_seller_id', $sellerId);

            if (! $communication) {
                $communication = $this->create([
                    'marketplace_seller_id' => $sellerId,
                    'subject'               => $data['message'],
                ]);
            } else {
                if ($communication->is_blocked) {
                    throw new \Exception(trans('marketplace::app.seller.communication.index.blocked-message'));
                }

                $communication->update(['subject' => $data['message']]);
            }

            $message = $communication->messages()->create([
                'sender_type' => $senderType,
                'text'        => $data['message'],
            ]);

            if (
                isset($data['attachment'])
                && $data['attachment'] instanceof UploadedFile
            ) {
                $this->handleAttachment(
                    $message,
                    $data['attachment'],
                    $communication,
                    $data['message']
                );
            }

            DB::commit();

            return [
                'communication' => $communication->refresh()->load('messages'),
                'messages'      => $message->refresh(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Handle the attachment.
     */
    private function handleAttachment($message, $attachment, $communication, $messageText)
    {
        $filePath = $attachment->store("marketplace/communication/{$message->id}");
        $fileName = $attachment->getClientOriginalName();
        $fileType = $attachment->getClientMimeType();

        $message->update([
            'attachment'      => $filePath,
            'attachment_name' => $fileName,
            'attachment_type' => $fileType,
        ]);

        if (empty($messageText)) {
            $communication->update(['subject' => $fileName]);
        }
    }

    /**
     * Method to get the communication messages.
     */
    public function getMessages(array $params)
    {
        $days = $params['days'] ?? null;
        $daysRange = self::DATE_RANGES[$days] ?? null;

        return $this->with('messages')
            ->scopeQuery(function ($query) use ($params, $daysRange) {
                return $query
                    ->leftJoin('mp_communication_messages', 'mp_communication_messages.mp_communication_id', '=', 'mp_communications.id')
                    ->when($daysRange, function ($query) use ($daysRange) {
                        return $query->where('mp_communication_messages.created_at', '>=', now()->subDays($daysRange));
                    })
                    ->where('marketplace_seller_id', $params['seller_id'])
                    ->orderBy('mp_communication_messages.created_at', 'asc');
            })
            ->get();
    }

    /**
     * Method to clear the chat.
     */
    public function clearChat($communication)
    {
        Storage::deleteDirectory("marketplace/communication/{$communication->id}");

        $communication->update(['subject' => null]);

        $communication->messages()->delete();

        return $communication->refresh();
    }
}

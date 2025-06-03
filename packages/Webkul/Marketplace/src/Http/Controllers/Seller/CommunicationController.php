<?php

namespace Webkul\Marketplace\Http\Controllers\Seller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Webkul\Marketplace\Enums\SenderType;
use Webkul\Marketplace\Http\Resources\Communication\CommunicationMessageResource;
use Webkul\Marketplace\Repositories\CommunicationRepository;

class CommunicationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CommunicationRepository $communicationRepository) {}

    /**
     * Method to load the index page for the seller communication.
     */
    public function index(): JsonResource|View
    {
        $communication = $this->communicationRepository->findOneWhere([
            'marketplace_seller_id' => seller()->user()->id,
        ]);

        if ($communication) {
            $communication->messages()
                ->where('sender_type', SenderType::ADMIN->value)
                ->update(['is_read' => 1]);

            $communication = $communication->refresh();
        }

        return view('marketplace::seller.communication.index')
            ->with([
                'isBlocked' => $communication?->is_blocked,
                'messages'  => CommunicationMessageResource::collection($communication->messages ?? []),
            ]);
    }

    /**
     * Method to store the communication.
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message'    => 'string|max:255',
            'attachment' => 'nullable|file|max:1024|mimes:jpeg,jpg,png,pdf,doc,docx',
        ]);

        try {
            $result = $this->communicationRepository->updateOrCreateCommunication(
                $data,
                seller()->user()->id,
                SenderType::SELLER->value
            );

            return new JsonResponse([
                'message'     => trans('marketplace::app.admin.communications.index.message-sent'),
                'new_message' => new CommunicationMessageResource($result['messages']),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'message' => trans('marketplace::app.admin.communications.index.message-failed'),
            ], 500);
        }
    }

    /**
     * Method to get the communication history.
     */
    public function messages(): JsonResponse
    {
        $params = [
            'seller_id' => seller()->user()->id,
            'days'      => request()->input('days'),
        ];

        $messages = $this->communicationRepository->getMessages($params);

        return new JsonResponse([
            'messages' => CommunicationMessageResource::collection($messages),
        ], 200);
    }
}

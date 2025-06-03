<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\View\View;
use Webkul\Marketplace\Enums\SenderType;
use Webkul\Marketplace\Http\Resources\Communication\CommunicationMessageResource;
use Webkul\Marketplace\Http\Resources\Communication\CommunicationResource;
use Webkul\Marketplace\Http\Resources\Communication\SellerResource;
use Webkul\Marketplace\Repositories\CommunicationRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class CommunicationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected CommunicationRepository $communicationRepository
    ) {}

    /**
     * Method to load the index page for the seller communication.
     */
    public function index(): JsonResource|View
    {
        if (request()->ajax()) {
            $params = ['name' => request()->input('query')];

            $sellers = $this->sellerRepository->getSellersForCommunication($params);

            return SellerResource::collection($sellers);
        }

        return view('marketplace::admin.communications.index');
    }

    /**
     * Method to store the communication.
     */
    public function sendMessage(Request $request, $sellerId): JsonResponse
    {
        $data = $request->validate([
            'message'    => 'string|max:255',
            'attachment' => 'nullable|file|max:1024|mimes:jpeg,jpg,png,pdf,doc,docx',
        ]);

        try {
            $result = $this->communicationRepository->updateOrCreateCommunication(
                $data,
                $sellerId,
                SenderType::ADMIN->value
            );

            return new JsonResponse([
                'message'       => trans('marketplace::app.admin.communications.index.message-sent'),
                'communication' => new CommunicationResource($result['communication']),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('marketplace::app.admin.communications.index.message-failed'),
            ], 500);
        }
    }

    /**
     * Method to get the communication history.
     */
    public function messages($sellerId): JsonResponse
    {
        $params = [
            'seller_id' => $sellerId,
            'days'      => request()->input('days'),
        ];

        $messages = $this->communicationRepository->getMessages($params);

        return new JsonResponse([
            'messages' => CommunicationMessageResource::collection($messages),
        ], 200);
    }

    /**
     * Method to mark the communication as read.
     */
    public function markAsRead($sellerId): JsonResponse
    {
        $communication = $this->communicationRepository->findOneWhere([
            'marketplace_seller_id' => $sellerId,
        ]);

        if ($communication) {
            $communication->messages()
                ->where('sender_type', SenderType::SELLER->value)
                ->update(['is_read' => 1]);

            $communication = $communication->refresh();
        }

        return new JsonResponse([
            'success'       => true,
            'communication' => new CommunicationResource($communication),
        ], 200);
    }

    /**
     * Method to clear the chat.
     */
    public function clearChat($sellerId): JsonResponse
    {
        $communication = $this->communicationRepository->findOneWhere([
            'marketplace_seller_id' => $sellerId,
        ]);

        if (! $communication) {
            return new JsonResponse([
                'success' => false,
            ], 200);
        }

        $communication = $this->communicationRepository->clearChat($communication);

        return new JsonResponse([
            'success'       => true,
            'message'       => trans('marketplace::app.admin.communications.index.chat-history-cleared'),
            'communication' => new CommunicationResource($communication),
        ], 200);
    }

    /**
     * Method to get block the seller.
     */
    public function handleAction(int $id): JsonResponse
    {
        $this->validate(request(), [
            'action' => 'required|string|in:block,unblock',
        ]);

        $action = request()->input('action');

        $this->communicationRepository->update([
            'is_blocked' => $action === 'block',
        ], $id);

        return new JsonResponse([
            'success' => true,
            'message' => trans('marketplace::app.admin.communications.index.'.$action.'-success'),
        ], 200);
    }
}

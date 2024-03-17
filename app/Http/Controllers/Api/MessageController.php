<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Services\MessageService;


class MessageController extends Controller{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index()
    {
        $user = auth()->user();
        $percentage = (string)$this->messageService->calculateMessagePercentage($user);

        return response()->json([
            'data' => [
                'percentage' => $percentage,
                'target' =>(string) $user->section->follow_up_target,
                'messages' => MessageResource::collection(getDataForCurrentMonth($user,Message::class)),
            ],
        ]);
    }

}

<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Services\MessageService;
use Carbon\Carbon;

class MessageController extends Controller{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index()
    {
        $user = auth()->user();
        $percentage = $this->messageService->calculateMessagePercentage($user);

        return response()->json([
            'data' => [
                'percentage' => $percentage,
                'target' => $user->section->follow_up_target,
                'programs' => MessageResource::collection($this->messageService->getMessageForCurrentMonth($user)),
            ],
        ]);
    }

}

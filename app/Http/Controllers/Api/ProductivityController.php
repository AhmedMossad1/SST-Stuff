<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\Program;
use App\Services\ProgramService;
use App\Services\MessageService;

class ProductivityController extends Controller
{
    protected $programService;
    protected $messageService;

    public function __construct(ProgramService $programService, MessageService $messageService)
    {
        $this->programService = $programService;
        $this->messageService = $messageService;
    }

    public function index()
    {
        $user = auth()->user();
        $programPercentage = $this->programService->calculateProgramPercentage($user);
        $messagePercentage = $this->messageService->calculateMessagePercentage($user);
        $maxPercentage = max($programPercentage, $messagePercentage);
        $target = $this->getTarget($maxPercentage);

        return response()->json([
            'data' => [
                'percentage' => (string)$maxPercentage,
                'target' => (string)$target,
                'programs' => ProgramResource::collection(getDataForCurrentMonth($user,Program::class)),
                'messages' => MessageResource::collection(getDataForCurrentMonth($user,Message::class)),
            ],
        ]);
    }

    private function getTarget(float $percentage): float
    {
        if ($percentage == $this->programService->calculateProgramPercentage(auth()->user())) {
            return auth()->user()->section->programs_target;
        } else {
            return auth()->user()->section->follow_up_target;
        }
    }
}

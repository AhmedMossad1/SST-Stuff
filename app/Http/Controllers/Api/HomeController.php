<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\MessageService;
use App\Services\ProgramService;

class HomeController extends Controller
{
    protected $programService;
    protected $messageService;

    public function __construct(ProgramService $programService,MessageService $messageService)
    {
        $this->programService = $programService;
        $this->messageService = $messageService;
    }

    public function index()
    {
        $user = auth()->user();
        $programsPercentage = $this->programService->calculateProgramPercentage($user);
        $messagePercentage = $this->messageService->calculateMessagePercentage($user);

        return response()->json([
            'data' => [
                'name' => $user->name,
                'Program percentage' => $programsPercentage,
                'message percentage' => $messagePercentage,
            ],
        ]);
    }

}

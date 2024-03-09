<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\MessageService;
use App\Services\ProgramService;
use App\Services\AttendanceService;

class HomeController extends Controller
{
    protected $programService;
    protected $messageService;
    protected $attendanceService;

    public function __construct(ProgramService $programService,MessageService $messageService,AttendanceService $attendanceService)
    {
        $this->programService = $programService;
        $this->messageService = $messageService;
        $this->attendanceService = $attendanceService;
    }

    public function index()
    {
        $user = auth()->user();
        $userId = auth()->id();
        $programsPercentage = $this->programService->calculateProgramPercentage($user);
        $messagePercentage = $this->messageService->calculateMessagePercentage($user);
        $productivity = max($programsPercentage,$messagePercentage);
        $attendanceService = $this->attendanceService->calculatePointsForUser($userId);


        return response()->json([
            'data' => [
                'productivity percentage' => $productivity,
                'attend percentage' => $attendanceService,

            ],
        ]);
    }

}

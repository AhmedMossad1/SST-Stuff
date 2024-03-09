<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\MessageService;
use App\Services\ProgramService;
use App\Services\AttendanceService;
use App\Services\ErrorsService;

class HomeController extends Controller
{
    protected $programService;
    protected $messageService;
    protected $attendanceService;
    protected $errorsService;

    public function __construct(ProgramService $programService,MessageService $messageService,AttendanceService $attendanceService,ErrorsService $errorsService)
    {
        $this->programService = $programService;
        $this->messageService = $messageService;
        $this->attendanceService = $attendanceService;
        $this->errorsService = $errorsService;
    }

    public function index()
    {
        $user = auth()->user();
        $userId = auth()->id();
        $programsPercentage = $this->programService->calculateProgramPercentage($user);
        $messagePercentage = $this->messageService->calculateMessagePercentage($user);
        $productivity = max($programsPercentage,$messagePercentage);
        $attendanceService = $this->attendanceService->calculatePointsForUser($userId);
        $errorsService = $this->errorsService->calculateErrorsPercentage($userId);

        return response()->json([
            'data' => [
                'productivity percentage' => $productivity,
                'attend percentage' => $attendanceService,
                'errors percentage' => $errorsService,

            ],
        ]);
    }

}

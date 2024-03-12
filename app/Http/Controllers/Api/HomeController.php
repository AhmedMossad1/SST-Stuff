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

    public function __construct(
        ProgramService $programService,
        MessageService $messageService,
        AttendanceService $attendanceService,
        ErrorsService $errorsService
    )
    {
        $this->programService = $programService;
        $this->messageService = $messageService;
        $this->attendanceService = $attendanceService;
        $this->errorsService = $errorsService;
    }

    public function index()
    {
        $user = auth()->user();

        $programsPercentage = $this->programService->calculateProgramPercentage($user);
        $messagePercentage = $this->messageService->calculateMessagePercentage($user);

        $productivity = (string) max($programsPercentage, $messagePercentage);
        $attendanceService = (string) $this->attendanceService->calculatePointsForUser($user);
        $errorsService = (string) $this->errorsService->calculateErrorsPercentage(auth()->id());

        $finalPercentage =  (string) $this->calculateFinalPercentage($productivity, $attendanceService, $errorsService);
        $finalGrade = $this->getGrade($finalPercentage);

        return response()->json([
            'data' => [
                'percentage' => [
                'productivity' => $productivity,
                'attend' => $attendanceService,
                'errors' => $errorsService,
                'final_percentage' => $finalPercentage,
                'final_grade' => $finalGrade,
                ],

            ],
        ]);
    }
    private function calculateFinalPercentage($productivity, $attendanceService, $errorsService)
    {
        return (($productivity * 30) + ($attendanceService * 50) + ($errorsService * 20)) / 100;
    }
    private function getGrade($finalPercentage)
    {
        if ($finalPercentage >= 90) {
            return 'A';
        } elseif ($finalPercentage >= 80) {
            return 'B';
        } elseif ($finalPercentage >= 70) {
            return 'C';
        } elseif ($finalPercentage >= 60) {
            return 'D';
        } else {
            return 'F';
        }
    }
}

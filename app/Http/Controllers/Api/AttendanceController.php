<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Services\AttendanceService;
class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }
    public function index(){
        $user = auth()->user();
        $totalPoints = (string) $this->attendanceService->calculatePointsForUser($user);
        return response()->json([
            'data' => [
                'percentage' => $totalPoints,
                'Attendnce' => AttendanceResource::collection($this->attendanceService->getAttendForCurrentMonth($user)),
            ],
    ]);
    }

}

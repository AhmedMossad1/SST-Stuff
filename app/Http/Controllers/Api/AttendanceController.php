<?php
namespace App\Http\Controllers\Api;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Services\AttendanceService;
class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }
    public function index(){
        $userId = auth()->id();
        $attendce = Attendance::where('user_id',auth()->id())->get();
        $totalPoints = $this->attendanceService->calculatePointsForUser($userId);
        return response()->json([
            'data' => [
                'percentage' => $totalPoints,
                'Attendnce' => AttendanceResource::collection($attendce),
            ],
    ]);
    }

}

<?php
namespace App\Http\Controllers\Api;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
class AttendanceController extends Controller
{
    public function index(){
        $userId = auth()->id();
        $attendce = Attendance::where('user_id',auth()->id())->get();
    $totalPoints = $this->calculatePointsForUser($userId)/100;
        return response()->json([
            'data' => [
                'percentage' => $totalPoints,
                'Attendnce' => AttendanceResource::collection($attendce),
            ],
    ]);
    }

    public function calculatePointsForUser($userId)
    {
        $attendances = Attendance::where('user_id', $userId)->get();
        $totalDelayHours = 0;
        $hasVacationAccept = false;
        $hasAbsenceNotAccept = false;
        foreach ($attendances as $attendance) {
            if ($attendance->delay) {
                    $delayHours = Carbon::parse($attendance->delay)->diffInMinutes(Carbon::parse('00:00:00')) / 60;
                    $totalDelayHours += $delayHours;
            }
            if (
                (strpos($attendance->asked_Note, 'not accept') !== false ||
                    strpos($attendance->asked_Note, 'تاخير') !== false) &&
                    $delayHours > 0
            ) {
                $totalDelayHours = max(0, $totalDelayHours);
            }
            if (strpos($attendance->asked_Note, 'اجازة') !== false && strpos($attendance->asked_Note, 'accept') !== false) {
                    $hasVacationAccept = true;
            }
        if (strpos($attendance->asked_Note, 'غياب') !== false && strpos($attendance->asked_Note, 'not accept') !== false) {
                $hasAbsenceNotAccept = true;
        }
    }
    $totalPoints = max(100 - (floor($totalDelayHours) * 5), 0);

    if ($hasVacationAccept) {
        $totalPoints = max(0, $totalPoints - 20);
    }
    if ($hasAbsenceNotAccept) {
        $totalPoints = max(0, $totalPoints - 50);
    }
    return $totalPoints;
}
}

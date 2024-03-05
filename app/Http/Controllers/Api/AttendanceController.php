<?php
namespace App\Http\Controllers\Api;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
class AttendanceController extends Controller
{
    public function show(){
        $userId = auth()->id();
        $attendce = Attendance::where('user_id',auth()->id())->get();
    $totalPoints = $this->calculatePointsForUser($userId);
        return response()->json([
            'data' => [
                'Attendnce' => AttendanceResource::collection($attendce),
                'totalPoints' => $totalPoints,
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

        // Check for 'غياب' and 'not accept' in asked_Note
        if (strpos($attendance->asked_Note, 'غياب') !== false && strpos($attendance->asked_Note, 'not accept') !== false) {
            $hasAbsenceNotAccept = true;
        }
    }

    $totalPoints = max(100 - (floor($totalDelayHours) * 5), 0);

    // Deduct 20 points if 'اجازة' and 'accept' are both present in asked_Note
    if ($hasVacationAccept) {
        $totalPoints = max(0, $totalPoints - 20);
    }

    // Deduct 50 points if 'غياب' is present and 'not accept' is not present in asked_Note
    if ($hasAbsenceNotAccept) {
        $totalPoints = max(0, $totalPoints - 50);
    }

    return $totalPoints;
}
}

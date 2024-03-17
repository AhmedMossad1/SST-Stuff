<?php
namespace App\Services;
use Carbon\Carbon;
use App\Models\Attendance;
use function getDataForCurrentMonth;
class AttendanceService
{

    public function calculatePointsForUser($userId)
    {
        $attendances = getDataForCurrentMonth($userId,Attendance::class);
        $totalDelayHours = $this->calculateTotalDelayHours($attendances);
        $hasVacationAccept = $this->hasVacationAccept($attendances);
        $hasAbsenceNotAccept = $this->hasAbsenceNotAccept($attendances);

        $totalPoints = max(100 - (floor($totalDelayHours) * 5), 0);

        if ($hasVacationAccept) {
            $totalPoints = max(0, $totalPoints - 20);
        }

        if ($hasAbsenceNotAccept) {
            $totalPoints = max(0, $totalPoints - 50);
        }

        return $totalPoints;
    }

    private function calculateTotalDelayHours($attendances)
    {
        $totalDelayHours = 0;

        foreach ($attendances as $attendance) {
            if ($attendance->delay) {
                $delayHours = Carbon::parse($attendance->delay)->diffInMinutes(Carbon::parse('00:00:00')) / 60;
                $totalDelayHours += max(0, $delayHours);
            }
        }

        return $totalDelayHours;
    }

    private function hasVacationAccept($attendances)
    {
        foreach ($attendances as $attendance) {
            if (strpos($attendance->asked_Note, 'اجازه') !== false && strpos($attendance->asked_Note, 'accept') !== false) {
                return true;
            }
        }

        return false;
    }

    private function hasAbsenceNotAccept($attendances)
    {
        foreach ($attendances as $attendance) {
            if (strpos($attendance->asked_Note, 'غياب') !== false && strpos($attendance->asked_Note, 'not accept') !== false) {
                return true;
            }
        }

        return false;
    }

}

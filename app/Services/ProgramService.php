<?php
namespace App\Services;
use App\Models\Program;
use Carbon\Carbon;

class ProgramService
{
    public function calculateProgramPercentage($user)
    {
        $negativePointsSum = 0;

        foreach ($this->getProgramsForCurrentMonth($user) as $program) {
            $points = $program->programs - $user->section->programs_target;
            if ($points < 0) {
                $negativePointsSum += $points;
            }
        }
        return ($negativePointsSum < 0) ? (100 - abs($negativePointsSum))  : 0;
    }

    public function getProgramsForCurrentMonth($user)
    {
        $currentMonth = Carbon::now()->format('Y-m');

        return Program::where('user_id', $user->id)
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->get();
    }
}

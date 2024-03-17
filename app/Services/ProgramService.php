<?php
namespace App\Services;
use App\Models\Program;
use Carbon\Carbon;

class ProgramService
{
    public function calculateProgramPercentage($user)
    {
        $negativePointsSum = 0;
        $programs= getDataForCurrentMonth($user, Program::class);

        foreach ($programs as $program) {
            $points = $program->programs - $user->section->programs_target;
            if ($points < 0) {
                $negativePointsSum += $points;
            }
        }
        return ($negativePointsSum < 0) ? (100 - abs($negativePointsSum))  : 0;
    }
}

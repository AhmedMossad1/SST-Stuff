<?php
namespace App\Services;
use App\Models\Message;
use Carbon\Carbon;

class MessageService
{
    public function calculateMessagePercentage($user)
    {
        $negativePointsSum = 0;

        foreach ($this->getMessageForCurrentMonth($user) as $program) {
            $points = $program->programs - $user->section->programs_target;
            if ($points < 0) {
                $negativePointsSum += $points;
            }
        }
        return ($negativePointsSum < 0) ? (100 - abs($negativePointsSum)) / 100 : 0;
    }

    public function getMessageForCurrentMonth($user)
    {
        $currentMonth = Carbon::now()->format('Y-m');

        return Message::where('user_id', $user->id)
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->get();
    }
}

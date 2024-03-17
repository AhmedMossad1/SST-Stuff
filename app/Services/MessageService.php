<?php
namespace App\Services;
use App\Models\Message;
//use Carbon\Carbon;
use function getDataForCurrentMonth;


class MessageService
{
    public function calculateMessagePercentage($user)
    {

        $negativePointsSum = 0;
        $messages= getDataForCurrentMonth($user, Message::class);

        foreach ($messages as $message) {
            $points = $message->messages - $user->section->follow_up_target;
            if ($points < 0) {
                $negativePointsSum += $points;
            }
        }
        return ($negativePointsSum < 0) ? (100 - abs($negativePointsSum))  : 0;
    }
   // MessageService::calculateMessagePercentage($user)

    // public function getMessageForCurrentMonth($user)
    // {
    //     return getDataForCurrentMonth($user, Message::class);
    // }


    // public function getMessageForCurrentMonth($user)
    // {
    //     $currentMonth = Carbon::now()->format('Y-m');

    //     return Message::where('user_id', $user->id)
    //         ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
    //         ->get();
    // }
}

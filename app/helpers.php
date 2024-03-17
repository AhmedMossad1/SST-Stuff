<?php
use Carbon\Carbon;


    function getDataForCurrentMonth($user, $model)
    {
        $currentMonth = Carbon::now()->format('Y-m');

        return $model::where('user_id', $user->id)
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->get();
    }


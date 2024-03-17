<?php
namespace App\Services;
use App\Models\Error as ModelsError;


class ErrorsService
{

    public function calculateErrorsPercentage($userId)
    {
        $currentMonthErrors = getDataForCurrentMonth($userId,ModelsError::class);
        $total = $currentMonthErrors->sum('degree');

        return ($total <= 100) ? (100 - $total)  : 0;
    }
}

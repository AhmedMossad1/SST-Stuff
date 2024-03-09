<?php
namespace App\Services;
use App\Models\Error as ModelsError;
use Carbon\Carbon;

class ErrorsService
{
    public function getErrorsForCurrentMonth($userId)
    {
        $currentMonth = Carbon::now()->format('Y-m');

        return ModelsError::where('user_id', $userId)
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->get();
    }

    public function calculateErrorsPercentage($userId)
    {
        $currentMonthErrors = $this->getErrorsForCurrentMonth($userId);
        $total = $currentMonthErrors->sum('degree');

        return ($total <= 100) ? (100 - $total) / 100 : 0;
    }
}

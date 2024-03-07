<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use Carbon\Carbon;

class ProgramController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->format('Y-m');

        $programs = Program::where('user_id', auth()->id())
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->get();

        $target = auth()->user()->section->programs_target;
        $negativePointsSum = 0;

        foreach ($programs as $program) {
            $points = $program->programs - $target;
            if ($points < 0) {
                $negativePointsSum += $points;
            }
        }

        if ($negativePointsSum < 0) {
            $percentage = (100 - abs($negativePointsSum)) / 100;
        } else {
            $percentage = 0;
        }

        return response()->json([
            'data' => [
                'percentage' => $percentage,
                'target' => $target,
                'programs' => ProgramResource::collection($programs),
            ],
        ]);
    }
}

<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use App\Services\ProgramService;

class ProgramController extends Controller
{
    protected $programService;

    public function __construct(ProgramService $programService)
    {
        $this->programService = $programService;
    }

    public function index()
    {
        $user = auth()->user();
        $percentage =(string)$this->programService->calculateProgramPercentage($user);

        return response()->json([
            'data' => [
                'percentage' => $percentage,
                'target' =>(string) $user->section->programs_target,
                'programs' => ProgramResource::collection($this->programService->getProgramsForCurrentMonth($user)),
            ],
        ]);
    }

}

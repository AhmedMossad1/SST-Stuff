<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    public function show(){
        $programs = Program::where('user_id',auth()->id())->get();
        $target = auth()->user()->section->programs_target;
        return response()->json([
            'data' => [
                'target' => $target,
                'programs' => ProgramResource::collection($programs),
            ],

    ]);
    }

}

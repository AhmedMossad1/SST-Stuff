<?php

namespace App\Http\Controllers\Api;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;

class AttendanceController extends Controller
{

    public function show(){
        $attendce = Attendance::where('user_id',auth()->id())->get();
        return response()->json([
            'data' => [
                'Attendnce' => AttendanceResource::collection($attendce),
            ],
    ]);
    }

}

<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Carbon\Carbon;

class MessageController extends Controller
{
    public function index(){
        $currentMonth = Carbon::now()->format('Y-m');

        $messages = Message::where('user_id', auth()->id())
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->get();
        $target = auth()->user()->section->follow_up_target;
        $negativePointsSum = 0;
        foreach ($messages as $message) {
            $points = $message->messages - $target;
            if ($points < 0) {
                $negativePointsSum += $points;
            }
        }
        if($negativePointsSum>0){
            $percentage= (100-abs($negativePointsSum))/100;
            }
            else{
                $percentage=0;
            }
        return response()->json([
            'data' => [
                'percentage'=>$percentage,
                'target' => $target,
                'messages' => MessageResource::collection($messages),
            ],
    ]);
    }
}

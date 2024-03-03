<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
class MessageController extends Controller
{
    public function show(){
        $messages = Message::where('user_id',auth()->id())->get();
        $target = auth()->user()->section->follow_up_target;
        $negativeMessagesSum = 0;
        foreach ($messages as $message) {
            $points = $message->messages - $target;
            if ($points < 0) {
                $negativeMessagesSum += $points;
            }
        }
        $percentage= (100-abs($negativeMessagesSum))/100;
        return response()->json([
            'data' => [
                'percentage'=>$percentage,
                'target' => $target,
                'messages' => MessageResource::collection($messages),
            ],
    ]);
    }
}

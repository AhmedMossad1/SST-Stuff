<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
class MessageController extends Controller
{
    public function show(){
        $message = Message::where('user_id',auth()->id())->get();
        $total = $message->sum('message');
        $target = auth()->user()->section->follow_up_target;
        $percentage= (100-$total)/100;
        return response()->json([
            'data' => [
                'percentage'=>$percentage,
                'target' => $target,
                'messages' => MessageResource::collection($message),
            ],
    ]);
    }
}

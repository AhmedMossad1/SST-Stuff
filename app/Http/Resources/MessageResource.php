<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $target = $this->user->section->follow_up_target;
        $total = $this->messages;
        $points= $total-$target;
        return [
            'messages'=>$this->messages,
            'points'=>$points,
            'date'=>$this->date,
        ];
    }
}

<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class ProgramResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $target = $this->user->section->programs_target;
        $total = $this->programs;
        $points= $total-$target;
        return [
            'programs'=>$this->programs,
            'points'=>$points,
            'date'=>$this->date,
        ];
    }
}

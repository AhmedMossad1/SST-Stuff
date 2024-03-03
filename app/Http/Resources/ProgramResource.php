<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class ProgramResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            //'target'=>$this->user->section->programs_target,
            'programs'=>$this->programs,
            'date'=>$this->date,
        ];
    }
}

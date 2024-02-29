<?php
namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class ProgramResource extends JsonResource
{

    public function toArray(Request $request): array
    {
       //section_target=25;
    //
        //per %
        //dd($this->user->section->programs_target);
        return [
            //'target'=>$this->user->section->programs_target,

            'programs'=>$this->programs,
            'date'=>$this->date,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'date'=>$this->date,
            'code'=>$this->code,
            'note'=>$this->note,
            'degree'=>$this->degree,
            'state'=>$this->state,
            'solved'=>$this->solved,
            'location'=>$this->location,
        ];
    }
}

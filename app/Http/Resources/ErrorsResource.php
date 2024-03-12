<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'date'=>$this->date,
            'code'=>$this->code,
            'note'=>$this->note,
            'degree'=> (string) $this->degree,
            'state'=>$this->state,
            'solved'=>$this->solved,
            'location'=>$this->location,
        ];
    }
}

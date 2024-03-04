<?php

namespace App\Http\Resources;

use Cron\HoursField;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{

    public function toArray(Request $request): array
    {

        return [
            'attend'=>$this->attend,
            'leave'=>$this->leave,
            'date'=>$this->date,
            'delay'=>$this->delay,
            'time_in_SSt'=>$this->time,
            'asked_note'=>$this->asked_Note,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    //
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id'); // assuming agent_id is the foreign key in appointments table
    }

    public function slot(){
        return $this->belongsTo(Slot::class,'slot_id');
    }
}

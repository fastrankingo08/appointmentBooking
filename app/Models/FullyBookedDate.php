<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FullyBookedDate extends Model
{
    //
    protected $table = 'fully_booked_dates';

    // Specify which columns can be mass-assigned.
    protected $fillable = ['date'];
}

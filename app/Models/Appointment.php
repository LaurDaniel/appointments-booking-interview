<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'time',
    ];

    protected $appends = [
        'start',
        'end',
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStartAttribute()
    {
        return $this->date . ' ' . $this->time;
    }

    public function getEndAttribute()
    {
        return Carbon::parse($this->date . ' ' . $this->time)->addHour();
    }



}

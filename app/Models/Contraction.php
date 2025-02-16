<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Contraction extends Model
{
    use HasFactory;

    protected $fillable = ['start_time', 'end_time'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Vypočíta trvanie kontrakcie
    public function getDurationAttribute()
    {
        return $this->end_time
            ? Carbon::parse($this->start_time)->longAbsoluteDiffForHumans($this->end_time, 2)
            : '—';
    }

    // Vypočíta rozdiel od predchádzajúcej kontrakcie
    public function getSinceLastAttribute()
    {
        $previous = self::where('start_time', '<', $this->start_time)->orderBy('start_time', 'desc')->first();
        return $previous ? $previous->start_time->longAbsoluteDiffForHumans($this->start_time, 2) : '—';
    }
}

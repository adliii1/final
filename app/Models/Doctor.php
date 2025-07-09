<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'specialist',
    ];

    /**
     * Relasi ke model Schedule.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Relasi ke model Booking melalui Schedule.
     */
    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Schedule::class);
    }

    /**
     * Mendapatkan antrian hari ini.
     */
    public function todayQueues()
    {
        $today = now()->format('Y-m-d');
        return Queue::whereHas('booking.schedule', function ($query) use ($today) {
            $query->where('doctor_id', $this->id)
                ->whereDate('tanggal', $today);
        });
    }
}
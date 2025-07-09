<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'schedule_id', 'status', 'catatan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Relasi ke model Queue.
     */
    public function queue()
    {
        return $this->hasOne(Queue::class);
    }

    /**
     * Mendapatkan informasi doctor melalui schedule.
     */
    public function doctor()
    {
        return $this->hasOneThrough(Doctor::class, Schedule::class, 'id', 'id', 'schedule_id', 'doctor_id');
    }

    /**
     * Scope untuk booking yang sudah disetujui.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope untuk booking berdasarkan tanggal.
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereHas('schedule', function ($q) use ($date) {
            $q->whereDate('tanggal', $date);
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id',
        'queue_number',
        'queue_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'queue_date' => 'date',
    ];

    /**
     * Relasi ke model Booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Mendapatkan informasi user melalui booking.
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Booking::class,
            'id',
            'id',
            'booking_id',
            'user_id'
        );
    }

    /**
     * Mendapatkan informasi schedule melalui booking.
     */
    public function schedule()
    {
        return $this->hasOneThrough(
            Schedule::class,
            Booking::class,
            'id',
            'id',
            'booking_id',
            'schedule_id'
        );
    }

    /**
     * Mendapatkan informasi doctor melalui booking dan schedule.
     * Gunakan eager loading untuk performa yang lebih baik.
     */
    public function getDoctorAttribute()
    {
        return $this->booking?->schedule?->doctor;
    }

    /**
     * Scope untuk antrian aktif (waiting dan in_progress).
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['waiting', 'in_progress']);
    }

    /**
     * Scope untuk antrian yang sedang menunggu.
     */
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    /**
     * Scope untuk antrian berdasarkan tanggal.
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('queue_date', $date);
    }

    /**
     * Scope untuk antrian berdasarkan doctor.
     */
    public function scopeByDoctor($query, $doctorId)
    {
        return $query->whereHas('booking.schedule', function ($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        });
    }

    /**
     * Generate nomor antrian berdasarkan tanggal dan doctor.
     */
    public static function generateQueueNumber($scheduleId, $queueDate)
    {
        $schedule = Schedule::find($scheduleId);
        if (!$schedule) {
            return null;
        }

        $lastQueue = self::whereHas('booking.schedule', function ($q) use ($schedule) {
            $q->where('doctor_id', $schedule->doctor_id);
        })
            ->whereDate('queue_date', $queueDate)
            ->orderBy('queue_number', 'desc')
            ->first();

        return $lastQueue ? $lastQueue->queue_number + 1 : 1;
    }
}

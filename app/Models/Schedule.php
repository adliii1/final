<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'doctor_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
    ];

    /**
     * Relasi ke model Doctor.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
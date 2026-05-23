<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperAppointment
 */
class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'status',
        'patient_notes',
        'doctor_notes',
        'cancelled_by',
        'cancelled_at'
    ];
    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function cancelledByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }


    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canBeCancelled(): bool
    {
        $appointmentDateTime = $this->appointment_date->setTimeFromTimeString($this->appointment_time);
        return now()->diffInHours($appointmentDateTime, false) >= 24;
    }
}

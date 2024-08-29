<?php

namespace App\Models;

use App\Observers\EventObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

#[ObservedBy(EventObserver::class)]
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'grade_id',
        'event_id'
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the user's first name.
     */

    public function getQrCodeAttribute()
    {
        return QrCode::generate($this->name);
    }

    public function grade(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

}

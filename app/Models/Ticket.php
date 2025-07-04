<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'description',
        'priority',
        'status',
        'assigned_to',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'High');
    }

    public function scopeMediumPriority($query)
    {
        return $query->where('priority', 'Medium');
    }

    public function scopeLowPriority($query)
    {
        return $query->where('priority', 'Low');
    }

    public function isOpen()
    {
        return $this->status === 'open';
    }

    public function isAssigned()
    {
        return $this->status === 'assigned';
    }

    public function isClosed()
    {
        return $this->status === 'closed';
    }
    
}

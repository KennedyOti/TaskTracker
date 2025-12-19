<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'status', 'assigned_user_id', 'due_date', 'priority'];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * Scope to filter tasks by title (name)
     */
    public function scopeFilterByName($query, $name)
    {
        if ($name) {
            return $query->where('title', 'like', '%' . $name . '%');
        }
        return $query;
    }

    /**
     * Scope to filter tasks by status
     */
    public function scopeFilterByStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope to filter tasks by due date
     */
    public function scopeFilterByDueDate($query, $dueDate)
    {
        if ($dueDate) {
            return $query->whereDate('due_date', $dueDate);
        }
        return $query;
    }

    /**
     * Scope to filter tasks by priority
     */
    public function scopeFilterByPriority($query, $priority)
    {
        if ($priority) {
            return $query->where('priority', $priority);
        }
        return $query;
    }
}

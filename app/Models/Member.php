<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';
    protected $fillable = [
        'member_id_number',
        'full_name',
        'position',
        'contact',
        'is_active',
    ];

    /**
     * Get all of the loans for the Member
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get all of the visits for the Member
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Hitung total poin kehadiran (dimana durasi > 10 menit)
     * Cara pakai: $member->attendance_points
     */
    public function getAttendancePointsAttribute()
    {
        return $this->visits()->where('got_point', true)->count();
    }
}

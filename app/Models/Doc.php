<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Doc extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'blocks',
        'version',
        'time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'blocks' => 'json',
    ];

    /**
     * Get the user that owns the doc.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* Check user is owner */
    public function isOwner()
    {
        return $this->user_id == auth()->user()->id;
    }
}

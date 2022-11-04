<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    // *********************** START PARENT CLASS *****************************

    /**
     * Get the user associated with the report.
     */
    public function articles()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

}

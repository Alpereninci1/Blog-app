<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Tag extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    
    /**
     * Get the user associated with the report.
     */
    public function articles()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the user associated with the report.
     */
    public function categories()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

}

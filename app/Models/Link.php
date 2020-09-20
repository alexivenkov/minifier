<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'original',
        'minified',
        'transitions_count',
        'length'
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'minified';
    }
}

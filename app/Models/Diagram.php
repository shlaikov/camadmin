<?php

namespace App\Models;

use App\Enums\DiagramEnum;
use App\Enums\EnumTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagram extends Model
{
    use HasFactory, EnumTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'uuid',
        'team_id',
        'name',
        'type',
        'url',
        'preview'
    ];

    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:m:s',
        'created_at' => 'datetime:Y-m-d H:m:s',
    ];

    protected $enums = [
        'type' => DiagramEnum::class
    ];
}

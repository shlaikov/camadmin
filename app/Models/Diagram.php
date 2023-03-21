<?php

namespace App\Models;

use App\Data\DiagramData;
use App\Enums\DiagramEnum;
use App\Enums\EnumTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class Diagram extends Model
{
    use HasFactory, EnumTrait, WithData;

    protected string $dataClass = DiagramData::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'uuid',
        'team_id',
        'name',
        'type',
        'url',
        'preview'
    ];

    /**
     * @var array<string,string>
     */
    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:m:s',
        'created_at' => 'datetime:Y-m-d H:m:s',
    ];

    /**
     * @var array<string,string>
     */
    protected array $enums = [
        'type' => DiagramEnum::class
    ];
}

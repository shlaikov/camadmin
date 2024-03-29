<?php

namespace App\Models;

use App\Data\InstanceData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class Instance extends Model
{
    use HasFactory, WithData;

    protected string $dataClass = InstanceData::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
        'description',
        'type',
        'tenant_id',
        'url',
        'has_counter'
    ];
}

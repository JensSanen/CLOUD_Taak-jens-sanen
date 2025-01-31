<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


// Model voor de tabel 'workers'
class LaravelWorkers extends Model
{
    /** @use HasFactory<\Database\Factories\LaravelWorkersFactory> */
    use HasFactory;

    protected $table = 'workers';

    protected $primaryKey = 'workerId';

    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'surname',
        'age',
        'function'
    ];

}

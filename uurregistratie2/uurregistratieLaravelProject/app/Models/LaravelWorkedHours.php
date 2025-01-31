<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


// Model voor de tabel 'worked_hours'
class LaravelWorkedHours extends Model
{
    /** @use HasFactory<\Database\Factories\LaravelWorkedHoursFactory> */
    use HasFactory;

        protected $table = 'worked_hours';

        protected $primaryKey = 'whId';

        public $incrementing = true;

        public $timestamps = false;

        protected $fillable = [
            'projectId',
            'workerId',
            'hours',
            'comment',
            'date'
        ];
}

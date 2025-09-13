<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableDowntime extends Model
{
    use HasFactory;

    protected $table = 'table_downtimes';

    protected $fillable = [
        'table_production_id',
        'reporter',
        'group',
        'date',
        'fy_n',
        'shift',
        'line',
        'model',
        'model_year',
        'item_name',
        'coil_no',
        'bolster_1',
        'bolster_2',
        'bolster_3',
        'bolster_4',
        'time_from',
        'time_until',
        'total_time',
        'process_name',
        'dt_category',
        'downtime_type',
        'dt_classification',
        'problem_description',
        'root_cause',
        'counter_measure',
        'pic',
        'status',
        'problem_picture'
    ];

    // Relasi many-to-one dengan DataProduksi
    public function dataProduksi()
    {
        // return $this->belongsTo(InputProduction::class, 'input_production_id');
        return $this->belongsTo(TableProduction::class, 'table_production_id');
    }
}

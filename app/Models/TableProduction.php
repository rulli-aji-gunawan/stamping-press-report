<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableProduction extends Model
{
    use HasFactory;

    // protected $table = 'input_productions';

    protected $fillable = [
        'reporter',
        'group',
        'date',
        'fy_n',
        'shift',
        'line',
        'start_time',
        'finish_time',
        'total_prod_time',
        'model',
        'model_year',
        'spm',
        'item_name',
        'coil_no',
        'bolster_1',
        'bolster_2',
        'bolster_3',
        'bolster_4',
        'plan_a',
        'plan_b',
        'ok_a',
        'ok_b',
        'rework_a',
        'rework_b',
        'scrap_a',
        'scrap_b',
        'sample_a',
        'sample_b',
        'rework_exp',
        'scrap_exp',
        'trial_sample_exp'
    ];

    // Relasi one-to-many dengan DetailProblemProduksi
    // public function productionProblems()
    // {
    //     return $this->hasMany(ProductionProblem::class);
    // }
    public function tableDowntimes()
    {
        return $this->hasMany(TableDowntime::class);
    }

    public function tableDefects()
    {
        // return $this->hasMany(ProductionProblem::class, 'table_production_id');
        return $this->hasMany(TableDefect::class, 'table_production_id');
    }
}

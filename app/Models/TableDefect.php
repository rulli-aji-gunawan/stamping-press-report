<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableDefect extends Model
{
    use HasFactory;

    protected $table = 'table_defects';

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
        'defect_category',
        'defect_name',
        'defect_qty_a',
        'defect_qty_b',
        'defect_area'
    ];

    // public function modelItem()
    // {
    //     return $this->belongsTo(ModelItem::class, 'model_item_id');
    // }

    public function dataProduksi()
    {
        // return $this->belongsTo(InputProduction::class, 'input_production_id');
        return $this->belongsTo(TableProduction::class, 'table_production_id');
    }
}

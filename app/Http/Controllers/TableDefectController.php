<?php

namespace App\Http\Controllers;

use App\Models\ModelItem;
use App\Models\ProcessName;
use App\Models\TableDefect;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;
use App\Models\InputProduction;
use App\Models\TableProduction;
use App\Models\DowntimeCategory;
use App\Exports\TableDefectExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DowntimeClassification;
use App\Http\Requests\StoreTableDefectRequest;
use App\Http\Requests\UpdateTableDefectRequest;

class TableDefectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TableDefect::query();

        // Filter date range
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_until')) {
            $query->where('date', '<=', $request->date_until);
        }

        // Filter FY-N
        if ($request->filled('fy_n')) {
            $query->where('fy_n', $request->fy_n);
        }

        // Filter reporter
        if ($request->filled('reporter')) {
            $query->where('reporter', 'like', '%' . $request->reporter . '%');
        }

        // Filter Line
        if ($request->filled('line')) {
            $query->where('line', $request->line);
        }

        // Filter Model
        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        // Filter Item Name
        if ($request->filled('item_name')) {
            $query->where('item_name', 'like', '%' . $request->item_name . '%');
        }

        // Filter Defect Name
        if ($request->filled('defect_name')) {
            $query->where('defect_name', 'like', '%' . $request->defect_name . '%');
        }

        $table_defects = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();

        // Ambil data unik untuk select option
        $fyNs = TableDefect::select('fy_n')->distinct()->orderBy('fy_n')->pluck('fy_n');
        $reporters = TableDefect::select('reporter')->distinct()->orderBy('reporter')->pluck('reporter');
        $lines = TableDefect::select('line')->distinct()->orderBy('line')->pluck('line');
        $models = TableDefect::select('model')->distinct()->orderBy('model')->pluck('model');
        $itemNames = TableDefect::select('item_name')->distinct()->orderBy('item_name')->pluck('item_name');
        $defectNames = TableDefect::select('defect_name')->distinct()->orderBy('defect_name')->pluck('defect_name');

        // Hitung nomor awal untuk penomoran pada halaman saat ini
        $perPage = $table_defects->perPage();
        $currentPage = $table_defects->currentPage();
        $startNumber = (($currentPage - 1) * $perPage) + 1;

        return view('table-data.table-defect', compact(
            'table_defects',
            'startNumber',
            'fyNs',
            'reporters',
            'lines',
            'models',
            'itemNames',
            'defectNames'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi sesuai kebutuhan
        $request->validate([
            'reporter' => 'required',
            'group' => 'required',
            'date' => 'required|date',
            'shift' => 'required',
            'line' => 'required|string',
            'model' => 'required',
            'model_year' => 'required',
            'item_name' => 'required',
            'coil_no' => 'required',
            'defect_areas' => 'required|array',
            'defect_names' => 'required|array',
            'defect_qtys_a' => 'required|array',
            'defect_qtys_b' => 'nullable|array',
            'defect_categories' => 'required|array',
        ]);

        // Simpan setiap baris area mapping sebagai satu record TableDefect
        $count = count($request->defect_areas);
        for ($i = 0; $i < $count; $i++) {
            \App\Models\TableDefect::create([
                'reporter' => $request->reporter,
                'group' => $request->group,
                'date' => $request->date,
                'shift' => $request->shift,
                'line' => $request->line,
                'model' => $request->model,
                'model_year' => $request->model_year,
                'item_name' => $request->item_name,
                'coil_no' => $request->coil_no,
                'defect_area' => $request->defect_areas[$i],
                'defect_name' => $request->defect_names[$i],
                'defect_qty_a' => $request->defect_qtys_a[$i],
                'defect_qty_b' => $request->defect_qtys_b[$i],
                'defect_category' => $request->defect_categories[$i],
            ]);
        }

        return redirect()->route('table_defect')->with('success', 'Defect data saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TableDefect $tableDefect)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        try {
            $production = TableProduction::with('tableDowntimes', 'tableDefects')->findOrFail($id);
            $models = ModelItem::select('model_code')->distinct()->pluck('model_code');
            $years = ModelItem::where('model_code', $production->model)
                ->select('model_year')->distinct()->pluck('model_year');
            $items = ModelItem::where('model_code', $production->model)->get();

            // Debug
            // dd($production->item_name, $items->pluck('id'));

            $processNames = \App\Models\ProcessName::all();
            $dtCategories = \App\Models\DowntimeCategory::all();
            $dtClassifications = \App\Models\DowntimeClassification::all();

            return view('input-report.defect-edit', compact(
                'production',
                'models',
                'years',
                'items',
                'processNames',
                'dtCategories',
                'dtClassifications'
            ));
        } catch (\Exception $e) {
            return redirect()->route('table_defect')->with('error', 'Data tidak ditemukan');
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $production = TableProduction::findOrFail($id);

            // Log raw request data
            Log::info('Update request data defect', [
                'id' => $id,
                'data' => $request->all()
            ]);

            // Validasi input
            // $validatedData = $request->validate([
            //     'reporter' => 'required|string',
            //     'group' => 'required|string',
            //     'date' => 'required|date',
            //     'shift' => 'required|string',
            //     'start_time' => 'required|date_format:H:i',
            //     'finish_time' => 'required|date_format:H:i',
            //     'total_prod_time' => 'required|integer',
            //     'model' => 'required|string',
            //     'model_year' => 'nullable|string',
            //     'spm' => 'required|numeric',
            //     'item_name' => 'required|string',
            //     'coil_no' => 'required|string',
            //     'plan_a' => 'required|integer',
            //     'plan_b' => 'required|integer',
            //     'ok_a' => 'required|integer',
            //     'ok_b' => 'required|integer',
            //     'rework_a' => 'required|integer',
            //     'rework_b' => 'required|integer',
            //     'scrap_a' => 'required|integer',
            //     'scrap_b' => 'required|integer',
            //     'sample_a' => 'required|integer',
            //     'sample_b' => 'required|integer',
            //     'rework_exp' => 'nullable|string',
            //     'scrap_exp' => 'nullable|string',
            //     'trial_sample_exp' => 'nullable|string',

            //     // Validasi untuk production problems dinamis
            //     'production_problems' => 'nullable|array',
            //     'production_problems.*.time_from' => 'required|date_format:H:i',
            //     'production_problems.*.time_until' => 'required|date_format:H:i',
            //     'production_problems.*.total_time' => 'required|integer',
            //     'production_problems.*.process_name' => 'required|string',
            //     'production_problems.*.dt_category' => 'required|string',
            //     'production_problems.*.downtime_type' => 'nullable|string',
            //     'production_problems.*.dt_classification' => 'required|string',
            //     'production_problems.*.problem_description' => 'required|string',
            //     'production_problems.*.root_cause' => 'required|string',
            //     'production_problems.*.counter_measure' => 'required|string',
            //     'production_problems.*.pic' => 'required|string',
            //     'production_problems.*.status' => 'required|string',
            // ]);

            // Log::info('Update validation passed', ['validatedData' => $validatedData]);

            // $date = $validatedData['date'];
            // $carbonDate = \Carbon\Carbon::parse($date);
            // $year = $carbonDate->year;
            // $month = $carbonDate->month;

            // // Hitung tahun fiskal
            // if ($month >= 4) {
            //     $fyYear = $year;
            // } else {
            //     $fyYear = $year - 1;
            // }

            // // Hitung urutan bulan fiskal (April = 1, Maret = 12)
            // $fiscalMonth = $month >= 4 ? $month - 3 : $month + 9;

            // // Format: FY2025-1, FY2025-2, dst
            // $validatedData['fy_n'] = 'FY' . $fyYear . '-' . $fiscalMonth;

            // // Update production data
            // $production->update($validatedData);
            // Log::info('InputProduction updated', ['id' => $production->id]);

            // // Delete existing production problems
            // $production->tableDowntimes()->delete();
            // Log::info('Existing production problems deleted');

            // // Data yang akan dishare untuk production problems
            // $sharedData = [
            //     // 'input_production_id' => $production->id,
            //     'table_production_id' => $production->id,
            //     'reporter' => $production->reporter,
            //     'group' => $production->group,
            //     'date' => $production->date,
            //     'fy_n' => $production->fy_n,
            //     'shift' => $production->shift,
            //     'model' => $production->model,
            //     'model_year' => $production->model_year,
            //     'item_name' => $production->item_name,
            //     'coil_no' => $production->coil_no,
            // ];

            // // Ambil data production problems
            // $productionProblems = $request->input('production_problems', []);

            // if (!empty($productionProblems)) {
            //     foreach ($productionProblems as $index => $problem) {
            //         try {
            //             Log::info('Processing updated production problem', [
            //                 'index' => $index,
            //                 'problem' => $problem
            //             ]);

            //             $problemData = array_merge($sharedData, $problem);

            //             $createdProblem = $production->tableDowntimes()->create($problemData);

            //             Log::info('ProductionProblem created for update', [
            //                 'id' => $createdProblem->id,
            //                 'data' => $createdProblem->toArray()
            //             ]);
            //         } catch (\Exception $e) {
            //             Log::error('Error creating updated production problem', [
            //                 'index' => $index,
            //                 'error' => $e->getMessage(),
            //                 'problem_data' => $problemData
            //             ]);
            //             throw $e;
            //         }
            //     }
            // }

            $production->tableDefects()->delete();

            // Simpan defect baru
            $defectAreas = $request->input('defect_areas', []);
            $defectNames = $request->input('defect_names', []);
            $defectQtysA = $request->input('defect_qtys_a', []);
            $defectQtysB = $request->input('defect_qtys_b', []);
            $defectCategories = $request->input('defect_categories', []);
            log::info('Defect data received', [
                'defect_areas' => $defectAreas,
                'defect_names' => $defectNames,
                'defect_qtys_a' => $defectQtysA,
                'defect_qtys_b' => $defectQtysB,
                'defect_categories' => $defectCategories
            ]);

            for ($i = 0; $i < count($defectAreas); $i++) {
                $production->tableDefects()->create([
                    'reporter' => $production->reporter,
                    'group' => $production->group,
                    'date' => $production->date,
                    'fy_n' => $production->fy_n,
                    'shift' => $production->shift,
                    'line' => $production->line,
                    'model' => $production->model,
                    'model_year' => $production->model_year,
                    'item_name' => $production->item_name,
                    'coil_no' => $production->coil_no,
                    'bolster_1' => $production->bolster_1,
                    'bolster_2' => $production->bolster_2,
                    'bolster_3' => $production->bolster_3,
                    'bolster_4' => $production->bolster_4,
                    'defect_area' => $defectAreas[$i],
                    'defect_name' => $defectNames[$i],
                    'defect_qty_a' => $defectQtysA[$i],
                    'defect_qty_b' => $defectQtysB[$i] ?? null,
                    'defect_category' => $defectCategories[$i],
                ]);
            }

            DB::commit();
            Log::info('Update transaction committed successfully');

            return redirect()->route('table_defect')->with('success', 'Data berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in update method', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function export(Request $request)
    {
        try {
            $query = TableDefect::query();

            // Gunakan filter yang sama dengan method index
            if ($request->filled('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }
            if ($request->filled('date_until')) {
                $query->where('date', '<=', $request->date_until);
            }
            if ($request->filled('fy_n')) {
                $query->where('fy_n', $request->fy_n);
            }
            if ($request->filled('reporter')) {
                $query->where('reporter', 'like', '%' . $request->reporter . '%');
            }
            if ($request->filled('line')) {
                $query->where('line', $request->line);
            }
            if ($request->filled('model')) {
                $query->where('model', $request->model);
            }
            if ($request->filled('item_name')) {
                $query->where('item_name', 'like', '%' . $request->item_name . '%');
            }

            // Urutkan data berdasarkan tanggal
            $query->orderBy('date', 'desc');

            // Log info untuk debug
            Log::info('Exporting defect data to Excel', [
                'filters' => $request->all(),
                'count' => $query->count()
            ]);

            // Generate nama file dengan timestamp
            $fileName = 'defect_data_' . date('Y-m-d_His') . '.xlsx';

            // Export ke Excel
            return Excel::download(new TableDefectExport($query->get()), $fileName);
        } catch (\Exception $e) {
            Log::error('Error exporting defect data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Failed to export data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TableDefect $tableDefect)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ModelItem;
use Illuminate\Http\Request;
use App\Models\InputProduction;
use App\Models\TableProduction;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreTableProductionRequest;
use App\Http\Requests\UpdateTableProductionRequest;
use App\Models\ProcessName;
use Doctrine\DBAL\Schema\Table;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TableProductionExport;

class TableProductionController extends Controller
{

    public function index(Request $request)
    {
        $query = TableProduction::query();

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

        $table_productions = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();

        // Ambil data unik untuk select option
        $fyNs = TableProduction::select('fy_n')->distinct()->orderBy('fy_n')->pluck('fy_n');
        $reporters = TableProduction::select('reporter')->distinct()->orderBy('reporter')->pluck('reporter');
        $lines = TableProduction::select('line')->distinct()->orderBy('line')->pluck('line');
        $models = TableProduction::select('model')->distinct()->orderBy('model')->pluck('model');
        $itemNames = TableProduction::select('item_name')->distinct()->orderBy('item_name')->pluck('item_name');

        // Hitung nomor awal untuk penomoran pada halaman saat ini
        $perPage = $table_productions->perPage();
        $currentPage = $table_productions->currentPage();
        $startNumber = (($currentPage - 1) * $perPage) + 1;


        return view('table-data.table-production', compact(
            'table_productions',
            'startNumber',
            'fyNs',
            'reporters',
            'lines',
            'models',
            'itemNames'
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
    public function store(StoreTableProductionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TableProduction $tableProduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $production = TableProduction::with('tableDowntimes')->findOrFail($id);
            $models = ModelItem::select('model_code')->distinct()->pluck('model_code');
            $years = ModelItem::where('model_code', $production->model)
                ->select('model_year')->distinct()->pluck('model_year');
            $items = ModelItem::where('model_code', $production->model)->get();
            $selectedItem = $items->firstWhere('item_name', $production->item_name);
            $pictures = $selectedItem ? $selectedItem->product_picture : '';

            $processNames = \App\Models\ProcessName::all();
            $dtCategories = \App\Models\DowntimeCategory::all();
            $dtClassifications = \App\Models\DowntimeClassification::all();

            return view('input-report.production-edit', compact(
                'production',
                'models',
                'years',
                'items',
                'pictures',
                'selectedItem',
                'processNames',
                'dtCategories',
                'dtClassifications'
            ));
        } catch (\Exception $e) {
            return redirect()->route('table_production')->with('error', 'Data tidak ditemukan');
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
            Log::info('Update request data', [
                'id' => $id,
                'data' => $request->all()
            ]);

            // Validasi input
            $validatedData = $request->validate([
                'reporter' => 'required|string',
                'group' => 'required|string',
                'date' => 'required|date',
                'shift' => 'required|string',
                'line' => 'required|string',
                'start_time' => 'required|date_format:H:i',
                'finish_time' => 'required|date_format:H:i',
                'total_prod_time' => 'required|integer',
                'model' => 'required|string',
                'model_year' => 'nullable|string',
                'spm' => 'required|numeric',
                'item_name' => 'required|string',
                'coil_no' => 'required|string',

                // Validasi untuk bolster data
                'bolster_1' => 'nullable|string|in:LH,RH',
                'bolster_2' => 'nullable|string|in:LH,RH',
                'bolster_3' => 'nullable|string|in:LH,RH',
                'bolster_4' => 'nullable|string|in:LH,RH',

                'plan_a' => 'required|integer',
                'plan_b' => 'required|integer',
                'ok_a' => 'required|integer',
                'ok_b' => 'required|integer',
                'rework_a' => 'required|integer',
                'rework_b' => 'required|integer',
                'scrap_a' => 'required|integer',
                'scrap_b' => 'required|integer',
                'sample_a' => 'required|integer',
                'sample_b' => 'required|integer',
                'rework_exp' => 'nullable|string',
                'scrap_exp' => 'nullable|string',
                'trial_sample_exp' => 'nullable|string',

                // Validasi untuk production problems dinamis
                'production_problems' => 'nullable|array',
                'production_problems.*.time_from' => 'required|date_format:H:i',
                'production_problems.*.time_until' => 'required|date_format:H:i',
                'production_problems.*.total_time' => 'required|integer',
                'production_problems.*.process_name' => 'required|string',
                'production_problems.*.dt_category' => 'required|string',
                'production_problems.*.downtime_type' => 'nullable|string',
                'production_problems.*.dt_classification' => 'required|string',
                'production_problems.*.problem_description' => 'required|string',
                'production_problems.*.root_cause' => 'required|string',
                'production_problems.*.counter_measure' => 'required|string',
                'production_problems.*.pic' => 'required|string',
                'production_problems.*.status' => 'required|string',
                'production_problems.*.problem_picture' => 'nullable|string',
                'production_problems.*.delete_picture' => 'nullable|boolean',
            ]);

            Log::info('Update validation passed', ['validatedData' => $validatedData]);


            $date = $validatedData['date'];
            $carbonDate = \Carbon\Carbon::parse($date);
            $year = $carbonDate->year;
            $month = $carbonDate->month;

            // Hitung tahun fiskal
            if ($month >= 4) {
                $fyYear = $year;
            } else {
                $fyYear = $year - 1;
            }

            // Hitung urutan bulan fiskal (April = 1, Maret = 12)
            $fiscalMonth = $month >= 4 ? $month - 3 : $month + 9;

            // Format: FY2025-1, FY2025-2, dst
            $validatedData['fy_n'] = 'FY' . $fyYear . '-' . $fiscalMonth;

            // Update production data
            $production->update($validatedData);
            Log::info('InputProduction updated', ['id' => $production->id]);

            // Delete existing production problems
            $production->tableDowntimes()->delete();
            Log::info('Existing production problems deleted');

            // Data yang akan dishare untuk production problems
            $sharedData = [
                'table_production_id' => $production->id,
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
            ];

            // Ambil data production problems
            $productionProblems = $request->input('production_problems', []);

            if (!empty($productionProblems)) {
                foreach ($productionProblems as $index => $problem) {
                    try {
                        Log::info('Processing production problem', [
                            'index' => $index,
                            'problem' => $problem
                        ]);

                        $problemData = array_merge($sharedData, $problem);

                        // Cek apakah ada data gambar base64
                        if (isset($problem['problem_picture_data']) && !empty($problem['problem_picture_data'])) {
                            // Ekstrak data gambar dari string base64
                            $base64Image = $problem['problem_picture_data'];
                            list($type, $data) = explode(';', $base64Image);
                            list(, $data) = explode(',', $data);
                            $imageData = base64_decode($data);

                            // Dapatkan ekstensi file
                            $extension = 'jpg'; // Default
                            if (isset($problem['problem_picture_name'])) {
                                $originalName = $problem['problem_picture_name'];
                                $extension = pathinfo($originalName, PATHINFO_EXTENSION) ?: 'jpg';
                            }

                            // Buat nama file unik
                            $filename = 'problem_picture_' . str_pad($production->id, 7, '0', STR_PAD_LEFT) . '_' . ($index + 1) . '.' . $extension;

                            // Simpan file
                            $path = public_path('images/problems');
                            if (!file_exists($path)) {
                                mkdir($path, 0777, true);
                            }
                            file_put_contents($path . '/' . $filename, $imageData);

                            // Simpan nama file ke database
                            $problemData['problem_picture'] = 'images/problems/' . $filename;

                            // Hapus data base64 dan nama file asli dari data yang akan disimpan ke database
                            unset($problemData['problem_picture_data']);
                            unset($problemData['problem_picture_name']);
                        }
                        // Cek apakah menggunakan metode tradisional file upload
                        else if ($request->hasFile('problem_pictures') && isset($request->file('problem_pictures')[$index])) {
                            $file = $request->file('problem_pictures')[$index];
                            if ($file) {
                                $filename = 'problem_picture_' . str_pad($production->id, 7, '0', STR_PAD_LEFT) . '_' . ($index + 1) . '.' . $file->getClientOriginalExtension();
                                $file->move(public_path('images/problems'), $filename);

                                // Ubah ini:
                                $problemData['problem_picture'] = $filename;

                                // Menjadi:
                                $problemData['problem_picture'] = 'images/problems/' . $filename;
                            }
                        }

                        if (isset($problem['delete_picture']) && $problem['delete_picture'] == 1) {
                            // Hapus file fisik jika ada
                            $oldPicturePath = public_path($problem['problem_picture'] ?? '');
                            if (file_exists($oldPicturePath) && is_file($oldPicturePath)) {
                                unlink($oldPicturePath);
                            }
                            // Set problem_picture ke null
                            $problemData['problem_picture'] = null;
                        }

                        // Simpan ke table_downtimes
                        $createdProblem = $production->tableDowntimes()->create($problemData);

                        Log::info('ProductionProblem created', [
                            'id' => $createdProblem->id,
                            'data' => $createdProblem->toArray()
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error creating production problem', [
                            'index' => $index,
                            'error' => $e->getMessage()
                        ]);
                        throw $e;
                    }
                }
            }

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

            return redirect()->route('table_production')->with('success', 'Data berhasil diupdate');
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
            $query = TableProduction::query();

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
            Log::info('Exporting production data to Excel', [
                'filters' => $request->all(),
                'count' => $query->count()
            ]);

            // Generate nama file dengan timestamp
            $fileName = 'production_data_' . date('Y-m-d_His') . '.xlsx';

            // Export ke Excel
            return Excel::download(new TableProductionExport($query->get()), $fileName);
        } catch (\Exception $e) {
            Log::error('Error exporting production data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Failed to export data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $production = TableProduction::findOrFail($id);
        $production->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data produksi berhasil dihapus.'
        ]);
    }
}

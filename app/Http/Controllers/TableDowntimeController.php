<?php

namespace App\Http\Controllers;

use App\Models\ModelItem;
use App\Models\ProcessName;
use Illuminate\Http\Request;
use App\Models\TableDowntime;
use App\Models\InputProduction;
use App\Models\TableProduction;
use App\Models\DowntimeCategory;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\DowntimeClassification;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreTableDowntimeRequest;
use App\Http\Requests\UpdateTableDowntimeRequest;
use Doctrine\DBAL\Schema\Table;

class TableDowntimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TableDowntime::query();

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

        $table_downtimes = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();

        // Ambil data unik untuk select option
        $fyNs = TableDowntime::select('fy_n')->distinct()->orderBy('fy_n')->pluck('fy_n');
        $reporters = TableDowntime::select('reporter')->distinct()->orderBy('reporter')->pluck('reporter');
        $lines = TableDowntime::select('line')->distinct()->orderBy('line')->pluck('line');
        $models = TableDowntime::select('model')->distinct()->orderBy('model')->pluck('model');
        $itemNames = TableDowntime::select('item_name')->distinct()->orderBy('item_name')->pluck('item_name');

        // Hitung nomor awal untuk penomoran pada halaman saat ini
        $perPage = $table_downtimes->perPage();
        $currentPage = $table_downtimes->currentPage();
        $startNumber = (($currentPage - 1) * $perPage) + 1;
        $table_productions = TableProduction::query();


        return view('table-data.table-downtime', compact(
            'table_downtimes',
            'startNumber',
            'table_productions',
            'fyNs',
            'reporters',
            'lines',
            'models',
            'itemNames',
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
        $validator = Validator::make($request->all(), [
            'production_problems' => 'required|array',
            'production_problems.*.time_from' => 'required',
            'production_problems.*.time_until' => 'required',
            'production_problems.*.total_time' => 'required',
            'production_problems.*.process_name' => 'required',
            'production_problems.*.dt_category' => 'required',
            'production_problems.*.downtime_type' => 'required',
            'production_problems.*.dt_classification' => 'required',
            'production_problems.*.problem_description' => 'required',
            'production_problems.*.root_cause' => 'required',
            'production_problems.*.counter_measure' => 'required',
            'production_problems.*.pic' => 'nullable',
            'production_problems.*.status' => 'nullable',
            'production_problems.*.problem_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        Log::info('Received production data:', $request->all());

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            Log::info('Starting to save problems');
            foreach ($request->production_problems as $problemData) {
                $processName = ProcessName::find($problemData['process_name'])->process_name;
                $dtCategory = DowntimeCategory::find($problemData['dt_category'])->dt_category;
                Log::info('Saving problem:', $problemData);
                TableDowntime::create([
                    'time_from' => $problemData['time_from'],
                    'time_until' => $problemData['time_until'],
                    'total_time' => $problemData['total_time'],
                    'process_name' => $processName,
                    'dt_category' => $dtCategory,
                    'downtime_type' => $problemData['downtime_type'],
                    'dt_classification' => $problemData['dt_classification'],
                    'problem_description' => $problemData['problem_description'],
                    'root_cause' => $problemData['root_cause'],
                    'counter_measure' => $problemData['counter_measure'],
                    'pic' => $problemData['pic'],
                    'status' => $problemData['status'],
                    'problem_picture' => $problemData['problem_picture']
                ]);
            }

            return response()->json(['message' => 'Data berhasil disimpan'], 201);
        } catch (\Exception $e) {
            Log::error('Error saving problems:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TableDowntime $tableDowntime)
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

            // Debug
            // dd($production->item_name, $items->pluck('id'));

            $processNames = \App\Models\ProcessName::all();
            $dtCategories = \App\Models\DowntimeCategory::all();
            $dtClassifications = \App\Models\DowntimeClassification::all();

            return view('input-report.downtime-edit', compact(
                'production',
                'models',
                'years',
                'items',
                'processNames',
                'dtCategories',
                'dtClassifications'
            ));
        } catch (\Exception $e) {
            return redirect()->route('table_downtime')->with('error', 'Data tidak ditemukan');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
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
                'coil_no' => 'nullable|string',

                // Validasi untuk material ticket data
                'which-side-material' => 'nullable|array',
                'material_ticket_no_text' => 'nullable|array',
                'material_ticket_no_r' => 'nullable|array',
                'material_ticket_no_s' => 'nullable|array',
                'material_ticket_no_p' => 'nullable|array',

                // Validasi untuk production problems
                'production_problems' => 'nullable|array',
                'production_problems.*.id' => 'nullable|integer',
                'production_problems.*.delete_picture' => 'nullable|boolean',
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
                'production_problems.*.problem_picture_data' => 'nullable|string',
                'production_problems.*.problem_picture_name' => 'nullable|string',
            ]);

            // Generate coil_no dari material ticket data
            $coilNumbers = [];
            $whichSides = $request->input('which-side-material', []);
            $ticketTexts = $request->input('material_ticket_no_text', []);
            $ticketRs = $request->input('material_ticket_no_r', []);
            $ticketSs = $request->input('material_ticket_no_s', []);
            $ticketPs = $request->input('material_ticket_no_p', []);

            // Pastikan array memiliki minimal 1 elemen dan tidak kosong
            if (!empty($whichSides) && !empty($ticketTexts)) {
                for ($i = 0; $i < count($whichSides); $i++) {
                    $whichSide = isset($whichSides[$i]) ? $whichSides[$i] : '';
                    $ticketText = isset($ticketTexts[$i]) ? $ticketTexts[$i] : '';
                    $ticketR = isset($ticketRs[$i]) ? $ticketRs[$i] : '';
                    $ticketS = isset($ticketSs[$i]) ? $ticketSs[$i] : '';
                    $ticketP = isset($ticketPs[$i]) ? $ticketPs[$i] : '';

                    // Hanya tambahkan jika ada data minimal which-side dan ticket text
                    if (!empty($whichSide) && !empty($ticketText)) {
                        // Format: "which-side : material_ticket_no_text-material_ticket_no_r-material_ticket_no_s-material_ticket_no_p"
                        $coilPart = $whichSide . ' : ' . $ticketText;

                        // Tambahkan R, S, P jika ada
                        if (!empty($ticketR)) {
                            $coilPart .= '-' . $ticketR;
                        }
                        if (!empty($ticketS)) {
                            $coilPart .= '-' . $ticketS;
                        }
                        if (!empty($ticketP)) {
                            $coilPart .= '-' . $ticketP;
                        }

                        $coilNumbers[] = $coilPart;
                    }
                }
            }

            // Gabungkan dengan semicolon dan spasi
            $validatedData['coil_no'] = implode(' ; ', $coilNumbers);

            Log::info('Generated coil_no for downtime update', ['coil_no' => $validatedData['coil_no']]);

            $production = TableProduction::findOrFail($id);

            // Update data produksi
            $production->update($validatedData);

            // Ambil data production problems
            $productionProblems = $request->input('production_problems', []);

            foreach ($productionProblems as $index => $problem) {
                // Jika ada ID, berarti ini update record yang sudah ada
                if (isset($problem['id'])) {
                    $downtimeId = $problem['id'];
                    $downtime = \App\Models\TableDowntime::find($downtimeId);

                    if ($downtime) {
                        // Update logic for downtime problems...
                        $downtime->update($problem);
                    }
                }
                // Jika tidak ada ID, berarti ini data baru
                else {
                    // Create new downtime record...
                    $production->tableDowntimes()->create($problem);
                }
            }

            DB::commit();
            return redirect()->route('downtime.edit', $id)->with('success', 'Data downtime berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in downtime update method', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('downtime.edit', $id)->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TableDowntime $tableDowntime)
    {
        //
    }
}

<x-app-layout>Table Data Downtime</x-app-layout>

<x-master-data-layout></x-master-data-layout>


<section class="home">
    <div class="toggle-sidebar">
        <i class='bx bx-x-circle' id="hide-toggle"></i>
        <i class='bx bx-menu' id="show-toggle"></i>
    </div>
    <div class="container">

        <!-- Links pagination -->
        {{ $table_downtimes->links('vendor.pagination.custom-tailwind') }}

        <form class="filter-table" method="GET" action="{{ route('table_downtime') }}" class="mb-4 flex flex-wrap gap-2">
            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From Date">
            <input type="date" name="date_until" value="{{ request('date_until') }}" placeholder="Until Date">

            <select name="fy_n">
                <option value="">FY-N --</option>
                @foreach ($fyNs as $fy)
                    <option value="{{ $fy }}" {{ request('fy_n') == $fy ? 'selected' : '' }}>
                        {{ $fy }}</option>
                @endforeach
            </select>

            <select name="reporter">
                <option value="">Reporter --</option>
                @foreach ($reporters as $reporter)
                    <option value="{{ $reporter }}" {{ request('reporter') == $reporter ? 'selected' : '' }}>
                        {{ $reporter }}</option>
                @endforeach
            </select>

            <select name="line">
                <option value="">Line --</option>
                @foreach ($lines as $line)
                    <option value="{{ $line }}" {{ request('line') == $line ? 'selected' : '' }}>
                        {{ $line }}</option>
                @endforeach
            </select>

            <select name="model">
                <option value="">Model --</option>
                @foreach ($models as $model)
                    <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                        {{ $model }}</option>
                @endforeach
            </select>

            <select name="item_name">
                <option value="">Item Name --</option>
                @foreach ($itemNames as $item)
                    <option value="{{ $item }}" {{ request('item_name') == $item ? 'selected' : '' }}>
                        {{ $item }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
            <a id="reset-filters" href="{{ route('table_downtime') }}" class="btn btn-secondary">Reset Filter</a>

            <a href="{{ route('table_downtime.export') }}?{{ http_build_query(request()->all()) }}"
                class="btn btn-success">
                <i class='bx bx-download'></i> Export to Excel
            </a>
        </form>

        <div class="table-scroll-indicator">
            <p>← Geser tabel ke kanan/kiri untuk melihat seluruh data →</p>
        </div>

        <div class="table-container">

            {{-- Table of Data Production --}}
            <table class="tbl-data-downtime">
                <thead>
                    <tr>
                        {{-- <th id="tbl-data-downtime">
                            <p>Action</p>
                        </th> --}}
                        <th id="tbl-data-downtime">
                            <p>No</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Date</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>FY-N</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Shift</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Line</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Group</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Reporter</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Model Year</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Model</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Item Name</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Time From</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Time Until</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Total Downtime</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Process</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>DT Category</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>DT Type</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>DT Classification</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Problem Description</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Root Cause</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Counter Measure</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>PIC</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Status</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Problem Picture</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Bolster 1</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Bolster 2</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Bolster 3</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Bolster 4</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Coil Number</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Created at</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Update at</p>
                        </th>
                        <th id="tbl-data-downtime">
                            <p>Action</p>
                        </th>

                    </tr>
                </thead>



                <tbody>
                    @forelse  ($table_downtimes as $index => $table_downtime)
                        <tr>
                            <td class="tbl-data-downtime" id="number">
                                <a href="{{ route('table_downtime.edit', $table_downtime->table_production_id) }}"
                                    id="linked-number">
                                    {{ $startNumber + $index }}
                                </a>
                            </td>
                            <td class="tbl-data-downtime" id="date">
                                <p>{{ \Carbon\Carbon::parse($table_downtime->date)->format('d-M-Y') }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="fy-n">
                                <p>{{ $table_downtime->fy_n }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="shift">
                                <p>{{ $table_downtime->shift }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="line">
                                <p>{{ $table_downtime->line }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="group">
                                <p>{{ $table_downtime->group }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="reporter">
                                <p>{{ $table_downtime->reporter }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="model-year">
                                <p>{{ $table_downtime->model_year }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="model">
                                <p>{{ $table_downtime->model }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="item-name">
                                <p>{{ $table_downtime->item_name }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="time-from">
                                <p>{{ $table_downtime->time_from }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="time-until">
                                <p>{{ $table_downtime->time_until }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="total-downtime">
                                <p>{{ $table_downtime->total_time }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="process-name">
                                <p>{{ $table_downtime->process_name }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="dt-category">
                                <p>{{ $table_downtime->dt_category }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="downtime-type">
                                <p>{{ $table_downtime->downtime_type }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="dt-classification">
                                <p>{{ $table_downtime->dt_classification }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="detail-problem">
                                <p>{{ $table_downtime->problem_description }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="detail-problem">
                                <p>{{ $table_downtime->root_cause }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="detail-problem">
                                <p>{{ $table_downtime->counter_measure }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="pic">
                                <p>{{ $table_downtime->pic }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="status">
                                <p>{{ $table_downtime->status }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="problem-picture">
                                @if ($table_downtime->problem_picture)
                                    <a href="#" class="problem-img-preview"
                                        data-img="{{ asset($table_downtime->problem_picture) }}">
                                        preview-img
                                    </a>
                                @else
                                    <span>No image</span>
                                @endif
                            </td>
                            <td class="tbl-data-downtime" id="bolster">
                                <p>{{ $table_downtime->bolster_1 }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="bolster">
                                <p>{{ $table_downtime->bolster_2 }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="bolster">
                                <p>{{ $table_downtime->bolster_3 }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="bolster">
                                <p>{{ $table_downtime->bolster_4 }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="coil-no">
                                <p>{{ $table_downtime->coil_no }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="data-time">
                                <p>{{ \Carbon\Carbon::parse($table_downtime->created_at)->format('Y-m-d H:i') }}</p>
                            </td>
                            <td class="tbl-data-downtime" id="data-time">
                                <p>{{ \Carbon\Carbon::parse($table_downtime->updated_at)->format('Y-m-d H:i') }}</p>
                            </td>
                            <td id="action-for-table-downtime">
                                {{-- Action buttons for each row --}}

                                <a href="{{ route('table_downtime.edit', $table_downtime->table_production_id) }}"
                                    class="table-downtime-btn" id="btn-edit">
                                    <i class="bx bx-edit" style="color: blue; font-size: 1rem;"></i>
                                    <i class="bx bx-trash" style="color: red; font-size: 1rem;"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="30" class="text-center">Tidak ada data production</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>

    <div id="imgModal"
        style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.6); align-items:center; justify-content:center;">
        <span id="closeImgModal"
            style="position:absolute; top:20px; right:30px; font-size:30px; color:white; cursor:pointer;">&times;</span>
        <img id="imgModalContent" src="" style="max-width:90%; max-height:90%; object-fit:contain;">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event handler untuk link preview
            document.querySelectorAll('.problem-img-preview').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const imgSrc = this.getAttribute('data-img');
                    document.getElementById('imgModalContent').src = imgSrc;
                    document.getElementById('imgModal').style.display = 'flex';
                });
            });

            // Event handler untuk tombol close
            document.getElementById('closeImgModal').addEventListener('click', function() {
                document.getElementById('imgModal').style.display = 'none';
            });

            // Tutup modal jika user klik diluar gambar
            document.getElementById('imgModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });
        });
    </script>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            alert("{{ session('success') }}");
        @endif

        @if (session('error'))
            alert("{{ session('error') }}");
        @endif
    });
</script>

<script src="../js/sidebar.js"></script>
<script src="../js/delete-table-downtime.js"></script>

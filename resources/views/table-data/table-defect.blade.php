<x-app-layout>Table Data Defect</x-app-layout>

<x-master-data-layout></x-master-data-layout>


<section class="home">
    <div class="toggle-sidebar">
        <i class='bx bx-x-circle' id="hide-toggle"></i>
        <i class='bx bx-menu' id="show-toggle"></i>
    </div>
    <div class="container">

        <!-- Links pagination -->
        {{ $table_defects->links('vendor.pagination.custom-tailwind') }}

        <form class="filter-table" method="GET" action="{{ route('table_defect') }}" class="mb-4 flex flex-wrap gap-2">
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

            <select name="defect_name">
                <option value="">Defect Name --</option>
                @foreach ($defectNames as $defect)
                    <option value="{{ $defect }}" {{ request('defect_name') == $defect ? 'selected' : '' }}>
                        {{ $defect }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
            <a id="reset-filters" href="{{ route('table_defect') }}" class="btn btn-secondary">Reset</a>

            <a href="{{ route('table_defect.export') }}?{{ http_build_query(request()->all()) }}"
                class="btn btn-success">
                <i class='bx bx-download'></i> Export to Excel
            </a>
        </form>

        <div class="table-scroll-indicator">
            <p>← Geser tabel ke kanan/kiri untuk melihat seluruh data →</p>
        </div>

        <div class="table-container">

            {{-- Table of Data Production --}}
            <table class="tbl-data-defect">
                <thead>
                    <tr>
                        {{-- <th id="tbl-data-defect">
                            <p>Action</p>
                        </th> --}}
                        <th id="tbl-data-defect">
                            <p>No</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Date</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>FY-N</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Shift</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Line</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Group</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Reporter</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Model Year</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Model</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Item Name</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Problem Category</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Defect/Problem</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Qty</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Qty2</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Area</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Bolster 1</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Bolster 2</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Bolster 3</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Bolster 4</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Coil Number</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Created at</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Update at</p>
                        </th>
                        <th id="tbl-data-defect">
                            <p>Action</p>
                        </th>

                    </tr>
                </thead>

                <tbody>
                    @forelse  ($table_defects as $index => $table_defect)
                        <tr>
                            <td class="tbl-data-defect" id="number">
                                {{-- Display the index + 1 to show the correct number --}}
                                <a href="{{ route('table_defect.edit', $table_defect->table_production_id) }}"
                                    id="linked-number">
                                    {{ $startNumber + $index }}
                                </a>
                            </td>
                            <td class="tbl-data-defect" id="date">
                                <p>{{ \Carbon\Carbon::parse($table_defect->date)->format('d-M-Y') }}</p>
                            </td>
                            <td class="tbl-data-defect" id="fy-n">
                                <p>{{ $table_defect->fy_n }}</p>
                            </td>
                            <td class="tbl-data-defect" id="shift">
                                <p>{{ $table_defect->shift }}</p>
                            </td>
                            <td class="tbl-data-defect" id="line">
                                <p>{{ $table_defect->line }}</p>
                            </td>
                            <td class="tbl-data-defect" id="group">
                                <p>{{ $table_defect->group }}</p>
                            </td>
                            <td class="tbl-data-defect" id="reporter">
                                <p>{{ $table_defect->reporter }}</p>
                            </td>
                            <td class="tbl-data-defect" id="model-year">
                                <p>{{ $table_defect->model_year }}</p>
                            </td>
                            <td class="tbl-data-defect" id="model">
                                <p>{{ $table_defect->model }}</p>
                            </td>
                            <td class="tbl-data-defect" id="item-name">
                                <p>{{ $table_defect->item_name }}</p>
                            </td>
                            <td class="tbl-data-defect" id="defect-category">
                                <p>{{ $table_defect->defect_category }}</p>
                            </td>
                            <td class="tbl-data-defect" id="defect-name">
                                <p>{{ $table_defect->defect_name }}</p>
                            </td>
                            <td class="tbl-data-defect" id="defect-qty-a">
                                <p>{{ $table_defect->defect_qty_a }}</p>
                            </td>
                            <td class="tbl-data-defect" id="defect-qty-b">
                                <p>{{ $table_defect->defect_qty_b }}</p>
                            </td>
                            <td class="tbl-data-defect" id="defect-area">
                                <p>{{ $table_defect->defect_area }}</p>
                            </td>
                            <td class="tbl-data-defect" id="bolster">
                                <p>{{ $table_defect->bolster_1 }}</p>
                            </td>
                            <td class="tbl-data-defect" id="bolster">
                                <p>{{ $table_defect->bolster_2 }}</p>
                            </td>
                            <td class="tbl-data-defect" id="bolster">
                                <p>{{ $table_defect->bolster_3 }}</p>
                            </td>
                            <td class="tbl-data-defect" id="bolster">
                                <p>{{ $table_defect->bolster_4 }}</p>
                            </td>
                            <td class="tbl-data-defect" id="coil-no">
                                <p>{{ $table_defect->coil_no }}</p>
                            </td>
                            <td class="tbl-data-defect" id="data-time">
                                <p>{{ \Carbon\Carbon::parse($table_defect->created_at)->format('Y-m-d H:i') }}</p>
                            </td>
                            <td class="tbl-data-defect" id="data-time">
                                <p>{{ \Carbon\Carbon::parse($table_defect->updated_at)->format('Y-m-d H:i') }}</p>
                            </td>
                            <td id="action-for-table-defect">
                                {{-- Action buttons for each row --}}

                                <a href="{{ route('table_defect.edit', $table_defect->table_production_id) }}"
                                    class="table-defect-btn" id="btn-edit">
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
<script src="../js/delete-table-defect.js"></script>

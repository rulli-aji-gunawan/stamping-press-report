@php use Carbon\Carbon; @endphp
<x-app-layout>Table Data Production</x-app-layout>

<x-master-data-layout></x-master-data-layout>


<section class="home">
    <div class="toggle-sidebar">
        <i class='bx bx-x-circle' id="hide-toggle"></i>
        <i class='bx bx-menu' id="show-toggle"></i>
    </div>
    <div class="container">

        <!-- Links pagination -->
        {{ $table_productions->links('vendor.pagination.custom-tailwind') }}

        <form class="filter-table" method="GET" action="{{ route('table_production') }}"
            class="mb-4 flex flex-wrap gap-2">
            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From Date">
            <input type="date" name="date_until" value="{{ request('date_until') }}" placeholder="Until Date">

            <select name="fy_n">
                <option value="">-- FY-N --</option>
                @foreach ($fyNs as $fy)
                    <option value="{{ $fy }}" {{ request('fy_n') == $fy ? 'selected' : '' }}>
                        {{ $fy }}</option>
                @endforeach
            </select>

            <select name="reporter">
                <option value="">-- Reporter --</option>
                @foreach ($reporters as $reporter)
                    <option value="{{ $reporter }}" {{ request('reporter') == $reporter ? 'selected' : '' }}>
                        {{ $reporter }}</option>
                @endforeach
            </select>

            <select name="line">
                <option value="">-- Line --</option>
                @foreach ($lines as $line)
                    <option value="{{ $line }}" {{ request('line') == $line ? 'selected' : '' }}>
                        {{ $line }}</option>
                @endforeach
            </select>

            <select name="model">
                <option value="">-- Model --</option>
                @foreach ($models as $model)
                    <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                        {{ $model }}</option>
                @endforeach
            </select>

            <select name="item_name">
                <option value="">-- Item Name --</option>
                @foreach ($itemNames as $item)
                    <option value="{{ $item }}" {{ request('item_name') == $item ? 'selected' : '' }}>
                        {{ $item }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
            <a id="reset-filters" href="{{ route('table_production') }}" class="btn btn-secondary">Reset Filter</a>

            <a href="{{ route('table_production.export') }}?{{ http_build_query(request()->all()) }}"
                class="btn btn-success">
                <i class='bx bx-download'></i> Export to Excel
            </a>
        </form>

        <div class="table-scroll-indicator">
            <p>← Geser tabel ke kanan/kiri untuk melihat seluruh data →</p>
        </div>

        <div class="table-container">

            {{-- Table of Data Production --}}
            <table class="tbl-data-production">
                <thead>
                    <tr>
                        {{-- <th id="tbl-data-production">
                            <p>Action</p>
                        </th> --}}
                        <th id="tbl-data-production">
                            <p>No</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Date</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>FY-N</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Shift</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Line</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Group</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Reporter</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Model Year</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Model</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Item Name</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Start Time</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Finish Time</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Total Time</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>SPM</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Plan-A</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Plan-B</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>OK-A</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>OK-B</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Rework-A</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Rework-B</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Scrap-A</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Scrap-B</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Sample-A</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Sample-B</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Rework Explanation</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Scrap Explanation</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Trial Sample Explanation</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Bolster 1</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Bolster 2</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Bolster 3</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Bolster 4</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Coil Number</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Created at</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Update at</p>
                        </th>
                        <th id="tbl-data-production">
                            <p>Action</p>
                        </th>

                    </tr>
                </thead>



                <tbody>
                    @forelse  ($table_productions as $index => $table_production)
                        <tr>
                            <td class="table-data-production" id="number">
                                <a href="{{ route('table_production.edit', $table_production->id) }}"
                                    id="linked-number">
                                    {{ $startNumber + $index }}
                                </a>
                            </td>
                            <td class="table-data-production" id="date">
                                <p>{{ \Carbon\Carbon::parse($table_production->date)->format('d-M-Y') }}</p>
                            </td>
                            <td class="table-data-production" id="fy-n">
                                <p>{{ $table_production->fy_n }}</p>
                            </td>
                            <td class="table-data-production" id="shift">
                                <p>{{ $table_production->shift }}</p>
                            </td>
                            <td class="table-data-production" id="line">
                                <p>{{ $table_production->line }}</p>
                            </td>
                            <td class="table-data-production" id="group">
                                <p>{{ $table_production->group }}</p>
                            </td>
                            <td class="table-data-production" id="reporter">
                                <p>{{ $table_production->reporter }}</p>
                            </td>
                            <td class="table-data-production" id="model-year">
                                <p>{{ $table_production->model_year }}</p>
                            </td>
                            <td class="table-data-production" id="model">
                                <p>{{ $table_production->model }}</p>
                            </td>
                            <td class="table-data-production" id="item-name">
                                <p>{{ $table_production->item_name }}</p>
                            </td>
                            <td class="table-data-production" id="start-time">
                                <p>{{ \Carbon\Carbon::parse($table_production->start_time)->format('H:i') }}</p>
                            </td>
                            <td class="table-data-production" id="finish-time">
                                <p>{{ \Carbon\Carbon::parse($table_production->finish_time)->format('H:i') }}</p>
                            </td>
                            <td class="table-data-production" id="total-time">
                                <p>{{ $table_production->total_prod_time }}</p>
                            </td>
                            <td class="table-data-production" id="spm">
                                <p>{{ $table_production->spm }}</p>
                            </td>
                            <td class="table-data-production" id="qty">
                                <p>{{ $table_production->plan_a }}</p>
                            </td>
                            <td class="table-data-production" id="qty">
                                <p>{{ $table_production->plan_b }}</p>
                            </td>
                            <td class="table-data-production" id="qty">
                                <p>{{ $table_production->ok_a }}</p>
                            </td>
                            <td class="table-data-production" id="qty">
                                <p>{{ $table_production->ok_b }}</p>
                            </td>
                            <td class="table-data-production" id="qty">
                                <p>{{ $table_production->rework_a }}</p>
                            </td>
                            <td class="table-data-production" id="qty">
                                <p>{{ $table_production->rework_b }}</p>
                            </td>
                            <td class="table-data-production" id="qty">
                                <p>{{ $table_production->scrap_a }}</p>
                            </td>
                            <td class="table-data-production" id="qty">
                                <p>{{ $table_production->scrap_b }}</p>
                            </td>
                            <td class="table-data-production" id="qty">
                                <p>{{ $table_production->sample_a }}</p>
                            </td>
                            <td class="table-data-production" id="qty">
                                <p>{{ $table_production->sample_b }}</p>
                            </td>
                            <td class="table-data-production" id="rework-exp">
                                <p>{{ $table_production->rework_exp }}</p>
                            </td>
                            <td class="table-data-production" id="scrap-exp">
                                <p>{{ $table_production->scrap_exp }}</p>
                            </td>
                            <td class="table-data-production" id="trial-sample-exp">
                                <p>{{ $table_production->trial_sample_exp }}</p>
                            </td>
                            <td class="table-data-production" id="bolster">
                                <p>{{ $table_production->bolster_1 }}</p>
                            </td>
                            <td class="table-data-production" id="bolster">
                                <p>{{ $table_production->bolster_2 }}</p>
                            </td>
                            <td class="table-data-production" id="bolster">
                                <p>{{ $table_production->bolster_3 }}</p>
                            </td>
                            <td class="table-data-production" id="bolster">
                                <p>{{ $table_production->bolster_4 }}</p>
                            </td>
                            <td class="table-data-production" id="coil-number">
                                <p>{{ $table_production->coil_no }}</p>
                            </td>
                            <td class="table-data-production" id="created-at">
                                <p>{{ \Carbon\Carbon::parse($table_production->created_at)->format('Y-m-d H:i') }}</p>
                            </td>
                            <td class="table-data-production" id="updated-at">
                                <p>{{ \Carbon\Carbon::parse($table_production->updated_at)->format('Y-m-d H:i') }}</p>
                            </td>
                            <td id="action-for-table-data-production">

                                <a href="{{ route('table_production.edit', $table_production->id) }}"
                                    class="table-production-btn" id="btn-edit">
                                    <i class="bx bx-edit" style="color: blue; font-size: 1rem;"></i>
                                </a>

                                <button class="delete-table-production-btn" id="btn-delete"
                                    data_id="{{ $table_production->id }}"
                                    item_name="{{ $table_production->item_name }}"
                                    production_date="{{ $table_production->date }}"><i class="bx bx-trash" style="color: red; font-size: 1rem;"></i></button>
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
<script src="../js/delete-table-production.js"></script>

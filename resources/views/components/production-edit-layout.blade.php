@props([
    'models',
    'years',
    'items',
    'selectedItem',
    'pictures',
    'production',
    'processNames',
    'dtCategories',
    'dtClassifications',
])
@php use Carbon\Carbon; @endphp

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/input-production-layout.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>


<section class="home">
    <div class="toggle-sidebar">
        <i class='bx bx-x-circle' id="hide-toggle"></i>
        <i class='bx bx-menu' id="show-toggle"></i>
    </div>
    <div class="container">

        {{-- ===== Form Untuk Update Data Production Report ===== --}}
        <form class="report-form" action="{{ route('table_production.update', $production->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="production-main-row">
                <div class="production-form-col">
                    <table id="tbl-form-input-data-production">
                        <tr>
                            <th><label for="reporter" class="td-right-gen">Reporter :</label></th>
                            <td>
                                <select id="reporter" name="reporter" required>
                                    <option value="">Select Reporter</option>
                                    <option value="Joni" {{ $production->reporter == 'Joni' ? 'selected' : '' }}>Joni
                                    </option>
                                    <option value="Kosim" {{ $production->reporter == 'Kosim' ? 'selected' : '' }}>
                                        Kosim
                                    </option>
                                    <option value="Sudarto" {{ $production->reporter == 'Sudarto' ? 'selected' : '' }}>
                                        Sudarto
                                    </option>
                                    <option value="Eman" {{ $production->reporter == 'Eman' ? 'selected' : '' }}>Eman
                                    </option>
                                </select>
                            </td>
                            <th class="td-right-gen">
                                <label for="group">Group :</label>
                            </th>
                            <td>
                                <select id="group" name="group" required>
                                    <option value="">-</option>
                                    <option value="A" {{ $production->group == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ $production->group == 'B' ? 'selected' : '' }}>B</option>
                                </select>
                            </td>
                            <th rowspan="2" class="td-right-gen">
                                <label for="line">Press Line :</label>
                            </th>
                            <td rowspan="2">
                                <select id="line" name="line" required>
                                    <option value="">-</option>
                                    <option value="Line-A" {{ $production->line == 'Line-A' ? 'selected' : '' }}>Line-A
                                    </option>
                                    <option value="Line-B" {{ $production->line == 'Line-B' ? 'selected' : '' }}>Line-B
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="date" class="td-right-gen">Date :</label></th>
                            <td>
                                <input type="date" id="date" name="date" value="{{ $production->date }}"
                                    required>
                            </td>
                            <td class="td-right-gen">
                                <label for="shift">Shift :</label>
                            </td>
                            <td>
                                <select name="shift" id="shift" required>
                                    <option value="">-</option>
                                    <option value="day" {{ $production->shift == 'day' ? 'selected' : '' }}>Day
                                    </option>
                                    <option value="night" {{ $production->shift == 'night' ? 'selected' : '' }}>Night
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="start_time" class="td-right-gen">Start Time :</label></th>
                            <td>
                                <input type="time" id="time" name="start_time"
                                    value="{{ \Carbon\Carbon::parse($production->start_time)->format('H:i') }}"
                                    required>
                            </td>
                            <th><label for="model" class="td-right-gen">Model :</label></th>
                            <td>
                                <select name="model" id="model" required>
                                    <option value="">---</option>
                                    @foreach ($models as $model)
                                        <option value="{{ $model }}"
                                            {{ $production->model == $model ? 'selected' : '' }}>
                                            {{ $model }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="td-right-gen">
                                <label for="total_prod_time">Total Time :</label>
                            </td>
                            <td>
                                <input type="text" name="show_total_prod_time" id="show_total_prod_time"
                                    value="{{ $production->total_prod_time }} minutes" placeholder="...minutes"
                                    min="0" step="1"
                                    oninput="document.getElementById('total_prod_time').value = this.value;" required>
                                <input type="hidden" name="total_prod_time" id="total_prod_time"
                                    value="{{ $production->total_prod_time }}">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="finish_time" class="td-right-gen">Finish Time :</label></th>
                            <td>
                                <input type="time" id="time" name="finish_time"
                                    value="{{ \Carbon\Carbon::parse($production->finish_time)->format('H:i') }}"
                                    required>
                            </td>
                            <td class="td-right-gen">
                                <label class="model_year" for="model_year">Model Year :</label>
                            </td>
                            <td>
                                <select name="model_year" id="model_year" required>
                                    <option value="">----</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}"
                                            {{ $production->model_year == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="td-right-gen">
                                <label for="spm">SPM :</label>
                            </td>
                            <td>
                                <input type="number" name="spm" id="spm" min="00.0" max="15.0"
                                    step="00.1" value="{{ $production->spm }}" placeholder="00.0"
                                    oninput="limitInputLength(this, 4)" required>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="item_name" class="td-right-gen">Item Name :</label></th>
                            <td colspan="3" class="items">

                                @php
                                    // Ambil bagian setelah strip, atau gunakan seluruh string jika tidak ada strip
                                    $searchName = Str::contains($production->item_name, '-')
                                        ? trim(Str::after($production->item_name, '-'))
                                        : $production->item_name;
                                    $selectedItem = $items->first(function ($item) use ($searchName) {
                                        return trim(strtolower($item->item_name)) === trim(strtolower($searchName));
                                    });
                                @endphp

                                {{-- Dropdown untuk memilih item --}}

                                <select name="item_name" id="item_name" required>
                                    {{-- Opsi yang sudah dipilih --}}
                                    <option value="{{ $production->item_name }}" selected
                                        data-picture="{{ $selectedItem?->product_picture }}">
                                        {{ $production->item_name }}
                                    </option>
                                    @foreach ($items as $item)
                                        @if ($item->item_name !== $production->item_name)
                                            <option value="{{ $item->model_code }}-{{ $item->item_name }}"
                                                data-picture="{{ $item->product_picture }}">
                                                {{ $item->model_code }}-{{ $item->item_name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>

                            </td>
                        </tr>

                        {{-- Material Ticket Number Section --}}
                        <tr>
                            <td class="td-right-gen">
                                <label>Material Ticket No. :</label>
                            </td>
                            <th>
                                <select class="which-side-material" name="which-side-material[]"
                                    id="which-side-material">
                                    <option value="">--</option>
                                    <option value="Single">Single</option>
                                    <option value="LH">LH</option>
                                    <option value="OTR">OTR</option>
                                    <option value="T/G">T/G</option>
                                    <option value="">--</option>
                                    <option value="RH">RH</option>
                                    <option value="INR">INR</option>
                                    <option value="S/P">S/P</option>
                                    <option value="RNE">RNE</option>
                                </select>
                            </th>
                            <td>
                                <input type="text" name="material_ticket_no_text[]" id="material_ticket_no_text"
                                    placeholder="...ticket no." required>
                            </td>
                            <td>
                                <select name="material_ticket_no_r[]" id="material_ticket_no_r">
                                    <option value="">--</option>
                                    <option value="R1">R1</option>
                                    <option value="R2">R2</option>
                                    <option value="R3">R3</option>
                                    <option value="R4">R4</option>
                                    <option value="R5">R5</option>
                                    <option value="R6">R6</option>
                                    <option value="R7">R7</option>
                                    <option value="R8">R8</option>
                                    <option value="R9">R9</option>
                                    <option value="R10">R10</option>
                                </select>
                            </td>
                            <td>
                                <select name="material_ticket_no_s[]" id="material_ticket_no_s">
                                    <option value="">--</option>
                                    <option value="S00">S00</option>
                                    <option value="S01">S01</option>
                                    <option value="S02">S02</option>
                                    <option value="S03">S03</option>
                                    <option value="S04">S04</option>
                                    <option value="S05">S05</option>
                                </select>
                            </td>
                            <td>
                                <select name="material_ticket_no_p[]" id="material_ticket_no_p">
                                    <option value="">--</option>
                                    <option value="P1">P1</option>
                                    <option value="P2">P2</option>
                                    <option value="P3">P3</option>
                                    <option value="P4">P4</option>
                                    <option value="P5">P5</option>
                                    <option value="P6">P6</option>
                                    <option value="P7">P7</option>
                                    <option value="P8">P8</option>
                                    <option value="P9">P9</option>
                                    <option value="P10">P10</option>
                                    <option value="P11">P11</option>
                                    <option value="P12">P12</option>
                                    <option value="P13">P13</option>
                                    <option value="P14">P14</option>
                                    <option value="P15">P15</option>
                                    <option value="P16">P16</option>
                                    <option value="P17">P17</option>
                                    <option value="P18">P18</option>
                                    <option value="P19">P19</option>
                                    <option value="P20">P20</option>
                                    <option value="P21">P21</option>
                                    <option value="P22">P22</option>
                                    <option value="P23">P23</option>
                                    <option value="P24">P24</option>
                                    <option value="P25">P25</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td class="td-right-gen"></td>
                            <td colspan="5" style="text-align: left; padding: 5px;">
                                <button type="button" id="btn-addMaterialTicketNumber"
                                    style="background: #4CAF50; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                                    Add Material Ticket
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td class="td-right-gen">
                                <label for="coil_no">Coil No. :</label>
                            </td>
                            <td colspan="5">
                                <input type="text" name="coil_no" id="coil_no"
                                    value="{{ $production->coil_no }}"
                                    placeholder="Auto-generated from material ticket" readonly>
                            </td>
                        </tr>

                        <tr>
                            <td class="td-right-gen">
                                <label>Bolster :</label>
                            </td>
                            <td colspan="5">
                                <table id="bolster-table">
                                    <thead>
                                        <tr>
                                            <th colspan="">Bolster No.1</th>
                                            <th colspan="">Bolster No.2</th>
                                            <th colspan="">Bolster No.3</th>
                                            <th colspan="">Bolster No.4</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="bolster_1" id="bolster_1">
                                                    <option value="">--</option>
                                                    <option value="LH"
                                                        {{ $production->bolster_1 == 'LH' ? 'selected' : '' }}>LH
                                                    </option>
                                                    <option value="RH"
                                                        {{ $production->bolster_1 == 'RH' ? 'selected' : '' }}>RH
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="bolster_2" id="bolster_2">
                                                    <option value="">--</option>
                                                    <option value="LH"
                                                        {{ $production->bolster_2 == 'LH' ? 'selected' : '' }}>LH
                                                    </option>
                                                    <option value="RH"
                                                        {{ $production->bolster_2 == 'RH' ? 'selected' : '' }}>RH
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="bolster_3" id="bolster_3">
                                                    <option value="">--</option>
                                                    <option value="LH"
                                                        {{ $production->bolster_3 == 'LH' ? 'selected' : '' }}>LH
                                                    </option>
                                                    <option value="RH"
                                                        {{ $production->bolster_3 == 'RH' ? 'selected' : '' }}>RH
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="bolster_4" id="bolster_4">
                                                    <option value="">--</option>
                                                    <option value="LH"
                                                        {{ $production->bolster_4 == 'LH' ? 'selected' : '' }}>LH
                                                    </option>
                                                    <option value="RH"
                                                        {{ $production->bolster_4 == 'RH' ? 'selected' : '' }}>RH
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr class="tbl-qty">
                            <th class="td-right-gen">Qty :</th>
                            <td class="label-act-qty" id="plan">
                                <p>Plan</p>
                            </td>
                            <td class="label-act-qty" id="ok">
                                <p>OK</p>
                            </td>
                            <td class="label-act-qty" id="rework">
                                <p>Rework</p>
                            </td>
                            <td class="label-act-qty" id="ng">
                                <p>NG Scrap</p>
                            </td>
                            <td class="label-act-qty" id="sample">
                                <p>Sample</p>
                            </td>
                        </tr>
                        <tr class="tbl-qty">
                            <th>
                                <select class="which-side-a" name="which-plan_a" id="which-side-a" required>
                                    <option value="single"
                                        {{ old('which-plan_a', $production->which_plan_a ?? '') == 'single' ? 'selected' : '' }}>
                                        Single</option>
                                    <option value="lh"
                                        {{ old('which-plan_a', $production->which_plan_a ?? '') == 'lh' ? 'selected' : '' }}>
                                        LH
                                    </option>
                                    <option value="otr"
                                        {{ old('which-plan_a', $production->which_plan_a ?? '') == 'otr' ? 'selected' : '' }}>
                                        OTR</option>
                                    <option value="tg-Otr"
                                        {{ old('which-plan_a', $production->which_plan_a ?? '') == 'tg-Otr' ? 'selected' : '' }}>
                                        T/G</option>
                                </select>
                            </th>
                            <td>
                                <label for="plan_a" class="td-right-a">A-side :</label>
                                <input class="input-qty" type="number" id="plan_a" name="plan_a"
                                    min="0" max="999" value="{{ $production->plan_a }}"
                                    oninput="limitInputLength(this, 3)" placeholder=".... pcs" required>
                            </td>
                            <td>
                                <label for="ok_a" class="td-right-a">A-side :</label>
                                <input class="input-qty" type="number" id="ok_a" name="ok_a"
                                    min="0" max="999" value="{{ $production->ok_a }}"
                                    oninput="limitInputLength(this, 3)" placeholder=".... pcs" required>
                            </td>
                            <td>
                                <label for="rework_a" class="td-right-a">A-side :</label>
                                <input class="input-qty" type="number" id="rework_a" name="rework_a"
                                    min="0" max="999" value="{{ $production->rework_a }}"
                                    oninput="limitInputLength(this, 3)" placeholder=".... pcs" required>
                            </td>
                            <td>
                                <label for="scrap_a" class="td-right-a">A-side :</label>
                                <input class="input-qty" type="number" id="scrap_a" name="scrap_a"
                                    min="0" max="999" value="{{ $production->scrap_a }}"
                                    oninput="limitInputLength(this, 3)" placeholder=".... pcs" required>
                            </td>
                            <td>
                                <label for="sample_a" class="td-right-a">A-side :</label>
                                <input class="input-qty" type="number" id="sample_a" name="sample_a"
                                    min="0" max="999" value="{{ $production->sample_a }}"
                                    oninput="limitInputLength(this, 3)" placeholder=".... pcs" required>
                            </td>
                        </tr>
                        <tr class="tbl-qty">
                            <th>
                                <select class="which-side-b" name="which-plan_b" id="which-side-b" required>
                                    <option value="---"
                                        {{ old('which-plan_b', $production->which_plan_b ?? '') == '---' ? 'selected' : '' }}>
                                        ---</option>
                                    <option value="rh"
                                        {{ old('which-plan_b', $production->which_plan_b ?? '') == 'rh' ? 'selected' : '' }}>
                                        RH
                                    </option>
                                    <option value="inr"
                                        {{ old('which-plan_b', $production->which_plan_b ?? '') == 'inr' ? 'selected' : '' }}>
                                        INR</option>
                                    <option value="spoiler"
                                        {{ old('which-plan_b', $production->which_plan_b ?? '') == 'spoiler' ? 'selected' : '' }}>
                                        S/P</option>
                                    <option value="rne"
                                        {{ old('which-plan_b', $production->which_plan_b ?? '') == 'rne' ? 'selected' : '' }}>
                                        RNE</option>
                                </select>
                            </th>
                            <td>
                                <label for="plan_b" class="td-right-b">B-side :</label>
                                <input class="input-qty" type="number" id="plan_b" name="plan_b"
                                    min="0" max="999" value="{{ $production->plan_b }}"
                                    oninput="limitInputLength(this, 3)" placeholder=".... pcs" required>
                            </td>
                            <td>
                                <label for="ok_b" class="td-right-b">B-side :</label>
                                <input class="input-qty" type="number" id="ok_b" name="ok_b"
                                    min="0" max="999" value="{{ $production->ok_b }}"
                                    oninput="limitInputLength(this, 3)" placeholder=".... pcs" required>
                            </td>
                            <td>
                                <label for="rework_b" class="td-right-b">B-side :</label>
                                <input class="input-qty" type="number" id="rework_b" name="rework_b"
                                    min="0" max="999" value="{{ $production->rework_b }}"
                                    oninput="limitInputLength(this, 3)" placeholder=".... pcs" required>
                            </td>
                            <td>
                                <label for="scrap_b" class="td-right-b">B-side :</label>
                                <input class="input-qty" type="number" id="scrap_b" name="scrap_b"
                                    min="0" max="999" value="{{ $production->scrap_b }}"
                                    oninput="limitInputLength(this, 3)" placeholder=".... pcs" required>
                            </td>
                            <td>
                                <label for="sample_b" class="td-right-b">B-side :</label>
                                <input class="input-qty" type="number" id="sample_b" name="sample_b"
                                    min="0" max="999" value="{{ $production->sample_b }}"
                                    oninput="limitInputLength(this, 3)" placeholder=".... pcs" required>
                            </td>
                        </tr>
                        <tr class="tbl-exp">
                            <th class="td-right-gen">Rework :</th>
                            <td colspan="5">
                                <input type="text" name="rework_exp" id="qty_exp"
                                    value="{{ $production->rework_exp }}" placeholder="...input defect name">
                            </td>
                        </tr>
                        <tr class="tbl-exp">
                            <th class="td-right-gen">NG Process :</th>
                            <td colspan="5">
                                <input type="text" name="scrap_exp" id="qty_exp"
                                    value="{{ $production->scrap_exp }}" placeholder="...input defect name">
                            </td>
                        </tr>
                        <tr class="tbl-exp">
                            <th class="td-right-gen">Trial & Sample :</th>
                            <td colspan="5">
                                <input type="text" name="trial_sample_exp" id="qty_exp"
                                    value="{{ $production->trial_sample_exp }}"
                                    placeholder="...input trial or sample purpose">
                            </td>
                        </tr>
                    </table>
                </div>


                <div class="area-mapping-col">
                    <div class="area-mapping-image">

                        <table id="area-matrix">
                            <thead>
                                <tr>
                                    <th></th>
                                    @for ($col = 0; $col < 16; $col++)
                                        <th>{{ chr(65 + $col) }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @for ($row = 1; $row <= 16; $row++)
                                    <tr>
                                        <th>{{ $row }}</th>
                                        @for ($col = 0; $col < 16; $col++)
                                            <td class="matrix-cell"
                                                data-area="{{ chr(65 + $col) }}{{ $row }}">
                                                <!-- Area cell -->
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                        <img id="product-image" src="" alt="Product Image" alt="Product Image">
                    </div>
                </div>
                <div class="area-mapping-table">
                    <table id="area-defect-table">
                        <thead>
                            <tr>
                                <th id="area">Area</th>
                                <th id="defect-name">Defect</th>
                                <th id="defect-qty-a">Qty-a</th>
                                <th id="defect-qty-b">Qty-b</th>
                                <th id="defect-category">Category</th>
                                <th id="defect-action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($production->tableDefects as $defect)
                                <tr id="area-row-{{ $defect->defect_area }}">
                                    <td>
                                        <input type="hidden" name="defect_areas[]"
                                            value="{{ $defect->defect_area }}">
                                        {{ $defect->defect_area }}
                                    </td>
                                    <td>
                                        <input type="text" name="defect_names[]"
                                            value="{{ $defect->defect_name }}" placeholder="Defect" required>
                                    </td>
                                    <td>
                                        <input type="number" name="defect_qtys_a[]"
                                            value="{{ $defect->defect_qty_a }}" min="1" placeholder="Qty-a"
                                            required>
                                    </td>
                                    <td>
                                        <input type="number" name="defect_qtys_b[]"
                                            value="{{ $defect->defect_qty_b }}" min="1" placeholder="Qty-b">
                                    </td>
                                    <td>
                                        <select name="defect_categories[]" required>
                                            <option value="" disabled>Category</option>
                                            <option value="inline"
                                                {{ $defect->defect_category == 'inline' ? 'selected' : '' }}>in-Line
                                            </option>
                                            <option value="outline"
                                                {{ $defect->defect_category == 'outline' ? 'selected' : '' }}>out-Line
                                            </option>
                                            <option value="scrap"
                                                {{ $defect->defect_category == 'scrap' ? 'selected' : '' }}>Scrap
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-remove-defect"
                                            onclick="removeDefectRow('{{ $defect->defect_area }}')">Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            <h3>Detail Description and Problem</h3>

            {{-- ===== Table untuk detail problem produksi ===== --}}
            <table id="tbl-prod-problem">
                <thead>
                    <tr>
                        <th>
                            <p class="header-a">From</p>
                        </th>
                        <th>
                            <p class="header-a">Until</p>
                        </th>
                        <th>
                            <p class="header-a2">Total</p>
                        </th>
                        <th>
                            <p class="header-b">Process</p>
                        </th>
                        <th>
                            <p class="header-b">DT Category</p>
                        </th>
                        <th class="header-hide">
                            <p class="header-b">DT Type</p>
                        </th>
                        <th>
                            <p class="header-b">DT Classification</p>
                        </th>
                        <th>
                            <p class="header-c">Problem Description</p>
                        </th>
                        <th>
                            <p class="header-c">Root Causes</p>
                        </th>
                        <th>
                            <p class="header-d">Action/Countermeasure</p>
                        </th>
                        <th>
                            <p class="header-a">PIC</p>
                        </th>
                        <th>
                            <p class="header-a">Status</p>
                        </th>
                        <th>
                            <p class="header-e">Picture</p>
                        </th>
                        <th>
                            <p class="header-e">Action</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($production->tableDowntimes as $index => $problem)
                        <tr id="row{{ $index }}">
                            <td><input type="time" name="production_problems[{{ $index }}][time_from]"
                                    value="{{ \Carbon\Carbon::parse($problem->time_from)->format('H:i') }}" required>
                            </td>
                            <td><input type="time" name="production_problems[{{ $index }}][time_until]"
                                    value="{{ \Carbon\Carbon::parse($problem->time_until)->format('H:i') }}" required>
                            </td>
                            <td><input type="number" id="total-problem-time"
                                    name="production_problems[{{ $index }}][total_time]"
                                    value="{{ $problem->total_time }}" min="1" required></td>
                            <td>
                                <select name="production_problems[{{ $index }}][process_name]" required>
                                    <option value="">Select Process</option>
                                    @foreach ($processNames as $process)
                                        <option value="{{ $process->process_name }}"
                                            {{ $problem->process_name == $process->process_name ? 'selected' : '' }}>
                                            {{ $process->process_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="production_problems[{{ $index }}][dt_category]"
                                    class="dt-category-select" data-index="{{ $index }}" required>
                                    <option value="">Select Category</option>
                                    @foreach ($dtCategories as $cat)
                                        <option value="{{ $cat->downtime_name }}" data-id="{{ $cat->id }}"
                                            {{ $problem->dt_category == $cat->downtime_name ? 'selected' : '' }}>
                                            {{ $cat->downtime_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="row-hide">
                                <input type="text" name="production_problems[{{ $index }}][downtime_type]"
                                    class="dt-type-input" value="{{ $problem->downtime_type }}" readonly>
                            </td>
                            <td>
                                <select name="production_problems[{{ $index }}][dt_classification]" required>
                                    <option value="">Select Classification</option>
                                    @foreach ($dtClassifications as $class)
                                        <option value="{{ $class->downtime_classification }}"
                                            {{ $problem->dt_classification == $class->downtime_classification ? 'selected' : '' }}>
                                            {{ $class->downtime_classification }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <textarea class="scroll-x-textarea" name="production_problems[{{ $index }}][problem_description]" required>{{ $problem->problem_description }}</textarea>
                            </td>
                            <td>
                                <textarea class="scroll-x-textarea" name="production_problems[{{ $index }}][root_cause]" required>{{ $problem->root_cause }}</textarea>
                            </td>
                            <td>
                                <textarea class="scroll-x-textarea" name="production_problems[{{ $index }}][counter_measure]" required>{{ $problem->counter_measure }}</textarea>
                            </td>
                            <td>
                                <select name="production_problems[{{ $index }}][pic]" required>
                                    <option value="">Select PIC</option>
                                    <option value="press" {{ $problem->pic == 'press' ? 'selected' : '' }}>Press
                                    </option>
                                    <option value="tooling" {{ $problem->pic == 'tooling' ? 'selected' : '' }}>
                                        Tooling
                                    </option>
                                    <option value="mtc" {{ $problem->pic == 'mtc' ? 'selected' : '' }}>MTC
                                    </option>
                                    <option value="mh" {{ $problem->pic == 'mh' ? 'selected' : '' }}>MH</option>
                                    <option value="pe stamping"
                                        {{ $problem->pic == 'pe stamping' ? 'selected' : '' }}>PE Stamping</option>
                                    <option value="supplier" {{ $problem->pic == 'supplier' ? 'selected' : '' }}>
                                        Supplier</option>
                                    <option value="other" {{ $problem->pic == 'other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </td>
                            <td>
                                <select name="production_problems[{{ $index }}][status]" required>
                                    <option value="">Select Status</option>
                                    <option value="open" {{ $problem->status == 'open' ? 'selected' : '' }}>Open
                                    </option>
                                    <option value="monitoring"
                                        {{ $problem->status == 'monitoring' ? 'selected' : '' }}>Monitoring</option>
                                    <option value="close" {{ $problem->status == 'close' ? 'selected' : '' }}>Closed
                                    </option>
                                </select>
                            </td>
                            <td>
                                <span class="problem-picture-link">
                                    @if ($problem->problem_picture)
                                        <div class="img-preview-container">
                                            <a href="#" class="problem-img-link"
                                                data-img="{{ asset($problem->problem_picture) }}">
                                                img
                                            </a>
                                            <button type="button" class="btn-delete-image"
                                                onclick="deleteExistingImage(this, {{ $index }}, {{ $problem->id }})">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                            <input type="hidden"
                                                name="production_problems[{{ $index }}][problem_picture]"
                                                value="{{ $problem->problem_picture }}">
                                        </div>
                                    @else
                                        <span>
                                            <button type="button" class="btn-upload-picture"
                                                onclick="showUploadForm(this)">
                                                <i class="bx bx-camera"></i>
                                            </button>
                                            <input type="file" name="problem_pictures[]" accept="image/*"
                                                capture="environment" style="display:none;"
                                                onchange="handlePictureUpload(this)">
                                        </span>
                                    @endif
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn-remove-row"
                                    onclick="deleteRow({{ $index }})">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="btn-row">
                <button type="button" id="btn-addRow">Add Row</button>
            </div>

            <div class="submit-btn">
                <button type="submit" id="submit">Update</button>
                <button type="button" id="cancel"
                    onclick="window.location.href='{{ route('table_production') }}'">Cancel</button>
            </div>
        </form>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
</section>

<div id="imgModal"
    style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.6); align-items:center; justify-content:center;">
    <span id="closeImgModal">&times;</span>
    <img id="imgModalContent" src="">
</div>

<script>
    $(document).on('click', '.problem-img-link', function(e) {
        e.preventDefault();
        const imgSrc = $(this).data('img');
        $('#imgModalContent').attr('src', imgSrc);
        $('#imgModal').fadeIn(200);
    });

    $('#closeImgModal, #imgModal').on('click', function(e) {
        // Hanya tutup jika klik di background atau tombol close
        if (e.target.id === 'imgModal' || e.target.id === 'closeImgModal') {
            $('#imgModal').fadeOut(200);
            $('#imgModalContent').attr('src', '');
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check untuk flash message dan tampilkan sebagai alert
        @if (session('success'))
            alert("{{ session('success') }}");
        @endif

        @if (session('error'))
            alert("{{ session('error') }}");
        @endif
    });
    // Pemilihan side item
    document.addEventListener('DOMContentLoaded', function() {
        const selectElementA = document.querySelector('.which-side-a');
        const selectElementB = document.querySelector('.which-side-b');
        const labelsA = document.querySelectorAll('label.td-right-a');
        const labelsB = document.querySelectorAll('label.td-right-b');

        function updateLabelsA(value) {
            labelsA.forEach(label => {
                switch (value) {
                    case 'single':
                        label.textContent = 'Single :';
                        break;
                    case 'lh':
                        label.textContent = 'LH :';
                        break;
                    case 'otr':
                        label.textContent = 'OTR :';
                        break;
                    case 'tg-Otr':
                        label.textContent = 'T/G :';
                        break;
                    default:
                        label.textContent = 'A-side :';
                }
            });
        }


        function updateLabelsB(value) {
            labelsB.forEach(label => {
                switch (value) {
                    case '---':
                        label.textContent = '--- :';
                        break;
                    case 'rh':
                        label.textContent = 'RH :';
                        break;
                    case 'inr':
                        label.textContent = 'INR :';
                        break;
                    case 'spoiler':
                        label.textContent = 'Spoiler :';
                        break;
                    case 'rne':
                        label.textContent = 'RNE :';
                        break;
                    default:
                        label.textContent = '--- :';
                }
            });
        }

        // Set initial label text based on default select value
        updateLabelsA(selectElementA.value);
        updateLabelsB(selectElementB.value);

        // Update labels when select value changes
        selectElementA.addEventListener('change', function() {
            updateLabelsA(this.value);
        });
        selectElementB.addEventListener('change', function() {
            updateLabelsB(this.value);
        });
    });

    // Menampilkan model dan item
    document.addEventListener('DOMContentLoaded', function() {
        const modelSelect = document.getElementById('model');
        const yearSelect = document.getElementById('model_year');
        const itemSelect = document.getElementById('item_name');
        const currentItem = "{{ $production->item_name }}";

        modelSelect.addEventListener('change', function() {
            yearSelect.innerHTML = '<option value="">----</option>';
            itemSelect.innerHTML = '<option value="">MODEL-PNL,ITEM NAME</option>';

            if (this.value) {
                fetch(`/api/years/${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(year => {
                            let option = new Option(year, year);
                            yearSelect.add(option);
                        });
                    });

                fetch(`/api/items/${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(item => {
                            let option = new Option(`${item.model_code}-${item.item_name}`,
                                `${item.model_code}-${item.item_name}`);
                            itemSelect.add(option);
                        });
                        // Jika model sama dengan model awal, set item_name ke value awal
                        if (this.value === "{{ $production->model }}") {
                            itemSelect.value = currentItem;
                        }
                    });
            }
        });
    });

    function showUploadForm(btn) {
        // Reset file input terlebih dahulu untuk memastikan event change terpicu
        const fileInput = btn.nextElementSibling;
        fileInput.value = '';
        fileInput.click();
    }

    // Fungsi untuk upload image problem
    function handlePictureUpload(input) {
        const file = input.files[0];
        if (file) {
            const row = $(input).closest('tr');
            const rowIndex = row.index();
            const linkSpan = row.find('.problem-picture-link'); // Gunakan class yang benar

            // Buat preview gambar base64
            const reader = new FileReader();
            reader.onload = function(e) {
                // Format HTML yang konsisten dengan format asli
                linkSpan.html(`
                <div class="img-preview-container">
                    <a href="#" class="problem-img-preview" data-img="${e.target.result}">
                        img
                    </a>
                    <button type="button" class="btn-delete-image" 
                            onclick="removeUploadedImage(this, ${rowIndex})">
                        <i class="bx bx-trash"></i>
                    </button>
                    <input type="hidden" name="production_problems[${rowIndex}][problem_picture_data]" 
                           value="${e.target.result}">
                    <input type="hidden" name="production_problems[${rowIndex}][problem_picture_name]" 
                           value="${file.name}">
                </div>
            `);

                // Hapus flag delete_picture jika ada
                linkSpan.find('input[name="production_problems[' + rowIndex + '][delete_picture]"]').remove();
            };
            reader.readAsDataURL(file);
        }
    }

    // Fungsi tambahan untuk menghapus gambar yang baru diupload
    function removeUploadedImage(btn, index) {
        if (!confirm("Apakah Anda yakin ingin menghapus gambar ini?")) {
            return false;
        }

        const linkSpan = $(btn).closest('.problem-picture-link');
        linkSpan.html(`
        <span class="no-image">No image</span>
        <input type="hidden" name="production_problems[${index}][delete_picture]" value="1">
    `);

        // Reset file input
        const row = linkSpan.closest('tr');
        const fileInput = row.find('input[type="file"]');
        fileInput.val('');

        return false;
    }

    // Fungsi tambahan untuk menghapus gambar yang sudah ada
    function deleteExistingImage(btn, index, problemId) {
        if (!confirm("Apakah Anda yakin ingin menghapus gambar ini?")) {
            return false;
        }

        // Dapatkan elemen parent dari tombol
        const linkSpan = $(btn).closest('.problem-picture-link');

        // Tampilkan loading state
        linkSpan.html('<span>Menghapus...</span>');

        // Kirim request AJAX untuk menghapus gambar
        $.ajax({
            url: '/delete-problem-picture/' + problemId,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Ganti dengan "No image" dan tambahkan flag untuk form submit
                linkSpan.html(`
                <span class="no-image">No image</span>
                <input type="hidden" name="production_problems[${index}][delete_picture]" value="1">
            `);
            },
            error: function(error) {
                console.error('Error deleting image', error);
                alert('Gagal menghapus gambar. Silakan coba lagi.');
            }
        });

        return false;
    }

    $(document).on('click', '.problem-img-preview', function(e) {
        e.preventDefault();
        const imgSrc = $(this).data('img');
        $('#imgModalContent').attr('src', imgSrc);
        $('#imgModal').fadeIn(200);
    });

    $('#closeImgModal, #imgModal').on('click', function(e) {
        // Hanya tutup jika klik di background atau tombol close
        if (e.target.id === 'imgModal' || e.target.id === 'closeImgModal') {
            $('#imgModal').fadeOut(200);
            $('#imgModalContent').attr('src', '');
        }
    });
</script>

<script>
    // Menampilkan gambar produk saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Set image saat halaman edit dibuka
        const itemSelect = document.getElementById('item_name');
        const img = document.getElementById('product-image');
        // Cari option yang selected
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        const picture = selectedOption.getAttribute('data-picture');
        // console.log('item Select: ', itemSelect, 'image: ', img, 'Selected Option:', selectedOption,
        //     'picture: ', picture);
        if (picture) {
            img.src = `/images/products/${encodeURIComponent(picture)}`;
        } else {
            img.src = '';
        }
        img.onerror = function() {
            this.src = '';
        };
    });

    // Marking matriks yang sudah ada defect-nya
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil semua area defect dari Blade ke JS
        let selectedDefectAreas = @json($production->tableDefects->pluck('defect_area')->toArray());

        // Tandai cell matrix yang sudah ada defect-nya
        selectedDefectAreas.forEach(function(area) {
            const cell = document.querySelector(`.matrix-cell[data-area="${area}"]`);
            if (cell) {
                cell.classList.add('selected');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {

        // Menangani perubahan pada dropdown item_name untuk menampilkan gambar produk

        document.getElementById('item_name').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const picture = selectedOption.getAttribute('data-picture');
            const img = document.getElementById('product-image');
            console.log('Selected Option change:', selectedOption, 'Picture:', picture);
            if (picture) {
                img.src = `/images/products/${encodeURIComponent(picture)}`;
            } else {
                img.src = '';
            }
            img.onerror = function() {
                this.src = '';
            };
        });

        const selectedAreas = {};
        document.querySelectorAll('.matrix-cell').forEach(cell => {
            cell.addEventListener('click', function() {
                const area = this.getAttribute('data-area');
                if (selectedAreas[area]) {
                    // Unselect
                    this.classList.remove('selected');
                    delete selectedAreas[area];
                    const row = document.getElementById('area-row-' + area);
                    if (row) row.remove();
                } else {
                    // Select
                    this.classList.add('selected');
                    selectedAreas[area] = true;
                    // Tambah row di tabel
                    const tbody = document.querySelector('#area-defect-table tbody');
                    const row = document.createElement('tr');
                    row.id = 'area-row-' + area;
                    row.innerHTML = `
                    <td><input type="hidden" id="area" name="defect_areas[]" value="${area}">${area}</td>
                    <td><input type="text" id="defect-name" name="defect_names[]" placeholder="Defect" required></td>
                    <td><input type="number" id="defect-qty-a" name="defect_qtys_a[]" min="1" placeholder="Qty-a" required></td>
                    <td><input type="number" id="defect-qty-b" name="defect_qtys_b[]" min="1" placeholder="Qty-b" nullable></td>
                    <td>
                        <select id="defect-category" name="defect_categories[]" required>
                            <option value="" disabled selected>Category</option>
                            <option value="inline">in-Line</option>
                            <option value="outline">out-Line</option>
                            <option value="scrap">Scrap</option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn-remove-defect" onclick="removeDefectRow('${area}')">Remove</button>
                    </td>
                `;
                    tbody.appendChild(row);
                }
            });


        });
    });

    function removeDefectRow(area) {
        const row = document.getElementById('area-row-' + area);
        if (row) row.remove();
        // Unselect cell di matrix jika ada
        const cell = document.querySelector(`.matrix-cell[data-area="${area}"]`);
        if (cell) cell.classList.remove('selected');
    }

    function deleteRow(rowId) {
        const row = document.getElementById('row' + rowId);
        if (row) row.remove();
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cegah submit form saat tekan Enter di input, select, textarea dalam form
        $('.report-form').on('keydown', 'input, select, textarea', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                return false;
            }
        });
    });
</script>

<script>
    // Material Ticket dan Auto-generate Coil Number functionality
    document.addEventListener('DOMContentLoaded', function() {
        let materialTicketRowCounter = 1;

        // Fungsi untuk generate coil_no dari material ticket data
        function generateCoilNumber() {
            const coilNumbers = [];

            // Ambil semua rows material ticket
            const materialTicketRows = document.querySelectorAll(
                'tr:has(select[name="which-side-material[]"])');

            materialTicketRows.forEach(function(row) {
                const whichSide = row.querySelector('select[name="which-side-material[]"]').value;
                const ticketText = row.querySelector('input[name="material_ticket_no_text[]"]').value;
                const ticketR = row.querySelector('select[name="material_ticket_no_r[]"]').value;
                const ticketS = row.querySelector('select[name="material_ticket_no_s[]"]').value;
                const ticketP = row.querySelector('select[name="material_ticket_no_p[]"]').value;

                if (whichSide && ticketText) {
                    // Format: "which-side : material_ticket_no_text-material_ticket_no_r-material_ticket_no_s-material_ticket_no_p"
                    let coilPart = whichSide + ' : ' + ticketText;

                    // Tambahkan R, S, P jika ada
                    if (ticketR) {
                        coilPart += '-' + ticketR;
                    }
                    if (ticketS) {
                        coilPart += '-' + ticketS;
                    }
                    if (ticketP) {
                        coilPart += '-' + ticketP;
                    }

                    coilNumbers.push(coilPart);
                }
            });

            // Update field coil_no
            const coilNoField = document.getElementById('coil_no');
            if (coilNoField) {
                coilNoField.value = coilNumbers.join(' ; ');
            }
        }

        // Event listeners untuk auto-generate coil number
        document.addEventListener('change', function(e) {
            if (e.target.matches('select[name="which-side-material[]"]') ||
                e.target.matches('select[name="material_ticket_no_r[]"]') ||
                e.target.matches('select[name="material_ticket_no_s[]"]') ||
                e.target.matches('select[name="material_ticket_no_p[]"]')) {
                generateCoilNumber();
            }
        });

        document.addEventListener('input', function(e) {
            if (e.target.matches('input[name="material_ticket_no_text[]"]')) {
                generateCoilNumber();
            }
        });

        // Add material ticket row functionality
        document.getElementById('btn-addMaterialTicketNumber').addEventListener('click', function(e) {
            e.preventDefault();

            const table = document.getElementById('tbl-form-input-data-production');
            const newRow = document.createElement('tr');
            newRow.classList.add('material-ticket-row');
            newRow.id = `material-ticket-row-${materialTicketRowCounter}`;

            newRow.innerHTML = `
                <td class="td-right-gen">
                    <label></label>
                </td>
                <th>
                    <select class="which-side-material" name="which-side-material[]" id="which-side-material-${materialTicketRowCounter}">
                        <option value="">--</option>
                        <option value="Single">Single</option>
                        <option value="LH">LH</option>
                        <option value="OTR">OTR</option>
                        <option value="T/G">T/G</option>
                        <option value="">--</option>
                        <option value="RH">RH</option>
                        <option value="INR">INR</option>
                        <option value="S/P">S/P</option>
                        <option value="RNE">RNE</option>
                    </select>
                </th>
                <td>
                    <input type="text" name="material_ticket_no_text[]" class="material_ticket_no_text" id="material_ticket_no_text-${materialTicketRowCounter}"
                        placeholder="...ticket no." required>
                </td>
                <td>
                    <select name="material_ticket_no_r[]" class="general-select" id="material_ticket_no_r-${materialTicketRowCounter}">
                        <option value="">--</option>
                        <option value="R1">R1</option>
                        <option value="R2">R2</option>
                        <option value="R3">R3</option>
                        <option value="R4">R4</option>
                        <option value="R5">R5</option>
                        <option value="R6">R6</option>
                        <option value="R7">R7</option>
                        <option value="R8">R8</option>
                        <option value="R9">R9</option>
                        <option value="R10">R10</option>
                    </select>
                </td>
                <td>
                    <select name="material_ticket_no_s[]" class="general-select" id="material_ticket_no_s-${materialTicketRowCounter}">
                        <option value="">--</option>
                        <option value="S00">S00</option>
                        <option value="S01">S01</option>
                        <option value="S02">S02</option>
                        <option value="S03">S03</option>
                        <option value="S04">S04</option>
                        <option value="S05">S05</option>
                    </select>
                </td>
                <td>
                    <select name="material_ticket_no_p[]" id="material_ticket_no_p-${materialTicketRowCounter}">
                        <option value="">--</option>
                        <option value="P1">P1</option>
                        <option value="P2">P2</option>
                        <option value="P3">P3</option>
                        <option value="P4">P4</option>
                        <option value="P5">P5</option>
                        <option value="P6">P6</option>
                        <option value="P7">P7</option>
                        <option value="P8">P8</option>
                        <option value="P9">P9</option>
                        <option value="P10">P10</option>
                        <option value="P11">P11</option>
                        <option value="P12">P12</option>
                        <option value="P13">P13</option>
                        <option value="P14">P14</option>
                        <option value="P15">P15</option>
                        <option value="P16">P16</option>
                        <option value="P17">P17</option>
                        <option value="P18">P18</option>
                        <option value="P19">P19</option>
                        <option value="P20">P20</option>
                        <option value="P21">P21</option>
                        <option value="P22">P22</option>
                        <option value="P23">P23</option>
                        <option value="P24">P24</option>
                        <option value="P25">P25</option>
                    </select>
                    <button type="button" class="btn-remove-material-ticket" onclick="removeMaterialTicketRow('material-ticket-row-${materialTicketRowCounter}')">
                        <i class="bx bx-trash"></i> Remove
                    </button>
                </td>
            `;

            // Insert sebelum row coil_no
            const coilRow = document.querySelector('tr:has(input[name="coil_no"])');
            coilRow.parentNode.insertBefore(newRow, coilRow);

            materialTicketRowCounter++;
        });

        // Initial generation saat page load
        generateCoilNumber();
    });

    // Fungsi untuk menghapus row material ticket
    function removeMaterialTicketRow(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            row.remove();
            // Re-generate coil number setelah hapus row
            setTimeout(function() {
                const coilNumbers = [];
                const materialTicketRows = document.querySelectorAll(
                    'tr:has(select[name="which-side-material[]"])');

                materialTicketRows.forEach(function(row) {
                    const whichSide = row.querySelector('select[name="which-side-material[]"]').value;
                    const ticketText = row.querySelector('input[name="material_ticket_no_text[]"]')
                        .value;
                    const ticketR = row.querySelector('select[name="material_ticket_no_r[]"]').value;
                    const ticketS = row.querySelector('select[name="material_ticket_no_s[]"]').value;
                    const ticketP = row.querySelector('select[name="material_ticket_no_p[]"]').value;

                    if (whichSide && ticketText) {
                        // Format: "which-side : material_ticket_no_text-material_ticket_no_r-material_ticket_no_s-material_ticket_no_p"
                        let coilPart = whichSide + ' : ' + ticketText;

                        // Tambahkan R, S, P jika ada
                        if (ticketR) {
                            coilPart += '-' + ticketR;
                        }
                        if (ticketS) {
                            coilPart += '-' + ticketS;
                        }
                        if (ticketP) {
                            coilPart += '-' + ticketP;
                        }

                        coilNumbers.push(coilPart);
                    }
                });

                const coilNoField = document.getElementById('coil_no');
                if (coilNoField) {
                    coilNoField.value = coilNumbers.join(' ; ');
                }
            }, 100);
        }
    }
</script>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

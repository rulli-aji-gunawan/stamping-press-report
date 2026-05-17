<x-app-layout>Master Downtime Category</x-app-layout>

<x-master-data-layout></x-master-data-layout>

<section class="home">
    <div class="toggle-sidebar">
        <i class='bx bx-x-circle' id="hide-toggle"></i>
        <i class='bx bx-menu' id="show-toggle"></i>
    </div>
    <div class="container">

        {{-- Add Downtime category button --}}
        <div class="btn-model">
            <button id="btn-addMasterData" onclick="openForm()">Add New DT</button>
        </div>

        {{-- Table of Downtime Category --}}
        <table class="tbl-master-data" id="tbl-master-data-downtime-category">
            <thead>
                <tr>
                    <th id="tbl-downtime-category-head-no">
                        <p>No</p>
                    </th>
                    <th id="tbl-downtime-category-head-downtime-name">
                        <p>Downtime Name</p>
                    </th>
                    <th id="tbl-downtime-category-head-downtime-type">
                        <p>Downtime Type</p>
                    </th>
                    <th id="tbl-downtime-category-head-time">
                        <p>Registered at</p>
                    </th>
                    <th id="tbl-downtime-category-head-time">
                        <p>Updated at</p>
                    </th>
                    <th id="tbl-downtime-category-head-action">
                        <p>Action</p>
                    </th>
                </tr>
            </thead>


            @foreach ($downtime_categories as $index => $downtime_category)
                <tr>
                    <td>
                        <p>{{ $index + 1 }}</p>
                    </td>
                    <td>
                        <p>{{ $downtime_category->downtime_name }}</p>
                    </td>
                    <td>
                        <p>{{ $downtime_category->downtime_type }}</p>
                    </td>
                    <td>
                        <p>{{ \Carbon\Carbon::parse($downtime_category->created_at)->format('Y-m-d | H:i') }}</p>
                    </td>
                    <td>
                        <p>{{ \Carbon\Carbon::parse($downtime_category->updated_at)->format('Y-m-d | H:i') }}</p>
                    </td>
                    <td id="action">
                        <p><button id="btn-editMasterData" class="edit-downtime-category-btn"
                                data_id="{{ $downtime_category->id }}"><i class='bx bx-edit'></i></button>

                            <button class="delete-downtime-category-btn" data_id="{{ $downtime_category->id }}"
                                downtime_name="{{ $downtime_category->downtime_name }}"
                                downtime_type="{{ $downtime_category->downtime_type }}" id="btn-delMasterData"><i
                                    class='bx bx-trash'></i></button>
                        </p>
                    </td>
                </tr>
            @endforeach
        </table>

        {{-- Add Downtime category button --}}
        <div class="btn-model">
            <button id="btn-addMasterData" onclick="openForm()">Add New DT</button>
        </div>

    </div>
</section>



{{-- Add new Downtime Category Popup Form --}}
<div class="form-popup" id="addForm">
    <form id="addDowntimeCategoryForm" class="form-container">
        @csrf
        <div class="addDowntimeCategory">
            <h3>Add Downtime Category</h3>
        </div>
        <span class="close-popup" onclick="closeForm()">close</span>

        <label for="downtime_name">Downtime Name</label>
        <input type="text" onchange="toUpperCase()" placeholder="Input downtime name" name="downtime_name"
            id="downtime_name" required>

        <label for="downtime_type">Downtime Type</label>
        <select name="downtime_type" id="" required>
            <option id="select_downtime_type" value="">Select downtime type</option>
            <option value="Downtime">Down Time</option>
            <option value="Planned Downtime">Planned Down Time</option>
            <option value="Non Productive Time">Non Productive Time</option>
        </select>
        <div class="form-buttons">
            <button type="submit" class="btn-add" onclick="updateDowntimeCategoryRow()">Add</button>
            <button type="button" class="btn-cancel" onclick="closeForm()">Cancel</button>
        </div>
    </form>
</div>


{{-- Edit Downtime Category Popup Form --}}
<div class="form-popup" id="editForm">
    <form action="{{ route('downtime_categories.edit', $downtime_category->id) }}" method="POST"
        class="form-container">
        @csrf
        @method('PUT')
        <div class="editDowntimeCategory">
            <h3>Edit Downtime Category</h3>
        </div>
        <span class="close-popup" onclick="closeForm()">close</span>

        <input type="hidden" id="editDowntimeCategoryId" name="id">

        <label for="downtime_name">Downtime Name</label>
        <input type="text" name="downtime_name" id="editDowntimeCategoryName" required>

        <label for="downtime_type">Downtime Type</label>
        <select name="downtime_type" id="editDowntimeCategoryType" required>
            <option value="{{ $downtime_category->downtime_type }}">
                {{ $downtime_category->downtime_type }}
            </option>
            <option value="Downtime">Down Time</option>
            <option value="Planned Downtime">Planned Down Time</option>
            <option value="Non Productive Time">Non Productive Time</option>
        </select>

        <div class="form-buttons">
            <button type="submit" class="btn-update" onclick="updateDowntimeCategoryRow()">Update</button>
            <button type="button" class="btn-cancel" onclick="closeForm()">Cancel</button>
        </div>
    </form>
</div>

<script src="../js/sidebar.js"></script>
<script src="../js/edit-downtime-category.js"></script>
<script src="../js/delete-downtime-category.js"></script>

<script>
    var addDowntimeCategoryUrl = '{{ route('downtime_categories.add') }}';
</script>
<script>
    var getDowntimeCategoryUrl = '{{ route('downtime_categories.getAll') }}';
</script>
<script src="../js/add-downtime-category.js"></script>

<x-app-layout>Master Data Process Name</x-app-layout>

<x-master-data-layout></x-master-data-layout>

<section class="home">
    <div class="toggle-sidebar">
        <i class='bx bx-x-circle' id="hide-toggle"></i>
        <i class='bx bx-menu' id="show-toggle"></i>
    </div>
    <div class="container">

        {{-- Add Process Name button --}}
        <div class="btn-model">
            <button id="btn-addMasterData" onclick="openForm()">✛ Add Process Name</button>
        </div>

        {{-- Table of Process Name --}}
        <table class="tbl-master-data" id="tbl-master-data-process-name">
            <thead>
                <tr>
                    <th id="tbl-process-name-head-no">
                        <p>No</p>
                    </th>
                    <th id="tbl-process-name-head-process-name">
                        <p>Process Name</p>
                    </th>
                    <th id="tbl-process-name-head-time">
                        <p>Registered at</p>
                    </th>
                    <th id="tbl-process-name-head-time">
                        <p>Updated at</p>
                    </th>
                    <th id="tbl-process-name-head-action">
                        <p>Action</p>
                    </th>
                </tr>
            </thead>


            @foreach ($process_names as $index => $process_name)
                <tr>
                    <td>
                        <p>{{ $index + 1 }}</p>
                    </td>
                    <td>
                        <p>{{ $process_name->process_name }}</p>
                    </td>
                    <td>
                        <p>{{ \Carbon\Carbon::parse($process_name->created_at)->format('Y-m-d | H:i') }}</p>
                    </td>
                    <td>
                        <p>{{ \Carbon\Carbon::parse($process_name->updated_at)->format('Y-m-d | H:i') }}</p>
                    </td>
                    <td id="action">
                        <p>
                            <button id="btn-editMasterData" class="edit-process-name-btn"
                                data_id="{{ $process_name->id }}" process_name="{{ $process_name->process_name }}"><i
                                    class='bx bx-edit'></i></button>

                            <button class="delete-process-name-btn" data_id="{{ $process_name->id }}"
                                process_name="{{ $process_name->process_name }}" id="btn-delMasterData"><i
                                    class='bx bx-trash'></i></button>
                        </p>
                    </td>
                </tr>
            @endforeach
        </table>

        {{-- Add Process Name button --}}
        <div class="btn-model">
            <button id="btn-addMasterData" onclick="openForm()">✛ Add Process Name</button>
        </div>

    </div>
</section>



{{-- Add new Process Name Popup Form --}}
<div class="form-popup" id="addForm">
    <form id="addProcessNameForm" class="form-container">
        @csrf
        <div class="addProcessname">
            <h3>Add Process Name</h3>
        </div>
        <span class="close-popup" onclick="closeForm()">ⓧ</span>

        <label for="process_name">Process Name</label>
        <input type="text" placeholder="Input process name" name="process_name" id="process_name" required>

        <div class="form-buttons">
            <button type="submit" class="btn-add" onclick="updateProcessNameRow()">Add</button>
            <button type="button" class="btn-cancel" onclick="closeForm()">Cancel</button>
        </div>

    </form>
</div>


{{-- Edit Process Name Popup Form --}}
<div class="form-popup" id="editForm">
    {{-- <form action="{{ route('process.edit', $process_name->id) }}" method="POST" class="form-container"> --}}
    <form action="{{ route('process.edit', ':id') }}" method="POST" class="form-container" id="editFormElement">
        @csrf
        @method('PUT')
        <div class="editProcessName">
            <h3>Edit Process Name</h3>
        </div>
        <span class="close-popup" onclick="closeForm()">close</span>

        <input type="hidden" id="editProcessNameId" name="id">

        <label for="process_name">Process Name</label>
        <input type="text" name="process_name" id="editProcessName" required>

        <div class="form-buttons">
            <button type="submit" class="btn-update" onclick="updateProcessNameRow()">Update</button>
            <button type="button" class="btn-cancel" onclick="closeForm()">Cancel</button>
        </div>
    </form>
</div>

<script src="../js/sidebar.js"></script>
<script src="../js/edit-process-name.js"></script>
<script src="../js/delete-process-name.js"></script>

<script>
    var addProcessNameUrl = '{{ route('process.add') }}';
</script>
<script>
    var getProcessNameUrl = '{{ route('process.getAll') }}';
</script>
<script src="../js/add-process-name.js"></script>

<x-app-layout>Master Data Downtime Classification</x-app-layout>

<x-master-data-layout></x-master-data-layout>

<section class="home">
    <div class="toggle-sidebar">
        <i class='bx bx-x-circle' id="hide-toggle"></i>
        <i class='bx bx-menu' id="show-toggle"></i>
    </div>
    <div class="container">

        {{-- Add Downtime Classification button --}}
        <div class="btn-model">
            <button id="btn-addMasterData" onclick="openForm()">Add</button>
        </div>

        {{-- Table of Downtime Classification --}}
        <table class="tbl-master-data" id="tbl-master-data-downtime-classification">
            <thead>
                <tr>
                    <th id="tbl-downtime-classification-head-no">
                        <p>No</p>
                    </th>
                    <th id="tbl-downtime-classification-head-downtime-classification">
                        <p>Downtime Classification</p>
                    </th>
                    <th id="tbl-downtime-classification-head-time">
                        <p>Registered at</p>
                    </th>
                    <th id="tbl-downtime-classification-head-time">
                        <p>Updated at</p>
                    </th>
                    <th id="tbl-downtime-classification-head-action">
                        <p>Action</p>
                    </th>
                </tr>
            </thead>


            @foreach ($dt_classifications as $index => $dt_classification)
                <tr>
                    <td>
                        <p>{{ $index + 1 }}</p>
                    </td>
                    <td>
                        <p>{{ $dt_classification->downtime_classification }}</p>
                    </td>
                    <td>
                        <p>{{ \Carbon\Carbon::parse($dt_classification->created_at)->format('Y-m-d | H:i') }}</p>
                    </td>
                    <td>
                        <p>{{ \Carbon\Carbon::parse($dt_classification->updated_at)->format('Y-m-d | H:i') }}</p>
                    </td>
                    <td id="action">
                        <p><button id="btn-editMasterData" class="edit-downtime-classification-btn"
                                data_id="{{ $dt_classification->id }}"
                                dt_classification="{{ $dt_classification->dt_classification }}"><i
                                    class='bx bx-edit'></i></button>

                            <button class="delete-downtime-classification-btn" data_id="{{ $dt_classification->id }}"
                                dt_classification="{{ $dt_classification->dt_classification }}"
                                dt_classification_name="{{ $dt_classification->downtime_classification }}"
                                id="btn-delMasterData"><i class='bx bx-trash'></i></button>
                        </p>
                    </td>
                </tr>
            @endforeach
        </table>

        {{-- Add Downtime Classification button --}}
        <div class="btn-model">
            <button id="btn-addMasterData" onclick="openForm()">Add</button>
        </div>

    </div>
</section>



{{-- Add new Downtime Classification Popup Form --}}
<div class="form-popup" id="addForm">
    <form id="addDowntimeClassificationForm" class="form-container">
        @csrf
        <div class="addDowntimeClassification">
            <h3>Add Downtime Classification</h3>
        </div>
        <span class="close-popup" onclick="closePopup()">close</span>

        <label for="dt_classification">Downtime Classification</label>
        <input type="text" placeholder="Input Downtime Classification" name="downtime_classification"
            id="dt_classification" required>

        <div class="form-buttons">
            <button type="submit" class="btn-add" onclick="updateDowntimeClassificationRow()">Add</button>
            <button type="button" class="btn-cancel" onclick="closePopup()">Cancel</button>
        </div>

    </form>
</div>


{{-- Edit Downtime Classification Popup Form --}}
<div class="form-popup" id="editForm">
    {{-- <form action="{{ route('dt_classifications.edit', $dt_classifications->id) }}" method="POST" class="form-container"> --}}
    <form action="{{ route('dt_classifications.edit', ':id') }}" method="POST" class="form-container"
        id="editFormElement">
        @csrf
        @method('PUT')
        <div class="editDowntimeClassification">
            <h3>Edit Downtime Classification</h3>
        </div>
        <span class="close-popup" onclick="closePopup()">close</span>

        <input type="hidden" id="editDowntimeClassificationId" name="id">

        <label for="downtime_classification">Downtime Classification</label>
        <input type="text" name="downtime_classification" id="editDowntimeClassification" required>


        <div class="form-buttons">
            <button type="submit" class="btn-update" onclick="updateDowntimeClassificationRow()">Update</button>
            <button type="button" class="btn-cancel" onclick="closeForm()">Cancel</button>
        </div>

    </form>
</div>

<script src="../js/sidebar.js"></script>
<script src="../js/add-downtime-classification.js"></script>
<script src="../js/edit-downtime-classification.js"></script>
<script src="../js/delete-downtime-classification.js"></script>

<script>
    var addDowntimeClassificationUrl = '{{ route('dt_classifications.add') }}';
</script>
<script>
    var getDowntimeClassificationUrl = '{{ route('dt_classifications.getAll') }}';
</script>

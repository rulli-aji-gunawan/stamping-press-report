<x-app-layout>Master Data Model</x-app-layout>

<x-master-data-layout></x-master-data-layout>

<section class="home">
    <div class="toggle-sidebar">
        <i class='bx bx-x-circle' id="hide-toggle"></i>
        <i class='bx bx-menu' id="show-toggle"></i>
    </div>
    <div class="container">

        {{-- Add model button --}}
        <div class="btn-model">
            <button id="btn-addMasterData" onclick="openForm()">Add New Model</button>
        </div>

        {{-- Table of User --}}
        <table class="tbl-master-data" id="tbl-master-data-model-item">
            <thead>
                <tr>
                    <th id="tbl-model-head-no">
                        <p>No</p>
                    </th>
                    <th id="tbl-model-head-model-code">
                        <p>Model Code</p>
                    </th>
                    <th id="tbl-model-head-model-year">
                        <p>Model Year</p>
                    </th>
                    <th id="tbl-model-head-item-name">
                        <p>Item Name</p>
                    </th>
                    <th id="tbl-model-head-item-picture">
                        <p>Panel Sketch</p>
                    </th>
                    <th id="tbl-model-head-time">
                        <p>Registered at</p>
                    </th>
                    <th id="tbl-model-head-time">
                        <p>Updated at</p>
                    </th>
                    <th id="tbl-model-head-action">
                        <p>Action</p>
                    </th>
                </tr>
            </thead>


            @foreach ($model_items as $index => $model_item)
                <tr>
                    <td>
                        <p>{{ $index + 1 }}</p>
                    </td>
                    <td>
                        <p>{{ $model_item->model_code }}</p>
                    </td>
                    <td>
                        <p>{{ $model_item->model_year }}</p>
                    </td>
                    <td>
                        <p>{{ $model_item->item_name }}</p>
                    </td>
                    <td>
                        <p>{{ $model_item->product_picture }}</p>
                    </td>
                    <td>
                        <p>{{ \Carbon\Carbon::parse($model_item->created_at)->format('Y-m-d | H:i') }}</p>
                    </td>
                    <td>
                        <p>{{ \Carbon\Carbon::parse($model_item->updated_at)->format('Y-m-d | H:i') }}</p>
                    </td>
                    <td id="action">
                        <p>
                            <button id="btn-editMasterData" class="edit-model-btn" data_id="{{ $model_item->id }}"><i
                                    class='bx bx-edit'></i></button>

                            <button class="delete-model-btn" data_id="{{ $model_item->id }}"
                                model_code="{{ $model_item->model_code }}" item_name="{{ $model_item->item_name }}"
                                id="btn-delMasterData"><i class='bx bx-trash'></i></button>
                        </p>
                    </td>
                </tr>
                {{-- <tbody>
                </tbody> --}}
            @endforeach
        </table>

        {{-- Add model button --}}
        <div class="btn-model">
            <button id="btn-addMasterData" onclick="openForm()">Add New Model</button>
        </div>

    </div>
</section>



{{-- Add Model Item Popup Form --}}
<div class="form-popup" id="addForm">
    {{-- <form id="addModelItemForm" action="{{ route('models.add') }}" method="POST" class="form-container"> --}}
    <form id="addModelItemForm" class="form-container" enctype="multipart/form-data">
        @csrf
        <div class="addModelItem">
            <h3>Add Model and Item</h3>
        </div>
        <span class="close-popup" onclick="closeForm()">close</span>

        <label for="model_code">Model Code</label>
        <input type="text" onchange="toUpperCase()" placeholder="Input model code" name="model_code" id="model_code"
            required>

        <label for="model_year">Model Year</label>
        <input type="text" placeholder="Input model year" name="model_year" id="model_year" required>

        <label for="item_name">Item Name</label>
        <input type="text" onchange="toUpperCase()" placeholder="Input item name" name="item_name" id="item_name"
            required>

        <label for="product_picture">Panel Sketch</label>
        <input type="file" name="product_picture" id="product_picture" accept="image/*" required>

        <div class="form-buttons">
            <button type="submit" class="btn-add">Add</button>
            <button type="button" class="btn-cancel" onclick="closeForm()">Cancel</button>
        </div>
    </form>
</div>


{{-- Edit Model Popup Form --}}
<div class="form-popup" id="editForm">
    <form action="{{ route('models.edit', $model_item->id) }}" method="POST" class="form-container">
        @csrf
        @method('PUT')
        <div class="addModel">
            <h3>Edit Model</h3>
        </div>
        <span class="close-popup" onclick="closeForm()">close</span>

        <input type="hidden" id="editModelId" name="id">

        <label for="model_code">Model Code</label>
        <input type="text" name="model_code" id="editModelCode" required>

        <label for="model_year">Model Year</label>
        <input type="text" name="model_year" id="editModelYear" required>

        <label for="item_name">Item Name</label>
        <input type="text" name="item_name" id="editItemName" required>

        <label for="editProductPicture">Panel Sketch (upload ulang jika perlu)</label>
        <div id="currentPictureDisplay" style="display:none; margin-bottom:6px;">
            <small>File saat ini: <span id="currentPictureName"></span></small>
        </div>
        <input type="file" name="product_picture" id="editProductPicture" accept="image/*">

        <div class="form-buttons">
            <button type="submit" class="btn-update">Update</button>
            <button type="button" class="btn-cancel" onclick="closeForm()">Cancel</button>
        </div>
    </form>
</div>

<script src="../js/sidebar.js"></script>
<script src="../js/edit-model-item.js"></script>
<script src="../js/delete-model-item.js"></script>

<script>
    var addModelItemUrl = '{{ route('models.add') }}';
</script>
<script>
    var getModelItemUrl = '{{ route('models.getAll') }}';
</script>
<script src="../js/add-model-item.js"></script>

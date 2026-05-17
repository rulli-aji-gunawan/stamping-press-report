<x-app-layout>Master Data User</x-app-layout>

<x-master-data-layout></x-master-data-layout>

<section class="home">
    <div class="toggle-sidebar">
        <i class='bx bx-x-circle' id="hide-toggle"></i>
        <i class='bx bx-menu' id="show-toggle"></i>
    </div>
    <div class="container">

        {{-- Add user button --}}
        <div class="btn-user">
            <button id="btn-addMasterData" onclick="openForm()">Add New User</button>
        </div>

        {{-- Table of User --}}
        <table class="tbl-master-data" id="tbl-master-data-user">
            <tr>
                <th id="tbl-user-head-no">
                    <p>No</p>
                </th>
                <th id="tbl-user-head-name">
                    <p>User Name</p>
                </th>
                <th id="tbl-user-head-email">
                    <p>Email Address</p>
                </th>
                <th id="tbl-user-head-admin">
                    <p>Is Admin</p>
                </th>
                <th id="tbl-user-head-time">
                    <p>Registered at</p>
                </th>
                <th id="tbl-user-head-action">
                    <p>Action</p>
                </th>
            </tr>

            @foreach ($users as $index => $user)
                <tr>
                    <td>
                        <p>{{ $index + 1 }}</p>
                    </td>
                    <td>
                        <p>{{ $user->name }}</p>
                    </td>
                    <td>
                        <p>{{ $user->email }}</p>
                    </td>
                    <td>
                        <p>{{ $user->is_admin }}</p>
                    </td>
                    <td>
                        <p>{{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d | H:i') }}</p>
                    </td>
                    <td id="action">
                        <p>
                            <button id="btn-editMasterData" class="edit-user-btn" data-id="{{ $user->id }}"><i
                                    class='bx bx-edit'></i></button>

                            <button class="delete-user-btn" data-id="{{ $user->id }}"
                                user-name="{{ $user->name }}" id="btn-delMasterData"><i
                                    class='bx bx-trash'></i></button>
                        </p>
                    </td>
                </tr>
            @endforeach
        </table>

        {{-- Add user button --}}
        {{-- <div class="btn-user">
            <button id="btn-addMasterData" onclick="openForm()">Add New User</button>
        </div> --}}
    </div>
</section>

{{-- Add User Popup Form --}}
<div class="form-popup" id="addForm">
    <form action="{{ route('users.add') }}" method="POST" class="form-container">
        @csrf
        <div class="addUser">
            <h3>Add User</h3>
        </div>
        <span class="close-popup" onclick="closeForm()">close</span>

        <label for="name">Username</label>
        <input type="text" placeholder="Input name" name="name" id="name" required>

        <label for="email">Email</label>
        <input type="email" placeholder="Input email" name="email" id="email" required>

        <label for="password">Password</label>
        <input type="password" placeholder="Input password" name="password" id="password" required>

        <label for="is_admin">Authorized as</label>
        <select name="is_admin" id="select-author" id="is_admin" required>
            <option id="select" value="">Select</option>
            <option value="0">User</option>
            <option value="1">Admin</option>
        </select>
        <div class="form-buttons">
            <button type="submit" class="btn-add">Add</button>
            <button type="button" class="btn-cancel" onclick="closeForm()">Cancel</button>
        </div>
    </form>
</div>


{{-- Edit User Popup Form --}}
<div class="form-popup" id="editForm">
    <form action="{{ route('users.edit', $user->id) }}" method="POST" class="form-container">
        @csrf
        @method('PUT')
        <div class="addUser">
            <h3>Edit User</h3>
        </div>
        <span class="close-popup" onclick="closeForm()">close</span>

        <input type="hidden" id="editUserId" name="id">

        <label for="name">Username</label>
        <input type="text" name="name" id="editName" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="editEmail" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="editPassword" required>

        <label for="is_admin">Authorized as</label>
        <select name="is_admin" id="editAdmin" required>
            <option value="">
                {{ $user->is_admin ? 'Admin' : 'User' }}
            </option>
            <option value="0">User</option>
            <option value="1">Admin</option>
        </select>

        <div class="form-buttons">
            <button type="submit" class="btn-update" onclick="updateUserRow()">Update</button>
            <button type="button" class="btn-cancel" onclick="closeForm()">Cancel</button>
        </div>
    </form>
</div>

<script src="../js/sidebar.js"></script>
<script src="../js/add-user.js"></script>
<script src="../js/edit-user.js"></script>
<script src="../js/delete-user.js"></script>

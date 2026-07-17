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
                <th id="tbl-user-head-role">
                    <p>Role</p>
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
                        <p>{{ $user->role }}</p>
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

        @if ($errors->any())
            <div
                style="background:#fdecea;border:1px solid #e57373;border-radius:4px;padding:8px 12px;margin-bottom:10px;font-size:0.8rem;color:#c0392b;">
                <ul style="margin:0;padding-left:16px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <label for="name">Username</label>
        <input type="text" placeholder="Input name" name="name" id="name" value="{{ old('name') }}"
            required>

        <label for="email">Email</label>
        <input type="email" placeholder="Input email" name="email" id="email" value="{{ old('email') }}"
            required>

        <label for="password">Password</label>
        <input type="password" placeholder="Input password" name="password" id="password" required>

        <label for="is_admin">Authorized as</label>
        <select name="is_admin" id="is_admin" required>
            <option value="">Select</option>
            <option value="0" {{ old('is_admin') === '0' ? 'selected' : '' }}>User</option>
            <option value="1" {{ old('is_admin') === '1' ? 'selected' : '' }}>Admin</option>
        </select>

        <label for="role">Role</label>
        <select name="role" class="form-control" required id="role">
            <option value="">Select</option>
            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="foreman" {{ old('role') === 'foreman' ? 'selected' : '' }}>Foreman</option>
            <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
            <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
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
        <select name="role" class="form-control" required id="editRole">
            <option value="admin">Admin</option>
            <option value="foreman">Foreman</option>
            <option value="staff">Staff</option>
            <option value="manager">Manager</option>
        </select>


        <div class="form-buttons">
            <button type="submit" class="btn-update" onclick="updateUserRow()">Update</button>
            <button type="button" class="btn-cancel" onclick="closeForm()">Cancel</button>
        </div>
    </form>
</div>

<script src="../js/sidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/add-user.js"></script>
<script src="../js/edit-user.js"></script>
<script src="../js/delete-user.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#1c6d3f',
                timer: 3000,
                timerProgressBar: true,
            });
        @endif

        @if ($errors->any())
            openForm();
            Swal.fire({
                title: 'Gagal!',
                html: '<ul style="text-align:left;padding-left:16px;margin:0">' +
                    '@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach' +
                    '</ul>',
                icon: 'error',
                confirmButtonText: 'Tutup & Perbaiki',
                confirmButtonColor: '#e24a64',
            });
        @endif

    });
</script>

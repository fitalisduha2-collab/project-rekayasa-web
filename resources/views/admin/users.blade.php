@extends('admin.template')
@section('content')
<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3 py-3">
             <h3>Data Users</h3>
            <header class="justify-content-end">
<button class="btn btn-primary" id="btnAddUser">Tambah User</button>
</header>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered" id="table_users">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="UserModal" tabindex="-1" aria-labelledby="UserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="userForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="UserModalLabel">Modal title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @csrf
          <input type="hidden" name="id" id="id">
          <div class="form-group mb-3">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="form-group mb-3">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="form-group mb-3">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="form-group mb-3">
              <label for="phone">Phone</label>
              <input type="text" class="form-control" id="phone" name="phone" required>
          </div>
          <div class="form-group mb-3">
              <label for="address">Address</label>
              <textarea class="form-control" id="address" name="address" required></textarea>
          </div>
          <div class="form-group mb-3">
              <label for="role">Role</label>
              <select class="form-select" id="role" name="role" required>
                  <option value="">Pilih Role</option>
                  <option value="admin">Admin</option>
                  <option value="user">User</option>
              </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        $('#table_users').DataTable({
            ajax: {
                url: '{{ route("list-users") }}',
                dataSrc: "data",
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + API_TOKEN
                }
            },
            columns: [
                { data: null, render: function(data, type, row, meta) {
                    return meta.row + 1; // Menampilkan nomor urut
                }},
                { data: 'name'},
                { data: 'email'},
                { data: 'phone'},
                { data: 'address'},
                { data: 'role'},
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-sm btn-warning btnEditUser" data-id="${row.id}">Edit</button>
                            <button class="btn btn-sm btn-danger btnDeleteUser" data-id="${row.id}">Hapus</button>
                        `;
                    }
                }
            ],
            initComplete: function(settings, json){
                if(json.message) {
                    toastr.success(json.message);
                }

            },
            error: function(xhr, error, thrown) {
                toastr.error('Terjadi kesalahan saat memuat data users.');
            }
        });

        $('#btnAddUser').click(function(){
            $('#userForm')[0].reset();
            $('#id').val('');
            $('#UserModalLabel').text('Tambah User');
            $('#UserModal').modal('show');
        })

        $('#userForm').submit(function(e){
            e.preventDefault();
            let id = $('#id').val();
            let url = id ? `/api/users/${id}` : '/api/users';
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                headers: {
                    'Authorization': 'Bearer ' + API_TOKEN
                },
                success: function(response) {
                    $('#UserModal').modal('hide');
                    $('#table_users').DataTable().ajax.reload();
                    toastr.success(response.message);
                },
                error: function(xhr, status, error) {
                    toastr.error('Terjadi kesalahan saat menyimpan data user.');
                }
            });
        });

            //edit
            $('#table_users').on('click', '.btnEditUser', function(){
                let id = $(this).data('id');
                $.ajax({
                    url: `/api/users/${id}`,
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + API_TOKEN
                    },
                    success: function(response) {
                        $('#id').val(response.data.id);
                        $('#name').val(response.data.name);
                        $('#email').val(response.data.email);
                        $('#phone').val(response.data.phone);
                        $('#address').val(response.data.address);
                        $('#role').val(response.data.role);
                        $('#UserModalLabel').text('Edit User');
                        $('#UserModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Terjadi kesalahan saat mengambil data user.');
                    }
                });
            });

        //delete
        $('#table_users').on('click', '.btnDeleteUser', function(){
            let id = $(this).data('id');
            if(confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                $.ajax({
                    url: `/api/users/${id}`,
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + API_TOKEN
                    },
                    success: function(response) {
                        $('#table_users').DataTable().ajax.reload();
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Terjadi kesalahan saat menghapus data user.');
                    }
                });
            }
        });
    });
</script>
@endsection
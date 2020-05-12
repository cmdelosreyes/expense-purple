@extends('layouts.adminlte-default')

@section('title')
    Roles
@stop

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h1 class="m-0 text-dark">Roles</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">User Management</a></li>
                    <li class="breadcrumb-item active"><a href="#">Roles</a></li>
                </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
              <div class="card-body">
                <table id="table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">Display Name</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr data-id="{{ $role->id }}">
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->description }}</td>
                                <td>{{ $role->created_at->format('F d, Y - h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
              </div>
              <div class="card-footer">
                <div class="row">
                    <div class="col-sm-1 offset-sm-11">
                        <button id="btnAdd" class="btn btn-sm btn-primary btn-block">Add more</button>
                    </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <form method="POST" action='{{ route('roles.update') }}' class="modal-content">
                @csrf
                @method('PATCH')
              <div class="overlay d-flex justify-content-center align-items-center">
                  <i class="fas fa-2x fa-sync fa-spin"></i>
              </div>
              <div class="modal-header">
                <h4 class="modal-title">Role Edit</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Role Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" id="description" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
              <div class="modal-footer justify-content-between">
                <button id="btnDelete" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
            <!-- /.modal-content -->
          </div>
    </div>
    <!-- End Edit Modal -->

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <form method="POST" action='{{ route('roles.destroy') }}' class="modal-content">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                <h4 class="modal-title">Role Delete</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <blockquote>
                        Are you sure you want to delete this Role?
                    </blockquote>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Yes</button>
                </div>
            </form>
            <!-- /.modal-content -->
            </div>
    </div>
    <!-- End Delete Modal -->

    <!-- Add Modal -->
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <form method="POST" action='{{ route('roles.add') }}' class="modal-content">
                @csrf
                @method('POST')
              <div class="modal-header">
                <h4 class="modal-title">Role Add</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Role Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" id="description" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
              </div>
            </form>
            <!-- /.modal-content -->
          </div>
    </div>
    <!-- End Add Modal -->
@stop

@section('js')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript">

    const modal_overlay = '<div class="overlay d-flex justify-content-center align-items-center"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $("#table").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });

    $("#btnAdd").click(function(e){
        e.preventDefault();
        $("#addModal").modal('show');
    });

    $("#btnDelete").click(function(e){
        $("#deleteModal #id").val($("#editModal #id").val());
        $("#deleteModal").modal('show');
    });

    $("#table tbody").on('click', 'tr', function(e){
        e.preventDefault();
        loadRoleData($(this).data('id'));
    });

    function loadRoleData(id)
    {
        $.ajax({
                url: "{{ route('roles.api') }}",
                method: 'POST',
                data: {
                    'id': id
                },
                beforeSend: function(){
                    $("#editModal").modal('show');
                    $("#editModal .modal-content").prepend(modal_overlay);
                },
                success: function(data){
                    $("#editModal .modal-content").find(".overlay").remove();
                    $("#editModal #id").val(data['id']);
                    $("#editModal #name").val(data['name']);
                    $("#editModal #description").val(data['description']);
                },
                error: function(){

                },
                complete: function(){

                },
            });
    }
</script>
@endsection



@extends('layouts.admin')

@section('title')
Menu
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3">Manage Data Menu</h3>
        <div class="row ml-auto p-2">
            <div class="col-md-12">
                @if (Auth::user()->id_user_level == 1)
                <button type="button" class="btn btn-secondary btn-sm btn-add">
                    <i class="fa fa-plus"></i> Add Data
                </button>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-sm table-striped" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Type</th>
                            <th>Parent</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="modal-form">
    <div class="modal-dialog">
        <form id="form" method="post">
            @csrf
            <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name Menu</label>
                        <input type="text" name="name" id="name" class="form-control form-control-sm" required
                            placeholder="Name Menu">
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control form-control-sm" required
                            placeholder="Slug Menu">
                    </div>
                    <div class="form-group">
                        <label for="name">Type</label>
                        <select name="type" id="type" class="form-control form-control-sm select2" style="width: 100%"
                            required>
                            <option value=""></option>
                            <option value="parent">Parent</option>
                            <option value="child">Children</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Parent</label>
                        <select name="id_parent" id="id_parent" class="form-control form-control-sm select2"
                            style="width: 100%" required>
                            <option value=""></option>
                            @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- End Modal --}}
@endsection

@push('js')
<script>
    $(document).ready(function(){
        const url_add = "{{ route('menu.store') }}";
        const url_edit = "{{ route('menu.update') }}";
        const url_delete = "{{ route('menu.destroy') }}"
        const table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax : {
                url : "{{ route('menu.data') }}",
                type : "GET",
            },
            lengthMenu: [ [10, 25, 50], [10, 25, 50] ],
            columns : [
                {
                    data : 'id',
                },
                {
                    data : 'name'
                },
                {
                    data : 'slug'
                },
                {
                    data : 'type'
                },
                {
                    data : 'parent',
                    render : function(data, type, row){
                        if(data){
                            return data.name;
                        }else{
                            return ''
                        }
                    }
                },
                {
                    data : 'id',
                    render : function(data, type, row){
                        const btn_edit = '<button type="button" class="btn btn-primary btn-sm btn-edit mr-md-2"><i class="fa fa-save"></i> Edit</button>';
                        const btn_delete = '<button type="button" class="btn btn-danger btn-sm btn-delete"><i class="fa fa-trash"></i> Delete</button>';
                        return btn_edit+btn_delete;
                    },
                    width : '150px',
                }
            ],
        });

        //Initialize Select2 Elements
        $('select[name=type]').select2({
            placeholder : 'Choose Type'
        })

        $('select[name=id_parent]').select2({
            placeholder : 'Choose Parent'
        })

        $('select[name=type]').on('change', function(){
            const val = $(this).val();
            val=='child'?$('select[name=id_parent]').prop('disabled',false):$('select[name=id_parent]').prop('disabled',true);
        })

        //add triger click
        $('button.btn-add').on('click', function(){
            //set modal title
            $('#modal-form h4.modal-title').html('Add Menu');
            //clear id field
            $('#modal-form input[name=id]').val('');
            //show modal
            $('#modal-form').modal('show');
        })

        //update trigger click
        $('#table tbody').on('click','button.btn-edit', function(){
            const data = table.row($(this).parents('tr')).data();
            //set modal title
            $('#modal-form h4.modal-title').html('Edit Menu');
            //set data
            $('#modal-form input[name=id]').val(data.id);
            $('#modal-form input[name=name]').val(data.name);
            $('#modal-form input[name=slug]').val(data.slug);
            $('#modal-form select[name=type]').val(data.type).change();
            $('#modal-form select[name=id_parent]').val(data.id_parent).change();
            //show modal
            $('#modal-form').modal('show');
        });

        //save function
        $('#form').on('submit', function(e){
            e.preventDefault();
            //get form data
            const id = $('#form input[name=id]').val();
            const formData = new FormData($(this)[0]);
            //prompt
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to save this data.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!',
                focusConfirm:true
            }).then((result)=>{
                if(result.value){
                    //submit data
                    $.ajax({
                        url : id==''?url_add:url_edit,
                        type : "POST",
                        data : formData,
                        contentType: false,
                        processData: false,
                        dataType : 'JSON',
                        beforeSend : function(){
                            Swal.fire({
                                title: 'Please Wait',
                                text: 'Fetching data...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });
                        },
                        success : function(result){
                            if(result.code == 200){
                                //show alert
                                Swal.fire({
                                    title : 'Success',
                                    html : result.message ?? 'Save data success',
                                    icon : 'success'
                                });
                                //clear field
                                $('#modal-form input[name=id]').val('');
                                $('#modal-form input[name=name]').val('');
                                //close modal
                                $('#modal-form').modal('hide');
                                //refresh table
                                table.ajax.reload();
                            }else{
                                Swal.fire({
                                    title : 'Error',
                                    html : result.message??'Save data failed',
                                    icon : 'error'
                                });
                            }
                        },
                        error : function(xhr){
                            const error = xhr.responseJSON;
                            const message = error == undefined ? 'Save data failed' : error.message;
                            Swal.fire({
                                title : 'Error',
                                html : message,
                                icon : 'error'
                            });
                        }
                    })
                }
            })
        });

        //delete trigger click
        $('#table tbody').on('click','button.btn-delete', function(){
            const data = table.row($(this).parents('tr')).data();
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this data.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                focusConfirm:true
            }).then((result)=>{
                if(result.value){
                    //submit data
                    $.ajax({
                        url : url_delete,
                        type : "POST",
                        data : {
                            _token : token,
                            id : data.id,
                        },
                        dataType : 'JSON',
                        beforeSend : function(){
                            Swal.fire({
                                title: 'Please Wait',
                                text: 'Fetching data...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });
                        },
                        success : function(result){
                            Swal.close();
                            if(result.code == 200){
                                //show alert
                                Swal.fire({
                                    title : 'Success',
                                    html : result.message ?? 'Delete data success',
                                    icon : 'success'
                                });
                                //refresh table
                                table.ajax.reload();
                            }else{
                                Swal.fire({
                                    title : 'Error',
                                    html : result.message??'Save data failed',
                                    icon : 'error'
                                });
                            }
                        },
                        error : function(xhr){
                            Swal.close();
                            const error = xhr.responseJSON;
                            const message = error == undefined ? 'Save data failed' : error.message;
                            Swal.fire({
                                title : 'Error',
                                html : message,
                                icon : 'error'
                            });
                        }
                    })
                }
            })
        })
    })
</script>
@endpush
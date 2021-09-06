@extends('app')
@section('style')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" />
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" rel="stylesheet">

@endsection
@section('content')
<table class="table table-bordered yajra-datatable">
    <button type="button" id="delete-records" class="btn btn-danger">Delete All</button>
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="checkAllCheckbox">
            </th>
            <th>No</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Address</th>
            <th>Salary</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<!-- Modal -->
<div class="modal fade" id="yajraEditModelForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="yajra-form-submit" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="edit-form-first-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit-form-name" placeholder="Name" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="edit-form-last-name" class="form-label">Designation</label>
                        <input type="text" class="form-control" id="edit-form-designation" placeholder="Designation" name="designation">
                    </div>
                    <div class="mb-3">
                        <label for="edit-form-last-name" class="form-label">Salary</label>
                        <input type="text" class="form-control" id="edit-form-salary" placeholder="Salary" name="salary">
                    </div>
                    <div class="mb-3">
                        <label for="edit-form-address" class="form-label">Address</label>
                        <textarea class="form-control" name="address" id="edit-form-address" rows="3"></textarea>
                    </div>
                    <input type="hidden" id="edit-form-id">
                    <button type="submit" class="btn btn-secondary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>


<script type="text/javascript">
    $(function() {
        var yajraModal = new bootstrap.Modal(document.getElementById('yajraEditModelForm'));

        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('employee.list')}}",
            columns: [{
                    "orderable": false,
                    "data": null,
                    "defaultContent": ''
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'designation',
                    name: 'designation'
                },
                {
                    data: 'address',
                    name: 'address'
                },
                {
                    data: 'salary',
                    name: 'salary'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ],
            columnDefs: [{
                orderable: false,
                className: 'select-checkbox',
                targets: 0,
                checkboxes: {
                    'selectRow': true
                },
                createdCell: function(row, data, index) {
                    $(row).find('td:eq(1)').attr('data-id', '3');
                }
            }],
            select: {
                style: 'os',
                selector: 'td:nth-child(1)'
            },
            pagingType: "full_numbers",
            rowId: 'id'
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //* Delete single record 
        $('.yajra-datatable').on('click', '#yajraDelete', function(e) {
            let row = table.row($(this).parent().parent()).data();
            if (confirm('Are you sure to Delete this Record'))
                deleteRow(e, row.id);
        });

        function deleteRow(e, id) {
            e.preventDefault();
            let url = "{{route('employee.destroy', ['id' => ':queryId'])}}";
            url = url.replace(':queryId', id);
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        table.ajax.reload();
                        alert(response.message) //Message come from controller
                    } else {
                        alert("Error")
                    }
                },
                error: function(error) {
                    console.log(error)
                }
            });
        }
        //* edit form 
        $('.yajra-datatable').on('click', '#yajraEdit', function(e) {
            let row = table.row($(this).parent().parent()).data();
            let id = row.id
            let url = "{{route('employee.edit', ['id' => ':queryId'])}}";
            url = url.replace(':queryId', id);

            $.ajax({
                url: url,
                method: 'GET',
                // data: data,
                success: function(response) {
                    document.getElementById('edit-form-name').value = response.data.name;
                    document.getElementById('edit-form-designation').value = response.data.designation;
                    document.getElementById('edit-form-address').value = response.data.address;
                    document.getElementById('edit-form-salary').value = response.data.salary;
                    document.getElementById('edit-form-id').value = response.data.id;
                    yajraModal.toggle(); //* toggle bootstrap modal
                },
                error: function(error) {
                    console.log(error)
                }
            });
        });
        //* update form
        $('#yajra-form-submit').on('submit', function(e) {
            e.preventDefault();
            let data = $(this).serialize();
            let id = document.getElementById('edit-form-id').value;
            let url = "{{ route('employee.update', ['id' => ':queryId']) }}";
            url = url.replace(':queryId', id);

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        yajraModal.hide(); //* toggle bootstrap modal
                        table.ajax.reload();
                    } else {
                        alert("Error")
                    }
                },
                error: function(error) {
                    console.log(error)
                }
            });
        });
        //* delete multiple records
        $('#delete-records').on('click', function(e) {
            let rows = table.rows('.selected').ids().toArray();
            if (rows.length > 0) {
                if (confirm('Are you sure to delete this records'))
                    deleteMultiRecords(e, rows);
            } else {
                alert('Please Select checkbox to delete record');
            }
        });

        function deleteMultiRecords(e, ids) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('employee.multipleDelete') }}",
                method: 'POST',
                data: {
                    'ids': ids
                },
                success: function(response) {
                    if (response.success) {
                        table.ajax.reload();
                        let checkAll = $('#checkAllCheckbox')
                        if (checkAll.is(":checked")) {
                            checkAll.prop("checked", false);
                        }
                        alert(response.message) //Message come from controller
                    } else {
                        alert("Error")
                    }
                },
                error: function(error) {
                    console.log(error)
                }
            });
        }

        $('#checkAllCheckbox').on('change', function(e) {
            console.log('test');
            if ($(this).is(":checked")) {
                table.rows().select();
            } else {
                table.rows().deselect();
            }
        })
    });
</script>

@endsection
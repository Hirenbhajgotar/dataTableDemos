@extends('app')
@section('style')
<link href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/searchbuilder/1.2.0/css/searchBuilder.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" rel="stylesheet">
<style>
    td.details-control {
        background: url("{{URL('/')}}/assets/details_open.png") no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url("{{ asset('/assets/details_close.png') }}") no-repeat center center;
    }
</style>
@endsection
@section('content')
<section>
    <div class="mb-5">
        <form id="form-submit" method="post">
            @csrf
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">First Name</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="first name" name="first_name">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="exampleFormControlTextarea1" placeholder="last name" name="last_name">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea2" class="form-label">Address</label>
                <textarea class="form-control" name="address" id="exampleFormControlTextarea2" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-secondary">Submit</button>
        </form>
    </div>

    <table id="table_id" class="display">
        <button type="button" id="delete-records" class="btn btn-danger">Delete All</button>
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th>Sr No.</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Action</th>
            </tr>
        </thead>

    </table>
</section>
<!-- Modal -->
<div class="modal fade" id="editModelForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-form-submit" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="edit-form-first-name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="edit-form-first-name" placeholder="first name" name="first_name">
                    </div>
                    <div class="mb-3">
                        <label for="edit-form-last-name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="edit-form-last-name" placeholder="last name" name="last_name">
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/searchbuilder/1.2.0/js/dataTables.searchBuilder.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script>
    /* Formatting function for row details - modify as you need */
    function format(d) {
        // `d` is the original data object for the row
        return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
            '<tr>' +
            '<td>Address:</td>' +
            '<td>' + d.address + '</td>' +
            '</tr>' +
            '</table>';
    }
    $(document).ready(function() {
        var myModal = new bootstrap.Modal(document.getElementById('editModelForm'));

        var table = $('#table_id').DataTable({
            "order": [
                [3, "asc"]
            ],
            // processing: true,
            // serverSide: true,
            // "ajax": "{{route('getHomeData')}}",
            "ajax": {
                type: "GET",
                url: "{{route('getHomeData')}}",
                "dataSrc": function(json) {
                    //Make your callback here.
                    // alert("Done!");
                    console.log(json);
                    return json.data;
                }
            },
            "columns": [{
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '+'
                },
                {
                    "orderable": false,
                    "data": null,
                    "defaultContent": ''
                },
                {
                    "data": "id"
                },
                {
                    "data": "first_name"
                },
                {
                    "data": "last_name"
                },
                {
                    "orderable": false,
                    "data": null,
                    "defaultContent": `<button type="button" id="delete-row" class="btn btn-danger btn-sm">Delete</button><button type="button" id="edit-row" class="btn btn-warning btn-sm" >Edit</button>`
                }
            ],
            columnDefs: [{
                orderable: false,
                className: 'select-checkbox',
                targets: 1,
                checkboxes: {
                    'selectRow': true
                },
                createdCell: function(row, data, index) {
                    $(row).find('td:eq(1)').attr('data-id', '3');
                }
            }],
            select: {
                style: 'os',
                selector: 'td:nth-child(2)'
            },
            rowId: 'id'
        });
        // Add event listener for opening and closing details
        $('#table_id tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });
        // ajax call post request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#form-submit').on('submit', function(e) {
            e.preventDefault();
            let data = $(this).serialize();
            let url = "{{ route('addHomeData') }}";

            $.ajax({
                url: "{{ route('addHomeData') }}",
                method: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        document.getElementById("form-submit").reset();

                        // table.destroy();
                        // $('#table_id').DataTable();
                        // let ajaxTable = ('#table_id').Datatable();
                        // ajaxTable.draw();
                        // $("#table_id").ajax.reload();
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
        });

        $('#table_id').on('click', '#delete-row', function(e) {
            let row = table.row($(this).parent().parent()).data();
            if (confirm('Are you sure to Delete this Record'))
                deleteRow(e, row.id);
        });

        //* Delete single record
        function deleteRow(e, id) {
            e.preventDefault();
            let url = "{{route('deleteHomeData', ['id' => ':queryId'])}}";
            url = url.replace(':queryId', id);
            $.ajax({
                url: url,
                method: 'POST',
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

        //* Delete multiple records 
        $('#delete-records').on('click', function(e) {
            let rows = table.rows({
                selected: true
            }).ids().toArray();
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
                url: "{{ route('deleteMultiRecords') }}",
                method: 'POST',
                data: {
                    'ids': ids
                },
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

        //* edit row 
        $('#table_id').on('click', '#edit-row', function(e) {
            e.preventDefault();
            let row = table.row($(this).parent().parent()).data();
            let id = row.id
            let url = "{{route('getSingleRecord', ['id' => ':queryId'])}}";
            url = url.replace(':queryId', id);

            $.ajax({
                url: url,
                method: 'GET',
                // data: data,
                success: function(response) {
                    document.getElementById('edit-form-first-name').value = response.data.first_name;
                    document.getElementById('edit-form-last-name').value = response.data.last_name;
                    document.getElementById('edit-form-address').value = response.data.address;
                    document.getElementById('edit-form-id').value = response.data.id;
                    myModal.toggle(); //* toggle bootstrap modal
                },
                error: function(error) {
                    console.log(error)
                }
            });
        })
        //* update row
        $('#edit-form-submit').on('submit', function(e) {
            e.preventDefault();
            let data = $(this).serialize();
            let id = document.getElementById('edit-form-id').value;
            let url = "{{ route('updateSingleRecord', ['id' => ':queryId']) }}";
            url = url.replace(':queryId', id);
            // console.log(url);
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        // var myModal2 = new bootstrap.Modal(document.getElementById('editModelForm'));
                        myModal.hide(); //* toggle bootstrap modal
                        table.ajax.reload();
                        // alert(response.message) //Message come from controller
                    } else {
                        alert("Error")
                    }
                },
                error: function(error) {
                    console.log(error)
                }
            });
        })

    });
</script>
@endsection
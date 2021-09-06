@extends('app')
@section('style')
<link href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/searchbuilder/1.2.0/css/searchBuilder.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" rel="stylesheet">
@endsection
@section('content')
<section>
    <table id="student_table_id" class="display">
        <div class="my-3">
            <button type="button" id="add-record" class="btn btn-sm mx-1 btn-primary">Add</button>
            <button type="button" id="delete-records" class="btn btn-sm mx-1 btn-danger">Delete All</button>
        </div>
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Gender</th>
                <th>State</th>
                <th>Image</th>
            </tr>
        </thead>

    </table>
</section>
<!-- Modal -->
<div class="modal fade " id="datatableModelForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="datatable-form-submit" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Name" name="name">
                            <span class="text-danger error-text name_err"></span>
                        </div>
                        <div class="col">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" placeholder="Phone" name="phone">
                            <span class="text-danger error-text phone_err"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" placeholder="Email" name="email">
                            <span class="text-danger error-text email_err"></span>
                        </div>
                        <div class="col">
                            <label for="formFile" class="form-label">Select Image</label>
                            <input class="form-control" type="file" id="profile_photo_path" name="profile_photo_path">
                            <span class="text-danger error-text profile_photo_path_err"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Country</label>
                            <select class="form-select" name="country_id" id="country_id" aria-label="Default select example">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                <option value="{{$country->id}}">{{$country->country}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text country_id_err"></span>
                        </div>
                        <div class="col">
                            <label class="form-label">State</label>
                            <select class="form-select" name="state_id" id="state_id" aria-label="Default select example">
                                <option value="">Select State</option>
                            </select>
                            <span class="text-danger error-text state_id_err"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Gender</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="male" value="male">
                                <label class="form-check-label" for="male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                                <label class="form-check-label" for="female">Female</label>
                            </div>
                            <br>
                            <span class="text-danger error-text gender_err"></span>
                        </div>
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
    $(document).ready(function() {
        var myModal = new bootstrap.Modal(document.getElementById('datatableModelForm'));

        var student_table = $('#student_table_id').DataTable({
            // ajax: "{{route('student.list')}}",
            ajax: {
                type: "GET",
                url: "{{route('student.list')}}",
                "dataSrc": function(json) {
                    // console.log(json.data);
                    return json.data;
                }
            },
            columns: [{
                    data: null,
                    // name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'gender',
                    name: 'gender'
                },
                {
                    data: 'state.state',
                    name: 'state'
                },
                {
                    data: 'profile_photo_path',
                    name: 'image',
                    render: getImg
                }
            ],
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
            }],
            rowId: 'id'
        });
        // render image in datatable
        function getImg(data, type, full, meta) {
            return `<img class="img-fluid" src="${'storage/'+data}" />`;
        }
        //! row index 
        student_table.on('order.dt search.dt', function() {
            student_table.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
        //* Add record
        $('#add-record').on('click', function(e) {
            myModal.toggle();
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Add form data
        $('#datatable-form-submit').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            let url = "{{ route('student.add') }}";
            $.ajax({
                url: url,
                data: formData,
                method: "POST",
                processData: false,
                contentType: false,
                success: function(res) {
                    console.log(res.error);
                    if (res.error) {
                        $.each(res.error, function(key, value) {
                            $(`.${key}_err`).text(value);
                        })
                    } else {
                        document.getElementById("datatable-form-submit").reset();
                        student_table.ajax.reload();
                        myModal.toggle()
                        alert(res.message) //Message come from controller
                    }
                },
                error: function() {

                }
            })
        });
        // Dependent country/state dropdown
        $('#country_id').on('change', function(e) {
            let countryId = $(this).val();
            let url = "{{route('country.get', ['id' => ':queryId'])}}";
            url = url.replace(':queryId', countryId);
            $.ajax({
                url: url,
                method: 'POST',
                success: function(res) {
                    console.log(res.data);
                    $('#state_id').empty();
                    $('#state_id').append('<option>Select State</option>');
                    $.each(res.data, function(key, val) {
                        $('#state_id').append(`<option value="${val.id}">${val.state}</option>`);
                    });
                },
                error: function(err) {
                    console.log(err);
                }
            })
        });
    })
</script>
@endsection
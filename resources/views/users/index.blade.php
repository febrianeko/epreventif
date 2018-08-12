@extends('adminlte::layouts.app')

@section('contentheader_title')
    User {{$role['roleName']}} Page
@endsection
@section('htmlheader_title')
    User {{$role['roleName']}}
@endsection


@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <form id="frmUser">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="page-header">
                                        <h3>Form Create/Edit</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="fullName">Full Name</label>
                                        <input type="text" class="form-control" id="fullName" name="fullName"
                                               placeholder="Input full name of user example Jhon Doe" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" name="email"
                                               placeholder="Input email of user example user@user.com" required>
                                    </div>
                                    <input type="hidden" id="userId">

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="position">Position</label>
                                        <select class="form-control" id="position" name="position" required>
                                            <option value=""></option>
                                            <option value="Admin">Admin</option>
                                            <option value="Engineer">Engineer</option>
                                            <option value="Supervisor">Supervisor</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="regionalId">Region</label>
                                        <select class="form-control" id="regionalId" name="regionalId" required>
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-md-offset-9">
                                    <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-check"></i>
                                        Save
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="resetInput()"><i
                                                class="fa fa-refresh"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-bordered table-hover table-condensed" id="tblUsers">
                            <thead>
                            <tr>
                                <th data-column-id="name">Full Name</th>
                                <th data-column-id="email">Email</th>
                                <th data-column-id="role_name">Role</th>
                                <th data-column-id="regional_name">Regional</th>
                                <th data-column-id="action" data-formatter="action" data-sortable="false"></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customscripts')
    <script>
        $(document).ready(function () {
            $('#position').select2();
            pagination();
            getRegional();
            validateForm();
        })

        function validateForm() {
            $('#frmUser').validate({
                rules: {
                    fullName: {
                        required: true
                    },
                    email:{
                        required:true
                    },
                    position:{
                        required:true
                    },
                    regionalId:{
                        required:true
                    }
                },

                messages: {
                    fullName: {
                        required: "Full name must be fill"
                    },
                    email:{
                        required:"Email must be fill"
                    },
                    position:{
                        required:"Choose one of position"
                    },
                    regionalId:{
                        required:"Choose one of regional"
                    }
                },

                highlight: function (element) { // hightlight error inputs
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label.closest('.form-group').removeClass('has-error');
                    label.remove();
                },

                submitHandler: function (form) {
                    runWaitMe('body', 'progressBar', 'Saving data...');
                    var id;
                    var url;
                    var notifMessage;

                    notifMessage = "Successfully create new user";
                    url = "{{route('createUser')}}";
                    id = $('#userId').val();

                    if (id != '' || typeof id == 'undefined') {
                        url = "{{route('updateUser')}}";
                        notifMessage = "Successfully update existing user";
                    }

                    $.ajax({
                        url: url,
                        method: "POST",
                        data: {
                            name: $('#fullName').val(),
                            email: $('#email').val(),
                            position:"{{$role['roleName']}}",
                            roleId:"{{$role['roleId']}}",
                            regionalId:$('#regionalId').val(),
                            id: id,
                            _token: "{{csrf_token()}}"
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrow) {
                            $('body').waitMe('hide');
                            notificationMessage(errorThrow, 'error');
                        },
                        success: function (s) {
                            if (s.success) {
                                $('body').waitMe('hide');
                                notificationMessage(notifMessage, 'success');

                                resetInput();
                            } else {
                                $('body').waitMe('hide');
                                var errorMessagesCount = s.message.length;
                                for (var i = 0; i < errorMessagesCount; i++) {
                                    notificationMessage(s.message[i], 'error');
                                }
                            }
                        }
                    })
                }
            });
        }

        function pagination() {
            var grid = $('#tblUsers').bootgrid({
                ajax: true,
                url: "{{route('paginationUsers')}}",
                post: function () {
                    return {
                        _token: "{{csrf_token()}}",
                        roleId: "{{$role['roleId']}}"
                    }
                },
                formatters: {
                    "action": function (column, row) {
                        return "<div class=\"btn-group\">" +
                            "<a href=\"#\" data-row-id=\"" + row.id + "\" data-row-name=\"" + row.name + "\"  data-row-email=\"" + row.email + "\" data-row-position=\""+row.position+"\" data-row-regional=\""+row.regional_id+"\" class=\"btn btn-link cmd-edit\"><i class=\"fa fa-pencil\"></i></a>" +
                            "<a href=\"#\" data-row-id=\"" + row.id + "\" data-row-name=\"" + row.name + "\" data-row-email=\"" + row.email + "\" data-row-position=\""+row.position+"\" data-row-regional=\""+row.regional_id+"\" class=\"btn btn-link cmd-delete\"><i class=\"fa fa-trash-o\"></i></a>" +
                            "</div>";
                    }
                }
            }).on("loaded.rs.jquery.bootgrid", function () {
                grid.find(".cmd-delete").on("click", function (e) {
                    e.preventDefault();
                    var url;
                    var msg;
                    msg = 'Are you sure want to delete user ' + $(this).data('row-name') + ' ?';
                    url = "/users/delete" + '/' + $(this).data('row-id');
                    popUpConfirmation(url, 'tblUsers', msg, 'Deleting...', 'User successfully deleted', "{{csrf_token()}}");
                    return false;
                });

                grid.find(".cmd-edit").on("click", function (e) {
                    e.preventDefault();
                    $('#fullName').val($(this).data('row-name'));
                    $('#email').val($(this).data('row-email'));
                    $('#position').val($(this).data('row-position')).trigger('change');
                    $('#regionalId').val($(this).data('row-regional')).trigger('change');
                    $('#userId').val($(this).data('row-id'));
                    return false;
                });
            });
        }

        function getRegional() {
            $.ajax({
                url:"{{route('showAllRegional')}}",
                method:"GET",
                success:function (s) {
                    console.log(s);
                    $('#regionalId').select2({
                        data:s.result,
                        placeholder:'Choose regional'
                    });
                }
            })
        }
        function resetInput() {
            $('input').val('');
            $('select').val('').trigger('change');
            $('#tblUsers').bootgrid('reload');
        }
    </script>
@endsection

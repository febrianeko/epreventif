@extends('adminlte::layouts.app')

@section('contentheader_title')
    User Roles Page
@endsection
@section('htmlheader_title')
    User Roles
@endsection


@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-5">
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
                            <div class="col-md-12">
                                <form id="frmRoles">
                                    <div class="form-group">
                                        <label for="roleName">Role Name</label>
                                        <input type="text" class="form-control" id="roleName" name="roleName"
                                               placeholder="input role name example administrator or engineer" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" rows="4" id="description"
                                                  name="description"></textarea>
                                    </div>
                                    <input type="hidden" id="roleId">
                                    <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Save</button>
                                    <button type="button" class="btn btn-warning" onclick="resetInput()"><i class="fa fa-refresh"></i> Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-7">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-bordered table-hover table-condensed" id="tblRoles">
                            <thead>
                            <tr>
                                <th data-column-id="role_name">Role Name</th>
                                <th data-column-id="description">Description</th>
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
            pagination();
            validateForm();
        })

        function validateForm() {
            $('#frmRoles').validate({
                rules: {
                    roleName: {
                        required: true
                    }
                },

                messages: {
                    roleName: {
                        required: "Role name cannot be empty"
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
                    var roleId;
                    var url;
                    var notifMessage;

                    notifMessage = "Successfully create new user role";
                    url = "{{route('createRole')}}";
                    roleId = $('#roleId').val();

                    if (roleId != '' || typeof roleId == 'undefined') {
                        url = "{{route('updateRole')}}";
                        notifMessage = "Successfully update existing user role";
                    }

                    $.ajax({
                        url: url,
                        method: "POST",
                        data: {
                            roleName: $('#roleName').val(),
                            description:$('#description').val(),
                            id: roleId,
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

                                location.reload();
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
            var grid = $('#tblRoles').bootgrid({
                ajax: true,
                url: "{{route('paginationRoles')}}",
                post: function () {
                    return {
                        _token: "{{csrf_token()}}"
                    }
                },
                formatters: {
                    "action": function (column, row) {
                        return "<div class=\"btn-group\">" +
                            "<a href=\"#\" data-row-id=\"" + row.id + "\" data-row-rolename=\"" + row.role_name + "\"  data-row-description=\""+row.description+"\" class=\"btn btn-link cmd-edit\"><i class=\"fa fa-pencil\"></i></a>" +
                            "<a href=\"#\" data-row-id=\"" + row.id + "\" data-row-rolename=\"" + row.role_name + "\" data-row-description=\""+row.description+"\" class=\"btn btn-link cmd-delete\"><i class=\"fa fa-trash-o\"></i></a>" +
                            "</div>";
                    }
                }
            }).on("loaded.rs.jquery.bootgrid", function () {
                grid.find(".cmd-delete").on("click", function (e) {
                    e.preventDefault();
                    var url;
                    var msg;
                    msg = 'Are you sure want to delete user role' + $(this).data('rolename') + ' ?';
                    url = "/roles/delete" + '/' + $(this).data('row-id');
                    popUpConfirmation(url, 'tblRoles', msg, 'Deleting...', 'User role successfully deleted', "{{csrf_token()}}");
                    return false;
                });

                grid.find(".cmd-edit").on("click", function (e) {
                    e.preventDefault();
                    $('#roleName').val($(this).data('row-rolename'));
                    $('#description').val($(this).data('row-description'));
                    $('#roleId').val($(this).data('row-id'));
                    return false;
                });
            });
        }

        function resetInput() {
            $('input').val('');
            $('textarea').val('');
        }
    </script>
@endsection

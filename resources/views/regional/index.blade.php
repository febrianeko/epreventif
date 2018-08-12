@extends('adminlte::layouts.app')

@section('contentheader_title')
    Master Regional Page
@endsection
@section('htmlheader_title')
    Master Regional
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
                                <form id="frmRegional">
                                    <div class="form-group">
                                        <label for="regionalName">Regional Name</label>
                                        <input type="text" class="form-control" id="regionalName" name="regionalName"
                                               placeholder="input regional name example denpasar or east java" required>
                                    </div>

                                    <input type="hidden" id="regionalId">
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
                        <table class="table table-bordered table-hover table-condensed" id="tblRegional">
                            <thead>
                            <tr>
                                <th data-column-id="regional_name">Regional Name</th>
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
            $('#frmRegional').validate({
                rules: {
                    regionalName: {
                        required: true
                    }
                },

                messages: {
                    regionalName: {
                        required: "Regional name cannot be empty"
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

                    notifMessage = "Successfully create new regional";
                    url = "{{route('createRegional')}}";
                    id = $('#regionalId').val();

                    if (id != '' || typeof id == 'undefined') {
                        url = "{{route('updateRegional')}}";
                        notifMessage = "Successfully update existing regional";
                    }

                    $.ajax({
                        url: url,
                        method: "POST",
                        data: {
                            regionalName: $('#regionalName').val(),
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
            var grid = $('#tblRegional').bootgrid({
                ajax: true,
                url: "{{route('paginationRegional')}}",
                post: function () {
                    return {
                        _token: "{{csrf_token()}}"
                    }
                },
                formatters: {
                    "action": function (column, row) {
                        return "<div class=\"btn-group\">" +
                            "<a href=\"#\" data-row-id=\"" + row.id + "\" data-row-regionalname=\"" + row.regional_name + "\" class=\"btn btn-link cmd-edit\"><i class=\"fa fa-pencil\"></i></a>" +
                            "<a href=\"#\" data-row-id=\"" + row.id + "\" data-row-regionalname=\"" + row.regional_name + "\" class=\"btn btn-link cmd-delete\"><i class=\"fa fa-trash-o\"></i></a>" +
                            "</div>";
                    }
                }
            }).on("loaded.rs.jquery.bootgrid", function () {
                grid.find(".cmd-delete").on("click", function (e) {
                    e.preventDefault();
                    var url;
                    var msg;
                    msg = 'Are you sure want to delete regional ' + $(this).data('row-regionalname') + ' ?';
                    url = "/regional/delete" + '/' + $(this).data('row-id');
                    popUpConfirmation(url, 'tblRegional', msg, 'Deleting...', 'Regional successfully deleted', "{{csrf_token()}}");
                    return false;
                });

                grid.find(".cmd-edit").on("click", function (e) {
                    e.preventDefault();
                    $('#regionalName').val($(this).data('row-regionalname'));
                    $('#regionalId').val($(this).data('row-id'));
                    return false;
                });
            });
        }

        function resetInput() {
            $('input').val('');
            $('#tblRegional').bootgrid('reload');
        }
    </script>
@endsection

@extends('adminlte::layouts.app')

@section('contentheader_title')
    Master Area Page
@endsection
@section('htmlheader_title')
    Master Area
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
                                <form id="frmArea">
                                    <div class="form-group">
                                        <label for="areaName">Area Name</label>
                                        <input type="text" class="form-control" id="areaName" name="areaName"
                                               placeholder="input area name example kuta or jimbaran" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="regionalId">Regional</label>
                                        <select id="regionalId" name="regionalId" class="form-control" required>
                                            <option value=""></option>
                                        </select>
                                    </div>

                                    <input type="hidden" id="areaId">
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
                        <table class="table table-bordered table-hover table-condensed" id="tblAreas">
                            <thead>
                            <tr>
                                <th data-column-id="area_name">Area Name</th>
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
            getRegional();
            validateForm();
        })

        function validateForm() {
            $('#frmArea').validate({
                rules: {
                    areaName: {
                        required: true
                    },
                    regionalId:{
                        required:true
                    }
                },

                messages: {
                    areaName: {
                        required: "Area name must be fill"
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

                    notifMessage = "Successfully create new area";
                    url = "{{route('createArea')}}";
                    id = $('#areaId').val();

                    if (id != '' || typeof id == 'undefined') {
                        url = "{{route('updateArea')}}";
                        notifMessage = "Successfully update existing area";
                    }

                    $.ajax({
                        url: url,
                        method: "POST",
                        data: {
                            areaName: $('#areaName').val(),
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
            var grid = $('#tblAreas').bootgrid({
                ajax: true,
                url: "{{route('paginationArea')}}",
                post: function () {
                    return {
                        _token: "{{csrf_token()}}"
                    }
                },
                formatters: {
                    "action": function (column, row) {
                        return "<div class=\"btn-group\">" +
                            "<a href=\"#\" data-row-id=\"" + row.id + "\" data-row-areaname=\"" + row.area_name + "\" data-row-regional=\""+row.regional_id+"\" class=\"btn btn-link cmd-edit\"><i class=\"fa fa-pencil\"></i></a>" +
                            "<a href=\"#\" data-row-id=\"" + row.id + "\" data-row-areaname=\"" + row.area_name + "\" data-row-regional=\""+row.regional_id+"\" class=\"btn btn-link cmd-delete\"><i class=\"fa fa-trash-o\"></i></a>" +
                            "</div>";
                    }
                }
            }).on("loaded.rs.jquery.bootgrid", function () {
                grid.find(".cmd-delete").on("click", function (e) {
                    e.preventDefault();
                    var url;
                    var msg;
                    msg = 'Are you sure want to delete area ' + $(this).data('row-areaname') + ' ?';
                    url = "/area/delete" + '/' + $(this).data('row-id');
                    popUpConfirmation(url, 'tblAreas', msg, 'Deleting...', 'Area successfully deleted', "{{csrf_token()}}");
                    return false;
                });

                grid.find(".cmd-edit").on("click", function (e) {
                    e.preventDefault();
                    $('#areaName').val($(this).data('row-areaname'));
                    $('#regionalId').val($(this).data('row-regional')).trigger('change');
                    $('#areaId').val($(this).data('row-id'));
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
            $('#tblAreas').bootgrid('reload');
        }
    </script>
@endsection

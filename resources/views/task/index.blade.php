@extends('adminlte::layouts.app')

@section('contentheader_title')
    Task
@endsection
@section('htmlheader_title')
    Task
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
                                <form id="frmTask">
                                    <div class="form-group">
                                        <label for="regional">Regional</label>
                                        <select class="form-control" id="regional" name="regional" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="area">Area</label>
                                        <select id="area" name="area" class="form-control" required>
                                            <option value=""></option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="site">Site</label>
                                        <select id="site" name="site" class="form-control" required>
                                            <option value=""></option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="engineer">Engineer</label>
                                        <select id="engineer" name="engineer" class="form-control" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="dateTask">Date Task</label>
                                        <input type="text" id="dateTask" name="dateTask" class="form-control" required>
                                    </div>
                                    <input type="hidden" id="taskId">
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
                        <table class="table table-bordered table-hover table-condensed" id="tblTask">
                            <thead>
                            <tr>
                                <th data-column-id="site_name">Site Name</th>
                                <th data-column-id="name">Engineer Name</th>
                                <th data-column-id="date_task">Date</th>
                                <th data-column-id="regional_name">Regional Name</th>
                                <th data-column-id="area_name">Area Name</th>
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
            $('#dateTask').datetimepicker({
                format:'YYYY-MM-DD'
            });
        })

        function validateForm() {
            $('#frmTask').validate({
                rules: {
                    regional: {
                        required: true
                    },
                    area:{
                        required:true
                    },
                    site:{
                        required:true
                    },
                    engineer:{
                        required:true
                    },
                    dateTask:{
                        required:true
                    }
                },

                messages: {
                    regional: {
                        required: "Choose one of regional"
                    },
                    area:{
                        required:"Choose one of area"
                    },
                    site:{
                        required:"Choose one of site"
                    },
                    engineer:{
                        required:"Choose on of engineer"
                    },
                    dateTask:{
                        required:"Choose date task"
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

                    notifMessage = "Successfully create new task";
                    url = "{{route('createTask')}}";
                    id = $('#taskId').val();

                    if (id != '' || typeof id == 'undefined') {
                        url = "{{route('updateTask')}}";
                        notifMessage = "Successfully update existing task";
                    }

                    $.ajax({
                        url: url,
                        method: "POST",
                        data: {
                            siteId: $('#site').val(),
                            engineerId:$('#engineer').val(),
                            dateTask:$('#dateTask').val(),
                            regionalId:$('#regional').val(),
                            areaId:$('#area').val(),
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
            var grid = $('#tblTask').bootgrid({
                ajax: true,
                url: "{{route('paginationTask')}}",
                post: function () {
                    return {
                        status:0,
                        _token: "{{csrf_token()}}"
                    }
                },
                formatters: {
                    "action": function (column, row) {
                        return "<div class=\"btn-group\">" +
                            "<a href=\"#\" data-row-id=\"" + row.id + "\" data-row-siteid=\"" + row.site_id + "\" data-row-regionalid=\""+row.regional_id+"\" data-row-areaid=\""+row.area_id+"\" data-row-datetask=\""+row.date_task+"\" data-row-engineerid=\""+row.engineer_id+"\" class=\"btn btn-link cmd-edit\"><i class=\"fa fa-pencil\"></i></a>" +
                            "<a href=\"#\" data-row-id=\"" + row.id + "\" data-row-siteid=\"" + row.site_id + "\" data-row-regionalid=\""+row.regional_id+"\" data-row-areaid=\""+row.area_id+"\" data-row-datetask=\""+row.date_task+"\" data-row-engineerid=\""+row.engineer_id+"\" class=\"btn btn-link cmd-delete\"><i class=\"fa fa-trash-o\"></i></a>" +
                            "</div>";
                    }
                }
            }).on("loaded.rs.jquery.bootgrid", function () {
                grid.find(".cmd-delete").on("click", function (e) {
                    e.preventDefault();
                    var url;
                    var msg;
                    msg = 'Are you sure want to delete task ?';
                    url = "/task/delete" + '/' + $(this).data('row-id');
                    popUpConfirmation(url, 'tblTask', msg, 'Deleting...', 'Task successfully deleted', "{{csrf_token()}}");
                    return false;
                });

                grid.find(".cmd-edit").on("click", function (e) {
                    e.preventDefault();
                    $('#regional').val($(this).data('row-regionalid')).trigger('change');
                    $('#area').val($(this).data('row-areaid')).trigger('change');
                    $('#site').val($(this).data('row-siteid')).trigger('change');
                    $('#engineer').val($(this).data('row-engineerid')).trigger('change');
                    $('#dateTask').val($(this).data('row-datetask')).trigger('change');
                    return false;
                });
            });
        }

        function getRegional() {
            $.ajax({
                url:"{{route('showAllRegional')}}",
                method:"GET",
                success:function (s) {
                    $('#regional').select2({
                        data:s.result,
                        placeholder:'Choose regional'
                    }).on('change',function () {
                        getArea($(this).val())
                    })
                }
            })
        }

        function getArea(regionalId) {
            if(typeof  regionalId !='undefined' || regionalId !=''){
                $.ajax({
                    url: "{{url('/area/read')}}/" + regionalId,
                    method: "GET",
                    success: function (s) {
                        $('#area').children('option:not(:first)').remove();
                        $('#area').select2({
                            data: s.result,
                            placeholder: 'Choose area'
                        }).on('change',function () {
                            getSite($(this).val());
                            getEngineer($(this).val());
                        })
                    }
                })
            }
        }

        function getSite(areaId) {
            if(typeof areaId!='undefined' || areaId!='undefined'){
                $.ajax({
                    url: "{{url('/sites/read-by-regional-area')}}/" + $('#regional').val()+'/'+areaId,
                    method: "GET",
                    success: function (s) {
                        $('#site').children('option:not(:first)').remove();
                        $('#site').select2({
                            data: s.result,
                            placeholder: 'Choose site'
                        });
                    }
                })
            }
        }

        function getEngineer(areaId) {
            if(typeof areaId!='undefined' || areaId!=''){
                $.ajax({
                    url: "{{url('/users/read-by-regional-area')}}/" + $('#regional').val()+'/'+areaId,
                    method: "GET",
                    success: function (s) {
                        $('#engineer').children('option:not(:first)').remove();
                        $('#engineer').select2({
                            data: s.result,
                            placeholder: 'Choose engineer'
                        });
                    }
                })
            }
        }

        function resetInput() {
            $('input').val('');
            $('select').val('').trigger('change');
            $('#tblTask').bootgrid('reload');
        }
    </script>
@endsection

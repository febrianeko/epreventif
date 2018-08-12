@extends('adminlte::layouts.app')

@section('contentheader_title')
    Tambah Site
@endsection
@section('htmlheader_title')
    Tambah Site
@endsection


@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form class="form-horizontal" id="frmSite">
                            <div class="form-group">
                                <label for="siteId" class="control-label col-md-3">Site Id</label>
                                <div class="col-md-6">
                                    <input type="text" id="siteId" name="siteId" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="siteName" class="control-label col-md-3">Site Name</label>
                                <div class="col-md-6">
                                    <input type="text" id="siteName" name="siteName" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="regional" class="control-label col-md-3">Regional</label>
                                <div class="col-md-6">
                                    <select id="regional" name="regional" class="form-control" required>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="area" class="control-label col-md-3">Area</label>
                                <div class="col-md-6">
                                    <select id="area" name="area" class="form-control" required>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="map" class="control-label col-md-3">Map</label>
                                <div class="col-md-9">
                                    <div id="map" style="width: 100%;height: 200px;">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="longitude" class="control-label col-md-3"></label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="longitude" value="0" name="longitude" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="latitude" value="0" name="latitude" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address" class="control-label col-md-3">Address</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="address" name="address"></textarea>
                                </div>
                            </div>
                            <input type="hidden" id="id">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customscripts')
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-fFLQ1OgmniSfEd_hNNF-zsB1eoeHSzw&callback=initMap">
    </script>
    <script>
        var area;
        area=0;
        $(document).ready(function () {

            validateForm();
            getRegional();
            $('#area').select2({
                placeholder:"Choose regional first",
                width:"100%"
            });
            $('#myModal').on('hidden.bs.modal',function () {
                resetInput();
            });
        });

        function validateForm() {
            $('#frmSite').validate({
                rules: {
                    siteId: {
                        required: true
                    },
                    siteName:{
                        required:true
                    },
                    regional:{
                        required:true
                    },
                    area:{
                        required:true
                    },
                },

                messages: {
                    siteId: {
                        required: "Site id must be fill"
                    },
                    siteName:{
                        required:"Site name must be fill"
                    },
                    regional:{
                        required:"Regional must be fill"
                    },
                    area:{
                        required:"Area must be fill"
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

                    notifMessage = "Successfully create new site";
                    url = "{{route('createSite')}}";
                    id = $('#id').val();

                    if (id != '' || typeof id == 'undefined') {
                        url = "{{route('updateSite')}}";
                        notifMessage = "Successfully update existing site";
                    }

                    $.ajax({
                        url: url,
                        method: "POST",
                        data: {
                            siteId: $('#siteId').val(),
                            siteName:$('#siteName').val(),
                            regionalId:$('#regional').val(),
                            areaId:$('#area').val(),
                            longitude:$('#longitude').val(),
                            latitude:$('#latitude').val(),
                            address:$('#address').val(),
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
                                notificationMessage(notifMessage,'success');
                                $('#myModal').modal('hide');
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop').remove();

                                resetInput();
                            } else {
                                $('body').waitMe('hide');
                                var errorMessagesCount = s.message.length;
                                for (var i = 0; i < errorMessagesCount; i++) {
                                    notificationMessage(s.message[i], 'error');
                                }
                                $('#myModal').modal('hide');
                            }
                        }
                    })
                }
            });
        }

        function resetInput() {
            $('input').val('');
            $('select').val('').trigger('change');
            $('#tblSites').bootgrid('reload');
            area=0;
        }

        function getRegional() {
            $.ajax({
                url:"{{route('showAllRegional')}}",
                method:"GET",
                success:function (s) {
                    $('#regional').select2({
                        data:s.result,
                        placeholder:'Choose regional',
                        width:'100%'
                    }).on('change',function () {
                        getArea($(this).val())
                    })
                }
            })
        }

        function getArea(regionalId) {

            if(area != 0){
                $.ajax({
                    url:"{{url('/area/read')}}/"+regionalId,
                    method:"GET",
                    success:function (s) {
                        $('#area').children('option:not(:first)').remove();
                        $('#area').select2({
                            data:s.result,
                            placeholder:'Choose area'
                        });
                        $('#area').val(area).trigger('change');
                    }
                })
            }else{
                $.ajax({
                    url:"{{url('/area/read')}}/"+regionalId,
                    method:"GET",
                    success:function (s) {
                        $('#area').children('option:not(:first)').remove();
                        $('#area').select2({
                            data:s.result,
                            placeholder:'Choose area'
                        });
                    }
                })
            }

        }
    </script>
@endsection

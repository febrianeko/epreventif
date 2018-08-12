@extends('adminlte::layouts.app')

@section('contentheader_title')
    Master Sites
@endsection
@section('htmlheader_title')
    Master Sites
@endsection


@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
                                        data-target="#myModal">
                                    Add New
                                </button>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover table-condensed" id="tblSites">
                                    <thead>
                                    <tr>
                                        <th data-column-id="site_id">Site Id</th>
                                        <th data-column-id="site_name">Site Name</th>
                                        <th data-column-id="regional_name">Regional</th>
                                        <th data-column-id="area_name">Area</th>
                                        <th data-column-id="mapShow" data-formatter="mapShow"
                                            data-sortable="false"></th>
                                        <th data-column-id="action" data-formatter="action" data-sortable="false"></th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('sites.formmodal')
@endsection

@section('customscripts')
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-fFLQ1OgmniSfEd_hNNF-zsB1eoeHSzw&callback=initMap">
    </script>
    <script>
        var area;
        area = 0;
        var map, infoWindow, marker, geocoder;
        var lat, lng;
        $(document).ready(function () {

            pagination();
            validateForm();
            getRegional();
            $('#area').select2({
                placeholder: "Choose regional first",
                width: "100%"
            });
            $('#myModal').on('hidden.bs.modal', function () {
                resetInput();
            });

            $("#myModal").on('shown.bs.modal', function () {
                initMap();
            })
        });

        function validateForm() {
            $('#frmSite').validate({
                rules: {
                    siteId: {
                        required: true
                    },
                    siteName: {
                        required: true
                    },
                    regional: {
                        required: true
                    },
                    area: {
                        required: true
                    },
                },

                messages: {
                    siteId: {
                        required: "Site id must be fill"
                    },
                    siteName: {
                        required: "Site name must be fill"
                    },
                    regional: {
                        required: "Regional must be fill"
                    },
                    area: {
                        required: "Area must be fill"
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
                            siteName: $('#siteName').val(),
                            regionalId: $('#regional').val(),
                            areaId: $('#area').val(),
                            longitude: $('#longitude').val(),
                            latitude: $('#latitude').val(),
                            address: $('#address').val(),
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

        function pagination() {
            var grid = $('#tblSites').bootgrid({
                ajax: true,
                url: "{{route('paginationSites')}}",
                post: function () {
                    return {
                        _token: "{{csrf_token()}}"
                    }
                },
                formatters: {
                    "action": function (column, row) {
                        return "<div class=\"btn-group\">" +
                            "<a href=\"#\" data-row-id=\"" + row.id + "\" data-row-siteid=\"" + row.site_id + "\" data-row-sitename=\"" + row.site_name + "\" data-row-regional=\"" + row.regional_id + "\" data-row-area=\"" + row.area_id + "\" data-row-lat=\"" + row.latitude + "\" data-row-lng=\"" + row.longitude + "\" data-row-addr=\"" + row.address + "\" class=\"btn btn-link cmd-edit\"><i class=\"fa fa-pencil\"></i></a>" +
                            "<a href=\"#\" data-row-id=\"" + row.id + "\" data-row-siteid=\"" + row.site_id + "\" data-row-sitename=\"" + row.site_name + "\" data-row-regional=\"" + row.regional_id + "\" data-row-area=\"" + row.area_id + "\" data-row-lat=\"" + row.latitude + "\" data-row-lng=\"" + row.latitude + "\" data-row-addr=\"" + row.address + "\" class=\"btn btn-link cmd-delete\"><i class=\"fa fa-trash-o\"></i></a>" +
                            "</div>";
                    },
                    "mapShow": function (column, row) {
                        return "<a href=\"#\" class=\"btn btn-link\">Show Map</a>"
                    }
                }
            }).on("loaded.rs.jquery.bootgrid", function () {
                grid.find(".cmd-delete").on("click", function (e) {
                    e.preventDefault();
                    var url;
                    var msg;
                    msg = 'Are you sure want to delete this site ' + $(this).data('row-sitename') + ' ?';
                    url = "/sites/delete" + '/' + $(this).data('row-id');
                    popUpConfirmation(url, 'tblSites', msg, 'Deleting...', 'Site successfully deleted', "{{csrf_token()}}");
                    return false;
                });

                grid.find(".cmd-edit").on("click", function (e) {
                    e.preventDefault();
                    $('#siteId').val($(this).data('row-siteid'));
                    $('#siteName').val($(this).data('row-sitename'));
                    $('#regional').val($(this).data('row-regional')).trigger('change');
                    $('#area').val($(this).data('row-area')).trigger('change');
                    $('#longitude').val($(this).data('row-lng'));
                    $('#latitude').val($(this).data('row-lat'));
                    $('#address').val($(this).data('row-addr'));
                    $('#id').val($(this).data('row-id'));
                    $('#myModal').modal('show');
                    area = parseInt($(this).data('row-area'));
                    lng = parseFloat($(this).data('row-lng'));
                    lat = parseFloat($(this).data('row-lat'));
                    return false;
                });
            });
        }

        function resetInput() {
            $('input').val('');
            $('select').val('').trigger('change');
            $('#tblSites').bootgrid('reload');
            area = 0;
            lat = 0;
            lng = 0;
        }

        function getRegional() {
            $.ajax({
                url: "{{route('showAllRegional')}}",
                method: "GET",
                success: function (s) {
                    $('#regional').select2({
                        data: s.result,
                        placeholder: 'Choose regional',
                        width: '100%'
                    }).on('change', function () {
                        getArea($(this).val())
                    })
                }
            })
        }

        function getArea(regionalId) {

            $.ajax({
                url: "{{url('/area/read')}}/" + regionalId,
                method: "GET",
                success: function (s) {
                    $('#area').children('option:not(:first)').remove();
                    $('#area').select2({
                        data: s.result,
                        placeholder: 'Choose area'
                    });
                    if (area != 0) {
                        $('#area').val(area).trigger('change');
                    }
                }
            })

        }



        function initMap() {

            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: lat, lng: lng},
                zoom: 15,
                zoomControl: true
            });

            infoWindow = new google.maps.InfoWindow;

            marker = new google.maps.Marker({
                position: {lat: lat, lng: lng},
                map: map,
                title: 'Hello World!'
            });
            google.maps.event.addListener(marker, 'dragend', function (evt) {
                $('#longitude').val(evt.latLng.lng());
                $('#latitude').val(evt.latLng.lat());
            });

            tryGeolocation();

        }


        var apiGeolocationSuccess = function (position) {
            //alert("API geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);
            geocoder = new google.maps.Geocoder();
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            $('#longitude').val(position.coords.longitude);
            $('#latitude').val(position.coords.latitude);
//            infoWindow.setPosition(pos);
//            infoWindow.setContent('Location found.');
//            infoWindow.open(map);
            map.setCenter(pos);

            marker = new google.maps.Marker({
                position: pos,
                map: map,
                title: 'Hello World!',
                draggable: true,
            });
            geocoder.geocode({
                'latLng': pos
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        $('#address').val(results[0].formatted_address);
                    } else {
                        alert('No results found');
                    }
                } else {
                    alert('Geocoder failed due to: ' + status);
                }
            });

            google.maps.event.addListener(marker, 'dragend', function (evt) {
                $('#longitude').val(evt.latLng.lng());
                $('#latitude').val(evt.latLng.lat());
                geocoder.geocode({
                    'latLng': evt.latLng
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            $('#address').val(results[0].formatted_address);
                        } else {
                            alert('No results found');
                        }
                    } else {
                        alert('Geocoder failed due to: ' + status);
                    }
                });
            });
        };

        var tryAPIGeolocation = function () {
            jQuery.post("https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyBLLIJXdpdqy5nJ1pNGxKMyaLFFLNOCqds", function (success) {
                apiGeolocationSuccess({coords: {latitude: success.location.lat, longitude: success.location.lng}});
            })
                .fail(function (err) {
                    alert("API Geolocation error! \n\n" + err);
                    console.log(err);
                });
        };

        var browserGeolocationSuccess = function (position) {
            console.log(lat)
            geocoder = new google.maps.Geocoder();
            //alert("Browser geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);

            if(lat ==0 || lng ==0){
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                $('#longitude').val(position.coords.longitude);
                $('#latitude').val(position.coords.latitude);
            }else{
                var pos ={
                    lat:lat,
                    lng:lng
                }
                $('#longitude').val(lng);
                $('#latitude').val(lat);
            }


//            infoWindow.setPosition(pos);
//            infoWindow.setContent('Location found.');
//            infoWindow.open(map);
            map.setCenter(pos);

            marker = new google.maps.Marker({
                position: pos,
                map: map,
                title: 'Hello World!',
                draggable: true,
            });

            geocoder.geocode({
                'latLng': pos
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        $('#address').val(results[0].formatted_address);
                    } else {
                        alert('No results found');
                    }
                } else {
                    alert('Geocoder failed due to: ' + status);
                }
            });

            google.maps.event.addListener(marker, 'dragend', function (evt) {
                $('#longitude').val(evt.latLng.lng());
                $('#latitude').val(evt.latLng.lat());
                geocoder.geocode({
                    'latLng': evt.latLng
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            $('#address').val(results[0].formatted_address);
                        } else {
                            alert('No results found');
                        }
                    } else {
                        alert('Geocoder failed due to: ' + status);
                    }
                });
            });

        };

        var browserGeolocationFail = function (error) {
            switch (error.code) {
                case error.TIMEOUT:
                    alert("Browser geolocation error !\n\nTimeout.");
                    break;
                case error.PERMISSION_DENIED:
                    if (error.message.indexOf("Only secure origins are allowed") == 0) {
                        tryAPIGeolocation();
                    }
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Browser geolocation error !\n\nPosition unavailable.");
                    break;
            }
        };

        var tryGeolocation = function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    browserGeolocationSuccess,
                    browserGeolocationFail,
                    {maximumAge: 50000, timeout: 20000, enableHighAccuracy: true});
            }
        };
    </script>

@endsection

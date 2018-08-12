<!-- REQUIRED JS SCRIPTS -->

<!-- JQuery and bootstrap are required by Laravel 5.3 in resources/assets/js/bootstrap.js-->
<!-- Laravel App -->
<script src="{{ url (mix('/js/app.js')) }}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery/jquery.js')}}"></script>
<script src="{{asset('vendors/bootstrap/js/bootstrap.js')}}"></script>
<script src="{{asset('vendors/jquerybootgrid/jquery.bootgrid.js')}}"></script>
<script src="{{asset('vendors/sweetalert/sweetalert2.js')}}"></script>
<script src="{{asset('vendors/toastr/toastr.js')}}"></script>
<script src="{{asset('vendors/waitme/waitMe.js')}}"></script>
<script src="{{asset('vendors/validation/validate.min.js')}}"></script>
<script src="{{asset('vendors/validation/additional_methods.min.js')}}"></script>
<script src="{{asset('vendors/moment/moment.js')}}"></script>
<script src="{{asset('vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{asset('vendors/select2/js/select2.full.js')}}"></script>

<script src="{{asset('js/base.js')}}"></script>
@yield('customscripts');

<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->

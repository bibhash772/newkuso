<script src="{{ asset('js/vendor-all.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{asset('js/pcoded.min.js') }}"></script>
<script src="{{asset('plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>  
<script src="{{asset('js/pages/form-validation.js') }}" ad-tw-fact="{{route('admin.two-factor-validate')}}"></script>  
@extends('layouts.auth')

@section('content')
<section >
    <div class="auth-wrapper">
    <div class="auth-content loginNew container">
        <div class="card">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="card-body">
                        @if(Session::has('login_error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ Session::get('login_error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif
                        <div class="_loginFormAlert"></div>
                        <div class="_firstloginboxbeforeOTP">
                            <h4 class="mb-3 f-w-400">Login into your account</h4>
                            <form id="loginform" action="/login" method="post">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="feather icon-mail"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="email" placeholder="Email">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" name="password" placeholder="Password">
                                </div>
                                <div class="form-group text-left mt-2">
                                    <div class="checkbox checkbox-primary d-inline">
                                         <input type="checkbox" name="remember_me" id="remember_me">
                                        <label for="remember_me" class="cr"> Remember me</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" id="_adminloginBtN">Submit</button>
                            </form>
                            <p class="mb-2 text-muted">Forgot password? <a href="/password/reset" class="f-w-400">Reset</a></p>
                        </div>

                        <div class="_adminloginOtpBox hidden">
                            <h4 class="mb-3 f-w-400">Enter OTP</h4>
                            <form id="_loginOTPform" action="{{route('admin.verify_otp_login')}}" class="digit-group" data-group-name="digits" autocomplete="off">
                                <input type="hidden" name="email" value="" id="_adminEmailFieldOtp">
                                <input type="text" id="digit-1" name="otp_inp[]" data-next="digit-2" class="digit-groupinput" />
                                <input type="text" id="digit-2" name="otp_inp[]" data-next="digit-3" class="digit-groupinput" data-previous="digit-1" />
                                <input type="text" id="digit-3" name="otp_inp[]" data-next="digit-4" class="digit-groupinput" data-previous="digit-2" />
                                <input type="text" id="digit-4" name="otp_inp[]" data-next="digit-5" class="digit-groupinput" data-previous="digit-3" />
                                <input type="text" id="digit-5" name="otp_inp[]" data-next="digit-6" class="digit-groupinput" data-previous="digit-4" />
                                <button type="button" class="btn btn-primary mt-20" id="_adminloginOtpBtN" disabled="">Submit</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
<style type="text/css">
    .loginNew .card{max-width: 360px; margin: 0 auto; width: 100%;}
    .digit-groupinput {
        width: 45px;
        height: 50px;
        background-color: #a5c1ca;
        border: none;
        line-height: 50px;
        text-align: center;
        font-size: 24px;
        font-family: 'Raleway', sans-serif;
        font-weight: 200;
        color: white;
        margin: 0 2px;
    }
</style>
@section('footer-script')
<script type="text/javascript">
    $(document).ready(function(){
        $('.digit-group').find('input').each(function() {
            $(this).attr('maxlength', 1);
            $(this).on('keyup', function(e) {
                var parent = $($(this).parent());
                
                if(e.keyCode === 8 || e.keyCode === 37) {
                    var prev = parent.find('input#' + $(this).data('previous'));
                    
                    if(prev.length) {
                        $(document).find("#_adminloginOtpBtN").prop("disabled", true);
                        $(prev).select();
                    }
                } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                    var next = parent.find('input#' + $(this).data('next'));
                    
                    if(next.length) {
                        $(next).select();
                    }else{
                        $(document).find("#_adminloginOtpBtN").removeAttr("disabled");
                    }
                }
            });
        });

        /* OTP Form submit */
        $(document).on("click", "#_adminloginOtpBtN", function(){

            $.ajax({
                url:"{{route('admin.verify_otp_login')}}",
                type: "POST",
                data: $("#_loginOTPform").serialize(),
                dataType: "json",
                async: false,
                cache:false,
                beforeSend: function(){
                   $("#_adminloginOtpBtN").html("Processing....").prop("disabled", true);
                },
                success:function(res){
                    $(document).find("._loginFormAlert").html('');
                    $("#_adminloginOtpBtN").html("Submit").removeAttr("disabled");
                    if(res.status == true){
                        window.location.href = "{{route('admin.admin-dashboard')}}";
                    }else{
                        $(document).find("._loginFormAlert").html('<div class="alert alert-danger alert-dismissible fade show">'+res.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>');
                    }
                }
            });
        })
    });
</script>
@endsection

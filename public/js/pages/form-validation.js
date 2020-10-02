'use strict';
let login_two_factor_url    = $(document.currentScript).attr('ad-tw-fact');
let login_otp_verify_url    = $(document.currentScript).attr('ad-vrfy-otp');
let login_ad_dash_url       = $(document.currentScript).attr('ad-dash-url');

$(document).ready(function() {

    $(function() {
        // [ Add phone validator ]
        $.validator.addMethod(
            'phone_format',
            function(value, element) {
                return this.optional(element) || /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/.test(value);
            },
            'Invalid phone number.'
        );

        // [ Initialize validation ]
        $('#loginform').validate({
            ignore: '.ignore, .select2-input',
            focusInvalid: false,
            rules: {
                'email': {
                    required: true,
                    email: true
                },
                'password': {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
            },

            // Errors //

            errorPlacement: function errorPlacement(error, element) {
                var $parent = $(element).parents('.form-group');

                // Do not duplicate errors
                if ($parent.find('.jquery-validation-error').length) {
                    return;
                }

                $parent.append(
                    error.addClass('jquery-validation-error small form-text invalid-feedback')
                );
            },
            highlight: function(element) {
                var $el = $(element);
                var $parent = $el.parents('.form-group');

                $el.addClass('is-invalid');

                // Select2 and Tagsinput
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            },
            submitHandler: function(form) {
                $.ajax({
                    url:login_two_factor_url,
                    type: "POST",
                    data: new FormData(form),
                    processData: false,
                    contentType:false,
                    beforeSend: function(){
                       $("#_adminloginBtN").html("Processing....").prop("disabled", true);
                    },
                    success:function(res){
                        $(document).find("._loginFormAlert").html('');
                        $("#_adminloginBtN").html("Submit").removeAttr("disabled");
                        if(res.status == true){
                            $("#_adminEmailFieldOtp").val(res.data.email);
                            $("._firstloginboxbeforeOTP").addClass('hidden');
                            $("._adminloginOtpBox").removeClass('hidden');
                        }else{
                            $(document).find("._loginFormAlert").html('<div class="alert alert-danger alert-dismissible fade show">'+res.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>');
                        }
                    }
                });
            }
        });

        // validation for forgot passsword 

        $('#forgot-password-form').validate({
            ignore: '.ignore, .select2-input',
            focusInvalid: false,
            rules: {
                'email': {
                    required: true,
                    email: true
                }
            },

            // Errors //

            errorPlacement: function errorPlacement(error, element) {
                var $parent = $(element).parents('.input-group');

                // Do not duplicate errors
                if ($parent.find('.jquery-validation-error').length) {
                    return;
                }

                $parent.append(
                    error.addClass('jquery-validation-error small form-text invalid-feedback')
                );
            },
            highlight: function(element) {
                var $el = $(element);
                var $parent = $el.parents('.input-group');

                $el.addClass('is-invalid');

                // Select2 and Tagsinput
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.input-group').find('.is-invalid').removeClass('is-invalid');
            }
        });
          // Reset password

        $('#reset_password').validate({
            ignore: '.ignore, .select2-input',
            focusInvalid: false,
            rules: {
                'email': {
                    required: true,
                    email: true
                },
                'password': {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                'password_confirmation': {
                    required: true,
                    minlength: 6,
                    equalTo: 'input[name="password"]'
                }
            },

            // Errors //

            errorPlacement: function errorPlacement(error, element) {
                var $parent = $(element).parents('.input-group');

                // Do not duplicate errors
                if ($parent.find('.jquery-validation-error').length) {
                    return;
                }

                $parent.append(
                    error.addClass('jquery-validation-error small form-text invalid-feedback')
                );
            },
            highlight: function(element) {
                var $el = $(element);
                var $parent = $el.parents('.input-group');

                $el.addClass('is-invalid');

                // Select2 and Tagsinput
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.input-group').find('.is-invalid').removeClass('is-invalid');
            }
        });

        // profile for validation
         $('#profile-setting').validate({
            ignore: '.ignore, .select2-input',
            focusInvalid: false,
            rules: {
                'first_name': {
                    required: true,
                    maxlength: 200
                },
                'last_name': {
                    required: true,
                     maxlength: 200
                },
                'phone_no': {
                    required: true,
                    phone_format: true,
                    maxlength: 20
                },
                'city': {
                    required: true,
                    maxlength: 200
                },
                'state': {
                    required: true,
                    maxlength: 200
                },
                'zip_code': {
                    required: true,
                    maxlength: 200
                },
                'country': {
                    required: true,
                    maxlength: 200
                },
                'address': {
                    required: true,
                    maxlength: 200
                },
            },

            // Errors //

            errorPlacement: function errorPlacement(error, element) {
                var $parent = $(element).parents('.form-group');

                // Do not duplicate errors
                if ($parent.find('.jquery-validation-error').length) {
                    return;
                }

                $parent.append(
                    error.addClass('jquery-validation-error small form-text invalid-feedback')
                );
            },
            highlight: function(element) {
                var $el = $(element);
                var $parent = $el.parents('.form-group');

                $el.addClass('is-invalid');

                // Select2 and Tagsinput
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            }
        });
        /**
         * Chnage paasword for admin and lab executive
        */
         $('#chnage-password').validate({
            ignore: '.ignore, .select2-input',
            focusInvalid: false,
            rules: {
                'current_password': {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                'password': {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                'cpassword': {
                    required: true,
                    minlength: 6,
                    equalTo: 'input[name="password"]'
                }
            },

            // Errors //

            errorPlacement: function errorPlacement(error, element) {
                var $parent = $(element).parents('.form-group');

                // Do not duplicate errors
                if ($parent.find('.jquery-validation-error').length) {
                    return;
                }

                $parent.append(
                    error.addClass('jquery-validation-error small form-text invalid-feedback')
                );
            },
            highlight: function(element) {
                var $el = $(element);
                var $parent = $el.parents('.form-group');

                $el.addClass('is-invalid');

                // Select2 and Tagsinput
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            }
        });
        /**
         * validation for user 
        */
       // profile for validation
         $('#add-user').validate({
            ignore: '.ignore, .select2-input',
            focusInvalid: false,
            rules: {
                'first_name': {
                    required: true,
                    maxlength: 200
                },
                'last_name': {
                    required: true,
                     maxlength: 200
                },
                'phone_no': {
                    required: true,
                    phone_format: true,
                    maxlength: 20
                },
                'city': {
                    required: true,
                    maxlength: 200
                },
                'state': {
                    required: true,
                    maxlength: 200
                },
                'zip_code': {
                    required: true,
                    maxlength: 200
                },
                'country': {
                    required: true,
                    maxlength: 200
                },
                'address': {
                    required: true,
                    maxlength: 200
                },
                'email': {
                    required: true,
                    email: true,
                    remote: {
                        url: "/api/check-email-exist",
                        type: "post",
                        data: {
                            email: function() {
                                return $( "#email" ).val();
                            }
                        }, 

                    }
                },
            },

            // Errors //

            errorPlacement: function errorPlacement(error, element) {
                var $parent = $(element).parents('.form-group');

                // Do not duplicate errors
                if ($parent.find('.jquery-validation-error').length) {
                    return;
                }

                $parent.append(
                    error.addClass('jquery-validation-error small form-text invalid-feedback')
                );
            },
            highlight: function(element) {
                var $el = $(element);
                var $parent = $el.parents('.form-group');

                $el.addClass('is-invalid');

                // Select2 and Tagsinput
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            },
            messages: {
                email: {
                    remote: "Email address already exists."
                },
                
            },
        });
        // generate password
        $('#generatepassword').validate({
            ignore: '.ignore, .select2-input',
            focusInvalid: false,
            rules: {
                'password': {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                'confirmpassword': {
                    required: true,
                    equalTo: '#password',
                    minlength: 6,
                    maxlength: 20
                },
            },

            // Errors //

            errorPlacement: function errorPlacement(error, element) {
                var $parent = $(element).parents('.input-group');

                // Do not duplicate errors
                if ($parent.find('.jquery-validation-error').length) {
                    return;
                }

                $parent.append(
                    error.addClass('jquery-validation-error small form-text invalid-feedback')
                );
            },
            highlight: function(element) {
                var $el = $(element);
                var $parent = $el.parents('.input-group');

                $el.addClass('is-invalid');

                // Select2 and Tagsinput
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.input-group').find('.is-invalid').removeClass('is-invalid');
            }
        });
        // [ validation for add new kit ]
        $('#add-kit').validate({
            ignore: '.ignore, .select2-input',
            focusInvalid: false,
            rules: {
                'kit_code': {
                    required: true,
                    minlength:8,
                },
            },

            // Errors //

            errorPlacement: function errorPlacement(error, element) {
                var $parent = $(element).parents('.form-group');

                // Do not duplicate errors
                if ($parent.find('.jquery-validation-error').length) {
                    return;
                }

                $parent.append(
                    error.addClass('jquery-validation-error small form-text invalid-feedback')
                );
            },
            highlight: function(element) {
                var $el = $(element);
                var $parent = $el.parents('.form-group');

                $el.addClass('is-invalid');

                // Select2 and Tagsinput
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            }
        });
         $('#taxanomy').validate({
            ignore: '.ignore, .select2-input',
            focusInvalid: false,
            rules: {
                'taxanomyfile': {
                    required: true
                }
            },

            // Errors //

            errorPlacement: function errorPlacement(error, element) {
                var $parent = $(element).parents('.form-group');

                // Do not duplicate errors
                if ($parent.find('.jquery-validation-error').length) {
                    return;
                }

                $parent.append(
                    error.addClass('jquery-validation-error small form-text invalid-feedback')
                );
            },
            highlight: function(element) {
                var $el = $(element);
                var $parent = $el.parents('.form-group');

                $el.addClass('is-invalid');

                // Select2 and Tagsinput
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            }
        });
         $('#sample-edit').validate({
            ignore: '.ignore, .select2-input',
            focusInvalid: false,
            rules: {
                'sample_date': {
                    required: true
                },
                'sample_time': {
                    required: true
                },
                'latitude': {
                    required: true
                },
                'longitude': {
                    required: true
                },
                'is_public': {
                    required: true
                },
                'site_name': {
                    required: true
                },
                'sample_notes': {
                    required: true
                },
                'sample_received_date': {
                    required: true
                },
            },

            // Errors //

            errorPlacement: function errorPlacement(error, element) {
                var $parent = $(element).parents('.form-group');

                // Do not duplicate errors
                if ($parent.find('.jquery-validation-error').length) {
                    return;
                }

                $parent.append(
                    error.addClass('jquery-validation-error small form-text invalid-feedback')
                );
            },
            highlight: function(element) {
                var $el = $(element);
                var $parent = $el.parents('.form-group');

                $el.addClass('is-invalid');

                // Select2 and Tagsinput
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            }
        });
    });
});

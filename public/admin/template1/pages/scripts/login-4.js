var Login = function () {
    var handleLogin = function () {
        $('.login-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true
                },
                remember: {
                    required: false
                }
            },

            messages: {
                username: {
                    required: "Username is required."
                },
                password: {
                    required: "Password is required."
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                $('.alert-danger', $('.login-form')).show();
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function (error, element) {
                error.insertAfter(element.closest('.input-icon'));
            },

            submitHandler: function (form) {
                form.submit();
            }
        });

        $('.login-form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('.login-form').validate().form()) {
                    $('.login-form').submit();
                }
                return false;
            }
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            handleLogin();

            // init background slide images
            $.backstretch([
                    "/admin/template1/pages/media/bg/1.jpg",
                    "/admin/template1/pages/media/bg/2.jpg",
                    "/admin/template1/pages/media/bg/3.jpg",
                    "/admin/template1/pages/media/bg/4.jpg"
                ], {
                    fade: 1000,
                    duration: 8000
                }
            );
        }
    };

}();

jQuery(document).ready(function () {
    Login.init();

    var verifyimg = $(".verifyimg").attr("src");
    $(".reloadverify").click(function () {
        if (verifyimg.indexOf('?') > 0) {
            $(".verifyimg").attr("src", verifyimg + '&random=' + Math.random());
        } else {
            $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
        }
    });

    $('#username').blur(function () {
        if ($(this).val() == '') {
            $('#user-check').show(1000);
        } else {
            $('#user-check').hide(1000);
        }
    });

    $('#password').blur(function () {
        if ($(this).val() == '') {
            $('#password-check').show(1000);
        } else {
            $('#password-check').hide(1000);
        }
    });

    $('#verify').blur(function () {
        if ($(this).val() == '') {
            $('#verify-check').show(1000);
        } else {
            $('#verify-check').hide(1000);
        }
    });

    $("#loginBtn").click(function () {
        $.ajax({
            type: "POST",
            url: "/admin/Account/checkVerify",
            dataType: "json",
            data: {
                verify: $('#verify').val()
            },
            success: function (rJson) {
                if (rJson.success === false) {
                    $("#verify-tips").slideDown();
                    $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
                } else {
                    $.ajax({
                        type: "POST",
                        url: "/admin/Account/checkLogin",
                        dataType: "json",
                        data: {
                            username: $('#username').val(),
                            password: $('#password').val()
                        },
                        success: function (rJson) {

                            if (rJson.success === false) {
                                $('#errorTip').text(rJson.data.error);
                                $("#login-error").slideDown();
                            }else{

                                location.href= "/admin/Index/index";
                            }

                        }
                    });
                }
            }
        });
    });

    $('#verify').keypress(function(e) {
        // 回车键事件
        if(e.which == 13) {
            $("#loginBtn").click();
        }
    });
});
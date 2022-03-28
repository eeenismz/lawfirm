"use strict";
$(document).ready(function(){
    $("#myform").validate({
        ignore: ":hidden",
        rules: {
            name: {
                required: true,
                minlength: 1
            },
            furikana: {
                required: true,
                minlength: 1
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                minlength: 1
            },
            message: {
                required: true,
                minlength: 5
            }
        },
        submitHandler: function (form) {
            $('#submit').prop( "disabled", true );
            $('.loader-wrap').fadeIn();
            $("body").css({"overflow":"hidden"});
            $.post("mailer.php",
                {
                    companyname: $('#companyname').val(),
                    name: $('#name').val(),
                    furikana: $('#furikana').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val(),
                    message: $('#message').val()
                },
                function(data, status){
                    console.log(data);
                    console.log(status);
                    if(status == "success"){
                        $("#alert_mail").html("お問い合わせありがとうございました。メールを送信致しました。");
                        $("#mail_alert_class").addClass("alert alert-success alert-dismissible");
                        $("#mail_alert_class").removeClass("d-none");
                        $('#myform').trigger("reset");
                        $('#submit').prop( "disabled", false );
                        $('.loader-wrap').fadeOut();
                        $("body").css({"overflow":"visible"});
                    }
                    else
                    {
                        $("#alert_mail").html("<strong>Failed! </strong>There is Some Error.");
                        $("#mail_alert_class").addClass("alert alert-danger alert-dismissible");
                        $("#mail_alert_class").removeClass("d-none");
                        $('#submit').prop( "disabled", false );
                        $('.loader-wrap').fadeOut();
                        $("body").css({"overflow":"visible"});
                    }
                });
            return false; // required to block normal submit since you used ajax
        }
    });
    $( "#mail_alert_class .close" ).click(function() {
        $("#mail_alert_class").addClass("d-none");
        $("#alert_mail").html("");
    });
});

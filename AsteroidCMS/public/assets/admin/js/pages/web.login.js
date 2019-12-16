"use strict";
var login = function() {
    return {
        init: function() {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            $("#kt_login_signin_submit").click(function(t) {
                t.preventDefault();
                var e = $(this),
                    n = $(this).closest("form");
                n.validate({
                    rules: {
                        username: {
                            required: !0
                        },
                        password: {
                            required: !0
                        }
                    }
                }), n.valid() && (e.addClass("kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light").attr("disabled", !0), n.ajaxSubmit({
                    url: "",
                    success: function() {
                        var user = $("[name=username]").val();
                        var pass = $("[name=password]").val();

                        if(!$("kt_login_signin_submit").hasClass("checkPin")) {
                            self.ajax_manager.post("/housekeeping/api/login/request", {username: user, password: pass}, function (result) {
                                setTimeout(function(i) {
                                    e.removeClass("kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light").attr("disabled", !1)
                                }, 2e3)

                                if (result.location) {
                                    window.location = result.location;
                                    return null;
                                }

                                if(result.modal === 'pin'){
                                    login.pin(user, pass);
                                }
                            });
                        }
                    }
                }))
            })
        },

        pin: function(user, pass){
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            $("#pincode").show();
            $(".loginform").hide();
            $("#kt_login_signin_submit").addClass('checkPin');
            $("#kt_login_signin_submit").html('Log in');

            $(".checkPin").unbind().click(function() {
                var pin = $("[name=pincode]").val();

                self.ajax_manager.post("/housekeeping/api/login/request", {username: user, password: pass, pincode: pin});
            });
        }
    }
}();
jQuery(document).ready(function() {
    login.init()
});
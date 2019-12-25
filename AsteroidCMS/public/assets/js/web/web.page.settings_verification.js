function WebPageSettingsVerificationInterface(main_page)
{
    this.main_page = main_page;

    /*
    * Generic function
    * */
    this.init = function ()
    {
        var self = this;
        var page_container = this.main_page.get_page_container();

        // Init type select
        page_container.find(".type-select").selectric({
            theme: "web",
            onChange: function (event)
            {
                self.switch_type(event.value);
            }
        });

        // Init questions selects
        page_container.find(".questions-select").selectric({
            theme: "web"
        });

        // Checkbox change event
        page_container.find("#enable-verification-target").change(function ()
        {
            self.switch_enable($(this).is(":checked"));
        });

        // Submit form
        page_container.find("form").submit(function (event)
        {
            event.preventDefault();

            var current_verification_type_enabled = page_container.find("#verification_enabled").val();
            var verification_enabled = page_container.find("input[name = 'enable_verification']").is(":checked");
            var verification_data = {
                enabled: false,
                type: null,
                data: null,
                current_password: page_container.find("input[name = 'current_password']").val()
            };
          
            if(isEmpty(verification_data.current_password))
            {
                Web.notifications_manager.create("error", Locale.web_page_settings_verification_fill_password, Locale.web_page_settings_verification_oops);
                return;
            }
          
            if (verification_enabled)
            {
                var verification_type = page_container.find("select[name = 'twosteps_login_type']").val();
                
                if (verification_type === "app")
                {
                    if (current_verification_type_enabled === "pincode")
                    {
                        Web.dialog_manager.create("default", Locale.web_page_settings_verification_2fa_on, Locale.web_page_settings_verification_oops, null, null, function ()
                        {
                            app_callback();
                        });
                    }
                    else if (isEmpty(current_verification_type_enabled))
                        app_callback();

                    function app_callback ()
                    {
                        Web.dialog_manager.create("confirm", Locale.web_page_settings_verification_2fa_secretkey, Locale.web_page_settings_verification_2fa_authcode, null, "pincode", function (result)
                        {
                            verification_data.type = "app";
                            verification_data.data = page_container.find("#twosteps_login_data_code").val();
                            verification_data.enabled = verification_enabled;
                            verification_data.input = result.toString();
                            
                            self.send_data(verification_data);
                        });
                    }
                }
                else if (verification_type === "pincode")
                {
                    if (current_verification_type_enabled === "app")
                    {
                        Web.dialog_manager.create("default", Locale.web_page_settings_verification_2fa_on, Locale.web_page_settings_verification_oops, null, null, function ()
                        {
                            questions_callback();
                        });
                    }
                    else if (current_verification_type_enabled === "pincode")
                    {
                        Web.dialog_manager.create("default", Locale.web_page_settings_verification_pincode_on, Locale.web_page_settings_verification_oops, null, null, function ()
                        {
                            questions_callback();
                        });
                    }
                    else
                        questions_callback();

                    function questions_callback ()
                    {
                        var twosteps_login_pincode = page_container.find("input[name = 'twosteps_login_pincode']").val();

                        verification_data.type = "pincode";
                        verification_data.data = twosteps_login_pincode;
                        verification_data.enabled = verification_enabled;

                        self.send_data(verification_data);
                    }
                }
                else
                {
                    verification_data.enabled = false;
                    self.send_data(verification_data);
                }
            }
            else if (current_verification_type_enabled == "app")
            {
                Web.dialog_manager.create("confirm", Locale.web_page_settings_verification_2fa_off, Locale.web_page_settings_verification_2fa_authcode, null, "pincode", function (result)
                {
                    verification_data.type = "app";
                    verification_data.enabled = false;
                    verification_data.data = page_container.find("#twosteps_login_data_code").val();
                    verification_data.input = result.toString();
                  
                    self.send_data(verification_data);
                });
            } 
            else if (current_verification_type_enabled == "pincode")
            {
                Web.dialog_manager.create("confirm", Locale.web_page_settings_verification_pincode_off, Locale.web_page_settings_verification_pincode, null, "pincode", function (result)
                {
                    verification_data.type = "pincode";
                    verification_data.enabled = false;
                    verification_data.input = result.toString();
                  
                    self.send_data(verification_data);
                });
            }
            else
            {
                Web.notifications_manager.create("error", Locale.web_page_settings_verification_switch, Locale.web_page_settings_verification_oops);
            }
        });
    };

    /*
    * Custom functions
    * */
    this.send_data = function (data)
    {
        Web.ajax_manager.post("/settings/verification/validate", data);
    };

    this.switch_enable = function (enabled)
    {
        if (enabled)
            this.main_page.get_page_container().find(".verification-container").show();
        else
            this.main_page.get_page_container().find(".verification-container").hide();
    };

    this.switch_type = function (type)
    {
        this.main_page.get_page_container().find(".verification-selected[data-method != '" + type + "']:visible").hide();
        this.main_page.get_page_container().find(".verification-selected[data-method = '" + type + "']").show();
    };
}
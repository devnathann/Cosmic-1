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
                Web.notifications_manager.create("error", "Vul je wachtwoord in!", "Oeps..");
                return;
            }
          
            if (verification_enabled)
            {
                var verification_type = page_container.find("select[name = 'twosteps_login_type']").val();
                
                if (verification_type === "app")
                {
                    if (current_verification_type_enabled === "pincode")
                    {
                        Web.dialog_manager.create("default", "Op dit moment staat Google Authenticatie ingesteld op jouw account.  Om een ander verificatie middel te gebruiken dien je eerst je oude verificatie te verwijderen!", "Oeps..", null, null, function ()
                        {
                            app_callback();
                        });
                    }
                    else if (isEmpty(current_verification_type_enabled))
                        app_callback();

                    function app_callback ()
                    {
                        Web.dialog_manager.create("confirm", "Heb je de QR-code gescand op je mobiel? Vul alleen nog even de secretkey in uit de Google Authenticatorom je account te bevestigen!", "Authenticatie code", null, "pincode", function (result)
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
                        Web.dialog_manager.create("default", "Op dit moment heb je Google Authenticatie ingesteld op jouw account. Om een ander verificatie middel te gebruiken dien je eerst je oude verificatie te verwijderen!", "Oeps..", null, null, function ()
                        {
                            questions_callback();
                        });
                    }
                    else if (current_verification_type_enabled === "pincode")
                    {
                        Web.dialog_manager.create("default", "Op dit moment heb je een pincode ingesteld op jouw account. Om een ander verificatie middel te gebruiken dien je eerst je oude verificatie te verwijderen!", "Oeps..", null, null, function ()
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
                Web.dialog_manager.create("confirm", "Om de Google Authenticatie uit te schakelen vragen wij je om de secretcode uit de generator in te vullen.", "Authenticatie code", null, "pincode", function (result)
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
                Web.dialog_manager.create("confirm", "Om de pincode authenticatie uit te schakelen vragen wij je om je pincode in te vullen.", "Pincode code", null, "pincode", function (result)
                {
                    verification_data.type = "pincode";
                    verification_data.enabled = false;
                    verification_data.input = result.toString();
                  
                    self.send_data(verification_data);
                });
            }
            else
            {
                Web.notifications_manager.create("error", "Selecteer de switch button om een authenticatie methode in te schakelen!", "Oops..");
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
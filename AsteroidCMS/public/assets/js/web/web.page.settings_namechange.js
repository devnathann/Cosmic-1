function WebPageSettingsNamechangeInterface(main_page)
{
    this.main_page = main_page;
    /*
    * Generic function
    * */
    this.init = function ()
    {
        var self = this;
        var page_container = this.main_page.get_page_container();

        page_container.find("#username").keyup(function ()
        {

            var namechange  = page_container.find("#username");
            var button  = page_container.find("#changeButton");

            var givenString = namechange.val();

            if (givenString.length > 0) {
                Web.ajax_manager.post("/settings/namechange/availability", {username: givenString}, function (result) {
                    if(givenString !== User.username) {
                        if (result.status !== "unavailable") {
                            button.removeAttr('disabled', 'disabled').html(Locale.web_page_settings_namechange_request);
                        } else {
                            button.attr('disabled', 'disabled').html(Locale.web_page_settings_namechange_not_available);
                        }
                    } else {
                        button.attr('disabled', 'disabled').html(Locale.web_page_settings_namechange_not_available);
                    }
                });
            } else {
                button.attr('disabled', 'disabled').html(Locale.web_page_settings_namechange_choose_name);
            }
        });

    };

}
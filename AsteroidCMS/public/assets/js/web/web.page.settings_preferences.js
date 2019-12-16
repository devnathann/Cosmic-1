function WebPageSettingsInterface(main_page)
{
    this.main_page = main_page;
    /*
    * Generic function
    * */
    this.init = function ()
    {
        var self = this;
        var page_container = this.main_page.get_page_container();
	  
        // Checkbox change event
        page_container.find(".settings").change(function ()
        {
            var post = $(this).attr("data-id");
            var type = this.checked;
			
			var array = ["hide_inroom", "hide_staff", "hide_online", "hide_last_online", "hide_home"]

			if(jQuery.inArray(post, array) !== -1) {
				type = type ? false : true;
			}
		
            var dataString = {post: post, type: type};

            self.send_data(dataString);
        });
      
    };

    /*
    * Custom functions
    * */
    this.send_data = function (data)
    {
        Web.ajax_manager.post("/settings/preferences/validate", data);
    };

}
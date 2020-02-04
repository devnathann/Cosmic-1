function WebPagesManagerInterface()
{
    this.current_page_url = null;
    this.current_page_interface = null;
    this.last_page_url = "home";
    this.page_container = null;

    /*
    * Manager initialization
    * */
    this.init = function ()
    {
        var self = this;

        this.page_container = $(".page-container");
        this.current_page_url = window.location.pathname.substr(1) + window.location.search;

        this.current_page_interface = new WebPageInterface(this, this.page_container.attr("data-page"));
        this.current_page_interface.assign_interface();

        Web.customforms_manager.init_forms();

        if (this.current_page_url === "") {
            this.current_page_url = "home";
        }

        if (this.current_page_url.match(/^hotel/) && User.is_logged) {
            Web.hotel_manager.open_hotel(this.current_page_url);
        }
      
        History.Adapter.bind(window, "statechange", function ()
        {
            var state = History.getState();
            var url = state.url.replace(document.location.origin, "").substring(1);

            if (self.current_page_url !== url)
            {
                if (url === "/") {
                    self.load("home", null, false, null, false);
                } else {
                    self.load("/" + url, null, false, null, false);
                }
            }
            self.current_page_url = url;
        });
    };

    /*
    * History push
    * */
    this.push = function (url, title, history_replace)
    {
        url = url.replace(/^\/|\/$/g, "");
        this.current_page_url = url;

        if (!history_replace) {
            History.pushState(null, title ? title : Site.name, "/" + url);
        } else {
            History.replaceState(null, title ? title : Site.name, "/" + url);
        }
    };

    /*
    * Load page
    * */
    this.load = function (url, data, scroll, callback, history_push, history_replace)
    {
      
        if (scroll === undefined) {
            scroll = true
        }

        if (history_push === undefined) {
            history_push = true
        }

        if (history_replace === undefined) {
            history_replace = false
        }
        
        var self = this;
        var body = $("body");

        if (url === "")
            url = "home";
      
        if(url.charAt(0)  !== "/") {
            url = "/" + url;
        }
    
        this.last_page_url = this.current_page_url;

        if (!url.match(/^\/hotel/))
        {
            PageLoading.show();

            $.ajax({
                type: "get",
                url: url,
                dataType: "json",
                error: function (request, status, error) {
                    PageLoading.hide();
                    Web.notifications_manager.create("error", error, request.responseText);
                }
            }).done(function (result)
            {
                PageLoading.hide();

                // Change full page
                if (result.location)
                {
                    window.location = result.location;
                    return null;
                }

                // Create notification
                if (!isEmpty(result.status) && !isEmpty(result.message))
                    Web.notifications_manager.create(result.status, result.message, (result.title ? result.title : null), (Number.isInteger(result.timer) ? result.timer : undefined), (result.link ? result.link : null));
            
              
                // Create dialog
                if (result.dialog) {
                    Web.dialog_manager.create("default", result.dialog, result.title, null, null);
                    return;
                }
                

                // Change page
                else if (result.loadpage)
                    self.load(result.loadpage);

                // Replace page
                else if (result.replacepage)
                    self.load(result.replacepage, null, true, null, true, true);

                // Build new page
                else
                {
                    self.current_page_interface = new WebPageInterface(self, result.id, scroll, result);
                    self.current_page_interface.build();

                    Web.customforms_manager.init_forms();

                    if (typeof callback === "function")
                        callback(result);
                }

                // Hide hotel
                if (body.hasClass("hotel-visible"))
                    body.removeClass("hotel-visible");

                // Push history & change document title
                if (window.location.pathname + window.location.search === "/" + url)
                    return;

                document.title = result.title;
                self.push(url, result.title, false);
            });
        }
        else if (User.is_logged)
        { 
            Web.hotel_manager.open_hotel(url.replace("hotel?", "").replace("hotel", ""));
            self.push(url, "Hotel - " + Site.name, false);
        }
    };
}

function WebPageInterface(manager, type, scroll, page_data)
{
    if (scroll === undefined) {
        scroll = true;
    }

    /*
    * Page configuration
    * */
    this.manager = manager;
    this.type = type;
    this.scroll = scroll;
    this.page_data = page_data;
    this.page_interface = null;

    /*
    * Build page
    * */
    this.build = function ()
    {
        if (this.page_data === null)
            return;

        var self = this;

        // Assign page
        self.manager.page_container.attr("data-page", this.type).html(this.page_data.content);

        // Update navigation
        var navigation_container = $(".navigation-container");

        // Set category
        var category = this.type.substr(0, this.type.lastIndexOf("_"));
        if(isEmpty(category))
            category = this.type;

        navigation_container.find(".navigation-item.selected:not([data-category='" + category + "'])").removeClass("selected");
        navigation_container.find(".navigation-item[data-category='" + category + "']").addClass("selected");
      
        if(this.manager.current_page_url.indexOf("forum") >= 0){
        } else {
            if(this.scroll)
                $("html, body").animate({scrollTop: navigation_container.offset().top}, 300);
        }

        // Custom page interface
        this.assign_interface();
    };

    /*
    * Custom interface
    * */
    this.assign_interface = function ()
    {
        if (this.type === "home")
            this.page_interface = new WebPageHomeInterface(this);
        else if (this.type === "registration")
            this.page_interface = new WebPageRegistrationInterface(this);
        else if (this.type === "article")
            this.page_interface = new WebPageArticleInterface(this);
        else if (this.type === "shop")
            this.page_interface = new WebPageShopInterface(this);
        else if (this.type === "shop_offers")
            this.page_interface = new WebPageShopOffersInterface(this);
        else if (this.type === "help_requests")
            this.page_interface = new WebPageHelpRequestsInterface(this);
	    else if (this.type === "help_new")
            this.page_interface = new WebPageHelpRequestsInterface(this);
        else if (this.type === "profile")
            this.page_interface = new WebPageProfileInterface(this);
        else if (this.type === "community_photos")
            this.page_interface = new WebPageCommunityPhotosInterface(this);
        else if (this.type === "community_value")
            this.page_interface = new WebPageCommunityValueInterface(this);
        else if (this.type === "jobs")
            this.page_interface = new WebPageJobsInterface(this);
        else if (this.type === "settings_preferences")
            this.page_interface = new WebPageSettingsInterface(this);
        else if (this.type === "settings_namechange")
            this.page_interface = new WebPageSettingsNamechangeInterface(this);
         else if (this.type === "settings_verification")
            this.page_interface = new WebPageSettingsVerificationInterface(this);
        else if (this.type === "password_claim")
            this.page_interface = new WebPagePasswordClaimInterface(this);
        else if (this.type === "forum")
            this.page_interface = new WebPageForumInterface(this);

        if (this.page_interface !== null)
            this.page_interface.init();
    };

    /*
    * Get page container
    * */
    this.get_page_container = function ()
    {
        return this.manager.page_container;
    };

    /*
    * Events
    * */
    this.update = function ()
    {};
}
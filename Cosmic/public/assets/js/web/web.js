var Web;

function WebInterface()
{
    /*
    * Main elements
    * */
    this.web_document = null;

    /*
    * Managers
    * */
    this.pages_manager = null;
    this.ajax_manager = null;
    this.notifications_manager = null;
    this.hotel_manager = null;
    this.customforms_manager = null;

    /*
    * Main initiation
    * */
    this.init = function ()
    {
        // Assign main elements
        this.web_document = $("body");

        // Initialize managers
        this.hotel_manager = new WebHotelManagerInterface();
        this.hotel_manager.init();
        this.customforms_manager = new WebCustomFormsManagerInterface();
        this.pages_manager = new WebPagesManagerInterface();
        this.ajax_manager = new WebAjaxManagerInterface();
        this.pages_manager.init();
        this.dialog_manager = new WebDialogManagerInterface();
        this.notifications_manager = new WebNotificationsManagerInterface();

        // Handlers
        this.forms_handler();
        this.links_handler();

        // Responsive
        this.init_responsive();

        // Cookies
        this.check_cookies();
    };

    /*
    * Forms
    * */
    this.forms_handler = function ()
    {
        var self = this;
        this.web_document.on("submit", "form:not(.default-prevent)", function (event)
        {
            event.preventDefault();

            if ($(this).attr("method") !== "get")
                self.ajax_manager.post('/' + $(this).attr("action"), new FormData(this), null, $(this));
            else
            {
                var href = $(this).attr("action").replace(Site.url + "/", "").replace(Site.url, "");
                self.pages_manager.load(href + "?" + $(this).serialize());
            }
        });
    };

    /*
    * Links
    * */
    this.links_handler = function ()
    {
        var self = this;
        this.web_document.on("click", "a", function (event)
        {
            if ($(this).attr("href") === "#" || $(this).hasClass("disabled"))
                event.preventDefault();

        }).on("mouseover", "a:not([target])", function (){
            if ($(this).attr("href"))
                if (!$(this).attr("href").match(new RegExp(Site.domain.replace(".", "\."), "g")) && !$(this).attr("href").match(/^#/))
                    $(this).attr("target", "_blank");

        }).on("click", "a:not([target])", function(event)
        {
            event.preventDefault();
            if ($(this).attr("href") !== "#" && $(this).attr("href") !== "javascript:;" && $(this).attr("href") !== "javascript:void(0)" && $(this).attr("href") !== undefined)
            {
                var href = $(this).attr("href").replace(Site.url + "/", "").replace(Site.url, "");
                if (!href)
                    href = "home";

                if (href.match(/^\#([A-z0-9-_]+)$/i))
                    window.location.hash = href;
                else if (window.location.pathname + window.location.search !== "/" + href || window.location.hash)
                    self.pages_manager.load(href);
            }

        }).on("click", ".login-dialog-button", function()
        {
            $.magnificPopup.open({
                items: {
                    type: "inline",
                    src: "#login-dialog"
                },
                mainClass: "my-mfp-zoom-in"
            });
        }).on("keydown", ".rounded-input", function(event)
        {
            var key = event.which;
            if (key == 13) {    
                $('#login-request').click();
            }
        }).on("click", "#login-request", function(event) 
        {
            event.preventDefault();
          
            var verification_data = {
                username: $(".login-form [name=username]").val(),
                password: $(".login-form [name=password]").val(),
                remember_me: $(".login-form [name=remember_me]").val()
            };
            
            $.magnificPopup.close();

            Web.ajax_manager.post("/home/login/request", verification_data, function(result) {

                if(result.status == "pincode_required")
                {
                    setTimeout(function(){ 
                    Web.dialog_manager.create("confirm", Locale.web_fill_pincode, Locale.web_twostep, null, "pincode", function (result)
                    {
                        verification_data.pincode = result.toString();
                        Web.ajax_manager.post("/home/login/request", verification_data);

                        $.magnificPopup.close();
                    });
                    }, 500);
                }
            });
        }).on("click", ".about-dialog-button", function()
        {
            $.magnificPopup.open({
                items: {
                    type: "inline",
                    src: "#about-dialog"
                },
                removalDelay: 300,
                mainClass: "my-mfp-zoom-in"
            });
        }).on("click", "[data-close-popup = 'true']", function()
        {
            $.magnificPopup.close();

        }).on("click", ".fa-flag", function()
        {
            if(User.is_logged)
            {
                var action = $(this).attr("data-report");

                $.magnificPopup.open({
                    items: {
                        type: "inline",
                        src: "#report-item"
                    },
                    removalDelay: 300,
                    mainClass: "my-mfp-zoom-in"
                });

                $("#reportForm").attr('action', 'ajax/report/' + action);
                $('#reportItemid').val($(this).attr("data-id"));
            } else {
                Web.notifications_manager.create("error", Locale.web_login, Locale.web_loggedout);
            }
        }).on("click", ".fa-times-circle", function()
        {
            if(User.is_logged)
            {
                var id = $(this).attr("data-id");
                self.ajax_manager.post("/ajax/report/photo", {itemId: id}, function (result)
                {
                    if(result.status == "success") {
                        $(".photos[data-id=" + id + "]").empty();
                        $.magnificPopup.close();
                    }
                });
            }
        });
    };

    /*
    * Responsive
    * */
    this.init_responsive = function ()
    {
        var self = this;

        // Menu
        this.web_document.find(".navigation-container").after('<nav class="mobile-navigation-container"><ul class="navigation-menu"></ul></nav>');

        this.web_document.find(".navigation-container .navigation-menu .navigation-item:not(.main-link-item):not(.navigation-right-side-item)").each(function ()
        {
            var mobile_item = $(this).clone().appendTo(".mobile-navigation-container .navigation-menu");
            mobile_item.removeClass("selected").removeAttr("data-category");
            if (mobile_item.hasClass("has-items"))
                mobile_item.children("a").attr("href", "#");
        });

        $('<li class="navigation-item mobile-menu cant-select">Menu</li>').prependTo(".navigation-container .navigation-menu").click(function ()
        {
            self.web_document.find(".mobile-navigation-container").finish().slideToggle();
            self.web_document.find(".mobile-navigation-container .navigation-item.has-items .navigation-submenu").finish().slideUp();
        });

        this.web_document.find(".mobile-navigation-container .navigation-item.has-items>a").click(function ()
        {
            self.web_document.find(".mobile-navigation-container .navigation-item.has-items").not($(this).parent()).find(".navigation-submenu").finish().slideUp();
            $(this).parent().find(".navigation-submenu").finish().slideToggle();
        });

        this.web_document.find(".mobile-navigation-container a").click(function ()
        {
            if ($(this).attr("href") !== "#")
            {
                self.web_document.find(".mobile-navigation-container .navigation-item.has-items .navigation-submenu").finish().slideUp();
                self.web_document.find(".mobile-navigation-container").finish().slideUp();
            }
        });
    };

    /*
    * Cookies
    * */
    this.check_cookies = function ()
    {
        if (Cookies.get("allow_cookies") === undefined)
        {
            this.web_document.find(".cookies-accept-container").show();
            this.web_document.find(".cookies-accept-container .close-container").click(function ()
            {
                Cookies.set("allow_cookies", true, { expires: 365 });
                $(this).parent().hide();
            });
        }
    }
}

$(function ()
{
    Web = new WebInterface();
    Web.init();
});
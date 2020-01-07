function WebPageRegistrationInterface(main_page)
{
    this.main_page = main_page;
    this.gender = "male";
    this.clouds_interval = null;
    this.clouds_frame = 0;

    /*
    * Generic function
    * */
    this.init = function ()
    {
        var self = this;
        var page_container = this.main_page.get_page_container();

        // Init type select
        page_container.find("select:not([name = 'gender']).selectric").selectric({
            theme: "web"
        });

        page_container.find("select[name = 'gender'].selectric").selectric({
            theme: "web",
            labelBuilder: "{text}",
            onChange: function()
            {
                self.gender = $(this).val();
                self.update_avatar(1);
            }
        });

        page_container.find(".username").keyup(function () {
            self.username_availability($(this).val());
        });

        page_container.find(".tabs-container span").click(function ()
        {
            if (!$(this).hasClass("selected"))
                self.update_avatar($(this).attr("data-avatar"));
        });
      
          
        if(Configuration.recaptcha_public)
            var registration_holder = grecaptcha.render("registration-recaptcha", {
                "sitekey": Configuration.recaptcha_public,
                "size": "invisible",
                "badge": "bottomright",
                "callback": function (recaptchaToken)
                {
                    page_container.find(".registration-form").removeClass("default-prevent").submit().addClass("default-prevent");
                    grecaptcha.reset(registration_holder);
                }
            });

            page_container.find(".registration-form").submit(function (event)
            {
                if (!$(this).hasClass("default-prevent"))
                    return;

                event.preventDefault();
                grecaptcha.execute(registration_holder);
            });
    };

    this.username_availability = function (username)
    {
        var page_container = this.main_page.get_page_container();

        if (username.length > 2) {
            Web.ajax_manager.post("/settings/namechange/availability", {username: username}, function (result) {
                if (result.status !== "available") {
                    page_container.find(".username").css('border', '1px solid red');
                } else {
                    page_container.find(".username").css('border', '1px solid green');
                }
            });
        } else {
            page_container.find(".username").css('border', '1px solid red');
        }
    };

    /*
    * Custom functions
    * */
    this.update_avatar = function (avatar)
    {
        var page_container = this.main_page.get_page_container();
        var avatars_preload = page_container.find(".avatars-preload");
        var avatar_preload = avatars_preload.find("." + this.gender + "-avatar" + avatar).attr("src");
        var avatar_figure = avatar_preload.replace(Site.figure_url + "/imaging/avatarimage?figure=", "").replace("&direction=4&size=l", "");

        page_container.find(".avatars-container input[name = 'figure']").val(avatar_figure);
        page_container.find(".avatars-container .avatar-container img").attr("src", avatar_preload);
        page_container.find(".tabs-container span.selected").removeClass("selected");
        page_container.find(".tabs-container span[data-avatar = '" + avatar + "']").addClass("selected");

        this.update_clouds();
    };

    this.update_clouds = function ()
    {
        var self = this;
        var page_container = this.main_page.get_page_container();
        clearTimeout(this.clouds_interval);
        this.clouds_frame = 0;
        this.clouds_interval = setInterval(function ()
        {
            self.clouds_frame++;
            page_container.find(".avatars-container .avatar-container").attr("data-random", self.clouds_frame);
            if (self.clouds_frame === 8)
            {
                clearTimeout(self.clouds_interval);
                self.clouds_frame = 0;
                page_container.find(".avatars-container .avatar-container").removeAttr("data-random");
            }
        }, 100);
    };

    this.check_captcha = function ()
    {
        var self = this;
        var page_container = this.main_page.get_page_container();

        if (page_container.find(".registration-recaptcha").length > 0)
            page_container.find(".registration-form").submit();
        else
        {
            setTimeout(function ()
            {
                self.check_captcha();
            }, 100);
        }
    };
}
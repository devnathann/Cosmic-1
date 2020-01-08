function WebPagePasswordClaimInterface(main_page)
{
    this.main_page = main_page;
    /*
    * Generic function
    * */
    this.init = function ()
    {
        var self = this;
        var page_container = this.main_page.get_page_container();

        page_container.find(".tabs-container span").click(function ()
        {
            if (!$(this).hasClass("selected"))
                self.update_avatar($(this).attr("data-avatar"));
        });

       if(Configuration.recaptcha_public)
          var password_claim = grecaptcha.render("password_claim-recaptcha", {
              "sitekey": Configuration.recaptcha_public,
              "size": "invisible",
              "badge": "bottomright",
              "callback": function (recaptchaToken)
              {
                  page_container.find(".password_claim-form").removeClass("default-prevent").submit().addClass("default-prevent");
                  grecaptcha.reset(password_claim);
              }
          });

          page_container.find(".password_claim-form").submit(function (event)
          {
              if (!$(this).hasClass("default-prevent"))
                  return;

              event.preventDefault();
              grecaptcha.execute(password_claim);
          });
    };

    this.check_captcha = function ()
    {
        var self = this;
        var page_container = this.main_page.get_page_container();

        if (page_container.find(".password_claim-recaptcha").length > 0)
            page_container.find(".password_claim-form").submit();
        else if(page_container.find(".password_claim_username-recaptcha").length > 0)
        {
            page_container.find(".password_claim-form").submit();
        } else {
            setTimeout(function ()
            {
                self.check_captcha();
            }, 100);
        }
    };
}
function WebPageCommunityValueInterface(main_page)
{
    this.main_page = main_page;

    this.init = function ()
    {
        var self = this;
        var page_container = this.main_page.get_page_container();

        if (!User.is_logged)
          return;
      
        var template = $(".page-content");
        template.find(".payment-button").click(function ()
        {
            var itemId = $(this).closest(".payment-button").attr("data-id");
            var image = $(this).closest(".payment-button").attr("data-image");
            var costs = $(this).closest(".payment-button").attr("data-costs");
            var type = $(this).closest(".payment-button").attr("data-type");
          
            self.open_solution_payment(itemId, image, costs, type);
        });

    };
  
    this.open_solution_payment = function (itemId, image, costs, type)
    {
        var self = this;

        var template = [
            '<div class="payment-popup zoom-anim-dialog">\n' +
            '    <div class="main-step">' +
            '        <h3 class="title">'+ image +'</h3>' +
            '        <h5 class="subtitle">' + costs + ' '+ type + '</h5>' +
            '        <img src="' + Site.game_url +'/meubels/' + image + '_icon.png" style="display: block;margin: auto;margin-top: 33px;width:15%">' +
            '        <div class="solution-details"></div>' +
            '        <div class="obtain-code"></div>' +
            '        <div class="row">' +
            '            <div class="column" style="margin-top:25px">' +
            '                <button class="rounded-button blue plain submit">Buy</button>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="success-step">' +
            '        <h3 class="title">'+ Locale.web_page_shop_offers_success +'</h3>' +
            '        '+ Locale.web_page_shop_offers_received +' <span></span> '+ Locale.web_page_shop_offers_received2 +'' +
            '        <img src="' + Site.url + '/assets/images/web/pages/shop/credits-success.png" alt="'+ Locale.web_page_shop_offers_success +'">' +
            '        <button class="rounded-button lightgreen plain">'+ Locale.web_page_shop_offers_close +'</button>' +
            '    </div>' +
            '    <div class="error-step">' +
            '        <h3 class="title">'+ Locale.web_page_shop_offers_failed +'</h3>' +
            '        '+ Locale.web_page_shop_offers_failed_desc +'' +
            '        <img src="' + Site.url + '/assets/images/web/pages/shop/credits-error.png" alt="'+ Locale.web_page_shop_offers_failed +'">' +
            '        <button class="rounded-button red plain">'+ Locale.web_page_shop_offers_back +'</button>' +
            '    </div>' +
            '</div>'
        ].join("");

        var dialog = $(template);
      
        $.magnificPopup.open({
            closeOnBgClick: false,
            items: {
                src: dialog,
                type: "inline"
            }
        });
    };
  
    this.submitted = false;
    this.submit_code = function (solution, code)
    {
        if (this.submitted)
            return null;
      
        this.disable_button();
    }
    
    this.disable_button = function ()
    {
        var dialog = $("body").find(".payment-popup");
        var submit_button = dialog.find(".main-step .submit");

        this.submitted = true;
        submit_button.text("Laden...").prop("disabled", true);
    };
}
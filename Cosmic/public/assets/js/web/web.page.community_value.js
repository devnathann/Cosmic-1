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
            var state = $(this).closest(".payment-button").attr("data-state");
          
            self.open_solution_payment(itemId, image, costs, type, state);
        });
      
        template.find(".sell-item").click(function ()
        {
            var itemId = $(this).closest(".sell-item").attr("data-id");
            var image = $(this).closest(".sell-item").attr("data-image");
            self.open_sell_item(itemId, image);
        });
      
        template.find("[name=search-furni]").on("input", function()
        {
            self.search_furni(this.value);
        });
    };
  
    this.search_furni = function (name)
    {
        var self = this;
        var template = $(".default-table");
      
        Web.ajax_manager.post("/shop/marketplace/search", {furni_name: name}, function (result)
        {
            if (result.length > 0) {
                template.find(".marketplace").empty();
              
                for (var i = 0; i < result.length; i++) {
                    var item_data = result[i];
                  
                    var item = [
                        '<tr>' +
                           '<td><img src="' + Site.game_url + '/meubels/' + item_data.catalog_name + '_icon.png" class="pixelated"></td>' +
                            '<td><a href="#" style="border-bottom: 1px dotted;" class="payment-button buy-button" data-id="' + item_data.id + '" data-image="' + item_data.catalog_name + '" data-costs="' + item_data.item_costs + '" data-type="' + item_data.currency + '">' + item_data.catalog_name + '</a></td>' +
                            '<td style="font-size: 14px">' + item_data.user.username + '</td>' +
                            '<td>' + item_data.currency + '</td>' +
                            '<td style="font-size: 14px; font-weight: bold">' + item_data.item_costs + '</td>' +
                        '</tr>'
                    ];
                    template.find(".marketplace").append(item);
                }
              
                var page = $(".page-content");
                page.find(".buy-button").click(function ()
                {
                    var itemId = $(this).closest(".buy-button").attr("data-id");
                    var image = $(this).closest(".buy-button").attr("data-image");
                    var costs = $(this).closest(".buy-button").attr("data-costs");
                    var type = $(this).closest(".buy-button").attr("data-type");
                  
                    self.open_solution_payment(itemId, image, costs, type, 'marketplace');
                });
            }
        });
    }
  
    this.open_sell_item = function (itemId, image)
    {
        var self = this;     
      
        var template = [
            '<div class="payment-popup sell-item-popup zoom-anim-dialog">\n' +
            '    <div class="main-step">' +
            '        <h3 class="title">Sell '+ image +'</h3>' +
            '        <img src="' + Site.game_url +'/meubels/' + image + '_icon.png" style="display: block;margin: auto;margin-top: 33px;width:15%">' +
            '        <div class="solution-details"></div>' +
            '        <div class="obtain-code"></div>' +
            '        <div class="row">' +
            '            <select name="currencys" class="selectric" style="margin-top:25px"></select>' +
            '            <input type="text" style="margin-top: 15px" class="rounded-input blue-active costs" placeholder="Costs...">' +
            '            <button class="rounded-button blue plain submit" style="margin-top:15px">'+ Locale.web_page_shop_offers_submit +'</button>' +
            '        </div>' +
            '    </div>' +
            '</div>'
        ].join("");
      
        var payment = $(template);
      
        Web.ajax_manager.get("/api/currencys", function (result)
        {
            for (var type in result){
                var currency = result[type];
                payment.find(".selectric").append(new Option(currency.currency, currency.type));
            }
          
            // Init type select
            payment.find(".selectric").selectric({
                theme: "web"
            });

            var dialog = $(payment);

            $.magnificPopup.open({
                closeOnBgClick: false,
                items: {
                    src: dialog,
                    type: "inline"
                }
            });

            dialog.find(".submit").click(function ()
            {
                var currency = dialog.find("[name=currencys]").val();
                var costs = dialog.find(".costs").val();
              
                Web.ajax_manager.post("/shop/marketplace/sell", {item_id: itemId, currency: currency, costs: costs}, function (data)
                {
                    if(data.status == 'success') {
                        $.magnificPopup.close();
                    }
                });
            });
        });
    };
  
    this.open_solution_payment = function (itemId, image, costs, type, state)
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
            '        <img src="/assets/images/web/pages/shop/credits-success.png" alt="'+ Locale.web_page_shop_offers_success +'">' +
            '        <button class="rounded-button lightgreen plain">'+ Locale.web_page_shop_offers_close +'</button>' +
            '    </div>' +
            '    <div class="error-step">' +
            '        <h3 class="title">'+ Locale.web_page_shop_offers_failed +'</h3>' +
            '        '+ Locale.web_page_shop_offers_failed_desc +'' +
            '        <img src="/assets/images/web/pages/shop/credits-error.png" alt="'+ Locale.web_page_shop_offers_failed +'">' +
            '        <button class="rounded-button red plain">'+ Locale.web_page_shop_offers_back +'</button>' +
            '    </div>' +
            '</div>'
        ].join("");

        var dialog = $(template);
        var page_url = this.main_page.manager.current_page_url;
      
        $.magnificPopup.open({
            closeOnBgClick: false,
            items: {
                src: dialog,
                type: "inline"
            }
        });
      
        dialog.find(".submit").click(function ()
        {
            self.submit_code(itemId, state, page_url);
        });
    };
  
    this.submit_code = function (itemId, state, page_url = false)
    {
        Web.ajax_manager.post("/shop/marketplace/catalogue", {item_id: itemId, state: state, page_url: page_url});
        $.magnificPopup.close();
    }
}
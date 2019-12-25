function WebPageShopOffersInterface(main_page)
{
    this.main_page = main_page;
    this.offer_id = null;
    this.amount = 0;
    this.country = "nl";
    this.payments = {
        "Neosurf": {
            name: Locale.web_page_shop_offers_neosurf_name,
            description: Locale.web_page_shop_offers_neosurf_description,
            class: "neosurf",
            dialog: Locale.web_page_shop_offers_neosurf_dialog
        },
        "Paypal": {
            name: Locale.web_page_shop_offers_paypal_name,
            description: Locale.web_page_shop_offers_paypal_description,
            class: "paypal",
            dialog: Locale.web_page_shop_offers_paypal_dialog
        },
        "SMS": {
            name: Locale.web_page_shop_offers_sms_name,
            description: Locale.web_page_shop_offers_sms_description,
            class: "sms-plus",
            dialog: Locale.web_page_shop_offers_sms_dialog
        },
        "Audiotel": {
            name: Locale.web_page_shop_offers_audiotel_name,
            description: Locale.web_page_shop_offers_audiotel_description,
            class: "audiotel",
            dialog: Locale.web_page_shop_offers_audiotel_dialog
        }
    };
    this.payment_template = [
        '<article class="default-section offer-payment flex-container flex-vertical-center">\n' +
        '    <div class="payment-image"></div>\n' +
        '    <div class="payment-description"></div>\n' +
        '    <div class="payment-button">\n' +
        '        <button type="button" class="rounded-button blue">Kies</button>\n' +
        '    </div>\n' +
        '</article>'
    ].join("");

    /*
    * Generic function
    * */
    this.init = function ()
    {
        var self = this;
        var page_container = this.main_page.get_page_container();

        if (!User.is_logged)
            return;

        // Init offers
        this.offer_id = page_container.find("#offer-id").val();
        this.amount = page_container.find("#offer-amount").val();
        this.country = page_container.find("#offer-country").val();
        $.ajax({
            type: "get",
            url: "https://api.dedipass.com/v1/pay/rates?key=" + this.offer_id,
            dataType: "json"
        }).done(function (solutions)
        {
            if (page_container.find(".loading-solutions").length > 0)
                page_container.find(".loading-solutions").remove();

            var solutionsSorted = solutions.sort(function (a, b)
            {
                var x = a.ordersolution;
                var y = b.ordersolution;
                return x < y ? -1 : x > y ? 1 : 0;
            });

            for (var i = 0; i < solutionsSorted.length; i++)
            {
                var solution = solutionsSorted[i];

                if (!self.payments.hasOwnProperty(solution.solution))
                    continue;

                if (solution.country.iso !== "all" && solution.country.iso !== self.country)
                    continue;

                var template = $(self.payment_template);
                template.attr("data-id", i);
                template.addClass(self.payments[solution.solution].class);
                template.find(".payment-description").html("<h4>" + self.payments[solution.solution].name + "</h4>" + self.payments[solution.solution].description);

                page_container.find(".shop-offer").append(template);

                template.find(".payment-button button").click(function ()
                {
                    var solution = solutionsSorted[$(this).closest(".offer-payment").attr("data-id")];
                    self.open_solution_payment(solution);
                });
            }
        });
    };

    /*
    * Custom functions
    * */
    this.open_solution_payment = function (solution)
    {
        var self = this;
        var payment_solution = this.payments[solution.solution];
        var template = [
            '<div class="payment-popup zoom-anim-dialog">\n' +
            '    <div class="main-step">' +
            '        <h3 class="title">'+ Locale.web_page_shop_offers_pay_with +' ' + payment_solution.name + '</h3>' +
            '        <h5 class="subtitle">' + this.amount + ' '+ Locale.web_page_shop_offers_points_for +' €' + number_format(solution.user_price, 2, ",", " ") + '</h5>' +
            '        <h5>1. '+ Locale.web_page_shop_offers_get_code +'</h5>' +
            '        ' + payment_solution.dialog +
            '        <div class="solution-details"></div>' +
            '        <div class="obtain-code"></div>' +
            '        <h5>2. '+ Locale.web_page_shop_offers_fill_code +'</h5>' +
            '        '+ Locale.web_page_shop_offers_fill_code_desc +'' +
            '        <div class="row">' +
            '            <div class="column-2">' +
            '                <input type="text" class="rounded-input blue-active code" placeholder="Code...">' +
            '            </div>' +
            '            <div class="column-2">' +
            '                <button class="rounded-button blue plain submit">'+ Locale.web_page_shop_offers_submit +'</button>' +
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
        var details_template = null;
        var obtain_template = null;

        if (payment_solution.class === "neosurf")
            details_template = Locale.web_page_shop_offers_no_card + " <a href=\"http://www.neosurf.com/fr_FR/application/findcard\" target=\"_blank\">"+ Locale.web_page_shop_offers_no_card2 +"</a>.";

        if (details_template !== null)
            dialog.find(".solution-details").html(details_template);
        else
            dialog.find(".solution-details").remove();

        if (payment_solution.class === "sms-plus")
        {
            obtain_template = [
                '<div class="sms-container ' + (this.country === "fr" ? "fr" : "") + '">' +
                '    <span class="keyword">' + solution.keyword + '</span> '+ Locale.web_page_shop_offers_to +' <span class="shortcode">' + solution.shortcode + '</span>' +
                '    <div class="mention">' + solution.mention + '</div>' +
                '</div>'
            ].join("");
        }
        else if (payment_solution.class === "audiotel")
        {
            obtain_template = [
                '<div class="audiotel' + (this.country !== "be" ? "fr" : "be") + '-container">' +
                '    ' + solution.phone +
                '    <div class="mention">' + solution.mention + '</div>' +
                '</div>'
            ].join("");
        }
        else if (!isEmpty(solution.link))
        {
            obtain_template = [
                '<button class="rounded-button blue">'+ Locale.web_page_shop_offers_buy_code +'</button>'
            ].join("");
        }

        if (obtain_template !== null)
            dialog.find(".obtain-code").html(obtain_template);

        if (!isEmpty(solution.link))
        {
            dialog.find(".obtain-code button").click(function ()
            {
                self.open_modal(solution.link);
            });
        }

        dialog.find(".code").keypress(function (e)
        {
            if (e.keyCode !== 13)
                return null;

            if (!isEmpty($(this).val()))
                self.submit_code(solution, $(this).val());
        });

        dialog.find(".submit").click(function ()
        {
            var code = dialog.find(".code").val();

            if (!isEmpty(code))
                self.submit_code(solution, code);
        });

        dialog.find(".error-step button").click(function ()
        {
            self.show_main_step();
        });

        dialog.find(".success-step button").click(function ()
        {
            $.magnificPopup.close();
        });

        $.magnificPopup.open({
            closeOnBgClick: false,
            items: {
                src: dialog,
                type: "inline"
            }
        });
    };

    this.open_modal = function (link)
    {
        window.open(link, "Laden...", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=600,height=550,left=420,top=150");
    };

    this.submitted = false;
    this.submit_code = function (solution, code)
    {
        if (this.submitted)
            return null;

        this.disable_button();

        var self = this;
        $.ajax({
            type: "get",
            url: "https://api.dedipass.com/v1/pay/?key=" + this.offer_id + "&rate=AUTORATE&code=" + code + "&tokenize",
            dataType: "json"
        }).done(function (result)
        {
            if (result.status === "success")
            {
                Web.ajax_manager.post("/shop/offers/validate", {offer_id: self.offer_id, code: code, price: solution.user_price}, function (data)
                {
                    if (data.status === "success")
                        self.show_success_step(data.amount);
                    else
                        self.show_error_step();
                });
            }
            else
                self.show_error_step();
        });
    };

    this.disable_button = function ()
    {
        var dialog = $("body").find(".payment-popup");
        var submit_button = dialog.find(".main-step .submit");

        this.submitted = true;
        submit_button.text("Laden...").prop("disabled", true);
    };

    this.enable_button = function ()
    {
        var dialog = $("body").find(".payment-popup");
        var submit_button = dialog.find(".main-step .submit");

        this.submitted = false;
        submit_button.text("Valideren..").prop("disabled", false);
    };

    this.show_main_step = function ()
    {
        this.enable_button();
        var dialog = $("body").find(".payment-popup");

        dialog.find(".main-step").show();
        dialog.find(".success-step").hide();
        dialog.find(".error-step").hide();
    };

    this.show_success_step = function (amount)
    {
        this.enable_button();
        var dialog = $("body").find(".payment-popup");

        dialog.find(".main-step").hide();
        dialog.find(".success-step span").text(amount);
        dialog.find(".success-step").show();
        dialog.find(".error-step").hide();
    };

    this.show_error_step = function ()
    {
        this.enable_button();
        var dialog = $("body").find(".payment-popup");

        dialog.find(".main-step").hide();
        dialog.find(".success-step").hide();
        dialog.find(".error-step").show();
    };
}
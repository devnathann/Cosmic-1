function WebPageShopOffersInterface(main_page)
{
    this.main_page = main_page;
    this.offer_id = null;
    this.amount = 0;
    this.country = "nl";
    this.payments = {
        "Neosurf": {
            name: "Neosurf",
            description: "Betaal gemakkelijk met Paypal en je bel-credits worden direct opgewaardeerd.",
            class: "neosurf",
            dialog: "Vul je onderstaande paypal mail adres in om door te gaan."
        },
        "Paypal": {
            name: "Paypal",
            description: "Betaal gemakkelijk met Paypal en je bel-credits worden direct opgewaardeerd.",
            class: "paypal",
            dialog: "Vul je onderstaande paypal mail adres in om door te gaan."
        },
        "SMS": {
            name: "SMS",
            description: "Stuur een code per sms en ontvang een Bel-Credits code.",
            class: "sms-plus",
            dialog: "Stuur de onderstaande code in een SMS om een Bel-Credit code te krijgen."
        },
        "Audiotel": {
            name: "Telefoon",
            description: "Bel een of meerdere keren een nummer om een Bel-Credit code te krijgen",
            class: "audiotel",
            dialog: "Bel naar het onderstaande nummer om een Bel-Credit code te krijgen:"
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
            '        <h3 class="title">Betaal via ' + payment_solution.name + '</h3>' +
            '        <h5 class="subtitle">' + this.amount + ' Bel-Credits voor €' + number_format(solution.user_price, 2, ",", " ") + '</h5>' +
            '        <h5>1. Krijg een Bel-Credit code</h5>' +
            '        ' + payment_solution.dialog +
            '        <div class="solution-details"></div>' +
            '        <div class="obtain-code"></div>' +
            '        <h5>2. Vul je Bel-Credit code in</h5>' +
            '        Vul hieronder je Bel-Credit code in om je Bel-Credits te ontvangen.' +
            '        <div class="row">' +
            '            <div class="column-2">' +
            '                <input type="text" class="rounded-input blue-active code" placeholder="Code...">' +
            '            </div>' +
            '            <div class="column-2">' +
            '                <button class="rounded-button blue plain submit">Bevestigen</button>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="success-step">' +
            '        <h3 class="title">Aankoop gelukt!</h3>' +
            '        Bedankt voor je aankoop. Je hebt <span></span> Bel-Credits ontvangen.' +
            '        <img src="' + Site.url + '/assets/images/web/pages/shop/credits-success.png" alt="Aankoop gelukt!">' +
            '        <button class="rounded-button lightgreen plain">Sluit</button>' +
            '    </div>' +
            '    <div class="error-step">' +
            '        <h3 class="title">Aankoop mislukt...</h3>' +
            '        De aankoop is mislukt. Probeer het nog eens of neem contact op via de Help Tool.' +
            '        <img src="' + Site.url + '/assets/images/web/pages/shop/credits-error.png" alt="Aankoop mislukt!">' +
            '        <button class="rounded-button red plain">Terug</button>' +
            '    </div>' +
            '</div>'
        ].join("");

        var dialog = $(template);
        var details_template = null;
        var obtain_template = null;

        if (payment_solution.class === "neosurf")
            details_template = "Als je geen Neosurf-prepaidkaart hebt, kun je de <a href=\"http://www.neosurf.com/fr_FR/application/findcard\" target=\"_blank\">verkoop punten zien</a>.";

        if (details_template !== null)
            dialog.find(".solution-details").html(details_template);
        else
            dialog.find(".solution-details").remove();

        if (payment_solution.class === "sms-plus")
        {
            obtain_template = [
                '<div class="sms-container ' + (this.country === "fr" ? "fr" : "") + '">' +
                '    <span class="keyword">' + solution.keyword + '</span> naar <span class="shortcode">' + solution.shortcode + '</span>' +
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
                '<button class="rounded-button blue">Koop toegangscode</button>'
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
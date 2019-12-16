function WebHotelManagerInterface()
{
    this.hotel_container = null;

    /*
    * Manager initialization
    * */
    this.init = function ()
    {
        this.hotel_container = $("#hotel-container");

        this.hotel_container.find(".client-buttons .client-close").click(this.close_hotel);
        this.hotel_container.find(".client-buttons .client-fullscreen").click(this.toggle_fullscreen.bind(this));
        this.hotel_container.find(".client-buttons .client-count").click(this.refresh_count);
        this.hotel_container.find(".client-buttons .client-radio").click(this.radio(this));

        setInterval(function() {
            $("body").find(".client-buttons .client-count #count").load("/api/player/count");
        }, 120000);
    };

    /*
    * Hotel toggle
    * */
    this.close_hotel = function ()
    {
        Web.pages_manager.load(Web.pages_manager.last_page_url, null, true, null, true);
    };

    this.refresh_count = function ()
    {
        $("body").find(".client-buttons .client-count #count").load("/api/player/count");
    };

    this.open_hotel = function (arguments)
    {
        var actions = {};

        if (arguments !== undefined) {
            parse_str(arguments, actions);
        }

        var body = $("body");

        body.find(".header-container .header-content .account-container .account-buttons .hotel-button").text("Terug naar Leet Hotel");

        if (!body.hasClass("hotel-visible"))
        {
            if (this.hotel_container.find(".client-frame").length === 0)
                this.hotel_container.prepend('<iframe class="client-frame" src="' + Site.url + '/client?' + arguments + '"></iframe>');

            body.addClass("hotel-visible");

            var radio = document.getElementById("stream");
            radio.src = "https://listen.leetfm.be/radio/8000/radio.mp3";
            radio.volume = 0.1;
            radio.play();

            $(".fa-play").hide();
            $(".fa-pause").show();

            this.hotel_actions(actions);
        }
    };

    /*
    * Hotel Actions
    * */
    this.hotel_actions = function () {
        this.hotel_actions = function (actions) {
            if (actions.hasOwnProperty("room")) {
                Web.ajax_manager.post("/ajax/room/go", {roomId: actions["room"]});
            }
        };
    };

    /*
    * LeetFM Player
    * */
    this.radio = function () {

        var radio = document.getElementById("stream");

        this.hotel_container.find(".client-buttons .client-radio .fa-play").click( function() {
            radio.src = "https://listen.leetfm.be/radio/8000/radio.mp3";
            radio.volume = 0.1;
            radio.play();

            $(".fa-play").hide();
            $(".fa-pause").show();
        });

        this.hotel_container.find(".client-buttons .client-radio .fa-pause").click( function() {

            radio.pause();
            radio.src = "";
            radio.load();

            $(".fa-play").show();
            $(".fa-pause").hide();
        });

        this.hotel_container.find(".client-buttons .client-radio .fa-volume-up").click( function() {
            var volume = radio.volume;

            if(volume > 1.0) {
                radio.volume += 0.0;
            } else {
                radio.volume += 0.1;
            }
        });

        this.hotel_container.find(".client-buttons .client-radio .fa-volume-down").click( function() {
            var volume = radio.volume;

            if(volume < 0.0) {
                radio.volume -= 0.0;
            } else {
                radio.volume -= 0.1;
            }
        });
    };

    /*
    * Fullscreen toggle
    * */
    this.toggle_fullscreen = function ()
    {
        if ((document.fullScreenElement && document.fullScreenElement) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
            if (document.documentElement.requestFullScreen) {
                document.documentElement.requestFullScreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullScreen) {
                document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
            }

            this.hotel_container.find(".client-buttons .client-fullscreen .client-fullscreen-icon").addClass("hidden");
            this.hotel_container.find(".client-buttons .client-fullscreen .client-fullscreen-icon-back").removeClass("hidden");
        } else {
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }

            this.hotel_container.find(".client-buttons .client-fullscreen .client-fullscreen-icon").removeClass("hidden");
            this.hotel_container.find(".client-buttons .client-fullscreen .client-fullscreen-icon-back").addClass("hidden");
        }
    };
}
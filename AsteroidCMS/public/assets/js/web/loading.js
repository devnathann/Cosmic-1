var SiteLoading;
var PageLoading;

$(function ()
{
    SiteLoading = new SiteLoadingInterface();
    SiteLoading.init();
    SiteLoading.load_file(0);
    PageLoading = new PageLoadingInterface();
});

function SiteLoadingInterface()
{
    this.files = [
        "web.page",
        "web.ajax",
        "web.notifications",
        "web.dialog",
        "web.hotel",
        "web.customforms",
        "web.page.article",
        "web.page.settings_preferences",
        "web.page.settings_namechange",
        "web.page.settings_verification",
        "web.page.help_requests",
        "web.page.profile",
        "web.page.community_photos",
        "web.page.home",
        "web.page.registration",
        "web.page.jobs",
        "web.page.shop",
        "web.page.shop_offers",
        "web.page.forum",
        "web.page.password_claim",
        "web",
    ];
    this.loaded_files = 0;
    this.total_files = 0;
    this.loading_container = null;
    this.cache_id = null;

    this.init = function ()
    {
        console.log(
            " |       |-----   |-----    -----\n" +
            " |       |-----   |-----      |\n" +
            " |____   |_____   |_____      |\n" +
            "Leet Hotel NL/BE - Asteroid\n" +
            "\n" +
            " Alles wat je hier doet valt onder je eigen verantwoordelijkheid. Geef\n" +
            "  nooit je code als iemand er om vraagt. Als je hier een code plakt krijg\n" +
            "  je nooit gratis (Bel)credits of andere items.\n" +
            "                                                                              \n" +
            "                                                                   - Leet Dev");
        this.total_files = this.files.length;
        this.loading_container = $(".loading-container");
        //this.cache_id = Configuration.cache;
        this.cache_id = (new Date().getTime() + Math.floor((Math.random() * 10000) + 1)).toString(16);
    };

    this.load_file = function (file_id)
    {
        var self = this;
        var file_name = this.files[file_id];
        var script = document.createElement("script");
        $("body").append(script);
        script.onload = function ()
        {
            self.loaded_files++;

            var percent = Math.floor(self.loaded_files / self.total_files * 100);

            self.loading_container.find(".c100").attr("class", "c100 p" + percent + " center");
            self.write_bodytext("Laden... " + percent + "%");

            if (file_id + 1 < self.total_files)
            {
                file_id++;
                self.load_file(file_id);
            }
            else
            {
                setTimeout(function ()
                {
                    self.close_loading();
                }, 100);
            }
        };
        script.onerror = function ()
        {
            console.log("Oops, file \"" + file_name + "\" not found.");
            self.write_bodytext("Oeps, er is iets misgegaan. <a href=\"javascript:window.location.reload();\">Herlaad de pagina</a>.");
        };
        script.src = Site.url + "/assets/js/web/" + file_name + ".js?" + this.cache_id;
    };

    this.write_bodytext = function (text)
    {
        this.loading_container.find(".loading-bodytext").html(text);
    };

    this.close_loading = function ()
    {
        this.loading_container.fadeOut(1000, function ()
        {
            $(this).remove();
        });
    };
}

function PageLoadingInterface()
{
    this.show = function ()
    {
        $(".page-loading").stop().fadeIn(500);
    };

    this.hide = function ()
    {
        $(".page-loading").stop().fadeOut(500);
    };
}
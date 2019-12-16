function WebPageShopChestsInterface(main_page)
{
    this.main_page = main_page;

    /*
    * Generic function
    * */
    this.init = function ()
    {
        var self = this;
        var page_container = this.main_page.get_page_container();

        if (!User.is_logged)
            return;

        // Open chest
        page_container.find(".chest-container button").click(function ()
        {
            var chest_container = $(this).closest(".chest-container");

            Web.ajax_manager.post("/ajax/request?action=shop_chests&open", {type: chest_container.attr("data-type")}, function (result)
            {
                if (result.status === "success")
                {
                    chest_container.find(".keys").text(result.amount);

                    if (result.amount <= 0)
                        chest_container.find("button").prop("disabled", true);
                    else
                        chest_container.find("button").prop("disabled", false);
                }
            });
        })
    };
}
function WebPageHelpRequestsInterface(main_page)
{
    this.main_page = main_page;

    /*
    * Generic function
    * */
    this.init = function ()
    {
        var page_container = this.main_page.get_page_container();

        // Init type select
        page_container.find(".selectric").selectric({
            theme: "web"
        });
    };
}
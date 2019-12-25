function WebPageArticleInterface(main_page)
{
    this.main_page = main_page;

    function urlFunc(str, p1, offset, s) {
        return '<a href="'+ p1+ '">' + offset + '</a>';
    }

    function urlReplace(str)
    {
        var bbcode =  [
            /\[url=(.*?)\](.*?)\[\/url\]/ig,
        ]; 

        var format_replace = [
            urlFunc
        ];

        for (var i =0;i<bbcode.length;i++) {
          str = str.replace(bbcode[i], format_replace[i]);
        }

        return str;
    }
  
    /*
    * Generic function
    * */
    this.init = function ()
    {
        var self = this;
        var page_container = this.main_page.get_page_container();

        this.reaction_tmp = [
            '<div class="ac-item" style="border-radius: 10px;">\n' +
            '   <div style="float: left; vertical-align: middle; ">\n' +
            '         <img style="margin-top: -30px; margin-bottom: -60px;" src="' + Site.figure_url + '/avatarimage?figure={{figure}}}&direction=2&head_direction=3&gesture=sml&size=b&headonly=1" alt="">\n' +
            '    </div>\n' +
            '   <strong> <a href="' + Site.url + '/profile/' + User.username + '">' + User.username + '</a></strong>: {{message}} \n' +
            '</div>'
        ].join("");   
      
        page_container.find(".fa-times, .fa-eye").click(function ()
        {
            if(User.is_logged == true && User.is_staff == true)
            {
                var id = $(this).attr("data-id");
                Web.ajax_manager.post("/community/articles/hide", {post: id}, function (result)
                {
                    if(result.status === "success") {
                        if(result.is_hidden === "hide"){
                            $(".fa-times[data-id=" + id + "]").attr('class', 'fa fa-eye');
                            $(".ac-item[data-id=" + id + "]").css("filter","grayscale(100%)");
                        } else {
                            $(".fa-eye[data-id=" + id + "]").attr('class', 'fa fa-times');
                            $(".ac-item[data-id=" + id + "]").css("filter","");
                        }
                    }
                });
            }
        });
      
        page_container.find(".article-reply").click(function ()
        {
            if(User.is_logged == true)
            {
                var id      = $(this).attr("data-id");
                var reply   = $('#reply-message').val();

                Web.ajax_manager.post("/community/articles/add", {articleid: id, message: reply}, function (result)
                {
                    if (result.status === "success"){
                        var reaction = urlReplace(result.bericht);
                        var reactions_template = $(self.reaction_tmp.replace(/{{figure}}/g, result.figure).replace(/{{message}}/g, reaction));

                        page_container.find(".nano-pane").append(reactions_template);
                        page_container.find(".reaction-reply").remove();
                        page_container.find(".nopost").remove();
                    }
                });
            }else{
                Web.notifications_manager.create("info", Locale.web_page_article_login);
            }
        });
    };
}
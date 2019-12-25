function WebPageProfileInterface(main_page)
{
    var loadmore = true;
    this.main_page = main_page;

    this.article_template = [
        '<div class="feed-item" data-id="{feed.id}" id="{feed.id}">\n' +
        '   <div class="fi-avatar logo" style="margin-right: 0; height: 60px; margin-top: -7px;">\n' +
        '       <div style="background: url(' + Site.figure_url + '/imaging/avatarimage?figure={{figure}}&direction=3&headonly=1) no-repeat center; width: 48px; height: 62px;"></div>\n' +
        '   </div>\n' +
        '   <div class="fi-content">\n' +
        '   <div class="fc-user">\n' +
        '   <strong><div class="user-style"><span class="user-style black"><span class="user-icon cog"></span>{{feed.from_username}}</span></div></strong>\n' +
        '     - <span data-toggle="tooltip" data-placement="right" title="" data-original-title="{{feed.timestamp|time_diff}}"><small>{{feed.timestamp}}</small></span>\n' +
        '   </div>\n' +
        '   <div class="fc-content">{feed.message}</div>\n' +
        '   <div class="fc-tools" data-id="{feed.id}">\n' +
        '       <a href="#" class="likes-count fc-like" data-id="{feed.id}">{{feed.likes}}</a> <a href="#" class="fc-like" data-id="{feed.id}" style="margin-right:10px;"><i class="fa fa-heart"  data-id="{feed.id}" style="color: #D67979;"></i></a>\n' +
        '      <a href="#"><i class="fa fa-flag" data-id="{feed.id}" data-report="feed" style="color: #7B7777;"></i></a>&nbsp;\n' +
        '   </div>' +
        '</div>'
    ].join("");
    this.current_page = 1;

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

        // Init photos gallery
        page_container.find(".default-section[data-section = 'photos'] .items-container").magnificPopup({
            delegate: "a",
            type: "image",
            closeOnContentClick: false,
            closeBtnInside: false,
            mainClass: "mfp-with-zoom mfp-img-mobile report",
            image: {
                verticalFit: true,
                titleSrc: function (item)
                {
                    if(User.id == item.el.attr("data-player")) {
                        return '<i class="fa fa-times-circle" data-id="' + item.el.attr("data-id") + '" style="color: #fff;"></i> <i class="fa fa-flag" data-report="photo" data-value="photos" data-id="' + item.el.attr("data-id") + '" style="color: #fff;"></i> ' + item.el.attr("data-title");
                    } else {
                        if(User.is_logged == true) {
                            return '<i class="fa fa-flag" data-report="photo" data-value="photos" data-id="' + item.el.attr("data-id") + '" style="color: #fff;"></i> ' + item.el.attr("data-title");
                        } else {
                            return item.el.attr("data-title");
                        }
                    }
                }
            },
            gallery: {
                enabled: true
            },
            zoom: {
                enabled: true,
                duration: 300,
                opener: function (element)
                {
                    return element.find("div");
                }
            }
        });

        page_container.find(".fa-heart").click(function ()
        {
            if(loadmore == true)
            {
                addLike($(this).attr("data-id"));
            }
        });

        page_container.find(".fa-remove").click(function () {
            var feedid = $(this).attr("data-id");

            Web.ajax_manager.post("/community/feeds/delete", {feedid: feedid});
        });

        /*
        * Loadmore function
        * */
        page_container.find(".load-more-button button").click(function ()
        {
            var userId = $(this).attr("data-id");
            var countdivs = $('.feed-item').length;
            Web.ajax_manager.post("/community/feeds/more", {current_page: self.current_page, player_id: userId, count: countdivs}, function (result)
            {
                if (result.feeds.length > 0)
                {
                    for (var i = 0; i < result.feeds.length; i++)
                    {
                        var feed_data = result.feeds[i];
                        var postmessage = urlReplace(feed_data.message);
                        var article_template = $(self.article_template.replace(/{{feed.from_username}}/g, feed_data.from_username).replace(/{{feed.timestamp}}/g, feed_data.timestamp).replace(/{feed.id}/g, feed_data.id).replace(/{{feed.to_username}}/g, feed_data.to_username).replace(/{feed.message}/g, postmessage).replace(/{{feed.likes}}/g, feed_data.likes).replace(/{{feed.countreactions}}/g, feed_data.countreactions).replace(/{{figure}}/g, feed_data.figure).replace(/{{feed.profile}}/g, feed_data.profile));
						
                        page_container.find(".feeds").append(article_template);

                        page_container.find(".fc-like[data-id=" + feed_data.id + "]").click(function ()
                        {
                            addLike($(this).attr("data-id"));
                        });

                    }

                    self.current_page = result.current_page;
                }

            });
        });
    };

    function addLike(id)
    {
        if(User.is_logged == true)
        {
            Web.ajax_manager.post("/community/feeds/like", {post: id}, function (result)
            {
                if(result.status == 'success')
                {
                    $('.fa-heart[data-id='+ id +']').addClass("pulsateOnce");
                    $('.likes-count[data-id='+ id +']').text(parseInt($('.likes-count[data-id='+ id +']').text())+1);
                }
            });
        }
        else
        {
            Web.notifications_manager.create("error", Locale.web_page_profile_login, Locale.web_page_profile_loggedout);
        }
    }

    function addPost(message, id)
    {
        Web.ajax_manager.post("/community/feeds/post", {reply: message, userid: id});
    }

    $($('.rounded-input')).on('keypress', function(e) {
        var code = e.keyCode || e.which;
        if(code==13){
            addPost($('.rounded-input').val(), $("input[name=userid]").val());
        }
    });
}
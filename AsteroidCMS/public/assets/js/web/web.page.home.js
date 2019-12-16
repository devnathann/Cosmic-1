function WebPageHomeInterface(main_page)
{
    this.main_page = main_page;
    this.article_template = [
        '<div class="article-container" style="display: none;">\n' +
        '    <a href="' + Site.url + '/article/{article.id}-{article.slug}" class="article-content" style="background-image: url({article.banner});">\n' +
        '        <div class="article-header">\n' +
        '            <div class="article-category">{article.category}</div>\n' +
        '            <div class="article-separation" style="background-color: {article.color};"></div>\n' +
        '            <div class="article-title title" data-id="{article.id}">{article.title}</div>\n' +
        '            <div class="article-title title-sub" data-id="{article.id}" style="display: none;">{article.title}</div>\n' +
        '        </div>\n' +
        '    </a>\n' +
        '</div>'
    ].join("");
    this.current_page = 1;

    /*
    * Generic function
    * */
    this.init = function ()
    {
        var self = this;
        var page_container = this.main_page.get_page_container();
        
      
        function mouseoverTitle()
        {
            $('.article-container').mouseenter(function () {
                  var id = $(this).attr("data-id");
                  $(".title[data-id=" + id + "]").hide();
                  $(".title-sub[data-id=" + id + "]").show();
             });

             $('.article-container').mouseleave(function () {
                  var id = $(this).attr("data-id");
                  $(".title[data-id=" + id + "]").show();
                  $(".title-sub[data-id=" + id + "]").hide();
                }
             ).mouseleave();
        }

        // Load more articles
        page_container.find(".load-more-button button").click(function ()
        {
            var countdivs = $('.article-container').length;
            Web.ajax_manager.post("/community/articles/more", {current_page: self.current_page, offset: countdivs}, function (result)
            {
                if (result.articles.length > 0)
                {
                    for (var i = 0; i < result.articles.length; i++)
                    {
                        var article_data = result.articles[i];
                        var article_template = $(self.article_template.replace(/{article.slug}/g, article_data.slug).replace(/{article.banner}/g, article_data.header).replace(/{article.id}/g, article_data.id).replace(/{article.category}/g, article_data.category).replace(/{article.color}/g, article_data.color).replace(/{article.title}/g, article_data.title));
                        page_container.find(".articles-container").append(article_template);
                        article_template.fadeIn();
                    }

                    self.current_page = result.current_page;
                }
            });
        });

        mouseoverTitle();
    };
}
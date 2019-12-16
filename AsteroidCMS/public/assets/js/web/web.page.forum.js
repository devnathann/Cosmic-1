function WebPageForumInterface(main_page) {

    this.main_page = main_page;
  
    this.editbox = [
        '<form action="community/forum/edit" method="post">\n' +
        '<div class="replybox" style="padding-top:20px; border-top: 1px solid #acacac; border-spacing: 15px;">\n' +
            '<textarea name="message" id="editor" class="rounded-textarea blue-active">{{data}}</textarea><br />' +
            '<input type="submit" class="btn btn-success" value="Aanpassen">' +
            '<input type="submit" class="btn btn-error" value="Annuleren">' +
            '<input type="hidden" name="action" value="edit">' +
            '<input type="hidden" name="id" value="{{id}}">' +
        '</div>'
    ].join("");
  
    this.init = function () {
        var self = this;
        var page_container = this.main_page.get_page_container();
      
        page_container.find(".new-thread").click(function () 
        {
          
            if (!User.is_logged)
              return;
          
            $("#editor").css("height","320px");
            $("#editor").wysibb();

            $("#forum-category, .new-thread, .pagination").hide();
            $("#thread-content, .redo-reply").show();
        });
      
        page_container.find(".redo-reply").click(function () 
        {
            $("#thread-content, .redo-reply").hide();
            $("#forum-category, .new-thread, .pagination").show();
        });
      
        page_container.find(".replybtn").click(function () {

            if($(this).data("id") !== undefined) {
                $("#editor").val('#quote:' + $(this).data("id") + '\n\n');
            }
          
            if($(this).data("status") == "closed") {
                Web.notifications_manager.create("info", "Dit topic is gesloten en er kan niet gereageerd worden.", "Oeps..");
                return;
            } else if(User.is_logged == false) {
                Web.notifications_manager.create("info", "Om te kunnen reageren dien je ingelogd te zijn!", "Oeps..");
                return;
            }
           
            $("#editor").css("height","220px");
            $("#editor").wysibb();

            $(".replybox").show();
            $('html,body').animate({scrollTop: document.body.scrollHeight},"fast");
            
        });
      
        page_container.find(".topicreply").click(function () 
        {
            var post_id = $(this).data("id");
          
            Web.ajax_manager.post("/community/forum/edit", {id: post_id, action: "view"}, function (result) {
                  if(result.status == "success") {
                      page_container.find(".replybox").remove();

                      var test = $(self.editbox.replace(/{{data}}/g, atob(result.data)).replace(/{{id}}/g, post_id));
                      page_container.find($(".forum-likes-container[data-id=" + post_id  +"]")).append(test);

                      $("#editor").wysibb();
                  }
            });
        });
      
        page_container.find(".fa-heart").click(function ()
        {
            if($(this).hasClass("tools-active"))
                self.like($(this).data("id"));
        });
      
        page_container.find(".btn-func").click(function ()
        {
            self.closeSticky($(this).data('id'), $(this).data('status'));
        });

        $('#pagination').twbsPagination({
            totalPages: $("[name=totalpages]").val(),
            pageUrl: $("[name=page_url]").val(),
            startPage: parseFloat($("[name=currentpage]").val()),
            visiblePages: 10,
            pageVariable: 'page',
            href: true,
            first: 'Eerste',
            prev: 'Vorige',
            last: 'Laatste',
            next: 'Volgende'
        });
    };

    this.closeSticky = function(forum_id, actions)
    {
        if(User.is_logged && User.is_staff)
            Web.ajax_manager.post("/community/forum/stickyclosethread", {id: forum_id, action: actions});
    };
  
    this.like = function (forum_id)
    {
        if(!User.is_logged){
            Web.notifications_manager.create("error", "Je moet ingelogd zijn om deze post te kunnen liken!", "Uitgelogd :(");
            fail;
        }
      
        Web.ajax_manager.post("/community/forum/like", {id: forum_id, url: Web.pages_manager.current_page_url}, function (result){
            if(result.status == 'success'){
                $('.fa-heart[data-id='+ forum_id +']').removeClass("tools-active");
            }
        });
    };
}
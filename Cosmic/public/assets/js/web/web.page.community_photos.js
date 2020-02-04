function WebPageCommunityPhotosInterface(main_page) {
    var loadmore = true;

    this.main_page = main_page;
    this.photo_template = [
        '<div class="photo-container" style="display: none;">\n' +
        '    <div class="photo-content">\n' +
        '        <a href="{story}" class="photo-picture" target="_blank" style="background-image: url({story});" data-title="{photo.date.min} door {creator.username}"></a>\n' +
        '        <a href="#" class="photo-meta flex-container flex-vertical-center">\n' +
        '            <div class="photo-meta-left-side"><img src="/imaging/avatarimage?figure={creator.figure}&gesture=sml&headonly=1" alt="{creator.username}" class="pixelated"></div>\n' +
        '            <div class="photo-meta-right-side">\n' +
        '                <div class="creator-name">{creator.username}</div>\n' +
        '                <div class="published-date">{photo.date.full}</div>\n' +
        '                <span class="likes-count fc-like" data-id="{photo._id}">{photo.likes}</span> <i class="fa fa-heart" data-id="{photo._id}" style="color: #D67979;"></i>  <i class="fa fa-flag" data-id="{photo._id}" data-report="photo" style="color: #7B7777;"></i>' +
        '            </div>\n' +
        '        </a>\n' +
        '    </div>\n' +
        '</div>'
    ].join("");
    this.current_page = 1;

    /*
    * Generic function
    * */
    this.init = function () {
        var self = this;
        var page_container = this.main_page.get_page_container();

        // Init photos gallery
        page_container.find(".photos-container").magnificPopup({
            delegate: "a.photo-picture",
            type: "image",
            closeOnContentClick: false,
            closeBtnInside: false,
            mainClass: "mfp-with-zoom mfp-img-mobile",
            image: {
                verticalFit: true,
                titleSrc: function (item) {
                    if (User.is_logged == true) {
                        return '<i class="fa fa-flag" data-value="photos" data-id="' + item.el.attr("data-id") + '" data-report="photo" style="color: #fff;"></i> ' + item.el.attr("data-title");
                    } else {
                        return item.el.attr("data-title");
                    }
                }
            },
            gallery: {
                enabled: true
            },
            zoom: {
                enabled: true,
                duration: 300,
                opener: function (element) {
                    return element;
                }
            }
        });

        page_container.find(".fa-heart").click(function () {
            if (loadmore == true) {
                addPhotoLike($(this).attr("data-id"));
            }
        });

        // Load more photos
        page_container.find(".load-more-button button").click(function () {
            var countdivs = $('.photo-container').length;
            Web.ajax_manager.post("/community/photos/more", {
                current_page: self.current_page,
                offset: countdivs
            }, function (result) {
                if (result.photos.length > 0) {
                    for (var i = 0; i < result.photos.length; i++) {
                        var photo_data = result.photos[i];
                        var photo_template = $(self.photo_template.replace(/{story}/g, photo_data.url).replace(/{photo._id}/g, photo_data.id).replace(/{photo.likes}/g, photo_data.likes).replace(/{photo.date.full}/g, photo_data.timestamp).replace(/{photo.date.min}/g, photo_data.timestamp).replace(/{creator.username}/g, photo_data.author).replace(/{creator.figure}/g, photo_data.look));
                        page_container.find(".photos-container").append(photo_template);
                        photo_template.fadeIn();

                        page_container.find(".fa-heart[data-id=" + photo_data.id + "]").click(function () {
                            addPhotoLike($(this).attr("data-id"));
                        });
                    }

                    self.current_page = result.current_page;
                }
            });
        });

        function addPhotoLike(id) {
            if (User.is_logged == true) {
                Web.ajax_manager.post("/community/photos/like", {post: id}, function (result) {
                    if (result.status == 'success') {
                        $('.fa-heart[data-id=' + id + ']').addClass("pulsateOnce");
                        $('.likes-count[data-id=' + id + ']').text(parseInt($('.likes-count[data-id=' + id + ']').text()) + 1);
                    }
                });
            } else {
                Web.notifications_manager.create("error", Locale.web_page_community_photos_login, Locale.web_page_community_photos_loggedout);
            }
        }
    };
}
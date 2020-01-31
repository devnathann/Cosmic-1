function WebAjaxManagerInterface() {
  
    this.get =  function(url, callback) {
        PageLoading.show();
      
        // Requests
        $.ajax({
            type: "get",
            url: url,
            dataType: "json",
            processData: false,
            contentType: false,
            error: function (request, status, error) {
                PageLoading.hide();
                Web.notifications_manager.create("error", error, request.responseText);
            }
        }).done(function (result) {
            PageLoading.hide();
          
            if (typeof callback === "function")
                callback(result);
        });
    }
  
    /*
    * Post method
    * */
    this.post = function (url, data, callback, form) {
        // Prepare data
        if (!(data instanceof FormData)) {
            if (!(data instanceof Object))
                return;

            var data_source = data;
            data = new FormData();
            for (var key in data_source) {
                if (!data_source.hasOwnProperty(key))
                    continue;

                data.append(key, data_source[key]);
            }
        }

        // Check form name
        if (form !== undefined) {
            if (form.attr("action") === "login")
                data.append("return_url", window.location.href);
        }

        PageLoading.show();

        // Requests
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: "json",
            processData: false,
            contentType: false
        }).done(function (result) {
            PageLoading.hide();

            // Change full page
            if (result.location) {
                window.location = result.location;
                return null;
            }

            // Change page
            if (result.pagetime)
                setTimeout(function () {
                    window.location = result.pagetime
                }, 2500);

            // Change page
            if (result.loadpage)
                Web.pages_manager.load(result.loadpage);

            // Replace page
            if (result.replacepage)
                Web.pages_manager.load(result.replacepage, null, true, null, true, true);

            // Build modal
            if (result.modal) {
                $.magnificPopup.open({
                    closeOnBgClick: false,
                    items: [{
                        modal: true,
                        src: "/popup/" + result.modal,
                        type: "ajax"
                    }]
                }, 0);
            }

            // Close popup
            if (result.close_popup)
                $.magnificPopup.close();

            // Check if is form
            if (form !== undefined) {
                if (!result.captcha_error)
                    form.find(".registration-recaptcha").removeClass("registration-recaptcha").removeAttr("data-sitekey").removeAttr("data-callback");
            }

            // Create notification
            if (!isEmpty(result.status) && !isEmpty(result.message))
                Web.notifications_manager.create(result.status, result.message, (result.title ? result.title : null), (Number.isInteger(result.timer) ? result.timer : undefined), (result.link ? result.link : null));

            // Callback if exists
            if (typeof callback === "function")
                callback(result);
        });
    };
}
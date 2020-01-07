function WebNotificationsManagerInterface()
{
    this.titles_configutation = {
        success: Locale.web_notifications_success,
        error: Locale.web_notifications_error,
        info: Locale.web_notifications_info
    };
    this.notifications = {};

    this.create = function (type, message, title, timer, link)
    {
        var notification_id = (new Date().getTime() + Math.floor((Math.random() * 10000) + 1)).toString(16);

        if (timer === undefined)
            timer = 5;

        this.notifications[notification_id] = new WebNotificationInterface(this, notification_id, type, message, title, timer, link);
        this.notifications[notification_id].init();
    };

    this.destroy = function (id)
    {
        if (!this.notifications.hasOwnProperty(id))
            return null;

        this.notifications[id].notification.remove();
        delete this.notifications[id];
    };
}

function WebNotificationInterface(manager, id, type, message, title, timer, link)
{
    this.manager = manager;
    this.id = id;
    this.type = type;
    this.message = message;
    this.title = title;
    this.timer = timer;
    this.link = link;
    this.notification = null;
    this.timeout = null;


    this.init = function ()
    {
        var self = this;
        var template = [
            '<div class="notification-container" data-id="' + this.id + '" data-type="' + this.type + '">\n' +
            '    <a href="#" class="notification-close"></a>\n' +
            '    <div class="notification-title">' + (this.title != null ? this.title : this.manager.titles_configutation[this.type]) + '</div>\n' +
            '    <div class="notification-content">' + this.message + '</div>\n' +
            '</div>'
        ].join("");

        this.notification = $(template).appendTo(".notifications-container");

        this.notification.find(".notification-close").click(function ()
        {
            self.close();
        });

        if (this.link !== null)
        {
            this.notification.click(function ()
            {
                if ($(this).hasClass("notification-close"))
                    return null;

                var href = self.link.replace(Site.url + "/", "").replace(Site.url, "");
                if (!href)
                    href = "home";

                Web.page_container.load(href);
            });
        }

        if (this.timer !== 0)
        {
            this.notification.hover(function ()
            {
                clearTimeout(self.timeout);
            }, function ()
            {
                self.timeout = setTimeout(function()
                {
                    self.close();
                }, self.timer * 1000);
            });
        }

        this.show();
    };

    this.show = function ()
    {
        var self = this;

        if (this.timer === 0)
            this.notification.fadeIn();
        else
        {
            this.notification.fadeIn();
            this.timeout = setTimeout(function()
            {
                self.close();
            }, this.timer * 1000);
        }
    };

    this.close = function ()
    {
        var self = this;
        this.notification.animate({"opacity": 0}, 300, function ()
        {
            $(this).slideUp(400, function ()
            {
                self.manager.destroy(self.id);
            });
        });
    };
}
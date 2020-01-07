function WebDialogManagerInterface()
{
    this.buttons = null;
    this.input = null;
    this.type = null;
    this.title = null;
    this.content = null;
    this.callback = null;

    this.create = function (type, content, title, buttons, input, callback)
    {
        // Reset default
        this.buttons = {
            cancel: Locale.web_dialog_cancel,
            confirm: Locale.web_dialog_validate
        };
        this.type = null;
        this.title = null;
        this.content = null;
        this.input = null;
        this.callback = null;
      
        // Assign new values
        this.type = type;
        this.title = title === undefined ? Locale.web_dialog_confirm : title;
        this.content = content;
        this.callback = callback;
        this.input = input;
      
        if (buttons !== undefined)
            this.assign_buttons(buttons);
      
        this.build();
    };

    this.build = function ()
    { 
      
        var self = this;

        var template = [
            '<div class="' + this.type + '-popup dialog-popup zoom-anim-dialog">\n' +
            '    <h3>' + this.title + '</h3>\n' +
            '    ' + this.content + '\n' +
            '    <div class="input-container"></div>' +
            '    <div class="buttons-container"></div>' +
            '</div>'
        ].join("");

        var dialog = $(template);
        
        dialog.find(".buttons-container").append('<button class="rounded-button ' + (this.type === "confirm" ? 'red' : 'lightblue') + ' cancel">' + this.buttons.cancel + '</button>');

        if(this.input !== null) {
            dialog.find(".input-container").append('<br /><input type="text" class="' + this.input + ' rounded-input purple-active dialog-input">');
        }
    
        if (this.type === "confirm")
            dialog.find(".buttons-container").append('<button class="rounded-button red plain confirm">' + this.buttons.confirm + '</button>');

        $.magnificPopup.open({
            modal: this.type === "confirm",
            items: {
                src: dialog,
                type: "inline"
            },
            callbacks: {
                open: function ()
                {
                    var content = $(this.content);

                    content.unbind().on("click", ".confirm", function ()
                    {
                      
                        var result = $('.dialog-input').map(function() {
                            return $(this).val();
                        }).toArray();      

                        $.magnificPopup.close();
                        $(document).off("keydown", keydownHandler);

                        if (typeof self.callback === "function")
                            self.callback(result)

                    }).on("click", ".cancel", function ()
                    {
                        $.magnificPopup.close();
                        $(document).off("keydown", keydownHandler);

                    });

                    var keydownHandler = function (event)
                    {
                        if (event.keyCode === 13)
                        {
                            content.find(".confirm").click();
                            return false;
                        }
                        else if (event.keyCode === 27)
                        {
                            content.find(".cancel").click();
                            return false;
                        }
                    };

                    $(document).on("keydown", keydownHandler);
                }
            }
        });
    };

    this.assign_buttons = function (buttons)
    {
        for (var name in buttons)
        {
            if (buttons.hasOwnProperty(name))
                this.buttons[name] = buttons[name];
        }
    };

}
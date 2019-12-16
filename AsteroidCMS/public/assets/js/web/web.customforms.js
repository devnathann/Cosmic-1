function WebCustomFormsManagerInterface()
{
    this.forms = {};

    this.init_forms = function ()
    {
        var self = this;

        this.clear();

        Web.pages_manager.page_container.find(".customform-container").each(function ()
        {
            var form_id = $(this).attr("data-id");
            var form_interface = new WebCustomFormInterface(self, form_id, $(this));
            form_interface.init();
            self.forms[form_id] = form_interface;
        });
    };

    this.clear = function ()
    {
        this.forms = {};
    };
}

function WebCustomFormInterface(manager, id, form_container)
{
    this.manager = manager;
    this.id = id;
    this.form_container = form_container;
    this.section_id = 0;
    this.section_elements = [];

    this.init = function ()
    {
        var self = this;

        Web.ajax_manager.post("/ajax/request?action=customform_init&form=" + this.id, {}, function (data)
        {
            if (data.error)
            {
                self.form_container.html(data.error);
                return null;
            }

            self.build_form_structure(data.form_data);
            self.load_section(data.load_section);
        });

        this.form_container.on("submit", ".customform-form", function (event)
        {
            event.preventDefault();
            var form_data = new FormData(this);

            var names_checked = [];
            var required_missing = false;
            $(this).find("[name]").each(function ()
            {
                var field_name = $(this).attr("name");
                if (names_checked.indexOf(field_name) >= 0)
                    return;

                var answer_container = $(this).closest(".customform-answer");
                var required = answer_container.attr("data-required") === "1";
                var type = ~~answer_container.attr("data-type");

                if (required)
                {
                    var value = form_data.get(field_name);
                    if (type === 2)
                        value = form_data.getAll(field_name);

                    if (isEmpty(value))
                        required_missing = true;
                }

                names_checked.push(field_name);
            });

            if (required_missing)
            {
                Web.notifications_manager.create("error", "Tous les champs marqués d'un astérisque sont obligatoires.");
                return null;
            }

            form_data.append("form-id", self.id);
            Web.ajax_manager.post("/ajax/request?action=customform_submit&form=" + self.id + "&section=" + self.section_id, form_data, function (data)
            {
                if (data.hasOwnProperty("load_section"))
                    self.load_section(data.load_section);

                if (data.hasOwnProperty("submitted"))
                    self.show_final_message();
            });
        });
    };

    this.load_section = function (section)
    {
        var self = this;

        Web.ajax_manager.post("/ajax/request?action=customform_section&form=" + this.id + "&section=" + section, {}, function (data)
        {
            self.build_section(data);
        });
    };

    this.build_form_structure = function (data)
    {
        var template = ['<div class="customform-header">\n' +
        '    <div class="customform-header-title"></div>\n' +
        '    <div class="customform-header-description"></div>\n' +
        '    <div class="customform-header-toggle"></div>\n' +
        '</div>'].join("");

        var form_header = $(template).appendTo(this.form_container);
        form_header.find(".customform-header-title").html(data.title);
        form_header.find(".customform-header-description").html(data.description);
        form_header.find(".customform-header-toggle").click(function ()
        {
            $(this).closest(".customform-container").toggleClass("opened");
        });

        $('<div class="customform-section customform-initialization">Chargement du formulaire...</div>').appendTo(this.form_container);
    };

    this.build_section = function (data)
    {
        this.section_id = data.section_data.id;

        var template = ['<div class="customform-section">\n' +
        '    <form action="" method="post" class="customform-form default-prevent">\n' +
        '        <div class="customform-section-title"></div>\n' +
        '        <div class="customform-section-description"></div>\n' +
        '        <div class="customform-section-elements"></div>\n' +
        '        <div class="customform-submit-container">\n' +
        '            <button type="submit" class="customform-submit rounded-button purple">' + (!data.last_section ? 'Suivant' : 'Terminer') + '</button>\n' +
        '        </div>\n' +
        '    </form>\n' +
        '</div>'].join("");

        this.form_container.find(".customform-initialization").remove();
        this.form_container.find(".customform-section").remove();

        var section = $(template).appendTo(this.form_container);
        section.find(".customform-section-title").html(data.section_data.title);
        section.find(".customform-section-description").html(data.section_data.description);

        for (var i in data.section_data.elements)
        {
            if (!data.section_data.elements.hasOwnProperty(i))
                continue;

            var element = new WebCustomFormElementInterface(this, data.section_data.elements[i]);
            this.section_elements.push(element);
            element.init();
        }
    };

    this.show_final_message = function ()
    {
        var template = ['<div class="customform-section">\n' +
        '    <div class="customform-section-title">Merci pour ta participation !</div>\n' +
        '    <div class="customform-section-description" style="margin-bottom: 0;">Tes réponses ont été envoyées et vont être analysées par la personne à l\'initiative de ce formulaire.<br><br>À bientôt !</div>\n' +
        '</div>'].join("");

        this.form_container.find(".customform-section").remove();
        $(template).appendTo(this.form_container);
    };
}

function WebCustomFormElementInterface(form, data)
{
    this.form = form;
    this.data = data;
    this.id = 0;
    this.type = null;
    this.required = false;
    // this.options = false;

    this.init = function ()
    {
        this.id = data.id;

        var template = ['<div class="customform-element">\n' +
        '    <div class="customform-element-title"></div>\n' +
        '    <div class="customform-element-description"></div>\n' +
        '</div>'].join("");

        var element = $(template).appendTo(form.form_container.find(".customform-section .customform-section-elements"));
        element.find(".customform-element-title").html(data.title);
        element.find(".customform-element-description").html(data.description);

        if (data.type === "question")
        {
            element.append('<div class="customform-element-options"></div>');

            var data_details = data.data.split(chr(1));
            this.type = ~~data_details[0];
            this.required = data_details[1] === "1";

            if (this.required)
                element.find(".customform-element-title").addClass("required");

            if (this.type >= 2 && this.type <= 4)
            {
                var customform_options = $("<div class=\"customform-options customform-answer\" data-type=\"" + this.type + "\" data-required=\"" + data_details[1] + "\"></div>").appendTo(element.find(".customform-element-options"));

                if (this.type === 4)
                    customform_options.append('<select name="customform-option-' + this.id + '"></select>');

                var options = data_details[3].split(chr(2));
                for (var i = 0; i < options.length; i++)
                {
                    var option = options[i];
                    var option_detail = option.split(chr(3));

                    var option_template;
                    if (this.type === 2)
                    {
                        option_template = ['<div class="customform-option">\n' +
                        '    <label class="radio-container purple">\n' +
                        '        <input type="checkbox" name="customform-option-' + this.id + '[]" value="' + option_detail[0] + '">\n' +
                        '        <span class="radio-button"></span>\n' +
                        '        <span class="radio-label">' + option_detail[1] + '</span>\n' +
                        '    </label>\n' +
                        '</div>'].join("");

                        $(option_template).appendTo(customform_options);
                    }
                    else if (this.type === 3)
                    {
                        option_template = ['<div class="customform-option">\n' +
                        '    <label class="radio-container rounded-radio purple">\n' +
                        '        <input type="radio" name="customform-option-' + this.id + '" value="' + option_detail[0] + '">\n' +
                        '        <span class="radio-button"></span>\n' +
                        '        <span class="radio-label">' + option_detail[1] + '</span>\n' +
                        '    </label>\n' +
                        '</div>'].join("");

                        $(option_template).appendTo(customform_options);
                    }
                    else if (this.type === 4)
                    {
                        $('<option value="' + option_detail[0] + '">' + option_detail[1] + '</option>').appendTo(customform_options.find("select"));
                    }
                }

                if (this.type === 4)
                    customform_options.find("select").selectric({
                        theme: "web"
                    });

            }
            else if (this.type === 5 || this.type === 6)
            {
                var customform_options = $("<div class=\"customform-answer\" data-required=\"" + data_details[1] + "\"></div>").appendTo(element.find(".customform-element-options"));

                if (this.type === 5)
                    customform_options.append('<input name="customform-option-' + this.id + '" class="rounded-input purple-active" placeholder="Ta réponse">');
                else
                    customform_options.append('<textarea name="customform-option-' + this.id + '" class="rounded-textarea purple-active" placeholder="Ta réponse"></textarea>');

            }
        }
    };
}
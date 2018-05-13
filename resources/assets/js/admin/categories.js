var Categories = (function() {

    var that = {};

    that.TitleToLink = function() {
        var title = $(".input_title_add");
        var link = $(".input_slug_add");

        title.bind("change paste keyup", function() {

            var accent = [
                /[\300-\306]/g, /[\340-\346]/g, // A, a
                /[\310-\313]/g, /[\350-\353]/g, // E, e
                /[\314-\317]/g, /[\354-\357]/g, // I, i
                /[\322-\330]/g, /[\362-\370]/g, // O, o
                /[\331-\334]/g, /[\371-\374]/g, // U, u
                /[\321]/g, /[\361]/g, // N, n
                /[\307]/g, /[\347]/g // C, c
            ];

            var noaccent = ['A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u', 'N', 'n', 'C', 'c'];

            var key = title.val();

            for (var i = 0; i < accent.length; i++) {
                key = key.replace(accent[i], noaccent[i]);
            }

            key = key.replace(/'/g, "");
            key = key.replace(/"/g, "");
            key = key.replace(/ /g, "-");

            link.val(key);
        });
    };

    that.updatetitlecat = function(get_t) {
        var catid = get_t.data("catid");
        var data_t = get_t.val();
        $.ajax({
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                    xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                }
            },
            method: "POST",
            url: App.GetBaseUrl() + "admin/ajax/categories/updatetitle",
            data: {
                'id': catid,
                'title': data_t
            },
            dataType: 'json',
            cache: false,
            success: function(data) {
                if (data.code = 1) {
                    App.NotifToast("success", data.title, "");
                } else {
                    App.NotifToast("error", "Erreur", data.message);
                }
            },
            error: function(data) {
                console.log(data.responseText);
            }
        });
    };

    that.updatecontentcat = function(get_t) {
        var catid = get_t.data("catid");
        var data_c = get_t.val();
        $.ajax({
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                    xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                }
            },
            method: "POST",
            url: App.GetBaseUrl() + "admin/ajax/categories/updatecontent",
            data: {
                'id': catid,
                'content': data_c
            },
            dataType: 'json',
            cache: false,
            success: function(data) {
                if (data.code = 1) {
                    App.NotifToast("success", data.title, "");
                } else {
                    App.NotifToast("error", "Erreur", data.message);
                }
            },
            error: function(data) {
                console.log(data.responseText);
            }
        });
    };

    that.addcat = function(get_par) {
        get_par.parents(".ac").before("<div class=\"col-md-3 casecat\">\n" +
            "                <div class=\"card border-secondary mb-3\">\n" +
            "<form class='add_cat_form'>\n" +
            "                    <div class=\"card-header\">\n" +
            "                        <input type=\"text\" class=\"form-control input_title_add\" placeholder='Titre' />\n" +
            "                        <input type=\"text\" class=\"form-control input_slug_add\" placeholder='Slug' />\n" +
            "                        <input type=\"text\" class=\"form-control input_image_add\" placeholder='Image' />\n" +
            "                    </div>\n" +
            "                    <div class=\"card-body\">\n" +
            "                        <p class=\"card-text\">\n" +
            "                            <textarea style=\"height: 150px;\" class=\"form-control textarea_content_add\"></textarea>\n" +
            "                        </p>\n" +
            "                    </div>\n" +
            "                    <div class=\"card-footer\">\n" +
            "                        <small class=\"text-muted\">\n" +
            "<button type='submit' class='btn btn-success'>Ajouter une nouvel catégorie</button>\n" +
            "                        </small>\n" +
            "                    </div>\n" +
            "</form>\n" +
            "                </div>\n" +
            "            </div>")
    };

    that.addcatform = function() {
        var title = $(".input_title_add").val();
        var slug = $(".input_slug_add").val();
        var content = $(".textarea_content_add").val();
        var icon = $(".input_image_add").val();

        if (title && content) {
            $.ajax({
                beforeSend: function(xhr, settings) {
                    if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                        xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                    }
                },
                method: "POST",
                url: App.GetBaseUrl() + "admin/ajax/categories/add",
                data: {
                    'title': title,
                    'content': content,
                    'slug': slug,
                    'icon': icon
                },
                dataType: 'json',
                cache: false,
                success: function(data) {
                    if (data.code = 1) {
                        App.NotifToast("success", data.title, data.message);
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else {
                        App.NotifToast("error", "Erreur", data.message);
                    }
                },
                error: function(data) {
                    console.log(data.responseText);
                }
            });
        }
    };

    that.init = function() {
        $(".input_title").bind("paste keyup", function() {
            that.updatetitlecat($(this));
        });

        $(".textarea_content").bind("paste keyup", function() {
            that.updatecontentcat($(this));
        });

        $(".add_cat").click(function() {
            that.addcat($(this));
            that.TitleToLink();
            $(".add_cat_form").submit(function(e) {
                e.preventDefault();
                that.addcatform();
                return false;
            });
        });
    };

    return that;

})();

$(document).ready(function() {
    Categories.init();
});
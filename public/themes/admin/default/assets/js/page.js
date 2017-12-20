var Page = (function() {

    var that = {};

    that.TitleToLink = function() {
        var title = $("#title");
        var link = $("#link");

        title.bind("change paste keyup", function() {

            var accent = [
                /[\300-\306]/g, /[\340-\346]/g, // A, a
                /[\310-\313]/g, /[\350-\353]/g, // E, e
                /[\314-\317]/g, /[\354-\357]/g, // I, i
                /[\322-\330]/g, /[\362-\370]/g, // O, o
                /[\331-\334]/g, /[\371-\374]/g, // U, u
                /[\321]/g, /[\361]/g, // N, n
                /[\307]/g, /[\347]/g, // C, c
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

    that.AddPage = function() {

        $(".add_page_form").submit(function(e) {
            e.preventDefault();

            var title = $("#title");
            var link = $("#link");
            var content = $("#content");

            var active = $("input[name=options]:checked").val();

            var errors = null;

            if (!title.val()) {
                errors = true;
                title.addClass("is-invalid");
                setTimeout(function() {
                    title.removeClass("is-invalid");
                }, 1500);
            } else {
                title.addClass("is-valid");
            }

            if (!link.val()) {
                errors = true;
                link.addClass("is-invalid");
                setTimeout(function() {
                    link.removeClass("is-invalid");
                }, 1500);
            } else {
                link.addClass("is-valid");
            }

            if (!content.val()) {
                errors = true;
                content.addClass("is-invalid");
                setTimeout(function() {
                    content.removeClass("is-invalid");
                }, 1500);
            } else {
                content.addClass("is-valid");
            }

            if (errors == null) {
                $.ajax({
                    beforeSend: function(xhr, settings) {
                        if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                            xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                        }
                    },
                    method: "POST",
                    url: App.GetBaseUrl() + "admin/ajax/page/add",
                    data: {
                        'title': title.val(),
                        'link': link.val(),
                        'content': content.val(),
                        'active': active
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data) {
                        if (data.code = 1) {
                            App.NotifToast("success", data.title, data.message);
                        } else {
                            App.NotifToast("error", "Erreur", data.message);
                        }
                    },
                    error: function(data) {
                        console.log(data.responseText);
                    }
                });
            }

            return false;
        });

    };

    that.EditPage = function() {

        $(".edit_page_form").submit(function(e) {
            e.preventDefault();

            var pageid = $(this).data("pageid");
            var title = $("#title");
            var link = $("#link");
            var content = $("#content");

            var active = $("input[name=options]:checked").val();

            var errors = null;

            if (!title.val()) {
                errors = true;
                title.addClass("is-invalid");
                setTimeout(function() {
                    title.removeClass("is-invalid");
                }, 1500);
            } else {
                title.addClass("is-valid");
            }

            if (!link.val()) {
                errors = true;
                link.addClass("is-invalid");
                setTimeout(function() {
                    link.removeClass("is-invalid");
                }, 1500);
            } else {
                link.addClass("is-valid");
            }

            if (!content.val()) {
                errors = true;
                content.addClass("is-invalid");
                setTimeout(function() {
                    content.removeClass("is-invalid");
                }, 1500);
            } else {
                content.addClass("is-valid");
            }

            if (errors == null) {
                $.ajax({
                    beforeSend: function(xhr, settings) {
                        if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                            xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                        }
                    },
                    method: "POST",
                    url: App.GetBaseUrl() + "admin/ajax/page/edit",
                    data: {
                        'pageid': pageid,
                        'title': title.val(),
                        'link': link.val(),
                        'content': content.val(),
                        'active': active
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data) {
                        if (data.code = 1) {
                            App.NotifToast("success", data.title, data.message);
                        } else {
                            App.NotifToast("error", "Erreur", data.message);
                        }
                    },
                    error: function(data) {
                        console.log(data.responseText);
                    }
                });
            }

            return false;
        });

    };

    that.init = function() {
        BBCode.init();
        that.TitleToLink();
        that.AddPage();
        that.EditPage();
    };

    return that;

})();

$(document).ready(function() {
    Page.init();
});
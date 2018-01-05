var Article = (function() {

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

    that.AddArticle = function() {

        $(".add_picture_one").click(function() {
            $.ajax({
                beforeSend: function(xhr, settings) {
                    if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                        xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                    }
                },
                method: "POST",
                url: App.GetBaseUrl() + "admin/ajax/media/modal",
                data: {
                    "type_modal": "add_picture_one"
                },
                dataType: 'json',
                cache: false,
                success: function(data) {
                    $("body").append(data.content);

                    $(".modal-media_add_picture_one").modal('show');

                    $('.modal-media_add_picture_one').on('shown.bs.modal', function(e) {

                        $(".input-add-media").bind("change", function() {

                            if ($(this).get(0).files.length !== 0) {

                                $(".btn-add-media").css("display", "block");

                                var file_data = $(".input-add-media").prop("files")[0]; // Getting the properties of file from file field
                                var form_data = new FormData(); // Creating object of FormData class
                                form_data.append("pictures", file_data);

                                var local_url = (window.URL ? URL : webkitURL).createObjectURL(file_data);

                                $(".form-add-media").before("<span class=\"image_name\">" + file_data.name + "</span>");

                                $(".form-add-media").parent(".card-body").before('<img class="card-img-top img_form_upload_card" src="' + local_url + '" alt="Card image cap">');

                                $(".form-add-media").submit(function(e) {
                                    e.preventDefault();

                                    $.ajax({
                                        beforeSend: function(xhr, settings) {
                                            if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                                                xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                                            }
                                        },
                                        method: "POST",
                                        url: App.GetBaseUrl() + "admin/ajax/media/add",
                                        data: form_data,
                                        dataType: 'json',
                                        cache: false,
                                        processData: false,
                                        contentType: false,
                                        success: function(data) {
                                            if (data.code == 1) {
                                                $(".col-add-media").after('<div class="col-sm-2">\n' +
                                                    '                        <div class="card mb-3">\n' +
                                                    '                            <img class="card-img-top" src="' + App.GetBaseUrl() + '/' + data.slug + '" alt="Card image cap">\n' +
                                                    '                            <div class="card-body">\n' +
                                                    '                                <h4 class="card-title">' + file_data.name + '</h4>\n' +
                                                    '                                <p class="card-text" style="text-align: center">\n' +
                                                    '                                    <span class="btn btn-secondary choice_img_add_picture_one" data-mediaid="' + data.id + '" data-mediaslug="' + App.GetBaseUrl() + '/' + data.slug + '" style="margin-left: auto;margin-right: auto;">Choisir</span>\n' +
                                                    '                                </p>\n' +
                                                    '                            </div>\n' +
                                                    '                        </div>\n' +
                                                    '                    </div>');

                                                $(".image_name").remove();
                                                $(".img_form_upload_card").remove();
                                                $(".btn-add-media").css("display", "none");
                                                form_data.delete("pictures");
                                                App.NotifToast("success", data.title, data.message);
                                            } else {
                                                App.NotifToast("error", "Erreur", data.message);
                                            }
                                        },
                                        error: function(data) {
                                            App.NotifToast("error", "Erreur", data.responseText);
                                        }
                                    });

                                    return false;
                                });

                            }

                        });

                        $(".choice_img_add_picture_one").click(function() {
                            if ($(".add_picture_one").parents(".col-sm-9").children().hasClass("sl_pic_on")) {
                                $(".sl_pic_on").remove();
                            }
                            $(".add_picture_one").parents(".col-sm-9").prepend('<img data-slug="' + $(this).data("mediaslug") + '" src="' + App.GetBaseUrl() + '/' + $(this).data("mediaslug") + '" class="sl_pic_on rounded mb-2 col-sm-12" />');
                            $(".modal-media_add_picture_one").modal('hide');
                        });
                    });

                    $(".modal-media_add_picture_one").on('hidden.bs.modal', function(e) {
                        $(".modal-media_add_picture_one").remove();
                    });

                },
                error: function(data) {
                    console.log(data.responseText);
                }
            });
        });

        $(".add_article_form").submit(function(e) {
            e.preventDefault();

            var title = $("#title");
            var link = $("#link");
            var content = $("#content");
            var wordkey = $("#wordkey");
            var cat = $("#cat");
            var pic = $("#pic");

            var important = $("input[name=options]:checked").val();

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

            if (!wordkey.val()) {
                errors = true;
                wordkey.addClass("is-invalid");
                setTimeout(function() {
                    wordkey.removeClass("is-invalid");
                }, 1500);
            } else {
                wordkey.addClass("is-valid");
            }

            if (!cat.val()) {
                errors = true;
                cat.addClass("is-invalid");
                setTimeout(function() {
                    cat.removeClass("is-invalid");
                }, 1500);
            } else {
                cat.addClass("is-valid");
            }

            if (errors == null) {
                $.ajax({
                    beforeSend: function(xhr, settings) {
                        if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                            xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                        }
                    },
                    method: "POST",
                    url: App.GetBaseUrl() + "admin/ajax/article/add",
                    data: {
                        'title': title.val(),
                        'link': link.val(),
                        'content': content.val(),
                        'wordkey': wordkey.val(),
                        'cat': cat.val().join(";"),
                        'pic': $(".sl_pic_on").data("slug"),
                        'important': important
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data) {
                        if (data.code = 1) {
                            App.NotifToast("success", data.title, data.message);
                            setTimeout(function() {
                                window.location.href = App.GetBaseUrl() + "admin/article/edit/" + data.post_id + "/2";
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
            return false;

        });

    };

    that.EditArticle = function() {

        $(".update_picture_one").click(function() {
            $.ajax({
                beforeSend: function(xhr, settings) {
                    if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                        xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                    }
                },
                method: "POST",
                url: App.GetBaseUrl() + "admin/ajax/media/modal",
                data: {
                    "type_modal": "picture_one"
                },
                dataType: 'json',
                cache: false,
                success: function(data) {
                    $("body").append(data.content);

                    $(".modal-media_picture_one").modal('show');

                    $('.modal-media_picture_one').on('shown.bs.modal', function(e) {
                        $(".choice_img_picture_one").click(function() {
                            $(".sl_pic_on").data("slug", $(this).data("mediaslug")).attr("src", App.GetBaseUrl() + '/' + $(this).data("mediaslug"));
                        })
                    });

                    $(".modal-media_picture_one").on('hidden.bs.modal', function(e) {
                        $(".modal-media_picture_one").remove();
                    });

                },
                error: function(data) {
                    console.log(data.responseText);
                }
            });
        });

        $("button.submit").click(function(e) {
            e.preventDefault();

            var postid = $(".edit_article_form").data("postid");
            var title = $("#title");
            var link = $("#link");
            var content = $("#content");
            var wordkey = $("#wordkey");
            var cat = $("#cat");
            var type = $(this).data("type");

            var important = $("input[name=options]:checked").val();

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

            if (!wordkey.val()) {
                errors = true;
                wordkey.addClass("is-invalid");
                setTimeout(function() {
                    wordkey.removeClass("is-invalid");
                }, 1500);
            } else {
                wordkey.addClass("is-valid");
            }

            if (!cat.val()) {
                errors = true;
                cat.addClass("is-invalid");
                setTimeout(function() {
                    cat.removeClass("is-invalid");
                }, 1500);
            } else {
                cat.addClass("is-valid");
            }

            if (errors == null) {
                $.ajax({
                    beforeSend: function(xhr, settings) {
                        if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                            xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                        }
                    },
                    method: "POST",
                    url: App.GetBaseUrl() + "admin/ajax/article/edit",
                    data: {
                        'postid': postid,
                        'title': title.val(),
                        'link': link.val(),
                        'content': content.val(),
                        'wordkey': wordkey.val(),
                        'cat': cat.val().join(";"),
                        'pic': $(".sl_pic_on").data("slug"),
                        'important': important,
                        'type': type
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
            return false;

        });

    };

    that.init = function() {
        BBCode.init();
        that.TitleToLink();
        that.AddArticle();
        that.EditArticle();
    };

    return that;

})();

$(document).ready(function() {
    Article.init();
});
var Article = (function(){
    
    var that = {};
    
    that.TitleToLink = function () {
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

            var noaccent = ['A','a','E','e','I','i','O','o','U','u','N','n','C','c'];

            var key = title.val();

            for(var i = 0; i < accent.length; i++){
                key = key.replace(accent[i], noaccent[i]);
            }

            key = key.replace(/'/g, "");
            key = key.replace(/"/g, "");
            key = key.replace(/ /g,"-");

            link.val(key);
        });
    }

    that.AddArticle = function() {

        $("#pic").bind("change", function() {
            var file_data = $("#pic").prop("files")[0];   // Getting the properties of file from file field
            var form_data = new FormData();                  // Creating object of FormData class
            form_data.append("pictures", file_data)
            $.ajax({
                beforeSend: function (xhr, settings) {
                    if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                        xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                    }
                },
                method: "POST",
                url: App.GetBaseUrl() + "admin/ajax/upload",
                data: form_data,
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    $("#pic").parent("div").prepend('<img data-slug="' + data.slug + '" src="' + App.GetBaseUrl() + '/' + data.slug + '" class="sl_pic_on rounded mb-2 col-sm-12" />');
                    App.NotifToast("success", data.title, data.message);
                },
                error: function (data) {
                    App.NotifToast("error", "Erreur", data.responseText);
                }
            });
        });

        $(".add_article_form").submit(function(e) {
            e.preventDefault();

            var title = $("#title");
            var link = $("#link");
            var content = $("#content");
            var wordkey = $("#wordkey");
            var cat =  $("#cat");
            var pic = $("#pic");

            var important = $("input[name=options]:checked").val();

            var errors = null;

            if(!title.val()) {
                errors = true;
                title.addClass("is-invalid");
                setTimeout(function() {
                    title.removeClass("is-invalid");
                }, 1500);
            }
            else {
                title.addClass("is-valid");
            }

            if(!link.val()) {
                errors = true;
                link.addClass("is-invalid");
                setTimeout(function() {
                    link.removeClass("is-invalid");
                }, 1500);
            }
            else {
                link.addClass("is-valid");
            }

            if(!content.val()) {
                errors = true;
                content.addClass("is-invalid");
                setTimeout(function() {
                    content.removeClass("is-invalid");
                }, 1500);
            }
            else {
                content.addClass("is-valid");
            }

            if(!wordkey.val()) {
                errors = true;
                wordkey.addClass("is-invalid");
                setTimeout(function() {
                    wordkey.removeClass("is-invalid");
                }, 1500);
            }
            else {
                wordkey.addClass("is-valid");
            }

            if(!cat.val()) {
                errors = true;
                cat.addClass("is-invalid");
                setTimeout(function() {
                    cat.removeClass("is-invalid");
                }, 1500);
            }
            else {
                cat.addClass("is-valid");
            }

            if (errors == null) {
                $.ajax({
                    beforeSend: function (xhr, settings) {
                        if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                            xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                        }
                    },
                    method: "POST",
                    url: App.GetBaseUrl() + "admin/ajax/article/add",
                    data: {'title': title.val(), 'link': link.val(), 'content': content.val(), 'wordkey': wordkey.val(), 'cat': cat.val().join(";"), 'pic': $(".sl_pic_on").data("slug"), 'important': important},
                    dataType: 'json',
                    cache: false,
                    success: function (data) {
                        if(data.code = 1) {
                            App.NotifToast("success", data.title, data.message);
                            setTimeout(function() {
                                window.location.href = App.GetBaseUrl() + "admin/article/edit/" + data.post_id + "/2";
                            }, 3000);
                        }
                        else {
                            App.NotifToast("error", "Erreur", data.message);
                        }
                    },
                    error: function (data) {
                        console.log(data.responseText);
                    }
                });
            }
            return false;

        });

    };

    that.EditArticle = function () {

        /*$("#pic2").bind("change", function() {
            var file_data = $("#pic2").prop("files")[0];   // Getting the properties of file from file field
            var form_data = new FormData();                  // Creating object of FormData class
            form_data.append("pictures", file_data)
            $.ajax({
                beforeSend: function (xhr, settings) {
                    if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                        xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                    }
                },
                method: "POST",
                url: App.GetBaseUrl() + "admin/ajax/upload",
                data: form_data,
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    //$("#pic").parent("div").prepend('<img data-slug="' + data.slug + '" src="' + App.GetBaseUrl() + '/' + data.slug + '" class="sl_pic_on rounded mb-2 col-sm-12" />');
                    $(".sl_pic_on").data("slug", data.slug).attr("src", App.GetBaseUrl() + '/' + data.slug);
                    App.NotifToast("success", data.title, data.message);
                },
                error: function (data) {
                    App.NotifToast("error", "Erreur", data.responseText);
                }
            });
        });*/

        $(".update_picture_one").click(function() {
            $.ajax({
                beforeSend: function (xhr, settings) {
                    if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                        xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                    }
                },
                method: "POST",
                url: App.GetBaseUrl() + "admin/ajax/media/modal",
                data: {"type_modal": "picture_one"},
                dataType: 'json',
                cache: false,
                success: function (data) {
                    $("body").append(data.content);

                    $(".modal-media_picture_one").modal('show');

                    $('.modal-media_picture_one').on('shown.bs.modal', function (e) {
                        $(".choice_img_picture_one").click(function() {
                            $(".sl_pic_on").data("slug", $(this).data("mediaslug")).attr("src", App.GetBaseUrl() + '/' + $(this).data("mediaslug"));
                        })
                    });

                    $(".modal-media_picture_one").on('hidden.bs.modal', function (e) {
                        $(".modal-media_picture_one").remove();
                    });

                },
                error: function (data) {
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
            var type = $(this).data("type")

            var important = $("input[name=options]:checked").val();

            var errors = null;

            if(!title.val()) {
                errors = true;
                title.addClass("is-invalid");
                setTimeout(function() {
                    title.removeClass("is-invalid");
                }, 1500);
            }
            else {
                title.addClass("is-valid");
            }

            if(!link.val()) {
                errors = true;
                link.addClass("is-invalid");
                setTimeout(function() {
                    link.removeClass("is-invalid");
                }, 1500);
            }
            else {
                link.addClass("is-valid");
            }

            if(!content.val()) {
                errors = true;
                content.addClass("is-invalid");
                setTimeout(function() {
                    content.removeClass("is-invalid");
                }, 1500);
            }
            else {
                content.addClass("is-valid");
            }

            if(!wordkey.val()) {
                errors = true;
                wordkey.addClass("is-invalid");
                setTimeout(function() {
                    wordkey.removeClass("is-invalid");
                }, 1500);
            }
            else {
                wordkey.addClass("is-valid");
            }

            if(!cat.val()) {
                errors = true;
                cat.addClass("is-invalid");
                setTimeout(function() {
                    cat.removeClass("is-invalid");
                }, 1500);
            }
            else {
                cat.addClass("is-valid");
            }

            if (errors == null) {
                $.ajax({
                    beforeSend: function (xhr, settings) {
                        if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                            xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                        }
                    },
                    method: "POST",
                    url: App.GetBaseUrl() + "admin/ajax/article/edit",
                    data: {'postid': postid, 'title': title.val(), 'link': link.val(), 'content': content.val(), 'wordkey': wordkey.val(), 'cat': cat.val().join(";"), 'pic': $(".sl_pic_on").data("slug"), 'important': important, 'type': type},
                    dataType: 'json',
                    cache: false,
                    success: function (data) {
                        if(data.code = 1) {
                            App.NotifToast("success", data.title, data.message);
                            setTimeout(function() {
                                location.reload();
                            }, 3000);
                        }
                        else {
                            App.NotifToast("error", "Erreur", data.message);
                        }
                    },
                    error: function (data) {
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

$(document).ready(function () {
    Article.init();
});
var Media = (function() {

    var that = {};

    that.AddMedia = function() {
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
                                    '                                    <span class="btn btn-danger remove_img" data-mediaid="' + data.id + '" data-medianame="' + file_data.name + '" data-mediaslug="/' + data.slug + '" style="margin-left: auto;margin-right: auto;">Delete</span>\n' +
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
    };

    that.removeMedia = function(get_pic) {
        var media_id = get_pic.data("mediaid");
        var media_slug = get_pic.data("mediaslug");
        var media_name = get_pic.data("medianame");

        $.ajax({
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                    xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                }
            },
            method: "POST",
            url: App.GetBaseUrl() + "admin/ajax/media/remove",
            data: {
                'id': media_id,
                'slug': media_slug,
                'name': media_name
            },
            dataType: 'json',
            cache: false,
            success: function(data) {
                if (data.code = 1) {
                    get_pic.parents(".col-sm-2").remove();
                    App.NotifToast("success", data.title, data.message);
                } else {
                    App.NotifToast("error", "Erreur", data.message);
                }
            },
            error: function(data) {
                console.error(data.responseText);
            }
        });

    };

    that.init = function() {
        that.AddMedia();
        $(".remove_img").click(function(e) {
            e.preventDefault();
            that.removeMedia($(this));
            return false;
        });
    };

    return that;

})();

$(document).ready(function() {
    Media.init();
});
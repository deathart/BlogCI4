var BBCode = (function() {

    var that = {};

    that.insertTag = function(openTag, closeTag, type, textareaId) {
        var textArea = textareaId;
        var len = textArea.val().length;
        var start = textArea[0].selectionStart;
        var end = textArea[0].selectionEnd;
        var selectedText = textArea.val().substring(start, end);
        var replacement = openTag + selectedText + closeTag;
        textArea.val(textArea.val().substring(0, start) + replacement + textArea.val().substring(end, len));
    };

    that.media = function(textarea) {
        $.ajax({
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                    xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                }
            },
            method: "POST",
            url: App.GetBaseUrl() + "admin/ajax/media/modal",
            data: {
                "type_modal": "bbcode"
            },
            dataType: 'json',
            cache: false,
            success: function(data) {
                $("body").append(data.content);

                $(".modal-media_bbcode").modal('show');

                $('.modal-media_bbcode').on('shown.bs.modal', function(e) {

                    $(".input-add-media").bind("change", function() {

                        if ($(this).get(0).files.length !== 0) {

                            $(".btn-add-media").css("display", "block");

                            var file_data = $(".input-add-media").prop("files")[0]; // Getting the properties of file from file field
                            var form_data = new FormData(); // Creating object of FormData class
                            form_data.append("pictures", file_data);

                            var local_url = (window.URL ? URL : webkitURL).createObjectURL(file_data);

                            $(".form-add-media").before(file_data.name);

                            $(".form-add-media").parent(".card-body").before('<img class="card-img-top" src="' + local_url + '" alt="Card image cap">');

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
                                                '                                    <span class="btn btn-secondary choice_img_bbcode" data-mediaid="' + data.id + '" data-mediaslug="' + App.GetBaseUrl() + '/' + data.slug + '" style="margin-left: auto;margin-right: auto;">Choisir</span>\n' +
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

                    $(".choice_img_bbcode").click(function() {
                        that.insertTag('[img id="' + $(this).data("mediaid") + '" width="0" height="0"]', "", "image", textarea);
                    });
                });

                $(".modal-media_bbcode").on('hidden.bs.modal', function(e) {
                    $(".modal-media_bbcode").remove();
                });

            },
            error: function(data) {
                console.log(data.responseText);
            }
        });
    };

    that.color = function(textarea) {

        $("#mycp").spectrum({
            showInput: true,
            allowEmpty: true,
            color: "#f00000",
            change: function(color) {
                console.log(color.toHexString());
                that.insertTag("[color=\"" + color.toHexString() + "\"]", "[/color]", "color", textarea);
            }
        });

    };

    that.init = function() {

        var selector = $(".form-redac").find("button");
        var textareaId = $(".form-redac").find("textarea");

        that.color(textareaId);

        selector.click(function() {
            var type = $(this).data("type");
            switch (type) {
                case "bold":
                    that.insertTag("[b]", "[/b]", "bold", textareaId);
                    break;
                case "italic":
                    that.insertTag("[i]", "[/i]", "italic", textareaId);
                    break;
                case "del":
                    that.insertTag("[del]", "[/del]", "del", textareaId);
                    break;
                case "underline":
                    that.insertTag("[u]", "[/u]", "underligne", textareaId);
                    break;
                case "quote":
                    that.insertTag("[quote]", "[/quote]", "underligne", textareaId);
                    break;
                case "header":
                    var header_size = $(this).data("header");
                    that.insertTag("[header=\"" + header_size + "\"]", "[/header]", "header", textareaId);
                    break;
                case "align":
                    var align_style = $(this).data("align");
                    that.insertTag("[align=\"" + align_style + "\"]", "[/align]", "align", textareaId);
                    break;
                case "link":
                    that.insertTag("[link=\"\"]", "[/link]", "link", textareaId);
                    break;
                case "code":
                    var code_lang = $(this).data("code");
                    that.insertTag("[code=\"" + code_lang + "\"]", "[/code]", "code", textareaId);
                    break;
                case "media":
                    that.media(textareaId);
                    break;
                case "source":
                    that.insertTag("[source]", "[/source]", "source", textareaId);
                    break;
            }
        });
    };

    return that;

})();
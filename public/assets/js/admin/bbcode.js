var BBCode = (function(){

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

    that.media = function() {

    };

    that.color = function(textarea) {

        $("#mycp").spectrum({
            showInput: true,
            allowEmpty: true,
            color: "#f00",
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
            switch(type) {
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
                case "header":
                    var header_size = $(this).data("header");
                    that.insertTag("[header=\"" + header_size + "\"]", "[/header]", "header", textareaId);
                    break;
                case "align":
                    var align_style = $(this).data("align");
                    that.insertTag("[align=\"" + align_style + "\"]", "[/align]", "align", textareaId);
                    break;
                case "link":
                    that.insertTag("[link]", "[/link]", "link", textareaId);
                    break;
                case "code":
                    var code_lang = $(this).data("code");
                    that.insertTag("[code=\"" + code_lang + "\"]", "[/code]", "code", textareaId);
                    break;
                case "media":
                    break;
                case "source":
                    that.insertTag("[source]", "[/source]", "source", textareaId);
                    break;
            }
        });
    };

    return that;

})();
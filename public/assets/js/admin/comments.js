var Comments = (function(){

    var that = {};

    that.ValideComments = function(get) {
        var parent_li = get.parent("li");
        var comments_id = parent_li.data("comments");
        $.ajax({
            beforeSend: function (xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                    xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                }
            },
            method: "POST",
            url: App.GetBaseUrl() + "admin/ajax/comments/valide",
            data: {'id': comments_id},
            dataType: 'json',
            cache: false,
            success: function (data) {
                if(data.code = 1) {
                    App.NotifToast("success", data.title, data.message);
                    parent_li.hide("slow");
                }
                else {
                    App.NotifToast("error", "Erreur", data.message);
                }
            },
            error: function (data) {
                console.log(data.responseText);
            }
        });
    };

    that.RefuseComments = function(get) {
        var parent_li = get.parent("li");
        var comments_id = parent_li.data("comments");
        $.ajax({
            beforeSend: function (xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                    xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                }
            },
            method: "POST",
            url: App.GetBaseUrl() + "admin/ajax/comments/refuse",
            data: {'id': comments_id},
            dataType: 'json',
            cache: false,
            success: function (data) {
                if(data.code = 1) {
                    App.NotifToast("success", data.title, data.message);
                    parent_li.hide("slow");
                }
                else {
                    App.NotifToast("error", "Erreur", data.message);
                }
            },
            error: function (data) {
                console.log(data.responseText);
            }
        });
    };

    that.init = function() {
        $(".comments-valide").click(function() {
            that.ValideComments($(this));
        });
        $(".comments-refuse").click(function() {
            that.RefuseComments($(this));
        });
    };

    return that;

})();

$(document).ready(function () {
    Comments.init();
});
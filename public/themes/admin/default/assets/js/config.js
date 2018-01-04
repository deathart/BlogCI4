var Config = (function() {

    var that = {};

    that.SaveParams = function(get) {

        var parent_class = get.parents(".justify-content-sm-center");
        var params_id = parent_class.data("configid");
        var input_key = parent_class.children().find("#params_key").val();
        var input_data = parent_class.children().find("#params_data").val();

        $.ajax({
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                    xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                }
            },
            method: "POST",
            url: App.GetBaseUrl() + "admin/ajax/config/updateparams",
            data: {
                'id': params_id,
                'key': input_key,
                'data': input_data
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

    };

    that.DelParams = function(get) {

        var parent_class = get.parents(".justify-content-sm-center");
        var params_id = parent_class.data("configid");

        $.ajax({
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                    xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                }
            },
            method: "POST",
            url: App.GetBaseUrl() + "admin/ajax/config/delparams",
            data: {
                'id': params_id
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

    };

    that.init = function() {

        $(".save-config").click(function() {
            that.SaveParams($(this));
        });

        $(".del-config").click(function() {
            that.DelParams($(this));
        });

    };

    return that;

})();

$(document).ready(function() {
    Config.init();
});
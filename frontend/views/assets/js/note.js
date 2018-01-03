window.yii.note = (function ($) {
    var pub = {
        // whether this module is currently active. If false, init() will not be called for this module
        // it will also not be called for all its child modules. If this property is undefined, it means true.
        isActive: true,
        init: function () {
            console.info('init note.');

            $(document).on('click', '[data-target="note-collect"]', function (e) {
                $(this).button('loading');
                var collect_btn = $(this);
                var model_id = $(this).data('model_id');
                var show_num = $(this).data('show_num');
                $.post("/note/note/collection", {model_id: model_id}, function (result) {
                    collect_btn.removeClass('disabled');
                    collect_btn.removeAttr('disabled');
                    if (result.status === 'collected') {
                        collect_btn.html('已收藏');
                        collect_btn.addClass('active');
                    } else {
                        collect_btn.html('收藏');
                        collect_btn.removeClass('active');
                    }

                    /*是否操作收藏数*/
                    if (Boolean(show_num)) {
                        var collect_num = collect_btn.nextAll("#collection-num").html();
                        if (result.status === 'collected') {
                            collect_btn.nextAll("#collection-num").html(parseInt(collect_num) + 1);
                        } else {
                            collect_btn.nextAll("#collection-num").html(parseInt(collect_num) - 1);
                        }
                    }
                });
            });
        }
    };
    return pub;
})(window.jQuery);
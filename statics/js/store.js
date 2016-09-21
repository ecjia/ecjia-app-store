// JavaScript Document
;(function (app, $) {
    app.store_list = {
        init: function () {
            //搜索功能
            $("form[name='searchForm'] .search_store").on('click', function (e) {
                e.preventDefault();
                var url = $("form[name='searchForm']").attr('action') + '&keywords=' + $("input[name='keywords']").val();
                ecjia.pjax(url);
            });
        }
    };
 
    app.store_edit = {
        init: function () {
            $("form[name='theForm']").validate({
                onkeyup: false,
                errorPlacement: function (error, element) {
                    error.appendTo(element.closest("div.controls"));
                },
                highlight: function (element) {
                    $(element).closest("div.control-group").addClass("error f_error");
                    var thisStep = $(element).closest('form').prev('ul').find('.current-step');
                    thisStep.addClass('error-image');
                },
                unhighlight: function (element) {
                    $(element).closest("div.control-group").removeClass("error f_error");
                    if (!$(element).closest('form').find('div.error').length) {
                        var thisStep = $(element).closest('form').prev('ul').find('.current-step');
                        thisStep.removeClass('error-image');
                    };
                },
                submitHandler: function () {
                    $("form[name='theForm']").ajaxSubmit({
                        dataType: "json",
                        success: function (data) {
                            ecjia.admin.showmessage(data);
                        }
                    });
                }
            });
 
            $('#info-toggle-button').toggleButtons({
                style: {
                    enabled: "info",
                    disabled: "success"
                }
            });
        }
    }
})(ecjia.admin, jQuery);
 
// end
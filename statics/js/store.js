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
    		var $form = $("form[name='theForm']");
			var option = {
					submitHandler : function() {
						$form.ajaxSubmit({
							dataType : "json",
							success : function(data) {
								ecjia.admin.showmessage(data);
							}
						});
					}
				}
			var options = $.extend(ecjia.admin.defaultOptions.validate, option);
			$form.validate(options);
        }
    }
})(ecjia.admin, jQuery);
 
// end
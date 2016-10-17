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
        	app.store_edit.get_longitude();
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
        },
        get_longitude: function() {
        	$('.longitude').on('click', function(e) {
    			e.preventDefault();
    			var address		    = $("input[name='detail_address']").val(); //详细地址
    			var url				= $(".longitude").attr('data-url'); //请求链接
    			if(address	    	== 'undefined')address ='';
    			if(url        		== 'undefined')url ='';
    			var filters = {
						'detail_address': address,
				};
    			$.post(url, filters, function(data) {
					var longitude = data.content.longitude;
					var latitude  = data.content.latitude;
					var geohash   = data.content.geohash;
					$('.long').append(longitude);
					$('.latd').append(latitude);
					$('.geo').append(geohash);
				}, "JSON");
    		});
        },
    };
    app.store_lock = {
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
        },
    };
})(ecjia.admin, jQuery);
 
// end
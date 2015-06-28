/*
 * panorama360 - jQuery plugin made by Liviu Holhos
 * Copyright (c) 2011 Minimalistic Studio (http://minimalisticstudio.com/)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 */

(function($) {
	$.fn.panorama360 = function(options){
		this.each(function(){
			var settings = {
				start_position: 0, // initial start position for the view
				image_width: 0,
				image_height: 0,
				mouse_wheel_multiplier: 20,
				bind_resize: true, // determine if window resize affects panorama viewport
				is360: true, // glue left and right and make it scrollable
				right_click: false 
			};
			var css;
			function w_size(ret_value)
			{
					var index_arr,size_h;
					var size=$(window).width();
					var size_h;
					if (size<='1100') { size_h=458;}
					if ((size>'1100')&&(size<='1285')) {size_h=550;}
					if ((size>'1285')&&(size<='1371')) {size_h=588;}
					if (size>'1371') {size_h=666;}
					
					size=$(window).width();
					if (size<='1100') { css='1000';index_arr=0; size_h=458;}
					else
					if ((size>'1100')&&(size<='1285')) {css='1240'; index_arr=1; size_h=550;}
					else
					if ((size>'1285')&&(size<='1371')) {css='1320'; index_arr=2; size_h=588;}
					else
					if (size>'1371') {css='1400'; index_arr=3; size_h=666;}
					
					if(ret_value=='size') return css;
					else if(ret_value=='size_h') return size_h;
					else return index_arr;
			}
			if(options) $.extend(settings, options);
			var viewport = $(this);
			var panoramaContainer = $('.panorama-container',viewport);
			var viewportImage = $('img:first-child',panoramaContainer);
			if(viewportImage.data("360")=='0') {  settings.is360=false; }
			if(settings.image_width<=0 && settings.image_height<=0){
				settings.image_width = viewportImage.data("width").split('~')[w_size()];
				settings.image_height = viewportImage.data("height").split('~')[w_size()];
				if (!(settings.image_width) || !(settings.image_height)) return;
			}
			var image_ratio = settings.image_height/settings.image_width;
			if(!settings.is360) {settings.start_position=settings.image_width/2;}
			else { settings.start_position=settings.image_width; }
			var elem_height =  w_size('size_h');
			var elem_width = parseInt(elem_height/image_ratio);
			var image_map = viewportImage.attr('usemap');
			var image_areas;
			var isDragged = false;
			var mouseXprev = 0;
			var scrollDelta = 0;
			if (settings.is360) viewportImage.height(elem_height).removeAttr("usemap").css("left",0).clone().css("left",elem_width+"px").insertAfter(viewportImage);
			var time = Math.round(settings.image_width/150*1000);
			if (settings.is360)
			{
				panoramaContainer.css({
				'margin-left': '0px',
				'width': (elem_width*2)+'px',
				'height': (elem_height)+'px'
				}).animate({'margin-left': '-'+settings.start_position+'px'}, time,  function(){
				});
			}
			else
			{
				panoramaContainer.css({
				'margin-left': '0px',
				'width': (elem_width)+'px',
				'height': (elem_height)+'px'
				}).animate({'margin-left': '-'+settings.start_position+'px'}, time,  function(){
				});
			}
			var timeout;
			function animate_arrows(stop,delta_)
			{
				if(stop!=0)
				{
					timeout=setInterval( function() {
					scrollView(panoramaContainer, elem_width, delta_, settings);
					}, 1);
				}
				else
				{
					clearTimeout(timeout);
					return false;
				}
			}
		    /*setInterval( function() {
				if (isDragged) return false;
				scrollDelta = scrollDelta * 0.98;
				if (Math.abs(scrollDelta)<=2) scrollDelta = 0;
				scrollView(panoramaContainer, elem_width, scrollDelta, settings);
			}, 1);*/
			viewport.parents('.pan').find('.gal_arrow_right').bind('contextmenu',stopEvent).mousedown(function()
			{
				if(!settings.right_click)
				{
					animate_arrows(1,-2)
				}
			}).mouseup(function()
			{
				settings.right_click=false;
				panoramaContainer.stop(0,0)
				animate_arrows(0,2)
			}).mouseleave(function(e){
				clearTimeout(timeout);
			}).bind('touchstart',function()
			{
				if(!settings.right_click)
				{
					animate_arrows(1,-2)
				}
			}).bind('touchend',function()
			{
				settings.right_click=false;
				panoramaContainer.stop(0,0)
				animate_arrows(0,2)
			}).bind('touchmove',function(e){
				clearTimeout(timeout);
			})
			
			
			
			viewport.parents('.pan').find('.gal_arrow_left').bind('contextmenu',stopEvent).mousedown(function()
			{
				
				if(!settings.right_click)
				{
					animate_arrows(1,2)
				}
			}).mouseup(function()
			{
				right_click=false;
				panoramaContainer.stop(0,0)
				animate_arrows(0,10)
			}).mouseleave(function(e){
				clearTimeout(timeout);
			}).bind('touchstart',function()
			{
				
				if(!settings.right_click)
				{
					animate_arrows(1,2)
				}
			}).bind('touchend',function()
			{
				right_click=false;
				panoramaContainer.stop(0,0)
				animate_arrows(0,10)
			}).bind('touchmove',function(e){
				clearTimeout(timeout);
			});
			viewport.unbind('mousedown mouseup mousemove mouseout mousewheel contextmenu touchstart touchmove touchend');
			viewport.mousedown(function(e){
				panoramaContainer.stop(0,0);
				if (!isDragged) 
				{
					$(this).addClass("grab");
					isDragged = true;
					mouseXprev = e.clientX;
					scrollOffset = 0;
				}
				return false;
			}).mouseup(function(){
				isDragged = false;
				$(this).removeClass("grab");
				scrollDelta = scrollDelta * 0.45;
				return false;
			}).mousemove(function(e){
				if (isDragged)
				{
					scrollDelta = parseInt((e.clientX - mouseXprev));
					mouseXprev = e.clientX;
					scrollView(panoramaContainer, elem_width, scrollDelta,settings);
				}
				return false;
			}).mouseleave(function(e){
				isDragged = false;
				return false;
			}).bind("mousewheel",function(e,distance){
				panoramaContainer.stop(0,0)
				clearTimeout(timeout);
				var delta=Math.ceil(Math.sqrt(Math.abs(distance)));
				delta=distance<0 ? -delta : delta;
				scrollDelta = scrollDelta + delta * 5;
				scrollView(panoramaContainer,elem_width,delta*settings.mouse_wheel_multiplier,settings);
				return false;
			}).bind('contextmenu',stopEvent).bind('touchstart', function(e){
				if (!isDragged)
				{
					isDragged = true;
					mouseXprev = e.originalEvent.touches[0].pageX;
					scrollOffset = 0;
				}
				return false;
			}).bind('touchmove', function(e){
				e.preventDefault();
				panoramaContainer.stop(0,0)
				clearTimeout(timeout);
				
				if (isDragged)
				{
					var touch_x = e.originalEvent.touches[0].pageX;
					scrollDelta = parseInt((touch_x - mouseXprev));
					mouseXprev = touch_x;
					
					scrollView(panoramaContainer, elem_width, scrollDelta,settings);
				}
				return false;
			}).bind('touchend', function(e){
				e.preventDefault();
				panoramaContainer.stop(0,0)
				clearTimeout(timeout);
				isDragged = false;
				scrollDelta = scrollDelta * 0.45;
				return false;
			});
			if (image_map) {
				new_area = $("a").addClass("area");
				$('map[name='+image_map+']').children('area').each(function(){
					switch ($(this).attr("shape").toLowerCase()){
						case 'rect':
							var area_coord = $(this).attr("coords").split(",");
							var new_area = $(document.createElement('a')).addClass("area").attr("href",$(this).attr("href")).attr("title",$(this).attr("alt"));
							new_area.addClass($(this).attr("class"));
							panoramaContainer.append(new_area.data("stitch",1).data("coords",area_coord));
							panoramaContainer.append(new_area.clone().data("stitch",2).data("coords",area_coord));
							break;
					}
				});
				$('map[name='+image_map+']').remove();
				image_areas = panoramaContainer.children(".area");
				image_areas.mouseup(stopEvent).mousemove(stopEvent).mousedown(stopEvent);
				repositionHotspots(image_areas,settings.image_height,elem_height,elem_width);
			}

			if (settings.bind_resize){
				$(window).resize(function(){
					elem_height = parseInt(viewport.height());
					elem_width = parseInt(elem_height/image_ratio);
					panoramaContainer.css({
						'width': (elem_width*2)+'px',
						'height': (elem_height)+'px'
					});
					viewportImage.css("left",0).next().css("left",elem_width+"px");
					if (image_map) repositionHotspots(image_areas,settings.image_height,elem_height,elem_width);
				});
			}

			if (settings.loaded && $.isFunction(loaded)) {
				settings.loaded();
			}

			if (settings.callback && $.isFunction(settings.callback)) {
				var img = 0;
				$('.panorama-container img').load(function(e){
					img += 1;
					if (img == 2) settings.callback();
				});
			}
		});

		function stopEvent(e){
			e.preventDefault();
			return false;
		}
		function scrollView(panoramaContainer,elem_width,delta,settings){
			var newMarginLeft = parseInt(panoramaContainer.css('marginLeft'))+delta;
			if(settings.is360){
				if (newMarginLeft > 0) {  newMarginLeft = -elem_width;}
				else
				if (newMarginLeft < -elem_width) {newMarginLeft = 0; }
			}
			else{
				var right = -(elem_width - panoramaContainer.parent().width());
				if (newMarginLeft > 0) newMarginLeft = 0;
				if (newMarginLeft < right) newMarginLeft = right;
			}
			panoramaContainer.css('marginLeft', newMarginLeft+'px');
		}

		function repositionHotspots(areas,image_height,elem_height,elem_width){
			var percent = elem_height/image_height;
			areas.each(function(){
				area_coord = $(this).data("coords");
				stitch = $(this).data("stitch");
				switch (stitch){
					case 1:
						$(this).css({
							'left':		(area_coord[0]*percent)+"px",
							'top':		(area_coord[1]*percent)+"px",
							'width':	((area_coord[2]-area_coord[0])*percent)+"px",
							'height':	((area_coord[3]-area_coord[1])*percent)+"px"
						});
						break;
					case 2:
						$(this).css({
							'left':		(elem_width+parseInt(area_coord[0])*percent)+"px",
							'top':		(area_coord[1]*percent)+"px",
							'width':	((area_coord[2]-area_coord[0])*percent)+"px",
							'height':	((area_coord[3]-area_coord[1])*percent)+"px"
						});
						break;
				}
			});
		}
	}
})(jQuery);
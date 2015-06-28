var alt_;  
var pictures=[],
    galerySize,     // переменная, в которой будет храниться размер галереи 
    current,        // текущее изображение (его порядок в массиве)
    nextImg,        // следующее изображение
    prevImg,        // предыдущее изображение 
    isAnimated = false;     // флаг - анимируется ли что-то в данный момент 
    var css,photo_menu,site;
    var src_;
 var m_anim_left;
 var floor;
 var Viewer2 = {
   open: function()
   {
   		pictures=[]; //обнуляем  
	    var size=$(window).width();
		if (size<='1100') { css='1000';}
		else
		if ((size>'1285')&&(size<='1371')) {css='1320';}
		else
		if (size>'1371') {css='1400';}	
		$('.galery').removeClass('display_none');
		var galery = $("#for-map"); 
		galery.empty();
		if(floor=='no')
		{
			$('.gal_arrow_left,.gal_arrow_right').addClass('display_none');
			src_='../img/photo_menu/'+css+'/'+photo_menu+'.jpg';
			var mainImg = $("<img />",{id : "mainImg",src : src_, alt : "galery"});
        	galery.append('<img src="'+src_+'"/>');
        	$('#count').replaceWith('<div id="count">1 / 1</div>');
			return false;
		}
		else
		{
			$('.galery .esc_gal, .galery #count, .galery .gal_arrow').removeClass('display_none')
			 $.ajax({
	        url: "../lib/img.php",
	        type: "post",
	        async: false,
	        data:{id:floor,folder_site:site},
	        success: function(data2){
	        	
	            eval(data2);    
	        }    
	    });
		}
	    if(pictures.length==1) {
	    	$('.gal_arrow_left,.gal_arrow_right').addClass('display_none');
	    }
	    else
	   	{
	   		$('.gal_arrow_left,.gal_arrow_right').removeClass('display_none');
	   	}
	    current=0;  
	    galerySize = pictures.length;
		if(floor=="-1")
		{
			src_ = pictures[current].replace('1000',css);
			
		}
	    else
	   	{
	   		src_ = '../img/'+site+'/photos/'+floor+'/'+css+'/'+pictures[current];
		    var preloadImgFirst = new Image();
		    preloadImgFirst.src = src_;
	   	}
		
        var mainImg = $("<img />",{id : "mainImg",src : src_, alt : "galery"});
        galery.append('<img src="'+src_+'"/>');
        $('#count').replaceWith('<div id="count">'+(current+1)+' / '+galerySize+'</div>');
        Viewer2.preload(current);
     },
      next: function()
     {
       if(pictures.length==1) return false;

       if (!isAnimated){
	     var size=$(window).width();

		if (size<='1100') { css='1000';m_anim_left=687;}

		else

		if ((size>'1100')&&(size<='1285')) {css='1240';m_anim_left=825;}

		else

		if ((size>'1285')&&(size<='1371')) {css='1320';m_anim_left=882;}

		else

		if (size>'1371') {css='1400';m_anim_left=999;}	

        isAnimated = true;

        // добавляем фото

        var tempImg = $("<img />",{id : "tempImg", alt: "temp", src: nextImg});

        tempImg.css({marginLeft: "-"+m_anim_left+"px"}); 

        	//$("#for-map").append(tempImg);

        // движение
        /*$("#mainImg").animate({marginLeft: m_anim_left}, "normal");
        $("#tempImg").animate({marginLeft: 0}, "normal",function(){
            // возвращаем все, как было
            //$("#mainImg").attr("src",nextImg);
            $("#mainImg").remove();
            $("#tempImg").attr("id","mainImg");
            current++;
            if (current==galerySize) current = 0;
            Viewer2.preload(current);
            isAnimated = false;	
            $('#count').replaceWith('<div id="count" >'+(current+1)+' / '+galerySize+'</div>');
            return false;
        });  */  
         	$('#for-map img').fadeOut("normal",function(){
					$(this).attr('src',nextImg).fadeIn("normal");
		            current++;
		            if (current==galerySize) current = 0;
		              Viewer2.preload(current);
		              isAnimated = false;	
					  $('#count').replaceWith('<div id="count" >'+(current+1)+' / '+galerySize+'</div>');
					
				});
				return false;
      }
    },
      prev: function() 
      	{
      	if(pictures.length==1) return false;
       	if (!isAnimated){
	    var size=$(window).width();
		if (size<='1100') { css='1000';m_anim_left=687;}
		else
		if ((size>'1100')&&(size<='1285')) {css='1240';m_anim_left=825;}
		else
		if ((size>'1285')&&(size<='1371')) {css='1320';m_anim_left=882;}
		else
		if (size>'1371') {css='1400';m_anim_left=999;}	
        isAnimated = true;
        // добавляем фото
        var tempImg = $("<img />",{id : "tempImg", alt: "temp", src: prevImg});
       // tempImg.css({ marginLeft: m_anim_left+"px"}); 
       	//$("#for-map").append(tempImg);
       	$('#for-map img').fadeOut("normal",function(){
					$(this).attr('src',prevImg).fadeIn("normal");
					 current--;
		            if (current<0) current = galerySize-1;
		           Viewer2.preload(current);
					isAnimated=false;
					 $('#count').replaceWith('<div id="count">'+(current+1)+' / '+galerySize+'</div>'); 
				});
				return false;
        // движение
        /*$("#mainImg").animate({marginLeft: '-'+m_anim_left}, "normal");
        $("#tempImg").animate({marginLeft: 0}, "normal",function(){
        $("#mainImg").remove();
        $("#tempImg").attr("id","mainImg");
            current--;
            if (current<0) current = galerySize-1;
            Viewer2.preload(current);
            isAnimated = false;
            $('#count').replaceWith('<div id="count">'+(current+1)+' / '+galerySize+'</div>');
            return false;
        });  */
    }
      },
      close: function(){
        $(".galery").addClass('display_none');
        $('.gal_arrow_left,.gal_arrow_right').removeClass('display_none');
        $("#for-map").empty();
        return false;
      },
      bindKeys: function(e){
        switch (e.keyCode){
          case 27: Viewer2.close(); break;
        }
      },
     preload: function(i){
 	 var css;
     var size=$(window).width();
	 if (size<='1100') { css='1000';}
	 else
	 if ((size>'1100')&&(size<='1285')) {css='1240';}
	else
	if ((size>'1285')&&(size<='1371')) {css='1320';}
	else
	if (size>'1371') {css='1400';}	
    var next = i+1;
    if (next == galerySize) next = 0;
    var prev = i-1;
    if (prev < 0) prev = galerySize-1;
    if(floor=="-1")
    {
		nextImg =pictures[next].replace('1000',css);
	    prevImg = pictures[prev].replace('1000',css);
    }
    else
    {
    	 nextImg = "../img/"+site+"/photos/"+floor+"/"+css+"/"+ pictures[next];
		 prevImg = "../img/"+site+"/photos/"+floor+"/"+css+"/" + pictures[prev];
    }
    var preloadImgNext = new Image();
    preloadImgNext.src = nextImg;
    var preloadImgPrev = new Image();
    preloadImgPrev.src = prevImg;
}
 }
	$('.gal_arrow_right').live('click touchend',function() { if(pictures.length>1) Viewer2.next(); })
    $('.gal_arrow_left').live('click touchend',function(){ if(pictures.length>1) Viewer2.prev(); })
    $(document).keyup(function(e){
      if (e.keyCode==27) { $(" .galery_back").trigger("click"); Viewer2.close()}
      if (e.keyCode==39) $(".gal_arrow_right").trigger("click");
      if (e.keyCode==37) $(".gal_arrow_left").trigger("click");
    });
	$('.photoapp, .dashed, .photoapp_zero,.photoapp_photo_bludo').live('click touchend',function()
	{
		var this_attr=$(this).attr('alt');
		var this_attr_data_id=$(this).attr('data-id');
		if(this_attr!=undefined)
		{
			floor=$(this).attr('alt');
			site=this_attr_data_id;
		}
		else
		{
			floor='no';
			photo_menu=this_attr_data_id;
			site=null;
		}
			Viewer2.open();
	}).live('mouseover',function()
	{
		if($(this).attr('alt')!=0)
		{
			$(' .shema_title_text',$(this).parent()).addClass('shema_title_text_on_hover_photo');
		}
	}).live('mouseout',function()
	{
		if($(this).attr('alt')!=0)
		{
			$('.shema_title_text',$(this).parent()).removeClass('shema_title_text_on_hover_photo');
		}
	})
	$('.galery_back,.close_photos_gal').live('click touchend',function(){ Viewer2.close(); })


(function($) {
	$.fn.gal = function(options){
	   	    var istouch;
            var total=0,totaldeliv=0
            if((!!('ontouchstart' in window)))
            {
                istouch='touchstart';
            }
            else
            {
                istouch='click';
            }
    		if(options) $.extend(settings, options);
            var folder,type="png",img,img_delim="~",ajax=false,prefolder;
            var next,prev;
            var current=0;
            var length=0;
            var fungal={
                ajaxs:function(data,f)
                {
                     $.ajax({
                        type: "POST",
            			url: "../lib/img.php",
            			data: data,
            			success: f
                		});
                },
                fsizegalch:function()
                {
                    var size =('innerWidth' in window) ? window.innerWidth : document.body.offsetWidth;
                    if (size<=1200) {css='sgal1';prefolder="fsize1";}
                    else
                	if (size>1200 && size<1600) {css='sgal2';prefolder="fsize2";}
                    else {css='sgal3';prefolder="fsize3";}
                   	document.getElementById('galsize').setAttribute('href', 'css/'+css+'.css');
                    return prefolder;
                }
                }
            fungal.fsizegalch();
            
            
 $(this).on(istouch,function()
            {
			    
                db=$(this).data('db');
                folder=$(this).data('folder')
                //console.log(folder)
                type=(typeof($(this).data('type'))!=="undefined")?$(this).data('type'):type;
                current=(typeof($(this).data('current'))!=="undefined")?$(this).data('current'):type;
                
                ajax=(typeof($(this).data('ajax'))!=="undefined")?$(this).data('ajax'):ajax
                if(ajax) {
                    fungal.ajaxs({dir:folder,db:db},function(data)
                    {
                        img=data
                        length=img.length;
                        if(current>length-1) current=length-1
                        $('.preloader').fadeIn(500)
                        $('#for-map').append('<img style="opacity:0; " src="'+folder+fungal.fsizegalch()+'/'+img+'"/>');
                        //console.log(folder+fungal.fsizegalch()+'/'+img)
                        $('#for-map img').load(function()
                            {
                                $('#for-map img').animate({opacity:1},500)
                                $('.preloader').fadeOut(500)
                            })
                        $('#count').text((current+1)+' / '+length)
                      
                    })
                    
                }
                else
                {
                    img_delim=(typeof($(this).data('img-delim'))!=="undefined")?$(this).data('img-delim'):img_delim
                    img=$(this).data('img');
                    if(img.indexOf(img_delim)==-1)
                    {
                        img=[$(this).data('img')];
                    }
                    else
                    {
                        img=img.split(img_delim);
                    }
                     
                     length=img.length
                     current=length-1
                    $('.preloader').fadeIn(500)
                    $('#for-map').append('<img style="opacity:0; " src="'+folder+fungal.fsizegalch()+'/'+img[current]+'"/>');
                    $('#for-map img').load(function()
                            {
                                $('#for-map img').animate({opacity:1},500)
                                $('.preloader').fadeOut(500)
                            })
                    $('#count').text(current+1+' / '+length)
                    if(length<=1)
                        {
                            $('.gal_arrow').addClass('display_none')
                        }
                        else
                        {
                            $('.gal_arrow').removeClass('display_none')
                        }
                }
                $('.gallery').removeClass('display_none');
                
            })  
        
            $('.galery_back').on(istouch,function(){
                $('.gallery').addClass('display_none')
                $('#for-map img,#for-map div').remove();
                $('#count').text('')
                $('.gal_arrow').removeClass('display_none')
            })
            $('.close_photos_gal').on(istouch,function()
            {
                $('.gallery').addClass('display_none')
                $('#for-map img,#for-map div').remove();
                
                $('#count').text('')
                $('.gal_arrow').removeClass('display_none')
            })
            $(window).resize(function()
            {
                $('.galery_back').trigger(istouch);
                fungal.fsizegalch();
            })
            $(document).keydown(function(e)
            {
                if(e.keyCode==27)
                {
                    $('.galery_back').trigger(istouch)
                }
            
                
            })
	  
	}
}
)(jQuery);
$(function()
{
	$('.gal').gal();
})

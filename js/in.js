/*Created by Sergey Brilenko*/
(function( $ ){
	$.fn.ini = function(options){
       var istouch,ishover, arrowis;
       if((!!('ontouchstart' in window)))
       {
            istouch='touchend';
            ishover='focusout';
            arrowis='touchstart'
       }
       else
       {
            istouch='click';
            ishover='focusout';
            arrowis='click'
       }
		var ini={
		    ok:function(th)
            {
                $('input[type="submit"]',th.parent()).remove()
                $("<input type='submit' class='okinput' value='Ок'/>").insertBefore(th)
            },
            okother:function(th)
            {
                $('input[type="submit"]',th.parent()).remove()
                $("<input type='submit' class='okinputother' value='Ок'/>").insertAfter(th)
            },
		    init:function($this)
            {
            },
            ajaxsender:function(ajaxdata,f_)
            {
                $.ajax({
    			url: "/ajax.php",
    			type: "post",
                dataType: 'json',
                data:ajaxdata,
    			async: false,
    			success:f_
		      })
            }
		}
        
        this.on(istouch,function(){ }).attr('value','').mask("+38 (999) 999 99 99",{completed:function()
                 {
                    if($(this).parent().parent().parent().hasClass('oforml-block'))
                    {
                        if($(this).val().indexOf('_')==-1) ini.okother($(this))
                    }
                    else
                    {
                        if($(this).val().indexOf('_')==-1) ini.ok($(this))
                    }
                 }});
        this.on('keyup',function()
        {
            if($(this).val().indexOf('_')!=-1)
            {
                $('.saminput').removeClass('error')
                $('.after-disc-card-text-before-err').remove()
                if($(this).parent().parent().parent().hasClass('oforml-block'))
                    {
                        $('.okinputother').remove()
                    }
                    else
                    {
                        $('.okinput').remove() 
                    }
                    
            }
        })
        this.on('keydown',function(e)
        {
            if(e.keyCode==13)
            {
                $('.saminput').removeClass('error')
                $('.after-disc-card-text-before-err').remove()
                //$('.okinput').remove()   
                if($(this).parent().parent().parent().hasClass('oforml-block'))
                    {
                        if($('.oforml-block .okinputother').length!=0)
                            {
                                $('.okinputother').trigger(arrowis)
                            }
                    }
                    else
                    {
                        if($('.okinput').length!=0)
                        {
                            $('.okinput').trigger(arrowis)
                        }
                    } 
                 e.stopPropagation()
                 e.preventDefault() 
                 return false
            }
        })
        this.on('paste',function()
        {
            $('.saminput').removeClass('error')
            $('.after-disc-card-text-before-err').remove()
            if($(this).parent().parent().parent().hasClass('oforml-block'))
                {
                    $('.okinputother').remove()
                }
                else
                {
                    $('.okinput').remove()
                }
            var t=$(this)
            setTimeout(function() {
                if(t.parent().parent().parent().hasClass('oforml-block'))
                {
                    (t.val().indexOf('_')!=-1)?$('.oforml-block input[type="submit"]').remove():ini.okother(t)
                }
                else
                {
                    (t.val().indexOf('_')!=-1)?$('input[type="submit"]',t.parent()).remove():ini.ok(t)
                }
                
            },100)
            
            
        })
        $(document).on(arrowis,'.okinput',function()
        {
            var th=$(this);
            var okinput=
            {
                url:'/in.php',
                beforeSubmit: function(jqForm) 
                {
                    var ob=new Object(); ob.name='l';ob.value=window.location.pathname;jqForm.push(ob);
                },
                success: function(responseText) 
                { 
                    responseText=$.parseJSON(responseText);
                    if(responseText.err)
                    {
                        $('.saminput').addClass('error')
                        $('.after-disc-card-text-before-err').remove()
                        $("<div class='after-disc-card-text-before-err'>"+responseText.err+"</div>").insertBefore($('.discount-cart').next().next())
                    }
                    else
                    {
                        location.href=responseText.lo
                    }
                }
            };
            th.parent().ajaxSubmit(okinput); 
            return false; 
        })
        $(document).on(arrowis,'.okinputother',function()
        {
            var th=$(this);
            var okinput=
            {
                url:'/in.php',
                beforeSubmit: function(jqForm) 
                {
                    var ob=new Object(); ob.name='l';ob.value=window.location.pathname;jqForm.push(ob);
                    ob=new Object(); ob.name='phone';ob.value=$('input[name=phone]',th.parent().parent()).val() ;jqForm.push(ob);
                    ob=new Object(); ob.name='fio';ob.value=$('input[name=fio]',th.parent().parent()).val() ;jqForm.push(ob);
                    ob=new Object(); ob.name='adress';ob.value=$('textarea',th.parent().parent()).text() ;jqForm.push(ob);
                },
                success: function(responseText) 
                { 
                    responseText=$.parseJSON(responseText);
                    if(responseText.err)
                    {
                        $('.saminput').addClass('error')
                        $('.after-disc-card-text-before-err').remove()
                        $("<div class='after-disc-card-text-before-err'>"+responseText.err+"</div>").insertAfter(th.parent())
                    }
                    else
                    {
                        location.href=responseText.lo
                    }
                }
            };
            th.parent().ajaxSubmit(okinput); 
            return false; 
        })
        $('.disc').on(istouch,'.dotted',function()
        {
             $('.saminput').removeClass('error')
             $('.after-disc-card-text-before-err').remove()
             $('.okinput').remove()             
            var dotted=$('.dotted',$(this).parent().parent())
            var nodotted=$('.nodotted',$(this).parent())
            dotted.addClass('nodotted').removeClass('dotted')
            nodotted.addClass('dotted').removeClass('nodotted')
            if($('.thiso',$(this).parent().parent()).hasClass('dotted'))
            {
                 
                 $('input[name=in]',$(this).parent().parent()).attr('value','').mask("99 99 99",{completed:function()
                 {
                     if( $('input[name=in]',$(this).parent().parent()).val().indexOf('_')==-1)  ini.ok($(this))
                 }
                 });
            }
            else
            {
               $('input[name=in]',$(this).parent().parent()).attr('value','').mask("+38 (999) 999 99 99",{completed:function()
                 {
                     if($(this).val().indexOf('_')==-1) ini.ok($(this))
                 }});
            }
        })
        $('.oforml-block ').on(istouch,'.dotted',function()
        {
            $('.saminput',$(this).parent().parent()).removeClass('error')
            $('.after-disc-card-text-before-err').remove()
            $('.okinputother').remove()             
            var dotted=$('.dotted',$(this).parent().parent())
            var nodotted=$('.nodotted',$(this).parent().parent())
            dotted.addClass('nodotted').removeClass('dotted')
            nodotted.addClass('dotted').removeClass('nodotted')
            if($('.thiso',$(this).parent().parent()).hasClass('dotted'))
            {
                $('input[name=phone]').parent().removeClass('display_none')
                 
                 $('input[name=in]',$(this).parent().parent()).attr('value','').mask("99 99 99",{completed:function()
                 {
                     if($('input[name=in]',$(this).parent().parent()).val().indexOf('_')==-1) ini.okother($(this))
                 }
                 });
            }
            else
            {
               $('input[name=phone]').parent().addClass('display_none')
               $('input[name=phone]').val('')
               $('input[name=in]',$(this).parent().parent()).val('').mask("+38 (999) 999 99 99",{completed:function()
                 {
                     if($('input[name=in]',$(this).parent().parent()).val().indexOf('_')==-1) ini.okother($(this))
                 }});
            }
        })
        
	}
})(jQuery);
$(function()
{
    $('input[name=in]').ini()
})		
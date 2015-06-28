/*Created by Sergey Brilenko*/
(function( $ ){
	$.fn.user = function(options){
       var active;
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
		var options = $.extend({
		}, options || {});
        
        
		var user={
		    setorother:function()
            {
              return ($('*').hasClass('findIndex'))?".findIndex .prodSet":".ass-all .kkz";  
            },
            promore:function()
            {
                return ($('*').hasClass('this-is-more'))?".this-is-more":user.setorother();  
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
        //if($('#nextuser').data('au')=='none') { active=20} else {active=parseInt($('#nextuser').data('au')-1)}
        this.on(istouch,function(){  user.init($(this))})
        this.on(ishover,function(){
        var name=$('#user').val();
        user.ajaxsender({action:'user',name_:name},function(data)
        {
            //console.log(data);
        });
        })
        $('#nextuser').on(istouch,function()
        {
            var $this=$(this)
            if($this.hasClass('active'))
            {
            if(!$this.hasClass('disabled'))
            {
                $this.addClass('disabled')
                user.ajaxsender({action:'usernext',a:active},function(data)
                {
                    $this.removeClass('disabled')
                    if(parseInt(data['active'])>=19)
                    {
                      $this.removeClass('active')
                    }
                    if(parseInt(data['active'])>0)
                    {
                        $('#prevuser').addClass('active')
                    }
                    $('#user').val(data['name'])
                    $('.zakazkol').html("Товаров: <span class='b'>"+data['kol']+"</span>");
                    $('.zakazsum').html('На сумму: <span class="b">'+data['price']+" грн</span>");
                    $('.zakaztotal').html('Сумма заказа: <span class="b">'+data['total']+" грн</span>")
                    if(data['ids'].length>0)
                    {
                        
                        $(user.promore()).each(function(index)
                        {
                            var id=$('input[name=id]',$(this)).val();
                            for(var i=0;i<data['ids'].length;i++)
                            {
                                if(data['ids'][i]['id']==id)
                                {
                                    $('.val',$(this)).val(data['ids'][i]['k'])
                                    $('.kkz-price',$(this)).text((data['ids'][i]['k']*data['ids'][i]['price']).toFixed(2)+" грн")
                                    $('.buy',$(this)).replaceWith('<a href="/cart" class="in-basket">В корзине</a>')
                                    $('.plus',$(this)).attr('disabled','disabled').attr('readonly')
                                    $('.minus',$(this)).attr('disabled','disabled').attr('readonly')
                                    break;
                                }
                                else
                                {
                                    
                                    for(var j=0;j<data['all'].length;j++)
                                    {
                                       if(data['all'][j]['id']==id)
                                       {
                                        var price=data['all'][j]['price'].toFixed(2)
                                        $('.val',$(this)).val('1')
                                        $('.kkz-price',$(this)).text(price+' грн')
                                        $('.in-basket',$(this)).replaceWith('<span class="buy">Купить</span>')
                                        $('.plus',$(this)).removeAttr('disabled').removeAttr('readonly')
                                        $('.minus',$(this)).removeAttr('disabled').removeAttr('readonly');
                                        break
                                       }
                                        
                                    }
                                    
                                }
                            }
                        })
                    }
                    else
                    {
                        $(user.promore()).each(function(index)
                        {
                             var id=$('input[type=hidden]',$(this)).val()
                             for(var j=0;j<data['all'].length;j++)
                                    {
                                       if(data['all'][j]['id']==id)
                                       {
                                        var price=data['all'][j]['price'].toFixed(2)
                                        $('.val',$(this)).val('1')
                                        $('.kkz-price',$(this)).text(price+' грн')
                                        $('.in-basket',$(this)).replaceWith('<span class="buy">Купить</span>')
                                        $('.plus',$(this)).removeAttr('disabled').removeAttr('readonly')
                                        $('.minus',$(this)).removeAttr('disabled').removeAttr('readonly');
                                        break
                                       }
                                        
                                    }
                        })
                    }
                } );
            }
            }
        })
        $('#prevuser').on(istouch,function()
        {
            var $this=$(this)
            if($this.hasClass('active'))
            {
            if(!$this.hasClass('disabled'))
            {
                $this.addClass('disabled')
                user.ajaxsender({action:'userprev'},function(data)
                {
                    $this.removeClass('disabled')
                    if(parseInt(data['active'])<=0)
                    {
                      $this.removeClass('active')
                    }
                    if(parseInt(data['active'])<20)
                    {
                        $('#nextuser').addClass('active')
                    }
                    $('#user').val(data['name'])
                    $('.zakazkol').html("Товаров: <span class='b'>"+data['kol']+"</span>");
                    $('.zakazsum').html('На сумму: <span class="b">'+data['price']+" грн</span>");
                    $('.zakaztotal').html('Сумма заказа: <span class="b">'+data['total']+" грн</span>")
                    if(data['ids'].length>0)
                    {
                        $(user.promore()).each(function(index)
                        {
                            var id=$('input[name=id]',$(this)).val()
                            for(var i=0;i<data['ids'].length;i++)
                            {
                                if(data['ids'][i]['id']==id)
                                {
                                    $('.val',$(this)).val(data['ids'][i]['k'])
                                    $('.kkz-price',$(this)).text((data['ids'][i]['price']*data['ids'][i]['k']).toFixed(2)+" грн")
                                    $('.buy',$(this)).replaceWith('<a href="/cart" class="in-basket">В корзине</a>') 
                                    $('.plus',$(this)).attr('disabled','disabled').attr('readonly')
                                    $('.minus',$(this)).attr('disabled','disabled').attr('readonly')                                   
                                    break;
                                }
                                else
                                {
                                    for(var j=0;j<data['all'].length;j++)
                                    {
                                       if(data['all'][j]['id']==id)
                                       {
                                        var price=data['all'][j]['price'].toFixed(2)
                                        $('.val',$(this)).val('1')
                                        $('.kkz-price',$(this)).text(price+' грн')
                                        $('.in-basket',$(this)).replaceWith('<span class="buy">Купить</span>')
                                        $('.plus',$(this)).removeAttr('disabled').removeAttr('readonly')
                                        $('.minus',$(this)).removeAttr('disabled').removeAttr('readonly');
                                        break
                                       }
                                        
                                    }
                                }                                                               
                            }
                        })
                    }
                    else
                    {
                        $(user.promore()).each(function(index)
                        {
                            var id=$('input[type=hidden]',$(this)).val()
                            for(var j=0;j<data['all'].length;j++)
                                    {
                                       if(data['all'][j]['id']==id)
                                       {
                                        var price=data['all'][j]['price'].toFixed(2)
                                        $('.val',$(this)).val('1')
                                        $('.kkz-price',$(this)).text(price+' грн')
                                        $('.in-basket',$(this)).replaceWith('<span class="buy">Купить</span>')
                                        $('.plus',$(this)).removeAttr('disabled').attr('readonly')
                                        $('.minus',$(this)).removeAttr('disabled').attr('readonly');
                                        break
                                       }
                                        
                                    }
                        })
                    }
                    $('#user').val(data['name'])
                    $('.zakazkol').html('Товаров: <span class="b">'+data['kol']+'</span>')
                    $('.zakazsum').html('На сумму: <span class="b">'+data['price']+' грн</span>')
                    $('.zakaztotal').html('Сумма заказа: <span class="b">'+data['total']+' грн</span>') 
                } );
            }
            }
            
            
        })
        
        $(document).on(istouch,'.buy:not(.disabled)',function(){
            var $t=$(this);
            var col=parseInt($('.val',$(this).parent()).val())
            var id=$('input[name=id]',$t.parent()).val();
            var pl=$('.plus',$t.parent())
            var minus=$('.minus',$t.parent())
            pl.attr('disabled','disabled')
            minus.attr('disabled','disabled')
            $t.addClass('disabled');
            var optionsUpdate = {
            url:    "/addcart.php",
            beforeSubmit: function(jqForm) {
                 ob=new Object(); ob.name='k';ob.value=col;jqForm.push(ob);
            },
            success: function(data) {
                data=$.parseJSON(data);
    			if(data)
                    {
                        if(data['is']==1)
                        {
                            //location.href='/cart'
                        }
                        else
                        {
                            $('.buy').each(function()
                            {
                                if(id==parseInt($(this).prev().val()))
                                {
                                    $(this).replaceWith('<a href="/cart" class="buyp disabled">В корзине</a>')
                                }
                            })
                            $('.priced',$t.parent().parent().prev()).text(data['pricethis'])
                            $('.zakazkol').html('Товаров: <span class="b">'+data['kol']+'</span>')
                            $('.zakazsum').html('На сумму: <span class="b">'+data['price']+' грн</span>')
                            $('.zakaztotal').html('Сумма заказа: <span class="b">'+data['total']+' грн</span>');
                            if(data['total']<=0 && !$('.oformlenie-link a').hasClass('oformlenie_link_disabled'))
                            {
                                $('.oformlenie-link a').addClass('oformlenie_link_disabled')
                            }
                            else
                            {
                                $('.oformlenie-link a').removeClass('oformlenie_link_disabled')
                            }

                            $t.replaceWith('<a href="/cart" class="in-basket">В корзине</a>')
                        }
                        
                    }
                    else
                    {
                         $t.removeClass('disabled');
                    }
            }
        };
            $t.parent().ajaxSubmit(optionsUpdate); 
            return false;
            
        })
         $(document).on(istouch,'.oformlenie-link a',function()
         {
             if($(this).hasClass('oformlenie_link_disabled')) return false
         })
        ///
         $(document).on(istouch,'.plus:not(.disabled)',function(){
            var $t=$(this);
            var col=parseInt($('.val',$t.parent()).val())
            col++
            if(col==100) {col=1;$('.val',$t.parent()).val(col)}
              else
              {
                $('.val',$t.parent()).val(col)
              }
            var pl=$t
            var minus=$('.minus',$t.parent())
            pl.attr('disabled','disabled')
            minus.attr('disabled','disabled')
            $t.addClass('disabled');
            var optionsUpdate = {
            url:    "/ajax.php",
            beforeSubmit: function(jqForm) {
                 var ob=new Object(); ob.name='action';ob.value='plus';jqForm.push(ob);
                 ob=new Object(); ob.name='k';ob.value=col;jqForm.push(ob);
                 
            },
            success: function(data) {
                data=$.parseJSON(data);
    			if(data)
                {
                    $('.val',$(this).parent()).val(data['k']);
                    $('.kkz-price',$t.parent().parent().parent()).text(data['price']+' грн')
                }
                 $t.removeAttr('disabled');
                 $t.removeClass('disabled');
                 minus.removeAttr('disabled');
                 minus.removeClass('disabled');    
            }
        };
            $t.parent().ajaxSubmit(optionsUpdate); 
            return false;
        })
        //// minus
        $(document).on(istouch,'.minus:not(.disabled)',function(){
            var $t=$(this);
            var col=parseInt($('.val',$t.parent()).val())
            col--;
            if(col==0) {col=99;}
            $('.val',$t.parent()).val(col)
            var pl=$('.plus',$t.parent())
            var minus=$t
            pl.attr('disabled','disabled')
            minus.attr('disabled','disabled')
            $t.addClass('disabled');
            var optionsUpdate = {
            url:    "/ajax.php",
            beforeSubmit: function(jqForm) {
                 var ob=new Object(); ob.name='action';ob.value='minus';jqForm.push(ob);
                 ob=new Object(); ob.name='k';ob.value=col;jqForm.push(ob);
            },
            success: function(data) {
                data=$.parseJSON(data);
    			if(data)
                    {
                        $('.val',$(this).parent()).val(data['k']);
                        $('.kkz-price',$t.parent().parent().parent()).text(data['price']+' грн')
                    }
                 $t.removeAttr('disabled');
                 $t.removeClass('disabled');
                 pl.removeAttr('disabled');
                 pl.removeClass('disabled');    
            }
        };
            $t.parent().ajaxSubmit(optionsUpdate); 
            return false;
        })
	}
})(jQuery);
$(function()
{
    $('#user').user() 
})		
/*Created by Sergey Brilenko*/
(function( $ ){
	$.fn.oform = function(options){
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
		var oform={
		    init:function($this)
            {
               var col=parseInt($('.val',$this.parents('.prorder')).text())
               var stadpr=parseFloat($this.data('price'));
               var priced=$('.price',$this.parents('.prorder')).text()
               var totalth=parseFloat($('.thisusertotal',$this.parent().parent().parent().parent()).text())
               
               if($this.hasClass('plus'))
               {
                  if(col==99) {col=0;$('.thisusertotal',$this.parent().parent().parent().parent()).text(parseFloat(totalth+stadpr-stadpr*99).toFixed(2))}
                  else
                  {
                    $('.thisusertotal',$this.parent().parent().parent().parent()).text(parseFloat(totalth+stadpr).toFixed(2))
                  }
                  col++
               }
               else
               {
                if(col==1) {col=100;$('.thisusertotal',$this.parent().parent().parent().parent()).text(parseFloat(totalth-stadpr+stadpr*99).toFixed(2))}
                else
                {
                    $('.thisusertotal',$this.parent().parent().parent().parent()).text(parseFloat(totalth-stadpr).toFixed(2))
                }
                col--;
               }
               var optionsUpdate = {
                url:    "/trpricepm.php",
                beforeSubmit: function(jqForm) {
                    var ob=new Object(); ob.name='k_';ob.value=col;jqForm.push(ob);
                    console.log(jqForm)
                },
                success: function(data) {
                    data=$.parseJSON(data);
                    console.log(data)
                        if(data)
                        {
                            $('.val',$this.parent()).text(col)
                            $('.price_',$this.parent().parent().parent()).text(parseFloat(data['price']*col).toFixed(2))
                            var price=0;
                            $('.price_',$this.parent().parent().parent().parent()).each(function()
                            {
                                price+=parseFloat($(this).text())
                            })
                            $('.thisusertotal',$this.parent().parent().parent().parent().parent()).text(price.toFixed(2)+' грн')
                           var allt=0;
                           $('.thisusertotal').each(function()
                           {
                             allt+=parseFloat($(this).text())
                           })
                           $('.alltotal').text(allt.toFixed(2)+' грн')
                        }
                    }
                };
               $this.parent().ajaxSubmit(optionsUpdate); 
               
               /*$('.val',$this.parent()).text(col)
               $('.price',$this.parent().parent()).text(parseFloat(stadpr*col).toFixed(2))*/
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
        
        this.on(istouch,function(){
            if(!$(this).hasClass('user'))
            {
                oform.init($(this))
            }
            })
        $('.depprouser').on(istouch,function(){
            var thi=$(this)
            var u=$(this).data("user")
            var delid=$(this).data("id")
            var pr=parseFloat($('.price_',$(this).parent()).text())
            var thistotal=$('.thisusertotal',$(this).parent().parent().parent())
            var thistotal_=parseFloat(thistotal.text())
            var alltotal=$('.alltotal')
            var alltotal_=parseFloat(alltotal.text())
            oform.ajaxsender({action:'depprouser',user_:u,id_:delid},function(data)
            {
                alltotal.text(parseFloat(alltotal_-pr).toFixed(2));
                thistotal.text(parseFloat(thistotal_-pr).toFixed(2))
                if($('li',thi.parent().parent()).length>1)
                {
                     thi.parent().remove()
                }
                else
                {
                    thi.parent().parent().parent().remove()
                }
               
            });
        })
        $('.delall').on(istouch,function(){
            var thi=$(this)
            var id_=$(this).data('id');
            oform.ajaxsender({action:'delall',id:id_},function(data)
            {
                thi.parent().parent().remove()
                if($('.carting li').length<=0)
                {
                    $('.alltotal').text('0')
                }
                console.log(data)
            });
        })
        /*$('#orderp').on(istouch,function()
        {
            var formorder=$('.formorder');
            if(!formorder.is(':visible'))
            {
                formorder.toggle('1000',function()
                {
                    $('#orderp').addClass('readysend').text('Подтвердить заказа')
                })
            }
            
        })*/
        $(document).on(istouch,'.readysend',function()
        {
            
            var po=$('input[name=phone]').val()
            var fioo=$('input[name=fio]').val()
            var adrso=$('textarea[name=address]').val()
            var arr=new Array(20);
            /*$('.cart>li').each(function()
            {
                var temparr=new Array();
                $('ul li',$(this)).each(function(index)
                {
                    var idtov=$('.depprouser',$(this)).data('id')
                    var pricediscount=$('.plus',$(this)).data('price')
                    var kol=$('.val',$(this)).text()
                    temparr.push({ 'id': idtov, 'k': kol,'prtov':pricediscount})
                })
                arr[$('.user',$(this)).data("n")]=temparr
            })*/
            oform.ajaxsender({action:'oform',a:arr,p:po,fio:fioo,ad:adrso},function(data)
            {
                console.log('readysend')
                //data=$.parseJSON(data);
                console.log(data)
                if(data['err'])
                {
                    //alert(data['err'])
                    $('<div id="secret"></div>').empty();
                    $('<div id="secret"></div>').append(data['err']).dialog({modal:true,resizable:false,draggable:false,width:'250',buttons:[{text:'',click:function() { $( this ).remove();} }], close: function( event, ui ) {$( this ).remove();},open: function(){$('.ui-button').blur()} })
                }
                else
                {
                    $('<div id="secret"></div>').empty();
                    $('<div id="secret"></div>').append('Ваш заказ принят').dialog({modal:true,resizable:false,draggable:false,width:'250',buttons:[{text:' ',click:function() { $( this ).remove(); location.href='/kkz'} }], close: function( event, ui ) {$( this ).remove(); location.href='/kkz'},open: function(){$('.ui-button').blur()} })
                    //alert('Ваш заказ принят');

                }
                
            });
        })
        $('.user').on(ishover,function(){
        var name=$(this).val();
        var n=$(this).data("n")
        oform.ajaxsender({action:'userofor',name_:name,n_:n},function(data)
        {
            console.log(data)
        });
        })
        /*$("input[name=phone]").mask("+38 (999) 999 99 99",{completed:function(){
            oform.ajaxsender({action:'phonediscount',val:$(this).val()},function(data)
            {
                console.log(data)
                return false;
                if(data['discount']>0)
                {
                    $('.price').each(function()
                    {
                        var pr=parseFloat($(this).text())
                        var discount=parseFloat(pr-(pr*data['discount'])/100).toFixed(2);
                        $(this).text(discount)
                    })
                    $('.thisusertotal').each(function()
                    {
                        var pr=parseFloat($(this).text())
                        var discount=parseFloat(pr-(pr*data['discount'])/100).toFixed(2);
                        $(this).text(discount)
                    })
                    var pr=parseFloat($('.alltotal').text())
                    var discount=parseFloat(pr-(pr*data['discount'])/100).toFixed(2);
                    $('.alltotal').text(discount)
                }
            });
        
        }
        })*/
	}
})(jQuery);
$(function()
{
    $('.carting input').oform()
})		
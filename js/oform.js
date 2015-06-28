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
                },
                success: function(data) {
                    data=$.parseJSON(data);
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
                if($('.carting li').length<=0)
                {
                    window.location.href="/kkz";
                    $('.alltotal').text('0')
                    $('.readysend').remove();

                }
               
            });
        })
        $('.delall').on(istouch,function(){
            var thi=$(this)
            var id_=$(this).data('id');
            oform.ajaxsender({action:'delall',id:id_},function(data)
            {
                var alltotal=$('.alltotal')
                var pr=parseFloat($('.price',thi.parent()).text())
                var alltotal_=parseFloat(alltotal.text())
                thi.parent().parent().parent().remove()
                if($('.carting li').length<=0)
                {
                    window.location.href="/kkz";
                    alltotal.text('0')
                    $('.readysend').remove();

                }
                else
                {
                    alltotal.text(parseFloat(alltotal_-pr).toFixed(2));
                }
            });
        })
        $(document).on(istouch,'.readysend',function()
        {
            var po=$('input[name=phone]').val()
            var fioo=$('input[name=fio]').val()
            var adrso=$('textarea[name=address]').val()
            oform.ajaxsender({action:'oform',p:po,fio:fioo,ad:adrso},function(data)
            {
                if(data['err'])
                {
                    $('<div>'+data['err']+'</div>').dialog({modal:true,resizable:false,draggable:false,width:'250',buttons:[{text:'',click:function() { $( this ).remove();} }], close: function( event, ui ) {$( this ).remove();},open: function(){$(this).parent().css({top:'50%',left:'50%',position:'fixed',marginTop:(-1)*$(this).parent().height()/2,marginLeft:(-1)*$(this).parent().width()/2});$('.ui-button').blur()} })
                }
                else
                {
                    $('<div>Ваш заказ принят</div>').dialog({modal:true,resizable:false,draggable:false,width:'250',buttons:[{text:'',click:function() { $( this ).remove(); location.href='/kkz'} }], close: function( event, ui ) {$( this ).remove(); location.href='/kkz'},open: function(){$(this).parent().css({top:'50%',left:'50%',position:'fixed',marginTop:(-1)*$(this).parent().height()/2,marginLeft:(-1)*$(this).parent().width()/2});$('.ui-button').blur()} })

                }
                
            });
        })
        $('.user').on(ishover,function(){
        var name=$(this).val();
        var n=$(this).data("n")
        oform.ajaxsender({action:'userofor',name_:name,n_:n},function(data)
        {
        });
        })

	}
})(jQuery);
$(function()
{
    $('.carting input').oform()
   
})		
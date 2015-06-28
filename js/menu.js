/*Created by Sergey Brilenko*/
(function( $ ){
	$.fn.menu = function(options){
       var istouch,ishover, arrowis;
       if((!!('ontouchstart' in window)))
       {
            istouch='touchend';
            ishover='touchstart';
            arrowis='touchstart'
       }
       else
       {
            istouch='click';
            ishover='mouseover';
            arrowis='click'
       }
		var options = $.extend({
		}, options || {});
        
		var m={
		    init:function($this)
            {
               if(!$this.hasClass('disabled'))
               {
                   $this.addClass('disabled')
                   var stadpr=parseFloat($this.data('price'));
                   var priced=$('.priced',$this.parent().parent()).text()
                   var col=parseFloat($('.val',$this.parent()).text())
                   if($this.hasClass('plus'))
                   {
                      if(col==99) col=0;
                      col++
                      
                   }
                   else
                   {
                    if(col==1) col=100;
                      col--;
                   }
                   //вернем правильную цену
                   ////
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
                                $('.priced',$this.parent().parent().prev()).text(parseFloat(data['price']*col).toFixed(2))
                            }
                            $this.removeClass('disabled')
                        }
                    };
                    $this.parent().parent().ajaxSubmit(optionsUpdate);
               } 
                
            }
		}
        
        this.on(istouch,function(){m.init($(this))})
        
	}
})(jQuery);
$(function()
{
    $('.products input').menu() 
})		
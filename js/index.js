var doit2;	
var css;
$(document).ready(function() {
    var func={
        chimg:function()
        {
            $('img').each(function() {
        	var splitin=$(this).attr('src').split('/');
        	var size=('innerWidth' in window) ? window.innerWidth : document.body.offsetWidth;
            
            if(size<1218) {css='fsize1';}
            else
    		if (size>=1218 && size<=1599) {css='fsize2';}
    		else
            if (size>=1600) {css='fsize3';}
            if($(this).attr('src').indexOf('fsize1')!=-1)
            {
                $(this).attr('src',$(this).attr('src').replace('fsize1',css));
            }
            if($(this).attr('src').indexOf('fsize2')!=-1)
            {
                $(this).attr('src',$(this).attr('src').replace('fsize2',css));
            }
            if($(this).attr('src').indexOf('fsize3')!=-1)
            {
                $(this).attr('src',$(this).attr('src').replace('fsize3',css));
            }
    		
        });
        }
    }
    var IE='\v'=='v';

            var size=('innerWidth' in window) ? window.innerWidth : document.body.offsetWidth;

            if(size<1218) {css='1000';}
            else
    		if (size>=1218 && size<=1599) {css='1200';}
    		else
            if (size>=1600) {css='1600';}
           document.getElementById('fsize').setAttribute('href', 'css/s'+css+'.css');
    func.chimg();
    $(window).bind('resize',function(){
      clearTimeout(doit2);
      doit2 = setTimeout(function(){
         func.chimg();
      
                var size=('innerWidth' in window) ? window.innerWidth : document.body.offsetWidth;
                if(size<1218) {css='1000';}
                else
        		if (size>=1218 && size<=1599) {css='1200';}
        		else
                if (size>=1600) {css='1600';}
               	document.getElementById('fsize').setAttribute('href', 'css/s'+css+'.css');
        
     
} , 100)
})
});


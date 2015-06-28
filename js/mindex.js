var doit2;	
var css;
$(document).ready(function() {
    var func={
        chimg:function()
        {
            $('img').each(function() {
        	var size=('innerWidth' in window) ? window.innerWidth : document.body.offsetWidth;
            if(size<1200) {css='fsize1';}
            else
    		if (size>=1201 && size<1599) {css='fsize2';}
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
    		
        });
        }
    }
    var IE='\v'=='v';
     var size=document.body.offsetWidth;
    if(IE)
    {
            
            if(size<640) {css='320';}
            else
    		if (size>=640 && size<977) {css='640';}
    		else
            location.href="http://mobile_version"
           	document.getElementById('fsize').setAttribute('href', 'css/s'+css+'.css');
    }
    else
    {
            if(size<640) {css='320';}
            else
    		if (size>=640 && size<977) {css='640';}
    		else
            location.href="http://mobile_version"
           	document.getElementById('fsize').setAttribute('href', 'css/s'+css+'.css');
    }
    
    
    func.chimg();
    $(window).bind('resize',function(){
         var IE='\v'=='v';
      clearTimeout(doit2);
      doit2 = setTimeout(function(){
         func.chimg();
          var size=document.body.offsetWidth;
          if(IE)
          {
            
            if(size<640) {css='320';}
            else
    		if (size>=640 && size<977) {css='640';}
    		else
            location.href="mobile_version"
           	document.getElementById('fsize').setAttribute('href', 'css/s'+css+'.css');
          }
          else
          {
            if(size<640) {css='320';}
            else
    		if (size>=640 && size<977) {css='640';}
    		else
            location.href="mobile_version"
           	document.getElementById('fsize').setAttribute('href', 'css/s'+css+'.css');
          }
     
} , 100)
})
});


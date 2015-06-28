$(function(){
    var aj ;
    var istouch=(!!('ontouchstart' in window))?'touchstart':'click';
    var speedSlideI =500;
    function heightrazmeraddblock(param)
    {
        var sel=$('#fsize').attr('href')
        if(sel.indexOf('1000')!=-1)
        {
            if(param==1) return 475;
            else return 412;
        }
        else
        if(sel.indexOf('1200')!=-1)
        {
            if(param==1) return 475;
            else return 443;
        }
        else
        if(sel.indexOf('1600')!=-1)
        {
            if(param==1) return 597;
            else return 534;
        }
    }
    function recss()
    {
        var sel=$('#fsize').attr('href')
        if(sel.indexOf('1000')!=-1)
        {
            return 'fsize1';
        }
        else
        if(sel.indexOf('1200')!=-1)
        {
            return 'fsize2';
        }
        else
        if(sel.indexOf('1600')!=-1)
        {
            return 'fsize3';
        }
    }

    $(document).on(istouch,'.prodSetName, .prodSetPhoto',function(){
        var _t = $(this);
        if(!_t.parent().hasClass('disabled'))
        {
            $('.prodSet ').removeClass('disabled')
        
        _t.parent().addClass('disabled')
        /*if (_t.hasClass('thisShow')) return false; 
        $('.thisShow').removeClass('thisShow')
        _t.addClass('thisShow')*/
        if (aj) aj.abort();

        var id = _t.parent().find('input[name="id"]').val();

        var number = parseInt(_t.parent().attr('id').split(':')[1]);

            var afterNumb = 4 - number % 4 + number;
        if (afterNumb > $('.prodSet').size()) afterNumb = $('.prodSet').size();
            
        var j = {
            "number": number,
            "id": id
        };
        var jlz = {
            "number": number,
            "id": id,
            "vis":1
        };
        //$('.infoAboutSetArrow').remove()
        if($('.addblock').length > 0)
        {
            var now_block=$('.addblock').prev().prev().attr('id').split(':')[1]
            if(now_block>number || now_block-4<number) //просто вставляем но в другое место
            {
                aj = $.ajax({
                    type: "post",
                    url: "/lib/_AjaxGetInfoAboutSets.php",
                    data: j,
                    success: function(result){

                        var out=result;
                        if(result.indexOf('fsize1')!=-1)
                        {
                            out=result.replace(/fsize1/g,recss())
                        }
                        $('.infoAboutSetArrow').hide();
                        $('.addblock').remove();
                        $('.prodSet:eq('+(afterNumb - 1 )+') ').after(out);
                        $('.InfoAboutSet').css({height:heightrazmeraddblock(0)})
                        $('.addblock').css({height:heightrazmeraddblock(1)});
                        $('.infoAboutSetArrow',_t.parent()).show();
                    }
                });
            }
            else  //просто вставляем в туже строку
            {
                aj = $.ajax({
                    type: "post",
                    url: "/lib/_AjaxGetInfoAboutSets.php",
                    data: jlz,
                    success: function(result){
                        var out=result;
                        if(result.indexOf('fsize1')!=-1)
                        {
                            out=result.replace(/fsize1/g,recss())
                        }
                        $('.infoAboutSetArrow').hide();
                        $('.addblock .InfoAboutSet>div').replaceWith(out);

                        $('.infoAboutSetArrow',_t.parent()).show();
                    }
                });
            }

        }
        else
        {
            aj = $.ajax({
                type: "post",
                url: "/lib/_AjaxGetInfoAboutSets.php",
                data: j,
                success: function(result){
                    var out=result;
                    if(result.indexOf('fsize1')!=-1)
                    {
                        out=result.replace(/fsize1/g,recss())
                    }
                    $('.infoAboutSetArrow').hide();
                    $('.prodSet:eq('+(afterNumb - 1 )+') ').after(out);
                    $('.InfoAboutSet').animate({height:heightrazmeraddblock(0)},speedSlideI)
                    $('.addblock').animate({height:heightrazmeraddblock(1)},speedSlideI,function()
                    {
                        //$('.removeaddblock').remove()
                    });
                    $('.infoAboutSetArrow',_t.parent()).show();
                }
            });

        }

        }

    return false
    })
    
    $(document).on(istouch,'.infoAboutSetClose>div',function(){
            $('.InfoAboutSet').animate({height:.0},speedSlideI)
            $('.addblock').animate({height:0},speedSlideI,function()
            {
                $(this).remove()
                $('.infoAboutSetArrow').hide();
                $('.prodSet ').removeClass('disabled')
            });
    })
    
    $(window).resize(function(){
        $('.infoAboutSetArrow').hide();
        $('.addblock').remove()
        $('.prodSet ').removeClass('disabled')
    })


})


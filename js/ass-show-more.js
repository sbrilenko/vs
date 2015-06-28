$(function()
{
    var count=1,all=0;
    var istouch=(!!('ontouchstart' in window))?'touchstart':'click';
    $(document).on(istouch,'.ass-show-more',function()
    {
            var th=$(this)
            if(!th.hasClass('dis'))
            {
                var datamore=th.attr('data-more'),datad=th.attr('data-d').split("/"),j;
                all=th.attr('data-all');
                j={
                    more:datamore,
                    cat:datad[0],
                    pr:datad[1],
                    vpr:datad[2],
                    firm:datad[3],
                    c:count
                }
                 th.addClass('dis')
                 $.ajax({
                    type: "post",
                    url: "/lib/ass-next.php",
                    data: j,
                    success: function(result){
                        if(result=="")
                        {
                            th.hide();
                            th.attr('data-more',0);
                        }
                        else
                        {
                            th.removeClass('dis')
                            count++;
                            if(all<=count*20) {th.hide(); th.attr('data-more',0);};
                            $('.ass-all').append(result)
                        }

                    }
                });
            }

    return false;
    })
})
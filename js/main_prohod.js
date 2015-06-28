var size_window=$(window).width();
			if(size_window<1218) {size_window='fsize1';}
			else
			if (size_window>=1218 && size_window<=1599) {size_window='fsize2'; }
    		else
            if (size_window>=1600) {size_window='fsize3';}


load_re={}
var istouch=(!!('ontouchstart' in window))?'touchstart':'click';
if (!window.requestAnimationFrame) {
    window.requestAnimationFrame = (function() {
        return window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame ||
            window.oRequestAnimationFrame ||
            window.msRequestAnimationFrame ||
            function(callback, element) {

                window.setTimeout(callback, 1000 / 60);
            };
    })();
}


//Array.prototype.max = function() {
//  return Math.max.apply(null, this)
//}

var ZoomFactor =  0.012;
//0.02//0.24;//0.04;
var flag_resize = 0;
var temp = 0//какая фото
var temp_g = 0;
var from = (temp)?temp:1//temp-1;//с какой начать
var to = (temp)?(temp+1):47//47 где закончить

var how = to;
var N = from - 1;
var raplay_click = 0;
var t = 2;
var Factor = [
    1.25,//1
    1.33,//2
    1.5,//3
    2,//4
    1.25,//5
    1.65,//6
    1.50,//7
    2,//8
    1.25,//9
    1.65,//10
    1.50,//11
    2,//12
    1.56,//13
    1.33,//14
    1.50,//15
    2,//16
    1.20,//17
    1.34,//18
    1.50,//19
    2,//20
    1.25,//21
    1.21,//22
    1.33,//23
    1.5,//24
    2,//25
    1.26,//26
    1.50,//27
    1.15,//28
    1.5,//29
    2,//30
    1.25,//31
    1.95,//32
    1.35,//33
    1.5,//34
    2,//35
    1.3,//36
    1.5,//37
    2,//38
    1.25,//39
    1.6,//40
    1.33,//41
    1.5,//42
    2,//43
    1.22,//44
    1.25,//45
    1.35,//46

    1.5,//47
    2,//48
    1.2,//49
    2//50
]

var isiPad = navigator.userAgent.toLowerCase().indexOf("iPad") > -1;
var isAndroid = navigator.userAgent.toLowerCase().indexOf("android") > -1;


switch (size_window) 
{
	case 'fsize1':
						var X = (isiPad || isAndroid)?621:621//document.body.offsetWidth;//720;
						var Y = (isiPad || isAndroid)?310:310// (X*0.655);//450;
						
	break;
	case 'fsize2':
						var X = (isiPad || isAndroid)?780:780//document.body.offsetWidth;//720;
						var Y = (isiPad || isAndroid)?390:390// (X*0.655);//450;
						
	break;
	case 'fsize3':
						var X = (isiPad || isAndroid)?940:940//document.body.offsetWidth;//720;
						var Y = (isiPad || isAndroid)?470:470// (X*0.655);//450;
						
	break;

}
var del_time;


var DeltaX = X;
var DeltaY = Y;
var dir = '/prohod_1_1/';
switch (resize_window) 
{

	case 'fsize1':
						dir = '/prohod_1_1/';
						
	break;
	case 'fsize2':
					 dir = '/prohod_1/';
						
	break;
	case 'fsize3':
				 dir = '/prohod_1/';
						
	break;

  	console.log(dir)
}


var imgs = new Array;
var ctx;
var MAXW = new Array //[Factor[0] * X, Factor[1] * X, Factor[2] * X, Factor[3] * X, Factor[4] * X, Factor[5] * X, Factor[6] * X, Factor[7] * X, Factor[8] * X, Factor[9] * X];
for (var k = 0; k < how; k++)
    MAXW[k] = Factor[k] * X;
var WA = new Array;
var HA = new Array;
//var TA = [0];
//var LA = [0];

//var WA2A = new Array;
//var HA2A = new Array;
//var TA2A = new Array;
//var LA2A = new Array;

var SW = new Array;
var SH = new Array;
var TW = new Array;
var TH = new Array;

var S = new Array;
var T = new Array;
var S2 = new Array;
var T2 = new Array;

var fps = [];

var i = 0;
var previous = [];
var cached;
var buffer = new Array;
var timeoutId;
$(document).ready(function(){

    init()
}).on(istouch,'#replay_bt',function() {
    ctx.clearRect(0,0,X,Y)
    //$('#bg_canvas').css({display:'block',opacity:1});
    clearTimeout(del_time)
    temp_t=0;
    //console.log(temp_t);
    document.getElementById('replay').style.display = "none";
    // setTimeout(" animate2() ", 2000);
    $('div#fly').animate({opacity: 1}, 1000 );
    requestAnimationFrame(animate2)
}).on(istouch,'#replay_bt_after',function() {
        ctx.clearRect(0,0,X,Y)
        $('#bg_canvas').css({display:'block',opacity:1});


        temp_t=0;

        $('div#fly').animate({opacity: 1}, 1 );
        document.getElementById('replay').style.display = "none";
        after_run()
        console.log('click')
    });

function preDraw()
{
    //document.getElementById('fly').setAttribute('style','display:none');
    //if (isiPad || isAndroid)
    {
        var m = 0;
        {
            var interval = setInterval(function(){
                ctx.drawImage(imgs[m], 0, 0, X, Y);
                m++;
                if (m >= how) {clearInterval(interval);

                    ctx.clearRect(0,0,X,Y)};
                //  console.log('test'+m);
            },1)

        }

    }
    //if (m >= how) document.getElementById('fly').setAttribute('style','');
    //else return false;
}

function  init()
{

    temp_t = 0;
    var canvas = document.getElementById('canvas');
    var fly = document.getElementById('fly');
    var gradient = document.getElementById('gradient');

    canvas.setAttribute('width',X+'px');
    canvas.setAttribute('height',Y+'px');
    ctx = canvas.getContext('2d');

    gradient.style.width = X+'px';
    gradient.style.height = Y+'px';

    fly.style.width = X+'px';
    fly.style.height = Y+'px';

    WA = [X];
    HA = [Y];
    var c = N;
    var s= 0;
    imgs = getImages(how)
    s= 0;

	if (flag_resize == 1)
							{
								flag_resize = 0;
								return false;
							}
							else
							{
		if (c < how)
			{
			
			
					console.log(flag_resize)
							imgs[c].onload = function()
						{
							
							if (flag_resize == 1)
							{
								flag_resize = 0;
								return false;
							}
							else
									{
										//  console.log(imgs[c]);

										if (s<=99)
										{
											s = s+2;
										}
										if (s>=94)
										{
											s = 100;
										}
										document.getElementById('procent_load').style.display = "block";
										document.getElementById('procent_load').innerHTML = s + '%';

										load_re[c]=renderToCanvas(X, Y, function (ctx) { ctx.drawImage(imgs[c], 0, 0, X, Y)})
										c++;
										if (c < how) setTimeout(arguments.callee, 15000/60);

										else
										{
											//setBuffer() ;
											buffer=load_re
											getVars();
											//preDraw();

											/*cached = renderToCanvas(X, Y, function (ctx) {
												ctx.drawImage(buffer[N], 0, 0, X, Y);
											});*/
											cached=buffer[N]
											ctx.save();

											console.log('canvas - ok\n');
											document.getElementById('fly').style.display = "block";

											document.getElementById('stats').innerHTML = "";

											var thistimer=setTimeout(function(){clearTimeout(thistimer);after_run();},200)

											return true;
										}
								
									}
						}
				
			}
			}
	
}
function after_run(){
    var timeput1=setTimeout(function(){clearTimeout(timeput1);anim1()}, 600)
    var timeput2=setTimeout(function(){clearTimeout(timeput2);anim2();}, 1500)
    var timeput3=setTimeout(function(){clearTimeout(timeput3);animate2();}, 2000)
}
function anim1(){
switch (size_window) 
{
	case 'fsize1':
						 $(".3img").animate({ right: "155px" });
						$(".9img").animate({ right: "155px" });
						$(".4img").animate({ left: "155px" });
						$(".10img").animate({ left: "155px" });
						
	break;
	case 'fsize2':
						 $(".3img").animate({ right: "195px" });
						$(".9img").animate({ right: "195px" });
						$(".4img").animate({ left: "195px" });
						$(".10img").animate({ left: "195px" });
						
	break;
	case 'fsize3':
						 $(".3img").animate({ right: "235px" });
						$(".9img").animate({ right: "235px" });
						$(".4img").animate({ left: "235px" });
						$(".10img").animate({ left: "235px" });
						
	break;

}
   


}

function anim2(){




switch (size_window) 
{
	case 'fsize1':
						     $(".3img").animate({ right: "310px" });
							$(".9img").animate({ right: "310px" });
							$(".4img").animate({ left: "310px" });
							$(".10img").animate({ left: "310px" });

							$(".2img").animate({ right: "154px" });
							$(".8img").animate({ right: "155px" });
							$(".5img").animate({ left: "155px" });
							$(".11img").animate({ left: "155px" });
						
	break;
	case 'fsize2':
						     
							
							  $(".3img").animate({ right: "390px" });
							$(".9img").animate({ right: "390px" });
							$(".4img").animate({ left: "390px" });
							$(".10img").animate({ left: "390px" });

							$(".2img").animate({ right: "195px" });
							$(".8img").animate({ right: "195px" });
							$(".5img").animate({ left: "195px" });
							$(".11img").animate({ left: "195px" });
						
	break;
	case 'fsize3':
	
							$(".3img").animate({ right: "470px" });
							$(".9img").animate({ right: "470px" });
							$(".4img").animate({ left: "470px" });
							$(".10img").animate({ left: "470px" });

							$(".2img").animate({ right: "235px" });
							$(".8img").animate({ right: "235px" });
							$(".5img").animate({ left: "235px" });
							$(".11img").animate({ left: "235px" });
							
						  
	break;

}


    $('#opacity_box,#opacity_box2').show();

}

function FPS() {
    if (previous.length > 60) {
        previous.splice(0, 1);
    }
    var start = (new Date).getTime();
    previous.push(start);
    var sum = 0;

    for (var id = 0; id < previous.length - 1; id++) {
        sum += previous[id + 1] - previous[id];
    }

    var diff = ~~(1000 / (sum / previous.length));

    document.getElementById('stats').innerHTML ="";
    /*document.getElementById('stats').innerHTML = diff + " FPS";*/
}
function anim1_reainim(){

    $(".3img").animate({ right: "0" });
    $(".9img").animate({ right: "0" });
    $(".4img").animate({ left: "0" });
    $(".10img").animate({ left: "0" });

    //	document.getElementById('replay').style.display = "none";
}

function replay() {


    // temp_t=0;
    // requestAnimationFrame(animate2);
    //document.getElementById('opacity_box').style.display = "none";
    //document.getElementById('opacity_box2').style.display = "none";
    document.getElementById('replay').style.display = "block";

    $('div#bg_canvas').animate({opacity: 0}, 10 );
    $('div#fly').animate({opacity: 0.5}, 1000 );
    $('div#replay').animate({opacity: 1}, 1000 );
    $('div#replay_bt').animate({opacity: 1}, 1000 ,function()
    {
        cached = renderToCanvas(X, Y, function (ctx) {
            ctx.drawImage(imgs[N], 0, 0, X, Y);
        });
    });
    //$('div#replay').animate({  filter: alpha(opacity=1);}, 1000 );
    //$('div#replay_bt').animate({  filter: alpha(opacity=1);}, 1000 );

}

function after_10_replay() {


    $('#procent_load').html('');
    $('#opacity_box,#opacity_box2').css({display:'none'})
	
	switch (size_window) 
{
	case 'fsize1':
						    $(".3img").animate({ right: "155px" });
							$(".9img").animate({ right: "155px" });
							$(".4img").animate({ left: "155px" });
							$(".10img").animate({ left: "155px" });
						
	break;
	case 'fsize2':
						     
							
							$(".3img").animate({ right: "195px" });
							$(".9img").animate({ right: "195px" });
							$(".4img").animate({ left: "195px" });
							$(".10img").animate({ left: "195px" });
	break;
	case 'fsize3':

							$(".3img").animate({ right: "235px" });
							$(".9img").animate({ right: "235px" });
							$(".4img").animate({ left: "235px" });
							$(".10img").animate({ left: "235px" });
							
						  
	break;

}
    

    $(".2img").animate({ right: "0" });
    $(".8img").animate({ right: "0" });
    $(".5img").animate({ left: "0" });
    $(".11img").animate({ left: "0" });
	
	
	
	
    $('#replay').hide();
    setTimeout(function()
    {
        anim1_reainim()

    }, 800)
    $('#replay_bt_after').show();


    /*

     $(".3img").animate({ right: "0" });
     $(".9img").animate({ right: "0" });
     $(".4img").animate({ left: "0" });
     $(".10img").animate({ left: "0" });*/


}

function animate2()
{
 if (flag_resize == 1)
 {

 }
 else
 {
    draw();
    //if (N!=0) 
 /*   if (isiPad) {
    setTimeout(animate2,1000/60);
	   console.log('isiPad')
	}
    else */
    //console.log(temp_t)
    if (temp_t < 1623) {
        if (t  < 460){

            requestAnimationFrame(animate2);
        }
        else
        {
            console.log('stop')
            temp_g = 1;

        }
    }
    else
    if (temp_t == 1623) { //1623


        var timer5=setTimeout(function(){clearTimeout(timer5);replay()}, 2000)
        del_time=setTimeout(function(){clearTimeout(del_time);after_10_replay()}, 12000)

        //setTimeout("after_10_replay()", 5000)
    }



    //console.log(temp_t);
    temp_t++;
    //else N = from;

}
}
$(window).scroll(function()
{
    t = $(window).scrollTop()
    if (t< 460 && temp_g == 1)
    {
        //console.log('vidno')
        animate2()
        temp_g = 0;
    }
    else
    {
        //console.log('nevidno')
    }
})

var renderToCanvas = function (width, height, renderFunction) {
    var buffer = document.createElement('canvas');
    buffer.width = width;
    buffer.height = height;
    renderFunction(buffer.getContext('2d'));
    return buffer;
};

function setBuffer()
{
    for (var j = 0; j < how; j++)
        buffer[j] = renderToCanvas(X, Y, function (ctx) {ctx.drawImage(imgs[j], 0, 0, X, Y)})

    //setTimeout(arguments.callee);
    // }
    //document.body.appendChild(buffer[j]);
    //console.log(buffer);
    //console.log(imgs);

}
function draw()
{
    //FPS();
    if (WA[i] >= MAXW[N])
    {
        if (++N >= how) N = from-1;
        i = 0;
        ctx.restore();
        /*cached = renderToCanvas(X, Y, function (ctx) {
            ctx.drawImage(buffer[N], 0, 0, X, Y);
        });*/
        cached =buffer[N]
        ctx.save();
        //console.log(N);
    }
    ctx.drawImage(cached, 0,0);
    ctx.translate(TW[i], TH[i]);
    ctx.scale(SW[i], SH[i]);


    //FPS();
    i++;
}

function getVars()
{
    while (Math.max.apply(null, MAXW) > WA[WA.length-1])
    {
        WA.push((WA[WA.length-1] * ZoomFactor + WA[WA.length-1]));
        HA.push((HA[HA.length-1] * ZoomFactor + HA[HA.length-1]));
    }
    var l = 0;
    while (WA[l+1])
    {
        S[l] = (WA[l+1]) / (WA[l]);
        T[l] = -(X*S[l]-X)/2;
        S2[l] = (HA[l+1]) / (HA[l]);
        T2[l] = -((Y*S2[l]-Y)/2);//*1.465;
        l++;
    }
    SW = S;
    TW = T;
    SH = S2;
    TH = T2;
    //console.log(SW)
    //console.log(Math.max.apply(null, MAXW))
    console.log('var - ok\n');
}

function getImages(n)
{
    var image, array = [],j;
    for(j = 1; j <= n; j++ )
    {
        image = new Image;
        image.src = dir + j + ".jpg";
        array.push(image);
        //  console.log(j);
    }
    return array;
}






 
 
    $(function(){

        if ($.browser.opera) $('.bg_img_main_1000').css('display','none').fadeIn(6000)
        else
            $('.bg_img_main_1000').css('display','none').load(function(){
                $(this).fadeIn(100)
            })

    })

    $(document).ready(function(){
	
	
	
        $('.boxgrid').hover(function(){
            $(".cover", this).stop().animate({right:'0px'},{queue:false,duration:300});

        }, function() {
		
		switch (size_window) 
{
	case 'fsize1':
						   $(".cover", this).stop().animate({right:'155px'},{queue:false,duration:300});
	break;
	case 'fsize2':
						 $(".cover", this).stop().animate({right:'195px'},{queue:false,duration:300});
	break;
	case 'fsize3':
						$(".cover", this).stop().animate({right:'235px'},{queue:false,duration:300})
	break;

}
           

        });
		
    });
	var resize_window=$(window).width();
			if(resize_window<1218) {resize_window='fsize1';}
			else
			if (resize_window>=1218 && resize_window<=1599) {resize_window='fsize2';}
    		else
            if (resize_window>=1600) {resize_window='fsize3';}	
			
			var resize_window_old = resize_window;

			
		$(window).resize(function(){
		
		var resize_window=('innerWidth' in window) ? window.innerWidth : document.body.offsetWidth;
		
			if(resize_window<1218) {resize_window='fsize1';}
			else
			if (resize_window>=1218 && resize_window<=1599) {resize_window='fsize2';}
    		else
            if (resize_window>=1600) {resize_window='fsize3';}	
				console.log(resize_window)
		
			
	if (resize_window_old != resize_window)
		{
	
		//location.reload();
		size_window=$(window).width();
		$('.boxgrid').removeAttr('style');
		$('.cover').removeAttr('style');
		switch (resize_window) 
{
	case 'fsize1':
						 X = (isiPad || isAndroid)?621:621
						Y = (isiPad || isAndroid)?310:310
						
	break;
	case 'fsize2':
					 X = (isiPad || isAndroid)?780:780
				     Y = (isiPad || isAndroid)?390:390
						
	break;
	case 'fsize3':
					 X = (isiPad || isAndroid)?940:940
					Y = (isiPad || isAndroid)?470:470
						
	break;

  
}
location.reload();
//flag_resize = 1;

//init()
//console.log(flag_resize)
		}
		resize_window_old = resize_window;
			//flag_resize = 0;
//location.reload();

	
		})

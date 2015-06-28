
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

var ZoomFactor = <?=($_GET['speed'] ? $_GET['speed'] : 0.011)?>
//0.02//0.24;//0.04;

var temp = 0//какая фото

var from = (temp)?temp:1//temp-1;//с какой начать
var to = (temp)?(temp+1):18//temp+1//14;//где закончить

var how = to;
var N = from - 1;

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
1.25,//17
1.95,//18
]

var isiPad = navigator.userAgent.toLowerCase().indexOf("iPad") > -1;  
var isAndroid = navigator.userAgent.toLowerCase().indexOf("android") > -1;

var X = (isiPad || isAndroid)?940:940//document.body.offsetWidth;//720;
var Y = (isiPad || isAndroid)?470:470// (X*0.655);//450;



var DeltaX = X;
var DeltaY = Y; 
var dir = '/photo_test_19/';

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
 
window.onload = function(){
    
        init()
}

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
                if (m >= how) clearInterval(interval);
                console.log('test'+m);
            },1000/30)
            
        }
        
    }
    //if (m >= how) document.getElementById('fly').setAttribute('style','');
    //else return false;
}

function  init()
{
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
    if (c < how)
    {
        
            imgs[c].onload = function()
            {
                console.log(imgs[c]);
				
				if (s<=99)
				{
				s = s+5;
}
	if (s>=90)
	{
		s = 100;
		}
			document.getElementById('procent_load').innerHTML = s + '%';
			
			
                c++;
                if (c < how) setTimeout(arguments.callee, 1000/60);
                else 
            {
    
     
    setBuffer();
    getVars();
    
    
    //preDraw();
    cached = renderToCanvas(X, Y, function (ctx) {
        ctx.drawImage(imgs[N], 0, 0, X, Y);
    });
    ctx.save();
    
    console.log('canvas - ok\n');
    document.getElementById('fly').style.display = "block";
        document.getElementById('stats').innerHTML = "Click me for start!";

    document.getElementById('fly').onclick = function(){animate();}
    return true;}
                
            }
    }
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
    
    
    
function animate() 
{
    
    
    draw();
    //if (N!=0) 
    //if (isiPad) 
    //setTimeout(animate,1000/60);
    //else 
    requestAnimationFrame(animate);
    //else N = from;
 
}

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
            cached = renderToCanvas(X, Y, function (ctx) {
            ctx.drawImage(buffer[N], 0, 0, X, Y);
            }); 
            ctx.save();
            //console.log(N);
        } 
        ctx.translate(TW[i], TH[i]);
        ctx.scale(SW[i], SH[i]);
        ctx.drawImage(cached, 0,0);
        FPS();
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
    }
    return array;
} 



//
// Copyright 2008 by Fabian Schlieper
// Schnee v1.0
// http://www.fabi.me/
// Ohne dieses Copyright darf dieser Code nicht verwendet werden!
//

// maximale Anzahl der sichtbaren Schneeflocken
var snow_max = 30;

// die verschiedenen Farbe, die die Schneeflocken haben sollen
var snow_color = new Array("#AAAACC","#DDDDFF","#CCCCDD","#F3F3F3","#F0FFFF");

// die Schriftarten, aus denen die Schneeflocken bestehen sollen
var snow_font  = new Array("Arial Black", "Arial Narrow", "Times", "Comic Sans MS");

var snow_char = "*";			// das Zeichen, das als Schneeflocke verwendet wird
var gravity = 0.6;				// wie schnell die Schneeflocken fallen
var snow_maxsize = 32;			// die maximale Schriftgröße einer Schneeflocke
var snow_minsize = 8;			// die minimale Schriftgröße einer Schneeflocke
var snow_area_id = "xmasBox";	// die ID des HTML-Elements, in dem es schneien soll


// ######################################################################
// HIER ENDET DIE KONFIGURATION. ÄNDERUNGEN IM FOLGENDEN SETZEN JAVSCRIPT-KENNTNISSE VORAUS
// ######################################################################


var snow = new Array();
var snowarea;
var i_snow = 0;
var x_mv = new Array();
var crds = new Array();
var lftrght = new Array();
var browserinfos = navigator.userAgent ;
var ie5 = document.all&&document.getElementById&&!browserinfos.match(/Opera/);
var ns6 = document.getElementById&&!document.all;
var opera = browserinfos.match(/Opera/);
var browserok = ie5||ns6||opera;

if(ie5 || opera) {
	snow_maxsize -=  4;
	snow_minsize -= 4;
}


function randommaker(range)
{		
	rand = Math.floor(range*Math.random());
    return rand;
}

function initSnow()
{	
	snowarea = document.getElementById(snow_area_id);
	
	if(snowarea == null)
	{
		setTimeout("initSnow()", 50);
		return;
	}
	
	snowarea.style.position = "relative";

	var snowsizerange = snow_maxsize - snow_minsize;
	
	var mleft		= snowarea.offsetLeft;
	var mright		= snowarea.offsetWidth;
	var mtop		= snowarea.offsetTop;
	var mbottom		= snowarea.offsetHeight;
			
	for (i = 0;i <= snow_max;i++) {
		crds[i] = 0;                      
    	lftrght[i] = Math.random()*15;         
    	x_mv[i] = 0.03 + Math.random()/10;
		snow[i] = document.getElementById("s"+i);
		snow[i].style.fontFamily = snow_font[randommaker(snow_font.length)];
		snow[i].size = randommaker(snowsizerange) + snow_minsize;
		snow[i].style.fontSize = snow[i].size;
		snow[i].style.color = snow_color[randommaker(snow_color.length)];
		snow[i].sink = gravity*snow[i].size/5;
		
		snow[i].posx = mleft + randommaker(mright - snow[i].size);
		snow[i].posy = mtop + randommaker(mbottom);
		
		snow[i].style.left = snow[i].posx;
		snow[i].style.top = snow[i].posy;
	}
	window.setInterval("updateSnow()", 50);
}

function updateSnow() {
	for (i = 0; i <= snow_max; i++) {
		crds[i] += x_mv[i];
		snow[i].posy+=snow[i].sink;
		snow[i].style.left = (snow[i].posx+lftrght[i]*Math.sin(crds[i])) + "px";
		snow[i].style.top = snow[i].posy + "px";
		
		var mleft		= snowarea.offsetLeft;
		var mright		= mleft + snowarea.offsetWidth;
		var mtop		= snowarea.offsetTop;
		var mbottom		= mtop + snowarea.offsetHeight - snow[i].size;
		
		if ( snow[i].posy >= mbottom || snow[i].posx  >= ( mright-3*lftrght[i]) ){
			snow[i].posx = mleft + randommaker(mright - mleft);
			snow[i].posy = mtop;
		}
	}
}

for (i = 0;i <= snow_max;i++)
{
	document.write("<span id='s"+i+"' style='position:absolute; top:-"+snow_maxsize+"px; z-index: 99;'>"+snow_char+"</span>");
}

setTimeout("initSnow()", 10);

function getSyncAJAX(uri) {
	if (window.XMLHttpRequest)	var xHr=new XMLHttpRequest();
	else						var xHr=new ActiveXObject('Microsoft.XMLHTTP');	
	xHr.open('GET',uri,false);
	xHr.send(null);
	return xHr.responseText;
}

function getAJAX(uri,type,arg1) {
	if (window.XMLHttpRequest)	var xHr=new XMLHttpRequest();
	else						var xHr=new ActiveXObject('Microsoft.XMLHTTP');	
	xHr.onreadystatechange = function() { if (xHr.readyState==4 && xHr.status==200) { parse(xHr.responseText,type,arg1); }}
	xHr.open('GET',uri,true);
	xHr.send();
}

function getCookie(str) {
	var cookie = document.cookie;
	if(cookie.indexOf(str)>-1) {
		var piece = cookie.substring(cookie.indexOf(str),cookie.length);
		return piece.substring(piece.indexOf('=')+1,piece.indexOf(';'))
	} else return -1;
}
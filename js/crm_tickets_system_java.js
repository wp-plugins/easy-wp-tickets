function confirmDelete(txt, url){
	var cf = confirm(txt);
	if (cf == true) window.location.assign(url);
}

function DesaOk(){
	document.getElementById("aok").innerHTML="";
	document.getElementById("aok").className = "alertOkClosed";
};
window.load=setTimeout('DesaOk()', 4000);

function DesaBad(){
	document.getElementById("aba").innerHTML="";
	document.getElementById("aba").className = "alertBadClosed";
};
window.load=setTimeout('DesaBad()', 4000);
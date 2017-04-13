var time;

function calcTime() {
	time = setTimeout(showPage, 3000);
}
function showPage() {
	document.getElementById("loader").style.display = "none";
	document.getElementById("webDiv").style.display = "block";
}
function TwitterPG() {
	window.open('https://twitter.com/CDA_Gamers', '_system');
}
function YoutubePG() {
	window.open('https://t.co/gvolbJr5ng', '_system');
}
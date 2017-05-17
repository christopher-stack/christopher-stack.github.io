var time;

function calcTime() {
	time = setTimeout(showPage, 2000);
}
function showPage() {
	document.getElementById("loader").style.display = "none";
	document.getElementById("webDiv").style.display = "block";
}
function HomePage() {
	window.open('index.html', '_self');
}

function NewsPage() {
	window.open('News.html', '_self');
}

function TwitterPage() {
	window.open('https://twitter.com/CDA_Gamers', '_system');
}

function YouTubePage() {
	window.open('https://t.co/gvolbJr5ng', '_system');
}

function HappyWheels() {
	window.open('Games/HappyWheels.html', '_self');
}
$( "#header_main" ).load( "php/header_main.html", function() {
  alert( "Header Loaded." );
});

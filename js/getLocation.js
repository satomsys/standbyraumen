/**
* 位置情報
*/
currentPos = null,
posdata = null,
$latlng = new Array();
myRequest = new XMLHttpRequest();


myRequest.addEventListener( 'progress', function( ev ){
 // posdata = getLatLng();

 // document.body.innerHTML = myRequest.responce;
});
myRequest.addEventListener( 'load', function( ev ){
 document.body.innerHTML = myRequest.responce;
});
myRequest.open( 'GET', '../index.php');
// myRequest.open( 'POST', '../index.php');

myRequest.send( );



/**
* 位置情報を返す
*/
function showPosition(position) {

	if( sessionStorage.length ){
		sessionStorage.clear;
	}

	sessionStorage.setItem( 'yourLat', position.coords.latitude );
	sessionStorage.setItem( 'yourLng', position.coords.longitude );
	// console.log( position.coords.latitude + ", " + position.coords.longitude );
}
function handleError(error) {
	document.getElementById("location").innerHTML = error.message;
}
function getPosition() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition, handleError);
	}
}
window.addEventListener("load", getPosition, false);
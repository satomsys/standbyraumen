/**
* 位置情報
*/
currentPos = null,
posdata = {};

function getLatLng(){
	if ( navigator.geolocation ){
		navigator.geolocation.getCurrentPosition( function( position ){
		var lat = position.coords.latitude,
			lng = position.coords.longitude;

			return currentPos = {
				lat : lat,
				lng : lng
			};			
		}, function( err ){
			console.warn( err );
		});
	} else {
		currentPos = '位置情報を有効にしてください。';
	}
}

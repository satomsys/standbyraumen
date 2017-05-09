/**
* google map settingz
*/
map = null,
marker = null;

function initMap( $latlng ){

	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition( function( position ){
			$latlng = {
				lat: Number( position.coords.latitude ),
				lng: Number( position.coords.longitude )
			},
			$postLocation = locationArg( 'gMapInit' );


		    map = new google.maps.Map( document.getElementById('map'), {
		      center: {
		      	lat: $latlng.lat ,
		      	lng: $latlng.lng
		      },
		      zoom: 13
		    } );

		    //読み込み終了イベント
		    google.maps.event.addListenerOnce( map, 'idle', function(){

				$service = new google.maps.places.PlacesService( map );	    	

		    	// console.log( $service.getDetails({}) )

			    /**
			    * マーカー生成
			    */
			    //自分の位置
				marker = new google.maps.Marker({
					position: $latlng,
					map: map
				});
				//instagram投稿場所
		        for (var i = 0,l = $postLocation.length; i < l; i++) {
		        	// setTimeout( function(){
			         	addMarker( $postLocation[i], map );	        		
		        	// }, 50 );
		        }
		    });

		
		}, function( error ){
			alert( error.message )
		} );
	}
}


/**
* 投稿の配列をscriptタグから受け取る
*/
function locationArg( $id ){

	var $target = document.getElementById( $id ),
		$locationArg = JSON.parse( $target.getAttribute('data-locationArg') );

	return $locationArg;

}

/**
* マーカーを追加
*/
function addMarker( $data, $map ){
	var marker = new google.maps.Marker({
		position: $data,
		map: $map,
		// icon: {
		// 	url: 'https://developers.google.com/maps/documentation/javascript/images/circle.png',
		// 	anchor: new google.maps.Point(10, 10),
		// 	scaledSize: new google.maps.Size(10, 17)
		// },		
		animation: google.maps.Animation.DROP		
	});

marker.addListener('click', function() {
	console.log( this.name )
  });	
}

/**
* 情報ウィンドウのセット
*/
function showInfo( $service, $marker, $map ){

	google.maps.event.addListener( $marker, 'click', function(){
		service.getDetails
	} );

}
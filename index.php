<?php

// var_dump( $_SERVER );
// var_dump( __FILE__ );
// var_dump( PATHINFO_DIRNAME );
// var_dump( PATHINFO_BASENAME );
// var_dump( PATHINFO_EXTENSION );
// var_dump( PATHINFO_FILENAME );
// (empty($_SERVER["HTTPS"]) ? "http://" : "https://")

include 'function/fortest.php';

	// 設定項目
	$access_token = '20917341.6fe37bd.53d5a385dd71465e8ef198164bb4195f';
	//↓こっちは取得できた。
	$request_url = 'https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $access_token ;		// リクエストURL
	//↓なぜかNULL
	// $request_url = 'https://api.instagram.com/v1/users/20917341/feed?access_token=' . $access_token ;		// リクエストURL

	// アイテムデータをJSON形式で取得する (CURLを使用)
	$curl = curl_init() ;

	// オプションのセット
	curl_setopt( $curl , CURLOPT_URL , $request_url ) ;
	curl_setopt( $curl , CURLOPT_HEADER, 1 ) ; 
	curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , false ) ;								// 証明書の検証を行わない
	curl_setopt( $curl , CURLOPT_RETURNTRANSFER , true ) ;								// curl_execの結果を文字列で返す
	curl_setopt( $curl , CURLOPT_TIMEOUT , 5 ) ;										// タイムアウトの秒数

	// 実行
	$res1 = curl_exec( $curl ) ;
	$res2 = curl_getinfo( $curl ) ;

	// 終了
	curl_close( $curl ) ;

	// 取得したデータ
	$json = substr( $res1, $res2['header_size'] ) ;										// 取得したデータ(JSONなど)
	$header = substr( $res1, 0, $res2['header_size'] ) ;								// レスポンスヘッダー (検証に利用したい場合にどうぞ)

	// HTML用
	$html = '' ;
	$locationArg = [];

	// JSONデータをオブジェクト形式に変換する
	$obj = json_decode( $json ) ;

	var_dump( $obj );

	//位置情報の配列を作成
	if( !$obj || !isset($obj->data) ){
		$html .= '<p>データを取得できませんでした…。設定を再確認して下さい。</p>' ;
	}
	else
	{
		$count = 0;
		foreach( $obj->data as $item ){
			if( $item->location && $item->location->latitude && $item->location->longitude ){

				$locationArg[$count] = [
					'lat' => $item->location->latitude,
					'lng' => $item->location->longitude
				]; 

				$count++;
			}
		}
	}	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="robots" content="noindex,nofollow">

		<!-- ビューポートの設定 -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA68RPDswpO5wwA_SRv0D-OUDkABO1n3BU&callback=initMap"></script>
		<script src="js/mapSetter.js"></script>
		<script src="js/getLocation.js"></script>

		<title> stand by raumen beta</title>
	</head>
<body>


<div id="map"></div>
<?php echo $html ?>



</body>
</html>

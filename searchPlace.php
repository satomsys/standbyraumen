<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="robots" content="noindex,nofollow">

		<!-- ビューポートの設定 -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Instagramで場所を検索するサンプルデモ</title>

		<script type="text/javascript" src="js/getLocation.js"></script>
		<script>
			// pos = null;

			// setInterval( function(){
			// 	if( pos ){
			// 		return;
			// 	};
			// 	pos = getLatLng();		
			// }, 100 );
		</script>


<?php

	// echo $_POST['getlat'];
	// 設定項目
	$access_token = '20917341.6fe37bd.53d5a385dd71465e8ef198164bb4195f';
	$client_id = '6fe37bddac50435e8246367988130e35' ;		// クライアントID
	$client_secret = 'a6af927ee4584d2e9c7c406f1e8d973b' ;		// クライアントシークレット
	$redirect_uri = explode( '?' , ( !isset($_SERVER['HTTPS']) || empty($_SERVER['HTTPS']) ? 'http://' : 'https://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] )[0] ;		// このプログラムを設置するURL
	$scope = 'public_content+follower_list' ;	
	$request_url = 'https://api.instagram.com/v1/locations/search?lat=36.701226&lng=137.21319&access_token=' . $access_token ;		// リクエストURL



	if( isset( $_GET['code'] ) && !empty( $_GET['code'] )
		 && isset( $_SESSION['state'] ) && !empty( $_SESSION['state'])
		 && isset( $_GET['state'] ) && !empty($_GET['state'])
		 && $_SESSION['state'] == $_GET['state'] ){
		//リクエスト用のコンテキスト

		$context = array( 
			'http' => array(
				'method' => 'POST',
				'content' => http_build_query( array(
					'client_id' => $client_id,
					'client_secret' => $client_secret,
					'grant_type' => 'authorization_code',
					'redirect_uri' => $redirect_uri,
					'code' => $_GET[ 'code' ]
				) )
			)
		);

		// アイテムデータをJSON形式で取得する (CURLを使用)
		$curl = curl_init() ;


		// オプションのセット
		curl_setopt( $curl , CURLOPT_URL , 'https://api.instagram.com/oauth/access_token' ) ;
		curl_setopt( $curl , CURLOPT_HEADER, 1 ) ; 
		curl_setopt( $curl , CURLOPT_CUSTOMREQUEST , $context['http']['method'] ) ;			// メソッド
		curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , false ) ;								// 証明書の検証を行わない
		curl_setopt( $curl , CURLOPT_RETURNTRANSFER , true ) ;								// curl_execの結果を文字列で返す
		curl_setopt( $curl , CURLOPT_POSTFIELDS , $context['http']['content'] ) ;			// リクエストボディ
		curl_setopt( $curl , CURLOPT_TIMEOUT , 5 ) ;										// タイムアウトの秒数

		$res1 = curl_exec( $curl );
		$res2 = curl_getinfo( $curl );

		curl_close( $curl );

		$json = substr( $res1, $res2['header_size'] );
		$header = substr( $res1, 0, $res2['header_size'] );
		$obj = json_decode( $json );

		// エラー判定
		if( !$obj || !isset($obj->user->id) || !isset($obj->user->username) || !isset($obj->user->profile_picture) || !isset($obj->access_token) ){
			$error = 'データを上手く取得できませんでした…。' ;
		} else {
			// 各データを整理
			$user_id = $obj->user->id ;		// ユーザーID
			$user_name = $obj->user->username ;		// ユーザーネーム
			$user_picture = $obj->user->profile_picture ;		// ユーザーアイコン
			$access_token = $obj->access_token ;		// アクセストークン

			// セッション終了
			$_SESSION = array() ;
			session_destroy() ;

			// 出力する
			$html .=  '<h2>実行結果</h2>' ;
			$html .=  '<dl>' ;
			$html .=  	'<dt>ユーザーID</dt>' ;
			$html .=  		'<dd>' . $user_id . '</dd>' ;
			$html .=  	'<dt>ユーザー名</dt>' ;
			$html .=  		'<dd>' . $user_name . '</dd>' ;
			$html .=  	'<dt>アイコン画像</dt>' ;
			$html .=  		'<dd><img class="_img" src="' . $user_picture . '"></dd>' ;
			$html .=  	'<dt>アクセストークン</dt>' ;
			$html .=  		'<dd>' . $access_token . '</dd>' ;
			$html .=  '</dl>' ;
		}

		// 取得したデータ
		$html .= '<h2>取得したデータ</h2>' ;
		$html .= '<p>下記のデータを取得できました。</p>' ;
		$html .= 	'<h3>JSON</h3>' ;
		$html .= 	'<p><textarea rows="8">' . $json . '</textarea></p>' ;
		$html .= 	'<h3>レスポンスヘッダー</h3>' ;
		$html .= 	'<p><textarea rows="8">' . $header . '</textarea></p>' ;

	}else{

	$curl = curl_init();


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

	// JSONデータをオブジェクト形式に変換する
	$obj = json_decode( $json ) ;

	// HTMLを作成
	$html .= '<h2>実行結果</h2>' ;

	// エラー判定
	if( !$obj || !isset($obj->data) ){
		$html .= '<p>データを取得できませんでした…。設定を再確認して下さい。</p>' ;
	} else {
		// 解析する
		foreach( $obj->data as $item ){
			// 各データの整理
			$id = $item->id ;		// 場所ID
			$name = $item->name ;		// 場所名
			$latitude = $item->latitude ;		// 緯度
			$longitude = $item->longitude ;		// 経度

			// ブラウザに出力
			$html .= '<dl>' ;
			$html .= 	'<dt>場所ID</dt>' ;
			$html .= 		'<dd>' . $id . '</dd>' ;
			$html .= 	'<dt>場所名</dt>' ;
			$html .= 		'<dd>' . $name . '</dd>' ;
			$html .= 	'<dt>緯度</dt>' ;
			$html .= 		'<dd>' . $latitude . '</dd>' ;
			$html .= 	'<dt>経度</dt>' ;
			$html .= 		'<dd>' . $longitude . '</dd>' ;
			$html .= 	'<dt>地図</dt>' ;
			$html .= 		'<dd><a href="https://www.google.co.jp/maps/@' . $latitude . ',' . $longitude . ',15z" target="_blank">Google Mapsで位置を確認する</a></dd>' ;
			$html .= '</dl>' ;
		}
	}

	// 取得したデータ
	$html .= '<h2>取得したデータ</h2>' ;
	$html .= '<p>下記のデータを取得できました。</p>' ;
	$html .= 	'<h3>JSON</h3>' ;
	$html .= 	'<p><textarea rows="8">' . $json . '</textarea></p>' ;
	$html .= 	'<h3>レスポンスヘッダー</h3>' ;
	$html .= 	'<p><textarea rows="8">' . $header . '</textarea></p>' ;

	// アプリケーション連携の解除
	$html .= '<h2>アプリケーション連携の解除</h2>' ;
	$html .= '<p>このアプリケーションとの連携は、下記設定ページで解除することができます。</p>' ;
	$html .= '<p><a href="https://instagram.com/accounts/manage_access/" target="_blank">https://instagram.com/accounts/manage_access/</a></p>' ;
}

?>



	</head>
<body>



<?php echo $html ?>


<p style="text-align:center"><a href="https://syncer.jp/instagram-api-matome">配布元: Syncer</a></p>






</body>
</html>

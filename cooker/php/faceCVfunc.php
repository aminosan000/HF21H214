<?php
function cvRequest($imageName){
	// APIキー
	$api_key = "AIzaSyBqptFpqbvbwCSuBTRjtDmt4HPLadRyk40" ;

	// リファラー (許可するリファラーを設定した場合)
	$referer = "https://gunmetal.sakura.ne.jp/hf21/" ;

	// 画像へのパス
	$image_path = "../../hf21/Images/Face/" . $imageName ;

	// リクエスト用のJSONを作成
	$json = json_encode( array(
		"requests" => array(
			array(
				"image" => array(
					"content" => base64_encode( file_get_contents( $image_path ) ) ,
				) ,
				"features" => array(
					array(
						"type" => "FACE_DETECTION" ,
						"maxResults" => 1 ,
					) ,/*
					array(
						"type" => "LANDMARK_DETECTION" ,
						"maxResults" => 3 ,
					) ,
					array(
						"type" => "LOGO_DETECTION" ,
						"maxResults" => 3 ,
					) ,
					array(
						"type" => "LABEL_DETECTION" ,
						"maxResults" => 10 ,
					) ,
					array(
						"type" => "TEXT_DETECTION" ,
						"maxResults" => 3 ,
					) ,
					array(
						"type" => "SAFE_SEARCH_DETECTION" ,
						"maxResults" => 3 ,
					) ,
					array(
						"type" => "IMAGE_PROPERTIES" ,
						"maxResults" => 3 ,
					) ,
					*/
				) ,
			) ,
		) ,
	) ) ;

	// リクエストを実行
	$curl = curl_init() ;
	curl_setopt( $curl, CURLOPT_URL, "https://vision.googleapis.com/v1/images:annotate?key=" . $api_key ) ;
	curl_setopt( $curl, CURLOPT_HEADER, true ) ; 
	curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "POST" ) ;
	curl_setopt( $curl, CURLOPT_HTTPHEADER, array( "Content-Type: application/json" ) ) ;
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false ) ;
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true ) ;
	if( isset($referer) && !empty($referer) ) curl_setopt( $curl, CURLOPT_REFERER, $referer ) ;
	curl_setopt( $curl, CURLOPT_TIMEOUT, 15 ) ;
	curl_setopt( $curl, CURLOPT_POSTFIELDS, $json ) ;
	$res1 = curl_exec( $curl ) ;
	$res2 = curl_getinfo( $curl ) ;
	curl_close( $curl ) ;

	// 取得したデータ
	$json = substr( $res1, $res2["header_size"] ) ;				// 取得したJSON
	$header = substr( $res1, 0, $res2["header_size"] ) ;		// レスポンスヘッダー
	$resArray = json_decode($json, true);
	$array = array();
	try{
		$joy = $resArray["responses"][0]["faceAnnotations"][0]["joyLikelihood"];
		$sorrow = $resArray["responses"][0]["faceAnnotations"][0]["sorrowLikelihood"];
		$array["joy"] = $joy;
		$array["sorrow"] = $sorrow;
	}catch(Exception $e){
		echo "error : " . $e;
	}
	return $array;
}
?>
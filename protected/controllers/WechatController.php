<?php

class SiteController extends Controller{
	public function actionLinkin($signature, $timestamp, $nonce, $echostr){

		$token = "laotangguan";
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
	
		if( $tmpStr == $signature ){
			return echo $echostr;
		}else{
			return false;
		}	
	}
}
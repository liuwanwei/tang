<?php

class WechatController extends Controller{

	public $layout = '//layouts/column2'; 

	/**
	 * @return array action filters
	 */
	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules(){
        return array(
            array(
                'allow',
                'actions' => array('linkin'),
                'users' => array('*'),
            ),                
        );
    }

	public function actionLinkin($signature = null, $timestamp = null, $nonce = null, $echostr = null){

		$token = "laotangguan";
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
	
		if( $tmpStr == $signature ){
			echo $echostr;
		}
	}


}
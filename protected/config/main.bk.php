<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Tang',
	'timeZone'=>'Asia/Chongqing',
	'language'=>'zh_cn',
	'defaultController' => 'restaurant',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'admin',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'class'=>'WebUser',
			'allowAutoLogin'=>true,
			'loginUrl'=>'/site/redirectLogin',
		),
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'rules'=>array(
				'county/<county:\d+>/*' => 'restaurant/index',
				'details/<restaurantId:\d+>' => 'comment/index',
				'logout' => 'site/logout',
				'up' => 'site/upversion',
				// '<controller:\w+>/<id:\d+>'=>'<controller>/view',
				// '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				// '<controller:\w+>/<action:\w+>/<county:\d+>/*'=>'<controller>/<action>/*',
			),
		),

		'cache'=>array(
            'class'=>'system.caching.CFileCache',
            'directoryLevel'=> 2,
		),

		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		
		
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=tang',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/redirectError',
		),

		'clientScript' => array(
        	'packages' => array(
            	'jquery' => array(
              		'baseUrl' => '//lib.sinaapp.com/js/jquery/1.8.3/',
              		'js' => array('jquery.min.js'),
            	),
            ),

            'scriptMap'=> array(
            	'jquery.ba-bbq.js'=> false,
            	'jquery.yiilistview.js'=> false
            )
        ),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'actionInterval'=>5,
	),
);
<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	/**
	 * 判断当前登陆用户是否是管理员
	 * @return boolean
	 */
	protected function isAdmin()
	{
		return Yii::app()->user->isAdmin;
	}

	/**
	*定义meta description的内容
	*/
	protected $pageDescription;
	
	/**
	*定义meta keyword的内容
	*/
	protected $pageKeyword;

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
				array('allow', // allow admin user to perform 'admin' and 'delete' actions
						'actions'=>array('admin','delete','create','update','index','view'),
						'expression'=>array($this,'isAdmin'),
				),
				array('deny',  // deny all users
						'users'=>array('*'),
				),
		);
	}
	
	public function importAdminLayout()
	{
		$this->layout='//layouts/column_admin';
	}


	public function checkActionFrequency(){
		$user = User::model()->findByPk(Yii::app()->user->id);
		if ($user !== null) {
			$now = time();
			$last = strtotime($user->last_action_time);
			return $now  - $last - Yii::app()->params['actionInterval'];
		}

		return -Yii::app()->params['actionInterval'];
	}

	public function updateLastActionTime(){
		$user = User::model()->findByPk(Yii::app()->user->id);
		$user->last_action_time = new CDbExpression('NOW()');
		$user->save();
	}

	public function redirectPrompt($code = 0, $message = '', $url = '') {
		$url = empty($url) ? $this->createUrl('/'.Yii::app()->defaultController) : $url;
		
		return $this->render('/site/redirect', array(
			'code'=> $code,
        	'message'=> $message,
        	'url'=> $url,
        	'delay'=> DELAY,
      	));
	}

	public function clearCacheFile($expire) {
		Yii::app()->cache->gc($expire);
	}

	private function encodeJsonData($data) {
		return json_encode($data);
	}

	public function makeResultMessage($code = 0, $msg = '', $others = array(), $json = true) {
		$baseData = array('code'=>$code, 'msg'=>$msg);
		$result = array_merge($baseData, $others);
		if ($json) {
			return $this->encodeJsonData($result);
		}
		
		return $result;
	}

	public function makeDataMessage($code = 0, $msg = '', $data = array()) {
		$result = array('code'=>$code, 'msg'=>$msg, 'data'=> $data);
		
		return CJSON::encode($result);
	}

	/** 移除组件默认加载的JS
	* $script js文件名
	*/
	protected function removeDefaultJS($script) {
		$scriptMap = Yii::app()->clientScript->scriptMap;
		if (is_array($scriptMap) !== true) {
			$scriptMap = array();
		}

		$scriptMap[$script] = false;
		
		Yii::app()->clientScript->scriptMap = $scriptMap;
	}
}

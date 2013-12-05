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
		return User::isAdmin();
	}
	
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
	
	public function actionAdmin()
	{
		$this->layout='//layouts/column_admin';
	}


	public function checkActionFrequency(){
		$user = User::model()->findByPk(Yii::app()->user->id);
		if ($user !== null) {
			$now = time();
			$last = strtotime($user->last_action_time);
			if (($now  - $last) > Yii::app()->params['actionInterval']) {
				return true;
			}
		}

		return false;
	}

	public function updateLastActionTime(){
		$user = User::model()->findByPk(Yii::app()->user->id);
		$user->last_action_time = new CDbExpression('NOW()');
		$user->save();
	}

}
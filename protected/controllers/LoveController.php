<?php

class LoveController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column_admin';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			// 'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function accessRules()
	{
		
		return array(
				// array('allow',
				// 	'actions'=>array('calculateRank'),
				// 	'users'=>array('*'),
				// ),
				array('allow',
					'actions'=>array('love'),
					'users'=>array('@')
				),
				// array('allow',
				// 	'actions'=>array('flush'),
				// 	'expression'=>array($this,'isAdmin'),
				// ),
				array('deny',  // deny all users
						'users'=>array('*'),
				),
		);
	}

	/**
	 * 查询“赞”总数。
	 */

	/**
	 * 添加一个“赞”。
	 * 参数：
	 *    $type: integer 餐馆1，评论2。
	 *    $id:   integer 赞的对象的id。
	 * 返回值：
	 *    TODO: 用前端约定好的ajax方式。
	 */
	public function actionLove($type, $id){
		$model=new Love;
		$model->target_type = $type;
		$model->target_id = $id;
		$model->user_id = Yii::app()->user->id;
		if ($model->validate()) {
			$criteria = new CDbCriteria();
			$criteria->compare('target_type', $type);
			$criteria->compare('target_id', $id);
			$criteria->compare('user_id', $model->user_id);
			$oldLove = $model->find($criteria);

			if (empty($oldLove)) {
				// 如果当前用户没有赞过，就添加一条赞记录。
				$model->save();	
				$others = array('type'=>'create');
			}else{
				// 如果当前用户已经赞过，就删除这条赞记录。
				$oldLove->delete();
				$others = array('type'=>'cancel');
			}

			echo $this->makeResultMessage(SUCCESS_CODE,SUCCESS_CODE_LOVE_OK, $others);
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Love the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Love::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Love $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='area-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

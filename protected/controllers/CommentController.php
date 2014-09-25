	<?php

class CommentController extends Controller
{	
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				array('allow',
						'actions'=>array('index'),
						'users'=>array('*')
				),
				array('allow',
						'actions'=>array('create'),
						'users'=>array('@')
				),
				array('allow', // allow admin user to perform 'admin' and 'delete' actions
						'actions'=>array('admin','delete','update','view'),
						'expression'=>array($this,'isAdmin'),
				),
				array('deny',  // deny all users
						'users'=>array('*'),
				),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($restaurantId) {	
		$url = $this->createUrl('comment/index', array('restaurantId' => $restaurantId));

		if(isset($_POST['Comment'])) {

			if($this->checkActionFrequency() >= 0) {
				$model = new Comment;
				$model->attributes=$_POST['Comment'];
				$model->restaurant_id=$restaurantId;
				$model->save();

				// 更新最后操作时间戳。
				$this->updateLastActionTime();	
				//清空所有缓存文件,为了返回首页时及时显示最新评论
				$this->clearCacheFile(false);
			}else {
				$this->redirectPrompt(ERROR_CODE_FREQUENCY,ERROR_CODE_MESSAGE_FREQUENCY,$url);
				return;
			}
		}

		$this->redirect($url);		
	}
	

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Comment']))
		{
			$model->attributes=$_POST['Comment'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		// 对应的评论不存在时，$this->loadModeel($id)会直接抛出异常。
		$model = $this->loadModel($id);
		if ($model) {
			$model->hidden = true;
			$model->save();
			//清空所有缓存文件
			$this->clearCacheFile(false);
			
			$this->redirect($this->createUrl('comment/index', array('restaurantId' => $model->restaurant_id)));
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * 展示餐馆详情和餐馆评论详情列表。
	 */
	public function actionIndex($restaurantId) {
		// 餐馆访问次数+1；
		$restaurant = Restaurant::model()->findByPk($restaurantId);
		if ($restaurant) {
			$restaurant->visits += 1;
			$restaurant->save();
		}

		$criteria = new CDbCriteria(array(
				'condition'=>'restaurant_id='.$restaurantId . ' AND hidden=0',
				'order'=>'create_datetime DESC',
		));

		$comments = new CActiveDataProvider('Comment',array(
			'criteria'=>$criteria,
		));
		$comments->pagination->pageSize = $comments->totalItemCount;
		
		$restaurant = Restaurant::model()->findByPk($restaurantId);

		$imgs = RestaurantImages::model()->findAllByAttributes(array('restaurant_id' => $restaurantId));
		 
		$this->render('index',array(
			'restaurant' => $restaurant,		// 餐馆详情。
			'dataProvider' => $comments,	// 评论详情。
			'model' => new Comment, 			//Yii CActiveForm需要使用Comment对象
			'imgs' => $imgs
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		parent::importAdminLayout();
		
		$model=new Comment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Comment']))
			$model->attributes=$_GET['Comment'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Comment the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Comment::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'评论对象不存在');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Comment $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

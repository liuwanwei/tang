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
						'actions'=>array('index','create'),
						'users'=>array('*')
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
	public function actionCreate($restaurant_id)
	{
		$model=new Comment;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		// echo $_POST["json"];
		if(isset($_POST['Comment']))
		{ 

			if ($this->checkActionFrequency()) {
				$model->attributes=$_POST['Comment'];
				$model->restaurant_id=$restaurant_id;
				if($model->save()){
					//$this->updateLastActionTime();
					if (isset($_POST['json']) && $_POST['json']=='1') {
						echo json_encode(array('code'=>"0",'msg' =>"评论成功"));
					}else{
						$this->redirect(array('view','id'=>$model->id));
					}
				}
			}else{
				// TODO 显示投票/评论频率过高警告页面。
			}
		}

		// $this->render('create',array(
		// 	'model'=>$model,
		// ));
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
		$model = $this->loadModel($id);
		if ($model) {
			$model->hidden = true;
			$model->save();
			//清空所有缓存文件
			$this->clearCacheFile(false);
			
			$this->redirect($this->createUrl('comment/index', array('restaurantId' => $model->restaurant_id)));
		}else{
			// TODO: 评论不存在，显示错误信息。
			die('');
		}

		// $this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($restaurantId) {
		$model = new Comment;
		
		if(isset($_POST['Comment'])) {

			if($this->checkActionFrequency()) {
				$model->attributes=$_POST['Comment'];
				$model->restaurant_id=$restaurantId;
				$model->save();

				// 更新最后操作时间戳。
				$this->updateLastActionTime();	
				//清空所有缓存文件
				$this->clearCacheFile(false);
			}else {
				$url = Yii::app()->request->url;
				$this->redirectPrompt(ERROR_CODE_FREQUENCY,ERROR_CODE_FREQUENCY_MESSAGE,$url);
				return;
			}
		}
		
		$criteria=new CDbCriteria(array(
				'condition'=>'restaurant_id='.$restaurantId . ' AND hidden=0',
				'order'=>'create_datetime DESC',
		));

		$dataProvider=new CActiveDataProvider('Comment',array(
			'criteria'=>$criteria,
		));
		$dataProvider->pagination->pageSize = $dataProvider->totalItemCount;
		
		$restaurant = Restaurant::model()->findByPk($restaurantId);
		 
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'model'=>$model,
			'restaurant'=>$restaurant,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		parent::actionAdmin();
		
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
			throw new CHttpException(404,'The requested page does not exist.');
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

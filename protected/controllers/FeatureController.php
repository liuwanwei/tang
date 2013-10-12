<?php

class FeatureController extends Controller
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
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
public function accessRules()
	{
		
		return array(
				array('allow',
						'actions'=>array('index'),
						'users'=>array('*')),
				array('allow', // allow admin user to perform 'admin' and 'delete' actions
						'actions'=>array('admin','delete','create','update','view','AddRestaurantFeature'),
						'expression'=>array($this,'isAdmin'),
				),
				array('deny',  // deny all users
						'users'=>array('*'),
				),
		);
	}

	public function actionQuery($restaurantId){
		$models = array();
		if (! empty($restaurantId)) {
			$dataProvider = new CActiveDataProvider('Feature');
			$criteria = new CDbCriteria();
			$criteria->compare('restaurant_id', $restaurantId);
			$dataProvider->criteria = $criteria;
			$models = $dataProvider->getData();

			$results = array();
			foreach ($models as $key => $model) {
				$results[] = array('id'=>$model->id, 
					'restaurant_id'=>$model->restaurant_id, 
					'feature_id' => $model->feature_id,
					'feature_name' => $model->restaurantFeature->name);
			}

			echo json_encode($results);
		}
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
	public function actionCreate()
	{
		$model=new Feature;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Feature']))
		{
			$model->attributes=$_POST['Feature'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 首页提供为餐馆打标签功能
	 * 从$_POST[Feature]获取参数，格式如：1,2
	 */
	public function actionAddRestaurantFeature()
	{
		$result = true;
		if (isset($_POST('Feature')))
		{
			$feature = $_POST('Feature');
			$restaurant_id = $feature['restaurant_id'];
			if (isset($restaurant_id))
			{
				$this->actionDeleteRestaurantFeatures($restaurant_id);
				
				$features = $feature['features'];
				if (isset($feature))
				{
					$features_array = split(',', $features);
					foreach ($features_array as $value)
					{
						$model = new Feature;
						$model->restaurant_id = $restaurant_id;
						$model->feature_id = $value;
						
						$model->save();
					}
				}
				else
				{
					$result = false;
				}
			}
			else
			{
					$result = false;
			}
		}
		else 
		{
			$result = false;
		}
		
		if ($result)
		{
			echo json_encode(array('sue' =>"" ));
		}
		else
		{
			echo json_encode(array('error' =>"" ));
		}
	}
	
	private function actionDeleteRestaurantFeatures($restaurant_id)
	{
		Feature::model()->deleteAll('restaurant_id=:restaurant_id',array(':restaurant_id'=>$restaurant_id));
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

		if(isset($_POST['Feature']))
		{
			$model->attributes=$_POST['Feature'];
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Feature');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Feature('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Feature']))
			$model->attributes=$_GET['Feature'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Feature the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Feature::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Feature $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='feature-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

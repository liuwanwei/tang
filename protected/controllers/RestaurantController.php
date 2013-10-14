<?php

class RestaurantController extends Controller
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
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
	public function accessRules()
	{
		
		return array(
				array('allow',
						'actions'=>array('index'),
						'users'=>array('*')),
				array('allow', // allow admin user to perform 'admin' and 'delete' actions
						'actions'=>array('admin','delete','create','update','view'),
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
		parent::actionAdmin();
		
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	private function staticSelectors(){
		// 所有行政县、区数据，用于选择汤馆所在区域。
		$dataProvider = new CActiveDataProvider('County');
		$data = $dataProvider->getData();
		$counties = array();
		foreach ($data as $key => $value) {
			$counties[$value->id] = $value->name;
		}

		// 所有自定义区域数据，用于选择汤馆所在区域。
		$dataProvider = new CActiveDataProvider('Area');
		$data = $dataProvider->getData();
		$areas = array();
		foreach ($data as $key => $value) {
			$areas[$value->id] = $value->name;
		}

		$dataProvider = new CActiveDataProvider('RestaurantStatus');
		$data = $dataProvider->getData();
		$statuses = array();
		foreach ($data as $key => $value) {
			$statuses[$value->id] = $value->name;
		}

		$dataProvider = new CActiveDataProvider('RestaurantType');
		$data = $dataProvider->getData();
		$types = array();
		foreach ($data as $key => $value) {
			$types[$value->id] = $value->name;
		}

		return array('counties'=>$counties, 'areas'=>$areas, 'statuses'=>$statuses, 'types'=>$types);
	}

	private function urlImagePath($model, $extension){
		return '/images/restaurant/profile_'.$model->id.'.'.$extension;
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		parent::actionAdmin();
		
		$model=new Restaurant;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Restaurant']))
		{
			$model->attributes=$_POST['Restaurant'];

			// 获取汤馆图片的地址，转换成服务器存储路径。
			$uploadedFile = CUploadedFile::getInstance($model, 'image_url');
			if (!empty($uploadedFile)) {
				$extension = $uploadedFile->getExtensionName();
				$filename = $this->urlImagePath($model, $extension);
				$model->image_url = $filename;
			}

			if($model->save())
				if (isset($filename)) {
					// 保存汤馆图片到服务器存储路径。
					$uploadedFile->saveAs(Yii::app()->basePath.'/..'.$filename);
				}
				
				$this->redirect(array('view','id'=>$model->id));
		}


		$this->render('create',array(
			'model'=>$model,
			'selectors'=>$this->staticSelectors(),
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		parent::actionAdmin();
		
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Restaurant']))
		{
			$model->attributes=$_POST['Restaurant'];

			$filename = '';
			$uploadedFile = CUploadedFile::getInstance($model, 'image_url');
			if (!empty($uploadedFile)) {
				$extension = $uploadedFile->getExtensionName();				
				$filename = $this->urlImagePath($model, $extension);
				$model->image_url = $filename;
			}
			
			if($model->save())

				if (!empty($uploadedFile)) {
					$destPath = Yii::app()->basePath.'/..'.$filename;
					// print_r($destPath);
					$uploadedFile->saveAs($destPath);
				}
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'selectors'=>$this->staticSelectors(),
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		parent::actionAdmin();
		
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	* 获取包含所有行政区的菜单。菜单的格式要符合zii::widgets::CMenu::items的形式要求。
	*/
	private function countyMenu(){
		$selectors = $this->staticSelectors();
		$counties = $selectors['counties'];

		$menuItems = array();
		foreach ($counties as $key => $value) {
			// var_dump($value);
			$menuItems[] = array('label' => $value, 'url' => array('/restaurant/index&county='.$key));
		}

		return $menuItems;
	}

	private function areaMenu($countyId){
		
		if ($countyId == 0) {
			return null;
		}
		
		$county = County::model()->findByPk($countyId);

		$restaurantAreaDataProvider = new CActiveDataProvider('Restaurant');

		$criteria = new CDbCriteria();
		$criteria->compare('county_id', $countyId);
		$criteria->select = array('area_id');
		$criteria->group = 'area_id';
		
		$restaurantAreaDataProvider->criteria = $criteria;
		
		$restaurants = $restaurantAreaDataProvider->getData();
		
		if (!isset($restaurants) && count($restaurants) === 0)
		{
			return null;
		}
		
		$areasId = array();
		foreach ($restaurants as $key => $value)
		{
			$areasId[] = $value->area_id;
		}
		
		$dataProvider = new CActiveDataProvider('Area');
		if ($countyId !== 0) {
			$criteria = new CDbCriteria();
// 			$criteria->compare('county_id', $countyId);
			$criteria->addInCondition('id', $areasId);
			$criteria->order = 'id desc';
			$dataProvider->criteria = $criteria;
		}

		$data = $dataProvider->getData();

		$baseUrl = '/restaurant/index&county='.$countyId;

		$menuItems = array();
		// 人为加入“全部”按钮后，区域选择界面也要改为区域总数大于1时再显示。
// 		if (count($restaurants) > 0) {
			$menuItems[] = array('label'=>$county->name, 'url'=>array($baseUrl));
// 		}
	
		foreach ($data as $key => $value) {
			$menuItems[] = array('label' => $value->name, 'url' => array($baseUrl.'&area='.$value->id));
		}

		return $menuItems;
	}

	private function typeMenu($countyId, $areaId){
		$restaurantAreaDataProvider = new CActiveDataProvider('Restaurant');
		$criteria = new CDbCriteria();
		$criteria->select = array('type_id');
		$criteria->group = 'type_id';
		
		if ($countyId != 0) {
			$criteria->compare('county_id', $countyId);
		}
		
		if ($areaId != 0) {
			$criteria->compare('area_id', $areaId);
		}
		
		$restaurantAreaDataProvider->criteria = $criteria;
		
		$restaurants = $restaurantAreaDataProvider->getData();
		
		if (!isset($restaurants) && count($restaurants) === 0)
		{
			return null;
		}
		
		$typesId = array();
		foreach ($restaurants as $key => $value)
		{
			$typesId[] = $value->type_id;
		}
		
		$dataProvider = new CActiveDataProvider('RestaurantType');
		$criteria = new CDbCriteria();
		$criteria->compare('id', '<>0');
		$criteria->addInCondition('id', $typesId);
		$dataProvider->criteria = $criteria;

		$data = $dataProvider->getData();
		$menuItems = array();
		$baseUrl = '/restaurant/index&county='.$countyId.'&area='.$areaId;
		$menuItems[] = array('label'=>'全部', 'url' => array($baseUrl));
		foreach ($data as $key => $value) {
			$menuItems[] = array('label'=>$value->name, 'url' => array($baseUrl.'&type='.$value->id));
		}

		return $menuItems;
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($county = 0, $area = 0, $type = 0)
	{
		// $rankFilePath = Yii::app()->basePath . "/../ranks.db";
		// $ranks = unserialize($rankFilePath);

		$dataProvider=new CActiveDataProvider('Restaurant');

		$criteria = new CDbCriteria();
		$criteria->order = 'weighted_points DESC';

		if (!empty($county)) {
			$criteria->compare('county_id', $county);
		}

		if (! empty($area)) {
			$criteria->compare('area_id', $area);
		}

		if (! empty($type)) {
			$criteria->compare('type_id', $type);
		}

		$dataProvider->criteria = $criteria;

		$data = $dataProvider->getData();
		$this->render('index',array(
			'dataProvider'=>$dataProvider, 
// 			'countyMenu'=>$this->countyMenu(),
			'areaMenu'=>$this->areaMenu($county),
			'typeMenu'=>$this->typeMenu($county, $area),
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		parent::actionAdmin();
		
		$model=new Restaurant('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Restaurant']))
			$model->attributes=$_GET['Restaurant'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Restaurant the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Restaurant::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Restaurant $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='restaurant-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

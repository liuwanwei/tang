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
			// 'postOnly + delete', // we only allow deletion via POST request
			array(
				'COutputCache + index + indexByPage',
				'duration'=>3600,
				'varyByParam'=>array('county','area','type'),
				'varyByExpression'=>Yii::app()->user->id
			),
		);
	}
	
	public function accessRules()
	{
		
		return array(
				array('allow',
						'actions'=>array('index', 'view', 'indexByPage', 'search'),
						'users'=>array('*')),
				array('allow',
						'actions'=>array('create', 'delete', 'update'),
						'users'=>array('@'),
				),
				array('allow', // allow admin user to perform 'admin' action.
						'actions'=>array('admin','view', 'check'),
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
		parent::importAdminLayout();
		
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

	private function randomFilename() {
		$length = 6;
        $str = '';
        for($i = 0; $i < $length; $i++) {
            $str .= mt_rand(0, $length);
        }

        return $str;
    }

	private function createImagePathWithExtension($extension){
		$datePart = date("Y") . '/' . date("m") . '/' . date("d");

		// TODO: 部署时，要想办法检查Web服务器是否对图片目录有访问权限。
		$destDir = '/images/profile/' . $datePart . '/';
		return $destDir . $this->randomFilename().'.'.$extension;
	}

	private function saveImage($uploadedFile, $filename){
		$destFile = Yii::app()->basePath.'/..'.$filename;
		$destPath = dirname($destFile);
		
		// 创建图片子目录。
		if (!file_exists($destPath)) {
			if(false === mkdir($destPath, 0755, true)){
				throw new CHttpException(403, '没有图片目录操作权限 ');
			}		
		}

		$uploadedFile->saveAs($destFile);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		parent::importAdminLayout();
		
		$model=new Restaurant;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Restaurant']))
		{
			$model->attributes=$_POST['Restaurant'];
			$model->creator = Yii::app()->user->id;

			// 获取汤馆图片的地址，转换成服务器存储路径。
			$uploadedFile = CUploadedFile::getInstance($model, 'image_url');
			if (!empty($uploadedFile)) {
				$extension = $uploadedFile->getExtensionName();
				$filename = $this->createImagePathWithExtension($extension);
				$model->image_url = $filename;
			}

			if($model->save()) {
				if (isset($filename)) {
					// 保存汤馆图片到服务器存储路径。
					$this->saveImage($uploadedFile, $filename);					
				}
				
				//清空所有缓存文件，让用户添加的餐馆能显示在首页
				if ($model->is_checked == 1) {
					$this->clearCacheFile(false);	
				}
				
				if (isset($_POST['Json'])){
					echo $this->makeResultMessage(SUCCESS_CODE,SUCCESS_CODE_MESSAGE_RESTAURANT_CREATE);
					return;
				}else {
					$this->redirect("/site/userCenter");
				}
			}
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
		parent::importAdminLayout();
		
		$model=$this->loadModel($id);

		if(isset($_POST['Restaurant'])) {
			$model->attributes=$_POST['Restaurant'];

			$uploadedFile = CUploadedFile::getInstance($model, 'image_url');
			if (!empty($uploadedFile)) {
				$extension = $uploadedFile->getExtensionName();				
				$filename = $this->createImagePathWithExtension($extension);
				$model->image_url = $filename;
			}
			
			if($model->save())
				if (isset($filename)) {
					// 保存汤馆图片到服务器存储路径。
					$this->saveImage($uploadedFile, $filename);
				}
				
				//清空所有缓存文件，让用户添加的餐馆能显示在首页
				if ($model->is_checked == 1) {
					$this->clearCacheFile(false);	
				}
			
				$this->redirect($_POST['returnUrl']);
		}

		$this->render('update',array(
			'model'=>$model,
			'selectors'=>$this->staticSelectors(),
			'returnUrl'=>Yii::app()->request->urlReferrer // 将上页地址传递给前端界面，用于保存成功后返回对应的上页界面
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id){	
		parent::importAdminLayout();		

		$model = $this->loadModel($id);

		// 只有管理员或者汤馆创建者才能删除汤馆。
		if ($model->creator  == Yii::app()->user->id || 
			parent::isAdmin()) {
			
			$model->delete();

			// 删除汤馆图片。
			if (! empty($model->image_url)) {
				$imagePath = Yii::app()->basePath . '/..' . $model->image_url;
				if (file_exists($imagePath)) {
					if (false === unlink($imagePath)) {
						throw new CHttpException(403, '没有图片目录访问权限');
					}
				}
			}

			//清空所有缓存文件
			$this->clearCacheFile(false);
		}else{
			throw new CHttpException(403,'没有访问操作权限');
		}
	}

	/**
	* 获取包含所有行政区的菜单。菜单的格式要符合zii::widgets::CMenu::items的形式要求。
	*/
	private function countyMenu(){
		$selectors = $this->staticSelectors();
		$counties = $selectors['counties'];

		$menuItems = array();
		foreach ($counties as $key => $value) {
			$url =  $this->createUrl('restaurant/index', array('county'=>$key));
			$menuItems[] = array('label' => $value, 'url' => $url);
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
			$criteria->addInCondition('id', $areasId);
			$criteria->order = 'id desc';
			$dataProvider->criteria = $criteria;
		}

		$data = $dataProvider->getData();
		$menuItems = array();

		// 人为加入“全部”按钮后，区域选择界面也要改为区域总数大于1时再显示。
		$menuItems[] = array('label'=>'全部', 'url'=>array($this->createUrl('restaurant/index'), 'county' => $countyId));
	
		foreach ($data as $key => $value) {
			$menuItems[] = array('label' => $value->name, 'url' => array($this->createUrl('restaurant/index'), 'county'=>$countyId,'area'=>$value->id));
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
		
		if ($areaId != -1) {
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
		$urlParams = array('county'=>$countyId, 'area'=>$areaId);

		$menuItems[] = array('label'=>'全部', 'url' => $this->createUrl('restaurant/index', $urlParams));
		foreach ($data as $key => $value) {
			$urlParams['type'] = $value->id;
			$menuItems[] = array('label'=>$value->name, 'url' => $this->createUrl('restaurant/index', $urlParams));
		}

		return $menuItems;
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($county = 0, $area = -1, $type = 0)
	{		 
        $this->removeDefaultJS('jquery.yiilistview.js');
        $this->removeDefaultJS('jquery.ba-bbq.js');

		$restaurant = new Restaurant();
		$restaurant->county_id = $county;
		$restaurant->area_id = $area;
		$restaurant->type_id = $type;
	
		$restaurantProvider = $restaurant->indexByPage();
	
		$this->render('index',array(
			'dataProvider'=>$restaurantProvider, 
			'count'=>$restaurantProvider->getTotalItemCount(),
			'county'=>$county,
			'area'=>$area,
			'type'=>$type,
			'areaMenu'=>$this->areaMenu($county),
			'typeMenu'=>$this->typeMenu($county, $area),
			'lastVotes'=>Vote::model()->getLastVotes(),
			'lastComments'=>Comment::model()->getLastComments(5),
		));
	}

	/**
	 * 汤馆审核功能-从导航栏”个人中心“进入。只有管理员才有这个子菜单，通过accessRules限制非管理员进入。
	 */
	public function actionAdmin(){
		parent::importAdminLayout();
		
		$model=new Restaurant('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Restaurant']))
			$model->attributes=$_GET['Restaurant'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}


	/*
	 * 根据关键词查询功能入口。
	 */
	public function actionSearch(){
		parent::importAdminLayout();

		$model = new Restaurant('search');
		$model->unsetAttributes();
		if (isset($_GET['Restaurant'])) {
			$model->attributes = $_GET['Restaurant'];
		}

		$this->render('admin', array(
			'model'=>$model,
		));
	}

	/*
	 * 进入”审核汤馆“界面，仅列出未审核的汤馆。
	 */
	public function actionCheck(){
		if (! parent::isAdmin()) {			
			throw new CHttpException(403,'用户没有操作权限');
		}

		parent::importAdminLayout();
		
		$model=new Restaurant('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Restaurant']))
			$model->attributes=$_GET['Restaurant'];

		$this->render('check', array(
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

	/**
	* 根据县区、区域、汤类型、页码不同的条件，返回相关的餐馆数据
	* @param  $county:县区Id；$area:区域Id；$type:汤类型; $page:当前页号(从0开始); $limit：每页显示的数量
	* @return 餐馆数组
	*/
	public function actionIndexByPage($county = 0, $area = -1, $type = 0, $page = 0, $limit = 10) {
		$restaurant = new Restaurant();
		$restaurant->county_id = $county;
		$restaurant->area_id = $area;
		$restaurant->type_id = $type;

		$restaurantsProviver = $restaurant->indexByPage($page,$limit);
		$restaurants = $restaurantsProviver->getData();
		$totalItemCount = $restaurantsProviver->getTotalItemCount();

		$result = array();
		if ($page * $limit <= $totalItemCount) {
			foreach ($restaurants as $key => $value) {
				$data = array();
				$data['restaurant'] = $value;
				if(count($value->features) > 0) {
					foreach($value->features as $keyFeature => $valueFeature)
					$data['features'][] =  $valueFeature->details;
				}
			
				$result[] = $data;
			}
		}

		echo CJSON::encode($result);
	}
}

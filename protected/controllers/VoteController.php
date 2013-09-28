<?php

class VoteController extends Controller
{
	private $_get_new_vote_key 	= 'get_new_vote';
	private $_new_vote_exist 	= '1';
	private $_new_vote_not_exist 	= '0';


	public function actionCalculateRank(){
		// 计算所有店铺平均分的算术平均值。

		$model = Setting::model()->findByPk($this->_get_new_vote_key);
		if ($model->value === $this->_new_vote_not_exist) {
			die('Data not changed yet!');
		}

		$dataProvider = new CActiveDataProvider('Restaurant');
		$records = $dataProvider->getData();
		$totalPoints = 0;
		foreach ($records as $key => $value) {
			$totalPoints += $value->average_points;
		}

		$totalAveragePoints = $totalPoints / count($records);

		// 计算每个店铺的权重得分。
		$ranks = array();
		$minVotes = 10;
		foreach ($records as $key => $value) {
			$weightedPoints = ($value->votes  / ($value->votes + $minVotes)) * $value->average_points + 
						($minVotes  / ($value->votes + $minVotes)) * $totalAveragePoints;
			$value->weighted_points = $weightedPoints;
			$value->save();
		}


		$model->value = $this->_new_vote_not_exist;
		$model->save();

		die('Update rank success!');
	}


	// 更新餐厅记录，由此引发餐厅排名的重新计算。
	private function updateRestaurant($model){
		$rating = $model->rating;

		$restaurant = Restaurant::model()->findByPk($model->restaurant_id);

		$oldVotes = $restaurant->votes;
		$oldAveragePoints = $restaurant->average_points;

		if (empty($model->oldRating)) {
			// 新的投票。
			$averagePoints = ($oldVotes * $oldAveragePoints + $model->rating ) / ($oldVotes + 1);
			$restaurant->votes = $oldVotes + 1;
		}else{
			// 更新旧投票。
			$averagePoints = ($oldVotes * $oldAveragePoints - $model->oldRating + $model->rating) / $oldVotes;
		}
		
		$restaurant->average_points = $averagePoints;

		if(! $restaurant->save()){
			die('save restaurant record faild: '.$restaurant->getErrors());
		}else{
			$model = Setting::model()->findByPk($this->_get_new_vote_key);
			$model->value = $this->_new_vote_exist;
			$model->save();
		}
	}

	private function saveVoteRecord($model){
		$criteria = new CDbCriteria();
		$criteria->compare('user_id', $model->attributes['user_id']);
		$criteria->compare('restaurant_id', $model->attributes['restaurant_id']);

		$oldModel = $model->find($criteria);

		if ($oldModel != null) {
			$oldModel->oldRating = $oldModel->rating;
			$oldModel->rating = $model->attributes['rating'];
			$oldModel->save();
			return $oldModel;
		}else{
			$model->save();
			return $model;
		}
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionCreate()
	{
		
		   	$model=new Vote;

	   	 if(isset($_POST['Vote']))
		{

		    $model->attributes=$_POST['Vote'];
		    if($model->validate())
		    {
		    	// 保存本次投票记录。
		    	$model = $this->saveVoteRecord($model);

		    	// 更新餐厅记录。
		    	$this->updateRestaurant($model);

		    	// 更新所有记录，包括餐厅排序。
		    	// $this->actionCalculateRank();

		        	// 重定向到餐厅列表。
		        	echo json_encode(array('msg' =>"0" ));

		        	return;
		    }
	    	}
	    	echo json_encode(array('msg' =>"1" ));
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
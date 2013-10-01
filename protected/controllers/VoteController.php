<?php

class VoteController extends Controller
{
	private $_get_new_vote_key 	= 'get_new_vote';
	private $_new_vote_exist 	= '1';
	private $_new_vote_not_exist 	= '0';

	// 更新（计算）所有参观的权重排名。
	public function actionCalculateRank(){
		// 获取“计算标记”。
		$model = Setting::model()->findByPk($this->_get_new_vote_key);
		if ($model->value === $this->_new_vote_not_exist) {
			die('Data not changed yet!');
		}

		// 计算所有店铺平均分的算术平均值。
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

		// 清除“计算标记”。
		$this->setCalculateRankFlag(false);

		die('Update rank success!');
	}

	private function setCalculateRankFlag($flag){
		$model = Setting::model()->findByPk($this->_get_new_vote_key);

		$value = $flag == true ? $this->_new_vote_exist : $this->_new_vote_not_exist;
		$model->value = $value;
		$model->save();
	}


	// 更新餐厅记录，由此引发餐厅排名的重新计算。
	private function updateRestaurant($model){
		$rating = $model->rating;

		$restaurant = Restaurant::model()->findByPk($model->restaurant_id);

		$oldVotes = $restaurant->votes;
		$oldAveragePoints = $restaurant->average_points;

		if (! isset($model->oldRating)) {
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
			$this->setCalculateRankFlag(true);
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

		        	// 提交成功向前台输出JSON。
		        	echo json_encode(array('msg' =>"0",'voteid'=>$model->id));
		        	return;
		    }
	    	}
	    	echo json_encode(array('msg' =>"1" ));
	}

	/**
	* 测试用投票接口。正式投票使用voteCreate接口.
	*/
	public function actionVote(){
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
		    }
	    	}
	    	
	    	$this->render('create', array('model' => $model));
	}

	// 删除餐馆的一个投票。
	private function removeVoteFromRestaurant($restaurantId, $rating){
		$calculateRank = false;
		$model = Restaurant::model()->findByPk($restaurantId);
		if (! empty($model)) {
			$totalPoints = $model->average_points * $model->votes;
			// die($totalPoints);
			if ($totalPoints != 0) {
				if ($totalPoints >= $rating) {
					$totalPoints -= $rating;
				}else{
					$totalPoints = 0;
				}

				if ($model->votes >= 1) {
					$model->votes --;
				}

				if ($model->votes === 0) {
					$model->average_points = 0;
				}else{
					$model->average_points = $totalPoints /  $model->votes;
				}

				$calculateRank = true;
			}else{
				// 数据紊乱。清零投票数。
				$model->votes = 0;
			}

			$model->save();

			if ($calculateRank === true) {
				// 需要重新计算所有餐馆的权重排名。
				$this->setCalculateRankFlag(true);
			}
		}
	}

	public function actionDelete(){
		$model=new Vote;

		if (isset($_POST['Vote'])) {
			// $model->attributes = $_POST['Vote'];
			$id = $_POST['Vote']['id'];
			$model = $this->loadModel($id);
			if (! empty($model)) {
				$rating = $model->rating;

				// 删除投票表中的记录。
				$model->delete();

				// 更新汤馆表中的平均分和投票总数。
				$this->removeVoteFromRestaurant($model->restaurant_id, $rating);
			}
			echo json_encode(array('msg' =>"0"));
			return;	
		}

		//$this->render('delete', array('model'=>$model));
		echo json_encode(array('msg' =>"1"));
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
		$model=Vote::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
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
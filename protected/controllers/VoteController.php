<?php

class VoteController extends Controller
{
	private $_max_rating_point		= 5;
	private $_get_new_vote_key 		= 'get_new_vote';
	private $_new_vote_exist 		= '1';
	private $_new_vote_not_exist 	= '0';

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
					'actions'=>array('calculateRank'),
					'users'=>array('*'),
				),
				array('allow',
					'actions'=>array('index', 'create', 'vote', 'delete', 'query'),
					'users'=>array('@')
				),
				array('allow',
					'actions'=>array('flush'),
					'expression'=>array($this,'isAdmin'),
				),
				array('deny',  // deny all users
						'users'=>array('*'),
				),
		);
	}

	// 更新（计算）所有餐厅的权重排名。
	public function actionCalculateRank(){
		// 获取“计算标记”。
		$model = Setting::model()->findByPk($this->_get_new_vote_key);
		if ($model->value === $this->_new_vote_not_exist) {
			die('Data not changed yet!');
		}

		// 计算所有店铺平均分的算术平均值。
		// 初始化CActiveDataProvider时要禁用分页，或使用Restaurant::model()->findAll().
		$dataProvider = new CActiveDataProvider('Restaurant', array('pagination'=>false));
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
			if ($value->votes > 0) {
				$weightedPoints = ($value->votes  / ($value->votes + $minVotes)) * $value->average_points + 
						($minVotes  / ($value->votes + $minVotes)) * $totalAveragePoints;
			}else{
				// 没有评分时，权重得分为0，排名垫底.
				$weightedPoints = 0;
			}	
				
			$value->weighted_points = $weightedPoints;			
			$value->save();
		}

		// 清除“计算标记”。
		$this->setCalculateRankFlag(false);

		die('Update rank success!');
	}

	public function setCalculateRankFlag($flag = true){
		$model = Setting::model()->findByPk($this->_get_new_vote_key);

		$value = $flag == true ? $this->_new_vote_exist : $this->_new_vote_not_exist;
		$model->value = $value;
		$model->save();
	}


	/* 更新餐厅平均得分（会引发餐厅排名的重新计算）。
	 * 分两种情况：
	 * 1，当前用户第一次对餐厅评分。此时将总得分加上新评分，总评分次数加一。最后将总评分除以总评分次数
	 * 后得到平均分。
	 * 2，当期用户修改对餐厅的评分。此时要先从餐厅总得分中减去用户上次评分值（$old_rating_value)，
	 * 再加上新的评分值后，除以总评分次数，得到平均分。
	 */
	private function updateRestaurant($model){
		$rating = $model->rating;

		$restaurant = Restaurant::model()->findByPk($model->restaurant_id);

		$oldVotes = $restaurant->votes;
		$oldAveragePoints = $restaurant->average_points;

		if (! isset($model->old_rating_value)) {
			// 新的投票。
			$averagePoints = ($oldVotes * $oldAveragePoints + $model->rating ) / ($oldVotes + 1);
			$restaurant->votes = $oldVotes + 1;
		}else{
			// 更新旧投票。
			$averagePoints = ($oldVotes * $oldAveragePoints - $model->old_rating_value + $model->rating) / $oldVotes;
		}
		
		// 平均分保留小数点后一位。
		$restaurant->average_points = number_format($averagePoints, 1);

		if(! $restaurant->save()){
			print_r($restaurant->getErrors());
			die('save restaurant failed');
		}else{
			$this->setCalculateRankFlag(true);
		}
	}

	/*
	 * 保存评分。
	 * 分两种情况：
	 * 1，用户第一次对餐馆评分。在评分表中新建一条记录。
	 * 2，用户修改对餐馆的评分。此时要找出上次打分记录，修改成新的打分值。
	 */
	private function saveVoteRecord($model){
		$criteria = new CDbCriteria();
		$criteria->compare('user_id', $model->attributes['user_id']);
		$criteria->compare('restaurant_id', $model->attributes['restaurant_id']);

		$oldModel = $model->find($criteria);

		if ($oldModel != null) {
			$oldModel->old_rating_value = $oldModel->rating;
			$oldModel->rating = $model->attributes['rating'];
			$oldModel->save();
			return $oldModel;
		}else{
			$model->save();
			return $model;
		}
	}

	private function saveCommentRecord($restaurantId) {
		if (isset($_POST['Comment'])) {
			$model = new Comment;
			$model->restaurant_id = $restaurantId;
			$model->content = $comment;

			$model->save();
		}
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionCreate() {
		$model=new Vote;

	   	if(isset($_POST['Vote'])) {
	   		$delay = $this->checkActionFrequency();
			if($delay >= 0) {
				$model->attributes=$_POST['Vote'];
		    	if($model->validate()) {
		    	// 保存本次投票记录。
		    	$model = $this->saveVoteRecord($model);

		    	// 更新餐厅记录。
		    	$this->updateRestaurant($model);

		    	// 保存评论信息
		    	
		    	$this->saveCommentRecord($model->restaurant_id;
		    	
		    	// 更新最后操作时间戳。
		    	$this->updateLastActionTime();

		    	//清空所有缓存文件
		    	$this->clearCacheFile(false);
	        	// 提交成功向前台输出JSON。
	        	$others = array('voteid'=>$model->id);
	        	echo $this->makeResultMessage(SUCCESS_CODE,SUCCESS_CODE_MESSAGE_VOTE_CREATE,$others);
		    }else {
		    	$others = array('delay'=>abs($delay));
		    	echo $this->makeResultMessage(ERROR_CODE_FREQUENCY,ERROR_CODE_MESSAGE_FREQUENCY,$others);
		    }
		}else {
			echo $this->makeResultMessage(ERROR_CODE_VOTE_CREATE,ERROR_CODE_MESSAGE_VOTE_CREATE,$others);
		}
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

	public function actionQuery($restaurantId, $userId){
		if (empty($restaurantId) || empty($userId)) {
			die('error: $restaurantId and $userId are needed!');
		}

		$criteria = new CDbCriteria;
		$criteria->compare('restaurant_id', $restaurantId);
		$criteria->compare('user_id', $userId);
		$model = Vote::model()->find($criteria);

		if (!empty($model)) {
			echo json_encode(array('id'=>$model->id, 'rating' => $model->rating));
		}else{
			echo json_encode(array('msg'=>'not found'));
		}
		
		return;
	}

	/*
	 * 重新计算每个汤馆的平均分。
	 * 有时候，计算平均分的结果会出问题，比如今天遇到平均分计算得到5.05分的问题（可能是代码版本兼容性问题），
	 * 而我们不能让用户看到这样的情况，一旦发现，除了排错之外，还需要一种机制来修正这个问题。so……
	 */
	public function actionFlush(){
		$restaurants = array();

		$allVotes = Vote::model()->findAll();
		foreach ($allVotes as $key => $vote) {
			$id = $vote->restaurant_id;
			if (isset($restaurants[$id])) {
				$restaurants[$id]['count'] = $restaurants[$id]['count'] + 1;
				$restaurants[$id]['points'] = $restaurants[$id]['points'] + $vote->rating;
			}else{
				$restaurants[$id]['count'] = 1;
				$restaurants[$id]['points'] = $vote->rating;
			}			
		}

		foreach ($restaurants as $id => $value) {
			$average_points = $value['points'] / $value['count'];

			$average_points = number_format($average_points, 1);

			if ($average_points > $this->_max_rating_point) {
				$average_points = $this->_max_rating_point;
				// TODO: 平均分超出打分最大值，某个打分被hacked，向管理员发提醒。
				print_r("汤馆（$id） average_points 计算错误： $average_points");
			}

			// if ($id == 3) {
			// 	var_dump($value);
			// 	var_dump($average_points);
			// }

			$model = Restaurant::model()->findByPk($id);
			$model->average_points = $average_points;
			$model->votes = $value['count'];
			$model->save();	
		}

		$this->setCalculateRankFlag();

		echo "重新计算每个汤馆的平均分！";
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
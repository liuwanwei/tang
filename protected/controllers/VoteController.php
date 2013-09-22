<?php

class VoteController extends Controller
{
	private function calculateRank(){
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

		// 保存所有店铺权重得分结果。
		// $rankFilePath = Yii::app()->basePath . '/../ranks.db';
		// file_put_contents($rankFilePath, $ranks);
	}


	// 更新餐厅记录，由此引发餐厅排名的重新计算。
	private function updateRestaurant($voteAttributes){
		$restaurantId = $voteAttributes['restaurant_id'];
		$rating = $voteAttributes['rating'];

		$restaurant = Restaurant::model()->findByPk($restaurantId);

		$oldVotes = $restaurant->votes;
		$oldAveragePoints = $restaurant->average_points;

		$averagePoints = ($oldVotes * $oldAveragePoints + $rating ) / ($oldVotes + 1);

		$restaurant->votes = $oldVotes + 1;
		$restaurant->average_points = $averagePoints;

		if(! $restaurant->save()){
			die('save restaurant record faild: '.$restaurant->getErrors());
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
		    	// 更新餐厅记录。
		    	$this->updateRestaurant($model->attributes);

		    	// 更新所有记录，包括餐厅排序。
		    	$this->calculateRank();
		    
		    	// 保存本次投票记录。
		        	$model->save();

		        	// 重定向到餐厅列表。
		        	$this->redirect(array('restaurant/index'));

		        	return;
		    }
	    	}
	    	$this->render('create',array('model'=>$model));
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
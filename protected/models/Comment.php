<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property integer $id
 * @property integer $user_id
 * @property string $content
 * @property integer $restaurant_id
 * @property integer $hidden
 * @property string $create_datetime
 */
class Comment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content', 'required'),
			array('user_id, restaurant_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, content, restaurant_id, create_datetime', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'user' => array(self::BELONGS_TO, 'User', 'user_id'),
				'restaurant' => array(self::BELONGS_TO, 'Restaurant', 'restaurant_id'),
				'loves' => array(self::STAT, 'Love', 'target_id', 'condition'=>'target_type=2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'content' => 'Content',
			'restaurant_id' => 'Restaurant',
			'create_datetime' => 'Create Datetime',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('restaurant_id',$this->restaurant_id);
		$criteria->compare('create_datetime',$this->create_datetime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * do some common field like user_id,create_datetime
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave()
	{
		if (parent::beforeSave())
		{  
			if ($this->isNewRecord)
			{
				$this->create_datetime = date('Y-m-d H:i:s');
				$this->user_id = Yii::app()->user->id;
			}
			return true;
		}else
		{
			return false;
		}
	}
	
	public function getLastComments($limit)
	{
		$criteria = new CDbCriteria(array(
				'condition'=> 'hidden = false',
				'limit'=> $limit,
				'offset'=> 0,
				'order'=>'create_datetime DESC',
				'with'=>array('user'),
		));
		
		$lastCommentsDataProvider = new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>false,
		));
		
		return $lastCommentsDataProvider->getData();
	}

	/*
	 *  查询评论量最大的餐馆列表。
	 *  参数：
	 *  	$county:	县区
	 *  	$area: 		区域
	 * 		$type: 		餐馆类型。
	 * 		$count:		查询结果数。
	 *  返回值：
	 *  	array:		符合条件的餐馆信息数组。		
	 */
	public function mostCommentedRestaurant($county, $area, $type, $count){		
        $whereClause = ' WHERE is_checked=1 ';
        if (! empty($county)) {
			$whereClause .= " AND county_id=$county ";
		}

		if ($area != -1) {
			$whereClause .= " AND area_id=$area ";
		}
		
		if (! empty($type)) {
			// 支持多种经营的餐馆类型的查询。
			$type = ',' . $type . ',';
			$whereClause .= " AND type_id LIKE '$type' ";
		}

		$sql = "SELECT
         			r.id,r.name,
         			c.comments
    			FROM(
        			SELECT
            			id,name
        			FROM restaurant 
        			$whereClause 
        		) as r
    			INNER JOIN (
                	SELECT
                        restaurant_id,
                        count(*) AS comments
                  	FROM comment
        		  	WHERE hidden=0
                  	GROUP BY restaurant_id
                ) as c
           		on r.id = c.restaurant_id
    			Order by c.comments desc
    			LIMIT $count";

    	// 执行一个超出当前model范围的查询，必须使用更“原始”接口，参考见这里：
    	// http://www.yiiframework.com/doc/guide/1.1/zh_cn/database.dao#sec-3
    	$connection = Yii::app()->db;
		$command = $connection->createCommand($sql);
		$result = $command->queryAll();

		return $result;
	}
}


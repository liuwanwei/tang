<?php

/**
 * This is the model class for table "restaurant".
 *
 * The followings are the available columns in table 'restaurant':
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $business_hour
 * @property string $address
 * @property string $type_id
 * @property integer $county_id
 * @property integer $area_id
 * @property integer $is_shutdown
 * @property integer $is_checked
 * @property string $coordinate
 * @property string $image_url
 * @property string $description
 * @property integer $votes
 * @property double $average_points
 * @property double $weighted_points
 * @property integer $creator
 */
class Restaurant extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Restaurant the static model class
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
		return 'restaurant';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, address, type_id, county_id, area_id', 'required'),
			array('county_id, area_id, is_shutdown,  is_checked, votes', 'numerical', 'integerOnly'=>true),
			array('average_points, weighted_points', 'numerical'),
			array('name, business_hour, address', 'length', 'max'=>128),
			array('phone', 'length', 'max'=>64),
			array('image_url, description, coordinate', 'length', 'max'=>256),
			array('image_url', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true, 'on'=>'update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, phone, business_hour, address, county_id, area_id, is_shutdown, image_url, coordinate, description', 'safe', 'on'=>'search'),
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
			'county' => array(self::BELONGS_TO, 'County', 'county_id'),
			'area' => array(self::BELONGS_TO, 'Area', 'area_id'),
			'type' => array(self::BELONGS_TO, 'RestaurantType', 'type_id'),
			'status' => array(self::BELONGS_TO, 'RestaurantStatus', 'is_shutdown'),
			'comment_count' => array(self::STAT, 'Comment','restaurant_id'),
			'features' => array(self::HAS_MANY, 'Feature', 'restaurant_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '店名',
			'type_id' => '类型',
			'type.name' => '类型',
			'phone' => '电话',
			'business_hour' => '营业时间',
			'address' => '地址',
			'county_id' => '区域',
			'area_id' => '位置',
			'is_shutdown' => '经营状态',
			'is_checked' => '审核状态',
			'status.name' => '服务状态',
			'county.name'	=> '区域',
			'area.name' => '商圈',
			'image_url' => '店面图片',
			'coordinate' => '坐标',
			'description' => '描述',
			'votes' => '投票数',
			'average_points' => '平均得分',
			'weighted_points' => '排名权重',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('business_hour',$this->business_hour,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('county_id',$this->county_id);
		$criteria->compare('area_id',$this->area_id);
		$criteria->compare('is_shutdown',$this->is_shutdown);
		$criteria->compare('is_checked', $this->is_checked);
		// $criteria->compare('image_url',$this->image_url,true);
		// $criteria->compare('description',$this->description,true);
		// $criteria->compare('votes',$this->votes);
		// $criteria->compare('average_points',$this->average_points);
		// $criteria->compare('weighted_points',$this->weighted_points);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/*
	 * 搜索未审核过的汤馆。管理员专有。
	 */
	public function searchUnchecked(){
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('county_id',$this->county_id);
		$criteria->compare('area_id',$this->area_id);
		$criteria->compare('is_shutdown',$this->is_shutdown);		

		$criteria->compare('is_checked', 0);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));	
	}

	/*
	 * 搜索当前登录用户添加的所有汤馆。所有人都有。
	 */
	public function searchCreatedByMe($is_checked){
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('county_id',$this->county_id);
		$criteria->compare('area_id',$this->area_id);
		$criteria->compare('is_shutdown',$this->is_shutdown);
		$criteria->compare('is_checked', $is_checked);

		$criteria->compare('creator', Yii::App()->user->id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));	
	}

	/*
	 *  查询访问量最大的餐馆列表。
	 *  参数：
	 *  	$county:	县区
	 *  	$area: 		区域
	 * 		$type: 		餐馆类型。
	 * 		$count:		查询结果数。
	 *  返回值：
	 *  	array:		符合条件的餐馆信息数组。		
	 */
	public function mostVisitedRestaurant($county, $area, $type, $count){
		$criteria = new CDbCriteria(array(
			'condition'=>'is_checked = 1 AND visits <> 0',
			'order'=>'visits DESC',
			'limit'=>$count
		));

		if (! empty($county)) {
			$criteria->compare('county_id', $county);
		}

		if ($area != -1) {
			$criteria->compare('area_id', $area);
		}
		
		if (! empty($type)) {
			$keyword = ',' . $type . ',';
			$criteria->addCondition("type_id LIKE '$keyword'");
		}

		return $this->findAll($criteria);
	}

	/*
	* 根据前台不同的过滤条件，搜索当前页的汤馆。
	*/
	public function indexByPage($page = 0, $limit = 10) {
		$criteria = new CDbCriteria(array(
			'condition'=> 'is_checked = 1',
			'order'=> 'weighted_points DESC',
			'with'=>array('features.details'),
		));
		
		if (! empty($this->county_id)) {
			$criteria->compare('county_id',$this->county_id);
		}

		if ($this->area_id != -1) {
			$criteria->compare('area_id', $this->area_id);
		}
		
		if (! empty($this->type_id)) {
			$keyword = ',' . $this->type_id . ',';
			// TODO：用这行代码代替下下面一行，搜索不到数据，不知道为啥。
			// $criteria->addSearchCondition('type_id', $keyword, false);
			$criteria->addCondition("type_id LIKE '$keyword'");
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(  
            	'pageSize'=>$limit,
            	'currentPage'=>$page,
        	),  
		));	
	}
}
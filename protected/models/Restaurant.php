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
 * @property integer $county_id
 * @property integer $area_id
 * @property integer $is_shutdown
 * @property string $image_url
 * @property double $latitude
 * @property double $longitude
 * @property string $description
 * @property integer $votes
 * @property double $average_points
 * @property double $weighted_points
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
			array('name, address', 'required'),
			array('county_id, area_id, is_shutdown, votes', 'numerical', 'integerOnly'=>true),
			array('latitude, longitude, average_points, weighted_points', 'numerical'),
			array('name, business_hour, address', 'length', 'max'=>128),
			array('phone', 'length', 'max'=>64),
			array('image_url, description', 'length', 'max'=>256),
			array('image_url', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true, 'on'=>'update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, phone, business_hour, address, county_id, area_id, is_shutdown, image_url, latitude, longitude, description', 'safe', 'on'=>'search'),
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
			'status' => array(self::BELONGS_TO, 'RestaurantStatus', 'is_shutdown'),
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
			'phone' => '电话',
			'business_hour' => '营业时间',
			'address' => '地址',
			'county_id' => '区域',
			'area_id' => '位置',
			'is_shutdown' => '状态',
			'status.name' => '服务状态',
			'county.name'	=> '区域',
			'area.name' => '商圈',
			'image_url' => '店面图片',
			'latitude' => '经度',
			'longitude' => '纬度',
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
		$criteria->compare('image_url',$this->image_url,true);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('votes',$this->votes);
		$criteria->compare('average_points',$this->average_points);
		$criteria->compare('weighted_points',$this->weighted_points);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
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
			array('county_id, area_id, is_shutdown', 'numerical', 'integerOnly'=>true),
			array('latitude, longitude', 'numerical'),
			array('name, business_hour, address', 'length', 'max'=>128),
			array('phone', 'length', 'max'=>64),
			array('image_url, description', 'length', 'max'=>256),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'phone' => 'Phone',
			'business_hour' => 'Business Hour',
			'address' => 'Address',
			'county_id' => 'County',
			'area_id' => 'Area',
			'is_shutdown' => 'Is Shutdown',
			'image_url' => 'Image Url',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'description' => 'Description',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
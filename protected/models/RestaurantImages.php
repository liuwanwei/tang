<?php

/**
 * This is the model class for table "restaurant_images".
 *
 * The followings are the available columns in table 'restaurant_images':
 * @property string $id
 * @property string $origin_url
 * @property string $thumbnail
 * @property integer $width
 * @property integer $height
 * @property integer $creator
 * @property integer $restaurant_id
 * @property string $is_check
 * @property string $create_datetime
 */
class RestaurantImages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'restaurant_images';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('width, height, creator, restaurant_id', 'numerical', 'integerOnly'=>true),
			array('origin_url, thumbnail', 'length', 'max'=>200),
			array('is_check', 'length', 'max'=>10),
			array('create_datetime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, origin_url, thumbnail, width, height, creator, restaurant_id, is_check, create_datetime', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'origin_url' => 'Origin Url',
			'thumbnail' => 'Thumbnail',
			'width' => 'Width',
			'height' => 'Height',
			'creator' => 'Creator',
			'restaurant_id' => 'Restaurant',
			'is_check' => 'Is Check',
			'create_datetime' => 'Create Datetime',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('origin_url',$this->origin_url,true);
		$criteria->compare('thumbnail',$this->thumbnail,true);
		$criteria->compare('width',$this->width);
		$criteria->compare('height',$this->height);
		$criteria->compare('creator',$this->creator);
		$criteria->compare('restaurant_id',$this->restaurant_id);
		$criteria->compare('is_check',$this->is_check,true);
		$criteria->compare('create_datetime',$this->create_datetime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RestaurantImages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

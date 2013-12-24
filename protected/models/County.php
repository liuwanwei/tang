<?php

/**
 * This is the model class for table "county".
 *
 * The followings are the available columns in table 'county':
 * @property integer $id
 * @property string $name
 * @property integer $type
 */
class County extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return County the static model class
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
		return 'county';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, type', 'safe', 'on'=>'search'),
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
				'restaurants' => array(self::HAS_MANY, 'Restaurant', 'id'),
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
			'type' => 'Type',
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
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 获取行政县、区数据
	 * @param  $type 0 区； 1 县
	 * @return array
	 */
	public function getCountyWithType($type)
	{
		$criteria=new CDbCriteria(array(
				'condition'=>'type='.$type,
		));
		$dataProvider=new CActiveDataProvider('County',array(
				'criteria'=>$criteria,
		));
		
		$data = $dataProvider->getData();
		$counties = array();
		foreach ($data as $key => $value)
		{
			$counties[$value->id] = $value->name;
		}
	
		return $counties;
	}
}
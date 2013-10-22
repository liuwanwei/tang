<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property integer $id
 * @property integer $user_id
 * @property string $content
 * @property integer $restaurant_id
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
}
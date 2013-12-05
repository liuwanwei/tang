<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $extension_user_id
 * @property string $nick_name
 * @property string $image_url
 * @property integer $role
 * @property integer $source
 * @property string $last_action_time
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('extension_user_id, nick_name, image_url, role, source', 'required'),
			array('role, source', 'numerical', 'integerOnly'=>true),
			array('extension_user_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, extension_user_id, nick_name, image_url, role, source', 'safe', 'on'=>'search'),
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
			'extension_user_id' => 'Extension User',
			'nick_name' => 'Nick Name',
			'image_url' => 'Image Url',
			'role' => 'Role',
			'source' => 'Source',
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
		$criteria->compare('extension_user_id',$this->extension_user_id,true);
		$criteria->compare('nick_name',$this->nick_name,true);
		$criteria->compare('image_url',$this->image_url,true);
		$criteria->compare('role',$this->role);
		$criteria->compare('source',$this->source);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 获取当前登录者头像地址
	 */
	public function getCurrentUserImageUrl()
	{
		if (isset(Yii::app()->session['currentUserImageUrl']))
		{
			return Yii::app()->session['currentUserImageUrl'];
		}
		else
		{
			$user = User::model()->findByPk(Yii::app()->user->id);
			if ($user != null)
			{
				Yii::app()->session->add('currentUserImageUrl',$user->image_url);
				return $user->image_url;
			}
			else
			{
				return "";
			}
		}
	}
	
	/**
	 * 当前用户是否是管理员
	 */
	public static function isAdmin()
	{
		$user = User::model()->findByPk(Yii::app()->user->id);
	
		if ($user !== null)
		{
			return $user->role == 1;
		}
	
		return false;
	}

}
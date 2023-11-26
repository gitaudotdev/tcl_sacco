<?php

/**
 * This is the model class for table "SMSGroup".
 *
 * The followings are the available columns in table 'SMSGroup':
 * @property integer $id
 * @property integer $groupSMSId
 * @property integer $groupId
 * @property string $status
 * @property integer $createdBy
 * @property string $createdAt
 */
class SMSGroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'SMSGroup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('groupSMSId, groupId, createdBy', 'required'),
			array('groupSMSId, groupId, createdBy', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>9),
			array('createdAt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, groupSMSId, groupId, status, createdBy, createdAt', 'safe', 'on'=>'search'),
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
			'groupSMSId' => 'Group Smsid',
			'groupId' => 'Group',
			'status' => 'Status',
			'createdBy' => 'Created By',
			'createdAt' => 'Created At',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('groupSMSId',$this->groupSMSId);
		$criteria->compare('groupId',$this->groupId);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('createdBy',$this->createdBy);
		$criteria->compare('createdAt',$this->createdAt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SMSGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

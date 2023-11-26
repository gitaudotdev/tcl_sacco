<?php

/**
 * This is the model class for table "handover".
 *
 * The followings are the available columns in table 'handover':
 * @property integer $id
 * @property integer $branch_id
 * @property integer $user_id
 * @property integer $leave_application_id
 * @property integer $handover_to
 * @property string $notes
 * @property integer $created_by
 * @property string $created_at
 */
class Handover extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'handover';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('branch_id, user_id, leave_application_id, handover_to, notes', 'required'),
			array('branch_id, user_id, leave_application_id, handover_to, created_by', 'numerical', 'integerOnly'=>true),
			array('notes', 'length', 'max'=>1024),
			array('id, branch_id, user_id, leave_application_id, handover_to, notes, created_by, created_at', 'safe', 'on'=>'search'),
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
			'branch_id' => 'Branch',
			'user_id' => 'User',
			'leave_application_id' => 'Leave Application',
			'handover_to' => 'Handover To',
			'notes' => 'Notes',
			'created_by' => 'Created By',
			'created_at' => 'Created At',
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
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('leave_application_id',$this->leave_application_id);
		$criteria->compare('handover_to',$this->handover_to);
		$criteria->compare('notes',$this->notes,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Handover the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

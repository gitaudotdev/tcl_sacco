<?php

/**
 * This is the model class for table "balancechecks".
 *
 * The followings are the available columns in table 'balancechecks':
 * @property integer $id
 * @property integer $user_id
 * @property string $conversationID
 * @property string $originatorConversationID
 * @property integer $responseCode
 * @property string $responseDesc
 * @property integer $resultType
 * @property integer $resultCode
 * @property string $resultDesc
 * @property string $transactionID
 * @property string $workingAccount
 * @property string $utilityAccount
 * @property string $created_at
 * @property string $updated_at
 */
class Balancechecks extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'balancechecks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, conversationID, originatorConversationID, responseCode, responseDesc', 'required'),
			array('user_id, responseCode, resultType, resultCode', 'numerical', 'integerOnly'=>true),
			array('conversationID, originatorConversationID, resultDesc', 'length', 'max'=>512),
			array('responseDesc', 'length', 'max'=>1024),
			array('transactionID', 'length', 'max'=>50),
			array('workingAccount, utilityAccount', 'length', 'max'=>15),
			array('updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, conversationID, originatorConversationID, responseCode, responseDesc, resultType, resultCode, resultDesc, transactionID, workingAccount, utilityAccount, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'conversationID' => 'Conversation',
			'originatorConversationID' => 'Originator Conversation',
			'responseCode' => 'Response Code',
			'responseDesc' => 'Response Desc',
			'resultType' => 'Result Type',
			'resultCode' => 'Result Code',
			'resultDesc' => 'Result Desc',
			'transactionID' => 'Transaction',
			'workingAccount' => 'Working Account',
			'utilityAccount' => 'Utility Account',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('conversationID',$this->conversationID,true);
		$criteria->compare('originatorConversationID',$this->originatorConversationID,true);
		$criteria->compare('responseCode',$this->responseCode);
		$criteria->compare('responseDesc',$this->responseDesc,true);
		$criteria->compare('resultType',$this->resultType);
		$criteria->compare('resultCode',$this->resultCode);
		$criteria->compare('resultDesc',$this->resultDesc,true);
		$criteria->compare('transactionID',$this->transactionID,true);
		$criteria->compare('workingAccount',$this->workingAccount,true);
		$criteria->compare('utilityAccount',$this->utilityAccount,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Balancechecks the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

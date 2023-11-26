<?php

/**
 * This is the model class for table "stkPush".
 *
 * The followings are the available columns in table 'stkPush':
 * @property integer $id
 * @property string $transactionType
 * @property string $accountNumber
 * @property string $phoneNumber
 * @property string $amountRequested
 * @property integer $profileId
 * @property integer $branchId
 * @property integer $managerId
 * @property string $merchantRequestId
 * @property string $checkoutRequestId
 * @property string $responseCode
 * @property string $responseDescription
 * @property string $customerMessage
 * @property string $resultCode
 * @property string $resultDesc
 * @property string $resultAmount
 * @property string $MPESAReceiptNumber
 * @property string $resultTransactionDate
 * @property string $resultPhoneNumber
 * @property integer $initiatedBy
 * @property string $createdAt
 * @property string $updatedAt
 */
class StkPush extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stkPush';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('accountNumber, phoneNumber, amountRequested, profileId, branchId, managerId, merchantRequestId,
			 checkoutRequestId, responseCode, responseDescription, customerMessage,initiatedBy', 'required'),
			array('profileId, branchId, managerId, initiatedBy', 'numerical', 'integerOnly'=>true),
			array('transactionType, phoneNumber, amountRequested, resultAmount, resultPhoneNumber', 'length', 'max'=>15),
			array('accountNumber, responseCode, resultCode, MPESAReceiptNumber', 'length', 'max'=>25),
			array('merchantRequestId, checkoutRequestId', 'length', 'max'=>255),
			array('responseDescription, customerMessage, resultDesc', 'length', 'max'=>1024),
			array('resultTransactionDate, createdAt, updatedAt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, transactionType, accountNumber, phoneNumber, amountRequested, profileId, branchId, managerId, merchantRequestId, checkoutRequestId, responseCode, responseDescription, customerMessage, resultCode, resultDesc, resultAmount, MPESAReceiptNumber, resultTransactionDate, resultPhoneNumber, initiatedBy, createdAt, updatedAt', 'safe', 'on'=>'search'),
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
			'transactionType' => 'Transaction Type',
			'accountNumber' => 'Account Number',
			'phoneNumber' => 'Phone Number',
			'amountRequested' => 'Amount Requested',
			'profileId' => 'Profile',
			'branchId' => 'Branch',
			'managerId' => 'Manager',
			'merchantRequestId' => 'Merchant Request',
			'checkoutRequestId' => 'Checkout Request',
			'responseCode' => 'Response Code',
			'responseDescription' => 'Response Description',
			'customerMessage' => 'Customer Message',
			'resultCode' => 'Result Code',
			'resultDesc' => 'Result Desc',
			'resultAmount' => 'Result Amount',
			'MPESAReceiptNumber' => 'Mpesareceipt Number',
			'resultTransactionDate' => 'Result Transaction Date',
			'resultPhoneNumber' => 'Result Phone Number',
			'initiatedBy' => 'Initiated By',
			'createdAt' => 'Created At',
			'updatedAt' => 'Updated At',
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
		$criteria->compare('transactionType',$this->transactionType,true);
		$criteria->compare('accountNumber',$this->accountNumber,true);
		$criteria->compare('phoneNumber',$this->phoneNumber,true);
		$criteria->compare('amountRequested',$this->amountRequested,true);
		$criteria->compare('profileId',$this->profileId);
		$criteria->compare('branchId',$this->branchId);
		$criteria->compare('managerId',$this->managerId);
		$criteria->compare('merchantRequestId',$this->merchantRequestId,true);
		$criteria->compare('checkoutRequestId',$this->checkoutRequestId,true);
		$criteria->compare('responseCode',$this->responseCode,true);
		$criteria->compare('responseDescription',$this->responseDescription,true);
		$criteria->compare('customerMessage',$this->customerMessage,true);
		$criteria->compare('resultCode',$this->resultCode,true);
		$criteria->compare('resultDesc',$this->resultDesc,true);
		$criteria->compare('resultAmount',$this->resultAmount,true);
		$criteria->compare('MPESAReceiptNumber',$this->MPESAReceiptNumber,true);
		$criteria->compare('resultTransactionDate',$this->resultTransactionDate,true);
		$criteria->compare('resultPhoneNumber',$this->resultPhoneNumber,true);
		$criteria->compare('initiatedBy',$this->initiatedBy);
		$criteria->compare('createdAt',$this->createdAt,true);
		$criteria->compare('updatedAt',$this->updatedAt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StkPush the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

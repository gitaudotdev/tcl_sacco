<?php

/**
 * This is the model class for table "stray_repayments".
 *
 * The followings are the available columns in table 'stray_repayments':
 * @property integer $id
 * @property string $transaction_id
 * @property string $providerRefId
 * @property string $clientAccount
 * @property string $source
 * @property string $amount
 * @property string $description
 * @property string $firstname
 * @property string $lastname
 * @property string $date
 */
class StrayRepayments extends CActiveRecord{
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'stray_repayments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('transaction_id, providerRefId,amount, description, firstname, lastname, date', 'required'),
			array('transaction_id, providerRefId', 'length', 'max'=>100),
			array('clientAccount', 'length', 'max'=>25),
			array('source', 'length', 'max'=>75),
			array('amount', 'length', 'max'=>15),
			array('description', 'length', 'max'=>1200),
			array('firstname, lastname', 'length', 'max'=>120),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, transaction_id, providerRefId, source, amount, description, firstname, lastname, date,is_paid', 'safe', 'on'=>'search'),
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
			'transaction_id' => 'Transaction',
			'providerRefId' => 'Provider Ref',
			'clientAccount' => 'Client Account',
			'source' => 'Source',
			'amount' => 'Amount',
			'is_paid' => 'Paid to Correct Account',
			'description' => 'Description',
			'firstname' => 'Firstname',
			'lastname' => 'Lastname',
			'date' => 'Date',
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
	public function search(){
		$alias = $this->getTableAlias(false,false);
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('transaction_id',$this->transaction_id,true);
		$criteria->compare('providerRefId',$this->providerRefId,true);
		$criteria->compare('clientAccount',$this->clientAccount,true);
		$criteria->compare('source',$this->source,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('is_paid',$this->is_paid,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('date',$this->date,true);

		$criteria->compare("$alias.is_paid",'0',true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			),
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}

	public function getClientName(){
		return $this->firstname.' '.$this->lastname;
	}

	public function getAmountTransacted(){
		return number_format($this->amount,2);
	}

	public function getPaymentTransactionDate(){
		return date("d/m/Y",strtotime($this->date));
	}

	public function getAction(){
		$view_link="<a href='#' title='Pay To Account' class='btn btn-info btn-sm' onclick='directPayment(\"".$this->id."\")'> Pay</a>";
		$action_link="$view_link";
		echo $action_link;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StrayRepayments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

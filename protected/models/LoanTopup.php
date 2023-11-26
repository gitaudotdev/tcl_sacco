<?php

/**
 * This is the model class for table "loan_topup".
 *
 * The followings are the available columns in table 'loan_topup':
 * @property integer $id
 * @property integer $loanaccount_id
 * @property string $topup_amount
 * @property string $disbursement_amount
 * @property string $is_approved
 * @property integer $topped_by
 * @property string $topped_at
 */
class LoanTopup extends CActiveRecord{
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'loan_topup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loanaccount_id, topup_amount, disbursement_amount, topped_by,interest_rate,repayment_period', 'required'),
			array('loanaccount_id, topped_by', 'numerical', 'integerOnly'=>true),
			array('topup_amount', 'length', 'max'=>15),
			array('disbursement_amount', 'length', 'max'=>17),
			array('is_approved', 'length', 'max'=>1),
			array('comment,disbursement_reason,rejection_reason,approval_reason', 'length', 'max'=>512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, loanaccount_id, topup_amount, disbursement_amount, is_approved, topped_by, topped_at,interest_rate,repayment_period,comment', 'safe', 'on'=>'search'),
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
			'loanaccount_id' => 'Loanaccount',
			'topup_amount' => 'Topup Amount',
			'disbursement_amount' => 'Disbursement Amount',
			'is_approved' => 'Is Approved',
			'interest_rate'=>'Interest Rate',
			'repayment_period'=>'Repayment Period',
			'comment'=>'Top Up Comment',
			'approval_reason'=>'Top Up Approval Reason',
			'rejection_reason'=>'Top Up Rejection Reason',
			'disbursement_reason'=>'Top Up Disbursement Reason',
			'topped_by' => 'Topped By',
			'topped_at' => 'Topped At',
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
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('loanaccount_id',$this->loanaccount_id);
		$criteria->compare('topup_amount',$this->topup_amount,true);
		$criteria->compare('disbursement_amount',$this->disbursement_amount,true);
		$criteria->compare('is_approved',$this->is_approved,true);
		$criteria->compare('topped_by',$this->topped_by);
		$criteria->compare('topped_at',$this->topped_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
         'pageSize'=>30
       ),
		));
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LoanTopup the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

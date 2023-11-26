<?php

/**
 * This is the model class for table "payroll_transactions".
 *
 * The followings are the available columns in table 'payroll_transactions':
 * @property integer $id
 * @property integer $staff_id
 * @property string $amount
 * @property integer $processed_by
 * @property string $processed_at
 */
class PayrollTransactions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'payroll_transactions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('staff_id, processed_by', 'required'),
			array('staff_id, processed_by', 'numerical', 'integerOnly'=>true),
			array('amount', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, staff_id,amount,processed_by,processed_at,is_complete', 'safe', 'on'=>'search'),
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
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'staff_id' => 'Staff',
			'amount' => 'Amount',
			'is_complete' => 'Processing Completed',
			'processed_by' => 'Processed By',
			'processed_at' => 'Processed At',
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
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('is_complete',$this->is_complete,true);
		$criteria->compare('processed_by',$this->processed_by);
		$criteria->compare('processed_at',$this->processed_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}

	public function getProcessedByName(){
		$user = Profiles::model()->findByPk($this->processed_by);
		return !empty($user) ? $user->ProfileFullName : "AUTOMATED";
	}

	public function getStaffName(){
		$user = Profiles::model()->findByPk($this->staff_id);
		return !empty($user) ? $user->ProfileFullName : "AUTOMATED";
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PayrollTransactions the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

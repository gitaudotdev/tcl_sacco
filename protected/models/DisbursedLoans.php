<?php

/**
 * This is the model class for table "disbursed_loans".
 *
 * The followings are the available columns in table 'disbursed_loans':
 * @property integer $id
 * @property integer $loanaccount_id
 * @property string $amount_disbursed
 * @property integer $disbursed_by
 * @property string $disbursed_at
 */
class DisbursedLoans extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'disbursed_loans';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loanaccount_id, disbursed_by', 'required'),
			array('loanaccount_id, disbursed_by', 'numerical', 'integerOnly'=>true),
			array('amount_disbursed', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, loanaccount_id, amount_disbursed, disbursed_by, disbursed_at,type', 'safe', 'on'=>'search'),
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
			'amount_disbursed' => 'Amount Disbursed',
			'type' => 'Disbursement Type',
			'disbursed_by' => 'Disbursed By',
			'disbursed_at' => 'Disbursed At',
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
		$criteria->compare('amount_disbursed',$this->amount_disbursed,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('disbursed_by',$this->disbursed_by);
		$criteria->compare('disbursed_at',$this->disbursed_at,true);

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
	 * @return DisbursedLoans the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

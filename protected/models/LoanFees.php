<?php

/**
 * This is the model class for table "loan_fees".
 *
 * The followings are the available columns in table 'loan_fees':
 * @property integer $loanfee_id
 * @property string $name
 * @property integer $loanproduct_id
 * @property string $calculation_method
 * @property string $is_deductable
 * @property string $value
 * @property integer $created_by
 * @property string $created_at
 */
class LoanFees extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'loan_fees';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, loanproduct_id, created_by', 'required'),
			array('loanproduct_id, created_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('calculation_method, is_deductable', 'length', 'max'=>1),
			array('value', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('loanfee_id, name, loanproduct_id, calculation_method, is_deductable, value, created_by, created_at', 'safe', 'on'=>'search'),
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
			'loanfee_id' => 'Loanfee',
			'name' => 'Name',
			'loanproduct_id' => 'Loanproduct',
			'calculation_method' => 'Calculation Method',
			'is_deductable' => 'Is Deductable',
			'value' => 'Value',
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

		$criteria->compare('loanfee_id',$this->loanfee_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('loanproduct_id',$this->loanproduct_id);
		$criteria->compare('calculation_method',$this->calculation_method,true);
		$criteria->compare('is_deductable',$this->is_deductable,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
         'pageSize'=>30
       ),
		));
	}

	public function getLoanProductName(){
		$loanproduct=Loanproduct::model()->findByPk($this->loanproduct_id);
		if(!empty($loanproduct)){
			echo $loanproduct->name;
		}else{
			echo '';
		}
	}

	public function getIsDeductableStatusName(){
		switch($this->is_deductable){
			case 0:
			return 'Not Deducted from Principal';
			break;

			case 1:
			return 'Deducted From Principal';
			break;
		}
	}

	public function getCalculationMethodName(){
		switch($this->calculation_method){
			case 0:
			return 'Fixed Amount';
			break;

			case 1:
			return 'Percent of Principal';
			break;
		}
	}

	public function getFeeValue(){
		$value=CommonFunctions::asMoney($this->value);
		switch($this->calculation_method){
			case 0:
			return 'KSH. '.$value;
			break;

			case 1:
			return $value.' %';
			break;
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LoanFees the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "loantransactions".
 *
 * The followings are the available columns in table 'loantransactions':
 * @property integer $loantransaction_id
 * @property integer $loanaccount_id
 * @property string $date
 * @property string $amount
 * @property string $type
 * @property string $is_void
 * @property integer $transacted_by
 * @property string $transacted_at
 */
class Loantransactions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'loantransactions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loanaccount_id, date, amount, transacted_by', 'required'),
			array('loanaccount_id, transacted_by', 'numerical', 'integerOnly'=>true),
			array('amount', 'length', 'max'=>15),
			array('type, is_void', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('loantransaction_id, loanaccount_id, date, amount, type, is_void, transacted_by, transacted_at', 'safe', 'on'=>'search'),
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
			'loantransaction_id' => 'Loantransaction',
			'loanaccount_id' => 'Loanaccount',
			'date' => 'Date',
			'amount' => 'Amount',
			'type' => 'Type',
			'is_void' => 'Is Void',
			'transacted_by' => 'Transacted By',
			'transacted_at' => 'Transacted At',
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

		$criteria->compare('loantransaction_id',$this->loantransaction_id);
		$criteria->compare('loanaccount_id',$this->loanaccount_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('is_void',$this->is_void,true);
		$criteria->compare('transacted_by',$this->transacted_by);
		$criteria->compare('transacted_at',$this->transacted_at,true);

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
	 * @return Loantransactions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "restructuredloans".
 *
 * The followings are the available columns in table 'restructuredloans':
 * @property integer $id
 * @property integer $loanaccount_id
 * @property string $previous_amount
 * @property string $restructured_amount
 * @property string $previous_rate
 * @property string $restructured_rate
 * @property integer $previous_period
 * @property integer $restructured_period
 * @property integer $restructured_by
 * @property string $restructured_at
 */
class Restructuredloans extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'restructuredloans';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loanaccount_id, previous_amount, restructured_amount, previous_rate, restructured_rate, previous_period, restructured_period', 'required'),
			array('loanaccount_id, previous_period, restructured_period, restructured_by', 'numerical', 'integerOnly'=>true),
			array('previous_amount, restructured_amount, previous_rate, restructured_rate', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, loanaccount_id, previous_amount, restructured_amount, previous_rate, restructured_rate, previous_period, restructured_period, restructured_by, restructured_at', 'safe', 'on'=>'search'),
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
			'previous_amount' => 'Previous Amount',
			'restructured_amount' => 'Restructured Amount',
			'previous_rate' => 'Previous Rate',
			'restructured_rate' => 'Restructured Rate',
			'previous_period' => 'Previous Period',
			'restructured_period' => 'Restructured Period',
			'restructured_by' => 'Restructured By',
			'restructured_at' => 'Restructured At',
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
		$criteria->compare('loanaccount_id',$this->loanaccount_id);
		$criteria->compare('previous_amount',$this->previous_amount,true);
		$criteria->compare('restructured_amount',$this->restructured_amount,true);
		$criteria->compare('previous_rate',$this->previous_rate,true);
		$criteria->compare('restructured_rate',$this->restructured_rate,true);
		$criteria->compare('previous_period',$this->previous_period);
		$criteria->compare('restructured_period',$this->restructured_period);
		$criteria->compare('restructured_by',$this->restructured_by);
		$criteria->compare('restructured_at',$this->restructured_at,true);

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
	 * @return Restructuredloans the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

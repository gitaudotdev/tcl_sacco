<?php

/**
 * This is the model class for table "clearedloans".
 *
 * The followings are the available columns in table 'clearedloans':
 * @property integer $clearance_id
 * @property integer $loanaccount_id
 * @property string $date_cleared
 * @property string $overpayment
 * @property string $created_at
 */
class Clearedloans extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'clearedloans';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loanaccount_id, date_cleared', 'required'),
			array('loanaccount_id', 'numerical', 'integerOnly'=>true),
			array('overpayment', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('clearance_id, loanaccount_id, date_cleared, overpayment, created_at', 'safe', 'on'=>'search'),
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
			'clearance_id' => 'Clearance',
			'loanaccount_id' => 'Loanaccount',
			'date_cleared' => 'Date Cleared',
			'overpayment' => 'Overpayment',
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

		$criteria->compare('clearance_id',$this->clearance_id);
		$criteria->compare('loanaccount_id',$this->loanaccount_id);
		$criteria->compare('date_cleared',$this->date_cleared,true);
		$criteria->compare('overpayment',$this->overpayment,true);
		$criteria->compare('created_at',$this->created_at,true);

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
	 * @return Clearedloans the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

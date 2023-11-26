<?php

/**
 * This is the model class for table "rejected_loans".
 *
 * The followings are the available columns in table 'rejected_loans':
 * @property integer $id
 * @property integer $loanaccount_id
 * @property string $type
 * @property string $reason
 * @property integer $rejected_by
 * @property string $rejected_at
 */
class RejectedLoans extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rejected_loans';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loanaccount_id, reason, rejected_by', 'required'),
			array('loanaccount_id, rejected_by', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>1),
			array('reason', 'length', 'max'=>512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, loanaccount_id, type, reason, rejected_by, rejected_at', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'reason' => 'Reason',
			'rejected_by' => 'Rejected By',
			'rejected_at' => 'Rejected At',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('rejected_by',$this->rejected_by);
		$criteria->compare('rejected_at',$this->rejected_at,true);

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
	 * @return RejectedLoans the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

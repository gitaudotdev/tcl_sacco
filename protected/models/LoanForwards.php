<?php

/**
 * This is the model class for table "loan_forwards".
 *
 * The followings are the available columns in table 'loan_forwards':
 * @property integer $id
 * @property integer $loanaccount_id
 * @property integer $forwarded_to
 * @property string $comment
 * @property string $resolved
 * @property integer $forwarded_by
 * @property string $forwarded_at
 */
class LoanForwards extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'loan_forwards';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loanaccount_id, forwarded_to, comment', 'required'),
			array('loanaccount_id, forwarded_to, forwarded_by', 'numerical', 'integerOnly'=>true),
			array('comment', 'length', 'max'=>512),
			array('resolved', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, loanaccount_id, forwarded_to, comment, resolved, forwarded_by, forwarded_at', 'safe', 'on'=>'search'),
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
			'forwarded_to' => 'Forwarded To',
			'comment' => 'Comment',
			'resolved' => 'Resolved',
			'forwarded_by' => 'Forwarded By',
			'forwarded_at' => 'Forwarded At',
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
		$criteria->compare('forwarded_to',$this->forwarded_to);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('resolved',$this->resolved,true);
		$criteria->compare('forwarded_by',$this->forwarded_by);
		$criteria->compare('forwarded_at',$this->forwarded_at,true);

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
	 * @return LoanForwards the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

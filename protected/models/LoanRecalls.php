<?php

/**
 * This is the model class for table "loan_recalls".
 *
 * The followings are the available columns in table 'loan_recalls':
 * @property integer $id
 * @property integer $loanaccount_id
 * @property string $resolved
 * @property integer $redirect_to
 * @property string $comment
 * @property integer $recalled_by
 * @property string $recalled_at
 */
class LoanRecalls extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'loan_recalls';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loanaccount_id, redirect_to, comment', 'required'),
			array('loanaccount_id, redirect_to, recalled_by', 'numerical', 'integerOnly'=>true),
			array('resolved', 'length', 'max'=>1),
			array('comment', 'length', 'max'=>512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, loanaccount_id, resolved, redirect_to, comment, recalled_by, recalled_at', 'safe', 'on'=>'search'),
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
			'resolved' => 'Resolved',
			'redirect_to' => 'Redirect To',
			'comment' => 'Comment',
			'recalled_by' => 'Recalled By',
			'recalled_at' => 'Recalled At',
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
		$criteria->compare('resolved',$this->resolved,true);
		$criteria->compare('redirect_to',$this->redirect_to);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('recalled_by',$this->recalled_by);
		$criteria->compare('recalled_at',$this->recalled_at,true);

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
	 * @return LoanRecalls the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

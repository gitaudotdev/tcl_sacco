<?php

/**
 * This is the model class for table "loan_redirects".
 *
 * The followings are the available columns in table 'loan_redirects':
 * @property integer $id
 * @property integer $loanaccount_id
 * @property string $comment
 * @property string $resubmitted
 * @property integer $redirected_by
 * @property string $redirected_at
 */
class LoanRedirects extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'loan_redirects';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loanaccount_id, comment', 'required'),
			array('loanaccount_id, redirected_by', 'numerical', 'integerOnly'=>true),
			array('comment', 'length', 'max'=>512),
			array('resubmitted', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, loanaccount_id, comment, resubmitted, redirected_by, redirected_at', 'safe', 'on'=>'search'),
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
			'comment' => 'Comment',
			'resubmitted' => 'Resubmitted',
			'redirected_by' => 'Redirected By',
			'redirected_at' => 'Redirected At',
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
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('resubmitted',$this->resubmitted,true);
		$criteria->compare('redirected_by',$this->redirected_by);
		$criteria->compare('redirected_at',$this->redirected_at,true);

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
	 * @return LoanRedirects the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

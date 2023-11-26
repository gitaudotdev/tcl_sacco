<?php

/**
 * This is the model class for table "savingproducts".
 *
 * The followings are the available columns in table 'savingproducts':
 * @property integer $savingproduct_id
 * @property string $name
 * @property string $opening_balance
 * @property string $interest_rate
 * @property string $interest_posting_frequency
 * @property integer $posting_date
 * @property integer $created_by
 * @property string $created_at
 */
class Savingproducts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'savingproducts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, created_by', 'required'),
			array('posting_date, created_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>75),
			array('opening_balance', 'length', 'max'=>15),
			array('interest_rate', 'length', 'max'=>5),
			array('interest_posting_frequency', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('savingproduct_id, name, opening_balance, interest_rate, interest_posting_frequency, posting_date, created_by, created_at', 'safe', 'on'=>'search'),
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
			'savingproduct_id' => 'Savingproduct',
			'name' => 'Name',
			'opening_balance' => 'Opening Balance',
			'interest_rate' => 'Interest Rate',
			'interest_posting_frequency' => 'Interest Posting Frequency',
			'posting_date' => 'Posting Date',
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

		$criteria->compare('savingproduct_id',$this->savingproduct_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('opening_balance',$this->opening_balance,true);
		$criteria->compare('interest_rate',$this->interest_rate,true);
		$criteria->compare('interest_posting_frequency',$this->interest_posting_frequency,true);
		$criteria->compare('posting_date',$this->posting_date);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
         'pageSize'=>30
       ),
		));
	}

	public function getPostingDateList(){
		$postingDateArray=array();
		for($i=1;$i<=28;$i++){
			array_push($postingDateArray,$i);
		}
		return $postingDateArray;
	}

	public function getSavingProductName(){
		return ucfirst($this->name);
	}

	public function getSavingProductOpeningBalance(){
		return CommonFunctions::asMoney($this->opening_balance);
	}

	public function getSavingProductInterestRate(){
		return CommonFunctions::asMoney($this->interest_rate).' %';
	}

	public function getInterestPostingFrequency(){
		switch($this->interest_posting_frequency){
			case '0':
			return "Every Month (Monthly)";
			break;

			case '1':
			return "Every Six(6) Months";
			break;

			case '2':
			return "Every Year (Yearly)";
			break;
		}
	}

	public function getInterestPostingDate(){
		return "On Date: ". $this->posting_date;
	}

	public function getAction(){
		switch(Yii::app()->user->user_level){
			case '0':
		$delete_link="<a href='#' class='btn btn-primary btn-round btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('savingproducts/delete/'.$this->savingproduct_id)."\")'><i class='fa fa-trash'></i> Delete</a>";
		$update_link="<a href='#' class='btn btn-warning btn-round btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('savingproducts/update/'.$this->savingproduct_id)."\")'><i class='fa fa-edit'></i> Update</a>";
		$action_links="$update_link&nbsp;$delete_link";
		echo $action_links;
			break;

			case '1':
		$delete_link="<a href='#' class='btn btn-primary btn-round btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('savingproducts/delete/'.$this->savingproduct_id)."\")'><i class='fa fa-trash'></i> Delete</a>";
		$update_link="<a href='#' class='btn btn-warning btn-round btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('savingproducts/update/'.$this->savingproduct_id)."\")'><i class='fa fa-edit'></i> Update</a>";
		$action_links="$update_link&nbsp;$delete_link";
		echo $action_links;
			break;

			case '2':
			$action_links="";
			echo $action_links;
			break;

			case '3':
			$action_links="";
			echo $action_links;
			break;
		}
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Savingproducts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "incomes".
 *
 * The followings are the available columns in table 'incomes':
 * @property integer $income_id
 * @property integer $incometype_id
 * @property string $name
 * @property string $amount
 * @property string $transaction_date
 * @property string $income_recur
 * @property integer $date_recurring
 * @property string $description
 * @property string $attachment
 * @property integer $created_by
 * @property string $created_at
 */
class Incomes extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'incomes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('incometype_id, name, transaction_date, description,created_by', 'required'),
			array('incometype_id, date_recurring, created_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>512),
			array('amount', 'length', 'max'=>15),
			array('income_recur', 'length', 'max'=>1),
			array('description, attachment', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('income_id, incometype_id, name, amount, transaction_date, income_recur, date_recurring, description, attachment, created_by, created_at', 'safe', 'on'=>'search'),
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
			'users'=>array(SELF::BELONGS_TO,'Users','created_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'income_id' => 'Income',
			'incometype_id' => 'Incometype',
			'name' => 'Name',
			'amount' => 'Amount',
			'transaction_date' => 'Transaction Date',
			'income_recur' => 'Income Recur',
			'date_recurring' => 'Date Recurring',
			'description' => 'Description',
			'attachment' => 'Attachment',
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

		$criteria->compare('income_id',$this->income_id);
		$criteria->compare('incometype_id',$this->incometype_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('transaction_date',$this->transaction_date,true);
		$criteria->compare('income_recur',$this->income_recur,true);
		$criteria->compare('date_recurring',$this->date_recurring);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('attachment',$this->attachment,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);
		/*Additional Conditions*/
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case'1':
			$criteria->with = array('users.incomes');
			$criteria->condition='users.branch_id='.$userBranch;
			break;

			case'2':
			$criteria->with = array('users.incomes');
			$criteria->condition='users.branch_id='.$userBranch;
			break;

			case '3':
			$criteria->with = array('users.incomes');
			$criteria->condition='incomes.created_by='.Yii::app()->user->user_id;
			break;
		}
		$criteria->order = "income_id DESC";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
         'pageSize'=>30
       ),
		));
	}
	
	public function getIncomeTypesList(){
		$incomeTypeSql="SELECT * FROM income_types";
		return CHtml::listData(IncomeTypes::model()->findAllBySql($incomeTypeSql),
			'incometype_id','name');
	}

	public function getIncomeTypeName(){
		$incomeType=IncomeTypes::model()->findByPk($this->incometype_id);
		if(!empty($incomeType)){
			$incomeTypeName=$incomeType->name;
			return ucfirst($incomeTypeName);
		}else{
			$incomeTypeName="";
			return ucfirst($incomeTypeName);
		}
	}

	public function getIncomeAmount(){
		return CommonFunctions::asMoney($this->amount);
	}

	public function getIncomeDate(){
		return date('jS M Y',strtotime($this->transaction_date));
	}

	public function getIncomeName(){
		return ucfirst($this->name);
	}

	public function getIncomeRecurring(){
		switch($this->income_recur){
			case '0':
			return 'Income Not Recurring';
			break;

			case '1':
			return 'Income Recurrring';
			break;
		}
	}

	public function getIncomeRecurringList(){
		$frequency_array=array();
		for($i=0;$i<=28;$i++){
			array_push($frequency_array,$i);
		}
		return $frequency_array;
	}

	public function getAction(){
		switch(Yii::app()->user->user_level){
			case '0':
		$delete_link="<a href='#' class='btn btn-primary btn-sm' title='Delete Income' onclick='Authenticate(\"".Yii::app()->createUrl('incomes/delete/'.$this->income_id)."\")'><i class='fa fa-trash'></i></a>";
		$update_link="<a href='#' class='btn btn-warning btn-sm' title='Update Income' onclick='Authenticate(\"".Yii::app()->createUrl('incomes/update/'.$this->income_id)."\")'><i class='fa fa-edit'></i>e</a>";
		$action_links="$update_link&nbsp;$delete_link";
		echo $action_links;
			break;

			case '1':
		$delete_link="<a href='#' class='btn btn-primary btn-sm' title='Delete Income' onclick='Authenticate(\"".Yii::app()->createUrl('incomes/delete/'.$this->income_id)."\")'><i class='fa fa-trash'></i></a>";
		$update_link="<a href='#' class='btn btn-warning btn-sm' title='Update Income' onclick='Authenticate(\"".Yii::app()->createUrl('incomes/update/'.$this->income_id)."\")'><i class='fa fa-edit'></i>e</a>";
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
	 * @return Incomes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

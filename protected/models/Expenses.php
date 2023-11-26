<?php
/**
 * This is the model class for table "expenses".
 *
 * The followings are the available columns in table 'expenses':
 * @property integer $expense_id
 * @property integer $expensetype_id
 * @property string $name
 * @property string $amount
 * @property string $expense_date
 * @property string $expense_recur
 * @property integer $date_recurring
 * @property string $description
 * @property string $attachment
 * @property integer $created_by
 * @property string $created_at
 */
class Expenses extends CActiveRecord{

	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'expenses';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('expensetype_id, name, expense_date, description,created_by,user_id,branch_id', 'required'),
			array('expensetype_id, date_recurring, created_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>512),
			array('amount', 'length', 'max'=>15),
			array('expense_recur', 'length', 'max'=>1),
			array('description, attachment', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('expense_id, expensetype_id, name, amount, expense_date, expense_recur, date_recurring, description, attachment, created_by, created_at,user_id,branch_id,startDate,endDate,modifiable', 'safe', 'on'=>'search'),
		);
	}
	/**
	 * @return array relational rules.
	 */
	public function relations(){
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
			'expense_id' => 'Expense',
			'expensetype_id' => 'Expensetype',
			'user_id' => 'User',
			'branch_id' => 'Branch',
			'name' => 'Name',
			'amount' => 'Amount',
			'expense_date' => 'Expense Date',
			'expense_recur' => 'Expense Recur',
			'date_recurring' => 'Date Recurring',
			'description' => 'Description',
			'modifiable' => 'Expense Modifiable',
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
	public function search(){
		$alias = $this->getTableAlias(false,false);
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('expense_id',$this->expense_id);
		$criteria->compare('expensetype_id',$this->expensetype_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('expense_date',$this->expense_date,true);
		$criteria->compare('expense_recur',$this->expense_recur,true);
		$criteria->compare('date_recurring',$this->date_recurring);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('modifiable',$this->modifiable,true);
		$criteria->compare('attachment',$this->attachment,true);
		$criteria->compare('created_at',$this->created_at,true);

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.expense_date)",$this->startDate, $this->endDate, 'AND');
		}else{
			$start_date=date('Y-m-01');
			$end_date=date('Y-m-t');
			$criteria->addBetweenCondition("DATE($alias.expense_date)",$start_date, $end_date, 'AND');
		}

		/*Additional Conditions*/
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		//Allowed to view Personal Expenses only
		if(Navigation::checkIfAuthorized(193) === 1){
			$criteria->addCondition('user_id ='.$userID);
		}else{
			switch(Yii::app()->user->user_level){
				case '0':
				break;

				case '1':
				$criteria->addCondition('branch_id ='.$userBranch);
				break;

				case '2':
				$criteria->addCondition('user_id ='.$userID);
				break;
			}
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'expense_id DESC',
			),
			'pagination'=>array(
				'pageSize'=>10
       		),
		));
	}

	public function getStaffList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getExpenseTypesList(){
		$expenseTypeSql="SELECT * FROM expense_types";
		return CHtml::listData(ExpenseTypes::model()->findAllBySql($expenseTypeSql),'expensetype_id','name');
	}

	public function getExpenseBranchName(){
		$branch=Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ? $branch->name :  "UNDEFINED";
	}

	public function getStaffName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getExpenseTypeName(){
		$expenseType=ExpenseTypes::model()->findByPk($this->expensetype_id);
		return !empty($expenseType) ? $expenseType->name : "UNDEFINED";
	}

	public function getExpenseAmount(){
		return CommonFunctions::asMoney($this->amount);
	}

	public function getExpenseDate(){
		return date('d/m/Y',strtotime($this->expense_date));
	}

	public function getExpenseName(){
		echo '<div class="text-wrap width-200">'.$this->name.'</div>';
	}

	public function getExpenseRecurrence(){
		return $this->expense_recur =='0' ? 'ONE OFF' : 'RECURRING';
	}

	public function getExpenseRecurringList(){
		$frequency_array=array();
		for($i=0;$i<=28;$i++){
			array_push($frequency_array,$i);
		}
		return $frequency_array;
	}

	public function getAction(){

		switch($this->modifiable){
			case '0':
			$delete_link="";
			$update_link="";
			break;

			case '1':
			/* DELETE EXPENSE */
			if(Navigation::checkIfAuthorized(102) == 1){
			 $delete_link="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('expenses/delete/'.$this->expense_id)."\")' title='Delete Expense'><i class='fa fa-trash'></i></a>";
			}else{
				$delete_link="";
			}
			/* UPDATE EXPENSE */
			if(Navigation::checkIfAuthorized(100) == 1){
				$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('expenses/update/'.$this->expense_id)."\")' title='Update Expense'><i class='fa fa-edit'></i></a>";
			}else{
				$update_link="";
			}
			break;
		}
		/* VIEW EXPENSE */
		if(Navigation::checkIfAuthorized(101) == 1){
			$view_link="<a href='".Yii::app()->createUrl('expenses/'.$this->expense_id)."' title='View Details' class='btn btn-info btn-sm'><i class='fa fa-eye'></i></a>";
		}else{
			$view_link="";
		}

		$action_links="$view_link&nbsp;$update_link&nbsp;$delete_link";
		
		echo $action_links;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Expenses the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

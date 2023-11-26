<?php

/**
 * This is the model class for table "payroll".
 *
 * The followings are the available columns in table 'payroll':
 * @property integer $id
 * @property integer $user_id
 * @property integer $branch_id
 * @property integer $transaction_id
 * @property string $sales_commision
 * @property string $collections_commision
 * @property string $gross_salary
 * @property string $net_salary
 * @property integer $payroll_month
 * @property integer $payroll_year
 * @property integer $processed_by
 * @property string $processed_at
 */
class Payroll extends CActiveRecord{
	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'payroll';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, branch_id, transaction_id, sales_commision, collections_commision, gross_salary, net_salary, payroll_month, payroll_year', 'required'),
			array('user_id, branch_id, transaction_id, payroll_month, payroll_year, processed_by', 'numerical','integerOnly'=>true),
			array('sales_commision, collections_commision, gross_salary, net_salary,loan_paid', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, branch_id, transaction_id, sales_commision, collections_commision, gross_salary, net_salary,loan_paid,payroll_month, payroll_year, processed_by, processed_at,startDate,endDate', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'branch_id' => 'Branch',
			'transaction_id' => 'Transaction',
			'sales_commision' => 'Sales Commision',
			'collections_commision' => 'Collections Commision',
			'gross_salary' => 'Gross Salary',
			'net_salary' => 'Net Salary',
			'loan_paid' => 'Total Loan Amount Paid',
			'payroll_month' => 'Payroll Month',
			'payroll_year' => 'Payroll Year',
			'processed_by' => 'Processed By',
			'processed_at' => 'Processed At',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('transaction_id',$this->transaction_id);
		$criteria->compare('sales_commision',$this->sales_commision,true);
		$criteria->compare('collections_commision',$this->collections_commision,true);
		$criteria->compare('gross_salary',$this->gross_salary,true);
		$criteria->compare('net_salary',$this->net_salary,true);
		$criteria->compare('loan_paid',$this->loan_paid,true);
		$criteria->compare('payroll_month',$this->payroll_month);
		$criteria->compare('payroll_year',$this->payroll_year);
		$criteria->compare('processed_by',$this->processed_by);
		$criteria->compare('processed_at',$this->processed_at,true);

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.processed_at)",$this->startDate, $this->endDate, 'AND');
		}

		/*Additional Conditions*/
		$userBranch = Yii::app()->user->user_branch;
		$userID     = Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case'1':
			$criteria->addCondition('branch_id ='.$userBranch);
			break;

			case'2':
			$criteria->addCondition('user_id ='.$userID);
			break;
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			),
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}

	public function getStaffPayrollList(){
		$userBranch     = Yii::app()->user->user_branch;
		$userID         = Yii::app()->user->user_id;
		$suppliersQuery = "SELECT * FROM profiles WHERE id IN(SELECT profileId FROM account_settings WHERE configType='PAYROLL_LISTED' AND configValue='ACTIVE')";
		switch(Yii::app()->user->user_level){
			case '0':
			$suppliersQuery .= "";
			break;

			case '1':
			$suppliersQuery .= " AND managerId=$userID";
			break;

			default:
			$suppliersQuery .= " AND id=$userID";
			break;
		}
		$suppliersQuery .= " ORDER BY firstName,lastName ASC";
		return CHtml::listData(Profiles::model()->findAllBySql($suppliersQuery),'id','ProfileFullName');
	}

	public function getBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getSystemAdminsList(){
		$userBranch  = Yii::app()->user->user_branch;
		$userID      = Yii::app()->user->user_id;
		$usersQuery  = "SELECT * from profiles WHERE profileType IN('STAFF')
		AND id IN(SELECT profileId FROM auths WHERE authStatus IN('ACTIVE') AND level IN('SUPERADMIN'))";
		switch(Yii::app()->user->user_level){
			case '0':
			$usersQuery.= "";
			break;

			case '1':
			$usersQuery.= " AND managerId=$userID";
			break;

			case '3':
			$usersQuery.= " AND id=$userID";
			break;
		}
		$usersQuery.= " ORDER BY firstName,lastName ASC";
		return CHtml::listData(Profiles::model()->findAllBySql($usersQuery),'id','ProfileFullName');
	}

	public function getPayrollMonthArray(){
		$monthArray = array();
		for($month=1;$month<=12;$month++){
			$monthArray[$month] = $month;
		}
		return $monthArray;
	}

	public function getPayrollYearArray(){
		$yearArray   = array();
		$currentYear = (int)date("Y");
		for($year=$currentYear;$year>=2017;$year--){
			$yearArray[$year] = $year;
		}
		return $yearArray;
	}

	public function getStaffMemberName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getStaffMemberBranchName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileBranch : "UNDEFINED";
	}

	public function getStaffMemberPayrollMonth(){
		return CommonFunctions::getMonthName($this->payroll_month);
	}

	public function getStaffMemberPayrollYear(){
		return $this->payroll_year;
	}

	public function getStaffMemberPayrollPeriod(){
		return CommonFunctions::getMonthName($this->payroll_month).'-'.$this->payroll_year;
	}

	public function getStaffMemberPayrollTotalLoan(){
		return CommonFunctions::asMoney($this->loan_paid);
	}

	public function getStaffMemberPayrollNetSalary(){
		return CommonFunctions::asMoney($this->net_salary);
	}

	public function getPayrollDateProcessed(){
		return date("jS M Y",strtotime($this->processed_at));
	}

	public function getPayrollDateProcessedBy(){
		$profile = Profiles::model()->findByPk($this->processed_by);
		return !empty($profile) ? $profile->ProfileFullName : "AUTOMATED";
	}

	public function getAction(){

		if(Navigation::checkIfAuthorized(164) == 1){
			$view_link="<a href='".Yii::app()->createUrl('payroll/'.$this->id)."' title='View Details' class='btn btn-info btn-sm'><i class='fa fa-eye'></i></a>";
		}else{
			$view_link="";
		}
		$action_links="";
		
		echo $action_links;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Payroll the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

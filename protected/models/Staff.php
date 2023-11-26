<?php

/**
 * This is the model class for table "staff".
 *
 * The followings are the available columns in table 'staff':
 * @property integer $staff_id
 * @property integer $user_id
 * @property integer $branch_id
 * @property string $first_name
 * @property string $last_name
 * @property string $id_number
 * @property string $phone
 * @property string $email
 * @property string $salary
 * @property integer $created_by
 * @property string $created_at
 */
class Staff extends CActiveRecord{

	public $gender,$dateOfBirth,$startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'staff';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, branch_id, first_name, last_name, id_number, phone, email, salary', 'required'),
			array('user_id, branch_id, created_by', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name', 'length', 'max'=>150),
			array('id_number, phone, salary,sales_target,collections_target', 'length', 'max'=>15),
			array('email', 'length', 'max'=>522),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('staff_id, user_id, branch_id, first_name, last_name, id_number, payroll_listed,is_active,type,phone, email, salary,bonus,commission,profit,sales_target,
				collections_target,created_by,created_at,is_supervisor,gender,dateOfBirth,startDate,endDate,
				payroll_auto_process,commentsDashboard_listed ', 'safe', 'on'=>'search'),
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
	public function attributeLabels(){
		return array(
			'staff_id' => 'Staff',
			'user_id' => 'User',
			'branch_id' => 'Branch',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'id_number' => 'Id Number',
			'is_supervisor' => 'Supervisory Role',
			'payroll_listed' => 'List on Payroll',
			'payroll_auto_process' => 'Automatically Process Salary',
			'type' => 'Staff Type',
			'is_active' => 'Account Status',
			'phone' => 'Phone',
			'email' => 'Email',
			'salary' => 'Salary',
			'bonus'=>'Bonus Percent',
			'commission'=>'Commission Percent',
			'profit'=>'profit Percent',
			'sales_target'=>'Staff Sales Target',
			'collections_target'=>'Staff Collections Target',
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

		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('id_number',$this->id_number,true);
		$criteria->compare('is_supervisor',$this->is_supervisor,true);
		$criteria->compare('payroll_listed',$this->payroll_listed,true);
		$criteria->compare('payroll_auto_process',$this->payroll_auto_process,true);
		$criteria->compare('commentsDashboard_listed',$this->commentsDashboard_listed,true);
		$criteria->compare('is_active',$this->is_active,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('salary',$this->salary,true);
		$criteria->compare('sales_target',$this->sales_target,true);
		$criteria->compare('collections_target',$this->collections_target,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		//$criteria->compare('is_active','1',true);

		if(isset($this->startDate) && isset($this->endDate)){
				$criteria->addBetweenCondition("DATE(created_at)",$this->startDate, $this->endDate, 'AND');
		}

		/*Additional Conditions*/
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case'1':
			$criteria->addCondition('branch_id='.$userBranch);
			break;

			case'2':
			$criteria->addCondition('user_id='.Yii::app()->user->user_id);
			break;

			case '3':
			$criteria->addCondition('user_id='.Yii::app()->user->user_id);
			break;
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
         'defaultOrder'=>'first_name ASC',
      ),
			'pagination'=>array(
         'pageSize'=>10
       ),
		));
	}

	public function getSaccoBranchList(){
		$userBranch=Yii::app()->user->user_branch;
		$branchQuery = "SELECT * from branch WHERE is_merged='0'";
		switch(Yii::app()->user->user_level){
			case '0':
			$branchQuery .= "";
			break;

			default:
			$branchQuery .= " AND branch_id=$userBranch";
			break;
		}
		return CHtml::listData(Branch::model()->findAllBySql($branchQuery),'branch_id','name');
	}

	public function getStaffList(){
		$userBranch=Yii::app()->user->user_branch;
		$staffQuery = "SELECT * from staff,users WHERE staff.user_id=users.user_id";
		switch(Yii::app()->user->user_level){
			case '0':
			$staffQuery .= "";
			break;

			default:
			$staffQuery .= " AND users.branch_id=$userBranch";
			break;
		}
		return CHtml::listData(Staff::model()->findAllBySql($staffQuery),'staff_id','FullName');
	}

	public function getBranchName(){
		$branch=Branch::model()->findByPk($this->branch_id);
		if(!empty($branch)){
			$branchName=$branch->name;
		}else{
			$branchName='No Branch';
		}
		return strtoupper($branchName);
	}

  public function getFullName(){
		return ucfirst(strtolower($this->last_name)).' '.ucfirst(strtolower($this->first_name)).' - '. $this->id_number;
	}

	public function getStaffFullName(){
		return ucfirst(strtolower($this->first_name)).' '.ucfirst(strtolower($this->last_name));
	}

	public function getStaffDetails(){
		echo 'Name : '.ucfirst(strtolower($this->first_name)).' '.ucfirst(strtolower($this->last_name)).'<br>'.
		'<span style="color:#2ca8ff;margin-top:2%!important;"> Email : '.$this->email.'</span>'.'<br>'.'Phone : '.$this->phone.'<br>'.'<span style="color:#ff3636;margin-top:2%!important;"> Account Status : '.$this->AccountStatus.'</span>';
	}

	public function getStaffSalary(){
		return CommonFunctions::asMoney($this->salary);
	}

	public function getLoansSold(){
		$currentMonth=date('m');
		$currentYear=date('Y');
		$userID=$this->user_id;
		$loanSQL="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','3','4') AND MONTH(date_approved)='$currentMonth'
		  AND YEAR(date_approved)='$currentYear' AND rm=$userID";
		$loans=Loanaccounts::model()->findAllBySql($loanSQL);
		if(!empty($loans)){
			$amount=0;
			foreach($loans as $loan){
				$amount+=$loan->amount_approved;
			}
		}else{
			$amount=0;
		}
		return $amount;
	}

	public function getLoansCollected(){
		$currentMonth=date('m');
		$currentYear=date('Y');
		$userID=$this->user_id;
		$repaymentSQL="SELECT * FROM loantransactions WHERE MONTH(transacted_at)='$currentMonth'
		 AND YEAR(transacted_at)='$currentYear'AND transacted_by=$userID AND is_void='0' AND type='1' ";
		$repayments=Loantransactions::model()->findAllBySql($repaymentSQL);
		if(!empty($repayments)){
			$amount=0;
			foreach($repayments as $repayment){
				$amount+=$repayment->amount;
			}
		}else{
			$amount=0;
		}
		return $amount;
	}

	public function getSalaryBonus(){
		$totalSold=$this->getLoansSold();
		$bonus=($this->bonus * $totalSold)/100;
		return $bonus; 
	}

	public function getSalaryCommission(){
		$totalCollected=$this->getLoansCollected();
		$commission=($this->commission * $totalCollected)/100;
		return $commission; 
	}

	public function getTargets(){
		echo 'Sales : '.$this->SalesTarget.'<br>'.
		'<span style="color:#2ca8ff;margin-top:2%!important;"> Collections : '.$this->CollectionsTarget.'</span>';
	}

	public function getSalesTarget(){
		return number_format($this->sales_target,2);
	}

	public function getCollectionsTarget(){
		return number_format($this->collections_target,2);
	}

	public function getTotalRepayment(){
		$userID=$this->user_id;
		$loanSQL="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','3','4') AND user_id=$userID";
		$loans=Loanaccounts::model()->findAllBySql($loanSQL);
		if(!empty($loans)){
			$amount=0;
			foreach($loans as $loan){
				$amount+=LoanApplication::getEMIAmount($loan->loanaccount_id);
			}
		}else{
			$amount=0;
		}
		return $amount;
	}

	public function getNetPay(){
		$totalPayment=$this->salary + $this->getSalaryBonus() + $this->getSalaryCommission();
		$amountPayable=$totalPayment-$this->getTotalRepayment();
		return $amountPayable;
	}

	public function getSupervisory(){
		switch($this->is_supervisor){
			case '0':
			$roleStaff="Normal Staff";
			break;

			case '1':
			$roleStaff="Supervisor";
			break;
		}
		return $roleStaff;
	}

	public function getAccountStatus(){
		switch($this->is_active){
			case '0':
			$accountStatus='Suspended';
			break;

			case '1':
			$accountStatus='Active';
			break;

			case '2':
			$accountStatus='Exited';
			break;

			case '3':
			$accountStatus='On Leave';
			break;
		}
		return $accountStatus;
	}

	public function getPayrollActions(){
		$payment_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('staff/payment/'.$this->staff_id)."\")' title='Process Payroll'><i class='fa fa-money'></i></a>";
		return $payment_link;
	}

	public function getAction(){

		switch($this->is_active){
			//Unsuspend Account
			case '0':
			if(Navigation::checkIfAuthorized(166) == 1){
				$reinstate_link="<a href='#' class='btn btn-info btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('staff/reinstate/'.$this->staff_id)."\")' title='Reinstate Staff Account'><i class='fa fa-check'></i></a>";
			}else{
				$reinstate_link="";
			}
			$transfer_link="$reinstate_link";
			break;
			//Suspend AND Exit
			case '1':
			if(Navigation::checkIfAuthorized(165) == 1){
				$suspend_link="<a href='#' class='btn btn-default btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('staff/suspend/'.$this->staff_id)."\")' title='Suspend Staff Account'><i class='fa fa-bolt'></i></a>";
			}else{
				$suspend_link="";
			}
			$exit_link="<a href='#' class='btn btn-success btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('staff/transfer/'.$this->staff_id)."\")' title='Transfer Exited Staff Account'><i class='fa fa-clone'></i></a>";
			$transfer_link="$suspend_link&nbsp;$exit_link";
			break;
			//Nothing can be done : Leave Blank
			case '2':
			$transfer_link="";
			break;
			//Nothing can be done : Leave Blank
			case '3':
			$transfer_link="";
			break;
		}

		if(Navigation::checkIfAuthorized(24) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('staff/update/'.$this->staff_id)."\")' title='Update Staff Account'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link="";
		}

		if(Navigation::checkIfAuthorized(25) == 1){
			$delete_link="<a href='#' class='btn btn-danger btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('staff/delete/'.$this->staff_id)."\")' title='Delete Staff Account'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_link="";
		}
		$action_links="$transfer_link&nbsp;$update_link&nbsp;$delete_link";
		echo $action_links;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Staff the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

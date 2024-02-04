<?php

/**
 * This is the model class for table "loanaccounts".
 *
 * The followings are the available columns in table 'loanaccounts':
 * @property integer $loanaccount_id
 * @property integer $user_id
 * @property string $account_number
 * @property string $amount_applied
 * @property string $amount_receivable
 * @property string $insurance_fee
 * @property string $processing_fee
 * @property string $insurance_fee_value
 * @property string $processing_fee_value
 * @property string $deduction_fee
 * @property string $interest_rate
 * @property integer $rm
 * @property string  $loan_status
 * @property string $penalty_amount
 * @property integer $direct_to
 * @property integer $forward_to
 * @property string $date_approved
 * @property string $amount_approved
 * @property integer $approved_by
 * @property string $repayment_cycle
 * @property integer $repayment_period
 * @property string $repayment_start_date
 * @property string $pay_frequency
 * @property string $created_at
 * @property integer $created_by
 */
class Loanaccounts extends CActiveRecord{
	public $disbursed_at,$date_defaulted, $penalty_amount,$is_paid,$loanStatusName,$loanStatusCount,$startDate,$endDate,$saving_balance,$user_employer,$maxLimit,$insuranceRate,$processingRate,$member_type;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'loanaccounts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id,rm,direct_to, repayment_period, repayment_start_date,interest_rate', 'required'),
			array('user_id,rm,direct_to,branch_id,forward_to,approved_by,repayment_period,repayments_count,created_by','numerical','integerOnly'=>true),
			array('account_number, approval_reason,disbursal_reason,special_comment,pay_mode', 'length', 'max'=>512),
			array('amount_applied,amount_receivable, amount_approved', 'length', 'max'=>15),
			array('interest_rate, penalty_amount', 'length', 'max'=>15),
			array('loan_status, repayment_cycle,loan_type,loan_security,repayment_mode,performance_level,crb_status,account_status', 'length', 'max'=>2),
			array('date_approved,date_restructured,created_at,repayment_start_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('loanaccount_id, approval_reason,disbursal_reason, user_id,branch_id, account_number, amount_applied,amount_receivable, interest_rate, rm, loan_status, penalty_amount, direct_to, forward_to, date_approved, amount_approved, approved_by,performance_level,repayment_cycle,special_comment,repayment_period,repayment_start_date,date_restructured,repayments_count,created_at,created_by,startDate,endDate,branch,repayment_mode,loan_security,loan_type,loan_security_details,is_frozen,account_status,maxLimit,insurance_fee ,processing_fee,deduction_fee,insurance_fee_value ,processing_fee_value,member_type', 'safe', 'on'=>'search'),
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
			'loanaccount_id' => 'Loanaccount',
			'performance_level'=>'Loan Status',
			'user_id' => 'User',
			'branch_id' => 'Branch',
			'account_number' => 'Account Number',
			'amount_applied' => 'Amount Applied',
			'interest_rate' => 'Interest Rate',
			'rm' => 'Relationship Manager',
			'arrears'=>'Loan Arrears',
			'is_frozen'=>'Accruing Interest Froze',
			'loan_status' => 'Process Status',
			'crb_status' => 'CRB Status',
			'account_status' => 'Account Status',
			'penalty_amount' => 'Penalty Amount',
			'direct_to' => 'Direct To',
			'forward_to' => 'Forward To',
			'date_approved' => 'Date Approved',
			'amount_approved' => 'Amount Approved',
			'repayment_mode' => 'Repayment Mode',
			'loan_type' => 'Loan Type',
			'loan_security' => 'Loan Security',
			'loan_security_details' => 'Loan Security Details',
			'approved_by' => 'Approved By',
			'repayment_cycle' => 'Repayment Cycle',
			'repayment_period' => 'Repayment Period',
			'repayment_start_date' => 'Repayment Start Date',
			'repayments_count' => 'Expected Repayments',
			'approval_reason' => 'Reason to Approve',
			'disbursal_reason' => 'Reason to Disburse',
			'special_comment'=>'Special Comment',
			'maxLimit'=>'Maximum Loan Limit',
			'date_restructured'=>'Date Restructured',
			'pay_mode'=>'Payment Frequency',
			'created_at' => 'Created At',
			'created_by' => 'Created By',

            'insuranceRate'=>'Insurance Rate',
            'processingRate'=>'Processing Rate',
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

		$criteria->compare("$alias.loanaccount_id",$this->loanaccount_id);
		$criteria->compare("$alias.user_id",$this->user_id);
		$criteria->compare("$alias.branch_id",$this->branch_id);
		$criteria->compare("$alias.account_number",$this->account_number,true);
		$criteria->compare("$alias.amount_applied",$this->amount_applied,true);
        $criteria->compare("$alias.amount_receivable",$this->amount_receivable,true);
		$criteria->compare("$alias.interest_rate",$this->interest_rate,true);
		$criteria->compare("$alias.loan_security",$this->loan_security,true);
		$criteria->compare("$alias.loan_type",$this->loan_type,true);
		$criteria->compare("$alias.repayment_mode",$this->repayment_mode,true);
		$criteria->compare("$alias.rm",$this->rm);
		$criteria->compare('arrears',$this->arrears);
		$criteria->compare('is_frozen',$this->is_frozen);
		$criteria->compare("$alias.loan_status",$this->loan_status,true);
		$criteria->compare("$alias.account_status",$this->account_status,true);
		$criteria->compare("$alias.performance_level",$this->performance_level,true);
		$criteria->compare("$alias.crb_status",$this->crb_status,true);
		$criteria->compare('penalty_amount',$this->penalty_amount,true);
		$criteria->compare('repayments_count',$this->repayments_count,true);
		$criteria->compare('direct_to',$this->direct_to);
		$criteria->compare('forward_to',$this->forward_to);
		$criteria->compare('date_approved',$this->date_approved,true);
		$criteria->compare('amount_approved',$this->amount_approved,true);
		$criteria->compare('approved_by',$this->approved_by);
		$criteria->compare('member_type',$this->member_type);

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.created_at)",$this->startDate,$this->endDate,'AND');
		}

        //filter by member_type, if member_type is not set, show all
//        if(isset($this->member_type)){
//            $criteria->addCondition("member_type = '$this->member_type'");
//        }

		switch(Yii::app()->user->user_level){
			case'1':
			$criteria->addCondition('branch_id ='.Yii::app()->user->user_branch);
			break;

			case'2':
			$criteria->addCondition('rm ='.Yii::app()->user->user_id);
			break;

			case '3':
			$criteria->addCondition('user_id ='.Yii::app()->user->user_id);
			break;
		}


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'loanaccount_id DESC',
			),
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}

	public function searchDisbursed(){
		$alias = $this->getTableAlias(false,false);
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;

		$criteria->compare("$alias.loanaccount_id",$this->loanaccount_id);
		$criteria->compare("$alias.user_id",$this->user_id);
		$criteria->compare("$alias.branch_id",$this->branch_id);
		$criteria->compare("$alias.account_number",$this->account_number,true);
		$criteria->compare("$alias.amount_applied",$this->amount_applied,true);
        $criteria->compare("$alias.amount_receivable",$this->amount_receivable,true);
		$criteria->compare("$alias.interest_rate",$this->interest_rate,true);
		$criteria->compare("$alias.loan_security",$this->loan_security,true);
		$criteria->compare("$alias.loan_type",$this->loan_type,true);
		$criteria->compare("$alias.repayment_mode",$this->repayment_mode,true);
		$criteria->compare("$alias.rm",$this->rm);
		$criteria->compare('arrears',$this->arrears);
		$criteria->compare('is_frozen',$this->is_frozen);
		$criteria->compare("$alias.loan_status",$this->loan_status,true);
		$criteria->compare("$alias.account_status",$this->account_status,true);
		$criteria->compare("$alias.performance_level",$this->performance_level,true);
		$criteria->compare("$alias.crb_status",$this->crb_status,true);
		$criteria->compare('penalty_amount',$this->penalty_amount,true);
		$criteria->compare('direct_to',$this->direct_to);
		$criteria->compare('forward_to',$this->forward_to);
		$criteria->compare('date_approved',$this->date_approved,true);
		$criteria->compare('amount_approved',$this->amount_approved,true);
		$criteria->compare('approved_by',$this->approved_by);

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.created_at)",$this->startDate, $this->endDate, 'AND');
		}

		switch(Yii::app()->user->user_level){
			case '1':
			$criteria->addCondition('branch_id ='.Yii::app()->user->user_branch);
			break;

			case '2':
			$criteria->addCondition('rm ='.Yii::app()->user->user_id);
			break;

			case '3':
			$criteria->addCondition('user_id ='.Yii::app()->user->user_id);
			break;
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'loanaccount_id DESC',
			),
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}

	public function getAccountApprovedBy(){
		$profile = Profiles::model()->findByPk($this->approved_by);
		return !empty($profile) ? $profile->ProfileFullName: "UNDEFINED";
	}

	public function getAccountRejectedBy(){
		$accountID    = $this->loanaccount_id;
		$accountQuery = "SELECT * FROM rejected_loans WHERE loanaccount_id=$accountID ORDER BY id DESC LIMIT 1";
		$account      = RejectedLoans::model()->findBySql($accountQuery);
		if(!empty($account)){
			$profile  = Profiles::model()->findByPk($account->rejected_by);
			$fullname = !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
		}else{
			$fullname = "UNDEFINED";
		}
		return $fullname;
	}

	public function getLoanAccountNumber(){
		return LoanManager::determineAccountNumber($this->loanaccount_id);
	}

	public function getLoanAccountPeriod(){
		return $this->repayment_period == 1  ? $this->repayment_period.' month' : $this->repayment_period.' month(s)';
	}

	public function getAccountRejectionReason(){
		$accountID    = $this->loanaccount_id;
		$accountQuery = "SELECT * FROM rejected_loans WHERE loanaccount_id=$accountID ORDER BY id DESC LIMIT 1";
		$account      = RejectedLoans::model()->findBySql($accountQuery);
		return !empty($account) ? ucfirst($account->reason) : "UNDEFINED";
	}

	public function getDirectedToList(){
		return CHtml::listData(ProfileEngine::getLoanDirectionProfiles(),'id','ProfileNameWithIdNumber');
	}

	public function getLoanAcountNumbersList(){
		$userBranch = Yii::app()->user->user_branch;
		$userID     = Yii::app()->user->user_id;
		$loanQuery  = "SELECT * FROM loanaccounts,profiles WHERE loanaccounts.loan_status NOT IN('3') AND loanaccounts.user_id=profiles.id";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanQuery.="";
			break;

			case '1':
			$loanQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$loanQuery.=" AND profiles.managerId=$userID";
			break;

			case '3':
			$loanQuery.=" AND loanaccounts.user_id=$userID";
			break;
		}
		return CHtml::listData(Loanaccounts::model()->findAllBySql($loanQuery),'loanaccount_id','account_number');
	}

	public function getDaysInArrears(){
		$element = $this->loan_status;
		$array   = array('0','1','3','4','8','9','10');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$DueDate       = $this->LoanAccountInstallmentDueDate;
			$startDate     = date("Y-m-d",strtotime($DueDate));
			$endDate       = date("Y-m-d");
			$daysInArrears = CommonFunctions::getDatesDifference($startDate,$endDate);
			break;

			case 1:
			$daysInArrears = 0;
			break;
		}
		return $daysInArrears;
	}

	public function getInstallmentsIn(){
		$accountID = $this->loanaccount_id;
		$installmentQuery="SELECT DISTINCT(DATE_FORMAT(date,'%m-%Y')) from loantransactions 
		WHERE  loanaccount_id=$accountID AND is_void IN('0','3')";
		$installments=Loantransactions::model()->findBySql($installmentQuery);
		return !empty($installments) ? count($installments) : 0;
	}

	public function getBorrowerBranchName(){
		$branch = Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ? $branch->name : "UNDEFINED";
	}

	public function getBorrowerBranchTown(){
		$branch=Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ?  $branch->branch_town : "UNDEFINED";
	}

	public function getDeterminedAccountNumber(){
		return LoanManager::determineAccountNumber($this->loanaccount_id);
	}

	public function getClientBranch(){
		$branch = Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ? ucfirst($branch->branch_town) : "UNDEFINED";
	}

	public function getForwadingList(){
		$profileQuery = "SELECT * from profiles,auths WHERE profiles.id=auths.profileId AND auths.authStatus IN('ACTIVE') AND profiles.profileType IN('STAFF')";
		return Profiles::model()->findAllBySql($profileQuery);
	}

	public function getAccountDetails(){
		return $this->account_number.' - '.$this->getBorrowerFullName();
	}

	public function getPrimaryIdentificationDoc(){
		return '001';
	}

	public function getRelationshipManagers(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}
    //ALL USERS
	public function getBorrowerList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('ALL'),'id','ProfileNameWithIdNumber');
	}
    //SALARIED
    public function getBorrowerListSALARIED(){
        return CHtml::listData(ProfileEngine::getProfilesByTypeSALARIED('SALARIED'),'id','ProfileNameWithIdNumber');
    }
	public function getCurrentBorrowerList(){
		$userBranch   = Yii::app()->user->user_branch;
		$userID       = Yii::app()->user->user_id;
		$profileQuery = "SELECT * from profiles,auths WHERE profiles.id=auths.profileId AND auths.authStatus='ACTIVE'
		 AND id NOT IN(SELECT user_id FROM loanaccounts WHERE loan_status NOT IN('3','4'))";
		switch(Yii::app()->user->user_level){
			case '0':
			$profileQuery.= "";
			break;

			case '1':
			$profileQuery.= " AND branchId=$userBranch";
			break;

			case '2':
			$profileQuery.= " AND managerId=$userID";
			break;

			case '3':
			$profileQuery.= " AND id=$userID";
			break;
		}
		return CHtml::listData(Profiles::model()->findAllBySql($profileQuery),'id','ProfileFullName');
	}
	
	public function getSaccoBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getBorrowerName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		echo !empty($profile) ? $profile->ProfileFullName : 'UNDEFINED';
	}

	public function getFullMemberName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileFullName: 'UNDEFINED';
	}

	public function getClientMaximumAmount(){
		$maxLoanLimit = ProfileEngine::getActiveProfileAccountSettingByType($this->user_id,'LOAN_LIMIT');
		return $maxLoanLimit==='NOT SET' ? 0.00 : floatval($maxLoanLimit);
	}

	public function getBorrowerEmployer(){
		$profile = Profiles::model()->findByPk($this->user_id);
		echo !empty($profile) ? $profile->ProfileEmployment :'UNDEFINED';
	}

	public function getMemberIndustryType(){
		$profileId = $this->user_id;
        $employmentQuery = "SELECT * FROM employments WHERE profileId=$profileId ORDER BY id DESC LIMIT 1";
		$employment = Employments::model()->findBySql($employmentQuery);
		return !empty($employment) ? $employment->EmploymentIndustryType : "UNDEFINED";
	}

	public function getMemberEmploymentDate(){
		$profileId = $this->user_id;
        $employmentQuery = "SELECT * FROM employments WHERE profileId=$profileId ORDER BY id DESC LIMIT 1";
		$employment = Employments::model()->findBySql($employmentQuery);
		return !empty($employment)? date("Ymd",strtotime($employment->dateEmployed)) :  "";
	}

	public function getMemberEmploymentIncomeAmount(){
		$profileId = $this->user_id;
        $employmentQuery = "SELECT * FROM employments WHERE profileId=$profileId ORDER BY id DESC LIMIT 1";
		$employment = Employments::model()->findBySql($employmentQuery);
		return !empty($employment)? $employment->salaryBand : 0;
	}

	public function getBorrowerEmployerAlt(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileEmployment : 'UNDEFINED';
	}

	public function getBorrowerJoiningDate(){
		$profile = Profiles::model()->findByPk($this->user_id);
		echo !empty($profile) ? date('jS M Y',strtotime($profile->createdAt)) : '';
	}

	public function getBorrowerFullName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileFullName : 'UNDEFINED';
	}

	public function getBorrowerFirstName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? strtoupper($profile->firstName) : 'UNDEFINED';
	}

	public function getBorrowerOtherNames(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? strtoupper($profile->lastName) : 'UNDEFINED';
	}

	public function getRelationshipManagerName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileManager : "UNDEFINED";
	}

	public function getBorrowerPhoneNumber(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfilePhoneNumber :  'UNDEFINED';
	}

	public function getLoanAccountUserResidence(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileResidence : 'UNDEFINED';
	}

	public function getDownloadableUserDetails(){
		echo $this->BorrowerName.'<br>'.'<span style="color:#00933b;margin-top:2%!important;"> Acc : '.$this->account_number.'</span>'.'<br>'.'Branch : '.$this->BorrowerBranchName.'<br>'.'<span style="color:#00933b;margin-top:2%!important;"> RM : '.$this->RelationshipManagerName.'</span><br>'.'<span> Employer : '.$this->BorrowerEmployerAlt.'</span>';
	}

	public function getCurrentLoanBalance(){
		return CommonFunctions::asMoney((LoanManager::getActualLoanBalance($this->loanaccount_id)));
	}

	public function getAmountApplied(){
		echo number_format($this->amount_applied,2);
	}

	public function getFormattedAmountApplied(){
		return number_format($this->amount_applied,2);
	}

	public function getAmountDisbursed(){
		echo number_format($this->NotFormattedExactAmountDisbursed,2);
	}

	public function getFormattedAmountDisbursed(){
		return number_format($this->NotFormattedExactAmountDisbursed,2);
	}

	public function getFormattedApplicationDate(){
		return date('jS M Y',strtotime($this->created_at));
	}

	public function getFormattedDisbursedDate(){
		$loanID      = $this->loanaccount_id;
		$loanStatus  = $this->loan_status;
		$arrayStatus = array('0','1','3');
		if(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus) == 0){
			$disburseQuery = "SELECT * FROM disbursed_loans WHERE loanaccount_id=$loanID ORDER BY id DESC LIMIT 1";
			$account       = DisbursedLoans::model()->findBySql($disburseQuery);
			$dateDisbursed = !empty($account) ? date('jS M Y',strtotime($account->disbursed_at)) : "";
		}else{
			$dateDisbursed = "";
		}
		return $dateDisbursed;
	}

	public function getDisbursedMonth(){
		$loanID      = $this->loanaccount_id;
		$loanStatus  = $this->loan_status;
		$arrayStatus = array('0','1','3','8','9','10');
		if(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus) == 0){
			$disburseQuery = "SELECT * FROM disbursed_loans WHERE loanaccount_id=$loanID ORDER BY id DESC LIMIT 1";
			$account       = DisbursedLoans::model()->findBySql($disburseQuery);
			$dateDisbursed = !empty($account) ? date('M',strtotime($account->disbursed_at)) : "";
		}else{
			$dateDisbursed = "";
		}
		return $dateDisbursed;
	}

	public function getDisbursedYear(){
		$loanID      = $this->loanaccount_id;
		$loanStatus  = $this->loan_status;
		$arrayStatus = array('0','1','3','8','9','10');
		if(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus) == 0){
			$disburseQuery = "SELECT * FROM disbursed_loans WHERE loanaccount_id=$loanID ORDER BY id DESC LIMIT 1";
			$account       = DisbursedLoans::model()->findBySql($disburseQuery);
			$dateDisbursed = !empty($account) ? date('Y',strtotime($account->disbursed_at)) : "";
		}else{
			$dateDisbursed = "";
		}
		return $dateDisbursed;
	}

	public function getNotFormattedExactAmountDisbursed(){
		$loanID        = $this->loanaccount_id;
		$disburseQuery = "SELECT COALESCE(SUM(amount_disbursed),0) AS amount_disbursed FROM disbursed_loans WHERE loanaccount_id=$loanID";
		$account       = DisbursedLoans::model()->findBySql($disburseQuery);
		if(!empty($account)){
			$amountDisbursed = $account->amount_disbursed <= 0 ? 0 : $account->amount_disbursed;
		}else{
			$amountDisbursed = 0;
		}
		return $amountDisbursed;
	}	

	public function getExactAmountDisbursed(){
		return CommonFunctions::asMoney($this->NotFormattedExactAmountDisbursed);
	}	

	public function getTotalAmountPaid(){
		$loanID         = $this->loanaccount_id;
		$transactionSQL = "SELECT COALESCE(SUM(amount),0) AS amount FROM loantransactions WHERE loanaccount_id=$loanID AND is_void='0'";
		$transaction    = Loantransactions::model()->findBySql($transactionSQL);
		return !empty($transaction) ? $transaction->amount : 0;
	}

	public function getInterestRate(){
		return $this->interest_rate ." %";
	}

	public function getAccruedInterest(){
		return CommonFunctions::asMoney(LoanManager::getUnpaidAccruedInterest($this->loanaccount_id));
	}

    public function getPaymentFrequency(){
        return ucfirst($this->pay_mode);
    }

    public function getDailyPenalty(){
        return $this->penalty_amount;
    }

	public function getAccountPrincipalBalance(){
		return CommonFunctions::asMoney(LoanManager::getPrincipalBalance($this->loanaccount_id));
	}

	public function getAccountPenalties(){
		return CommonFunctions::asMoney(LoanManager::getUnpaidAccruedPenalty($this->loanaccount_id));
	}

	public function getAccountAmountDue(){
		return CommonFunctions::asMoney(LoanManager::getActualLoanBalance($this->loanaccount_id));
	}

	public function getAccountProfitOrLoss(){
		return CommonFunctions::asMoney(LoanApplication::getAccountTotalProfitOrLoss($this->loanaccount_id));
	} 

	public function getAccountAmountPaid(){
		return CommonFunctions::asMoney(LoanTransactionsFunctions::getTotalAmountPaid($this->loanaccount_id));
	}

	public function getCurrentMonthPayment(){
		return CommonFunctions::asMoney(LoanManager::getCurrentMonthLoanPayment($this->loanaccount_id));
	}

	public function getLoanAccountPaymentDate(){
		return date('jS',strtotime($this->repayment_start_date));
	}

	public function getAccountPaymentDate(){
		$lastDate = LoanTransactionsFunctions::getLastAccountPaymentDate($this->loanaccount_id);
		return $lastDate == 0 ? '' : $lastDate;
	}

	public function getDaysPastDisbursementDate22(){
		$zeroDaysArray = array('0','1','3','8','9','10');
		$dateDisbursed = $this->FormattedDisbursedDate;
		if(in_array($this->loan_status,$zeroDaysArray)){
			return 0;
		}else{
			return !empty($dateDisbursed) 
			? CommonFunctions::getDatesDifference(date("Y-m-d",strtotime($dateDisbursed)),date("Y-m-d")) 
			: 0;
		}
	}

	public function getDaysPastDisbursementDate(){
		$zeroDaysArray = array('0','1','3','8','9','10');
		$dateDisbursed = $this->FormattedDisbursedDate;
		if(in_array($this->loan_status,$zeroDaysArray)){
			$daysPast = 0;
		}else{
			$lastPaymentDate = LoanTransactionsFunctions::getLastAccountPaymentDate($this->loanaccount_id);
			if($lastPaymentDate == 0){
				$daysPast = 0;
			}else{
				$daysPast = !empty($dateDisbursed) 
				? CommonFunctions::getDatesDifference(date("Y-m-d",strtotime($dateDisbursed)),date("Y-m-d",strtotime($lastPaymentDate))) 
				: 0;
			}
		}
		//hereunder code is edited word "days" is removed from... return $daysPast === 1 ? $daysPast .'day' : $daysPast .'days';  to...
		return $daysPast === 1 ? $daysPast .' ' : $daysPast .' ';
	}

	public function getDaysPastLastAccountPayment(){
		$zeroDaysArray   = array('0','1','3','4','8','9','10');
		$lastPaymentDate = $this->AccountPaymentDate;
		if(in_array($this->loan_status,$zeroDaysArray)){
			return 0;
		}else{
			return $lastPaymentDate =='N/A' ? 0 : CommonFunctions::getDatesDifference($lastPaymentDate,date("Y-m-d"));
		}
	}

	public function getProfitLoss(){
		$amountApproved = $this->amount_approved;
		$amountPaid     = $this->getTotalAmountPaid();
		$difference     = $amountPaid-$amountApproved;
		echo $difference < 0 ?  "Loss : Kshs. ". number_format(abs($difference),2) : "Profit : Kshs. ". number_format($difference,2);
	}

	public function getAccountProfitLoss(){
		$amountApproved= $this->amount_approved;
		$amountPaid    = $this->getTotalAmountPaid();
		$difference    = $amountPaid-$amountApproved;
		return $difference < 0 ? "Loss : ". number_format(abs($difference),2) : "Profit : ". number_format($difference,2);
	}

	public function getLoanAccountStatus(){
		switch($this->loan_status){
			case 0:
			echo "<span class='badge badge-info'>Submitted</span>";
			break;

			case 1:
			echo "<span class='badge badge-success'>Approved</span>";
			break;

			case 2:
			echo "<span class='badge badge-success'>Disbursed</span>";
			break;

			case 3:
			echo "<span class='badge badge-danger'>Rejected</span>";
			break;

			case 4:
			echo "<span class='badge badge-warning'>Fully Paid</span>";
			break;

			case 5:
			echo "<span class='badge badge-info'>Restructured</span>";
			break;

			case 6:
			echo "<span class='badge badge-success'>Topped Up</span>";
			break;

			case 7:
			echo "<span class='badge badge-danger'>Defaulted</span>";
			break;

			case 8:
			echo "<span class='badge badge-warning'>Forwarded</span>";
			break;

			case 9:
			echo "<span class='badge badge-danger'>Returned</span>";
			break;

			case 10:
			echo "<span class='badge badge-info'>Resubmitted</span>";
			break;
		}
	}

	public function getLoanAccountPerfomanceStatus(){
		switch($this->performance_level){
			case 'A':
			echo "<span class='badge badge-success'>Normal</span>";
			break;

			case 'B':
			echo "<span class='badge badge-info'>Watch</span>";
			break;

			case 'C':
			echo "<span class='badge badge-default'>Substandard</span>";
			break;

			case 'D':
			echo "<span class='badge badge-warning'>Doubtful/Recovery</span>";
			break;

			case 'E':
			echo "<span class='badge badge-danger'>Loss/Recovery</span>";
			break;
		}
	}

	public function getEmptyLoanAccountPerfomanceStatus(){
		switch($this->performance_level){
			case 'A':
			return "Normal";
			break;

			case 'B':
			return "Watch";
			break;

			case 'C':
			return "Substandard";
			break;

			case 'D':
			return "Doubtful/Recovery";
			break;

			case 'E':
			return "Loss/Recovery";
			break;
		}
	}

	public function getEmptyLoanAccountPerfomanceStatusInitial(){
		switch($this->performance_level){
			case 'A':
			return "Normal";
			break;

			case 'B':
			return "Watch";
			break;

			case 'C':
			return "Substandard";
			break;

			case 'D':
			return "Doubtful/Recovery";
			break;

			case 'E':
			return "Loss/Recovery";
			break;
		}
	}

	public function getEmptyLoanAccountStatus(){
		switch($this->account_status){
			case 'A':
			return "Closed";
			break;

			case 'B':
			return "Dormant";
			break;

			case 'C':
			return "Write-Off";
			break;

			case 'D':
			return "Legal";
			break;

			case 'E':
			return "Collection";
			break;

			case 'F':
			return "Active";
			break;

			case 'G':
			return "Facility Rescheduled";
			break;

			case 'H':
			return "Settled";
			break;

			case 'J':
			return "Called Up";
			break;

			case 'K':
			return "Suspended";
			break;

			case 'L':
			return "Client Deceased";
			break;

			case 'M':
			return "Deferred";
			break;

			case 'N':
			return "Not Updated";
			break;

			case 'P':
			return "Disputed";
			break;
		}
	}

	public function getEmptyCurrentLoanAccountStatus(){
		switch($this->loan_status){
			case 0:
			return "Submitted";
			break;

			case 1:
			return "Approved";
			break;

			case 2:
			return "Disbursed";
			break;

			case 3:
			return "Rejected";
			break;

			case 4:
			return "Fully Paid";
			break;

			case 5:
			return "Restructured";
			break;

			case 6:
			return "Topped Up";
			break;

			case 7:
			return "Defaulted";
			break;

			case 8:
			return "Forwarded";
			break;
			
			case 9:
			return "Returned";
			break;

			case 10:
			return "Resubmitted";
			break;
		}
	}

	public function getCurrentLoanAccountStatus(){
		switch($this->loan_status){
			case 0:
			return "<span class='badge badge-info'>Submitted</span>";
			break;

			case 1:
			return "<span class='badge badge-success'>Approved</span>";
			break;

			case 2:
			return "<span class='badge badge-success'>Disbursed</span>";
			break;

			case 3:
			return "<span class='badge badge-danger'>Rejected</span>";
			break;

			case 4:
			return "<span class='badge badge-warning'>Fully Paid</span>";
			break;

			case 5:
			return "<span class='badge badge-info'>Restructured</span>";
			break;

			case 6:
			return "<span class='badge badge-warning'>Topped Up</span>";
			break;

			case 7:
			return "<span class='badge badge-danger'>Defaulted</span>";
			break;

			case 8:
			return "<span class='badge badge-warning'>Forwarded</span>";
			break;

			case 9:
			return "<span class='badge badge-danger'>Returned</span>";
			break;

			case 10:
			return "<span class='badge badge-info'>Resubmitted</span>";
			break;
		}
	}

	public function getCurrentPenaltyStatus($is_paid){
		return $is_paid==='0' ? "<span class='badge badge-danger'>Not Paid</span>" :  "<span class='badge badge-success'>Fully Paid</span>";
	}

	public function getLoanSecurityStatus(){
		return $this->loan_security =='0' ? "S" : "U";
	}

	public function getFullLoanSecurityStatus(){
		return $this->loan_security =='0' ? "SECURED" : "NON-SECURED";
	}

    public function getMemberType(){
        return $this->member_type =='0' ? "SALARIED" : "BUSINESS";
    }
	public function getLoanAccountProduct(){
		switch($this->loan_type){
			case '0':
			$accountProduct="H";
			break;

			case '1':
			$accountProduct="D";
			break;

			case '2':
			$accountProduct="C";
			break;

			default:
			$accountProduct="N";
			break;

		}
		return strtoupper($accountProduct);
	}

	public function getPrudentialRisk(){
		$arrearsDays=$this->DaysInArrears;
		if($arrearsDays <= 0){
			$risk="A";
			$crbStatus="a";	
		}elseif($arrearsDays >= 0 && $arrearsDays <=30){
			$risk="A";
			$crbStatus="a";
		}elseif($arrearsDays >30 && $arrearsDays <=90 ){
			$risk="B";
			$crbStatus="a";
		}elseif($arrearsDays >90 && $arrearsDays <=180){
			$risk="C";
			$crbStatus="a";
		}elseif($arrearsDays >180 && $arrearsDays <=360){
			$risk="D";
			$crbStatus="b";
		}else{
			$risk="E";
			$crbStatus="b";
		}
		return $risk;
	}

	public function getLoanAccountInstallmentDueDate(){
		$accountID  = $this->loanaccount_id;
		$matureQuery= "SELECT * FROM loan_maturities WHERE loanaccount_id=$accountID";
		$maturity   = LoanMaturities::model()->findBySql($matureQuery);
		return !empty($maturity) ? date("Y-m-d",strtotime($maturity->maturity_date)) : date("Y-m-d");
	}

	public function getLoanAccountLastPaymentDate(){
		$accountID       = $this->loanaccount_id;
		$transactionQuery= "SELECT * FROM loantransactions WHERE loanaccount_id=$accountID AND is_void IN('0','3')
		ORDER BY loantransaction_id DESC LIMIT 1";
		$transaction     = Loantransactions::model()->findBySql($transactionQuery);
		return !empty($transaction) ? date("Ymd",strtotime($transaction->transacted_at)) : "";
	}

	public function getLoanAccountLastPaymentAmount(){
		$accountID = $this->loanaccount_id;
		$transactionQuery="SELECT * FROM loantransactions WHERE loanaccount_id=$accountID AND is_void IN('0','3')
		ORDER BY loantransaction_id DESC LIMIT 1";
		$transaction=Loantransactions::model()->findBySql($transactionQuery);
		return !empty($transaction) ? $transaction->amount : 0;
	}

	public function getAction(){
		$loanStatus=$this->loan_status;
		$loanID=$this->loanaccount_id;
		/*******
		 UPDATE LOAN
		***************/
		if(Navigation::checkIfAuthorized(31) == 1){
			$arrayStatus=array('3');
		if(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus) == 0){
			$update_action="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('loanaccounts/update/'.$this->loanaccount_id)."\")' title='Update Account'><i class='fa fa-edit'></i></a>";
		}else{
			$update_action="";
		}
		}else{
			$update_action="";
		}
		/*******
		 VIEW LOAN
		***************/
		if(Navigation::checkIfAuthorized(32) == 1){
		$arrayStatus=array('0');
			if(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus) == 0){
				$view_action="<a href='".Yii::app()->createUrl('loanaccounts/'.$this->loanaccount_id)."' title='View Account Details' class='btn btn-info btn-sm'><i class='fa fa-eye'></i></a>";
			}else{
				$view_action="";
			}
		}else{
			$view_action="";
		}
        /*******
         * Freeze Penalty
         */
        if(Navigation::checkIfAuthorized(51) == 1) {
            $arrayStatus = array('0', '1', '3', '4', '8', '9', '10');
            if (CommonFunctions::searchElementInArray($loanStatus, $arrayStatus) == 0) {
                $penalty_action = "<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"" . Yii::app()->createUrl('loanaccounts/freeze_penalty/' . $this->loanaccount_id) . "\")' title='Freeze Penalty'><i class='fa fa-adjust'></i></a>";
            } else {
                $penalty_action = "";
            }
        }else{
            $penalty_action = "";
        }

		/*******
		 DELETE LOAN
		***************/
		if(Navigation::checkIfAuthorized(33) == 1){
		$arrayStatus=array('0','1','3','8','9','10');
		if(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus) == 1){
			$delete_action="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('loanaccounts/delete/'.$this->loanaccount_id)."\")' title='Delete Account'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_action="";
		}
		}else{
			$delete_action="";
		}
		/*******
		 DISBURSE LOAN
		***************/
		if(Navigation::checkIfAuthorized(36) == 1){
		$arrayStatus=array('1');
		if(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus) == 1){
				$disburse_action="<a href='#' class='btn btn-info btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('loanaccounts/disburse/'.$this->loanaccount_id)."\")' title='Disburse Account'><i class='fa fa-random'></i></a>";
		}else{
			$disburse_action="";
		}
		}else{
			$disburse_action="";
		}
		/**********
		 RESUBMIT LOAN
		*****************/
		if(Navigation::checkIfAuthorized(46) == 1){
		$arrayStatus=array('0','9');
		if(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus) == 1){
			if($this->created_by === Yii::app()->user->user_id){
				if(LoanApplication::checkIfApplicationReturned($this->loanaccount_id) === 0){
					$resubmit_action="";
				}else{
					$resubmit_action="<a href='".Yii::app()->createUrl('loanaccounts/resubmit/'.$this->loanaccount_id)."' title='Resubmit Account' class='btn btn-info btn-sm'><i class='fa fa-mail-forward'></i></a>";
				}
			}else{
				$resubmit_action="";
			}
		}else{
			$resubmit_action="";
		}
		}else{
			$resubmit_action="";
		}
		/**********
		 RECALL LOAN
		*****************/
		if(Navigation::checkIfAuthorized(41) == 1){
		$arrayStatus=array('0');
		if(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus) == 1){
			if($this->created_by === Yii::app()->user->user_id){
				$recall_action="";
				//$recall_action="<a href='".Yii::app()->createUrl('loanaccounts/recall/'.$this->loanaccount_id)."' title='Recall Account' class='btn btn-warning btn-sm'><i class='fa fa-reply-all'></i></a>";
			}else{
				$recall_action="";
			}
		}else{
			$recall_action="";
		}
		}else{
			$recall_action="";
		}
		/*************
		 VIEW LOAN DETAILS
		*******************/
		if(Navigation::checkIfAuthorized(50) == 1){
		$arrayStatus=array('0','10');
		if(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus) == 1){
			$details_action="<a href='".Yii::app()->createUrl('loanaccounts/viewDetails/'.$this->loanaccount_id)."' title='View Loan Details' class='btn btn-default btn-sm'><i class='fa fa-bullhorn'></i></a>";
		}else{
			$details_action="";
		}
		}else{
			$details_action="";
		}
		/*************
		 VIEW TOP UP DETAILS
		*******************/
		if(Navigation::checkIfAuthorized(42) == 1){
		$arrayStatus=array('6');
		if(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus) == 1){
				if(LoanApplication::topUpApprovedOrRejected($this->loanaccount_id) === 1){
					$topup_action="<a href='#' class='btn btn-default btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('loanaccounts/viewTopup/'.$this->loanaccount_id)."\")' title='View Loan Top Up'><i class='fa fa-bolt'></i></a>";
				}else{
					$topup_action="";
				}
			}else{
				$topup_action="";
			}
		}else{
			$topup_action="";
		}
		/*************
		 RETURN ACCOUNT
		*******************/
		if(Navigation::checkIfAuthorized(39) == 1){
		$arrayStatus=array('0');
		if(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus) == 1){
			if($this->created_by != Yii::app()->user->user_id){
					$returnQuery="SELECT * FROM loan_redirects WHERE loanaccount_id=$loanID";
					$redirected=LoanRedirects::model()->findAllBySql($returnQuery);
					if(empty($redirected)){
						$return_action="<a href='#' class='btn btn-info btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('loanaccounts/return/'.$this->loanaccount_id)."\")' title='Return Account'><i class='fa fa-random'></i></a>";
					}elseif(!empty($redirected)){
						$return_action="";
					}
				}else{
					$return_action="";
				}
			}else{
				$return_action="";
			}
		}else{
			$return_action="";
		}
		/*************
		 FREEZE ACCRUAL
		*******************/
		if(Navigation::checkIfAuthorized(138) == 1){
		$arrayStatus=array('0','1','3','4');
		if(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus) == 0){
				if($this->is_frozen  === '0'){
					$freeze_action="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('loanaccounts/freeze/'.$this->loanaccount_id)."\")' title='Freeze Interest Accrual'><i class='fa fa-asterisk'></i></a>";
				}else{
					$freeze_action="<a href='#' class='btn btn-default btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('loanaccounts/unfreeze/'.$this->loanaccount_id)."\")' title='Unfreeze Interest Accrual'><i class='fa fa-history'></i></a>";
				}
			}else{
				$freeze_action="";
			}
		}else{
			$freeze_action="";
		}

		$authOnly = array('10');
		switch(CommonFunctions::searchElementInArray($loanStatus,$authOnly)){
			case 0:
			$accounts_actions="$view_action&nbsp;$details_action&nbsp;$update_action&nbsp;$penalty_action
			&nbsp;$resubmit_action&nbsp;$recall_action&nbsp;$return_action
			&nbsp;$disburse_action&nbsp;$topup_action&nbsp;$delete_action&nbsp;$freeze_action";
			break;

			case 1:
			$accounts_actions="$details_action&nbsp;$update_action&nbsp;$penalty_action
			&nbsp;$delete_action";
			break;
		}
		echo $accounts_actions;
	}

/**
 * Returns the static model of the specified AR class.
 * Please note that you should have this exact method in all your CActiveRecord descendants!
 * @param string $className active record class name.
 * @return Loanaccounts the static model class
 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

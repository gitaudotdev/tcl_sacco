<?php

/**
 * This is the model class for table "loanrepayments".
 *
 * The followings are the available columns in table 'loanrepayments':
 * @property integer $loanrepayment_id
 * @property integer $loanaccount_id
 * @property integer $loantransaction_id
 * @property string $date
 * @property string $principal_paid
 * @property string $interest_paid
 * @property string $fee_paid
 * @property string $penalty_paid
 * @property string $is_void
 * @property integer $repaid_by
 * @property string $repaid_at
 */
class Loanrepayments extends CActiveRecord{

	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'loanrepayments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loanaccount_id, loantransaction_id, date, repaid_by,branch_id,rm', 'required'),
			array('loanaccount_id, loantransaction_id, repaid_by', 'numerical', 'integerOnly'=>true),
			array('principal_paid, interest_paid, fee_paid, penalty_paid', 'length', 'max'=>15),
			array('is_void', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('loanrepayment_id,loanaccount_id, loantransaction_id, date, principal_paid,phone_transacted,user_id,interest_paid, fee_paid, penalty_paid, is_void, repaid_by, repaid_at,startDate,endDate,branch_id,rm', 'safe', 'on'=>'search'),
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
	public function attributeLabels(){
		return array(
			'loanrepayment_id' => 'Loanrepayment',
			'loanaccount_id' => 'Loanaccount',
			'loantransaction_id' => 'Loantransaction',
			'branch_id' => 'Branch',
			'user_id' => 'User',
			'phone_transacted' => 'Transacting Phone',
			'rm' => 'Relation Manager',
			'date' => 'Date',
			'principal_paid' => 'Principal Paid',
			'interest_paid' => 'Interest Paid',
			'fee_paid' => 'Fee Paid',
			'penalty_paid' => 'Penalty Paid',
			'is_void' => 'Is Void',
			'repaid_by' => 'Repaid By',
			'repaid_at' => 'Repaid At',
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
		$criteria->compare("$alias.loanrepayment_id",$this->loanrepayment_id);
		$criteria->compare("$alias.loanaccount_id",$this->loanaccount_id);
		$criteria->compare("$alias.loantransaction_id",$this->loantransaction_id);
		$criteria->compare("$alias.branch_id",$this->branch_id);
		$criteria->compare("$alias.rm",$this->rm);
		$criteria->compare("$alias.user_id",$this->user_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('principal_paid',$this->principal_paid,true);
		$criteria->compare('interest_paid',$this->interest_paid,true);
		$criteria->compare('fee_paid',$this->fee_paid,true);
		$criteria->compare('penalty_paid',$this->penalty_paid,true);
		$criteria->compare('is_void',$this->is_void,true);
		$criteria->compare("$alias.repaid_by",$this->repaid_by);
		$criteria->compare("$alias.repaid_at",$this->repaid_at,true);
		//$criteria->compare("$alias.is_void",'0',true);
		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.repaid_at)",$this->startDate, $this->endDate, 'AND');
		}

		switch(Yii::app()->user->user_level){
			case'1':
			$criteria->addCondition('branch_id ='.Yii::app()->user->user_branch);
			break;

			case'2':
			$criteria->addCondition('rm ='.Yii::app()->user->user_id);
			break;

			case'2':
			$criteria->addCondition('user_id ='.Yii::app()->user->user_id);
			break;
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'loanrepayment_id DESC',
			),
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}

	public function getLoanRepaymentBranch(){
		$branch = Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ? $branch->name : "UNDEFINED";
	}

	public function getLoanRepaymentManager(){
		$user = Profiles::model()->findByPk($this->rm);
		return !empty($user) ? $user->ProfileFullName: "UNDEFINED";
	}

	public function getSaccoBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getLoanAcountNumbersList(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$accountsQuery="SELECT * FROM loanaccounts WHERE loanaccount_id IN(SELECT loanaccount_id FROM loanrepayments) AND loan_status <>'3'";
		switch(Yii::app()->user->user_level){
			case '0':
			$accountsQuery.="";
			break;

			case '1':
			$accountsQuery.=" AND rm=$userID";
			break;

			case '3':
			$accountsQuery.=" AND user_id=$userID";
			break;
		}
		return CHtml::listData(Loanaccounts::model()->findAllBySql($accountsQuery),'loanaccount_id','AccountDetails');
	}

	public function getBorrowerList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('ALL'),'id','ProfileNameWithIdNumber');
	}

	public function getRelationshipManagers(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getLoanRepaidByList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getFormattedTransactionDate(){
		return date('d/m/Y h:i A',strtotime($this->repaid_at));
	}

	public function getFormattedClearTransactionDate(){
		return date('jS M Y h:i A',strtotime($this->repaid_at));
	}

	public function getDisbursedMonth(){
		$loanID      = $this->loanaccount_id;
		$loanaccount = Loanaccounts::model()->findByPk($loanID);
		$loanStatus  = $loanaccount->loan_status;
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
		$loanaccount = Loanaccounts::model()->findByPk($loanID);
		$loanStatus  = $loanaccount->loan_status;
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
			$amountDisbursed = $account->amount_disbursed < 0 ? 0 : $account->amount_disbursed;
		}else{
			$amountDisbursed = 0;
		}
		return $amountDisbursed;
	}	

	public function getMaturityDate(){
		$loanID  = $this->loanaccount_id;
		$loanSQL = "SELECT * FROM loan_maturities WHERE loanaccount_id=$loanID";
		$loan    = LoanMaturities::model()->findBySql($loanSQL);
		return !empty($loan) ? date('jS M Y',strtotime($loan->maturity_date)) : "UNDEFINED";
	}

	public function getBorrowerPhone(){
		$loanaccount = Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loanaccount) ? $loanaccount->BorrowerPhoneNumber : "UNDEFINED";
	}

	public function getPrincipalPaid(){
		return CommonFunctions::asMoney($this->principal_paid);
	}

	public function getInterestPaid(){
		return CommonFunctions::asMoney($this->interest_paid);
	}

	public function getFeePaid(){
		return CommonFunctions::asMoney($this->fee_paid);
	}

	public function getPenaltyPaid(){
		return CommonFunctions::asMoney($this->penalty_paid);
	}

	public function getLoanAccountNumber(){
		$loanaccount = Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loanaccount) ? $loanaccount->account_number : "UNDEFINED";
	}

	public function getLoanBorrowerName(){
		$loanaccount = Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loanaccount) ? $loanaccount->BorrowerFullName: "UNDEFINED";
	}

	public function getLoanAccountMemberEmployer(){
		$loanaccount = Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loanaccount) ? $loanaccount->BorrowerEmployerAlt: "UNDEFINED";
	}

	public function getLoanAccountBalance(){
		return CommonFunctions::asMoney(LoanManager::getActualLoanBalance($this->loanaccount_id));
	}

	public function getTotalAmountPaid(){
		$loantransaction = Loantransactions::model()->findByPk($this->loantransaction_id);
		if(!empty($loantransaction)){
			$totalAmountPaid = $loantransaction->is_void !='1' ? $loantransaction->amount : 0;
		}else{
			$totalAmountPaid = 0;
		}
		return CommonFunctions::asMoney($totalAmountPaid);
	}

	public function getTransactedBy(){
		return $this->RepaymentTransactingPhone;
	}

	public function getRepaymentTransactingPhone(){
		return $this->phone_transacted === Yii::app()->params['DEFAULTREPAYMENTPHONE'] ? $this->phone_transacted : '0'.substr($this->phone_transacted,-9);
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(64) == 1){
			$deleteLink="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('loanrepayments/void/'.$this->loanrepayment_id)."\")'><i class='fa fa-trash'></i></a>";
		}else{
			$deleteLink="";
		}

		if(Navigation::checkIfAuthorized(63) == 1){
			$updateLink="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('loanrepayments/update/'.$this->loanrepayment_id)."\")'><i class='fa fa-edit'></i></a>";
		}else{
			$updateLink="";
		}
		$actionLink = "$updateLink&nbsp;$deleteLink";
		
		echo $actionLink;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Loanrepayments the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

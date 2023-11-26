<?php
/**
 * This is the model class for table "fixed_payments".
 *
 * The followings are the available columns in table 'fixed_payments':
 * @property integer $id
 * @property string $batch_number
 * @property integer $expensetype_id
 * @property integer $user_id
 * @property integer $branch_id
 * @property integer $rm
 * @property string $amount
 * @property string $status
 * @property integer $created_by
 * @property string $created_at
 */
class FixedPayments extends CActiveRecord{

	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'fixed_payments';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		return array(
			array('batch_number,expensetype_id, user_id,branch_id,rm,amount,created_by,expense_month,expense_year', 'required'),
			array('expensetype_id, user_id, branch_id, rm, created_by', 'numerical', 'integerOnly'=>true),
			array('batch_number', 'length', 'max'=>100),
			array('amount', 'length', 'max'=>15),
			array('status', 'length', 'max'=>1),
			array('id, batch_number, expensetype_id, user_id,branch_id, rm, amount, status, created_by, created_at,startDate,endDate,approved_by,rejected_by,disbursed_by,cancelled_by','safe','on'=>'search'),
		);
	}
	/**
	 * @return array relational rules.
	 */
	public function relations(){
		return array(
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'batch_number' => 'Batch Number',
			'expensetype_id' => 'Expensetype',
			'user_id' => 'User',
			'branch_id' => 'Branch',
			'rm' => 'Rm',
			'amount' => 'Amount',
			'status' => 'Status',
			'created_by' => 'Created By',
			'created_at' => 'Created At',
			'startDate'  => 'Date Started',
			'endDate'    => 'Date Ended',
		);
	}
	/**
	 * FILTERS
	 */
	public function search(){
		$alias = $this->getTableAlias(false,false);
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('batch_number',$this->batch_number,true);
		$criteria->compare('expensetype_id',$this->expensetype_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('rm',$this->rm);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('expense_year',$this->expense_year,true);
		$criteria->compare('expense_month',$this->expense_month,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.created_at)",$this->startDate, $this->endDate, 'AND');
		}

		switch(Yii::app()->user->user_level){
			case '0':
			break;

			case '1':
			$criteria->addCondition('branch_id ='.Yii::app()->user->user_branch);
			break;

			case '2':
			$criteria->addCondition('rm ='.Yii::app()->user->user_id);
			break;

			default:
			$criteria->addCondition('user_id ='.Yii::app()->user->user_id);
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

	public function getFixedPaymentBranches(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getFixedPaymentTypes(){
		return CHtml::listData(ExpenseTypes::model()->findAll(),'expensetype_id','ExpenseTypeName');
	}

	public function getFixedPaymentSuppliers(){
		$userBranch     = Yii::app()->user->user_branch;
		$userID         = Yii::app()->user->user_id;
		$suppliersQuery = "SELECT * FROM profiles WHERE id IN(SELECT profileId FROM account_settings WHERE configType='FIXED_PAYMENT_LISTED' AND configValue='ACTIVE')";
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
		return CHtml::listData(Profiles::model()->findAllBySql($suppliersQuery),'id','ProfileSavingAccount');
	}

	public function getFixedPaymentsInitiators(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileSavingAccount');
	}

	public function getFixedPaymentBatchNumber(){
		return $this->batch_number;
	}

	public function getFixedPaymentExpenseTypeName(){
		$expenseType = ExpenseTypes::model()->findByPk($this->expensetype_id);
		return !empty($expenseType) ? strtoupper($expenseType->name) : "UNDEFINED"; 
	}

	public function getFixedPaymentSupplierName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getFixedPaymentSupplierAccountNumber(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfilePhoneNumber : "UNDEFINED";
	}

	public function getFixedPaymentSupplierMaximumLimit(){
		$profile = Profiles::model()->findByPk($this->user_id);
		$defaultLimit = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_LIMIT');
		return $defaultLimit === 'NOT SET' ? CommonFunctions::asMoney(Yii::app()->params['DEFAULTMAXLOANAMOUNT']) : CommonFunctions::asMoney(floatval($defaultLimit));
	}

	public function getFixedPaymentSupplierMaximumLimitRough(){
		$profile      = Profiles::model()->findByPk($this->user_id);
		$defaultLimit = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_LIMIT');
		return $defaultLimit === 'NOT SET' ? Yii::app()->params['DEFAULTMAXLOANAMOUNT'] : floatval($defaultLimit);
	}

	public function getFixedPaymentSupplierBranchName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? strtoupper($profile->ProfileBranch) : "UNDEFINED"; 
	}

	public function getFixedPaymentSupplierManager(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileManager : "UNDEFINED";
	}

	public function getFixedPaymentAmount(){
		return CommonFunctions::asMoney($this->amount);
	}

	public function getFixedPaymentStatus(){
		switch($this->status){
			case '0':
			$statusName='Initiated';
			break;

			case '1':
			$statusName='Approved';
			break;

			case '2':
			$statusName='Disbursed';
			break;

			case '3':
			$statusName='Rejected';
			break;

			case '4':
			$statusName='Cancelled';
			break;
		}
		return $statusName;
	}

	public function getFormattedFixedPaymentStatus(){
		switch($this->status){
			case '0':
			$statusName='<p class="text-info">';
			break;

			case '1':
			case '2':
			$statusName='<p class="text-success">';
			break;

			default:
			$statusName='<p class="text-danger">';
			break;
		}
		return $statusName.strtoupper($this->FixedPaymentStatus).'</p>';
	}

	public function getFixedPaymentPeriod(){
		$monthPeriod = $this->expense_month."-".$this->expense_year;
		return CommonFunctions::getRespectiveMonth($monthPeriod);
	}

	public function getFixedPaymentCreatedBy(){
		$profile = Profiles::model()->findByPk($this->created_by);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getFixedPaymentApprovedBy(){
		$profile = Profiles::model()->findByPk($this->approved_by);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getFixedPaymentRejectedBy(){
		$profile = Profiles::model()->findByPk($this->rejected_by);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getFixedPaymentDisbursedBy(){
		$profile = Profiles::model()->findByPk($this->disbursed_by);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getFixedPaymentCancelledBy(){
		$profile = Profiles::model()->findByPk($this->cancelled_by);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getFixedPaymentCreatedAt(){
		return is_null($this->created_at) ? "UNDEFINED" : date('jS M Y',strtotime($this->created_at));
	}

	public function getAction(){
		$element = $this->status;
    	$array   = array('2','3','4');
		if(Navigation::checkIfAuthorized(246) == 1){
			$view_link = "<a href='#' class='btn btn-info btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('fixedPayments/'.$this->id)."\")'><i class='fa fa-eye'></i></a>";
		}else{
			$view_link = "";
		}

		if(Navigation::checkIfAuthorized(245) == 1){
			if(CommonFunctions::searchElementInArray($element,$array) == 0){
				$update_link = "<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('fixedPayments/update/'.$this->id)."\")'><i class='fa fa-edit'></i></a>";
			}else{
				$update_link = "";
			}
		}else{
			$update_link = "";
		}
		echo $view_link."&emsp;".$update_link;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FixedPayments the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

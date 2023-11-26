<?php

/**
 * This is the model class for table "out_payments".
 *
 * The followings are the available columns in table 'out_payments':
 * @property integer $id
 * @property integer $expensetype_id
 * @property integer $branch_id
 * @property integer $user_id
 * @property integer $rm
 * @property integer $expense_id
 * @property string $status
 * @property string $amount
 * @property integer $initiated_by
 * @property string $initiation_reason
 * @property integer $approved_by
 * @property string $approval_reason
 * @property integer $rejected_by
 * @property string $rejection_reason
 * @property integer $disbursed_by
 * @property string $disbursal_reason
 * @property integer $cancelled_by
 * @property string $cancellation_reason
 * @property string $initiated_at
 * @property string $approved_at
 * @property string $rejected_at
 * @property string $disbursed_at
 * @property string $cancelled_at
 */
class OutPayments extends CActiveRecord{

	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'out_payments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('expensetype_id, branch_id, user_id, rm, amount, outpayment_date,outpayment_status,outpayment_recur_date,initiated_by, initiation_reason', 'required'),
			array('expensetype_id, branch_id, user_id, rm, expense_id, initiated_by, approved_by, rejected_by, disbursed_by, cancelled_by', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>1),
			array('amount', 'length', 'max'=>15),
			array('initiation_reason, approval_reason, disbursal_reason, cancellation_reason', 'length', 'max'=>250),
			array('rejection_reason', 'length', 'max'=>259),
			array('approved_at, rejected_at, disbursed_at, cancelled_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, expensetype_id,branch_id,user_id,rm,expense_id, status, amount, initiated_by, initiation_reason, approved_by, approval_reason, rejected_by, rejection_reason, disbursed_by, disbursal_reason,cancelled_by, cancellation_reason,initiated_at, approved_at, rejected_at, disbursed_at,cancelled_at,startDate,endDate,outpayment_receipt,startDate,endDate','safe','on'=>'search'),
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
			'expensetype_id' => 'Expensetype',
			'branch_id' => 'Branch',
			'user_id' => 'User',
			'rm' => 'Rm',
			'expense_id' => 'Expense',
			'status' => 'Status',
			'amount' => 'Amount',
			'outpayment_receipt' => 'Outpayment Receipt',
			'outpayment_status' => 'Outpayment Recurring Status',
			'outpayment_date' => 'Outpayment Date',
			'outpayment_recur_date' => 'Outpayment Recurring Date',
			'initiated_by' => 'Initiated By',
			'initiation_reason' => 'Initiation Reason',
			'approved_by' => 'Approved By',
			'approval_reason' => 'Approval Reason',
			'rejected_by' => 'Rejected By',
			'rejection_reason' => 'Rejection Reason',
			'disbursed_by' => 'Disbursed By',
			'disbursal_reason' => 'Disbursal Reason',
			'cancelled_by' => 'Cancelled By',
			'cancellation_reason' => 'Cancellation Reason',
			'initiated_at' => 'Initiated At',
			'approved_at' => 'Approved At',
			'rejected_at' => 'Rejected At',
			'disbursed_at' => 'Disbursed At',
			'cancelled_at' => 'Cancelled At',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('expensetype_id',$this->expensetype_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('rm',$this->rm);
		$criteria->compare('expense_id',$this->expense_id);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('outpayment_status',$this->outpayment_status,true);
		$criteria->compare('outpayment_date',$this->outpayment_date,true);
		$criteria->compare('outpayment_recur_date',$this->outpayment_recur_date,true);
		$criteria->compare('initiated_by',$this->initiated_by);
		$criteria->compare('initiation_reason',$this->initiation_reason,true);
		$criteria->compare('approved_by',$this->approved_by);
		$criteria->compare('approval_reason',$this->approval_reason,true);
		$criteria->compare('rejected_by',$this->rejected_by);
		$criteria->compare('rejection_reason',$this->rejection_reason,true);
		$criteria->compare('disbursed_by',$this->disbursed_by);
		$criteria->compare('disbursal_reason',$this->disbursal_reason,true);
		$criteria->compare('cancelled_by',$this->cancelled_by);
		$criteria->compare('cancellation_reason',$this->cancellation_reason,true);
		$criteria->compare('initiated_at',$this->initiated_at,true);
		$criteria->compare('approved_at',$this->approved_at,true);
		$criteria->compare('rejected_at',$this->rejected_at,true);
		$criteria->compare('disbursed_at',$this->disbursed_at,true);
		$criteria->compare('cancelled_at',$this->cancelled_at,true);

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.initiated_at)",$this->startDate, $this->endDate, 'AND');
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

	public function getEligibleSupplierList(){
		$profileBranchID = Yii::app()->user->user_branch;
		$profileID       = Yii::app()->user->user_id;
        $profileQuery    = "SELECT * FROM profiles WHERE profileType IN('STAFF','SUPPLIER')";
		switch(Yii::app()->user->user_level){
			case '0':
			$profileQuery.="";
			break;

			case '1':
            $profileQuery.=" AND branchId=$profileBranchID";
			break;

            case '2':
            $profileQuery.=" AND managerId=$profileID";
            break;

            case '3':
            $profileQuery.=" AND id=$profileID";
            break;
		}
        $profileQuery.="  ORDER BY firstName,lastName ASC";
		return CHtml::listData(Profiles::model()->findAllBySql($profileQuery),'id','ProfileSavingAccount');
	}

	public function getExpenseTypeList(){
		return CHtml::listData(ExpenseTypes::model()->findAll(),'expensetype_id','name');
	}

	public function getOutPaymentRecurringList(){
		$frequency_array=array();
		for($i=0;$i<=30;$i++){
			array_push($frequency_array,$i);
		}
		return $frequency_array;
	}

	public function getEligibleRelationManagerList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileSavingAccount');
	}

	public function getOutPaymentBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getOutPaymentExpenseType(){
		$expenseType = ExpenseTypes::model()->findByPk($this->expensetype_id);
		return !empty($expenseType) ? strtoupper($expenseType->name) : "UNDEFINED"; 
	}

	public function getOutPaymentBranch(){
		$branch=Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ? strtoupper($branch->name) : "UNDEFINED";
	}

	public function getOutPaymentSupplier(){
		$user = Profiles::model()->findByPk($this->user_id);
		return !empty($user) ? strtoupper($user->ProfileFullName) : "UNDEFINED";
	}

	public function getOutPaymentRelationManager(){
		$user = Profiles::model()->findByPk($this->rm);
		return !empty($user) ? strtoupper($user->ProfileFullName) : "UNDEFINED";
	}

	public function getOutPaymentStatus(){
		switch($this->status){
			case '0':
			echo "<span class='badge badge-info'>Initiated</span>";
			break;

			case '1':
			echo "<span class='badge badge-success'>Approved</span>";
			break;

			case '2':
			echo "<span class='badge badge-success'>Disbursed</span>";
			break;

			case '3':
			echo "<span class='badge badge-danger'>Rejected</span>";
			break;

			case '4':
			echo "<span class='badge badge-danger'>Cancelled</span>";
			break;
		}
	}

	public function getOutPaymentAmount(){
		return CommonFunctions::asMoney($this->amount);
	}

	public function getOutPaymentInitiatedBy(){
		if(is_null($this->initiated_by)){
			$initiatorName="N/A"; 
		}else{
			$user = Profiles::model()->findByPk($this->initiated_by);
			$initiatorName = !empty($user) ? strtoupper($user->ProfileFullName) : "UNDEFINED";
		}
		return strtoupper($initiatorName);
	}

	public function getOutPaymentInitiatedAt(){
		return is_null($this->initiated_at) ? "UNDEFINED" : date('jS M Y',strtotime($this->initiated_at));
	}

	public function getOutPaymentApprovedBy(){
		if(is_null($this->approved_by)){
			$approverName ="UNDEFINED"; 
		}else{
			$user = Profiles::model()->findByPk($this->approved_by);
			$approverName = !empty($user) ? strtoupper($user->ProfileFullName) : "UNDEFINED";
		}
		return strtoupper($approverName);
	}

	public function getOutPaymentApprovedAt(){
		return is_null($this->approved_at) ? "UNDEFINED" : date('jS M Y',strtotime($this->approved_at));
	}

	public function getOutPaymentRejectedBy(){
		if(is_null($this->rejected_by)){
			$rejectedBy="UNDEFINED"; 
		}else{
			$user = Profiles::model()->findByPk($this->rejected_by);
			$rejectedBy = !empty($user) ? strtoupper($user->ProfileFullName) : "UNDEFINED";
		}
		return strtoupper($rejectedBy);
	}

	public function getOutPaymentRejectedAt(){
		return is_null($this->rejected_at) ? "UNDEFINED" : date('jS M Y',strtotime($this->rejected_at));
	}

	public function getOutPaymentDisbursedBy(){
		if(is_null($this->disbursed_by)){
			$disbursedBy = "UNDEFINED"; 
		}else{
			$user = Profiles::model()->findByPk($this->disbursed_by);
			$disbursedBy = !empty($user) ? strtoupper($user->ProfileFullName) : "UNDEFINED";
		}
		return strtoupper($disbursedBy);
	}

	public function getOutPaymentDisbursedAt(){
		return is_null($this->disbursed_at) ? "UNDEFINED" : date('jS M Y',strtotime($this->disbursed_at));
	}

	public function getOutPaymentCancelledBy(){
		if(is_null($this->cancelled_by)){
			$cancelledBy="UNDEFINED"; 
		}else{
			$user = Profiles::model()->findByPk($this->cancelled_by);
			$cancelledBy = !empty($user) ? strtoupper($user->ProfileFullName) : "UNDEFINED";
		}
		return strtoupper($cancelledBy);
	}

	public function getOutPaymentCancelledAt(){
		return is_null($this->cancelled_at) ? "UNDEFINED" : date('jS M Y',strtotime($this->cancelled_at));
	}

	public function getOutPaymentDate(){
		return date('jS M Y',strtotime($this->outpayment_date));
	}

	public function getOutPaymentRecurringStatus(){
		return $this->outpayment_status =='0' ? "NOT RECURRING" : "RECURRING";
	}

	public function getOutPaymentRecurringDate(){
		return $this->outpayment_status == '0' ? "UNDEFINED" : $this->outpayment_recur_date;
	}

	public function getAction(){

		$statusHayStack=array('2','3','4');

		if(Navigation::checkIfAuthorized(210) == 1){
			$view_link="<a href='#' class='btn btn-info btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('outPayments/'.$this->id)."\")' title='View Payment'><i class='fa fa-eye'></i></a>";
		}else{
			$view_link="";
		}

		if(CommonFunctions::searchElementInArray($this->status,$statusHayStack) === 0){
			if(Navigation::checkIfAuthorized(209) == 1){
				$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('outPayments/update/'.$this->id)."\")' title='Update Payment'><i class='fa fa-edit'></i></a>";
			}else{
				$update_link="";
			}
		}else{
			$update_link="";
		}
		$action_links="$update_link&nbsp;$view_link";
		
		echo $action_links;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OutPayments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

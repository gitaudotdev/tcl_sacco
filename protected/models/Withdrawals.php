<?php

/**
 * This is the model class for table "withdrawals".
 *
 * The followings are the available columns in table 'withdrawals':
 * @property integer $id
 * @property integer $savingaccount_id
 * @property integer $user_id
 * @property integer $branch_id
 * @property string $amount
 * @property string $is_approved
 * @property integer $approver
 * @property string $withdrawal_reason
 * @property string $authorization_reason
 * @property string $date_authorized
 * @property string $created_at
 */
class Withdrawals extends CActiveRecord
{
	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'withdrawals';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('savingaccount_id, user_id, branch_id, amount, approver, withdrawal_reason', 'required'),
			array('savingaccount_id, user_id, branch_id, approver', 'numerical', 'integerOnly'=>true),
			array('amount', 'length', 'max'=>15),
			array('is_approved,type', 'length', 'max'=>1),
			array('withdrawal_reason, authorization_reason', 'length', 'max'=>512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, savingaccount_id, user_id, branch_id, amount, is_approved, approver, withdrawal_reason, authorization_reason, date_authorized, created_at,type,startDate,endDate', 'safe', 'on'=>'search'),
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
			'savingaccount_id' => 'Savingaccount',
			'user_id' => 'User',
			'branch_id' => 'Branch',
			'amount' => 'Amount',
			'is_approved' => 'Is Approved',
			'type' => 'Withdrawal Type',
			'approver' => 'Approver',
			'withdrawal_reason' => 'Withdrawal Reason',
			'authorization_reason' => 'Authorization Reason',
			'date_authorized' => 'Date Authorized',
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
		$criteria->compare('id',$this->id);
		$criteria->compare('savingaccount_id',$this->savingaccount_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('is_approved',$this->is_approved,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('approver',$this->approver);
		$criteria->compare('withdrawal_reason',$this->withdrawal_reason,true);
		$criteria->compare('authorization_reason',$this->authorization_reason,true);
		$criteria->compare('date_authorized',$this->date_authorized,true);
		$criteria->compare('created_at',$this->created_at,true);

		if(isset($this->startDate) && isset($this->endDate)){
				$criteria->addBetweenCondition("DATE($alias.created_at)",$this->startDate, $this->endDate, 'AND');
		}else{
			$start_date=date('Y-m-01');
			$end_date=date('Y-m-t');
			$criteria->addBetweenCondition("DATE($alias.created_at)",$start_date, $end_date, 'AND');
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
         'defaultOrder'=>'id DESC',
      ),
			'pagination'=>array(
         'pageSize'=>10
       ),
		));
	}

	public function getSavingAccountNumbersList(){
		$accountsQuery ="SELECT * FROM savingaccounts WHERE is_approved='1'";
		return CHtml::listData(Savingaccounts::model()->findAllBySql($accountsQuery),'savingaccount_id','AccountDetails');
	}

	public function getStaffList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getAuthList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getRequestAccountNumber(){
		$account=Savingaccounts::model()->findByPk($this->savingaccount_id);
		return !empty($account) ? $account->account_number : "UNDEFINED";
	}

	public function getRequestAccountHolder(){
		$account=Savingaccounts::model()->findByPk($this->savingaccount_id);
		return !empty($account) ? $account->SavingAccountHolderName : "UNDEFINED";
	}

	public function getRequestBy(){
		$user = Profiles::model()->findByPk($this->user_id);
		return !empty($user) ? $user->ProfileFullName : "UNDEFINED";
	}

	public function getRequestBranch(){
		$branch = Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ? $branch->name : "UNDEFINED";
	}

	public function getRequestType(){
		return  $this->type== '0' ? "NORMAL" : "ACCRUED INTEREST";
	}

	public function getRequestAmount(){
		return CommonFunctions::asMoney($this->amount);
	}

	public function getRequestStatus(){
		switch($this->is_approved){
			case '0':
			echo "<span class='badge badge-info'> Submitted </span>";
			break;

			case '1':
			echo "<span class='badge badge-success'> Approved </span>";
			break;

			case '2':
			echo "<span class='badge badge-danger'> Rejected </span>";
			break;
		}
	}

	public function getRequestAuthorizedBy(){
		$user=Profiles::model()->findByPk($this->approver);
		return !empty($user) ? $user->ProfileFullName : "UNDEFINED";
	}

	public function getRequestDate(){
		return date('d/m/Y',strtotime($this->created_at));
	}

	public function getAction(){

		if(Navigation::checkIfAuthorized(153) == 1){
			$view_link="<a href='".Yii::app()->createUrl('withdrawals/'.$this->id)."' title='View Details' class='btn btn-info btn-sm'><i class='fa fa-eye'></i></a>";
		}else{
			$view_link="";
		}

		if(Navigation::checkIfAuthorized(153) == 1){
			if($this->is_approved === '0'){
				$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('withdrawals/update/'.$this->id)."\")'><i class='fa fa-edit'></i></a>";
			}else{
				$update_link="";
			}
		}else{
			$update_link="";
		}

		$action_links="$view_link&nbsp;$update_link";
		
		echo $action_links;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Withdrawals the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

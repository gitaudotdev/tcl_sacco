<?php

/**
 * This is the model class for table "savingaccounts".
 *
 * The followings are the available columns in table 'savingaccounts':
 * @property integer $savingaccount_id
 * @property integer $profile_id
 * @property integer $savingproduct_id
 * @property string $account_number
 * @property integer $created_by
 * @property string $created_at
 */
class Savingaccounts extends CActiveRecord{

	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'savingaccounts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		return array(
			array('user_id, interest_rate, account_number, opening_balance', 'required'),
			array('user_id,created_by,fixed_period,rm,branch_id', 'numerical', 'integerOnly'=>true),
			array('account_number,type', 'length', 'max'=>25),
			array('is_approved', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('savingaccount_id, user_id,fixed_period, interest_rate, account_number,opening_balance,type,is_approved,created_by,branch_id,rm,startDate,endDate','safe', 'on'=>'search'),
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
			'savingaccount_id' => 'Savingaccount',
			'user_id' => 'User',
			'interest_rate' => 'Interest Rate',
			'account_number' => 'Account Number',
			'opening_balance' => 'Account Opening Balance',
			'type' => 'Account Type',
			'is_approved' => 'Account Approval Status',
			'fixed_period'=>'Fixed Saving Period',
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

		$criteria->compare('savingaccount_id',$this->savingaccount_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('interest_rate',$this->interest_rate);
		$criteria->compare('account_number',$this->account_number,true);
		$criteria->compare('opening_balance',$this->opening_balance,true);
		$criteria->compare('fixed_period',$this->fixed_period,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare("$alias.branch_id",$this->branch_id);
		$criteria->compare("$alias.rm",$this->rm);
		$criteria->compare('is_approved',$this->is_approved,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.created_at)",$this->startDate, $this->endDate, 'AND');
		}

		switch(Yii::app()->user->user_level){
			case'1':
			$criteria->compare("$alias.branch_id",Yii::app()->user->user_branch);
			break;

			case'2':
			$criteria->compare("$alias.rm",Yii::app()->user->user_id);
			break;

			case '3':
			$criteria->compare("$alias.user_id",Yii::app()->user->user_id);
			break;
		}

		$criteria->order = "savingaccount_id DESC";
		return new CActiveDataProvider($this, array(
			'criteria'  => $criteria,
			'pagination'=> array(
				'pageSize'=>30
			),
		));
	}

	public function getSaccoBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getRelationshipManagers(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getUsersList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('ALL'),'id','ProfileNameWithIdNumber');
	}

	public function getSavingAccountHolderName(){
		$user = Profiles::model()->findByPk($this->user_id);
		return !empty($user) ? $user->ProfileFullName : "UNDEFINED";
	}

	public function getAccountDetails(){
		return $this->SavingAccountHolderName.' : '.$this->account_number;
	}

	public function getAccountType(){
		return ucfirst($this->type);
	}

	public function getSavingAccountHolderBranch(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileBranch : "UNDEFINED";
	}

	public function getSavingAccountHolderRelationManager(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileManager : "UNDEFINED";
	}

	public function getSavingAccountHolderIDNumber(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->idNumber : "UNDEFINED";
	}

	public function getSavingAccountHolderPhoneNumber(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfilePhoneNumber : "UNDEFINED";
	}

	public function getSavingAccountHolderEmail(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileEmailAddress : "UNDEFINED";
	}

	public function getSavingAccountHolderMemberSince(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? date('jS M Y',strtotime($profile->createdAt)) : "UNDEFINED";
	}

	public function getAccountOpenedAt(){
		return date('jS M Y',strtotime($this->created_at));
	}

	public function getSavingAccountHolderLastLogin(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileLastLoggedAt : "UNDEFINED";
	}

	public function getSavingAccountNumber(){
		return $this->account_number;
	}

	public function getAccountInterestRate(){
		return CommonFunctions::asMoney($this->interest_rate).' % p.m.';
	}

	public function getAccountOpeningBalance(){
		echo "<strong> Kshs. ".CommonFunctions::asMoney($this->opening_balance)."</strong>";
	}

	public function getSavingAccountBalance(){
		return SavingFunctions::getSavingAccountBalance($this->savingaccount_id);
	}

	public function getFormattedSavingAccountBalance(){
		return CommonFunctions::asMoney($this->getSavingAccountBalance());
	}

	public function getSavingAccountInterestAccrued(){
		return SavingFunctions::getSavingAccountAccruedInterest($this->savingaccount_id);
	}

	public function getFormattedSavingAccountInterestAccrued(){
		return CommonFunctions::asMoney($this->getSavingAccountInterestAccrued());
	}

	public function getSavingAccountTotal(){
		return $this->getSavingAccountBalance();
	}

	public function getFormattedSavingAccountTotal(){
		return CommonFunctions::asMoney($this->getSavingAccountTotal());
	}

	public function getAccountAuthStatus(){
		switch($this->is_approved){
			case '0':
			$authStatus="<span class='badge badge-warning' style='background-color:#ffb236!important;color:#fff!important;' title='Unauthorized'>SUBMITTED</span>";
			break;

			case '1':
			$authStatus="<span class='badge badge-success' title='Approved'>APPROVED</span>";
			break;

			case '2':
			$authStatus="<span class='badge badge-danger' title='Rejected'>REJECTED</span>";
			break;
		}
		echo $authStatus;
	}

	public function getPlainAccountAuthStatus(){
		switch($this->is_approved){
			case '0':
			$authStatus="submitted";
			break;

			case '1':
			$authStatus="Approved";
			break;

			case '2':
			$authStatus="Rejected";
			break;
		}
		return strtoupper($authStatus);
	}

	public function getAction(){
		/*Update*/
		if(Navigation::checkIfAuthorized(52) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('savingaccounts/update/'.$this->savingaccount_id)."\")'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link="";
		}
		/*View*/
		if(Navigation::checkIfAuthorized(53) == 1){
			$view_link="<a href='".Yii::app()->createUrl('savingaccounts/'.$this->savingaccount_id)."' class='btn btn-info btn-sm'><i class='fa fa-eye'></i></a>";
		}else{
			$view_link="";
		}
		/*Delete Link*/
		if(Navigation::checkIfAuthorized(57) == 1){
			$delete_link="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('savingaccounts/delete/'.$this->savingaccount_id)."\")'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_link="";
		}
		/*Authorize Link*/
		if(Navigation::checkIfAuthorized(54) == 1){
			if($this->is_approved === '0'){
				$authorize="<a href='#' class='btn btn-success btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('savingaccounts/authorize/'.$this->savingaccount_id)."\")'><i class='fa fa-check'></i></a>";
			}else{
				$authorize="";
			}
		}else{
			$authorize="";
		}
		$action_links="$authorize&nbsp;$update_link&nbsp;$view_link&nbsp;$delete_link";
		echo $action_links;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Savingaccounts the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

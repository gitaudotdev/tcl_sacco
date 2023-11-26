<?php

/**
 * This is the model class for table "profiles".
 *
 * The followings are the available columns in table 'profiles':
 * @property integer $id
 * @property integer $branchId
 * @property String $clientCategoryClass
 * @property integer $managerId
 * @property string $profileType
 * @property string $firstName
 * @property string $lastName
 * @property string $gender
 * @property string $birthDate
 * @property string $idNumber
 * @property string $kraPIN
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $createdBy
 */
class Profiles extends CActiveRecord
{

	public $startDate, $endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'profiles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('branchId, managerId, firstName, lastName', 'required'),
			array('branchId, managerId, createdBy', 'numerical', 'integerOnly'=>true),
            array('clientCategoryClass', 'length', 'max'=>25),
			array('profileType', 'length', 'max'=>8),
			array('profileStatus', 'length', 'max'=>25),
			array('firstName, lastName', 'length', 'max'=>255),
			array('gender', 'length', 'max'=>6),
			array('idNumber, kraPIN', 'length', 'max'=>25),
			array('birthDate, createdAt, updatedAt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, branchId,clientCategoryClass, managerId, profileType, profileStatus, firstName, lastName, gender, birthDate, idNumber, kraPIN, createdAt, updatedAt, createdBy,startDate,endDate', 'safe', 'on'=>'search'),
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
			'branchId' => 'Branch',
			'clientCategoryClass' => 'Category Class',
			'managerId' => 'Manager',
			'profileType' => 'Profile Type',
			'profileStatus' => 'Profile Status',
			'firstName' => 'First Name',
			'lastName' => 'Last Name',
			'gender' => 'Gender',
			'birthDate' => 'Birth Date',
			'idNumber' => 'Id Number',
			'kraPIN' => 'Kra Pin',
			'createdAt' => 'Created At',
			'updatedAt' => 'Updated At',
			'createdBy' => 'Created By',
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
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('branchId',$this->branchId);
		$criteria->compare('clientCategoryClass',$this->clientCategoryClass);
		$criteria->compare('managerId',$this->managerId);
		$criteria->compare('profileType',$this->profileType,true);
		$criteria->compare('profileStatus',$this->profileStatus,true);
		$criteria->compare('firstName',$this->firstName,true);
		$criteria->compare('lastName',$this->lastName,true);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('birthDate',$this->birthDate,true);
		$criteria->compare('idNumber',$this->idNumber,true);
		$criteria->compare('kraPIN',$this->kraPIN,true);
		$criteria->compare('createdAt',$this->createdAt,true);
		$criteria->compare('updatedAt',$this->updatedAt,true);
		$criteria->compare('createdBy',$this->createdBy);
		
		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition('DATE(createdAt)',$this->startDate, $this->endDate, 'AND');
		}

		switch(Yii::app()->user->user_level){
			case '0':
			break;

			case '1':
			$criteria->addCondition('branchId ='.Yii::app()->user->user_branch);
			break;

			case '2':
			$criteria->addCondition('managerId ='.Yii::app()->user->user_id);
			break;

			default:
			$criteria->addCondition('id ='.Yii::app()->user->user_id);
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

	public function searchStaff(){
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('branchId',$this->branchId);
		$criteria->compare('clientCategoryClass',$this->clientCategoryClass);
		$criteria->compare('managerId',$this->managerId);
		$criteria->compare('profileType',$this->profileType,true);
		$criteria->compare('profileStatus',$this->profileStatus,true);
		$criteria->compare('firstName',$this->firstName,true);
		$criteria->compare('lastName',$this->lastName,true);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('birthDate',$this->birthDate,true);
		$criteria->compare('idNumber',$this->idNumber,true);
		$criteria->compare('kraPIN',$this->kraPIN,true);
		$criteria->compare('createdAt',$this->createdAt,true);
		$criteria->compare('updatedAt',$this->updatedAt,true);
		$criteria->compare('createdBy',$this->createdBy);
		$criteria->compare('profileType',array('STAFF'),true);

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition('DATE(createdAt)',$this->startDate, $this->endDate, 'AND');
		}

		switch(Yii::app()->user->user_level){
			case '0':
			break;

			case '1':
			$criteria->addCondition('branchId ='.Yii::app()->user->user_branch);
			break;

			case '2':
			$criteria->addCondition('managerId ='.Yii::app()->user->user_id);
			break;

			default:
			$criteria->addCondition('profileId ='.Yii::app()->user->user_id);
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

	public function getProfilePhoneNumber(){
		$contactValue = ProfileEngine::getProfileContactByType($this->id,'PHONE');
		return $contactValue != '' ? "0".substr($contactValue,-9) : "UNDEFINED";
	}

	public function getProfileCountryCodePhoneNumber(){
		$contactValue = ProfileEngine::getProfileContactByType($this->id,'PHONE');
		return $contactValue != '' ? "254".substr($contactValue,-9) : "UNDEFINED";
	}

	public function getProfileEmailAddress(){
		$contactValue = ProfileEngine::getProfileContactByType($this->id,'EMAIL');
		return $contactValue != '' ? $contactValue : "UNDEFINED";
	}

	public function getProfileSavingAccount(){
		$contactValue = ProfileEngine::getProfileContactByTypeOrderDesc($this->id,'PHONE');
		$phone        = $contactValue != '' ? "0".substr($contactValue,-9) : "UNDEFINED";
		return $this->ProfileFullName.'-'.$phone;
	}

	public function getProfileFullName(){
		return strtoupper($this->firstName). ' '.strtoupper($this->lastName);
	}

	public function getProfileNameWithIdNumber(){
		return $this->ProfileFullName.'-'.$this->idNumber;
	}

	public function getProfileGender(){
		return strtoupper($this->gender);
	}

	public function getProfileType(){
		return $this->profileType;
	}

	public function getProfileIdNumber(){
		return $this->idNumber;
	}

	public function getProfileBranch(){
		$branch = Branch::model()->findByPk($this->branchId);
		return !empty($branch) ? strtoupper($branch->name) : "UNDEFINED";
	}

	public function getProfileBranchCode(){
		$branch = Branch::model()->findByPk($this->branchId);
		return !empty($branch) ? $branch->BranchCode : "UNDEFINED";
	}

	public function getProfileManager(){
		$profile = Profiles::model()->findByPk($this->managerId);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getProfileManagersList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getProfilesList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('ALL'),'id','ProfileNameWithIdNumber');
	}
	
	public function getProfileBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getProfileAuthStatus(){
        $auth = Auths::model()->find('profileId=:a',array(':a' => $this->id));
		return !empty($auth) ? $auth->level : "UNDEFINED";
	}

	public function getProfileUsername(){
        $auth = Auths::model()->find('profileId=:a',array(':a' => $this->id));
		return !empty($auth) ? $auth->username : "UNDEFINED";
	}

	public function getProfileAccountStatus(){
        $auth = Auths::model()->find('profileId=:a',array(':a' => $this->id));
		return !empty($auth) ? $auth->authStatus : "UNDEFINED";
	}

	public function getProfileAge(){
		$dob = new DateTime($this->birthDate);
		$now = new DateTime();
		$difference = $now->diff($dob);
		$age = $difference->y;
		echo $age;
	}

	public function getProfileLastLoggedAt(){
        $auth = Auths::model()->find('profileId=:a',array(':a' => $this->id));
		return !empty($auth) ? $auth->AuthLastLoginFormattedDate : "UNDEFINED";
	}

	public function getProfileResidence(){
		return ProfileEngine::getRecentProfileResidence($this->id);
	}

	public function getProfileEmployment(){
		return ProfileEngine::getRecentProfileEmployment($this->id);
	}

	public function getProfileCreatedAt(){
		return date('jS M Y',strtotime($this->createdAt));
	}

	public function getProfileCreatedYear(){
		return date('Y',strtotime($this->createdAt));
	}

	public function getProfileLoansCount(){
		$userID       = $this->id;
		$acountsQuery = "SELECT COUNT(loanaccount_id) AS loanaccount_id FROM loanaccounts WHERE user_id=$userID 
		AND loan_status NOT IN('0','1','3','8','9','10')";
		$accounts     = Loanaccounts::model()->findBySql($acountsQuery);
		return !empty($accounts) ? $accounts->loanaccount_id : 0;
	}

	public function getProfileMaxLoanLimit(){
		$maxLoanLimit = ProfileEngine::getActiveProfileAccountSettingByType($this->id,'LOAN_LIMIT');
		return $maxLoanLimit==='NOT SET' ? CommonFunctions::asMoney(0) : CommonFunctions::asMoney(floatval($maxLoanLimit));
	}

	public function getProfileLoansInterest(){
		$loanInterest = ProfileEngine::getActiveProfileAccountSettingByType($this->id,'LOAN_INTEREST_RATE');
		return $loanInterest==='NOT SET' ? "0.00 %" : number_format(floatval($loanInterest),2)." %";
	}

	public function getProfileSavingsInterest(){
		$savingsInterestRate = ProfileEngine::getActiveProfileAccountSettingByType($this->id,'SAVINGS_INTEREST_RATE');
		return $savingsInterestRate==='NOT SET' ? "0.00 %" : number_format(floatval($savingsInterestRate),2)." %";
	}

	public function getProfileAlertByColumnStatus($column){
		return ProfileEngine::getActiveProfileAccountSettingByType($this->id,'$column') == 'DISABLED' ? "<span class='badge badge-danger'> INACTIVE</span>" 
		: "<span class='badge badge-success'> ACTIVE</span>";
	}

	public function getProfileSavings(){
		$profileId     = $this->id;
		$accountsQuery = "SELECT * FROM savingaccounts WHERE user_id=$profileId AND is_approved IN('1') ORDER BY savingaccount_id DESC";
		$accounts      = Savingaccounts::model()->findAllBySql($accountsQuery);
		if(!empty($accounts)){
			$savingsBalance = 0;
			foreach($accounts AS $account){
				$savingsBalance+=SavingFunctions::getTotalSavingAccountBalance($account->savingaccount_id);
			}
		}else{
			$savingsBalance = 0;
		}
		return $savingsBalance;
	}

	public function getProfileSavingsBalance(){
		return CommonFunctions::asMoney($this->ProfileSavings);
	}

	public function getProfileOriginalPrincipal(){
		$profileId    = $this->id;
		$loanSQL      = "SELECT * FROM loanaccounts WHERE user_id=$profileId AND loan_status NOT IN('0','1','3','4','8','9','10')";
		$loanaccounts = Loanaccounts::model()->findAllBySql($loanSQL);
		if(!empty($loanaccounts)){
			$orginalPrincipal = 0;
			foreach($loanaccounts AS $loanaccount){
				$orginalPrincipal += $loanaccount->NotFormattedExactAmountDisbursed;
			}
		}else{
			$orginalPrincipal = 0;
		}
		return CommonFunctions::asMoney($orginalPrincipal);
	}

	public function getProfileLoanBalance(){
		$profileId    = $this->id;
		$loanSQL      = "SELECT * FROM loanaccounts WHERE user_id=$profileId AND loan_status NOT IN('0','1','3','4','8','9','10')";
		$loanaccounts = Loanaccounts::model()->findAllBySql($loanSQL);
		if(!empty($loanaccounts)){
			$currentBalance = 0;
			foreach($loanaccounts AS $loanaccount){
				$currentBalance += LoanTransactionsFunctions::getTotalLoanBalance($loanaccount->loanaccount_id);
			}
		}else{
			$currentBalance = 0;
		}
		return $currentBalance;
	}

	public function getProfileOutstandingLoanBalance(){
		return CommonFunctions::asMoney($this->ProfileLoanBalance);
	}

	public function getProfileRoleName(){
		$roleQuery = "SELECT roles.name as name FROM roles,user_role WHERE user_role.user_id=$this->id
		AND roles.role_id=user_role.role_id";
		$role  = Roles::model()->findBySql($roleQuery);
		return !empty($role) ?  strtoupper($role->name) :  "UNDEFINED";
	}
	/****
	 * STAFF
	 *
	 * 
	 * */
	public function getProfileSalary(){
		$salaryAmount = ProfileEngine::getActiveProfileAccountSettingByType($this->id,'SALARY');
		return $salaryAmount==='NOT SET' ?  0.00 : floatval($salaryAmount);
	}

	public function getTotalRepayment(){
		$userID    = $this->id;
		$loanQuery = "SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','4','8','9','10') AND user_id=$userID
		ORDER BY loanaccount_id DESC LIMIT 1";
		$loan      = Loanaccounts::model()->findBySql($loanQuery);
		return !empty($loan) ? LoanApplication::getEMIAmount($loan->loanaccount_id) : 0;
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(17) == 1){
			$viewLink = "<a href='#' class='btn btn-info btn-sm' title='View User Details' onclick='Authenticate(\"".Yii::app()->createUrl('profiles/'.$this->id)."\")'><i class='fa fa-eye'></i></a>";
		}else{
			$viewLink = "";
		}

		if(Navigation::checkIfAuthorized(16) == 1){
			$updateLink = "<a href='#' class='btn btn-warning btn-sm' title='Update User Details' onclick='Authenticate(\"".Yii::app()->createUrl('profiles/update/'.$this->id)."\")'><i class='fa fa-edit'></i></a>";
		}else{ 
			$updateLink = "";
		}

		if(Navigation::checkIfAuthorized(18) == 1){
			$resetLink = "<a href='#' class='btn btn-primary btn-sm' title='Reset User Password' onclick='Authenticate(\"".Yii::app()->createUrl('profiles/resetPassword/'.$this->id)."\")'><i class='fa fa-bolt'></i></a>";
		}else{
			$resetLink = "";
		}
		$actionLinks = $viewLink."&nbsp;".$updateLink."&nbsp;".$resetLink;
		echo $actionLinks;
	}

	public function getMoreActions(){
		if(Navigation::checkIfAuthorized(27) == 1){
			$assignedRole = UserRole::model()->find('user_id=:a',array(':a' => $this->id));
			if(!empty($assignedRole)){
				if(Navigation::checkIfAuthorized(26) == 1){
				 $assignLink    = "<a href='#' class='btn btn-info btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('profiles/reassign/'.$this->id)."\")'>Reassign</a>";
				}else{
					$assignLink = "";
				}
			}else{
				if(Navigation::checkIfAuthorized(27) == 1){
				    $assignLink = "<a href='#' class='btn btn-success btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('profiles/assign/'.$this->id)."\")'>Assign</a>";
				}else{
					$assignLink = "";
				}
			}
		}else{
			$assignLink = "";
		}
		echo $assignLink;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Profiles the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

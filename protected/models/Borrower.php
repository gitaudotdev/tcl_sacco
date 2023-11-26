<?php

/**
 * This is the model class for table "borrower".
 *
 * The followings are the available columns in table 'borrower':
 * @property integer $borrower_id
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $id_number
 * @property string $email
 * @property string $birth_date
 * @property string $photo
 * @property string $working_status
 * @property integer $branch_id
 * @property string $gender
 * @property string $employer
 * @property string $date_employed
 * @property string $address
 * @property string $city
 * @property string $residence_land_mark
 * @property string $job_title
 * @property string $job_email
 * @property string $office_phone
 * @property string $office_location
 * @property string $office_land_mark
 * @property string $alternative_phone
 * @property string $referred_by
 * @property string $referee_phone
 * @property integer $created_by
 * @property string $created_at
 */
class Borrower extends CActiveRecord{

	public $loanaccount_id,$genderName,$genderCount,$startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'borrower';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, first_name, last_name, email, birth_date, branch_id, date_employed, address, city', 'required'),
			array('user_id, branch_id, rm,created_by', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name, photo, address, city, residence_land_mark, job_title, referred_by', 'length', 'max'=>255),
			array('phone,salary_band', 'length', 'max'=>15),
			array('id_number, office_phone, alternative_phone, referee_phone', 'length', 'max'=>512),
			array('email, employer, job_email, office_location, office_land_mark', 'length', 'max'=>512),
			array('working_status,segment', 'length', 'max'=>1),
			array('gender,industry_type', 'length', 'max'=>6),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('borrower_id, user_id, first_name, last_name, phone, id_number, email, birth_date, photo, working_status, branch_id, gender, employer, date_employed, address, city, residence_land_mark, job_title, job_email, office_phone, office_location, office_land_mark, alternative_phone, referred_by, referee_phone, created_by, created_at,startDate,endDate,rm,segment,industry_type,salary_band', 'safe', 'on'=>'search'),
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
			'borrower_id' => 'Borrower',
			'user_id' => 'User',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'phone' => 'Phone',
			'id_number' => 'Id Number',
			'email' => 'Email',
			'birth_date' => 'Birth Date',
			'photo' => 'Photo',
			'working_status' => 'Working Status',
			'branch_id' => 'Branch',
			'industry_type' => 'Industry Type',
			'gender' => 'Gender',
			'employer' => 'Employer',
			'date_employed' => 'Date Employed',
			'salary_band' => 'Income Amount',
			'address' => 'Address',
			'city' => 'City',
			'rm'=>'Relationship Manager',
			'residence_land_mark' => 'Residence Land Mark',
			'job_title' => 'Job Title',
			'job_email' => 'Job Email',
			'office_phone' => 'Office Phone',
			'office_location' => 'Office Location',
			'office_land_mark' => 'Office Land Mark',
			'alternative_phone' => 'Alternative Phone',
			'referred_by' => 'Referred By',
			'referee_phone' => 'Referee Phone',
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

		$criteria->compare('borrower_id',$this->borrower_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('id_number',$this->id_number,true);
		$criteria->compare('segment',$this->segment,true);
		$criteria->compare('industry_type',$this->industry_type,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('birth_date',$this->birth_date,true);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('working_status',$this->working_status,true);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('employer',$this->employer,true);
		$criteria->compare('date_employed',$this->date_employed,true);
		$criteria->compare('salary_band',$this->salary_band,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('residence_land_mark',$this->residence_land_mark,true);
		$criteria->compare('job_title',$this->job_title,true);
		$criteria->compare('job_email',$this->job_email,true);
		$criteria->compare('office_phone',$this->office_phone,true);
		$criteria->compare('office_location',$this->office_location,true);
		$criteria->compare('office_land_mark',$this->office_land_mark,true);
		$criteria->compare('alternative_phone',$this->alternative_phone,true);
		$criteria->compare('referred_by',$this->referred_by,true);
		$criteria->compare('referee_phone',$this->referee_phone,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('rm',$this->rm,true);
		
		if(isset($this->startDate) && isset($this->endDate)){
				$criteria->addBetweenCondition('DATE(created_at)',$this->startDate, $this->endDate, 'AND');
		}
		/*Extra Conditions*/
		$userBranch = Yii::app()->user->user_branch;
		$userID     = Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			break;

			case '1':
			$criteria->addCondition('branch_id ='.$userBranch);
			break;

			case '2':
			$criteria->addCondition('rm ='.$userID);
			break;

			default:
			$criteria->addCondition('user_id ='.$userID);
			break;
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
         'defaultOrder'=>'borrower_id DESC',
      ),
			'pagination'=>array(
         'pageSize'=>30
       ),
		));
	}

	public function getRelationshipManagers(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$staffSQL = "SELECT * from staff,users WHERE staff.user_id=users.user_id ";
		switch(Yii::app()->user->user_level){
			case '0':
			$staffSQL.= " ";
			break;

			case '1':
			$staffSQL.= " AND users.branch_id=$userBranch";
			break;

			case '2':
			$staffSQL.= " AND staff.user_id=$userID";
			break;

			case '3':
			$staffSQL.= " AND staff.user_id=$userID";
			break;
		}
		$staffSQL.= " ORDER BY staff.first_name,staff.last_name ASC";
		return CHtml::listData(Staff::model()->findAllBySql($staffSQL),'user_id','StaffFullName');
	}

	public function getMembersList(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$memberQuery = "SELECT * from borrower,users WHERE borrower.user_id=users.user_id ";
		switch(Yii::app()->user->user_level){
			case '0':
			$memberQuery.= " ";
			break;

			case '1':
			$memberQuery.= " AND users.branch_id=$userBranch";
			break;

			case '2':
			$memberQuery.= " AND borrower.rm=$userID";
			break;

			case '3':
			$memberQuery.= " AND borrower.user_id=$userID";
			break;
		}
		$memberQuery.= " ORDER BY borrower.first_name,borrower.last_name ASC";
		return CHtml::listData(Borrower::model()->findAllBySql($memberQuery),'borrower_id','BorrowerFullDetails');
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

	public function getBranchName(){
		$branch=Branch::model()->findByPk($this->branch_id);
		if(!empty($branch)){
			$branchName= $branch->name;
		}else{
			$branchName= 'No Branch';
		}
		return $branchName;
	}

	public function getBorrowerDetails(){
		echo strtoupper($this->first_name).' '.strtoupper($this->last_name).'<br>'.
				 '<span style="color:#2ca8ff;margin-top:2%!important;">'.$this->id_number.'</span>';
	}

	public function getBorrowerAge(){
		$dob = new DateTime($this->birth_date);
		$now = new DateTime();
		$difference = $now->diff($dob);
		$age = $difference->y;
		echo $age;
	}

	public function getYearsWorked(){
		$dob = new DateTime($this->date_employed);
		$now = new DateTime();
		$difference = $now->diff($dob);
		$age = $difference->y;
		echo $age;
	}

	public function getBorrowerWorkingStatus(){
		switch($this->working_status){
			case '0':
			echo 'Employee';
			break;

			case '1':
			echo 'Business Owner';
			break;

			case '2':
			echo 'Student';
			break;

			case '3':
			echo 'Overseas Worker';
			break;
		}
	}

	public function getMemberSegment(){
		switch($this->segment){
			case '0':
			$segmentName="Small";
			break;

			case '1':
			$segmentName="Premier";
			break;

			case '2':
			$segmentName="Corporate";
			break;
		}
		return $segmentName;
	}

	public function getMemberIndustryType(){
		switch($this->industry_type){
			case '001':
			$industryTypeName="Agriculture";
			break;

			case '002':
			$industryTypeName="Manufacturing";
			break;

			case '003':
			$industryTypeName="Building/ Construction";
			break;

			case '004':
			$industryTypeName="Mining/ Quarrying";
			break;

			case '005':
			$industryTypeName="Energy/ Water";
			break;

			case '006':
			$industryTypeName="Trade";
			break;

			case '007':
			$industryTypeName="Tourism/ Restaurant/ Hotels";
			break;

			case '008':
			$industryTypeName="Transport/ Communications";
			break;

			case '009':
			$industryTypeName="Real Estate";
			break;

			case '010':
			$industryTypeName="Financial Services";
			break;

			case '011':
			$industryTypeName="Government";
			break;
		}
		return $industryTypeName;
	}

	public function getFullName(){
		$name=strtoupper($this->first_name). ' '.strtoupper($this->last_name);
		echo $name;
	}

	public function getBorrowerFullName(){
		$name=strtoupper($this->first_name). ' '.strtoupper($this->last_name);
		return $name;
	}

	public function getBorrowerPhoneNumber(){
		$phoneNumber="0".$this->phone;
		return $phoneNumber;
	}

	public function getRelationManager(){
		$user=Users::model()->findByPk($this->rm);
		if(!empty($user)){
			$managerName=$user->getUserFullName();
		}else{
			$managerName="";
		}
		return $managerName;
	}

	public function getCreatedAtFormatted(){
		$user=Users::model()->findByPk($this->user_id);
		if(!empty($user)){
			$dateCreated=date('jS M Y',strtotime($user->created_at));
		}else{
			$dateCreated="";
		}
		return $dateCreated;
	}

	public function getMemberIDNumber(){
		return $this->id_number;
	}

	public function getMemberGender(){
		return strtoupper($this->gender);
	}

	public function getMemberCreatedYear(){
		return date('Y',strtotime($this->CreatedAtFormatted));
	}

	public function getMemberLoansCount(){
		$userID=$this->user_id;
		$acountsQuery="SELECT COUNT(loanaccount_id) AS loanaccount_id FROM loanaccounts WHERE user_id=$userID 
		AND loan_status NOT IN('0','1','3')";
    $accounts=Loanaccounts::model()->findBySql($acountsQuery);
    if(!empty($accounts)){
      $counter=$accounts->loanaccount_id; 
    }else{
      $counter=0;
    }
    return $counter;
	}

	public function getMemberSavings(){
		$userID=$this->user_id;
		$accountsQuery="SELECT * FROM savingaccounts WHERE user_id=$userID AND is_approved IN('1') ORDER BY savingaccount_id DESC LIMIT 1";
		$account=Savingaccounts::model()->findBySql($accountsQuery);
		if(!empty($account)){
			$savingsBalance=SavingFunctions::getTotalSavingAccountBalance($account->savingaccount_id);
		}else{
			$savingsBalance=0;
		}
		return $savingsBalance;
	}

	public function getMemberEmployer(){
		return strtoupper($this->employer);
	}
	
	public function getCurrentLoanBalance(){
		$userID=$this->user_id;
		$loanSQL="SELECT * FROM loanaccounts WHERE user_id=$userID AND loan_status NOT IN('0','1','3','4')";
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanSQL);
		if(!empty($loanaccounts)){
			$balance=0;
			foreach($loanaccounts AS $loanaccount){
				$balance+=LoanTransactionsFunctions::getTotalLoanBalance($loanaccount->loanaccount_id);
			}
			$currentBalance=$balance;
		}else{
			$currentBalance=0;
		}
		return $currentBalance;
	}

	public function getFormattedCurrentLoanBalance(){
		return CommonFunctions::asMoney($this->CurrentLoanBalance);
	}

	public function getMemberOriginalPrincipal(){
		$userID=$this->user_id;
		$loanSQL="SELECT * FROM loanaccounts WHERE user_id=$userID AND loan_status NOT IN('0','1','3','4')";
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanSQL);
		if(!empty($loanaccounts)){
			$balance=0;
			foreach($loanaccounts AS $loanaccount){
				$balance+=$loanaccount->amount_approved;
			}
			$currentBalance=$balance;
		}else{
			$currentBalance=0;
		}
		return $currentBalance;
	}

	public function getFormattedMemberOriginalPrincipal(){
		return CommonFunctions::asMoney($this->MemberOriginalPrincipal);
	}

	public function getBorrowerFullDetails(){
		$name=strtoupper($this->first_name). ' '.strtoupper($this->last_name).'-'.$this->id_number;
		return $name;
	}

	public function getViewActions(){
		$loans_link="<a href='".Yii::app()->createUrl('borrower/loans/'.$this->borrower_id)."' title='Member Loans' class='btn btn-success btn-sm'><i class='now-ui-icons business_money-coins'></i></a>";
		$view_links="$loans_link&nbsp;<a href='".Yii::app()->createUrl('borrower/savings/'.$this->borrower_id)."' title='Member Savings' class='btn btn-warning btn-sm'><i class='now-ui-icons business_bank'></i></a>";
		echo $view_links;
	}

	public function getAction(){

			if(Navigation::checkIfAuthorized(7) == 1){
				$view_link="<a href='".Yii::app()->createUrl('borrower/'.$this->borrower_id)."' title='View Member' class='btn btn-info btn-sm'><i class='fa fa-eye'></i></a>";
			}else{
				$view_link="";
			}

			if(Navigation::checkIfAuthorized(8) == 1){
				$delete_link="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('borrower/delete/'.$this->borrower_id)."\")' title='Delete Member'><i class='fa fa-trash'></i></a>";
			}else{
				$delete_link="";
			}

			if(Navigation::checkIfAuthorized(6) == 1){
				$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('borrower/update/'.$this->borrower_id)."\")' title='Update Member'><i class='fa fa-edit'></i></a>";
			}else{
				$update_link="";
			}

			$action_links="$view_link&nbsp;$update_link&nbsp;$delete_link";
		  echo $action_links;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Borrower the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

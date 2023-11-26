<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $user_id
 * @property integer $branch_id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $level
 * @property string $is_active
 * @property string $last_login
 * @property string $token
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 */
class Users extends CActiveRecord{

	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'users';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('branch_id, first_name, last_name, username, email, password,id_number', 'required'),
			array('branch_id, created_by,rm,phone', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name, username, email,phone', 'length', 'max'=>255),
			array('password, token,residence', 'length', 'max'=>512),
			array('kra_pin', 'length', 'max'=>25),
			array('phone', 'length', 'max'=>15),
			array('level, is_active,sms_notifications', 'length', 'max'=>1),
			array('created_at,dateOfBirth,gender', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, branch_id, first_name, phone,last_name, username, email, password, level, is_active, last_login, token, created_by, created_at, updated_at,id_number,sms_notifications,kra_pin,rm,startDate,
				endDate,residence,maximum_limit,fixed_payment_enlisted,loans_interest,savings_interest', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'branch_id' => 'Branch',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'username' => 'Username',
			'email' => 'Email',
			'phone' => 'Phone Number',
			'password' => 'Password',
			'level' => 'Level',
			'is_active' => 'Is Active',
			'last_login' => 'Last Login',
			'token' => 'Token',
			'sms_notifications'=>'Receive SMS Notifications',
			'created_by' => 'Created By',
			'kra_pin' => 'KRA PIN',
			'rm' => 'Relation Manager',
			'dateOfBirth' => 'Date of  Birth',
			'gender' => 'Gender',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('id_number',$this->id_number,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('level',$this->level,true);
		$criteria->compare('is_active',$this->is_active,true);
		$criteria->compare('last_login',$this->last_login,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('kra_pin',$this->kra_pin,true);
		$criteria->compare('rm',$this->rm,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('maximum_limit',$this->maximum_limit,true);
		$criteria->compare('fixed_payment_enlisted',$this->fixed_payment_enlisted,true);
		/*Additional Conditions*/
		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.created_at)",$this->startDate, $this->endDate, 'AND');
		}
		
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			$criteria->addCondition('user_id !='.Yii::app()->user->user_id);
			break;

			case '1':
			$criteria->addCondition('branch_id ='.$userBranch);
			$criteria->addCondition('level != "4"');
			$criteria->addCondition('user_id !='.Yii::app()->user->user_id);
			break;

			case '2':
			$criteria->addCondition('branch_id ='.$userBranch);
			$criteria->addCondition('user_id !='.Yii::app()->user->user_id);
			break;

			case '3':
			$criteria->addCondition('user_id !='.Yii::app()->user->user_id);
			break;
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
         'defaultOrder'=>'user_id DESC',
      ),
			'pagination'=>array(
         'pageSize'=>30
       ),
		));
	}

	public function getUserPhoneNumber(){
		return "0".$this->phone;
	}

	public function getUserPhoneNumberAlternate(){
		$phoneNumber=$this->phone;
		return "254".$phoneNumber;
	}

	public function getUserSavingAccount(){
		return $this->UserFullName.'-'.$this->getUserPhoneNumber();
	}

	public function getBorrowerFullDetails(){
		$details=ucfirst(strtolower($this->first_name)). ' '.ucfirst(strtolower($this->last_name)).'-'.$this->id_number;
		return $details;
	}

	public function getRelationManagersList(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$rmQuery="SELECT * FROM users WHERE level IN('0','1','2')";
		switch(Yii::app()->user->user_level){
			case '0':
			$rmQuery.="";
			break;

			case '1':
			$rmQuery.=" AND branch_id=$userBranch";
			break;

			case '2':
			$rmQuery.=" AND user_id=$userID";
			break;
		}
		return CHtml::listData(Users::model()->findAllBySql($rmQuery),'user_id','BorrowerFullDetails');
	}

	public function getUsersList(){
		$userBranch=Yii::app()->user->user_branch;
		$userQuery = "SELECT * from users";
		switch(Yii::app()->user->user_level){
			case '0':
			$userQuery.= "";
			break;

			default:
			$userQuery.= " WHERE branch_id=$userBranch";
			break;
		}
		$userQuery.= " ORDER BY first_name,last_name ASC";
		return CHtml::listData(Users::model()->findAllBySql($userQuery),'user_id','BorrowerFullDetails');
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

	public function getBranchCode(){
		$branch=Branch::model()->findByPk($this->branch_id);
		if(!empty($branch)){
			$branchName= $branch->BranchCode;
		}else{
			$branchName= 'No Branch';
		}
		return $branchName;
	}

	public function getAuthorizationLevel(){
		switch($this->level){
			case '0':
			$authorizationLevel='Superadmin';
			break;

			case '1':
			$authorizationLevel='Admin';
			break;

			case '2':
			$authorizationLevel='Staff';
			break;

			case '3':
			$authorizationLevel='Member';
			break;

			case '4':
			$authorizationLevel='ShareHolder';
			break;

			case '5':
			$authorizationLevel='Supplier';
			break;

			case '6':
			$authorizationLevel='Group/Chama';
			break;
		}
		return $authorizationLevel;
	}

	public function getNotificationStatus(){
		switch($this->sms_notifications){
			case '0':
			$notifyStatus='DISABLED';
			break;

			case '1':
			$notifyStatus='ACTIVE';
			break;
		}
		return $notifyStatus;
	}

	public function getAccountDetails(){
		echo $this->username.'<br>'.'<span style="color:#2ca8ff;margin-top:2%!important;">'.$this->AuthorizationLevel.'</span>';
	}

	public function getUserDetails(){
		$authLevel = $this->AuthorizationLevel;
		echo ' Full Name : '.$this->UserFullName.'<br>'.
		     ' Email : <span style="color:#00933b;margin-top:2%!important;">'.$this->email.'</span><br>'.
		     ' Username : <span style="color:#00933b;margin-top:2%!important;">'.$this->username.'</span><br>'.
		     ' User Level : <span style="color:#00933b;margin-top:2%!important;">'.$authLevel.'</span><br>'.
		     ' Assigned Role : <span style="color:#f96332;margin-top:2%!important;">'. $this->RoleName.'</span><br>';
	}

	public function getUserFullName(){
		return ucfirst(strtolower($this->first_name)). ' '.ucfirst(strtolower($this->last_name));
	}

	public function getUserRelationManager(){
		$user=Users::model()->findByPk($this->rm);
		if(!empty($user)){
			$fullname=$user->UserFullName;
		}else{
			$fullname="";
		}
		return $fullname;
	}
	
	public function getLastLoginDateFormatted(){
		$formatted_date=date('jS F Y H:i:s',strtotime($this->last_login));
		return $formatted_date;
	}

	public function getUserResidence(){
		return ucwords($this->residence);
	}

	public function getRoleName(){
		$role_sql="SELECT roles.name as name FROM roles,user_role WHERE user_role.user_id=$this->user_id
		 AND roles.role_id=user_role.role_id";
		$role=Roles::model()->findBySql($role_sql);
		if(!empty($role)){
			$name=$role->name;
		}else{
			$name="";
		}
		return $name;
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(17) == 1){
			$view_link="<a href='#' class='btn btn-info btn-sm' title='View User Details' onclick='Authenticate(\"".Yii::app()->createUrl('users/'.$this->user_id)."\")'><i class='fa fa-eye'></i></a>";
		}else{
			$view_link="";
		}

		if(Navigation::checkIfAuthorized(16) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' title='Update User Details' onclick='Authenticate(\"".Yii::app()->createUrl('users/update/'.$this->user_id)."\")'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link="";
		}

		if(Navigation::checkIfAuthorized(18) == 1){
			$reset_link="<a href='#' class='btn btn-primary btn-sm' title='Reset User Password' onclick='Authenticate(\"".Yii::app()->createUrl('users/reset/'.$this->user_id)."\")'><i class='fa fa-bolt'></i></a>";
		}else{
			$reset_link="";
		}
		$action_links=$view_link."&nbsp;".$update_link."&nbsp;".$reset_link;
		echo $action_links;
	}

	public function getMoreActions(){
		$currentUserID=$this->user_id;
		/*User Has permission to assign staff membeer a role*/
		if(Navigation::checkIfAuthorized(27) == 1){
			$checkSQL="SELECT * FROM user_role WHERE user_id=$currentUserID";
			$assignedRole=UserRole::model()->findAllBySql($checkSQL);
			if(!empty($assignedRole)){
				if(Navigation::checkIfAuthorized(26) == 1){
				 $assign_link="<a href='#' class='btn btn-info btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('staff/reassign/'.$this->user_id)."\")'>Reassign</a>";
				}else{
					$assign_link="";
				}
			}else{
				if(Navigation::checkIfAuthorized(27) == 1){
				 $assign_link="<a href='#' class='btn btn-success btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('staff/assign/'.$this->user_id)."\")'>Assign</a>";
				}else{
					$assign_link="";
				}
			}
		}else{
			$assign_link="";
		}
		$action_links=$assign_link;
		echo $action_links;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

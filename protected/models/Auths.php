<?php

/**
 * This is the model class for table "auths".
 *
 * The followings are the available columns in table 'auths':
 * @property integer $id
 * @property integer $profileId
 * @property string $username
 * @property string $password
 * @property string $level
 * @property string $authStatus
 * @property string $resetToken
 * @property string $lastLoggedAt
 * @property string $createdAt
 * @property integer $createdBy
 * @property string $updatedAt
 */
class Auths extends CActiveRecord{

	public $startDate, $endDate;

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'auths';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('profileId,username, password', 'required'),
			array('profileId,branchId,managerId,createdBy', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>75),
			array('password, resetToken', 'length', 'max'=>1024),
			array('level', 'length', 'max'=>10),
			array('authStatus', 'length', 'max'=>9),
			array('lastLoggedAt, createdAt, updatedAt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, profileId,branchId,managerId,username, password, level, authStatus, resetToken, lastLoggedAt, createdAt, createdBy, updatedAt,startDate,endDate', 'safe', 'on'=>'search'),
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
			'profileId' => 'Profile',
			'username' => 'Username',
			'password' => 'Password',
			'level' => 'Level',
			'authStatus' => 'Auth Status',
			'resetToken' => 'Reset Token',
			'lastLoggedAt' => 'Last Logged At',
			'createdAt' => 'Created At',
			'createdBy' => 'Created By',
			'updatedAt' => 'Updated At',
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
		$criteria->compare('profileId',$this->profileId);
		$criteria->compare('branchId',$this->branchId);
		$criteria->compare('managerId',$this->managerId);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('authStatus',$this->authStatus);
		$criteria->compare('resetToken',$this->resetToken,true);
		$criteria->compare('lastLoggedAt',$this->lastLoggedAt,true);
		$criteria->compare('createdAt',$this->createdAt,true);
		$criteria->compare('createdBy',$this->createdBy);
		$criteria->compare('updatedAt',$this->updatedAt,true);

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.createdAt)",$this->startDate, $this->endDate,'AND');
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

	public function getProfileManagersList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getProfilesList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('ALL'),'id','ProfileNameWithIdNumber');
	}
	
	public function getProfileBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getAuthLastLoginFormattedDate(){
		return is_null($this->lastLoggedAt) ? "NEVER" : date('jS M Y \A\t g:ia',strtotime($this->lastLoggedAt));
	}

	public function getAuthProfile(){
		$profile = Profiles::model()->findByPk($this->profileId);
		return !empty($profile) ? $profile : array();
	}

	public function getAuthBranch(){
		return !empty($this->AuthProfile) ? $this->getAuthProfile()->ProfileBranch : 'UNDEFINED';
	}

	public function getAuthManager(){
		return !empty($this->AuthProfile) ? $this->getAuthProfile()->ProfileManager : 'UNDEFINED';
	}

	public function getAuthMember(){
		return !empty($this->AuthProfile) ? $this->getAuthProfile()->ProfileFullName : 'UNDEFINED';
	}

	public function getAuthProfileType(){
		return !empty($this->AuthProfile) ? $this->getAuthProfile()->ProfileType : 'UNDEFINED';
	}

	public function getAuthAuthorization(){
		return !empty($this->AuthProfile) ? $this->level : 'UNDEFINED';
	}

	public function getAuthLevel(){
		switch($this->level){
			case 'SUPERADMIN':
			$numericLevel = '0';
			break;

			case 'ADMIN':
			$numericLevel = '1';
			break;

			case 'STAFF':
			$numericLevel = '2';
			break;

			case 'USER':
			$numericLevel = '3';
			break;
		}
		return $numericLevel;
	}

	public function getAuthAction(){
		echo Navigation::checkIfAuthorized(300) == 1 ? "<a href='#' class='btn btn-warning btn-sm' title='Update Authorization' onclick='Authenticate(\"".Yii::app()->createUrl('auths/update/'.$this->id)."\")'><i class='fa fa-edit'></i></a>" : "";
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Auths the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

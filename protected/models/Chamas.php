<?php

/**
 * This is the model class for table "chamas".
 *
 * The followings are the available columns in table 'chamas':
 * @property integer $id
 * @property string $name
 * @property string $is_registered
 * @property integer $organization_id
 * @property integer $location_id
 * @property integer $leader
 * @property integer $rm
 * @property integer $branch_id
 * @property integer $created_by
 * @property string $created_at
 */
class Chamas extends CActiveRecord{

	public $startDate, $endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'chamas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, organization_id, location_id, leader, rm, branch_id, created_by', 'required'),
			array('organization_id, location_id, leader, rm, branch_id, created_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>256),
			array('is_registered', 'length', 'max'=>1),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, is_registered, organization_id, location_id, leader, rm, branch_id, created_by, created_at,startDate,endDate', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'is_registered' => 'Is Registered',
			'organization_id' => 'Organization',
			'location_id' => 'Location',
			'leader' => 'Leader',
			'rm' => 'Rm',
			'branch_id' => 'Branch',
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
		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('is_registered',$this->is_registered,true);
		$criteria->compare('organization_id',$this->organization_id);
		$criteria->compare('location_id',$this->location_id);
		$criteria->compare('leader',$this->leader);
		$criteria->compare('rm',$this->rm);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.created_at)",$this->startDate, $this->endDate,'AND');
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
			$criteria->addCondition('leader ='.Yii::app()->user->user_id);
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

	public function getChamaName(){
		return strtoupper($this->name);
	}

	public function getChamaBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getChamaManagersList(){
		return CHtml::listData(ProfileEngine::getProfileGroupAccountManagers(),'id','ProfileNameWithIdNumber');
	}
	
	public function getChamaLeadersList(){
		return CHtml::listData(ProfileEngine::getProfileGroupLeaders(),'id','ProfileNameWithIdNumber');
	}

	public function getChamaOrganizationsList(){
		return CHtml::listData(Chama::getChamaOrganizations(),'id','name');
	}

	public function getChamaLocationsList(){
		return CHtml::listData(Chama::getChamaLocations(),'id','name');
	}

	public function getGroupLeaderName(){
		$profile = Profiles::model()->findByPk($this->leader);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getGroupLeaderPhoneNumber(){
		$profile = Profiles::model()->findByPk($this->leader);
		return !empty($profile) ? $profile->ProfilePhoneNumber : "UNDEFINED";
	}

	public function getGroupCollectorName(){
		$profile = Profiles::model()->findByPk($this->rm);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getChamaTotalSavings(){
		$chamaId       = $this->id;
		$accountsQuery = "SELECT * FROM savingaccounts WHERE is_approved IN('1')
		AND user_id IN(SELECT user_id FROM chama_members WHERE chama_id=$chamaId)";
		$accounts      = Savingaccounts::model()->findAllBySql($accountsQuery);
		if(!empty($accounts)){
			$totalSavings = 0;
			foreach($accounts AS $account){
				$totalSavings += SavingFunctions::getTotalSavingAccountBalance($account->savingaccount_id);
			}
			return $totalSavings;
		}else{
			return 0;
		}
	}

	public function getChamaTotalLoans(){
		$chamaId      = $this->id;
		$acountsQuery = "SELECT * FROM loanaccounts WHERE  loan_status NOT IN('0','1','3','8','9','10')
		AND user_id IN(SELECT user_id FROM chama_members WHERE chama_id=$chamaId)";
		$accounts     = Loanaccounts::model()->findAllBySql($acountsQuery);
		if(!empty($accounts)){
			$totalBalance = 0;
			foreach($accounts AS $account){
				$totalBalance += LoanTransactionsFunctions::getTotalLoanBalance($account->loanaccount_id);
			}
			return $totalBalance;
		}else{
			return 0;
		}
	}

	public function getChamaStatus(){
		return $this->is_registered=='0' ? "NOT REGISTERED" : "REGISTERED";
	}

	public function getChamaOrganization(){
		$organization = ChamaOrganizations::model()->findByPk($this->organization_id);
		return !empty($organization) ? strtoupper($organization->name) : "UNDEFINED";
	}

	public function getChamaLocation(){
		$location = ChamaLocations::model()->findByPk($this->location_id);
		return !empty($location) ? strtoupper($location->name) : "UNDEFINED";
	}

	public function getChamaBranch(){
		$branch = Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ? strtoupper($branch->name) : "UNDEFINED";
	}

	public function getChamaMembershipCount(){
		$chamaID         = $this->id;
		$membershipQuery = "SELECT COUNT(user_id) AS user_id FROM chama_members WHERE chama_id=$chamaID";
		$membership      = ChamaMembers::model()->findBySql($membershipQuery);
		return !empty($membership) ? $membership->user_id : 0;
	}

	public function getChamaCreatedBy(){
		$profile = Profiles::model()->findByPk($this->created_by);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getChamaCreatedAt(){
		return date("jS M Y",strtotime($this->created_at));
	}

	public function getAction(){
		/*VIEW*/
		if(Navigation::checkIfAuthorized(137) === 1){
			$view_link ="<a href='#' class='btn btn-info btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('chamas/view/'.$this->id)."\")'><i class='fa fa-eye'></i></a>";
		}else{
			$view_link="";
		}
		/*UPDATE*/
		if(Navigation::checkIfAuthorized(136) === 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('chamas/update/'.$this->id)."\")'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link="";
		}
		/*DELETE*/
		if(Navigation::checkIfAuthorized(135) === 1){
			$delete_link="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('chamas/delete/'.$this->id)."\")'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_link="";
		}
		$action_links="$view_link&nbsp;$update_link&nbsp;$delete_link";
		echo $action_links;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Chamas the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

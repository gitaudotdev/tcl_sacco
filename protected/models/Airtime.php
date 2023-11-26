<?php

/**
 * This is the model class for table "airtime".
 *
 * The followings are the available columns in table 'airtime':
 * @property integer $id
 * @property integer $user_id
 * @property integer $branch_id
 * @property integer $rm
 * @property string $phone_number
 * @property string $amount
 * @property string $status
 * @property integer $authorized_by
 * @property integer $disbursed_by
 * @property string $date_authorized
 * @property string $date_disbursed
 * @property string $created_at
 */
class Airtime extends CActiveRecord{

	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'airtime';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		return array(
			array('user_id, branch_id, rm, phone_number, amount', 'required'),
			array('user_id, branch_id, rm, authorized_by, disbursed_by', 'numerical', 'integerOnly'=>true),
			array('phone_number, amount', 'length', 'max'=>15),
			array('status', 'length', 'max'=>1),
			array('reason', 'length', 'max'=>512),
			array('date_authorized, date_disbursed', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, branch_id, rm, phone_number, amount, status, authorized_by, disbursed_by, date_authorized, date_disbursed, created_at,startDate,endDate,reason', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'branch_id' => 'Branch',
			'rm' => 'Rm',
			'phone_number' => 'Phone Number',
			'amount' => 'Amount',
			'status' => 'Status',
			'reason' => 'Airtime Reason',
			'authorized_by' => 'Authorized By',
			'disbursed_by' => 'Disbursed By',
			'date_authorized' => 'Date Authorized',
			'date_disbursed' => 'Date Disbursed',
			'created_at' => 'Created At',
		);
	}
	
	public function search(){
		$alias = $this->getTableAlias(false,false);
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('rm',$this->rm);
		$criteria->compare('phone_number',$this->phone_number,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('authorized_by',$this->authorized_by);
		$criteria->compare('disbursed_by',$this->disbursed_by);
		$criteria->compare('date_authorized',$this->date_authorized,true);
		$criteria->compare('date_disbursed',$this->date_disbursed,true);
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

			case '3':
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

	public function getRelationManagersList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileSavingAccount');
	}

	public function getBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getStaffList(){
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

	public function getAirtimeBranchName(){
		$branch=Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ? strtoupper($branch->name) : "UNDEFINED";
	}

	public function getAirtimeRelationManager(){
		$profile = Profiles::model()->findByPk($this->rm);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getAirtimeMemberName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getAirtimePhoneNumber(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfilePhoneNumber : "UNDEFINED";
	}

	public function getAirtimeAmount(){
		return CommonFunctions::asMoney($this->amount);
	}

	public function getAirtimeRequestStatus(){
		switch($this->status){
			case '0':
			$requestStatus="<span class='badge badge-default'>Initiated</span>";
			break;

			case '1':
			$requestStatus="<span class='badge badge-info'>Approved</span>";
			break;

			case '2':
			$requestStatus="<span class='badge badge-success'>Disbursed</span>";
			break;

			case '3':
			$requestStatus="<span class='badge badge-danger'>Rejected</span>";
			break;
		}
		echo $requestStatus;
	}

	public function getAirtimeDateRequested(){
		return date('jS M Y',strtotime($this->created_at));
	}

	public function getAirtimeAction(){
		//Update
		if(Navigation::checkIfAuthorized(173) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' title='Update Transaction' onclick='Authenticate(\"".Yii::app()->createUrl('airtime/update/'.$this->id)."\")'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link="";
		}
		//Approve
		if(Navigation::checkIfAuthorized(169) == 1){
			$approve_link="<a href='#' class='btn btn-success btn-sm' title='Approve Transaction' onclick='Authenticate(\"".Yii::app()->createUrl('airtime/approve/'.$this->id)."\")'><i class='fa fa-check'></i></a>";
		}else{
			$approve_link="";
		}
		//Reject
		if(Navigation::checkIfAuthorized(170) == 1){
			$reject_link="<a href='#' class='btn btn-danger btn-sm' title='Reject Transaction ' onclick='Authenticate(\"".Yii::app()->createUrl('airtime/reject/'.$this->id)."\")'><i class='fa fa-bolt'></i></a>";
		}else{
			$reject_link="";
		}
		//Disburse
		if(Navigation::checkIfAuthorized(171) == 1){
			$disburse_link="<a href='#' class='btn btn-success btn-sm' title='Disburse Airtime' onclick='Authenticate(\"".Yii::app()->createUrl('airtime/disburse/'.$this->id)."\")'><i class='fa fa-random'></i></a>";
		}else{
			$disburse_link="";
		}
		switch($this->status){
			case '0':
			$actionLinks="$update_link&nbsp;$approve_link&nbsp;$reject_link";
			break;

			case '1':
			$actionLinks="$disburse_link";
			break;

			default:
			$actionLinks="";
			break;
		}
		echo $actionLinks;
	}

	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "groupSMS".
 *
 * The followings are the available columns in table 'groupSMS':
 * @property integer $id
 * @property string $message
 * @property string $status
 * @property integer $createdBy
 * @property string $createdAt
 * @property integer $actionedBy
 * @property string $actionedAt
 * @property string $actionReason
 */
class GroupSMS extends CActiveRecord{

	public $startDate, $endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'groupSMS';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message, createdBy', 'required'),
			array('createdBy,branchId,managerId, actionedBy', 'numerical', 'integerOnly'=>true),
			array('message, actionReason', 'length', 'max'=>1024),
			array('status', 'length', 'max'=>9),
			array('createdAt, actionedAt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, message, status, createdBy,branchId,managerId,groupType, createdAt, actionedBy, actionedAt, actionReason,startDate,endDate', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'groupType' => 'Group Type',
			'message' => 'Message',
			'status' => 'Status',
			'createdBy' => 'Created By',
			'createdAt' => 'Created At',
			'actionedBy' => 'Actioned By',
			'actionedAt' => 'Actioned At',
			'actionReason' => 'Action Reason',
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
		$criteria->compare('managerId',$this->managerId);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('createdBy',$this->createdBy);
		$criteria->compare('createdAt',$this->createdAt,true);
		$criteria->compare('actionedBy',$this->actionedBy);
		$criteria->compare('actionedAt',$this->actionedAt,true);
		$criteria->compare('actionReason',$this->actionReason,true);
		$criteria->addInCondition('groupType',array('CHAMA'));

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition('DATE(createdAt)',$this->startDate, $this->endDate,'AND');
		}

		switch(Yii::app()->user->user_level){
			case '0':
			break;

			case '1':
			$criteria->addCondition('branchId ='.Yii::app()->user->user_branch);
			break;

			default:
			$criteria->addCondition('createdBy ='.Yii::app()->user->user_id);
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

	public function searchAuths(){
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('branchId',$this->branchId);
		$criteria->compare('managerId',$this->managerId);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('createdBy',$this->createdBy);
		$criteria->compare('createdAt',$this->createdAt,true);
		$criteria->compare('actionedBy',$this->actionedBy);
		$criteria->compare('actionedAt',$this->actionedAt,true);
		$criteria->compare('actionReason',$this->actionReason,true);
		$criteria->addInCondition('groupType',array('AUTH_LEVEL'));

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition('DATE(createdAt)',$this->startDate, $this->endDate,'AND');
		}

		switch(Yii::app()->user->user_level){
			case '0':
			break;

			case '1':
			$criteria->addCondition('branchId ='.Yii::app()->user->user_branch);
			break;

			default:
			$criteria->addCondition('createdBy ='.Yii::app()->user->user_id);
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

	public function getManagersList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}
	
	public function getBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getGroupSMSDateInitiated(){
      return date('jS M Y',strtotime($this->createdAt));
	}

	public function getGroupSMSMessage(){
      echo '<div class="text-wrap width-75">'.$this->message.'</div>';
	}

	public function getGroupSMSStatus(){
      return $this->status;
	}

	public function getGroupSMSInitiatedBy(){
		$profile = Profiles::model()->findByPk($this->createdBy);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getGroupSMSBranch(){
		$profile = Profiles::model()->findByPk($this->createdBy);
		return !empty($profile) ? $profile->ProfileBranch : "UNDEFINED";
	}

	public function getGroupSMSManager(){
		$profile = Profiles::model()->findByPk($this->managerId);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getGroupSMSAction(){
		/* VIEW */
		if(Navigation::checkIfAuthorized(288) === 1){
			$view_link ="<a href='#' class='btn btn-info btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('groupSMS/view/'.$this->id)."\")'><i class='fa fa-eye'></i></a>";
		}else{
			$view_link="";
		}
		/* UPDATE */
		if(Navigation::checkIfAuthorized(289) === 1 && $this->status === 'SUBMITTED'){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('groupSMS/update/'.$this->id)."\")'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link="";
		}
		$action_links="$view_link&nbsp;$update_link";
		echo $action_links;
	}

	public function getAuthLevelSMSAction(){
		/* VIEW */
		if(Navigation::checkIfAuthorized(307) === 1){
			$view_link ="<a href='#' class='btn btn-info btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('groupSMS/authsView/'.$this->id)."\")'><i class='fa fa-eye'></i></a>";
		}else{
			$view_link="";
		}
		/* UPDATE */
		if(Navigation::checkIfAuthorized(306) === 1 && $this->status === 'SUBMITTED'){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('groupSMS/authsUpdate/'.$this->id)."\")'><i class='fa fa-edit'></i></a>";
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
	 * @return GroupSMS the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

<?php
/**
 * This is the model class for table "leave_applications".
 *
 * The followings are the available columns in table 'leave_applications':
 * @property integer $id
 * @property integer $leave_id
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 * @property integer $authorized_by
 * @property string $authorized_at
 */
class LeaveApplications extends CActiveRecord{
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'leave_applications';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		return array(
			array('leave_id, start_date, end_date, directed_to', 'required'),
			array('leave_id, authorized_by,directed_to,handover_to,branch_id,user_id', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>1),
			array('id, leave_id, start_date, end_date, status, authorized_by, authorized_at,created_at,directed_to,handover_to,branch_id,user_id,auth', 'safe', 'on'=>'search'),
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
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'leave_id' => 'Leave ID',
			'start_date' => 'Leave Start On',
			'end_date' => 'Report Back On',
			'status' => 'Application Status',
			'authorized_by' => 'Authorized By',
			'authorized_at' => 'Authorized At',
			'directed_to' => 'Directed To',
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
		$criteria->compare('leave_id',$this->leave_id);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('authorized_by',$this->authorized_by);
		$criteria->compare('handover_to',$this->handover_to);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('authorized_at',$this->authorized_at,true);
		$criteria->compare('directed_to',$this->directed_to,true);
		$criteria->compare('created_at',$this->created_at,true);

		switch(Yii::app()->user->user_level){
			case'1':
			$criteria->addCondition('branch_id ='.Yii::app()->user->user_branch);
			break;

			case'2':
			$criteria->addCondition('user_id ='.Yii::app()->user->user_id);
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
				'pageSize'=>15
			),
		));
	}

	public function getBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getFullSaccoStaffList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getDirectedTo(){
		$profile = Profiles::model()->findByPk($this->directed_to);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getApplicationStaffName(){
		$leave = Leaves::model()->findByPk($this->leave_id);
		if(!empty($leave)){
			$profile = Profiles::model()->findByPk($leave->user_id);
			$fullname= !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
		}else{
			$fullname = "UNDEFINED";
		}
		return $fullname;
	}

	public function getApplicationStaffBranch(){
		$leave = Leaves::model()->findByPk($this->leave_id);
		if(!empty($leave)){
			$profile = Profiles::model()->findByPk($leave->user_id);
			$staffBranch= !empty($profile) ? $profile->ProfileBranch : "UNDEFINED";
		}else{
			$staffBranch = "UNDEFINED";
		}
		return $staffBranch;
	}

	public function getApplicationStaffPhone(){
		$leave = Leaves::model()->findByPk($this->leave_id);
		if(!empty($leave)){
			$profile    = Profiles::model()->findByPk($leave->user_id);
			$staffPhone = !empty($profile) ? $profile->ProfilePhoneNumber : "UNDEFINED";
		}else{
			$staffPhone = "UNDEFINED";
		}
		return $staffPhone;
	}

	public function getApplicationStaffEmail(){
		$leave = Leaves::model()->findByPk($this->leave_id);
		if(!empty($leave)){
			$email        = ProfileEngine::getProfileContactByTypeOrderDesc($leave->user_id,'EMAIL');
			$emailAddress = !empty($email) ? $email : "UNDEFINED";
		}else{
			$emailAddress = "UNDEFINED";
		}
		return $emailAddress;
	}

	public function getApplicationStatus(){
		echo $this->Status;
	}

	public function getStatus(){
		switch($this->status){
			case '0':
			return "<span class='badge badge-info'>Submitted</span>";
			break;

			case '1':
			return "<span class='badge badge-success'>Approved</span>";
			break;

			case '2':
			return "<span class='badge badge-danger'>Rejected</span>";
			break;
		}
	}	

	public function getApplicationAuthorizedByName(){
		switch($this->authorized_by){
			case 0:
			$fullname   = "UNDEFINED";
			break;

			default:
			$profile    = Profiles::model()->findByPk($this->authorized_by);
			$fullname   = !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
			break;
		}
		return $fullname;
	}

	public function getLeaveStartOn(){
		return date('d-m-y',strtotime($this->start_date));
	}

	public function getLeaveEndOn(){
		return date('d-m-y',strtotime($this->end_date));
	}

	public function getLeaveCreatedAt(){
		return date('d-m-y',strtotime($this->created_at));
	}

	public function getLeaveAuthorizedAt(){
		return date('jS M Y',strtotime($this->authorized_at));
	}

	public function getHandoverTo(){
		switch($this->handover_to){
			case 0:
			$initiator  = 'AUTOMATED';
			break;

			default:
			$profile    = Profiles::model()->findByPk($this->handover_to);
			$initiator  = !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
			break;
		}
		return $initiator;
	}

	public function getAction(){
		$leave = Leaves::model()->findByPk($this->leave_id);
		if(Navigation::checkIfAuthorized(120) == 1){
			$view_link="<a href='".Yii::app()->createUrl('leaveApplications/'.$this->id)."' title='View Request' class='btn btn-info btn-sm'> <i class='fa fa-eye'></i></a>";
		}else{
			$view_link="";
		}
		if(Navigation::checkIfAuthorized(180) == 1){
			$reject_link="<a href='#' class='btn btn-default btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('leaveApplications/reject/'.$this->id)."\")' title='Reject Request'><i class='fa fa-times'></i></a>";
		}else{
			$reject_link="";
		}
		if(Navigation::checkIfAuthorized(179) == 1){
			$approve_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('leaveApplications/approve/'.$this->id)."\")' title='Approve Request'><i class='fa fa-check'></i></a>";
		}else{
			$approve_link="";
		}

		if(Navigation::checkIfAuthorized(183) == 1){
			$delete_link="<a href='#' class='btn btn-danger btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('leaveApplications/delete/'.$this->id)."\")' title='Delete Request'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_link="";
		}

		$action_links="$view_link&nbsp;$approve_link&nbsp;$reject_link&nbsp;$delete_link";
		switch(Yii::app()->user->user_level){
			case '0':
			if(Yii::app()->user->user_id === $leave->user_id){
				echo "$view_link";
			}else{
				if($this->status === '0'){
					echo $action_links;
				}else{
					echo "$view_link&nbsp;$delete_link";
				}
			}
			break;

			case '1':
			if(Yii::app()->user->user_id === $leave->user_id){
				echo "$view_link";
			}else{
				if($this->status === '0'){
					echo $action_links;
				}else{
					echo "$view_link&nbsp;$delete_link";
				}
			}
			break;

			default:
			echo "$view_link&nbsp;$delete_link";
			break;
		}
	}

	public function getSpecificApplicationAction(){
		$leave = Leaves::model()->findByPk($this->leave_id);
		if(Navigation::checkIfAuthorized(120) == 1){
			$view_link="<a href='".Yii::app()->createUrl('leaveApplications/'.$this->id)."' title='View Request' class='btn btn-info btn-sm'> <i class='fa fa-eye'></i></a>";
		}else{
			$view_link="";
		}

		if(Navigation::checkIfAuthorized(180) == 1){
			$reject_link="<a href='#' class='btn btn-default btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('leaveapplications/rejectApplication/'.$this->id)."\")' title='Reject Request'><i class='fa fa-times'></i></a>";
		}else{
			$reject_link="";
		}

		if(Navigation::checkIfAuthorized(179) == 1){
			$approve_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('leaveapplications/approveApplication/'.$this->id)."\")' title='Approve Request'><i class='fa fa-check'></i></a>";
		}else{
			$approve_link="";
		}

		if(Navigation::checkIfAuthorized(183) == 1){
			$delete_link="<a href='#' class='btn btn-danger btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('leaveApplications/delete/'.$this->id)."\")' title='Delete Request'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_link="";
		}

		$action_links="$view_link&nbsp;$approve_link&nbsp;$reject_link&nbsp;$delete_link";
		switch(Yii::app()->user->user_level){
			case '0':
			if(Yii::app()->user->user_id === $leave->user_id){
				echo "$view_link";
			}else{
				if($this->status === '0'){
					echo $action_links;
				}else{
					echo "$view_link&nbsp;$delete_link";
				}
			}
			break;

			case '1':
			if(Yii::app()->user->user_id === $leave->user_id){
				echo "$view_link";
			}else{
				if($this->status === '0'){
					echo $action_links;
				}else{
					echo "$view_link&nbsp;$delete_link";
				}
			}
			break;

			default:
			echo "$view_link&nbsp;$delete_link";
			break;
		}
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LeaveApplications the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

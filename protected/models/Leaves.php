<?php
/**
 * This is the model class for table "leaves".
 *
 * The followings are the available columns in table 'leaves':
 * @property integer $id
 * @property integer $user_id
 * @property integer $leave_days
 * @property integer $carry_over
 * @property integer $created_by
 * @property string $created_at
 */
class Leaves extends CActiveRecord{

	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'leaves';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		return array(
			array('user_id, leave_days, carry_over', 'required'),
			array('user_id, leave_days, carry_over, created_by,branch_id', 'numerical', 'integerOnly'=>true),
			array('user_id,leave_days,carry_over,created_at,startDate,endDate,branch_id','safe','on'=>'search'),
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
			'user_id' => 'Staff Member',
			'leave_days' => 'Total Annual Leave Days',
			'carry_over' => 'Total Days To Carry Over (To The Following Year)',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('leave_days',$this->leave_days);
		$criteria->compare('carry_over',$this->carry_over);
		$criteria->compare('created_by',$this->created_by);
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

			default:
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

	public function getSaccoStaffList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getFullSaccoStaffList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getLeaveStaffName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getLeaveStaffBranch(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileBranch : "UNDEFINED";
	}

	public function getLeaveStaffPhone(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfilePhoneNumber : "UNDEFINED";
	}

	public function getLeaveStaffEmail(){
		$email = ProfileEngine::getProfileContactByTypeOrderDesc($this->user_id,'EMAIL');
		return !empty($email) ? $email : "UNDEFINED";
	}

	public function getLeaveCreatedByName(){
		$profile = Profiles::model()->findByPk($this->created_by);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getLeaveCreatedAt(){
		return date('jS M Y',strtotime($this->created_at));
	}

	public function getStaffLeaveBalance(){
		$totalLeaveDays=$this->leave_days;
		$totalDaysTaken=leavesManager::calculateTotalLeaveDaysTaken($this->id);
		return leavesManager::calculateRemainingLeaveDays($totalLeaveDays,$totalDaysTaken);
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(187) == 1){
			$view_link="<a href='".Yii::app()->createUrl('leaves/'.$this->id)."' title='View Applications' class='btn btn-info btn-sm'> <i class='fa fa-eye'></i></a>";
		}else{
			$view_link="";
		}
		if(Navigation::checkIfAuthorized(186) == 1){
			$delete_link="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('leaves/delete/'.$this->id)."\")'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_link="";
		}
		if(Navigation::checkIfAuthorized(184) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('leaves/update/'.$this->id)."\")'><i class='fa fa-edit'></i></a>";
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
	 * @return Leaves the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

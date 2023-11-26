<?php

/**
 * This is the model class for table "sms_alerts".
 *
 * The followings are the available columns in table 'sms_alerts':
 * @property integer $id
 * @property string $message_id
 * @property string $phone_number
 * @property string $cost
 * @property string $message
 * @property integer $sent_by
 * @property string $sent_at
 */
class SmsAlerts extends CActiveRecord{

	public $startDate,$endDate;

	public function scopes(){
		return array(
			'byID' => array('order' => 'id DESC')
		);
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sms_alerts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message_id, phone_number, cost, message', 'required'),
			array('sent_by', 'numerical', 'integerOnly'=>true),
			array('message_id, message', 'length', 'max'=>512),
			array('phone_number', 'length', 'max'=>20),
			array('cost', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, message_id, phone_number, cost, message, sent_by, sent_at,startDate,endDate,profileId,branchId,managerId', 'safe', 'on'=>'search'),
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
			'message_id' => 'Message',
			'profileId' => 'Profile',
			'branchId' => 'Branch',
			'managerId' => 'Manager',
			'phone_number' => 'Phone Number',
			'cost' => 'Cost',
			'message' => 'Message',
			'sent_by' => 'Sent By',
			'sent_at' => 'Sent At',
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
		$criteria->compare('message_id',$this->message_id,true);
		$criteria->compare('profileId',$this->profileId);
		$criteria->compare('managerId',$this->managerId);
		$criteria->compare('branchId',$this->branchId);
		$criteria->compare('phone_number',$this->phone_number,true);
		$criteria->compare('cost',$this->cost,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('sent_by',$this->sent_by);
		$criteria->compare('sent_at',$this->sent_at,true);
		$criteria->addCondition('phone_number !=0');

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.sent_at)",$this->startDate, $this->endDate, 'AND');
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
		return CHtml::listData(ProfileEngine::getProfilesByType('ALL'),'id','ProfileSavingAccount');
	}
	
	public function getProfileBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getAlertMessage(){
		echo'<div class="text-wrap width-100">'.$this->message.'</div>';
	}

	public function getAlertPhoneNumber(){
		return $this->phone_number;
	}

	public function getAlertCost(){
		return $this->cost;
	}

	public function getAlertSentBy(){
		if($this->sent_by == 1){
			$sentBy = 'SYSTEM GENERATED';
		}else{
			$user= Profiles::model()->findByPk($this->sent_by);
			$sentBy = !empty($user) ? $user->ProfileFullName : 'SYSTEM GENERATED';
		}
		return $sentBy;
	}

	public function getAlertBranchName(){
		$user = Profiles::model()->findByPk($this->sent_by);
		return !empty($user) ? $user->ProfileBranch : 'UNDEFINED';;
	}

	public function getDateAlertSent(){
		return date('d/m/Y h:i A',strtotime($this->sent_at));
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SmsAlerts the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

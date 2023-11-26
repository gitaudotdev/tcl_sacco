<?php

/**
 * This is the model class for table "notifications".
 *
 * The followings are the available columns in table 'notifications':
 * @property integer $id
 * @property integer $branch_id
 * @property integer $user_id
 * @property string $message
 * @property string $status
 * @property string $is_replied
 * @property string $replied_at
 * @property integer $sent_to
 * @property string $reply
 * @property integer $created_by
 * @property string $created_at
 */
class Notifications extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'notifications';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('branch_id, user_id, message, sent_to', 'required'),
			array('branch_id, user_id, sent_to, created_by', 'numerical', 'integerOnly'=>true),
			array('message, reply', 'length', 'max'=>1024),
			array('status, is_replied', 'length', 'max'=>1),
			array('replied_at', 'safe'),
			array('id, branch_id, user_id, message, status, is_replied, replied_at, sent_to, reply, created_by, created_at', 'safe', 'on'=>'search'),
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
			'branch_id' => 'Branch',
			'user_id' => 'User',
			'message' => 'Message',
			'status' => 'Status',
			'is_replied' => 'Is Replied',
			'replied_at' => 'Replied At',
			'sent_to' => 'Sent To',
			'reply' => 'Reply',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('is_replied',$this->is_replied,true);
		$criteria->compare('replied_at',$this->replied_at,true);
		$criteria->compare('sent_to',$this->sent_to);
		$criteria->compare('reply',$this->reply,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		/*Additional Conditions*/
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case'1':
			$criteria->addCondition('branch_id ='.$userBranch);
			break;

			case '3':
			$criteria->addCondition('user_id ='.$userID);
			break;
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
         'defaultOrder'=>'id DESC',
      ),
			'pagination'=>array(
         'pageSize'=>30
       ),
		));
	}

	public function getBranchName(){
		$branch=Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ? strtoupper($branch->name) : "UNDEFINED";
	}

	public function getNotificationSender(){
		$user=Profiles::model()->findByPk($this->user_id);
		return !empty($user) ? $user->ProullName : "UNDEFINED";
	}

	public function getNotificationRecipient(){
		$user=Profiles::model()->findByPk($this->sent_to);
		return !empty($user) ? $user->ProullName : "UNDEFINED";
	}

	public function getNotificationContent(){
		echo '<div class="text-wrap width-200">'.$this->message.'</div>';
	}

	public function getNotificationStatus(){
		return $this->status === '0' ? "UNREAD" : "READ";
	}

	public function getNotificationReplied(){
		return $this->is_replied === '0' ? "NOT REPLIED" : "REPLIED";
	}

	public function getNotificationReplyContent(){
		echo '<div class="text-wrap width-200">'.$this->reply.'</div>';
	}

	public function getNotificationInitiatedBy(){
		switch($this->created_by){
			case 0:
			$initiator='Automated';
			break;

			default:
			$user = Profiles::model()->findByPk($this->created_by);
			$initiator = !empty($user) ? $user->ProfileFullName : "UNDEFINED";
			break;
		}
		return $initiator;
	}

	public function getNOtificationInitiationDate(){
		return date('jS M Y',strtotime($this->created_at));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notifications the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

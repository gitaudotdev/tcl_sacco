<?php
/**
 * This is the model class for table "logs".
 *
 * The followings are the available columns in table 'logs':
 * @property integer $log_id
 * @property integer $user_id
 * @property integer $branch_id
 * @property string $activity
 * @property string $severity
 * @property string $logged_at
 */
class Logs extends CActiveRecord{

	public $startDate,$endDate;

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'logs';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		return array(
			array('user_id, activity', 'required'),
			array('user_id, branch_id', 'numerical', 'integerOnly'=>true),
			array('activity', 'length', 'max'=>255),
			array('severity', 'length', 'max'=>6),
			array('user_id,branch_id,severity,logged_at,startDate,endDate', 'safe', 'on'=>'search'),
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
			'log_id' => 'Log',
			'user_id' => 'User',
			'branch_id' => 'Branch',
			'activity' => 'Activity',
			'severity' => 'Severity',
			'logged_at' => 'Logged At',
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
		$criteria->compare('log_id',$this->log_id);
		$criteria->compare("$alias.user_id",$this->user_id);
		$criteria->compare("$alias.branch_id",$this->branch_id);
		$criteria->compare("$alias.activity",$this->activity,true);
		$criteria->compare("$alias.severity",$this->severity);

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.logged_at)",$this->startDate, $this->endDate, 'AND');
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
				'defaultOrder'=>'log_id DESC',
			),
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}

	public function getSaccoBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getUsersList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('ALL'),'id','ProfileNameWithIdNumber');
	}

	public function getLoggedUser(){
		$user = Profiles::model()->findByPk($this->user_id);
		return !empty($user) ? $user->ProfileFullName : "SYSTEM AUTOMATED";
	}

	public function getLoggedUserRelationManager(){
		$user = Profiles::model()->findByPk($this->user_id);
		return !empty($user) ? $user->ProfileManager : "SYSTEM AUTOMATED";
	}

	public function getLoggedBranch(){
		$branch=Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ? strtoupper($branch->name) : "AUTOMATED BRANCH";
	}

	public function getLoggedActivity(){
		echo '<div class="text-wrap width-200">'.$this->activity.'</div>';
	}

	public function getActivitySeverity(){
		return ucfirst($this->severity);
	}

	public function getDateLogged(){
		return date('d/m/Y h:i A',strtotime($this->logged_at));
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Logs the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

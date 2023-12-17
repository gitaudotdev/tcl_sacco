<?php

/**
 * This is the model class for table "penalty_freezes".
 *
 * The followings are the available columns in table 'interest_freezes':
 * @property integer $id
 * @property integer $loanaccount_id
 * @property string $date_frozen
 * @property integer $period_frozen
 * @property integer $frozen_by
 * @property string $date_unfrozen
 * @property integer $unfrozen_by
 * @property string $unfrozen_type
 * @property string $unfrozen_reason
 * @property integer $created_by
 * @property string $created_at
 */
class PenaltyFreezes extends CActiveRecord{

	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'penalty_freezes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loanaccount_id, period_frozen, frozen_by, created_by,branch_id,user_id,rm', 'required'),
			array('loanaccount_id, period_frozen, frozen_by, unfrozen_by, created_by', 'numerical', 'integerOnly'=>true),
			array('unfrozen_type', 'length', 'max'=>1),
			array('unfrozen_reason,freezing_reason', 'length', 'max'=>512),
			array('date_frozen, date_unfrozen', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, loanaccount_id, date_frozen, period_frozen, frozen_by, date_unfrozen, unfrozen_by, unfrozen_type, unfrozen_reason,freezing_reason,created_by, created_at,startDate,endDate,branch_id,user_id,rm', 'safe', 'on'=>'search'),
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
			'loanaccount_id' => 'Loanaccount',
			'branch_id' => 'Branch',
			'user_id' => 'Loanaccount Holder',
			'rm' => 'Account Manager',
			'date_frozen' => 'Date Frozen',
			'period_frozen' => 'Period Frozen',
			'frozen_by' => 'Frozen By',
			'date_unfrozen' => 'Date Unfrozen',
			'unfrozen_by' => 'Unfrozen By',
			'unfrozen_type' => 'Unfrozen Type',
			'unfrozen_reason' => 'Unfrozen Reason',
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
		$criteria->compare('loanaccount_id',$this->loanaccount_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('rm',$this->rm);
		$criteria->compare('date_frozen',$this->date_frozen,true);
		$criteria->compare('period_frozen',$this->period_frozen);
		$criteria->compare('frozen_by',$this->frozen_by);
		$criteria->compare('date_unfrozen',$this->date_unfrozen,true);
		$criteria->compare('unfrozen_by',$this->unfrozen_by);
		$criteria->compare('unfrozen_type',$this->unfrozen_type,true);
		$criteria->compare('unfrozen_reason',$this->unfrozen_reason,true);
		$criteria->compare('freezing_reason',$this->freezing_reason,true);
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

			case '2':
			$criteria->addCondition('rm ='.Yii::app()->user->user_id);
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

	public function getSaccoBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getRelationshipManagers(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getBorrowerList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('ALL'),'id','ProfileNameWithIdNumber');
	}

	public function getBranchName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileBranch : "UNDEFINED";
	}

	public function getRelationManager(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileManager : "UNDEFINED";
	}

	public function getClientName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getCurrentLoanBalance(){
		$loanaccount=Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loanaccount) ? LoanManager::getActualLoanBalance($loanaccount->loanaccount_id) : 0;
	}

	public function getFormattedCurrentLoanBalance(){
		return CommonFunctions::asMoney($this->CurrentLoanBalance);
	}

	public function getCurrentInterestRate(){
		$loanaccount = Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loanaccount) ? $loanaccount->interest_rate." %" : "UNDEFINED";
	}

	public function getFreezeStartDate(){
		return date('jS M Y',strtotime($this->date_frozen));
	}

	public function getFreezeEndDate(){
		switch($this->period_frozen){
			case 575:
			$freezeEndDate="INDEFINITE";
			break;

			default:
			if($this->unfrozen_type != '0'){
				$freezeEndDate="ALREADY UNFROZEN";
			}else{
				$currentDate=$this->FreezeStartDate;
				$period=$this->period_frozen;
				$freezeEndDate=date("jS M Y",strtotime($currentDate. "+ $period days"));
			}
			break;
		}
		return $freezeEndDate;
	}

	public function getFreezeRemainingDays(){
		switch($this->period_frozen){
			case 575:
			$remainingFreezeDays="INDEFINITE";
			break;

			default:
			if($this->unfrozen_type != '0'){
				$remainingFreezeDays=0;
			}else{
				$startDate=date('Y-m-d',strtotime($this->FreezeStartDate));
				$endDate=date('Y-m-d');
				$currentDifference=CommonFunctions::getDatesDifference($startDate,$endDate);
				$remainingFreezeDays=$this->period_frozen - $currentDifference;
				if($remainingFreezeDays<=0){
					$remainingFreezeDays=0;
				}
			}
			break;
		}
		return $remainingFreezeDays;
	}

	public function getFreezeStatus(){
		switch($this->unfrozen_type){
			case '0':
			$accountFreezeStatus="FROZEN";
			break;

			case '1':
			$accountFreezeStatus="AUTOMATICALLY UNFROZEN";
			break;

			case '2':
			$accountFreezeStatus="UNFROZEN BY: ".$this->UnfrozenBy;
			break;
		}
		return $accountFreezeStatus;
	}

	public function getUnfrozenBy(){
		switch($this->unfrozen_type){
			case '0':
			$accountUnfrozenBy="STILL FROZEN";
			break;

			case '1':
			$accountUnfrozenBy="AUTOMATICALLY UNFROZEN";
			break;

			case '2':
			$profile = Profiles::model()->findByPk($this->unfrozen_by);
			$accountUnfrozenBy = !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
			break;
		}
		return $accountUnfrozenBy;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InterestFreezes the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "loaninterests".
 *
 * The followings are the available columns in table 'loaninterests':
 * @property integer $id
 * @property integer $loanaccount_id
 * @property string $interest_accrued
 * @property string $is_paid
 * @property string $accrued_at
 */
class Loaninterests extends CActiveRecord{

	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'loaninterests';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loanaccount_id, interest_accrued', 'required'),
			array('loanaccount_id', 'numerical', 'integerOnly'=>true),
			array('interest_accrued', 'length', 'max'=>15),
			array('is_paid', 'length', 'max'=>1),
			array('transaction_type', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id,loanaccount_id,interest_accrued,transaction_type,is_paid,accrued_at,startDate,endDate,profileId,branchId,managerId','safe','on'=>'search'),
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
			'loanaccount_id' => 'Loanaccount',
			'interest_accrued' => 'Interest Accrued',
			'transaction_type' => 'Transaction Type',
			'is_paid' => 'Is Paid',
			'accrued_at' => 'Accrued At',
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
		$criteria->compare('profileId',$this->profileId);
		$criteria->compare('branchId',$this->branchId);
		$criteria->compare('managerId',$this->managerId);
		$criteria->compare('loanaccount_id',$this->loanaccount_id);
		$criteria->compare('interest_accrued',$this->interest_accrued,true);
		$criteria->compare('transaction_type',$this->transaction_type,true);
		$criteria->compare('is_paid',$this->is_paid,true);
		$criteria->compare('accrued_at',$this->accrued_at,true);
		$criteria->compare("$alias.is_paid",'0',true);

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.accrued_at)",$this->startDate, $this->endDate, 'AND');
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

			case '3':
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

	public function getLoanAcountNumbersList(){
		$userBranch= Yii::app()->user->user_branch;
		$userID    = Yii::app()->user->user_id;
		$accountsQuery = "SELECT * FROM loanaccounts WHERE loan_status IN('2','5','6','7')";
		switch(Yii::app()->user->user_level){
			case '0':
			$accountsQuery.="";
			break;

			case '1':
			$accountsQuery.=" AND branch_id=$userBranch";
			break;

			case '2':
			$accountsQuery.=" AND rm=$userID";
			break;

			case '3':
			$accountsQuery.=" AND user_id=$userID";
			break;
		}
		return CHtml::listData(Loanaccounts::model()->findAllBySql($accountsQuery),'loanaccount_id','AccountDetails');
	}

	public function getBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getRelationshipManagers(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getAccountTotalInterest(){
		$accountID     = $this->loanaccount_id;
		$interestQuery = "SELECT COALESCE(SUM(interest_accrued),0) AS interest_accrued FROM loaninterests WHERE loanaccount_id=$accountID";
		$interest      = Loaninterests::model()->findBySql($interestQuery);
		return !empty($interest) ? $interest->interest_accrued : 0;
	}

	public function getMemberFullName(){
		$user = Profiles::model()->findByPk($this->profileId);
		return !empty($user) ? $user->ProfileFullName : "UNDEFINED";
	}

	public function getMemberBranchName(){
		$user = Profiles::model()->findByPk($this->profileId);
		return !empty($user) ? $user->ProfileBranch : "UNDEFINED";
	}

	public function getRelationshipManagerName(){
		$user = Profiles::model()->findByPk($this->profileId);
		return !empty($user) ? $user->ProfileManager : "UNDEFINED";
	}

	public function getAccountNumber(){
		$loanaccount = Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loanaccount) ? $loanaccount->account_number : 'UNDEFINED';
	}

	public function getInterestAccrued(){
		return CommonFunctions::asMoney($this->interest_accrued);
	}

	public function getDateAccrued(){
		return date('jS M Y',strtotime($this->accrued_at));
	}

	public function getAction(){

		if(Navigation::checkIfAuthorized(123) == 1){
			$updateLink="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('loaninterests/update/'.$this->id)."\")'><i class='fa fa-edit'></i></a>";
		}else{
			$updateLink="";
		}

		if(Navigation::checkIfAuthorized(124) == 1){
			$voidLink="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('loaninterests/void/'.$this->id)."\")'><i class='fa fa-trash'></i></a>";
		}else{
			$voidLink="";
		}

		$actionLinks="$updateLink&nbsp;$voidLink";
		echo $actionLinks;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Loaninterests the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

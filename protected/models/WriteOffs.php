<?php

/**
 * This is the model class for table "write_offs".
 *
 * The followings are the available columns in table 'write_offs':
 * @property integer $id
 * @property integer $loanaccount_id
 * @property string $amount
 * @property integer $created_by
 * @property string $created_at
 */
class WriteOffs extends CActiveRecord{

	public $startDate,$endDate;

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'write_offs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loanaccount_id,branch_id,user_id,rm, amount', 'required'),
			array('loanaccount_id, created_by', 'numerical', 'integerOnly'=>true),
			array('amount', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, loanaccount_id, amount, created_by,created_at,type,reason,branch_id,user_id,rm.startDate,endDate','safe', 'on'=>'search'),
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
			'loanaccount_id' => 'Loanaccount',
			'branch_id' => 'Branch',
			'user_id' => 'Loanaccount Holder',
			'rm' => 'Loanaccount Relation Manager',
			'amount' => 'Amount',
			'type' => 'Type',
			'reason' => 'Reason',
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
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('type',$this->type);
		$criteria->compare('reason',$this->reason);
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

	public function getLoanList(){
		$loanSQL="SELECT * FROM loanaccounts WHERE loanaccount_id IN(SELECT loanaccount_id FROM write_offs)";
		return CHtml::listData(Loanaccounts::model()->findAllBySql($loanSQL),'loanaccount_id','AccountDetails');
	}

	public function getClientName(){
		$loanaccount = Loanaccounts::model()->findByPk($this->loanaccount_id);
		if(!empty($loanaccount)){
			$user     = Profiles::model()->findByPk($loanaccount->user_id);
			$fullName = !empty($user) ? $user->ProfileFullName : "UNDEFINED";
		}else{
			$fullName = "UNDEFINED";
		}
		return $fullName;
	}

	public function getBranchName(){
		$loanaccount=Loanaccounts::model()->findByPk($this->loanaccount_id);
		if(!empty($loanaccount)){
			$user       = Profiles::model()->findByPk($loanaccount->user_id);
			$branchName = !empty($user) ? $user->ProfileBranch : "UNDEFINED";
		}else{
			$branchName = "UNDEFINED";
		}
		return $branchName;
	}

	public function getManagerName(){
		$loanaccount=Loanaccounts::model()->findByPk($this->loanaccount_id);
		if(!empty($loanaccount)){
			$user = Profiles::model()->findByPk($loanaccount->user_id);
			$rm   = !empty($user) ? $user->ProfileManager : "UNDEFINED";
		}else{
			$rm = "UNDEFINED";
		}
		return $rm;
	}

	public function getAccountNumber(){
		$loanaccount=Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loanaccount) ? $loanaccount->account_number : "UNDEFINED";
	}

	public function getWriteOffAmount(){
		return number_format($this->amount);
	}

	public function getTransactedBy(){
		$user = Profiles::model()->findByPk($this->created_by);
		return !empty($user) ? $user->ProfileFullName : "UNDEFINED";
	}


	public function getTransactionDate(){
		return date('jS M Y',strtotime($this->created_at));
	}

	public function getWriteOffType(){
		return ucwords($this->type);
	}

	public function getOriginalLoanAmount(){
		$loanaccount=Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loanaccount) ? $loanaccount->NotFormattedExactAmountDisbursed : 0;
	}

	public function getOriginalInterestRate(){
		$loanaccount=Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loanaccount) ? $loanaccount->interest_rate : 0;
	}

	public function getFormattedOriginalLoanAmount(){
		return CommonFunctions::asMoney($this->OriginalLoanAmount);
	}

	public function getFormattedOriginalInterestRate(){
		return CommonFunctions::asMoney($this->OriginalInterestRate)." %";
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WriteOffs the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

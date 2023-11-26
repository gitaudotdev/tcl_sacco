<?php

/**
 * This is the model class for table "chama_members".
 *
 * The followings are the available columns in table 'chama_members':
 * @property integer $id
 * @property integer $chama_id
 * @property integer $user_id
 * @property integer $created_by
 * @property string $created_at
 */
class ChamaMembers extends CActiveRecord{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'chama_members';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('chama_id, user_id, created_by', 'required'),
			array('chama_id, user_id, created_by', 'numerical', 'integerOnly'=>true),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, chama_id, user_id, created_by, created_at', 'safe', 'on'=>'search'),
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
			'chama_id' => 'Chama',
			'user_id' => 'User',
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
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('chama_id',$this->chama_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getChamaMemberName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getChamaMemberActiveLoanAccountNumber(){
		$profileID    = $this->user_id;
		$acountsQuery = "SELECT * FROM loanaccounts WHERE user_id=$profileID AND loan_status NOT IN('0','1','3','8','9','10')
		ORDER BY loanaccount_id DESC LIMIT 1";
		$account      = Loanaccounts::model()->findBySql($acountsQuery);
		return !empty($account) ? $account->account_number : "NO ACCOUNT";
	}

	public function getChamaMemberActiveLoanBalance(){
		$profileID    = $this->user_id;
		$acountsQuery = "SELECT * FROM loanaccounts WHERE user_id=$profileID AND loan_status NOT IN('0','1','3','8','9','10')";
		$accounts     = Loanaccounts::model()->findAllBySql($acountsQuery);
		if(!empty($accounts)){
			$totalBalance = 0;
			foreach($accounts AS $account){
				$totalBalance += LoanTransactionsFunctions::getTotalLoanBalance($account->loanaccount_id);
			}
			return $totalBalance;
		}else{
			return 0;
		}
	}

	public function getChamaMemberSavingAccountNumber(){
		$profileId     = $this->user_id;
		$accountsQuery = "SELECT * FROM savingaccounts WHERE user_id=$profileId AND is_approved IN('1') ORDER BY savingaccount_id DESC LIMIT 1";
		$account       = Savingaccounts::model()->findBySql($accountsQuery);
		return !empty($account) ? $account->account_number : "NO ACCOUNT";
	}

	public function getChamaMemberSavingsBalance(){
		$profileId     = $this->user_id;
		$accountsQuery = "SELECT * FROM savingaccounts WHERE user_id=$profileId AND is_approved IN('1')";
		$accounts      = Savingaccounts::model()->findAllBySql($accountsQuery);
		if(!empty($accounts)){
			$totalSavings = 0;
			foreach($accounts AS $account){
				$totalSavings += SavingFunctions::getTotalSavingAccountBalance($account->savingaccount_id);
			}
			return $totalSavings;
		}else{
			return 0;
		}
	}

	public function getChamaMemberAction(){
		/* APPLY LOAN */
		if(Navigation::checkIfAuthorized(136) === 1 && LoanApplication::restrictMultipleRunningAccounts($this->user_id) == 0){
			$newLoanLink = "<a href='#' class='btn btn-success btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('chamas/applyLoan/'.$this->user_id)."\")'><i class='fa fa-edit'></i></a>";
		}else{
			$newLoanLink = "";
		}
		echo $newLoanLink;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ChamaMembers the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "savingtransactions".
 *
 * The followings are the available columns in table 'savingtransactions':
 * @property integer $savingtransaction_id
 * @property integer $savingaccount_id
 * @property string $amount
 * @property string $type
 * @property string $description
 * @property integer $transacted_by
 * @property string $transacted_at
 */
class Savingtransactions extends CActiveRecord{

	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'savingtransactions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('savingaccount_id, amount, description, transacted_by', 'required'),
			array('savingaccount_id, transacted_by', 'numerical', 'integerOnly'=>true),
			array('amount', 'length', 'max'=>15),
			array('type', 'length', 'max'=>6),
			array('description', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('savingtransaction_id, savingaccount_id, amount, type, description, transacted_by,startDate,endDate,transacted_at,phone_transacted,profileId,branchId,managerId', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'savingaccounts'=>array(SELF::BELONGS_TO,'Savingaccounts','savingaccount_id'),
			'profiles'=>array(SELF::BELONGS_TO,'Profiles','transacted_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'savingtransaction_id' => 'Savingtransaction',
			'savingaccount_id' => 'Savingaccount',
			'amount' => 'Amount',
			'type' => 'Type',
			'description' => 'Description',
			'transacted_by' => 'Transacted By',
			'transacted_at' => 'Transacted At',
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
		$criteria->compare("savingtransaction_id",$this->savingtransaction_id);
		$criteria->compare("savingaccount_id",$this->savingaccount_id);
		$criteria->compare('profileId',$this->profileId);
		$criteria->compare('branchId',$this->branchId);
		$criteria->compare('managerId',$this->managerId);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('transacted_by',$this->transacted_by);
		$criteria->compare('phone_transacted',$this->phone_transacted);
		$criteria->compare('transacted_at',$this->transacted_at,true);
		$criteria->addInCondition('is_void',array('0'));

		/*Additional Conditions*/
		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE(transacted_at)",$this->startDate, $this->endDate, 'AND');
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
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'savingtransaction_id DESC',
			),
			'pagination' => array(
				'pageSize' => Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}

	public function getSaccoBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getRelationshipManagers(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getSavingAccountNumbersList(){
		$accountQuer = "SELECT * from savingaccounts WHERE is_approved='1'";
		return CHtml::listData(Savingaccounts::model()->findAllBySql($accountQuer),'savingaccount_id','AccountDetails');
	}

	public function getSavingAccountHolderName(){
		$savingaccount=Savingaccounts::model()->findByPk($this->savingaccount_id);
		return !empty($savingaccount) ? $savingaccount->SavingAccountHolderName : "UNDEFINED";
	}

	public function getSavingAccountNumber(){
		$savingaccount=Savingaccounts::model()->findByPk($this->savingaccount_id);
		return !empty($savingaccount) ? $savingaccount->account_number : "UNDEFINED";
	}

	public function getSavingTransactionAmount(){
		return CommonFunctions::asMoney($this->amount);
	}

	public function getSavingTransactionType(){
		return $this->type == 'credit' ? "DEPOSIT" : "WITHDRAW";
	}

	public function getSavingTransactionDescription(){
		return ucfirst($this->description);
	}
	
	public function getSavingTransactionPhoneNumber(){
		return $this->phone_transacted;
	}

	public function getSavingAccountBranch(){
		$savingaccount=Savingaccounts::model()->findByPk($this->savingaccount_id);
		return !empty($savingaccount) ? $savingaccount->SavingAccountHolderBranch : "UNDEFINED";
	}

	public function getSavingAccountRelationManager(){
		$savingaccount=Savingaccounts::model()->findByPk($this->savingaccount_id);
		return !empty($savingaccount) ? $savingaccount->SavingAccountHolderRelationManager : "UNDEFINED";
	}

	public function getSavingTransactionDate(){
		return date('jS M Y',strtotime($this->transacted_at));
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(59) == 1){
			$receipt_link="<a href='#' class='btn btn-info btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('savingtransactions/receipt/'.$this->savingtransaction_id)."\")' title='Download Receipt'><i class='fa fa-file'></i></a>";
		}else{
			$receipt_link="";
		}

		if(Navigation::checkIfAuthorized(60) == 1){
			$delete_link="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('savingtransactions/void/'.$this->savingtransaction_id)."\")' title='Void Transaction'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_link="";
		}

		if(Navigation::checkIfAuthorized(62) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('savingtransactions/update/'.$this->savingtransaction_id)."\")' title='Update Transaction'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link="";
		}
		
		$action_links="$receipt_link&nbsp;$update_link&nbsp;$delete_link";	
		echo $action_links;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Savingtransactions the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

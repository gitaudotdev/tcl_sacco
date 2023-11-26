<?php

/**
 * This is the model class for table "collateral".
 *
 * The followings are the available columns in table 'collateral':
 * @property integer $collateral_id
 * @property integer $collateraltype_id
 * @property integer $loanaccount_id
 * @property string $name
 * @property string $model
 * @property string $serial_number
 * @property string $photo
 * @property string $market_value
 * @property string $status
 * @property integer $uploaded_by
 * @property string $uploaded_at
 */
class Collateral extends CActiveRecord
{
	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'collateral';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('collateraltype_id, loanaccount_id, name, model,serial_number, market_value, uploaded_by,user_id,branch_id', 'required'),
			array('collateraltype_id, loanaccount_id, uploaded_by,user_id,branch_id', 'numerical', 'integerOnly'=>true),
			array('name, model, photo', 'length', 'max'=>512),
			array('serial_number', 'length', 'max'=>25),
			array('market_value', 'length', 'max'=>15),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('collateral_id, collateraltype_id, loanaccount_id, name, model, serial_number, market_value, status, uploaded_by,uploaded_at,user_id,branch_id,startDate,endDate', 'safe', 'on'=>'search'),
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
			'collateral_id' => 'Collateral',
			'collateraltype_id' => 'Collateraltype',
			'loanaccount_id' => 'Loanaccount',
			'user_id' => 'Relation Manager',
			'branch_id' => 'Branch',
			'name' => 'Name',
			'model' => 'Model',
			'serial_number' => 'Serial Number',
			'photo' => 'Photo',
			'market_value' => 'Market Value',
			'status' => 'Status',
			'uploaded_by' => 'Uploaded By',
			'uploaded_at' => 'Uploaded At',
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

		$criteria->compare('collateral_id',$this->collateral_id);
		$criteria->compare('collateraltype_id',$this->collateraltype_id);
		$criteria->compare('loanaccount_id',$this->loanaccount_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('serial_number',$this->serial_number,true);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('market_value',$this->market_value,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('uploaded_by',$this->uploaded_by);
		$criteria->compare('uploaded_at',$this->uploaded_at,true);

		if(isset($this->startDate) && isset($this->endDate)){
				$criteria->addBetweenCondition("DATE($alias.uploaded_at)",$this->startDate, $this->endDate, 'AND');
		}

		/*Additional Conditions*/
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case'1':
			$criteria->addCondition('branch_id ='.$userBranch);
			break;

			case'2':
			$criteria->addCondition('user_id ='.$userID);
			break;
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'collateral_id DESC',
			),
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}

	public function getStaffList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getCollateralBranchName(){
		$branch = Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ? $branch->name : "UNDEFINED";
	}

	public function getCollateralStaffName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getCollateralTypeList(){
		$typeQuery="SELECT * FROM collateraltypes";
		return CHtml::listData(Collateraltypes::model()->findAllBySql($typeQuery),'collateralType_id','name');
	}

	public function getLoanAcountNumbersList(){
		$accountsQuery="SELECT * FROM loanaccounts WHERE loan_status <>'3'";
		return CHtml::listData(Loanaccounts::model()->findAllBySql($accountsQuery),'loanaccount_id','AccountDetails');
	}

	public function getCollateralTypeName(){
		$collateralType=Collateraltypes::model()->findByPk($this->collateraltype_id);
		return !empty($collateralType) ? $collateralType->name : "UNDEFINED";
	}

	public function getCollateralModel(){
		return $this->model;
	}

	public function getCollateralSerialNumber(){
		return $this->serial_number;
	}

	public function getCollateralMarketValue(){
		return CommonFunctions::asMoney($this->market_value);
	}

	public function getCollateralOriginalMarketValue(){
		return $this->market_value;
	}

	public function getCollateralOriginalLoanAmount(){
		$loanaccount=Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loanaccount) ? $loanaccount->NotFormattedExactAmountDisbursed : 0;
	}

	public function getFormattedCollateralOriginalLoanAmount(){
		$loanaccount=Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loanaccount) ? CommonFunctions::asMoney($loanaccount->NotFormattedExactAmountDisbursed) : 0;
	}

	public function getCollateralLoanCurrentBalance(){
		$loanaccount=Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loanaccount) ? LoanManager::getActualLoanBalance($loanaccount->loanaccount_id) : 0;
	}

	public function getFormattedCollateralLoanCurrentBalance(){
		return CommonFunctions::asMoney($this->CollateralLoanCurrentBalance);
	}

	public function getCollateralLoanToValueRatio(){
		$loanaccount=Loanaccounts::model()->findByPk($this->loanaccount_id);
		if(!empty($loanaccount)){
			$loanAmount=$loanaccount->amount_approved;
			$collateralValue=$this->market_value;
			$loanvalueratio=($loanAmount/$collateralValue) * 100;
			$loanToValueRatio=round($loanvalueratio,2) .' %';
			return $loanToValueRatio;
		}else{
			$loanToValueRatio=0.00 .' %';
			return $loanToValueRatio;
		}
	}

	public function getCollateralCurrentStatus(){
		switch($this->status){
			case 0:
			$collateralStatus="Deposited Into Branch";
			break;

			case 1:
			$collateralStatus="Collateral With Member";
			break;

			case 2:
			$collateralStatus="Returned To Member";
			break;

			case 3:
			$collateralStatus="Repossesion Initiated";
			break;

			case 4:
			$collateralStatus="Repossesed";
			break;

			case 5:
			$collateralStatus="Under Auction";
			break;

			case 6:
			$collateralStatus="Sold";
			break;

			case 7:
			$collateralStatus="Lost";
			break;
		}
		return strtoupper($collateralStatus);
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(142) == 1){
			$delete_link="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('collateral/delete/'.$this->collateral_id)."\")'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_link="";
		}

		if(Navigation::checkIfAuthorized(140) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('collateral/update/'.$this->collateral_id)."\")'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link="";
		}
		$action_links="$update_link&nbsp;$delete_link";
		
		echo $action_links;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Collateral the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

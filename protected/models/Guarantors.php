<?php

/**
 * This is the model class for table "guarantors".
 *
 * The followings are the available columns in table 'guarantors':
 * @property integer $guarantor_id
 * @property integer $loanaccount_id
 * @property integer $user_id
 * @property integer $branch_id
 * @property integer $rm
 * @property string $name
 * @property string $id_number
 * @property string $phone
 * @property integer $created_by
 * @property string $created_at
 */
class Guarantors extends CActiveRecord{

	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'guarantors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		return array(
			array('loanaccount_id, name, id_number, phone', 'required'),
			array('loanaccount_id, user_id, branch_id, rm, created_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>512),
			array('id_number', 'length', 'max'=>25),
			array('phone', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('guarantor_id, loanaccount_id, user_id, branch_id, rm, name, id_number, phone, created_by,startDate,endDate, created_at', 'safe', 'on'=>'search'),
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
			'guarantor_id' => 'Guarantor',
			'loanaccount_id' => 'Loanaccount',
			'user_id' => 'User',
			'branch_id' => 'Branch',
			'rm' => 'Rm',
			'name' => 'Name',
			'id_number' => 'Id Number',
			'phone' => 'Phone',
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

		$criteria=new CDbCriteria;

		$criteria->compare('guarantor_id',$this->guarantor_id);
		$criteria->compare('loanaccount_id',$this->loanaccount_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('rm',$this->rm);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('id_number',$this->id_number,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		if(isset($this->startDate) && isset($this->endDate)){
				$criteria->addBetweenCondition("DATE($alias.created_at)",$this->startDate, $this->endDate, 'AND');
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
         'defaultOrder'=>'guarantor_id DESC',
      ),
			'pagination'=>array(
         'pageSize'=>30
       ),
		));
	}

	public function getRelationshipManagers(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getRelationManager(){
		$profile = Profiles::model()->findByPk($this->rm);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getUsersList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('ALL'),'id','ProfileNameWithIdNumber');
	}

	public function getAccountHolder(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getSaccoBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getBranchName(){
		$branch=Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ? $branch->name : 'UNDEFINED';
	}

	public function getLoanAcountNumbersList(){
		$loanQuery="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','4','8','9','10')";
		return CHtml::listData(Loanaccounts::model()->findAllBySql($loanQuery),'loanaccount_id','AccountDetails');
	}

	public function getLoanAccountNumber(){
		$loan=Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($loan) ? $loan->account_number : "UNDEFINED";
	}

	public function getGuarantorName(){
		return ucwords($this->name);
	}

	public function getGuarantorIDNumber(){
		return $this->id_number;
	}

	public function getGuarantorPhoneNumber(){
		return $this->phone;
	}

	public function getNotificationLink(){
		echo Navigation::checkIfAuthorized(284) == 1 ? "<a href='#' class='btn btn-danger btn-xs' title='Notify Guarantor' onclick='Authenticate(\"".Yii::app()->createUrl('guarantors/notify/'.$this->guarantor_id)."\")'><i class='now-ui-icons ui-1_send'></i></a>"
		:"";
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(108) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' title='Update Guarantor' onclick='Authenticate(\"".Yii::app()->createUrl('guarantors/update/'.$this->guarantor_id)."\")'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link="";
		}

		if(Navigation::checkIfAuthorized(110) == 1){
			$delete_link="<a href='#' class='btn btn-primary btn-sm' title='Delete Guarantor' onclick='Authenticate(\"".Yii::app()->createUrl('guarantors/delete/'.$this->guarantor_id)."\")'><i class='fa fa-remove'></i></a>";
		}else{
			$delete_link="";
		}
		$action_links="$update_link&nbsp;$delete_link";
		echo $action_links;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Guarantors the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

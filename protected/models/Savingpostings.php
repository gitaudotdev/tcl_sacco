<?php

/**
 * This is the model class for table "savingpostings".
 *
 * The followings are the available columns in table 'savingpostings':
 * @property integer $id
 * @property integer $savingtransaction_id
 * @property string $posted_interest
 * @property string $posted_at
 */
class Savingpostings extends CActiveRecord{

	public $savingAccountID,$description,$startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'savingpostings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('savingtransaction_id, posted_interest', 'required'),
			array('savingtransaction_id', 'numerical', 'integerOnly'=>true),
			array('posted_interest', 'length', 'max'=>15),
			array('is_withdrawn,is_void', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('savingtransaction_id,posted_interest,posted_at,savingAccountID,description,is_void,startDate,
				endDate,type','safe','on'=>'search'),
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
			'savingtransaction_id' => 'Savingtransaction',
			'posted_interest' => 'Posted Interest',
			'is_withdrawn'=>'Interest Withdrawn',
			'is_void'=>'Interest Voided',
			'type'=>'Transaction Type',
			'posted_at' => 'Posted At',
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
		$criteria->compare('savingtransaction_id',$this->savingtransaction_id);
		$criteria->compare('posted_interest',$this->posted_interest,true);
		$criteria->compare('posted_at',$this->posted_at,true);
		$criteria->compare('savingAccountID',$this->savingAccountID,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('is_void',$this->is_void,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare("$alias.is_withdrawn",'0',true);
		
		if(isset($this->startDate) && isset($this->endDate)){
				$criteria->addBetweenCondition("DATE($alias.posted_at)",$this->startDate, $this->endDate, 'AND');
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
         'defaultOrder'=>'id DESC',
      ),
			'pagination'=>array(
         'pageSize'=>10,
       ),
		));
	}

	public function getPostingAccountNumbersList(){
		$accountQuer = "SELECT * from savingaccounts WHERE is_approved='1'";
		return CHtml::listData(Savingaccounts::model()->findAllBySql($accountQuer),'savingaccount_id','AccountDetails');
	}

	public function getPostingAccountHolderName(){
		$transaction=Savingtransactions::model()->findByPk($this->savingtransaction_id);
		return $transaction->SavingAccountHolderName;
	}

	public function getPostingAccountNumber(){
		$transaction=Savingtransactions::model()->findByPk($this->savingtransaction_id);
		return $transaction->SavingAccountNumber;
	}

	public function getPostingAmount(){
		return CommonFunctions::asMoney($this->posted_interest);
	}

	public function getPostingBranch(){
		$transaction=Savingtransactions::model()->findByPk($this->savingtransaction_id);
		return $transaction->SavingAccountBranch;
	}

	public function getPostingRelationManager(){
		$transaction=Savingtransactions::model()->findByPk($this->savingtransaction_id);
		return $transaction->SavingAccountRelationManager;
	}

	public function getPostingDate(){
		$transaction=Savingtransactions::model()->findByPk($this->savingtransaction_id);
		return $transaction->SavingTransactionDate;
	}
	
	public function getPostingStatus(){
		if($this->is_void === '0'){
			$postStatus="<span style='color:green'>Active</span>";
		}else{
			$postStatus="<span style='color:red'>Voided</span>";
		}
		echo strtoupper($postStatus);
	}

	public function getAction(){
		switch($this->is_void){
			case '0':
			if(Navigation::checkIfAuthorized(191) == 1){
				$delete_link="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('savingpostings/void/'.$this->id)."\")' title='Void Saving Interest Posting'><i class='fa fa-trash'></i></a>";
			}else{
				$delete_link="";
			}

			if(Navigation::checkIfAuthorized(189) == 1){
				$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('savingpostings/update/'.$this->id)."\")' title='Update Saving Interest Posting'><i class='fa fa-edit'></i></a>";
			}else{
				$update_link="";
			}
			$action_links="$update_link&nbsp;$delete_link";
			break;

			case '1':
			$action_links="";
			break;
		}
		echo $action_links;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Savingpostings the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

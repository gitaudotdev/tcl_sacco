<?php
/**
 * This is the model class for table "branch".
 *
 * The followings are the available columns in table 'branch':
 * @property integer $branch_id
 * @property string $name
 * @property integer $created_by
 * @property string $created_at
 */
class Branch extends CActiveRecord{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'branch';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		return array(
			array('name, created_by', 'required'),
			array('created_by', 'numerical', 'integerOnly'=>true),
			array('is_merged', 'length', 'max'=>1),
			array('name,branch_town', 'length', 'max'=>512),
			array('sales_target', 'length', 'max'=>15),
			array('collections_target, processing_fee, interest_rate, insurance_rate, savings_interest_rate, loan_limit', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('branch_id, name, is_merged,created_by, created_at,sales_target,collections_target,processing_fee, interest_rate, insurance_rate, savings_interest_rate,loan_limit,branch_town', 'safe', 'on'=>'search'),
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
			'branch_id' => 'Branch',
			'branch_town' => 'Town',
			'name' => 'Name',
			'is_merged'=>'Branch Merged',
			'sales_target'=>'Branch Sales Target',
			'collections_target'=>'Branch Collections Target',
            'insurance_rate' => 'Insurance',
            'interest_rate' => 'Loan Interest Rate',
            'savings_interest_rate' => 'Savings Interest',
            'processing_fee' => 'Processing Fee',
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

		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('branch_town',$this->branch_town,true);
		$criteria->compare('is_merged',$this->is_merged,true);
		$criteria->compare('sales_target',$this->sales_target,true);
		$criteria->compare('collections_target',$this->collections_target,true);
		$criteria->compare('insurance_rate',$this->insurance_rate,true);
		$criteria->compare('interest_rate',$this->interest_rate,true);
		$criteria->compare('savings_interest_rate',$this->savings_interest_rate,true);
		$criteria->compare('processing_fee',$this->processing_fee,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare("$alias.is_merged",'0',true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'branch_id DESC',
			),
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}

	public function getBranchCode(){
		$branchCode="TCL";
		if($this->branch_id < 10){
			$branchCode.='00'.$this->branch_id;
		}else if($this->branch_id >= 10 && $this->branch_id < 100){
			$branchCode.='0'.$this->branch_id;
		}else{
			$branchCode.=''.$this->branch_id;
		}
		return $branchCode;
	}

	public function getBranchTown(){
		return $this->branch_town;
	}

	public function getSalesTarget(){
		return number_format($this->sales_target,2);
	}

	public function getCollectionsTarget(){
		return number_format($this->collections_target,2);
	}

    public function getLoanLimit(){
		return number_format($this->loan_limit,2);
	}

    public function getInsuranceRate(){
        return number_format($this->insurance_rate,2);
    }

    public function getInterestRate(){
        return number_format($this->interest_rate,2);
    }

    public function getSavingsInterestRate(){
        return number_format($this->savings_interest_rate,2);
    }

    public function getProcessingFee(){
        return number_format($this->processing_fee,2);
    }

	public function getCreatedByFullName(){
		$profile = Profiles::model()->findByPk($this->created_by);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getCreatedByDateFormatted(){
		return date('jS F Y',strtotime($this->created_at));
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(3) == 1){
			$delete_link="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('branch/delete/'.$this->branch_id)."\")' title='Delete Branch'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_link="";
		}

		if(Navigation::checkIfAuthorized(2) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('branch/update/'.$this->branch_id)."\")' title='Update Branch'><i class='fa fa-edit'></i></a>";
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
	 * @return Branch the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

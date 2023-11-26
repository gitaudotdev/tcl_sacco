<?php
/**
 * This is the model class for table "assets".
 *
 * The followings are the available columns in table 'assets':
 * @property integer $asset_id
 * @property integer $asset_type_id
 * @property string $asset_name
 * @property string $purchase_date
 * @property string $purchase_price
 * @property string $replacement_value
 * @property string $serial_number
 * @property string $description
 * @property string $attachment
 * @property integer $created_by
 * @property string $created_at
 */
class Assets extends CActiveRecord{

	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'assets';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('asset_type_id, asset_name, purchase_date, serial_number, created_by,user_id,branch_id', 'required'),
			array('asset_type_id, created_by,user_id,branch_id', 'numerical', 'integerOnly'=>true),
			array('asset_name, serial_number, description', 'length', 'max'=>150),
			array('purchase_price, replacement_value', 'length', 'max'=>15),
			array('attachment', 'length', 'max'=>512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('asset_id, asset_type_id, asset_name, purchase_date, purchase_price, replacement_value, serial_number, description,attachment,created_by,created_at,user_id,branch_id,startDate,endDate,status', 'safe', 'on'=>'search'),
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
	public function attributeLabels()
	{
		return array(
			'asset_id' => 'Asset',
			'asset_type_id' => 'Asset Type',
			'user_id' => 'Staff Member',
			'branch_id' => 'Branch',
			'asset_name' => 'Asset Name',
			'status' => 'Asset Status',
			'purchase_date' => 'Purchase Date',
			'purchase_price' => 'Purchase Price',
			'replacement_value' => 'Replacement Value',
			'serial_number' => 'Serial Number',
			'description' => 'Description',
			'attachment' => 'Attachment',
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

		$criteria->compare('asset_id',$this->asset_id);
		$criteria->compare('asset_type_id',$this->asset_type_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('asset_name',$this->asset_name,true);
		$criteria->compare('purchase_date',$this->purchase_date,true);
		$criteria->compare('purchase_price',$this->purchase_price,true);
		$criteria->compare('replacement_value',$this->replacement_value,true);
		$criteria->compare('serial_number',$this->serial_number,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('attachment',$this->attachment,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);
		
		if(isset($this->startDate) && isset($this->endDate)){
				$criteria->addBetweenCondition("DATE($alias.purchase_date)",$this->startDate, $this->endDate, 'AND');
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
				'defaultOrder'=>'asset_id DESC',
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

	public function getAssetBranchName(){
		$branch=Branch::model()->findByPk($this->branch_id);
		return !empty($branch) ? $branch->name : "UNDEFINED";
	}

	public function getAssetStaffName(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getAssetTypeList(){
		return CHtml::listData(AssetType::model()->findAll(),'asset_type_id','name');
	}

	public function getAssetName(){
		return ucfirst($this->asset_name);
	}

	public function getAssetTypeName(){
		$type = AssetType::model()->findByPk($this->asset_type_id);
		return !empty($type) ? $type->name : "UNDEFINED";
	}

	public function getAssetSerialNumber(){
		return $this->serial_number;
	}

	public function getAssetPurchasePrice(){
		return "Ksh. ".CommonFunctions::asMoney($this->purchase_price);
	}

	public function getAssetReplacementValue(){
		return "Ksh. ".CommonFunctions::asMoney($this->replacement_value);
	}

	public function getAssetStatus(){
		return ucwords($this->status);
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(146) == 1){
			$delete_link="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('assets/delete/'.$this->asset_id)."\")' title='Delete Asset'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_link="";
		}

		if(Navigation::checkIfAuthorized(144) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('assets/update/'.$this->asset_id)."\")' title='Update Asset'><i class='fa fa-edit'></i></a>";
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
	 * @return Assets the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

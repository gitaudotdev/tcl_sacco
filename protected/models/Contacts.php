<?php

/**
 * This is the model class for table "contacts".
 *
 * The followings are the available columns in table 'contacts':
 * @property integer $id
 * @property integer $profileId
 * @property string $contactType
 * @property integer $isPrimary
 * @property string $contactValue
 * @property integer $isVerified
 * @property string $createdAt
 * @property integer $createdBY
 */
class Contacts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'contacts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('profileId, contactValue', 'required'),
			array('profileId, isPrimary, isVerified, createdBY', 'numerical', 'integerOnly'=>true),
			array('contactType', 'length', 'max'=>5),
			array('contactValue', 'length', 'max'=>35),
			array('createdAt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, profileId, contactType, isPrimary, contactValue, isVerified, createdAt, createdBY', 'safe', 'on'=>'search'),
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
			'profileId' => 'Profile',
			'contactType' => 'Contact Type',
			'isPrimary' => 'Is Primary',
			'contactValue' => 'Contact Value',
			'isVerified' => 'Is Verified',
			'createdAt' => 'Created At',
			'createdBY' => 'Created By',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('profileId',$this->profileId);
		$criteria->compare('contactType',$this->contactType,true);
		$criteria->compare('isPrimary',$this->isPrimary);
		$criteria->compare('contactValue',$this->contactValue,true);
		$criteria->compare('isVerified',$this->isVerified);
		$criteria->compare('createdAt',$this->createdAt,true);
		$criteria->compare('createdBY',$this->createdBY);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getContactValueFormatted(){
		return $this->contactType == 'PHONE' ? '0'.substr($this->contactValue,-9) : $this->contactValue;
	}

	public function getContactPrimaryStatus(){
		return $this->isPrimary==0 ? "<span class='badge badge-danger'>SECONDARY</span>" : "<span class='badge badge-success'>PRIMARY</span>";
	}

	public function getContactVerificationStatus(){
		return $this->isVerified==0 ? "<span class='badge badge-danger'>UNVERIFIED</span>" : "<span class='badge badge-success'>VERIFIED</span>";
	}

	public function getAction(){

		if(Navigation::checkIfAuthorized(16) == 1){
			$updateLink="<a href='#' class='btn btn-warning btn-sm' title='Update Contact Details' onclick='Authenticate(\"".Yii::app()->createUrl('contacts/update/'.$this->id)."\")'><i class='fa fa-edit'></i></a>";
		}else{
			$updateLink="";
		}

		if(Navigation::checkIfAuthorized(18) == 1){
			if($this->isPrimary == 0){
				$primaryLink="<a href='#' class='btn btn-success btn-sm' title='Make Contact Primary' onclick='Authenticate(\"".Yii::app()->createUrl('contacts/makePrimary/'.$this->id)."\")'><i class='fa fa-check'></i></a>";
			}else{
				$primaryLink="";
			}
		}else{
			$primaryLink="";
		}
		$actionLink=$updateLink."&emsp;".$primaryLink;
		echo $actionLink;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Contacts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "account_settings".
 *
 * The followings are the available columns in table 'account_settings':
 * @property integer $id
 * @property integer $profileId
 * @property string $configType
 * @property string $configValue
 * @property integer $createdBy
 * @property string $createdAt
 */
class AccountSettings extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'account_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('profileId, configValue', 'required'),
			array('profileId, createdBy', 'numerical', 'integerOnly'=>true),
			array('configType,configValue', 'length', 'max'=>255),
			array('createdAt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, profileId, configType,configValue, configActive,createdBy, createdAt', 'safe', 'on'=>'search'),
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
			'profileId' => 'Profile',
			'configType' => 'Config Type',
			'configValue' => 'Config Value',
			'createdBy' => 'Created By',
			'createdAt' => 'Created At',
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
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('profileId',$this->profileId);
		$criteria->compare('configType',$this->configType,true);
		$criteria->compare('configValue',$this->configValue,true);
		$criteria->compare('configActive',$this->configActive);
		$criteria->compare('createdBy',$this->createdBy);
		$criteria->compare('createdAt',$this->createdAt,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getAccountConfigType(){
		return $this->configType;
	}

	public function getAccountConfigValue(){
		return $this->configValue;
	}

	public function getAccountConfigStatus(){
		return $this->configActive =='DISABLED' ? "<span class='badge badge-danger'> PREVIOUS</span>" : "<span class='badge badge-success'> CURRENT</span>";
	}

	public function getAccountConfigDate(){
		return date('jS M Y',strtotime($this->createdAt));
	}

	public function getAction(){
		echo Navigation::checkIfAuthorized(16) == 1 ? "<a href='#' class='btn btn-warning' title='Update Setting'
		onclick='Authenticate(\"".Yii::app()->createUrl('accountSettings/update/'.$this->id)."\")'><i class='fa fa-edit'>&nbsp;UPDATE</i></a>" : "";
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccountSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

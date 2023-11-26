<?php

/**
 * This is the model class for table "employments".
 *
 * The followings are the available columns in table 'employments':
 * @property integer $id
 * @property integer $profileId
 * @property string $industryType
 * @property string $employer
 * @property string $salaryBand
 * @property string $landMark
 * @property string $town
 * @property string $dateEmployed
 * @property string $contactPhone
 * @property integer $isCurrent
 * @property string $createdAt
 * @property integer $createdBy
 */
class Employments extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'employments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('profileId, employer', 'required'),
			array('profileId, isCurrent, createdBy', 'numerical', 'integerOnly'=>true),
			array('industryType', 'length', 'max'=>3),
			array('employer, landMark, town', 'length', 'max'=>255),
			array('salaryBand', 'length', 'max'=>15),
			array('contactPhone', 'length', 'max'=>25),
			array('dateEmployed, createdAt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, profileId, industryType, employer, salaryBand, landMark, town, dateEmployed, contactPhone, isCurrent, createdAt, createdBy', 'safe', 'on'=>'search'),
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
			'industryType' => 'Industry Type',
			'employer' => 'Employer',
			'salaryBand' => 'Salary Band',
			'landMark' => 'Land Mark',
			'town' => 'Town',
			'dateEmployed' => 'Date Employed',
			'contactPhone' => 'Contact Phone',
			'isCurrent' => 'Is Current',
			'createdAt' => 'Created At',
			'createdBy' => 'Created By',
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
		$criteria->compare('profileId',$this->profileId);
		$criteria->compare('industryType',$this->industryType,true);
		$criteria->compare('employer',$this->employer,true);
		$criteria->compare('salaryBand',$this->salaryBand,true);
		$criteria->compare('landMark',$this->landMark,true);
		$criteria->compare('town',$this->town,true);
		$criteria->compare('dateEmployed',$this->dateEmployed,true);
		$criteria->compare('contactPhone',$this->contactPhone,true);
		$criteria->compare('isCurrent',$this->isCurrent);
		$criteria->compare('createdAt',$this->createdAt,true);
		$criteria->compare('createdBy',$this->createdBy);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getEmploymentEmployer(){
		return strtoupper($this->employer);
	}

	public function getEmploymentIndustryType(){
		switch($this->industryType){
			case '001':
			$industryTypeName="Agriculture";
			break;

			case '002':
			$industryTypeName="Manufacturing";
			break;

			case '003':
			$industryTypeName="Building/ Construction";
			break;

			case '004':
			$industryTypeName="Mining/ Quarrying";
			break;

			case '005':
			$industryTypeName="Energy/ Water";
			break;

			case '006':
			$industryTypeName="Trade";
			break;

			case '007':
			$industryTypeName="Tourism/ Restaurant/ Hotels";
			break;

			case '008':
			$industryTypeName="Transport/ Communications";
			break;

			case '009':
			$industryTypeName="Real Estate";
			break;

			case '010':
			$industryTypeName="Financial Services";
			break;

			case '011':
			$industryTypeName="Government";
			break;
		}
		return strtoupper($industryTypeName);
	}

	public function getEmploymentTown(){
		return strtoupper($this->town);
	}

	public function getEmploymentLandMark(){
		return strtoupper($this->landMark);
	}

	public function getEmploymentDate(){
		return date('jS M Y',strtotime($this->dateEmployed));
	}

	public function getEmploymentCurrentStatus(){
		return $this->isCurrent==1 ? "<span class='badge badge-success'>CURRENT</span>" : "<span class='badge badge-danger'>PAST</span>";
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(16) == 1){
			$updateLink="<a href='#' class='btn btn-warning btn-sm' title='Update Employment Details' onclick='Authenticate(\"".Yii::app()->createUrl('employments/update/'.$this->id)."\")'><i class='fa fa-edit'>&nbsp;UPDATE</i></a>";
		}else{
			$updateLink="";
		}
		echo $updateLink;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Employments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

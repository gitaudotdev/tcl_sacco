<?php

/**
 * This is the model class for table "residences".
 *
 * The followings are the available columns in table 'residences':
 * @property integer $id
 * @property integer $profileId
 * @property string $residence
 * @property string $landMark
 * @property string $town
 * @property integer $isCurrent
 * @property string $createdAt
 * @property integer $createdBy
 */
class Residences extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'residences';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('profileId, residence, landMark, town', 'required'),
			array('profileId, isCurrent, createdBy', 'numerical', 'integerOnly'=>true),
			array('residence, landMark', 'length', 'max'=>1024),
			array('town', 'length', 'max'=>255),
			array('createdAt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, profileId, residence, landMark, town, isCurrent, createdAt, createdBy', 'safe', 'on'=>'search'),
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
			'residence' => 'Residence',
			'landMark' => 'Land Mark',
			'town' => 'Town',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('profileId',$this->profileId);
		$criteria->compare('residence',$this->residence,true);
		$criteria->compare('landMark',$this->landMark,true);
		$criteria->compare('town',$this->town,true);
		$criteria->compare('isCurrent',$this->isCurrent);
		$criteria->compare('createdAt',$this->createdAt,true);
		$criteria->compare('createdBy',$this->createdBy);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getResidenceCurrentStatus(){
		return $this->isCurrent==1 ? "<span class='badge badge-success'>CURRENT</span>" : "<span class='badge badge-danger'>PAST</span>";
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(16) == 1){
			$updateLink="<a href='#' class='btn btn-warning btn-sm' title='Update Details' onclick='Authenticate(\"".Yii::app()->createUrl('residences/update/'.$this->id)."\")'><i class='fa fa-edit'>&nbsp;UPDATE</i></a>";
		}else{
			$updateLink="";
		}
		echo $updateLink;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Residences the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

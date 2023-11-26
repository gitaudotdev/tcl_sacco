<?php

/**
 * This is the model class for table "dependencies".
 *
 * The followings are the available columns in table 'dependencies':
 * @property integer $id
 * @property integer $profileId
 * @property string $relationType
 * @property string $firstName
 * @property string $lastName
 * @property string $relation
 * @property string $phoneNumber
 * @property string $createdAt
 * @property integer $createdBy
 */
class Dependencies extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dependencies';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('profileId, firstName, lastName', 'required'),
			array('profileId, createdBy', 'numerical', 'integerOnly'=>true),
			array('relationType', 'length', 'max'=>9),
			array('firstName, lastName', 'length', 'max'=>255),
			array('relation', 'length', 'max'=>225),
			array('phoneNumber', 'length', 'max'=>25),
			array('createdAt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, profileId, relationType, firstName, lastName, relation, phoneNumber, createdAt, createdBy', 'safe', 'on'=>'search'),
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
			'relationType' => 'Relation Type',
			'firstName' => 'First Name',
			'lastName' => 'Last Name',
			'relation' => 'Relation',
			'phoneNumber' => 'Phone Number',
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
		$criteria->compare('relationType',$this->relationType,true);
		$criteria->compare('firstName',$this->firstName,true);
		$criteria->compare('lastName',$this->lastName,true);
		$criteria->compare('relation',$this->relation,true);
		$criteria->compare('phoneNumber',$this->phoneNumber,true);
		$criteria->compare('createdAt',$this->createdAt,true);
		$criteria->compare('createdBy',$this->createdBy);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(16) == 1){
			$updateLink="<a href='#' class='btn btn-warning btn-sm' title='Update Details' onclick='Authenticate(\"".Yii::app()->createUrl('dependencies/update/'.$this->id)."\")'><i class='fa fa-edit'>&nbsp;UPDATE</i></a>";
		}else{
			$updateLink="";
		}
		echo $updateLink;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Dependencies the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

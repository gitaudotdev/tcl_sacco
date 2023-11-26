<?php

/**
 * This is the model class for table "borrowerFiles".
 *
 * The followings are the available columns in table 'borrowerFiles':
 * @property integer $id
 * @property integer $user_id
 * @property string $id_card
 * @property string $passport
 * @property string $business
 * @property string $residence_landmark
 * @property integer $created_by
 * @property string $created_at
 */
class BorrowerFiles extends CActiveRecord{
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'borrowerFiles';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, id_card, passport, business, residence_landmark', 'required'),
			array('user_id, created_by', 'numerical', 'integerOnly'=>true),
			array('id_card, passport, business, residence_landmark', 'length', 'max'=>512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, id_card, passport, business, residence_landmark, created_by, created_at', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'user_id' => 'User',
			'id_card' => 'Upload ID Card Photo',
			'passport' => 'Upload Passport Photo',
			'business' => 'Upload Business/Office Photo',
			'residence_landmark' => 'Upload Residence Photo',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('id_card',$this->id_card,true);
		$criteria->compare('passport',$this->passport,true);
		$criteria->compare('business',$this->business,true);
		$criteria->compare('residence_landmark',$this->residence_landmark,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
         'pageSize'=>30
       ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BorrowerFiles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

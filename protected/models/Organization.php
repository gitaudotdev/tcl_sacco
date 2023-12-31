<?php

/**
 * This is the model class for table "organization".
 *
 * The followings are the available columns in table 'organization':
 * @property integer $organization_id
 * @property string $name
 * @property string $logo
 * @property string $email
 * @property string $website
 * @property string $address
 * @property string $phone
 * @property string $created_at
 */
class Organization extends CActiveRecord{
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'organization';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, email, website, address, phone', 'required'),
			array('name, logo, email, website, address', 'length', 'max'=>512),
			array('phone', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('organization_id, name, logo, email, website, address, phone, enable_mpesa_b2c, created_at,automated_payroll', 'safe', 'on'=>'search'),
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
			'organization_id' => 'Organization',
			'name' => 'Name',
			'logo' => 'Logo',
			'email' => 'Email',
			'website' => 'Website',
			'address' => 'Address',
			'phone' => 'Phone',
			'enable_mpesa_b2c'=>'M-PESA B2C Status',
			'automated_payroll'=>'Automate Payroll',
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
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;
		$criteria->compare('organization_id',$this->organization_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('automated_payroll',$this->automated_payroll,true);
		$criteria->compare('enable_mpesa_b2c',$this->enable_mpesa_b2c,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Organization the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "permissions".
 *
 * The followings are the available columns in table 'permissions':
 * @property integer $permission_id
 * @property string $name
 * @property string $display_name
 * @property string $category
 * @property integer $created_by
 * @property string $created_at
 */
class Permissions extends CActiveRecord{
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'permissions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, display_name', 'required'),
			array('created_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>150),
			array('display_name', 'length', 'max'=>255),
			array('category', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('permission_id, name, display_name, category, created_by, created_at', 'safe', 'on'=>'search'),
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
			'permission_id' => 'Permission',
			'name' => 'Name',
			'display_name' => 'Display Name',
			'category' => 'Category',
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
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;

		$criteria->compare('permission_id',$this->permission_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('display_name',$this->display_name,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30
			),
		));
	}

	public function getPermissionCategoryName(){
		switch($this->category){
			case '0':
			$categoryName='Branch';
			break;

			case '1':
			$categoryName='Members';
			break;

			case '2':
			$categoryName='Users';
			break;

			case '3':
			$categoryName='Staff';
			break;

			case '4':
			$categoryName='Loan Accounts';
			break;

			case '5':
			$categoryName='Saving Accounts';
			break;

			case '6':
			$categoryName='Saving Transactions';
			break;

			case '7':
			$categoryName='Loan Repayments';
			break;

			case '8':
			$categoryName='Stray Repayments';
			break;

			case '9':
			$categoryName='Share Holders';
			break;

			case '10':
			$categoryName='Roles';
			break;

			case '11':
			$categoryName='Reports';
			break;

			case '12':
			$categoryName='Next of Kin';
			break;

			case '13':
			$categoryName='Referee';
			break;

			case '14':
			$categoryName='Expenses';
			break;

			case '15':
			$categoryName='Incomes';
			break;

			case '16':
			$categoryName='Guarantors';
			break;

			case '17':
			$categoryName='HRM';
			break;

			case '18':
			$categoryName='Collateral';
			break;

			case '19':
			$categoryName='Assets';
			break;

			case '20':
			$categoryName='Airtime';
			break;

			case '21':
			$categoryName='Administration';
			break;

			case '22':
			$categoryName='Analytics';
			break;
		}
		return $categoryName;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

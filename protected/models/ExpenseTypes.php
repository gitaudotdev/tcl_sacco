<?php

/**
 * This is the model class for table "expense_types".
 *
 * The followings are the available columns in table 'expense_types':
 * @property integer $expensetype_id
 * @property string $name
 * @property integer $created_by
 * @property string $created_at
 */
class ExpenseTypes extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'expense_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, created_by', 'required'),
			array('created_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('expensetype_id, name, created_by, created_at', 'safe', 'on'=>'search'),
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
			'expensetype_id' => 'Expensetype',
			'name' => 'Name',
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

		$criteria->compare('expensetype_id',$this->expensetype_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
         'pageSize'=>30
       ),
		));
	}

	public function getExpenseTypeName(){
		return ucfirst($this->name);
	}

	public function getAction(){
		switch(Yii::app()->user->user_level){
			case '0':
			$delete_link="<a href='#' class='btn btn-primary btn-sm' title='Delete Type' onclick='Authenticate(\"".Yii::app()->createUrl('expenseTypes/delete/'.$this->expensetype_id)."\")'><i class='fa fa-trash'></i></a>";
			$update_link="<a href='#' class='btn btn-warning btn-sm' title='Update Type' onclick='Authenticate(\"".Yii::app()->createUrl('expenseTypes/update/'.$this->expensetype_id)."\")'><i class='fa fa-edit'></i></a>";
			$action_links="$update_link&nbsp;$delete_link";
			echo $action_links;
			break;

			case '1':
			$delete_link="<a href='#' class='btn btn-primary btn-sm' title='Delete Type' onclick='Authenticate(\"".Yii::app()->createUrl('expenseTypes/delete/'.$this->expensetype_id)."\")'><i class='fa fa-trash'></i></a>";
			$update_link="<a href='#' class='btn btn-warning btn-sm' title='Update Type' onclick='Authenticate(\"".Yii::app()->createUrl('expenseTypes/update/'.$this->expensetype_id)."\")'><i class='fa fa-edit'></i></a>";
			$action_links="$update_link&nbsp;$delete_link";
			echo $action_links;
			break;

			case '2':
			$action_links="";
			echo $action_links;
			break;

			case '3':
			$action_links="";
			echo $action_links;
			break;
		}
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExpenseTypes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "roles".
 *
 * The followings are the available columns in table 'roles':
 * @property integer $role_id
 * @property string $name
 * @property integer $created_by
 * @property string $created_at
 */
class Roles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'roles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('created_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>75),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('role_id, name, created_by, created_at', 'safe', 'on'=>'search'),
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
			'role_id' => 'Role',
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

		$criteria->compare('role_id',$this->role_id);
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

	public function getPermissionsActions(){
		if(Navigation::checkIfAuthorized(79) == 1){
			$assign_link="<a href='#' class='btn btn-success btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('roles/assignPermissions/'.$this->role_id)."\")' title='Assign Permissions'><i class='fa fa-edit'></i></a>";
		}else{
			$assign_link="";
		}
		if(Navigation::checkIfAuthorized(76) == 1){
			$view_permissions_link="<a href='#' class='btn btn-info btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('roles/permissions/'.$this->role_id)."\")' title='View Permissions'><i class='fa fa-table'></i></a>";
		}else{
			$view_permissions_link="";
		}
		$action_links="$view_permissions_link&nbsp;$assign_link";
		echo $action_links;
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(78) == 1){
			$delete_link="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('roles/delete/'.$this->role_id)."\")' title='Delete Role'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_link="";
		}

		if(Navigation::checkIfAuthorized(75) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('roles/update/'.$this->role_id)."\")' title='Update Role'><i class='fa fa-edit'></i></a>";
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
	 * @return Roles the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "borrowergroup".
 *
 * The followings are the available columns in table 'borrowergroup':
 * @property integer $group_id
 * @property string $name
 * @property integer $group_leader
 * @property integer $collector_id
 * @property integer $created_by
 * @property string $created_at
 */
class Borrowergroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'borrowergroup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, group_leader, collector_id', 'required'),
			array('group_leader, collector_id, created_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>75),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('group_id, name, group_leader, collector_id, created_at', 'safe', 'on'=>'search'),
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
			'profiles'=>array(SELF::BELONGS_TO,'Profiles','created_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'group_id' => 'Group',
			'name' => 'Name',
			'group_leader' => 'Group Leader',
			'collector_id' => 'Collector',
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
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('group_leader',$this->group_leader);
		$criteria->compare('collector_id',$this->collector_id);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);
		/*Additional Conditions*/
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case'1':
			$criteria->with = array('profiles.groups');
			$criteria->condition='profiles.branchId='.$userBranch;
			break;

			case'2':
			$criteria->with = array('profiles.groups');
			$criteria->condition='profiles.managerId='.Yii::app()->user->user_id;
			break;

			case '3':
			$criteria->with = array('profiles.groups');
			$criteria->condition='profiles.id='.Yii::app()->user->user_id;
			break;
		}
		$criteria->order = "group_id DESC";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}

	public function getGroupLeaderName(){
		$profile = Profiles::model()->findByPk($this->group_leader);
		echo !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getGroupCollectorName(){
		$profile = Profiles::model()->findByPk($this->collector_id);
		echo !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getAction(){
		/*UPDATE*/
		if(Navigation::checkIfAuthorized(136) === 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('borrowergroup/update/'.$this->group_id)."\")'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link="";
		}
		/*DELETE*/
		if(Navigation::checkIfAuthorized(135) === 1){
			$delete_link="<a href='#' class='btn btn-primary btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('borrowergroup/delete/'.$this->group_id)."\")'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_link="";
		}

		$action_links="$update_link&nbsp;$delete_link";
		echo $action_links;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Borrowergroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

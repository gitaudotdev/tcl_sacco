<?php

/**
 * This is the model class for table "chama_locations".
 *
 * The followings are the available columns in table 'chama_locations':
 * @property integer $id
 * @property string $name
 * @property string $town
 * @property integer $created_by
 * @property string $created_at
 */
class ChamaLocations extends CActiveRecord{
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'chama_locations';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, town, created_by', 'required'),
			array('created_by', 'numerical', 'integerOnly'=>true),
			array('name, town', 'length', 'max'=>256),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, town, created_by, created_at', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'town' => 'Town',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('town',$this->town,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			),
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}

	public function getCreatedByFullName(){
		$profile = Profiles::model()->findByPk($this->created_by);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getCreatedByDateFormatted(){
		return date('jS F Y',strtotime($this->created_at));
	}

	public function getAction(){
		if(Navigation::checkIfAuthorized(291) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('chamaLocations/update/'.$this->id)."\")' title='Update Location'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link="";
		}
		$action_links="$update_link";
		
		echo $action_links;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ChamaLocations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "alert_configs".
 *
 * The followings are the available columns in table 'alert_configs':
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $is_active
 * @property integer $created_by
 * @property string $created_at
 */
class AlertConfigs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'alert_configs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, type, created_by', 'required'),
			array('created_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>512),
			array('type, is_active', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, type, is_active, created_by, created_at', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'is_active' => 'Is Active',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('is_active',$this->is_active,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'name ASC',
			),
			'pagination'=>array(
				'pageSize'=> Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}
	
	public function getAlertStatus(){
		echo $this->is_active =='0' ? "<span class='badge badge-primary'>DISABLED</span>" : "<span class='badge badge-info'>ACTIVE</span>";
	}

	public function getAlertName(){
		echo ucfirst($this->name);
	}

	public function getAlertDate(){
		return date('jS M Y',strtotime($this->created_at));
	}

	public function getAction(){
		echo $this->is_active == '0' ? "<a href='#' class='btn btn-success btn-sm' title='Activate Config' onclick='Authenticate(\"".Yii::app()->createUrl('alertConfigs/activate/'.$this->id)."\")'> <i class='fa fa-check'></i></a>"
		: "<a href='#' class='btn btn-danger btn-sm' title='Deactivate Config' onclick='Authenticate(\"".Yii::app()->createUrl('alertConfigs/disable/'.$this->id)."\")'> <i class='fa fa-trash'></i></a>";	
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AlertConfigs the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

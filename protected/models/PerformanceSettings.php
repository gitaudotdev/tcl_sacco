<?php

/**
 * This is the model class for table "performance_settings".
 *
 * The followings are the available columns in table 'performance_settings':
 * @property integer $id
 * @property string $name
 * @property integer $minimum
 * @property integer $maximum
 * @property string $colour
 * @property integer $created_by
 * @property string $created_at
 */
class PerformanceSettings extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'performance_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, minimum, maximum, colour,percent_multiplier', 'required'),
			array('minimum, maximum, created_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>75),
			array('colour', 'length', 'max'=>6),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, minimum, maximum, colour, created_by, created_at,percent_multiplier', 'safe', 'on'=>'search'),
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
			'minimum' => 'Minimum',
			'maximum' => 'Maximum',
			'percent_multiplier' => 'Multiplier',
			'colour' => 'Colour',
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
		$criteria->compare('minimum',$this->minimum);
		$criteria->compare('maximum',$this->maximum);
		$criteria->compare('maximum',$this->maximum);
		$criteria->compare('percent_multiplier',$this->percent_multiplier,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getPerformanceName(){
		return ucfirst($this->name);
	}

	public function getPerformanceMinimumValue(){
		return CommonFunctions::asMoney($this->minimum);
	}

	public function getPerformanceMaximumValue(){
		return CommonFunctions::asMoney($this->maximum);
	}

	public function getPerformanceColour(){
		return ucfirst($this->colour);
	}

	public function getPerformancePercentMultiplier(){
		return $this->percent_multiplier." %";
	}

	public function getAction(){
		/*Updating Setting*/
		if(Navigation::checkIfAuthorized(163) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('performanceSettings/update/'.$this->id)."\")' title='Update Setting'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link="";
		}
		$action_link="$update_link";
		echo $action_link;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PerformanceSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

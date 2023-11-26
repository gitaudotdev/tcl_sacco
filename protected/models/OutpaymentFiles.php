<?php

/**
 * This is the model class for table "outpayment_files".
 *
 * The followings are the available columns in table 'outpayment_files':
 * @property integer $id
 * @property integer $outpayment_id
 * @property string $name
 * @property string $filename
 * @property string $activity
 * @property integer $uploaded_by
 * @property string $uploaded_at
 */
class OutpaymentFiles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'outpayment_files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('outpayment_id, name, filename, activity', 'required'),
			array('outpayment_id, uploaded_by', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>75),
			array('filename', 'length', 'max'=>512),
			array('activity', 'length', 'max'=>125),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, outpayment_id, name, filename, activity, uploaded_by, uploaded_at', 'safe', 'on'=>'search'),
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
			'outpayment_id' => 'Outpayment',
			'name' => 'Name',
			'filename' => 'Filename',
			'activity' => 'Activity',
			'uploaded_by' => 'Uploaded By',
			'uploaded_at' => 'Uploaded At',
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
		$criteria->compare('outpayment_id',$this->outpayment_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('activity',$this->activity,true);
		$criteria->compare('uploaded_by',$this->uploaded_by);
		$criteria->compare('uploaded_at',$this->uploaded_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OutpaymentFiles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "imports".
 *
 * The followings are the available columns in table 'imports':
 * @property integer $id
 * @property string $filename
 * @property string $integrity_hash
 * @property integer $imported_by
 * @property string $imported_at
 */
class Imports extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'imports';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('filename, integrity_hash, imported_by', 'required'),
			array('imported_by', 'numerical', 'integerOnly'=>true),
			array('filename, integrity_hash', 'length', 'max'=>512),
			array('imported_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, filename, integrity_hash, imported_by, imported_at', 'safe', 'on'=>'search'),
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
			'filename' => 'Filename',
			'integrity_hash' => 'Integrity Hash',
			'imported_by' => 'Imported By',
			'imported_at' => 'Imported At',
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
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('integrity_hash',$this->integrity_hash,true);
		$criteria->compare('imported_by',$this->imported_by);
		$criteria->compare('imported_at',$this->imported_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Imports the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

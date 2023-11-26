<?php

/**
 * This is the model class for table "expense_files".
 *
 * The followings are the available columns in table 'expense_files':
 * @property integer $id
 * @property integer $expense_id
 * @property string $filename
 * @property integer $uploaded_by
 * @property string $uploaded_at
 *
 * The followings are the available model relations:
 * @property Expenses $expense
 */
class ExpenseFiles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'expense_files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('expense_id, filename, uploaded_by', 'required'),
			array('expense_id, uploaded_by', 'numerical', 'integerOnly'=>true),
			array('filename', 'length', 'max'=>512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, expense_id, filename, uploaded_by, uploaded_at', 'safe', 'on'=>'search'),
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
			'expense' => array(self::BELONGS_TO, 'Expenses', 'expense_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'expense_id' => 'Expense',
			'filename' => 'Filename',
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
		$criteria->compare('expense_id',$this->expense_id);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('uploaded_by',$this->uploaded_by);
		$criteria->compare('uploaded_at',$this->uploaded_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getUploadeBy(){
		$profile = Profiles::model()->findByPk($this->uploaded_by);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getDateUploaded(){
		return date('d/m/Y h:i A',strtotime($this->uploaded_at));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExpenseFiles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

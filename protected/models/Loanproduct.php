<?php

/**
 * This is the model class for table "loanproduct".
 *
 * The followings are the available columns in table 'loanproduct':
 * @property integer $id
 * @property string $loantype_id
 * @property string $name
 * @property float $interest_rate
 * @property string repayment_method
 * @property int disbursed_by
 * @property int created_by
 * @property string created_at
 */

class Loanproduct extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'loanproduct';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('loantype_id, name, interest_rate, repayment_method, disbursed_by, created_by, created_at', 'required'),
            array('interest_rate', 'numerical'),
            array('loantype_id, disbursed_by, created_by', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>512),
            array('repayment_method', 'length', 'max'=>256),
            array('created_at', 'safe'),
            array('id, loantype_id, name, interest_rate, repayment_method, disbursed_by, created_by, created_at', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
//            'loantype' => array(self::BELONGS_TO, 'Loantype', 'loantype_id'),
//            'loanaccounts' => array(self::HAS_MANY, 'Loanaccount', 'loanproduct_id'),
//            'createdby' => array(self::BELONGS_TO, 'Profile', 'created_by'),
//            'disbursedby' => array(self::BELONGS_TO, 'Profile', 'disbursed_by'),
//            'loanrepayments' => array(self::HAS_MANY, 'Loanrepayment', 'loanproduct_id'),
//            'loanfees' => array(self::HAS_MANY, 'Loanfee', 'loanproduct_id'),
//            'loanpenalties' => array(self::HAS_MANY, 'Loanpenalty', 'loanproduct_id'),

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
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('loantype_id',$this->loantype_id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('interest_rate',$this->interest_rate);
        $criteria->compare('repayment_method',$this->repayment_method,true);
        $criteria->compare('disbursed_by',$this->disbursed_by);
        $criteria->compare('created_by',$this->created_by);
        $criteria->compare('created_at',$this->created_at,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));

    }

    public function getLoanType(){
        $loantype = Loantype::model()->findByPk($this->loantype_id);
        return $loantype->name;
    }

    public function getDisbursedBy(){
        $profile = Profiles::model()->findByPk($this->disbursed_by);
        return $profile->getFullNames();
    }

    public static function model($classname=__CLASS__)
    {
        return parent::model($classname);
    }

}
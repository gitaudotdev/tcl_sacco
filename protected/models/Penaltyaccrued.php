<?php

/**
 * This is the model class for table "penaltyaccrued".
 *
 * The followings are the available columns in table 'penaltyaccrued':
 * @property integer $id
 * @property integer $loanaccount_id
 * @property string $date_defaulted
 * @property string $penalty_amount
 * @property string $is_paid
 * @property string $created_at
 */
class Penaltyaccrued extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'penaltyaccrued';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('loanaccount_id, date_defaulted', 'required'),
            array('loanaccount_id', 'numerical', 'integerOnly'=>true),
            array('penalty_amount', 'length', 'max'=>15),
            array('is_paid', 'length', 'max'=>1),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, loanaccount_id, date_defaulted, penalty_amount, is_paid, created_at', 'safe', 'on'=>'search'),
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
            'loanaccount_id' => 'Loanaccount',
            'date_defaulted' => 'Date Defaulted',
            'penalty_amount' => 'Penalty Amount',
            'is_paid' => 'Is Paid',
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
        $criteria->compare('loanaccount_id',$this->loanaccount_id);
        $criteria->compare('date_defaulted',$this->date_defaulted,true);
        $criteria->compare('penalty_amount',$this->penalty_amount,true);
        $criteria->compare('is_paid',$this->is_paid,true);
        $criteria->compare('created_at',$this->created_at,true);


        switch(Yii::app()->user->user_level){
            case '0':
                break;

            case '1':
                $criteria->addCondition('branch_id ='.Yii::app()->user->user_branch);
                break;

            case '2':
                $criteria->addCondition('rm ='.Yii::app()->user->user_id);
                break;

            default:
                $criteria->addCondition('user_id ='.Yii::app()->user->user_id);
                break;
        }


        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>30
            ),
        ));
    }

    public function getSaccoBranchList(){
        return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
    }

    public function getRelationshipManagers(){
        return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
    }

    public function getBorrowerList(){
        return CHtml::listData(ProfileEngine::getProfilesByType('ALL'),'id','ProfileNameWithIdNumber');
    }


    public function getLoanList(){
        $loanSQL="SELECT * FROM loanaccounts WHERE loanaccount_id IN(SELECT loanaccount_id FROM penaltyaccrued)";
        return CHtml::listData(Loanaccounts::model()->findAllBySql($loanSQL),'loanaccount_id','AccountDetails');
    }

    public function getClientName(){
        $loanaccount = Loanaccounts::model()->findByPk($this->loanaccount_id);
        if(!empty($loanaccount)){
            $user     = Profiles::model()->findByPk($loanaccount->user_id);
            $fullName = !empty($user) ? $user->ProfileFullName : "UNDEFINED";
        }else{
            $fullName = "UNDEFINED";
        }
        return $fullName;
    }

    public function getAccountNumber(){
        $loanaccount=Loanaccounts::model()->findByPk($this->loanaccount_id);
        return !empty($loanaccount) ? $loanaccount->account_number : "UNDEFINED";
    }

    public function getPenaltyAmount(){
        return number_format($this->penalty_amount);
    }

    public function getIsPaid(){
        return $this->is_paid == 1 ? "YES" : "NO";
    }

    public function getDefaultedDate(){
        return date('jS M Y',strtotime($this->date_defaulted));
    }

    public function getTransactionDate(){
        return date('jS M Y',strtotime($this->created_at));
    }



    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Penaltyaccrued the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}

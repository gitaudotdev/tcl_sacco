<?php

/**
 * This is the model class for table "notices".
 *
 * The followings are the available columns in table 'notices':
 * @property integer $id
 * @property string $message
 * @property string $level
 * @property string $is_active
 * @property integer $created_by
 * @property string $created_at
 */
class Notices extends CActiveRecord
{
	public $startDate,$endDate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'notices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message, created_by', 'required'),
			array('created_by', 'numerical', 'integerOnly'=>true),
			array('message', 'length', 'max'=>1200),
			array('level, is_active', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, message, level, is_active, created_by, created_at,startDate,endDate', 'safe', 'on'=>'search'),
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
			'message' => 'Notice Message',
			'level' => 'Notice Level',
			'is_active' => 'Is Active',
			'created_by' => 'Notice Author',
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
		$alias = $this->getTableAlias(false,false);
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('level',$this->level,true);
		$criteria->compare('is_active',$this->is_active,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE($alias.created_at)",$this->startDate, $this->endDate, 'AND');
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
         'defaultOrder'=>'id DESC',
      ),
			'pagination'=>array(
         'pageSize'=>15
       ),
		));
	}

	public function getNoticeContent(){
		echo '<div class="text-wrap width-200">'.$this->message.'</div>';
	}

	public function getNoticeAuthor(){
		return Profiles::model()->findByPk($this->created_by)->ProfileFullName;
	}

	public function getNoticeDate(){
		return date('jS M Y',strtotime($this->created_at));
	}

	public function getNoticeStatus(){
		echo $this->is_active=='0' ? "<span class='badge badge-primary'>Disabled Notice</span>" : "<span class='badge badge-info'>Active Notice</span>";
	}

	public function getNoticeLevel(){
		switch($this->level){
			case '0':
			$noticeLevel='All';
			break;

			case '1':
			$noticeLevel='Superadmin';
			break;

			case '2':
			$noticeLevel='Admin';
			break;

			case '3':
			$noticeLevel='Staff';
			break;

			case '4':
			$noticeLevel='Member';
			break;

			case '5':
			$noticeLevel='Shareholder';
			break;

			case '6':
			$noticeLevel='Regional';
			break;
		}
		return $noticeLevel;
	}

	public function getAction(){
		/*Updating Notices*/
		if(Navigation::checkIfAuthorized(130) == 1){
			$update_link="<a href='#' class='btn btn-warning btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('notices/update/'.$this->id)."\")' title='Update Notice'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link="";
		}
		/*Activating Notices*/
		if(Navigation::checkIfAuthorized(131) == 1){
			$activate_link="<a href='#' class='btn btn-success btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('notices/activate/'.$this->id)."\")' title='Activate Notice'><i class='fa fa-check'></i></a>";
		}else{
			$activate_link="";
		}
		/*Deactivating Notices*/
		if(Navigation::checkIfAuthorized(132) == 1){
			$deactivate_link="<a href='#' class='btn btn-info btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('notices/deactivate/'.$this->id)."\")' title='Deactivate Notice'><i class='fa fa-remove'></i></a>";
		}else{
			$deactivate_link="";
		}
		/*Delete Notices*/
		if(Navigation::checkIfAuthorized(167) == 1){
			$delete_link="<a href='#' class='btn btn-danger btn-sm' onclick='Authenticate(\"".Yii::app()->createUrl('notices/delete/'.$this->id)."\")' title='Delete Notice'><i class='fa fa-trash'></i></a>";
		}else{
			$delete_link="";
		}
		/*Activate/Deactivate*/
		if($this->is_active === '0'){
			$auth_link=$activate_link;
		}else{
			$auth_link=$deactivate_link;
		}
		/*Action Links*/
		$action_link="$update_link&nbsp;$auth_link&nbsp;$delete_link";
		echo $action_link;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notices the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

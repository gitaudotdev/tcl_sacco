<?php

class CommentTypes extends CActiveRecord{

	public $startDate, $endDate;
	
	public function tableName(){
		return 'comment_types';
	}
	

	public function rules(){
		return array(
			array('user_id, name', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('is_active', 'length', 'max'=>1),
			array('id, user_id, name, is_active, created_at,startDate,endDate', 'safe', 'on'=>'search'),
		);
	}

	
	public function relations(){
		return array(
		);
	}

	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'name' => 'Name',
			'is_active' => 'Is Active',
			'created_at' => 'Created At',
		);
	}

	public function search(){
		$alias = $this->getTableAlias(false,false);

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('is_active',$this->is_active,true);
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
         'pageSize'=>10
       ),
		));
	}

	public function getTypeName(){
		return ucfirst($this->name);
	}

	public function getTypeStatus(){
		return $this->is_active === '0' ? 'DISABLED' : 'ACTIVE';
	}

	public function getCreatedBy(){
		$profile = Profiles::model()->findByPk($this->user_id);
		return !empty($profile) ? $profile->ProfileFullName : "UNDEFINED";
	}

	public function getCreatedAt(){
		return date('jS M Y', strtotime($this->created_at));
	}

	public function getAction(){
		switch($this->is_active){
			case '0':
			if(Navigation::checkIfAuthorized(254) == 1){
				$auth_link = "<a href='#' class='btn btn-success btn-sm' title='Activate Type' onclick='Authenticate(\"".Yii::app()->createUrl('commentTypes/activate/'.$this->id)."\")'><i class='fa fa-check'></i></a>";
			}else{
				$auth_link = "";
			}
			break;

			case '1':
			if(Navigation::checkIfAuthorized(255) == 1){
				$auth_link = "<a href='#' class='btn btn-danger btn-sm' title='Deactivate Type' onclick='Authenticate(\"".Yii::app()->createUrl('commentTypes/deactivate/'.$this->id)."\")'><i class='fa fa-times'></i></a>";
			}else{
				$auth_link = "";
			}
			break;
		}

		if(Navigation::checkIfAuthorized(253) == 1){
			$update_link = "<a href='#' class='btn btn-warning btn-sm' title='Update Details' onclick='Authenticate(\"".Yii::app()->createUrl('commentTypes/update/'.$this->id)."\")'><i class='fa fa-edit'></i></a>";
		}else{
			$update_link = "";
		}

		$action_links=$update_link."&emsp;".$auth_link;
		echo $action_links;
	}

	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}
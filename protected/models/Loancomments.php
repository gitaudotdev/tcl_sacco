<?php

class Loancomments extends CActiveRecord{

	public $startDate,$endDate;
	
	public function tableName(){
		return 'loancomments';
	}

	public function rules(){
		return array(
			array('loanaccount_id, comment', 'required'),
			array('loanaccount_id, commented_by', 'numerical', 'integerOnly'=>true),
			array('comment', 'length', 'max'=>512),
			array('comment_id,loanaccount_id,comment,activity,commented_by,commented_at,
				type_id,rm,user_id,branch_id,startDate,endDate','safe','on'=>'search'),
		);
	}

	public function relations(){
		return array(
		);
	}

	public function attributeLabels(){
		return array(
			'comment_id' => 'Comment',
			'loanaccount_id' => 'Loanaccount',
			'comment' => 'Comment',
			'activity'=>'Action',
			'commented_by' => 'Commented By',
			'commented_at' => 'Commented At',
		);
	}
	
	public function search(){
		$criteria = new CDbCriteria;

		$criteria->compare('comment_id',$this->comment_id);
		$criteria->compare('rm',$this->rm);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('activity',$this->activity,true);
		$criteria->compare('commented_by',$this->commented_by);
		$criteria->compare('commented_at',$this->commented_at,true);
		$criteria->compare('loanaccount_id',$this->loanaccount_id);
		//$criteria->addCondition('type_id != 1');

		if(isset($this->startDate) && isset($this->endDate)){
			$criteria->addBetweenCondition("DATE(commented_at)",$this->startDate, $this->endDate, 'AND');
		}

		switch(Yii::app()->user->user_level){
			case '0':
			break;
			
			case '1':
			$criteria->addCondition('branch_id='.Yii::app()->user->user_branch);
			break;

			case '2':
			$criteria->addCondition('rm='.Yii::app()->user->user_id);
			break;

			case '3':
			$criteria->addCondition('user_id='.Yii::app()->user->user_id);
			break;
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'comment_id DESC',
			),
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['DEFAULTRECORDSPERPAGE']
			),
		));
	}

	public function getCommentBranchList(){
		return CHtml::listData(Reports::getAllBranches(),'branch_id','name');
	}

	public function getCommentManagerList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('STAFF'),'id','ProfileNameWithIdNumber');
	}

	public function getCommentTypeList(){
		return CHtml::listData(LoanApplication::getAllCommentTypes(),'id','name');
	}


	public function getAllCommentTypeList(){
		$typeQuery = "SELECT * FROM comment_types  ORDER BY name ASC";
		return CHtml::listData(CommentTypes::model()->findAllBySql($typeQuery),'id','name');
	}

	public function getCommentClientList(){
		return CHtml::listData(ProfileEngine::getProfilesByType('ALL'),'id','ProfileNameWithIdNumber');
	}

	public function getLoanActualComment(){
		return $this->comment;
	}

	public function getActualLoanBalance(){
		return LoanManager::getActualLoanBalance($this->loanaccount_id);
	}

	public function getFormattedActualLoanBalance(){
		return CommonFunctions::asMoney($this->ActualLoanBalance);
	}

	public function getCommentAccountNumber(){
		$account = Loanaccounts::model()->findByPk($this->loanaccount_id);
		return !empty($account) ? $account->account_number : "";
	}

	public function getCommentBranchName(){
		$branch = Branch::model()->findByPk($this->branch_id);
		return !empty($branch)? strtoupper($branch->name) :  "";
	}

	public function getCommentRelationManager(){
		$user = Profiles::model()->findByPk($this->rm);
		return !empty($user) ? $user->ProfileFullName : "";
	}

	public function getCommentClientName(){
		$user = Profiles::model()->findByPk($this->user_id);
		return !empty($user) ? $user->ProfileFullName : "";
	}

	public function getLoanCommentedByName(){
		$user = Profiles::model()->findByPk($this->commented_by);
		return !empty($user) ? $user->ProfileFullName : "";
	}

	public function getCommentTypeName(){
		$comment = CommentTypes::model()->findByPk($this->type_id);
		return !empty($comment) ? $comment->name : "No Comment Type";
	}

	public function getLoanCommentedAt(){
		return date('jS M Y H:i:s',strtotime($this->commented_at));
	}

	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}

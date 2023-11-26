

<?php

class CollateralFunctions{
	
	public static function createCollateralType($data){
		$collateralName=$data['Collateraltypes']['name'];
		$status=CollateralFunctions::checkIfCollateralTypeExists($collateralName);
		switch($status){
			case 0:
			$collateralType=new Collateraltypes;
			$collateralType->name=$collateralName;
			$collateralType->created_by=Yii::app()->user->user_id;
			if($collateralType->save()){
				$saveStatus=1;
				return $saveStatus;
			}else{
				$saveStatus=0;
				return $saveStatus;
			}
			break;

			case 1:
			$saveStatus=2;
			return $saveStatus;
			break;
		}
	}

	public static function checkIfCollateralTypeExists($collateralName){
		$collateralSql="SELECT * FROM collateraltypes WHERE name='$collateralName'";
		$collateral=Collateraltypes::model()->findAllBySql($collateralSql);
		if(count($collateral) >0 ){
			$status=1;
			return $status;
		}else{
			$status=0;
			return $status;
		}
	}

	public static function createCollateral($data){
		switch(CollateralFunctions::checkIfCollateralExists($data)){
			case 0:
			$collateral=new Collateral;
			$collateral->collateraltype_id=$data['collateraltype_id'];
			$collateral->loanaccount_id=$data['loanaccount_id'];
			$collateral->name=$data['name'];
			$collateral->model=$data['model'];
			$collateral->serial_number=$data['serial_number'];
			$collateral->market_value=$data['market_value'];
			$collateral->photo=$data['photo'];
			$collateral->photo->saveAs(Yii::app()->basePath."/../docs/loans/collaterals/".$collateral->photo);
			$collateral->uploaded_by=Yii::app()->user->user_id;
			if($collateral->save()){
				$activity="Added Loan Collateral";
	      $severity='high';
	      Logger::logUserActivity($activity,$severity);
				$status=1;
			}else{
				$status=0;
			}
			break;

			case 1:
			$status=2;
			break;
		}
		return $status;
	}

	public static function checkIfCollateralExists($data){
		$serialnumber=$data['serial_number'];
		$collateralSql="SELECT * FROM collateral WHERE serial_number='$serialnumber'";
		$collateral=Collateral::model()->findAllBySql($collateralSql);
		if(count($collateral) >0 ){
			$status=1;
			return $status;
		}else{
			$status=0;
			return $status;
		}
	}

	public static function getCollateralTypes(){
		$typeSql="SELECT * FROM collateraltypes";
		$types=Collateraltypes::model()->findAllBySql($typeSql);
		if(!empty($types)){
			foreach($types as $type){
				echo '<option value=';echo $type->collateralType_id;echo'>';echo $type->name;echo'</option>';
			}
		}
	}

	public static function getAllLoanCollateral($loanaccount_id){
		$collateralSql="SELECT * FROM collateral WHERE loanaccount_id=$loanaccount_id";
		$collateral=Collateral::model()->findAllBySql($collateralSql);
		return $collateral;
	}
}
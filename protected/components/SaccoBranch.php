<?php

class SaccoBranch{

	public static function mergeBranches($data){
		$formedBranchName = $data['newBranch'];
		switch(SaccoBranch::restrictSameBranchNames(strtoupper($formedBranchName))){
			case 0:
			$formedBranchID = SaccoBranch::getRecentlyCreatedBranchID($formedBranchName);
			if($formedBranchID > 0){
				$merged = SaccoBranch::migrateBranchAssets($data,$formedBranchID) === 1 ? 1 : 0;
			}else{
				$merged = 0;
			}
			break;

			case 1:
			$merged=2;
			break;
		}
		return $merged;
	}

	public static function restrictSameBranchNames($formedBranchName){
		$branchQuery="SELECT * FROM branch WHERE name='$formedBranchName'";
		$branch=Branch::model()->findAllBySql($branchQuery);
		return !empty($branch) ? 1 : 0;
	}

	public static function getRecentlyCreatedBranchID($formedBranchName){
		$morphedBranch=new Branch;
		$morphedBranch->name       = strtoupper($formedBranchName);
		$morphedBranch->created_by = Yii::app()->user->user_id;
		$morphedBranch->created_at = date('Y-m-d H:i:s');
		return $morphedBranch->save() ? $morphedBranch->branch_id : 0;
	}

	public static function migrateBranchAssets($data,$newBranchID){
		if(!empty($data)){
			foreach($data['branches'] AS $branch){
				if(SaccoBranch::doMergeBranch($branch) === 1){
					SaccoBranch::migrateBranchUsers($branch,$newBranchID);
				}
			}
			$migrated=1;
		}else{
			$migrated=0;
		}
		return $migrated;
	}

	public static function doMergeBranch($branchID){
		$branch = Branch::model()->findByPk($branchID);
		$branch->is_merged = '1';
		return $branch->update() ? 1 : 0;
	}

	public static function migrateBranchUsers($currentBranchID,$newBranchID){
		$userQuery = "SELECT * FROM profiles WHERE branchId=$currentBranchID";
		$users     = Profiles::model()->findAllBySql($userQuery);
		if(!empty($users)){
			foreach($users AS $user){
				$user->branchId = $newBranchID;
			    $user->update();
				ProfileEngine::propagateProfileUpdate($user->id,$user->branchId,$user->managerId,$user->idNumber);
			}
		}
	}

	public static function getUnmergedBranches(){
		$branchQuery = "SELECT * FROM branch WHERE is_merged='0'";
		return Branch::model()->findAllBySql($branchQuery);
	}

	public static function displaySaccoBranches(){
		$branches = SaccoBranch::getUnmergedBranches();
		if(!empty($branches)){
			foreach($branches as $branch){
			echo '<div class="col-md-6 col-lg-6 col-sm-12">
					<div class="form-check">
						<label class="form-check-label">
						<input class="form-check-input" type="checkbox" name="branches[]" value="';echo $branch->branch_id;
						echo'">
						<span class="form-check-sign"></span>';
						echo $branch->name; echo '
						</label>
					</div>
				</div>';
			}
		}
	}
}
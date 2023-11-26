<?php

class Chama{

    public static function checkIfDuplicateChamaLocation($location,$town){
       $duplicateQuery = "SELECT * FROM chama_locations WHERE name='$location' AND town='$town'";
       $duplicate      = ChamaLocations::model()->findAllBySql($duplicateQuery);
       return !empty($duplicate) ? 1000 : 1001;
    }

    public static function checkIfDuplicateChamaOrganization($chamaOrganization){
        $duplicateQuery = "SELECT * FROM chama_organizations WHERE name='$chamaOrganization'";
        $duplicate      = ChamaOrganizations::model()->findAllBySql($duplicateQuery);
        return !empty($duplicate) ? 1000 : 1001;
    }

    public static function checkIfDuplicateChama($chamaName){
        $duplicateQuery = "SELECT * FROM chamas WHERE name='$chamaName'";
        $duplicate      = Chamas::model()->findAllBySql($duplicateQuery);
        return !empty($duplicate) ? 1000 : 1001;
    }

    public static function getChamaLocations(){
      return ChamaLocations::model()->findAll();
    }

    public static function getChamaOrganizations(){
        return ChamaOrganizations::model()->findAll();
    }

    public static function onboardChamaMember($chamaId,$memberId){
        switch(Chama::checkMemberAlreadyOnboarded($memberId)){
            case 1000:
            return 1001;
            break;

            case 1001:
            $chama       = Chamas::model()->findByPk($chamaId);
            $chamaName   = strtoupper($chama->name);
            $profile     = Profiles::model()->findByPk($memberId);
            $firstName   = $profile->firstName;
            $profilePhone= $profile->ProfilePhoneNumber;
            $phoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
            $numbers     = array();
			array_push($numbers,$phoneNumber);
            $member = new ChamaMembers;
            $member->chama_id   = $chamaId;
            $member->user_id    = $memberId;
            $member->created_by = Yii::app()->user->user_id;
            $member->created_at = date("Y-m-d H:i:s");
            if($member->save()){
                $chamaWelcomeMessage = "Dear $firstName, Welcome to TCL, you are now a registered member of $chamaName GROUP.
                 Your account number is $profilePhone.Thank you!";
				SMS::broadcastSMS($numbers,$chamaWelcomeMessage,'42',$profile->id);
                return 1000;
            }else{
                return 1003;
            }
            break;
        }
    }

    public static function getChamaLevelBased(){
        $chamaQuery = "SELECT * FROM chamas ";
        $userID     = Yii::app()->user->user_id;
        $userBranch = Yii::app()->user->user_branch;
        switch(Yii::app()->user->user_level){
			case '0':
            $chamaQuery .= " ";
			break;

			case '1':
            $chamaQuery .= " WHERE branch_id = $userBranch";
			break;

			case '2':
            $chamaQuery .= " WHERE rm = $userID";
			break;

			default:
            $chamaQuery .= " WHERE leader = $userID";
			break;
		}
        $chamaQuery .= " ORDER BY name ASC";
        return Chamas::model()->findAllBySql($chamaQuery);
    }

    public static function checkMemberAlreadyOnboarded($memberId){
        $duplicateQuery = "SELECT * FROM chama_members WHERE user_id=$memberId";
        $duplicate      = ChamaMembers::model()->findAllBySql($duplicateQuery);
        return !empty($duplicate) ? 1000 : 1001;
    }

    public static function getMemberChama($memberId){
        $chamaQuery = "SELECT * FROM chamas WHERE id=(SELECT chama_id FROM chama_members WHERE user_id=$memberId)";
        return Chamas::model()->findBySql($chamaQuery);
    }

    public static function displayChamas($chamas){
		if(!empty($chamas)){
			foreach($chamas as $chama){
			echo '<div class="col-md-3 col-lg-3 col-sm-12">
					<div class="form-check">
						<label class="form-check-label">
						<input class="form-check-input" type="checkbox" name="chamas[]" value="';echo $chama->id;
						echo'">
						<span class="form-check-sign"></span>';
						echo $chama->ChamaName; echo '
						</label>
					</div>
				</div>';
			}
		}
	}


}
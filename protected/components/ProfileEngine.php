<?php

class ProfileEngine {

    /******************************
    * 
    * PROFILES
    * 
    */
    public static function enforceProfileUniqueness($column,$value){
        $profile = Profiles::model()->findAllBySql("SELECT * FROM profiles WHERE $column=$value");
        return empty($profile) && count($profile)==0 ? 1000 : 1001;
    }

    public static function updateCommonDetails($model,$profileId){
        $profile = Profiles::model()->findByPk($profileId);
        $model->profileId = $profile->id;
        $model->managerId = $profile->managerId;
        $model->branchId  = $profile->branchId;
        $model->update();
    }

    public static function propagateProfileUpdate($profileId,$branchId,$managerId,$idNumber){
        $propagations = Yii::app()->params['PROFILE-UPDATE-PROPAGATE-TABLES'];
        foreach($propagations AS $propagation){
            Yii::app()->db->createCommand("UPDATE $propagation SET branch_id=$branchId, rm=$managerId WHERE user_id=$profileId")->execute();
        }
        Yii::app()->db->createCommand("UPDATE auths SET branchId=$branchId, managerId=$managerId WHERE profileId=$profileId")->execute();
        Yii::app()->db->createCommand("UPDATE loanaccounts SET account_number='$idNumber' WHERE user_id=$profileId")->execute();
    }

    //ALL USERS
    public static function getProfilesByType($profileType){
        $profileBranchID = Yii::app()->user->user_branch;
		$profileID       = Yii::app()->user->user_id;
        $profileQuery    = $profileType === 'ALL' ? "SELECT * FROM profiles WHERE id IN(SELECT profileId FROM auths)"
                                                  : "SELECT * FROM profiles WHERE id IN(SELECT profileId FROM auths) AND profileType IN('$profileType')";
		switch(Yii::app()->user->user_level){
			case '0':
			$profileQuery.="";
			break;

			case '1':
            $profileQuery.=" AND branchId=$profileBranchID";
			break;

            case '2':
            $profileQuery.=" AND managerId=$profileID";
            break;

            case '3':
            $profileQuery.=" AND id=$profileID";
            break;
		}
        $profileQuery.="  ORDER BY firstName,lastName ASC";
        return Profiles::model()->findAllBySql($profileQuery);
    }

    //ALL SALARIED CATEGORIES
    public static function getProfilesByTypeSALARIED($profileType){
        $profileBranchID = Yii::app()->user->user_branch;
        $profileID       = Yii::app()->user->user_id;
        $profileQuery    = $profileType === 'SALARIED' ? "SELECT * FROM profiles WHERE id IN(SELECT profileId FROM auths) AND profiles.clientCategoryClass = 'SALARIED';"
            : "SELECT * FROM profiles WHERE id IN(SELECT profileId FROM auths) AND profileType IN('$profileType')";
        switch(Yii::app()->user->user_level){
            case '0':
                $profileQuery.="";
                break;

            case '1':
                $profileQuery.=" AND branchId=$profileBranchID";
                break;

            case '2':
                $profileQuery.=" AND managerId=$profileID";
                break;

            case '3':
                $profileQuery.=" AND id=$profileID";
                break;
        }
        $profileQuery.="  ORDER BY firstName,lastName ASC";
        return Profiles::model()->findAllBySql($profileQuery);
    }

    //BOTH SALARIED & BUSINESS CATEGORIES
    public static function getProfilesByCategory($profileType)
    {
        $profileBranchID = Yii::app()->user->user_branch;
        $profileID       = Yii::app()->user->user_id;
        $profileQuery    = $profileType === 'ALL' ? "SELECT * FROM profiles WHERE id IN(SELECT profileId FROM auths) AND profiles.clientCategoryClass IN('SALARIED','BUSINESS');"
            : "SELECT * FROM profiles WHERE id IN(SELECT profileId FROM auths) AND profileType IN('$profileType')";
        switch(Yii::app()->user->user_level){
            case '0':
                $profileQuery.="";
                break;

            case '1':
                $profileQuery.=" AND branchId=$profileBranchID";
                break;

            case '2':
                $profileQuery.=" AND managerId=$profileID";
                break;

            case '3':
                $profileQuery.=" AND id=$profileID";
                break;
        }
        $profileQuery.="  ORDER BY firstName,lastName ASC";
        return Profiles::model()->findAllBySql($profileQuery);
    }

    public static function getLoanDirectionProfiles(){
        $userLevel = Yii::app()->user->user_level;
        $directQuery = "";

        switch($userLevel){
            case '0':
                $directQuery = "SELECT * FROM profiles WHERE id IN(SELECT profileId FROM auths WHERE level IN('SUPERADMIN', 'ADMIN'))";
                break;
            case '1':
            case '2':
                $directQuery = "SELECT * FROM profiles WHERE id IN(SELECT profileId FROM auths WHERE level IN('ADMIN'))";
                break;
            default:
                throw new Exception("Invalid user level: $userLevel");
        }
        return Profiles::model()->findAllBySql($directQuery);
    }

    public static function getProfileLoanAccounts($profileId){
		return Loanaccounts::model()->findAllBySql("SELECT * FROM loanaccounts WHERE user_id=$profileId ORDER BY loanaccount_id DESC");
    }

    //before introducing category class
//    public static function createUserProfile($branchId,$managerId,$profileType,$firstName,$lastName,$gender,$birthDate,$username,$password,$level,$phoneNumber,$idNumber){
//        $profileId = ProfileEngine::createAccountProfile($branchId,$managerId,$profileType,$firstName,$lastName,$gender,$birthDate,$idNumber);
//        switch($profileId){
//            case -1:
//            return 1006;
//            break;
//
//            default:
//            return $profileId > 0 ? ProfileEngine::createLoginProfile($profileId,$username,$password,$level,$phoneNumber) : 1005;
//            break;
//        }
//    }
    public static function createUserProfile($branchId,$clientCategoryClass,$managerId,$profileType,$firstName,$lastName,$gender,$birthDate,$username,$password,$level,$phoneNumber,$idNumber){
        $profileId = ProfileEngine::createAccountProfile($branchId,$clientCategoryClass,$managerId,$profileType,$firstName,$lastName,$gender,$birthDate,$idNumber);
        switch($profileId){
            case -1:
                return 1006;
                break;

            default:
                return $profileId > 0 ? ProfileEngine::createLoginProfile($profileId,$username,$password,$level,$phoneNumber) : 1005;
                break;
        }
    }


    public static function enforceIdNumberUniqueness($idNumber){
        $profile = Profiles::model()->find('idNumber=:a',array(':a'=>$idNumber));
		return !empty($profile) ? 1 : 0;
    }

    //Before category class
//    public static function createAccountProfile($branchId,$managerId,$profileType,$firstName,$lastName,$gender,$birthDate,$idNumber){
//        switch(ProfileEngine::enforceIdNumberUniqueness($idNumber)){
//            case 0:
//            $profile = new Profiles;
//            $profile->branchId    = $branchId;
//            $profile->managerId   = $managerId;
//            $profile->profileType = $profileType;
//            $profile->firstName   = $firstName;
//            $profile->lastName    = $lastName;
//            $profile->gender      = $gender;
//            $profile->birthDate   = $birthDate;
//            $profile->idNumber    = $idNumber;
//            $profile->createdBy   = Yii::app()->user->user_id;
//            $profile->createdAt   = date('Y-m-d H:i:s');
//            return $profile->save() ? $profile->id : 0;
//            break;
//
//            case 1:
//            return -1;
//            break;
//        }
//    }
    public static function createAccountProfile($branchId,$clientCategoryClass,$managerId,$profileType,$firstName,$lastName,$gender,$birthDate,$idNumber){
        switch(ProfileEngine::enforceIdNumberUniqueness($idNumber)){
            case 0:
                $profile = new Profiles;
                $profile->branchId    = $branchId;
                $profile->clientCategoryClass    = $clientCategoryClass;
                $profile->managerId   = $managerId;
                $profile->profileType = $profileType;
                $profile->firstName   = $firstName;
                $profile->lastName    = $lastName;
                $profile->gender      = $gender;
                $profile->birthDate   = $birthDate;
                $profile->idNumber    = $idNumber;
                $profile->createdBy   = Yii::app()->user->user_id;
                $profile->createdAt   = date('Y-m-d H:i:s');
                return $profile->save() ? $profile->id : 0;
                break;
            case 1:
                return -1;
                break;
        }
    }

    public static function createLoginProfile($profileId,$username,$password,$level,$phoneNumber){
        $profile = Profiles::model()->findByPk($profileId);
        switch(ProfileEngine::enforceUsernameUniqueness($username)){
            case 1000:
            $auth = new Auths;
            $auth->profileId = $profile->id;
            $auth->branchId = $profile->branchId;
            $auth->managerId = $profile->managerId;
            $auth->username  = $username;
            $auth->password  = password_hash($password,PASSWORD_DEFAULT);
            $auth->level     = $level;
            $auth->createdBy = Yii::app()->user->user_id;
            $auth->createdAt = date('Y-m-d H:i:s');
            if($auth->save()){
                if(ProfileEngine::enforceContactUniqueness($phoneNumber) == 1000){
                    $contact = new Contacts;
                    $contact->profileId    = $profileId;
                    $contact->contactValue = $phoneNumber;
                    $contact->createdAt    = date('Y-m-d H:i:s');
                    $contact->createdBY    = Yii::app()->user->user_id;
                    $contact->save();
                }
                ProfileEngine::setMandatoryProfileAccountSettings($profileId);
                return 1000;
            }else{
                ProfileEngine::rollbackCreatedProfile($profileId);
                return 1004;
            }
            break;

            case 1001:
            ProfileEngine::rollbackCreatedProfile($profileId);
            return 1003;
            break;
        }
    }

    public static function rollbackCreatedProfile($profileId){
        Yii::app()->db->createCommand("DELETE FROM profiles WHERE id=$profileId")->execute();
    }

    /*******************************
    * 
    * AUTHS
    * 
    */
    public static function determineStatusActionLink($profileId){
        $profile = Profiles::model()->findByPk($profileId);
        switch($profile->profileStatus){
            case 'ACTIVE':
            if(Navigation::checkIfAuthorized(21) == 1){
                echo "&emsp;&emsp;<a href='#' class='btn btn-danger btn-sm' title='Suspend Account'
                onclick='Authenticate(\"".Yii::app()->createUrl('profiles/suspendProfile/'.$profileId)."\")'><i class='fa fa-close'></i></a>";
            }else{
                echo "";
            }
            break;

            default:
            if(Navigation::checkIfAuthorized(20) == 1){
                echo "&emsp;&emsp;<a href='#' class='btn btn-success btn-sm' title='Activate Account'
                onclick='Authenticate(\"".Yii::app()->createUrl('profiles/activateProfile/'.$profileId)."\")'><i class='fa fa-check'></i></a>";
            }else{
                echo "";
            }
            break;
        }
       
    }

    public static function manageAuthAccountStatus($authId,$authStatus){
        $auth = Auths::model()->findByPk($authId);
        $auth->authStatus = $authStatus;
        $auth->update();
        $profile = Profiles::model()->findByPk($auth->profileId);
        $profile->profileStatus = $authStatus;
        $profile->updatedAt     = date('Y-m-d H:i:s');
        return $profile->update() ? 1000 : 1001;
    }

    public static function enforceUsernameUniqueness($username){
        $auth = Auths::model()->find('username=:a',array(':a'=>$username));
        return !empty($auth) ? 1001 : 1000;
    }

    public static function persistLastLogInDate($profileId){
        $auth = Auths::model()->find('profileId=:a',array(':a'=>$profileId));
        $auth->lastLoggedAt = date('Y-m-d H:i:s');
        $auth->update();
    }

    public static function persistPasswordUpdateDate($profileId){
        $auth = Auths::model()->find('profileId=:a',array(':a'=>$profileId));
        $auth->updatedAt = date('Y-m-d H:i:s');
        $auth->update();
        ProfileEngine::persistLastLogInDate($profileId);
        ProfileEngine::manageAuthAccountStatus($auth->id,'ACTIVE');
    }
    /*******************************
    * 
    * CONTACTS
    * 
    * 
    */
    public static function enforceContactUniqueness($contact){
        $contactValue = Contacts::model()->find('contactValue=:a',array(':a'=>$contact));
        return !empty($contactValue) ? 1001 : 1000;
    }

    public static function enforceSinglePrimaryContact($profileId,$contactType){
        $contactQuery = "SELECT * FROM contacts WHERE profileId=$profileId AND contactType='$contactType' AND isPrimary=1";
        $contactValue = Contacts::model()->findAllBySql($contactQuery);
        return empty($contactValue) && count($contactValue)==0 ? 1000 : 1001;
    }

    public static function toggleContactPrimaryStatus($contactID,$toggleStatus){
        $contact = Contacts::model()->findByPk($contactID);
        $contact->isPrimary  = $toggleStatus;
        $contact->isVerified = $toggleStatus;
        $contact->save();
        if($toggleStatus == 1 && $contact->contactType =='PHONE'){
            ProfileEngine::updateSavingAccountNumber($contact->profileId,$contact->contactValue);
        }
    }

    public static function makePreviousContactSecondary($profileId,$contactType){
        $contactQuery = "SELECT * FROM contacts WHERE profileId=$profileId AND contactType='$contactType' AND isPrimary=1";
        $contacts     = Contacts::model()->findAllBySql($contactQuery);
        foreach($contacts AS $contact){
            ProfileEngine::toggleContactPrimaryStatus($contact->id,0);
        }
    }

    public static function updateSavingAccountNumber($profileId,$contactValue){
        $accountNumber = "0".substr($contactValue,-9);
        Yii::app()->db->createCommand("UPDATE savingaccounts SET account_number='$accountNumber' WHERE user_id=$profileId")->execute();
    }

    public static function getProfileContactsByType($profileId,$contactType){
        $contactQuery = "SELECT * FROM contacts WHERE profileId=$profileId";
        switch($contactType){
            case "ALL":
            $contactQuery .="";
            break;

            default:
            $contactQuery .=" AND contactType='$contactType'";
            break;
        }
        $contactQuery .=" ORDER BY id DESC";
        return Contacts::model()->findAllBySql($contactQuery);
    }

    public static function getProfileContactByType($profileId,$contactType){
        $contactQuery = "SELECT contactValue FROM contacts WHERE profileId=$profileId AND contactType='$contactType' AND isPrimary=1";
        $contact  = Contacts::model()->findBySql($contactQuery);
        return !empty($contact) ? $contact->contactValue : "";
    }

    public static function getProfileContactByTypeOrderDesc($profileId,$contactType){
        return ProfileEngine::getProfileContactByType($profileId,$contactType);
    }

    public static function getProfileByContact($contactValue,$contactType){
        $contactQuery = "SELECT profileId FROM contacts WHERE contactValue='$contactValue' AND contactType='$contactType' ORDER BY id DESC LIMIT 1";
        $contact  = Contacts::model()->findBySql($contactQuery);
        return !empty($contact) ? $contact->profileId : 0;
    }
    /*******************************
    * 
    * RELATIONS
    * 
    */
    public static function getProfileRelationsByType($profileId,$relationType){
        $relationQuery = "SELECT * FROM dependencies WHERE profileId=$profileId";
        switch($relationType){
            case "ALL":
            $relationQuery .= " ";
            break;

            default:
            $relationQuery .= " AND relationType='$relationType'";
            break;
        }
        $relationQuery .= "ORDER BY id DESC";
        return Dependencies::model()->findAllBySql($relationQuery);
    }

    public static function enforceRelationPhoneUniqueness($phoneNumber){
        $relationQuery = "SELECT * FROM dependencies WHERE phoneNumber='$phoneNumber'";
        $relationValue = Dependencies::model()->findAllBySql($relationQuery);
        return empty($relationValue) && count($relationValue)==0 ? 1000 : 1001;
    }
    /*******************************
    * 
    * EMPLOYMENTS
    * 
    */
     public static function getProfileEmployments($profileId){
        $employmentQuery = "SELECT * FROM employments WHERE profileId=$profileId ORDER BY id DESC";
        return Employments::model()->findAllBySql($employmentQuery);
     }

     public static function getRecentProfileEmployment($profileId){
        $employmentQuery = "SELECT * FROM employments WHERE profileId=$profileId ORDER BY id DESC LIMIT 1";
        $employment = Employments::model()->findBySql($employmentQuery);
        return !empty($employment) ? strtoupper($employment->employer) : "";
     }

     public static function enforceEmploymentUniqueness($id,$employer){
        $employmentQuery = "SELECT * FROM employments WHERE employer='$employer' AND profileId='$id'";
        $employment     = Contacts::model()->findAllBySql($employmentQuery);
        return empty($employment) && count($employment)==0 ? 1000 : 1001;
     }

     public static function markEmploymentsOrResidencesAsPast($profileId,$tableName){
        Yii::app()->db->createCommand("UPDATE $tableName SET isCurrent=0 WHERE profileId=$profileId")->execute();
     }
    /*******************************
    * 
    * RESIDENCES
    * 
    */
    public static function getProfileResidences($profileId){
        $residenceQuery = "SELECT * FROM residences WHERE profileId=$profileId ORDER BY id DESC";
        return Residences::model()->findAllBySql($residenceQuery);
     }

     public static function getRecentProfileResidence($profileId){
        $residenceQuery = "SELECT * FROM residences WHERE profileId=$profileId ORDER BY id DESC LIMIT 1";
        $reside = Residences::model()->findBySql($residenceQuery);
        return !empty($reside) ?  $reside->residence : "";
     }

     public static function enforceResidenceUniqueness($profileId,$residence){
        $residenceQuery = "SELECT * FROM residences WHERE residence='$residence' AND profileId='$profileId'";
        $residence      = Residences::model()->findAllBySql($residenceQuery);
        return empty($residence) && count($residence)==0 ? 1000 : 1001;
     }
     
    /*******************************
    * 
    * ACCOUNT CONFIGS
    * 
    */
    public static function getProfileConfiguredTypes($profileId){
        $configQuery = "SELECT configType FROM account_settings WHERE profileId=$profileId";
        $configs     = AccountSettings::model()->findAllBySql($configQuery);
        $configArray = array();
        if(!empty($configs)){
            foreach($configs AS $config){
                array_push($configArray,$config->configType);
            }
        }
        return $configArray;
    }
    
    public static function getProfileConfigTypes($profileId){
        $assigned    = ProfileEngine::getProfileConfiguredTypes($profileId);
        $configTypes = Yii::app()->params['PROFILE_CONFIG_TYPES'];
        $configArray = array();
        foreach($configTypes AS $configType){
            if(!in_array($configType,$assigned)){
                array_push($configArray,$configType);
            }
        }
        return $configArray;
    }

    public static function previousSettingAsPast($id,$configType){
        Yii::app()->db->createCommand("UPDATE account_settings SET configActive='DISABLED' WHERE profileId=$id AND configType='$configType'")->execute();
    }

    public static function getProfileAccountSettings($id,$configType){
        $settingQuery = "SELECT * FROM account_settings WHERE profileId=$id";
        switch($configType){
            case "ALL":
            $settingQuery.=""; 
            break;

            default:
            $settingQuery.=" AND configType='$configType'"; 
            break;
        }
        $settingQuery.=" ORDER BY id DESC"; 
        return AccountSettings::model()->findAllBySql($settingQuery);
    }

    public static function getActiveProfileAccountSettingByType($id,$configType){
        $settingQuery = "SELECT * FROM account_settings WHERE profileId=$id AND configType='$configType' AND configActive='ACTIVE'
         ORDER BY id DESC LIMIT 1";
        $setting      = AccountSettings::model()->findBySql($settingQuery);
        return !empty($setting) ? $setting->configValue : "NOT SET";
    }

    public static function setMandatoryProfileAccountSettings($profileId){
        $profile = Profiles::model()->findByPk($profileId);
        //settings will now be picked from user branch,
        //this is because we have now introduced branch specific settings
        $branch = Branch::model()->findByPk($profile->branchId);
        $settings = array(
            'LOAN_LIMIT'               => $branch->loan_limit,
            'LOAN_INTEREST_RATE'       => $branch->interest_rate,
            'SAVINGS_INTEREST_RATE'    => $branch->savings_interest_rate,
            'INSURANCE_PERCENT'        => $branch->insurance_rate,
            'PROCESSING_PERCENT'       => $branch->processing_fee,
        );
        //once done with the above, we can now set the rest of the settings from params
        $settings = array_merge($settings,Yii::app()->params['PROFILE_CONFIG_TYPES_KEYS_VALUES']);
        foreach($settings AS $key => $value){
            ProfileEngine::setProfileAccountSetting($profileId,$key,$value);
        }
    }

    public static function setProfileAccountSetting($profileId,$configType,$configValue){
        $config = new AccountSettings;
        $config->profileId   = $profileId;
        $config->configType  = $configType;
        $config->configValue = $configValue;
        $config->createdBy   = Yii::app()->user->user_id;
        $config->createdAt   = date('Y-m-d H:i:s');
        $config->save();
    }
    /*
    *
    *GROUPS / CHAMAS
    **/
    public static function getProfilesNotInAnyGroup(){
		$membersQuery = "SELECT * FROM profiles WHERE id NOT IN(SELECT user_id FROM chama_members) AND profileType IN('MEMBER')";
		$userBranch  = Yii::app()->user->user_branch;
		$userID      = Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
            case '1':
            $membersQuery.=" AND branchId=$userBranch";
            break;

            case '2':
            $membersQuery.=" AND managerId=$userID";
            break;

            case '3':
            $membersQuery.=" AND id=$userID";
            break;
		}
		$membersQuery.=" ORDER BY firstName,lastName ASC";
		return Profiles::model()->findAllBySql($membersQuery);
	}
	
	public static function getProfileGroupAccountManagers(){
		$collectorsQuery= "SELECT * FROM profiles WHERE profileType IN('STAFF') AND id IN(SELECT profileId from auths WHERE authStatus IN('ACTIVE'))";
		$userBranch     = Yii::app()->user->user_branch;
		$userID         = Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '1':
			$collectorsQuery.=" AND branchId=$userBranch";
			break;

            case '2':
            $collectorsQuery.=" AND managerId=$userID";
            break;

			case '3':
			$collectorsQuery.=" AND id=$userID";
			break;
		}
		$collectorsQuery.=" ORDER BY firstName,lastName ASC";
		return Profiles::model()->findAllBySql($collectorsQuery);
	}

	public static function createNewProfilesGroup($data){
		$group_id = ProfileEngine::createNewGroup($data);
		switch($group_id){
			case 0:
			$status=0;
			break;

			default:
			ProfileEngine::enlistProfilesToGroup($group_id,$data);
			$status=1;
			break;
		}
		return $status;
	}
  
	public static function createNewGroup($data){
        $profile = Profiles::model()->findByPk($data['collector']);
        if(!empty($profile)){
            $chama    = new Chamas;
            $chama->name            = $data['group'];
            $chama->rm              = $profile->id;
            $chama->location_id     = $data['locationId'];
            $chama->leader          = $data['group_leader'];
            $chama->is_registered   = $data['isRegistered'];
            $chama->organization_id = $data['organizationId'];
            $chama->branch_id       = $data['branch_id'];
            $chama->created_at      = date("Y-m-d H:i:s");
            $chama->created_by      = Yii::app()->user->user_id;
            return $chama->save() ? $chama->id : 0;
        }else{
            return 0;
        }
	}

	public static function enlistProfilesToGroup($group_id,$data){
		foreach($data['group_members'] as $borrower){
            Chama::onboardChamaMember($group_id,$borrower);
		}
	}

    public static function getProfileChamaMembers($group_id){
        $membershipQuery = "SELECT * FROM chama_members WHERE chama_id=$group_id";
		$members = ChamaMembers::model()->findAllBySql($membershipQuery);
		return !empty($members) ? $members : 0;
	}

	public static function getProfileGroupMembers($group_id){
        $membershipQuery = "SELECT * FROM profiles WHERE
        id IN(SELECT user_id FROM chama_members WHERE chama_id=$group_id) ORDER BY firstName,lastName ASC";
		$members = Profiles::model()->findAllBySql($membershipQuery);
		return !empty($members) ? $members : 0;
	}

    public static function getProfileGroupLeaders(){
        $membershipQuery = "SELECT * FROM profiles WHERE id IN(SELECT leader FROM chamas) ORDER BY firstName,lastName ASC";
		return Profiles::model()->findAllBySql($membershipQuery);
    }

	public static function getProfileGroupMembersWithoutLeader($group_id){
        $membershipQuery = "SELECT * FROM profiles WHERE id IN(SELECT user_id FROM chama_members WHERE chama_id=$group_id)
        AND id NOT IN(SELECT leader FROM chamas WHERE id=$group_id) ORDER BY firstName,lastName ASC";
		$members = Profiles::model()->findAllBySql($membershipQuery);
		return !empty($members) ? $members : 0;
	}

	public static function displayGroupMembers($members){
		if(!empty($members)){
			foreach($members as $member){
			echo '<div class="col-md-3 col-lg-3 col-sm-12">
					<div class="form-check">
						<label class="form-check-label">
						<input class="form-check-input" type="checkbox" name="borrowers[]" value="';echo $member->id;
						echo'">
						<span class="form-check-sign"></span>';
						echo $member->ProfileFullName; echo '
						</label>
					</div>
				</div>';
			}
		}
	}

}
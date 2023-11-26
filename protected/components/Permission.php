<?php

class Permission{

	public static function checkForRolePermissions($roleId){
		$permissions_sql="SELECT * FROM role_permission WHERE role_id=$roleId LIMIT 2";
		$permissions=RolePermission::model()->findAllBySql($permissions_sql);
		$permissions_count=count($permissions);
		return $permissions_count > 0 ?  1 :  0;
	}

	public static function getAllRolePermissions($roleId){
		$permissionQuery = "SELECT permission_id, display_name FROM permissions WHERE permission_id IN(SELECT permission_id FROM role_permission WHERE role_id=$roleId)
		ORDER BY category ASC";
		$permissions = Permissions::model()->findAllBySql($permissionQuery);
		$counter     = count($permissions);
		return $counter > 0 ?  $permissions :  0;
	}

	public static function getAllRoleCategoryPermissions($roleId,$categoryType){
		$permissionQuery = "SELECT permission_id, display_name FROM permissions WHERE 
		permission_id IN(SELECT permission_id FROM role_permission WHERE role_id=$roleId) AND category='$categoryType'";
		$permissions=Permissions::model()->findAllBySql($permissionQuery);
		$permissions_count=count($permissions);
		return $permissions_count > 0 ?  $permissions :  0;
	}

	public static function getAllPermissions($roleId){
		$permissions_sql="SELECT category,permission_id,display_name FROM permissions WHERE
		permission_id NOT IN(SELECT permission_id FROM role_permission WHERE role_id=$roleId) ORDER BY category ASC";
		$permissions=Permissions::model()->findAllBySql($permissions_sql);
		$permissions_count=count($permissions);
		return $permissions_count > 0 ?  $permissions :  0;
	}

	public static function getAllCategoryPermissions($role_id,$categoryType){
		$permiQuery="SELECT category,permission_id,display_name FROM permissions WHERE category='$categoryType'
		AND permission_id NOT IN(SELECT permission_id FROM role_permission WHERE role_id=$role_id) ORDER BY category ASC";
		$permissions=Permissions::model()->findAllBySql($permiQuery);
		$permissions_count=count($permissions);
		return $permissions_count > 0 ?  $permissions :  0;
	}

	public static function getAllPermissionsCategories(){
		$categoryQuery="SELECT DISTINCT(category) AS category FROM permissions ORDER BY category ASC";
		return Permissions::model()->findAllBySql($categoryQuery);
	}

	public static function getCategoryPermissions($categoryType){
		$categoryQuery="SELECT * FROM permissions WHERE category='$categoryType'";
		return Permissions::model()->findAllBySql($categoryQuery);
	}

	public static function displayPermissionsHTMLContent($permissions){
		foreach($permissions as $permission){
			echo '<div class="col-md-3 col-lg-3 col-sm-12">
					<div class="form-check">
						<label class="form-check-label">
						<input class="form-check-input" type="checkbox" name="permissions[]" value="';echo $permission->permission_id;echo'">
						<span class="form-check-sign"></span>';
						echo $permission->display_name; echo '
						</label>
					</div>
				</div>';
		}
	}

	public static function displayCategoryPermissionsHTMLContent($permissions){
		if($permissions != 0){
			foreach($permissions as $permission){
				echo '<div class="col-md-4 col-lg-4 col-sm-12">
							<div class="form-check">
								<label class="form-check-label">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="';echo $permission->permission_id;echo'">
								<span class="form-check-sign"></span>';
								echo $permission->display_name; echo '
								</label>
							</div>
					</div>';
			}
		}else{
			echo '<div class="col-md-8 col-lg-8 col-sm-12">
					<br>
					<strong style="color:#18ce0f !important;"> No Permissions Found for this Category</strong>
				</div>';	
		}
	}

	public static function displayRolePermissionsHTMLContent($permissions){
		foreach($permissions as $permission){
			echo '<div class="col-md-3 col-lg-3 col-sm-12">
					<div class="form-check">
						<label class="form-check-label">
						<input class="form-check-input" type="checkbox" name="permissions[]" value="';echo $permission->permission_id;
						echo'">
						<span class="form-check-sign"></span>';
						echo $permission->display_name; echo '
						</label>
					</div>
				</div>';
		}
	}

	public static function displayRolesHTMLContent($roles){
		foreach($roles as $role){
			echo '<div class="col-md-3 col-lg-3 col-sm-12 roles_content">
					<div class="form-check form-check-radio">
						<label class="form-check-label">
						<input class="form-check-input" type="radio" name="roles" value="';echo $role->role_id;
						echo'">
						<span class="form-check-sign"></span>';
						echo $role->name; echo '
						</label>
					</div>
				</div>';
		}
	}

	public static function checkIfUserAssignedRole($user_id){
		$roles_sql="SELECT role_id FROM user_role WHERE user_id=$user_id";
		$roles=UserRole::model()->findBySql($roles_sql);
		return !empty($roles) ? 1 : 0;
	}
}
<?php 
class User extends CActiveRecords
{	
	public $role = 0;
	
	public function getRoles (){
		return array(
			'user'=>0,
			'moderator'=>1,
			'admin'=>2,
			'client'=>3,
		);
	}
	
	public function getTokenForRoles($roles=array()){
		$token = 0;
		$_roles = $this->getRoles();
		for(var $i=0;$i<count($roles);$i++){
			if(isset($_roles[$roles[$i]])){
				$token = $token | (1<<$_roles[$i]);
			}
		}
		return $token;
	}
	
	public function calcTokenRoles($roles, $token = 0){
		$_roles = $this->getRoles();
		if(is_string($roles)){
			$_roles_kit = explode('-', $roles);
		} else {
			$_roles_kit = $roles;
		}
		$token = $token | $this->getTokenForRoles(explode('|',$_roles_kit[0]));
		if(isset($_roles_kit[1])){
			$token = $token - ( $token& $this->getTokenForRoles(explode('|',$_roles_kit[1])) );
		}
		return $token;
	}
	
	public function add ($data) {
		
	}
	
	public static function model($className=__CLASS__){
        return parent::model($className);
    }
 
    public function tableName(){
        return 'tbl_users';
    }
	
	public function validate ($data){
		
	}
	
	public function add (){
		
	}
}
?>
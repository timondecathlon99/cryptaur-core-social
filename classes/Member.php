<?php
class Member extends Unit{

  public $login;
  public $password;
  public $result;
  
  
	public function setTable(){ 
	  return 'users';
	}
	
	public function member_id(){
		if($this->showField('id')){
			return $this->showField('id');
		}else{
			return 0;
		}
	}
	
	public function name(){
			return $this->showField('title');
	}
	
	public function surName(){
			return $this->showField('surname');
	}

    public function fatherName(){
            return $this->showField('fathername');
    }

    public function fullName(){
            return $this->name().' '.$this->surName().' '.$this->fatherName();
    }
	
	public function status(){
		return $this->showField('status');
	}
  
	public function reputation_points(){
		return $this->showField('reputation_points');
	}
    
	public function photo(){
		if(unserialize($this->showField('photo'))[0] != ''){
			return unserialize($this->showField('photo'))[0];
		}else{
			return 'https://openclipart.org/image/2400px/svg_to_png/247319/abstract-user-flat-3.png';	
		}
	}
  
	public function photos(){
		if(unserialize($this->showField('photo')) != ''){
			return unserialize($this->showField('photo'));
		}
	}
  
	public function cover_photo(){
			return $this->showField('photo_small');
	}
  
	public function respect_points(){
	  return $this->showField('respect_points');
	}

  
	public function isActive(){
		if($this->showField('activity') == 1){
			return true;
		}else{
			return false;
		}
	}
  
	public function is_online(){
		if(time() - $this->showField('last_visit') < 60){
			return true;
		}else{
			return false;
		}
	}
  
	public function joined(){
			return date_format_rus($this->showField('publ_time'));
	}
  
	public function last_was(){
			return date_format_rus($this->showField('last_visit'));
	}
  
	public function instagram(){
			return $this->showField('instagram');
	}
  
	/* check login and pass  */
	public function loginCheck($login, $password){ 
		$this->login = $login;
		$this->password = $password;
		$member_sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." ");
        $member_sql->execute();
		$members = $member_sql->get_result()->fetch_all(MYSQLI_ASSOC);
		foreach($members as $member){
			if($member['title'] == $this->login && hash_equals($member['password'], crypt($this->password, $member['password']))){
				$id = $member['id'];
				$member_password_hash = $member['password'];
				break;	   
			}
		}
		if($id > 0){
			setcookie ("member_id", "$id",time()+3600,"/");
            $user_hash = md5($_SERVER ['HTTP_USER_AGENT'].'%'.$member_password_hash); //создаем хэш для защиты куки
            $sql = $this->mysqli->prepare("UPDATE ".$this->setTable()." SET ip_address=? , user_hash=?   WHERE id=?");
            $sql->bind_param("ssi", $_SERVER['REMOTE_ADDR'],  $user_hash, $id);
            $sql->execute();			
		}else{
			setcookie ("member_id","0",time()-3600,"/"); 
		}
		
	}
	
	/* validate the users id*/
	public function is_valid(){
		$sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
		$sql->bind_param("i", $this->id);
		$sql->execute();
		$member =  $sql->get_result()->fetch_assoc();
		$user_hash = md5($_SERVER ['HTTP_USER_AGENT'].'%'.$member['password']); //создаем хэш для проверки
		if(!hash_equals ( $member['user_hash'] , $user_hash )){  
			setcookie ("member_id","0",time()-3600,"/");
			return false;	  
		}else{
			return true;
		}
	}
  
	/* check if the user is admin  */
	public function isAdmin(){
		if($this->showField('member_group_id') == 4){
			return true;
		}else{
			return false;
		}
	}
	
	
	
	/* total users amount count */
	public function users_count(){
		$sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." ");
		$sql->execute();
		return $sql->get_result()->num_rows;
	}

	/* online users amount count */
	public function online_users_count(){
		$sql = $this->mysqli->prepare("SELECT * FROM visitors WHERE activity='1' ");
		$sql->execute();
		return $sql->get_result()->num_rows;
	}

	/* today visitors count */
	public function today_visits_count(){
	$now = time();
	$today_start = strtotime(date("d-m-Y", time()));
    $sql = $this->mysqli->prepare("SELECT * FROM visitors WHERE publ_time >'$today_start' AND publ_time <'$now' ");
    $sql->execute();
	return $sql->get_result()->num_rows;
	}

	/* month visitors count */
	public function month_visits_count(){
		$now = time();
		$month_start = strtotime(date("m-Y", time()));
		$sql = $this->mysqli->prepare("SELECT * FROM visitors WHERE publ_time >'$month_start' AND publ_time <'$now' ");
		$sql->execute();
		return $sql->get_result()->num_rows;
	}

	/* get the last visit time of a user */
	public function get_last_visit(){
		$sql = $this->mysqli->prepare("UPDATE ".$this->setTable()." SET last_visit=? WHERE id=?");
		$sql->bind_param('ii', time(), $this->id );
		try{
			$sql->execute();	
		}catch (PDOException $e) {
			echo 'Подключение не удалось: ' . $e->getMessage();
		}
	}
 
	/*
	public function visit_find(){
		$time = time() - 60;
		$sql = $this->mysqli->prepare("UPDATE visitors SET activity='0' WHERE publ_time< ?");   
		$sql->bindParam("i", $time);
		$sql->execute();
    
		if($_SESSION['page_hash']){
			$page_hash = $_SESSION['page_hash'];	
		}else{
			$page_hash = 0;	
		}  	  
		$sql = $this->mysqli->prepare("SELECT * FROM users WHERE ip_address=?");   
		$sql->bindParam("s", $_SERVER['REMOTE_ADDR']);
		$sql->execute();
		if($sql->get_results->num_rows > 0){
			$member =  $sql->get_results->fetch_assoc();
			$name = $member['title'];
			$user_id = $member['id'];		
		}else{
			$name = 'Гость'; 
			$user_id = 0;	
		}
		try{  
			$sql = $this->mysqli->prepare("SELECT * FROM visitors WHERE activity='1' AND ip_address=?");   
			$sql->bindParam("s", $_SERVER['REMOTE_ADDR']);
			$sql->execute();  
			if($sql->get_results->num_rows > 0){  
				
			}else{
				if( $curl = curl_init() ) {
				curl_setopt($curl, CURLOPT_URL, 'ru.sxgeo.city/xml/'.$_SERVER['REMOTE_ADDR']);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
				$out = curl_exec($curl);
				$items = new SimpleXMLElement($out);// перезаписываем items для каждой конкретной машины
				$item = $items->ip;
				$country = $item->country;
				$country = $country->name_en;
				$city = $item->city;
				$city = $city->name_en;
				if($country == ''){
				   $country = 'Australia'; 
				}
               curl_close($curl);
			}
			$sql = $this->mysqli->prepare("INSERT INTO visitors(title, ip_address, country, city, publ_time, user_id, page_hash, activity)"."VALUES(:title, :ip_address, :country, :city, :publ_time, :user_id, :page_hash, '1')");  
			$sql->bindParam("s", $name);  
			//$sql->bindParam(":title", $name);  
			//$sql->bindParam(":publ_time", time());  
			//$sql->bindParam(":user_id", $user_id);  
			//$sql->bindParam(":page_hash", $page_hash);  
			//$sql->bindParam(":ip_address", $_SERVER['REMOTE_ADDR']);
			//$sql->bindParam(":country", $country);
			//$sql->bindParam(":city", $city);
			$sql->execute(); 
		}	
      
		}catch (PDOException $e) {
			echo 'Подключение не удалось: ' . $e->getMessage();
		}
	}
		*/
  
	public function check_email($email){
		$sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE email=?");
		$sql->bind_param("s", $email);
		$sql->execute();
		if($sql->get_result()->num_rows > 0){
			return true;
		}else{
			return false;
		}
	}
  
	public function check_name($name){
		$sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE title=?");
		$sql->bind_param("s", $name);
		$sql->execute();
		if($sql->get_result()->num_rows > 0){
			return true;
		}else{
			return false;
		}
	}
  
	public function check_password($password, $confirm){
		if($password === $confirm && $password !='' && $password !=' '){
			return true;
		}else{
			return false;
		}
	}
  
	public function change_pass($new_pass){
		$sql = $this->mysqli->prepare("UPDATE ".$this->setTable()." SET password=?	WHERE restore_hash=?");
		$sql->bind_param("s", crypt($new_pass));
		$sql->bind_param("s", $_COOKIE['restore_hash']);
		try{
			$sql->execute();  
			$sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE restore_hash=?");
			$sql->bindParam("s", $_COOKIE['restore_hash']);
			$sql->execute();
			$member = $sql->get_result()->fetch_assoc();
			setcookie ("pass_restored", "1",time()+3600,"/");
			setcookie ("restore_hash","0",time()-3600,"/"); 	 
			setcookie ("member_id", $member['id'],time()+3600,"/");
			return true; 	 
		}catch(PDOException $e){
			echo 'Подключение не удалось: ' . $e->getMessage();
		}
	}
  
  
	public function has_point(){
		$sql = $this->mysqli->prepare("SELECT * FROM points_users WHERE user_id=?");
		$sql->bind_param("i", $this->id);
		$sql->execute();
		if($sql->get_result()->num_rows > 0){
			return true;
		}else{
			return false;
		}
	}
  
	public function email(){
			return $this->showField('email');
	}
  
	public function phone(){
			return $this->showField('phone');
	}
  
	public function find_by_param($param_name, $param_val){
		$sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE ?=?");
		$sql->bind_param("ss", $param_name, $param_val);
		if($sql->execute()){
			$member = $sql->get_result()->fetch_asscoc();
			return $member['id'];
		}
	}
  
  /*
  public function create($PAR_ARR, $global_pars, $domain){
     $line1 = '';
     $line2 = '';
	 $publ_time = time();
     $i =0;
	 ($global_pars['email_verification'] == 1) ? $activity = 1 : $activity = 0;
     $member_group_id = 2;	 
     $reg_hash = md5(time().md5(time()));	 
	 $arr_isset = array();
     //смотрим какие поля были получены
     foreach($PAR_ARR as $arr_item){
      if($_POST[$arr_item] != NULL){
	   if(is_array($_POST[$arr_item])){ //проверяем не массив ли это
	     $arr_isset[$arr_item] = serialize($_POST[$arr_item]);
	   }else{
         if($arr_item == 'password'){	    
          $arr_isset[$arr_item] = crypt($_POST[$arr_item]); //криптуем пароль 
	     }else{
          $arr_isset[$arr_item] = $_POST[$arr_item];
         }		 
	   }
      }
     }
	 //создаем строку полей
     foreach($arr_isset as $arr_isset_item =>$key){
       $line1 = $line1.$arr_isset_item.', ';
     }
	 //создаем строку знаений
     foreach($arr_isset as $arr_isset_item){
      $line2 = $line2."'".addslashes ($arr_isset_item)."'".', ';
      $arr_isset_values[$i] = $arr_isset_item; 
      $i++;  
     }
	
	$line1 = $line1.'activity, publ_time, reg_hash, member_group_id'; 
    $line2 = $line2."'$activity', "."'$publ_time', "."'$reg_hash', "."'$member_group_id'";
	
    try {
     $user = $this->pdo->prepare("INSERT INTO users(".$line1.")"."VALUES(".$line2.")");
     $user->execute(); 
	 
	 //смотрим пользователя
	 $sql = $this->pdo->prepare("SELECT * FROM users WHERE email= :email");
     $sql->bindParam(":email", $_POST['email']);
     $sql->execute();
	 $new_user = $sql->fetch();
	 
	 if($global_pars['email_verification'] == 1 && $new_user['id'] ){
	   $ver_link = $domain."/modules/verification/?code=".$reg_hash;
	   $msg = $new_user['title'].", благодарим Вас за регистрацию на сайте ".$global_pars['site_name'].".<br></br>  Для активации Вашего аккаунта пройдите по ссылке $ver_link";
	   setcookie ("member_id", "-1",time()+3600,"/"); 
	   mail($new_user['email'], $global_pars['site_name'].". Подтверждение E-mail адреса",$msg); 
     }else{
       setcookie ("member_id", $new_user['id'],time()+3600,"/");     
     }	
     
     //Отправляем или не отправляе подтверждение на почту 
	 		 
    }catch (PDOException $e) {
     echo 'Подключение не удалось: ' . $e->getMessage();
    }
  }
	
	public function verify($code){
		$sql = $this->pdo->prepare("SELECT * FROM users WHERE reg_hash= :hash");
		$sql->bindParam(":hash", $code);
		$sql->execute();
		if($sql->rowCount() > 0 ){
			try{
				$member = $sql->fetch();
				$ver_sql = $this->pdo->prepare("UPDATE users SET activity='1', reg_hash=''  WHERE id= :member_id");
				$ver_sql->bindParam(":member_id", $member['id']);
				$ver_sql->execute();
				$member_id = $member['id'];
				setcookie ("member_id", "$member_id",time()+3600,"/");
				return true;		 
			}
			catch (PDOException $e){
				echo 'Подключение не удалось: ' . $e->getMessage();
				return false;
			}		
		}
	}
	*/
  
	
  
	public function group_id(){
	    if($this->showField('member_group_id')){
            return $this->showField('member_group_id');
        }else{
	        return 1;
        }
	}
  
	public function group_name(){
	    $group = new Group($this->group_id());
        return $group->title();
	}                          
  
	public function can_see($permissions_arr){
		if(is_array($permissions_arr)){
			if($this->id == NULL){ //если нету куки тоесть гость то считаем его группу равной 1
			    $member_group = 1;
			}else{
				$member_group = $this->group_id();
			}
			if(in_array($member_group, $permissions_arr)){
				return true;
			}else{
				return false;
			}
		}
	}
  


	/* FRIENDS METHODS */
	public function friendsList()
    {
        return json_decode($this->showField('friends'));
    }

    public function isSubscribed(int $user_id)
    {
        if(in_array($user_id,$this->friendsList())){
            return true;
        }else{
            return false;
        }
    }

    public function isFriend(int $user_id)
    {
        if(in_array($user_id,$this->friendsList())){
            $user = new Member($user_id);
            if(in_array($this->id,$user->friendsList())){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    public function friendsCount()
    {
        return count($this->friendsList());
    }

    public function friendDelete(int $user_id)
    {
        $friendsArray =  $this->friendsList();
        if(in_array($user_id,$friendsArray)){
            unset($friendsArray[array_search($user_id, $friendsArray)]);
            sort($friendsArray); //иначе при перегоне из json в массив будет косяк
            $friendsArray = json_encode($friendsArray);
            $sql = $this->mysqli->prepare("UPDATE ".$this->setTable()." SET friends='$friendsArray'  WHERE id='".$this->id."'");
            $sql->execute();
        }

    }

    public function friendAdd(int $user_id)
    {
        $friendsArray =  $this->friendsList();
        if($friendsArray == NULL){
            $friendsArray =  array();
        }
        if(!in_array($user_id,$friendsArray)){
            array_push($friendsArray, $user_id);
            $friendsArray = json_encode($friendsArray);
            $sql = $this->mysqli->prepare("UPDATE ".$this->setTable()." SET friends='$friendsArray' WHERE id='".$this->id."' ");
            $sql->execute();
        }
    }

    public function memberInfoField($field)
    {
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
        $sql->bind_param("i", $this->id);
        $sql->execute();
        $unit = $sql->get_result()->fetch_assoc();
        if($unit[$field]){
            $field = json_decode($unit[$field]);
            $fieldValue = $field->value;
            $fieldPermission = $field->permission;
            return $unit[$field];
        }
        $sql->close();
    }



    public function isFriendOfFriend($id)
    {
        $sql = $this->mysqli->prepare("SELECT * FROM " . $this->setTable() . " WHERE id=?");
        $sql->bind_param("i", $this->id);
        $sql->execute();
        $unit = $sql->get_result()->fetch_assoc();
        $friendsArray = json_decode($unit['friends']);
        if(in_array($id, $friendsArray)){
            return true;
        }else{
            foreach ($friendsArray as $friendId) {
                $sql = $this->mysqli->prepare("SELECT * FROM " . $this->setTable() . " WHERE id=?");
                $sql->bind_param("i", $friendId);
                $sql->execute();
                $f = $sql->get_result()->fetch_assoc();
                $friendsOfFriendArray = json_decode($unit['friends']);
                if (in_array($id, $friendsOfFriendArray)) {
                    return true;
                    break;
                } else {
                    return false;
                }
            }
        }
    }
  
	
  
}



?>
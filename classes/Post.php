<?
class Post extends Unit
{
	protected $table;

    public function setTable(){

    }

	public function getTable(string $table){
        $this->table =  $table;
	}


    public function getAllFields(){
        $par_arr = array();
        $columns_sql= $this->mysqli->prepare("SHOW COLUMNS FROM ".$this->table."");
        $columns_sql->execute();
        $columns = $columns_sql->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach($columns as $column){
            $par_arr[$column['Field']] = $column['Field'];
        }
        return $par_arr;
    }

    public function showField($field)
    {
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->table." WHERE id=?");
        $sql->bind_param("i", $this->id);
        $sql->execute();
        $unit = $sql->get_result()->fetch_assoc();
        $sql->close();
        return $unit[$field];
    }

    public function show()
    {
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->table." WHERE id=?");
        $sql->bind_param("i", $this->id);
        $sql->execute();
        $unit = $sql->get_result()->fetch_assoc();
        $sql->close();
        return $unit;
    }

	
	
   public function createUpdate(){
     $line1 = '';
     $line2 = '';
     $line_update= '';
	 $publ_time = time();
     $activity = 1;
	 $table_name=$this->table;
     $id_num=$this->id;
     $i =0;
	 $arr_isset = array();
     //смотрим какие поля были получены
     foreach($this->getAllFields() as $arr_item){
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
     $i = 0;
	 //создаем строку обновления
     foreach($arr_isset as $arr_update_item =>$key){
      $sub_line = "$arr_update_item = '$arr_isset_values[$i]',";
      $line_update = $line_update." ".$sub_line;
      $i++;
     }
     $uploaddir = DOMAIN . 'uploads/'; //папка для загрузки
     //смотрим информацию о посте, если он есть
     $post_sql=$this->mysqli->prepare("SELECT * FROM $table_name WHERE id=?");
     $post_sql->bind_param('i', $this->id);
     $post_sql->execute();
     $src =$post_sql->fetch();

//////Загрузка вторичного изображения;
     if($_FILES['file']['name'] == NULL){
	    $photo_small = $src['photo_small'];
		if($this->table == 'items' || $this->table == 'database_records'){//для тамбов
			$thumb_small = $src['thumb_small'];
		}
     }else{
       if(file_exists($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$src['photo_small'])){
         unlink($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$src['photo_small']);
       }
	   if(file_exists($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$src['thumb_small'])){
         unlink($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$src['thumb_small']);
       }
       $time = time();
	   if($table_name == 'uploads'){
	     $ext = '.'.$_POST['upload_type'];
	   }else{
	    if(preg_match('/[.](jpg)$/', $_FILES['file']['name'])) {
			$ext = '.jpg';
		} elseif (preg_match('/[.](gif)$/', $_FILES['file']['name'])) {
			$ext = '.gif';
		} elseif (preg_match('/[.](png)$/', $_FILES['file']['name'])) {
			$ext = '.png';
		}
	   }
       $file_sec_new_name = md5($_FILES['file']['name'].$time).$ext;
       $uploadfile_sec = $uploaddir.$file_sec_new_name;
	   echo $_SERVER['DOCUMENT_ROOT'].$uploadfile_sec;
       if(move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$uploadfile_sec)){
		//$photo_small = $uploadfile_sec;
		$photo_small = '/uploads/'.$file_sec_new_name;
		if($this->table == 'items' || $this->table == 'database_records'){//для тамбов
			createThumbnail($file_sec_new_name);
			$thumb_small =  '/uploads/'.'thumb_'.$file_sec_new_name;
		}
		 echo "Загрузили";

       }else{
		   echo "не Загрузили";
	   }
     }
//////Загрузка вторичного изображения;

//////Загрузка основных изображений;
    $photo = array();
    if($_FILES['files']['name'][0] == NULL){
	 $photo = $src['photo'];
	 if($this->table == 'items' || $this->table == 'database_records'){//для тамбов
	  $thumbs = $src['thumbs'];
	 }
    }else{
     //удаляем фотки и строку
     $photos_old = unserialize($src['photo']);
     foreach($photos_old as $photo_old){
      if(file_exists($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$photo_old)){
        unlink($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$photo_old);
      }
     }
	 if($this->table == 'items' || $this->table == 'database_records'){//для тамбов
	  //удаляем thumbnails
      $thumbs_old = unserialize($src['thumbs']);
      foreach($thumbs_old as $thumb_old){
       if(file_exists($_SERVER["DOCUMENT_ROOT"]. DOMAIN . $thumb_old)){
	    unlink($_SERVER["DOCUMENT_ROOT"]. DOMAIN . $thumb_old);
       }
      }
	 }
     //загружаем новые и создаем ссылки
     for($i =0; $i < 10;$i++){
      $time = time();
		if(preg_match('/[.](jpg)$/', $_FILES['files']['name'][$i])) {
			$ext = '.jpg';
		} elseif (preg_match('/[.](gif)$/', $_FILES['files']['name'][$i])) {
			$ext = '.gif';
		} elseif (preg_match('/[.](png)$/', $_FILES['files']['name'][$i])) {
			$ext = '.png';
		}
      $file_new_name = md5($_FILES['files']['name'][$i].$time).$ext;
      $uploadfile = $uploaddir.$file_new_name;
      if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'].$uploadfile)) {
	   //$photo[$i] = $uploadfile;
	   $photo[$i] = '/uploads/'.$file_new_name;
	   if($this->table == 'items' || $this->table == 'database_records'){//для тамбов
	    createThumbnail($file_new_name);
	    $thumb[$i] =  '/uploads/'.'thumb_'.$file_new_name;
	   }
      }else {}
     }
     $photo = serialize($photo);
	 if($this->table == 'items' || $this->table == 'database_records'){
	  $thumbs = serialize($thumb);
	 }
    }

	//если PHP то создаем файл с кодом и записываем его название в title
	if($src['block_name'] || $_POST['category'] == 'php'){
	  if($src['block_name']){//если редактируем блок и унего есть имя блока(значит это PHP)
		$file = $src['block_name']; //сохраняем имя файла
		if(file_exists($_SERVER["DOCUMENT_ROOT"].'/blocks/'.$file)){
	     unlink($_SERVER["DOCUMENT_ROOT"].'/blocks/'.$file); //удаляем файл далее создадим новый с тем же именем
	    }
	  }
	  /* else{
		$file = str_replace(" ","_",$_POST['title']).'_'.md5(time().$_POST['title']).'.php'; //генирируем имя файля
	  } */
	  $file = str_replace(" ","_",$_POST['title']).'_'.md5(time().$_POST['title']).'.php'; //генирируем имя файля
	  $blocks_dir = '../blocks/'; //папка в которой будет создаваться блок
	  if (!file_exists($file)) {
       $fp = fopen($blocks_dir.$file, "w"); // ("r" - считывать "w" - создавать "a" - добовлять к тексту),мы создаем файл
       fwrite($fp, $_POST['description']);
       fclose($fp);
      }
	  $bl_name_field = ', block_name';
	  $bl_name_value = ",  '$file'";
	  $bl_upd = "block_name = '$file',";

    }
	($this->table == 'blocks') ? $description = addslashes($_POST['description'])  :  $description = addslashes($_POST['description']);
    //если это тема то создаем файл CSS с кодом и записываем его название в title
	if($this->table == 'themes'){
	  if($src['css_name']){//если редактируем блок
		$fileCss = $src['css_name']; //сохраняем имя файла
		$theme_content = $_POST['description'];
		if(file_exists($_SERVER["DOCUMENT_ROOT"].'/css/'.$fileCss)){
	     unlink($_SERVER["DOCUMENT_ROOT"].'/css/'.$fileCss); //удаляем файл далее создадим новый с тем же именем
	    }
	  }else{
	    $theme_content = file_get_contents('../css/style.css');
	  }
	  $fileCss = str_replace(" ","_",$_POST['title']).'_'.md5(time().$_POST['title']).'.css'; //генирируем имя файля
	  $css_dir = '../css/'; //папка в которой будет создаваться .css файл
	  if (!file_exists($fileCss)) {
       $fp = fopen($css_dir.$fileCss, "w"); // ("r" - считывать "w" - создавать "a" - добовлять к тексту),мы создаем файл
       fwrite($fp, $theme_content);
       fclose($fp);
      }
        $bl_name_field = ', css_name';
        $bl_name_value = ",  '$fileCss'";
        $bl_upd = "css_name = '$fileCss',";
        $description = str_replace("'",'"',$theme_content);
    }
    //Создание хэша страниц
	if($this->table == 'pages'){
	  $page_hash_field = ', page_hash';
	  $page_hash_val = ", '".md5($_POST['title'].time())."'";
	}
	//Записываем thumbs
	if($this->table == 'items' || $this->table == 'database_records'){
	  $thumbs_field = ', thumbs, thumb_small';
	  $thumbs_val = ", '$thumbs', '$thumb_small'";
	  $thumbs_upd = "thumbs='$thumbs', thumb_small='$thumb_small',";
	}
	//записывае FURL
	if($this->table == 'pages' || $this->table == 'items'  || $this->table == 'database_records'){
	  if($_POST['furl'] == NULL || $_POST['furl'] == ' ' || $_POST['furl'] == ''){
	    $furl = furl_create($_POST['title']);
      }else{
        $furl = $_POST['furl'];
      }
      $furl_field = ', furl';
	  $furl_value = ",  '$furl'";
	}

	$line1 = $line1.'activity, publ_time, photo, photo_small, description'.$thumbs_field.$bl_name_field.$page_hash_field.$furl_field;
    $line2 = $line2."'$activity', "."'$publ_time', "."'$photo', "."'$photo_small' , "."'$description'".$thumbs_val.$bl_name_value.$page_hash_val.$furl_value;
    $line_update = $line_update." photo ='$photo', photo_small ='$photo_small', $bl_upd $thumbs_upd description ='$description'";

    if($id_num > 0){
     //Запрос обновления
	 $id_num = $this->id;
     $update_sql = "UPDATE $table_name SET $line_update WHERE id=:id";
     $new_sql = $update_sql;
     $new_title = 'Обновлено';
    }else{
     //создаем новую строку
     $insert_sql = "INSERT INTO $table_name(".$line1.")"."VALUES(".$line2.")";
     $new_sql = $insert_sql;
     $new_title = 'Создано';
    }
    try {
     $post_sql = $this->mysqli->prepare($new_sql);
	 if($id_num > 0){
	  $post_sql->bind_param("i",$id_num);
	 }
     $post_sql->execute();
	 if(!$id_num && $table_name == 'core_databases'){
		$sql = $this->mysqli->prepare("SELECT MAX(id) FROM $table_name");
		$sql->execute();
		$id_info = $sql->fetch();
		$num = $id_info['MAX(id)'];
		$base_create_sql = "CREATE TABLE custom_database_$num( `id` INT NOT NULL AUTO_INCREMENT , `title` TEXT NOT NULL , `photo` TEXT NOT NULL , `photo_small` TEXT NOT NULL , `link` TEXT NOT NULL , `description` TEXT NOT NULL , `publ_time` INT NOT NULL , `order_row` INT NOT NULL , `activity` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
	    $sql = $this->mysqli->prepare($base_create_sql);
		$sql->execute();
	 }
	 //echo "good";
    }catch (PDOException $e) {
     echo 'Подключение не удалось: ' . $e->getMessage();
    }
  }

  public function delete()
	{
		//if(in_array($this->table, $WHITE_LIST))
		if(1)
		{
			$table_name = $this->table;
			$post_sql = $this->mysqli->prepare("SELECT * FROM $this->table WHERE id=?");
			$post_sql->bind_param('i', $this->id);
			$post_sql->execute();
			$post_info = $post_sql->get_result()->fetch_assoc();

			foreach($post_info as $colVal){
				if(file_exists($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$colVal) && $colVal !='')
				{
					unlink($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$colVal);
					echo $_SERVER["DOCUMENT_ROOT"]. DOMAIN .$colVal;
				}
				if(is_array(unserialize($colVal)))
				{
					$photos = unserialize($colVal);
					foreach($photos as $photo){
						unlink($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$photo);
					}
				}
				//ищем есть ли поле с названием block_name у данного поста
				if(array_search($colVal, $post_info) == 'block_name')
				{
					unlink($_SERVER["DOCUMENT_ROOT"]. DOMAIN .'/blocks/'.$colVal);
					//echo $_SERVER["DOCUMENT_ROOT"]. DOMAIN .'/blocks/'.$colVal;
				}
				if(array_search($colVal, $post_info) == 'css_name')
				{
					unlink($_SERVER["DOCUMENT_ROOT"]. DOMAIN .'/css/'.$colVal);
					//echo $_SERVER["DOCUMENT_ROOT"]. DOMAIN .'/css/'.$colVal;
				}
			}


			try {
				$post_del_sql = $this->mysqli->prepare("DELETE FROM $this->table WHERE id=?");
				$post_del_sql->bind_param('i', $this->id);
				$post_del_sql->execute();
			}catch (PDOException $e) {
				echo 'Подключение не удалось: ' . $e->getMessage();
			}
		}
   }


   public function photoChange($photo){
     $all_photos = array();
     $table_name = $this->table;
     if($_GET['action'] == 'delete'){
      $select_sql = $this->mysqli->prepare("SELECT * FROM $table_name  WHERE id=?");
      $select_sql->bind_param("i", $this->id);
      $select_sql->execute();
      $post_info = $select_sql->fetch();

      $all_photos = unserialize($post_info['photo']);
      unset($all_photos[array_search($photo, $all_photos)]);
      if(unlink($_SERVER["DOCUMENT_ROOT"].$photo)){
         echo "Фото удалено / ";
      }
     }else{
      $photo = substr($photo,0,-1); //удалям последнюю запятую
      $all_photos = explode(",",$photo); //создаем массив фоток ссылок
     }
     $photo_string = serialize($all_photos); // сериализуем
     $update_sql = "UPDATE $table_name SET photo='$photo_string ' WHERE id=?";

     try {
       $photo_update = $this->mysqli->prepare($update_sql);
	   $photo_update->bind_param("i", $this->id);
       $photo_update->execute();
	   echo "Порядок фото обновлен";
     }catch (PDOException $e) {
       echo 'Подключение не удалось: ' . $e->getMessage();
     }
   }
   
   public function title(){
      return $this->showField('title'); 
   }
   
   public function furl(){
      return $this->showField('furl'); 
   }
   
   public function post_id(){
      return $this->showField('id'); 
   }
   
   public function photo(){
      return unserialize($this->showField('photo'))[0];
   }
   
   public function photos(){
      return unserialize($this->showField('photo'));
   }
   
   public function thumbnail(){
      return $this->showField('photo_small');
   }
   
   public function publ_time(){
	  return date_format_rus($this->showField('publ_time'));
   }
   
   public function price(){
	  return $this->showField('price');
   }
   
   public function articul(){
	  return $this->showField('articul');
   }
   
   public function pack(){
	  return $this->showField('pack');
   }
   
   public function sale(){
	  return $this->showField('sale');
   }
   
   public function discount(){
	  return $this->showField('discount');
   }
   
   public function size(){
	  return $this->showField('size');
   }
   
   public function color(){
	  return $this->showField('color');
   }
   
   public function description(){
	  return $this->showField('description');
   }
       
   public function collection(){
	  return $this->showField('collection');
   }  
     
}


?>
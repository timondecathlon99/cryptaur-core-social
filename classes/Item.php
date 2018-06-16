<?php
class Item extends  Unit {

    public function setTable(){
        return 'items';
    }


    public function title(){
        return $this->showField('title');
    }

    public function category(){
        return $this->showField('category');
    }

    public function categoryFurl(){
        $category = new Category($this->category());
        return $category->furl();
    }

    public function photo(){
        return $this->photos()[0];
    }

    public function photos(){
        return json_decode($this->showField('photo'));
    }

    public function thumbs(){
        return json_decode($this->showField('thumbs'));
    }

    public function thumb(){
        return $this->thumbs()[0];
    }

    public function description(){
        return $this->showField('description');
    }



    public function createUpdate(){
        $line1 = '';
        $line2 = '';
        $line_update= '';
        $publ_time = time();
        $activity = 1;
        $uploaddir = DOMAIN . 'uploads/'; //папка для загрузки
        $table_name=$this->table;
        $id_num=$this->id;

        $arr_isset = array();
        $par_arr = $this->getAllFields();
        unset($par_arr[array_search('description', $par_arr)]);


        /* search for values in $_POST array and create array of values */
        foreach($par_arr as $arr_item){
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
        /* get INSERT and UPDATE lines */
        foreach($arr_isset as $key=>$value){
            $line1 = $line1.$key.', ';
            $line2 = $line2."'".addslashes($value)."'".', ';
            $line_update = $line_update." "."$key = '$value',";
        }

        //смотрим информацию о посте, если он есть
        $post_sql=$this->mysqli->prepare("SELECT * FROM $table_name WHERE id=?");
        $post_sql->bind_param('i', $this->id);
        $post_sql->execute();
        $src = $post_sql->get_result()->fetch_assoc();
        echo $src['title'];

//////Загрузка вторичного изображения;
        if($_FILES['file']['name'] == NULL){
            $photo_small = $src['photo_small'];
            if($this->table == 'items' || $this->table == 'database_records'){//для тамбов
                $thumb_small = $src['thumb_small'];
            }
        }else{
            if(file_exists($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$this->photo_small())){
                unlink($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$this->photo_small());
            }
            if(file_exists($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$this->thumb_small())){
                unlink($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$this->thumb_small());
            }
            $time = time();
            if($this->table == 'uploads'){
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
        if($src['block_name'] || $_POST['block_type'] == 'php'){
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
            $blocks_dir = $_SERVER["DOCUMENT_ROOT"].'/blocks/'; //папка в которой будет создаваться блок
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
        if($this->setTable() == 'themes'){
            echo "Таблицу определтли<br>";
            if($src['css_name']){//если редактируем тему
                $fileCss = $src['css_name']; //сохраняем имя файла
                $theme_content = $_POST['description'];
                echo "Редактируем....берем содержимое текстового поля<br>";
                if(file_exists($_SERVER["DOCUMENT_ROOT"].'/css/'.$fileCss)){
                    unlink($_SERVER["DOCUMENT_ROOT"].'/css/'.$fileCss); //удаляем файл далее создадим новый с тем же именем
                }
            }else{
                $theme_content = file_get_contents($_SERVER["DOCUMENT_ROOT"].'/css/style.css');
            }


            $fileCss = str_replace(" ","_",$_POST['title']).'_'.md5(time().$_POST['title']).'.css'; //генирируем имя файля
            $css_dir = '/css/'; //папка в которой будет создаваться .css файл
            if (!file_exists($fileCss)) {
                $fp = fopen($_SERVER["DOCUMENT_ROOT"].$css_dir.$fileCss, "w");
                echo $_SERVER["DOCUMENT_ROOT"].$css_dir.$fileCss;// ("r" - считывать "w" - создавать "a" - добовлять к тексту),мы создаем файл
                if(fwrite($fp, $theme_content)){
                    fclose($fp);
                    echo "Файл не существует и его создаем<br>";
                }
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


        if($this->id > 0){
            /* Запрос обновления */
            $sql = "UPDATE $this->table SET $line_update WHERE id='$id_num'";
        }else{
            /* запрос создания */
            $sql = "INSERT INTO $this->table(".$line1.")"."VALUES(".$line2.")";
        }

        try {
            $post_sql = $this->mysqli->prepare($sql);
            $post_sql->execute();
            //echo "good";
        }catch (Exception $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }

    }

    public function getComments(){
        $sql = $this->pdo->prepare("SELECT * FROM comments WHERE comment_group='2' AND record_id=:id");
        $sql->bindParam(':id',$this->id);
        $sql->execute();
        return $sql->fetchAll();
    }

    public function getRating(){
        $sum = 0;
        $num = 0;
        $sql = $this->pdo->prepare("SELECT * FROM comments WHERE record_id=:id   ");
        $sql->bindParam(':id',$this->itemId());
        $sql->execute();
        while($res = $sql->fetch(PDO::FETCH_LAZY)){
            $sum = $sum+$res->rating;
            $num++;
        }
        $rating = round($sum/$num);
        if($rating >0){
            $sql = $this->pdo->prepare("SELECT * FROM rating  WHERE price='".$rating."'");
            $sql->execute();
            $star = $sql->fetch(PDO::FETCH_LAZY);
            return $rating.' ('.$star->title.')';
        }else{
            return 'нет оценок';
        }

    }

    public function metaTitle(){
        if($this->showField('content_title')){
            return $this->showField('content_title');
        }else{
            return $this->showField('title');
        }
    }

    public function metaKeywords(){
        if($this->showField('content_keywords')){
            return $this->showField('content_keywords');
        }else{
            return $this->showField('title');
        }
    }

    public function metaDescription(){
        if($this->showField('content_description')){
            return $this->showField('content_description');
        }else{
            return $this->showField('title');
        }
    }

    public function metaIcon(){
        return unserialize($this->showField('photo'))[0];
    }

    public function itemId(){
        return $this->showField('id');
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


}


?>
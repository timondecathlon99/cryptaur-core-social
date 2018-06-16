<?
class Post extends Unit
{
    protected $table;
    protected $author;

    public function getTable(string $table){
        $this->table =  $table;
    }

    public function setTable(){
        return $this->table;
    }

    public function setAuthor(){
        $this->author =  $this->currentUser();
    }

    public function currentUser(){
        $member = new Member($_COOKIE['member_id']);
        return $member->member_id();
    }

    public function permissions(){
        return json_decode($this->showField('permissions'));
    }

    public function canSee(){
        $user = new Member($_COOKIE['member_id']);
        if(in_array($user->group_id(),$this->permissions())){
            return true;
        }else{
            return false;
        }
    }


    public function createUpdate(){
        $line1 = '';
        $line2 = '';
        $line_update= '';
        $publ_time = time();
        $uploaddir = '/'.PHOTO_FOLDER.'/'; //папка для загрузки
        $arr_isset = array();
        $par_arr = $this->getTableColumnsNames();
        unset($par_arr[array_search('description', $par_arr)]);


        /* search for values in $_POST array and create array of values */
        foreach($par_arr as $arr_item){
            if($_POST[$arr_item] != NULL){
                if(is_array($_POST[$arr_item])){ //проверяем не массив ли это
                    $arr_isset[$arr_item] = json_encode($_POST[$arr_item], JSON_UNESCAPED_UNICODE);
                }else{
                    if($arr_item == 'password'){
                        $arr_isset[$arr_item] = crypt($_POST[$arr_item]); //криптуем пароль
                    }else{
                        $arr_isset[$arr_item] = $_POST[$arr_item];
                    }
                }
            }
        }
        var_dump($arr_isset);
        /* get INSERT and UPDATE lines */
        foreach($arr_isset as $key=>$value){
            $line1 = $line1.$key.', ';
            $line2 = $line2."'".addslashes($value)."'".', ';
            $line_update = $line_update." "."$key = '$value',";
        }
        echo $line1;
        //смотрим информацию о посте, если он есть
        $src = $this->show();

//////Загрузка вторичного изображения;
        if($_FILES['file']['name'] == NULL){
            $photo_small = $src['photo_small'];
            if($this->setTable() == 'items' || $this->setTable() == 'database_records'){//для тамбов
                $thumb_small = $src['thumb_small'];
            }
            echo 222;
        }else{
            if(file_exists($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$this->photoSmall())){
                unlink($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$this->photoSmall());
            }
            if(file_exists($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$this->thumbSmall())){
                unlink($_SERVER["DOCUMENT_ROOT"]. DOMAIN .$this->thumbSmall());
            }
            $time = time();
            if($this->setTable() == 'uploads'){
                $ext = '.'.$_POST['upload_type'];
            }else{
                $ext = $this->findExtention($_FILES['file']['name']);
            }
            $file_sec_new_name = md5($_FILES['file']['name'].$time).$ext;
            $uploadfile_sec = $uploaddir.$file_sec_new_name;
            echo $uploadfile_sec;
            if(move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$uploadfile_sec)){
                $photo_small = $uploaddir.$file_sec_new_name;
                if($this->setTable() == 'items' || $this->setTable() == 'database_records'){//для тамбов
                    $this->createThumbnail($file_sec_new_name);
                    $thumb_small =  $uploaddir.'thumb_'.$file_sec_new_name;
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
            $photo = json_encode($this->photos());
            if($this->setTable() == 'items' || $this->setTable() == 'database_records'){//для тамбов
                $thumbs = json_encode($this->thumbs());
            }
        }else{
            //удаляем фотки и строку
            $photos_old = $this->photos();
            foreach($photos_old as $photo_old){
                if(file_exists($_SERVER["DOCUMENT_ROOT"].$photo_old)){
                    unlink($_SERVER["DOCUMENT_ROOT"].$photo_old);
                }
            }
            if($this->table == 'items' || $this->table == 'database_records'){//для тамбов
                //удаляем thumbnails
                $thumbs_old = $this->thumbs();
                foreach($thumbs_old as $thumb_old){
                    if(file_exists($_SERVER["DOCUMENT_ROOT"].$thumb_old)){
                        unlink($_SERVER["DOCUMENT_ROOT"].$thumb_old);
                    }
                }
            }
            //загружаем новые и создаем ссылки
            for($i =0; $i < 20;$i++){
                $file_new_name = md5($_FILES['files']['name'][$i].$publ_time).$this->findExtention($_FILES['files']['name'][$i]);
                $uploadfile = $uploaddir.$file_new_name;
                if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'].$uploadfile)) {
                    $photo[$i] = $uploaddir.$file_new_name;
                    if($this->table == 'items' || $this->table == 'database_records'){//для тамбов
                        $this->createThumbnail($file_new_name);
                        $thumb[$i] =  $uploaddir.'thumb_'.$file_new_name;
                    }
                }
            }
            $photo = json_encode($photo);
            if($this->setTable() == 'items' || $this->setTable() == 'database_records'){
                $thumbs = json_encode($thumb);
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

        ($this->setTable() == 'blocks') ? $description = addslashes($_POST['description'])  :  $description = addslashes($_POST['description']);

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
        if($this->setTable() == 'pages'){
            $page_hash_field = ', page_hash';
            $page_hash_val = ", '".md5($_POST['title'].time())."'";

        }

        if($this->author){
            $author_field = ', author';
            $author_val = ", '$this->author'";
        }

        if(1){
            $photos_field = ', photo, photo_small';
            $photos_val = ", '$photo', '$photo_small'";
            $photos_upd = "photo='$photo', photo_small='$photo_small',";
        }
        //Записываем thumbs
        if($this->setTable() == 'items' || $this->setTable() == 'database_records'){
            $thumbs_field = ', thumbs, thumb_small';
            $thumbs_val = ", '$thumbs', '$thumb_small'";
            $thumbs_upd = "thumbs='$thumbs', thumb_small='$thumb_small',";
            $update_time = "last_update='$publ_time',";
        }
        //записывае FURL
        if($this->setTable() == 'pages' || $this->setTable() == 'items'  || $this->setTable() == 'database_records'){
            if($_POST['furl'] == NULL || $_POST['furl'] == ' ' || $_POST['furl'] == ''){
                $furl = furl_create($_POST['title']);
            }else{
                $furl = $_POST['furl'];
            }
            $furl_field = ', furl';
            $furl_value = ",  '$furl'";

        }

        $line1 = $line1.'publ_time, description'.$photos_field.$thumbs_field.$bl_name_field. $author_field.$page_hash_field.$furl_field;
        $line2 = $line2."'$publ_time', "."'$description'".$photos_val.$thumbs_val.$bl_name_value.$author_val.$page_hash_val.$furl_value;
        $line_update = $line_update."$update_time $bl_upd $photos_upd $thumbs_upd description ='$description'";

        if($this->postId() > 0){
            /* Запрос обновления */
            $sql = "UPDATE $this->table SET $line_update WHERE id=:id";
        }else{
            /* запрос создания */
            $sql = "INSERT INTO $this->table(".$line1.")"."VALUES(".$line2.")";
        }
        echo $sql;
        try {
            $post_sql = $this->pdo->prepare($sql);
            $post_sql->bindParam(':id',$this->postId());
            if($post_sql->execute()){
                echo '$good';
            }
            //echo "good";
        }catch (Exception $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }

    }

    public function createThumbnail($filename) {

        $final_width_of_image = 700;
        $path_to_image_directory = $_SERVER["DOCUMENT_ROOT"].'/'.PHOTO_FOLDER.'/'; //Папка, куда будут загружаться полноразмерные изображения
        $path_to_thumbs_directory = $_SERVER["DOCUMENT_ROOT"].'/'.PHOTO_FOLDER.'/';//Папка, куда будут загружаться миниатюры

        if(preg_match('/[.](jpg)$/', $filename)) {
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);
        } else if (preg_match('/[.](gif)$/', $filename)) {
            $im = imagecreatefromgif($path_to_image_directory . $filename);
        } else if (preg_match('/[.](png)$/', $filename)) {
            $im = imagecreatefrompng($path_to_image_directory . $filename);
        }
        //Определяем формат изображения

        //Получаем высоту и ширину исходного изображения
        $ox = imagesx($im);
        $oy = imagesy($im);
        echo "Ширина: ".$ox;
        //задаем размеры холста
        $nx = $final_width_of_image;
        $ny = floor($oy * ($final_width_of_image / $ox));

        $nm = imagecreatetruecolor($nx, $ny); //создаем новый холст с заданными параметрами
        imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy); //переносим исходник на холст
        imagejpeg($nm, $path_to_thumbs_directory .'thumb_'.$filename);
    }



    public function delete()
    {
        //if(in_array($this->table, $WHITE_LIST))
        if(1)
        {
            $post_info = $this->show();

            foreach($post_info as $colVal){
                if(file_exists($_SERVER["DOCUMENT_ROOT"].$colVal) && $colVal !='')
                {
                    unlink($_SERVER["DOCUMENT_ROOT"].$colVal);
                }
                if(is_array(json_decode($colVal)))
                {
                    $photos = json_decode($colVal);
                    foreach($photos as $photo){
                        unlink($_SERVER["DOCUMENT_ROOT"].$photo);
                    }
                }
                /* ищем есть ли поле с названием block_name у данного поста */
                if(array_search($colVal, $post_info) == 'block_name')
                {
                    unlink($_SERVER["DOCUMENT_ROOT"].'/blocks/'.$colVal);
                }
                if(array_search($colVal, $post_info) == 'css_name')
                {
                    unlink($_SERVER["DOCUMENT_ROOT"].'/css/'.$colVal);
                }
            }

            $this->deleteLine();
        }
    }


    public function photoChange(string $photo){

        if($_GET['action'] == 'delete'){
            $photo = parse_url($photo);
            $photo = $photo['path'];
            $all_photos = $this->photos();
            $all_thumbs = $this->thumbs();
            unset($all_photos[array_search($photo, $all_photos)]);
            unset($all_thumbs[array_search('thumb_'.explode('/',$photo)[2], $all_thumbs)]);
            sort($all_photos);
            sort($all_thumbs);
            if(file_exists($_SERVER["DOCUMENT_ROOT"].$photo)){
                unlink($_SERVER["DOCUMENT_ROOT"].$photo);
                echo "Фото удалено / ";
            }
            if(file_exists($_SERVER["DOCUMENT_ROOT"].'/uploads/thumb_'.explode('/',$photo)[2])){
                unlink($_SERVER["DOCUMENT_ROOT"].'/uploads/thumb_'.explode('/',$photo)[2]);
                echo "Thumbnail удалено / ";
            }
        }else{
            $photo = substr($photo,0,-1);//удалям последнюю запятую
            $all_photos = explode(",",$photo); //создаем массив фоток ссылок
            foreach($all_photos as $key=>$value){
                $all_photos[$key] = parse_url($value)['path'];
                $all_thumbs[$key] = '/uploads/thumb_'.explode('/',$all_photos[$key])[2];
            }

        }
        $photo_string = json_encode($all_photos); // сериализуем
        $thumb_string = json_encode($all_thumbs); // сериализуем
        $photo_update_sql = "UPDATE $this->table SET photo='$photo_string ' WHERE id=?";
        $thumb_update_sql = "UPDATE $this->table SET thumbs='$thumb_string ' WHERE id=?";

        try {
            $update = $this->mysqli->prepare($photo_update_sql);
            $update->bind_param("i", $this->id);
            $update->execute();
            echo "Порядок фото обновлен";
        }catch (Exception $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }

        try {
            $update = $this->mysqli->prepare($thumb_update_sql);
            $update->bind_param("i", $this->id);
            $update->execute();
            echo "Порядок фото обновлен";
        }catch (Exception $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
    }

    public function findExtention($file){
        if(preg_match('/[.](jpg)$/', $file)) {
            $ext = '.jpg';
        } elseif (preg_match('/[.](gif)$/', $file)) {
            $ext = '.gif';
        } elseif (preg_match('/[.](png)$/', $file)) {
            $ext = '.png';
        }
        return $ext;
    }



    public function title(){
        return $this->showField('title');
    }

    public function furl(){
        return $this->showField('furl');
    }

    public function postId(){
        return $this->showField('id');
    }

    public function author(){
        return $this->showField('author');
    }

    public function supervisor(){
        return $this->showField('broker');
    }

    public function photos(){
        return json_decode($this->showField('photo'));
    }

    public function thumbs(){
        return json_decode($this->showField('thumbs'));
    }

    public function photo(){
        return $this->photos()[0];
    }

    public function photoSmall(){
        return $this->showField('photo_small');
    }

    public function thumbSmall(){
        return $this->showField('thumb_small');
    }

    public function thumbnail(){
        return $this->showField('photo_small');
    }

    public function publTime(){
        return date_format_rus($this->showField('publ_time'));
    }

    public function lastUpdate(){
        return date_format_rus($this->showField('update_time'));
    }

    public function price(){
        return $this->showField('price');
    }

    public function articul(){
        return $this->showField('articul');
    }

    public function isActive(){
        if($this->showField('activity') == 1){
            return true;
        }else{
            return false;
        }
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
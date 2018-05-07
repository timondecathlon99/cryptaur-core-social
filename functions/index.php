<?
//Приведение даты к читаемому виду
function date_format_rus($date){
 $month_array = array (
  1 => 'Января',
  2 => 'Февраля',
  3 => 'Марта',
  4 => 'Апреля', 
  5 => 'Мая',
  6 => 'Июня',   
  7 => 'Июля', 
  8 => 'Августа', 
  9 => 'Сентября',
  10 => 'Октября',
  11 => 'Ноября',
  12 => 'Декабря'
 );
  
  $time = time();
  $tm = date('H:i', $date);
  $d = date('d', $date);
  $m = date('m', $date);
  $y = date('Y', $date);

  if($time - $date < 60){
		 $text =  "Только что"; 
  }elseif($time - $date > 60 && $time - $date < 3600 ){
		 $minute = round(($time - $date)/60);
	     if($minute < 55){
	       if($minute % 10 == 1 && $minute != 11 ){
		    $text =  "$minute минуту назад";
	       }elseif($minute % 10 > 1  && $minute% 10 < 5  ){
		    $text =  "$minute минуты назад";
	       }else{
		    $text =  "$minute минут назад"; 
	       }
         } 
  }elseif($time - $date > 3600){
		 $last = round(($time - $date)/3600);
         if( ($last < 13) && ($d.$m.$y == date('dmY',$time))){ 
	      if($last % 10 == 1 && $last != 11 ){
		     $text =  "$last час назад";
	      }elseif($last % 10 > 1  && $last% 10 < 5  ){
		     $text =  "$last часа назад";
	      }else{
		     $text =  "$last часов назад"; 
	      }
         }elseif($d.$m.$y == date('dmY',$time)){
	      $text =  "Сегодня в $tm";
         }elseif($d.$m.$y == date('dmY', strtotime('-1 day'))){
	      $text =  "Вчера в $tm";
         }else{
	       $text =  $d.' '.$month_array[(int)($m)].' '.$y;
         }
  }else{
		 $text =  $d.' '.$month_array[(int)($m)].' '.$y;
  }

  return $text; 
}

//Пеервод цвета на русский
function color_detect($color){
	  if(substr_count($color, 'зеленый') > 0){
	     $color = 'green';
	  }elseif(strpos($color, 'синий') !== FALSE){
	     $color = 'blue';
	  }elseif(substr_count($color, 'оранжевый') > 0){
	     $color = 'orange';
	  }elseif(substr_count($color, 'серый') > 0){
	     $color = 'grey';
	  }elseif(substr_count($color, 'золотой') > 0){
	     $color = 'gold';
	  }elseif(substr_count($color, 'красный') > 0){
	     $color = 'red';
	  }elseif(substr_count($color, 'коричневый') > 0){
	     $color = 'brown';
	  }elseif(substr_count($color, 'бежевый') > 0){
	     $color = 'tan';
	  }elseif(substr_count($color, 'белый') > 0){
	     $color = 'rgb(240, 240, 240)';
	  }elseif(substr_count($color, 'розовый') > 0){
	     $color = '#f6a59c';
	  }elseif(substr_count($color, 'голубой') > 0){
	     $color = 'lightblue';
	  }elseif(substr_count($color, 'зеленый') > 0){
	     $color = 'green';
	  }elseif(substr_count($color, 'желтый') > 0){
	     $color = 'yellow';
	  }elseif(substr_count($color, 'фиолетовый') > 0){
	     $color = '#8A2BE2';
	  }elseif(substr_count($color, 'серебряный') > 0){
	     $color = 'silver';
	  }elseif(substr_count($color, 'черный') > 0){
	     $color = 'black';
	  }else{
	     $color = 'white';
	  }
	  return $color;
}


//Запазной вариант для проверки хэша
if(!function_exists('hash_equals'))
{
    function hash_equals($str1, $str2)
    {
        if(strlen($str1) != strlen($str2))
        {
            return false;
        }
        else
        {
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--)
            {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }
}

//Транслитерация
function furl_create($string){
      $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    $str=strtr($string, $converter);
	
    // в нижний регистр
    $str = strtolower($str);
    // заменям все ненужное нам на "-"
    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
    // удаляем начальные и конечные '-'
    $str = trim($str, "-");
    return $str;
  }
//Создание thumbnails
function createThumbnail($filename) {
	
	
  $final_width_of_image = 700;
  $path_to_image_directory = '../uploads/'; //Папка, куда будут загружаться полноразмерные изображения
  $path_to_thumbs_directory = '../uploads/';//Папка, куда будут загружаться миниатюры  
  
  //echo "В функию передается файл: ".$path_to_image_directory . $filename;
  
  //$dick = $path_to_image_directory . $filename;
  
  //echo "Это картинка в функции: <img style='width : 300px;' src='$path_to_image_directory$filename' alt='$path_to_image_directory . $filename'/>";
  /*
  if(file_exists($dick)){
	  echo 'hui';
  }else{
	 echo $dick.'  doesnt exist';  
  }
	*/  
  
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
  /*
  if(!file_exists($path_to_thumbs_directory)) {
  if(!mkdir($path_to_thumbs_directory)) {
  die("Возникли проблемы! попробуйте снова!");
  } 
  }
  */
  if(imagejpeg($nm, $path_to_thumbs_directory .'thumb_'.$filename)){
	 echo $path_to_thumbs_directory .'thumb_'.$filename;  
  }
  
  $tn = '<img src="' . $path_to_thumbs_directory . 'thumb_'.$filename . '" alt="image" />';
  $tn .= '<br />Поздравляем! Ваше изображение успешно загружено и его миниатюра удачно выполнена. Выше Вы можете просмотреть результат:';
  //echo $tn;
  }//Сжимаем изображение, если есть оишибки, то говорим о них, если их нет, то выводим получившуюся миниатюру
  
?>
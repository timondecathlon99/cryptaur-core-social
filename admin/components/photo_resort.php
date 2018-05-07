<?include('./../../global_pass.php');?>
<? 

$table = $_GET['category'];
$id = $_GET['id'];
$photo = $_GET['photo'];

/*
echo 'Порядок фото изменен';
$post = new Post($_GET['category'], $_GET['id'],$pdo);
if($post->photoChange($photo)){
 echo 'Порядок фото изменен';
}  
*/
$all_photos = array();

if($_GET['action'] == 'delete'){
  $select_sql = $pdo->prepare("SELECT * FROM $table WHERE id= :id");
  $select_sql->bindParam(":id", $id);
  $select_sql->execute();
  
  $post_info = $select_sql->fetch();
  
  $all_photos = unserialize($post_info['photo']);
  unset($all_photos[array_search($photo, $all_photos)]);
  if(unlink($_SERVER["DOCUMENT_ROOT"].$photo)){
    echo $_SERVER["DOCUMENT_ROOT"].$photo."/Фото удалено / ";
  }
}else{
  $photo = substr($photo,0,-1); //удалям последнюю запятую
  $all_photos = explode(",",$photo); //создаем массив фоток ссылок
}


$photo_string = serialize($all_photos); // сериализуем


$update_sql = "UPDATE $table SET photo='$photo_string ' WHERE id= :id";

try {
    $photo_update = $pdo->prepare($update_sql);
	$photo_update->bindParam(":id", $id);
    $photo_update->execute();
	echo "Порядок фото обновлен";
  }catch (PDOException $e) {
    echo 'Подключение не удалось: ' . $e->getMessage();
  } 

?> 
	

  
	
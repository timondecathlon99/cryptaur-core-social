<?setcookie(); ?>
<?include_once('../global_pass.php');?>
<?include_once('distance_count.php'); ?>
<?php

           

           
if($_GET['shop_id'] != ''  ){
	$shop_id = "AND id='".(int)$_GET['shop_id']."'";
}

if($_GET['user_latitude'] !='' && $_GET['user_longitude'] !=''){
	$user_lat = (double)$_GET['user_latitude'];
	$user_lon = (double)$_GET['user_longitude'];
	session_start();
	$_SESSION['user_lat'] = $user_lat;
	$_SESSION['user_lon'] = $user_lon;
	//setcookie("user_lat", $user_lat);
	//setcookie("user_lon", $user_lon);
	
  $near_shops_sql = $pdo->prepare("SELECT * FROM $table_shops" );
  $near_shops_sql->execute();
  $shops_id = array();
  $j = 0;
  while($near_shops_info = $near_shops_sql->fetch())
  {
   $near_lat = $near_shops_info['latitude'];
   $near_lon = $near_shops_info['longitude'];
   if((calculateTheDistance($user_lat, $user_lon, $near_lat, $near_lon) < $radius) ){
      $shops_id[$j] = $near_shops_info['id'];
	  $j++;	
   }
  }
  
  foreach($shops_id as $shops_id_elem){$near_shops_str = $near_shops_str."'$shops_id_elem',";}
  $near_shops_str = substr($near_shops_str,0,-1);
  $near_shops_arr = "AND id IN($near_shops_str)";
  //echo $near_shops_arr;
  //var_dump($shops_id);
}  

if($_GET['city_map'] != ''  ){
	$city_map = "AND city='".$_GET['city_map']."'";
	$source = $pdo->prepare("SELECT * FROM $table_shops WHERE city!='md' AND city= :city_map $shop_id $near_shops_arr" ); 
    $source->bindParam(':city_map', $_GET['city_map']);
}else{
    $source = $pdo->prepare("SELECT * FROM $table_shops WHERE city!='md' $shop_id $near_shops_arr" ); 
}
$i = 0;
$shops = array();

$source->execute();
//echo "SELECT * FROM $table WHERE city!='md' $city_map $shop_id $near_shops_arr";

while($src = $source->fetch())
{
  $id = $src['id'];
  $title = $src['title'];
  $lat = $src['latitude'];
  $lon = $src['longitude'];
  $city = $src['city'];
  $address = $src['address'];
  $phone = $src['phone'];
  $working_time = $src['working_time'];
  $description = $src['description'];
  $photo = unserialize($src['photo']);
  $photo = $photo[0];
  $shops[$i] = $id.'&'.$title.'&'.$lat.'&'.$lon.'&'.$city.'&'.$address.'&'.$phone.'&'.$working_time.'&'.$description.'&'.$photo;
  $i++;
}
echo json_encode($shops);


?>

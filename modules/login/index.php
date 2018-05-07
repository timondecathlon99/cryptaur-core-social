<? include_once('../../global_pass.php');

$member = new Member($_COOKIE['member_id'],$pdo);
//echo $member->member_id();
$member->loginCheck($_POST['login'], $_POST['pass']);


if($_POST['no_reload'] != 1){
  header("Location: ".$_SERVER['HTTP_REFERER']);
}
if($member->member_id() > 0){
  echo 1; 
}else{
  echo 2;
}

//echo $_POST['login'];
//echo $_POST['pass'];
//var_dump($pdo);
?>
<?
include('../global_pass.php');
function my_autoloader($class) {
    require_once './../classes/' . $class . '.php';  
}
spl_autoload_register('my_autoloader');

$member = new Member($_COOKIE['member_id']);
$member->loginCheck($_POST['login'], $_POST['pass']);
header("Location: $domain/admin/index.php");

 
?>
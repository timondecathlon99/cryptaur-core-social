<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 07.05.2018
 * Time: 9:50
 */
require_once('./../../global_pass.php');
require_once('../../classes/autoload.php');

$PAR_ARR = array();
$columns_sql= $pdo->prepare("SHOW COLUMNS FROM ".$_GET['category']."");
$columns_sql->execute();
$i = 0;
while($column = $columns_sql->fetch()){
    $PAR_ARR[$column['Field']] = $column['Field'];
    $i++;
}
unset($PAR_ARR[array_search('description', $PAR_ARR)]);

$logedUser = new Member($_COOKIE['member_id']);
if($logedUser->is_valid()){
    $post = new Post($_GET['id']);
    $post->getTable($_GET['category']);
    $post->createUpdate($PAR_ARR);
    if($_GET['category'] == 'leftovers' || $_GET['category'] == 'users'){
        header("Location: ".$_SERVER['HTTP_REFERER']);
    }else{
        $new_loc = "/admin/index.php?action=show&type=".$_GET['category'].'#'.$_GET['num'];
        header("Location: ".$new_loc);
    }
}else{
    header("Location: ".$_SERVER['HTTP_REFERER']);
    echo "F*ck you, hacker=)";
}


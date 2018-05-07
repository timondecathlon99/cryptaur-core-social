<?
include('../../global_pass.php');
$column = $_GET['column_info'];


$column = explode('~', $column);

$column_name = $column[0]; //получем айди поля
$table =  $column[1];    //получаем название таблицы 


	 $del_sql = $pdo->prepare("ALTER TABLE $table DROP $column_name");
     if($del_sql->execute()){	
       echo 'Столбец удален';	
     }









?>
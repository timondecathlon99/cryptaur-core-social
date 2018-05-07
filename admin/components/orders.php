
  <div class='desktop'>
      <div class='desktop_form'>
	   <table class='order_table'> 
          <tr>
            <th>Номер заказа</th>
			<th>Дата создания</th>
			<th>Имя</th>
            <th style='width : 150px;'>Телефон</th>
            <th>E-mail</th>
			<th>Менеджер</th>
			<th>Статус</th>
          </tr>
<?php 
   //$sort_par = $_GET['sort_par'];  
   $sort_par = 'publ_time';  
   $order_sql = $pdo->prepare("SELECT * FROM $table_orders ");		
   $order_sql->execute();
   while($order = $order_sql->fetch()){ ?>
	    
	      <div class='desktop_form_unit'>
              <tr> 
			    <td><a href='post_form.php?category=<?=$table_orders?>&id=<?=$order['id']?>&action=change&title=изменить'><b>#<?=$order['id']?></b></td>
				<td><?=date('d/m/y в G:i',$order['publ_time'])?></td>
				<?$user_id = $order['user_id'];
				  $user_sql = $pdo->prepare("SELECT * FROM $table_users WHERE id='$user_id'");		
                  $user_sql->execute();
				  $user = $user_sql->fetch();
				?>
				<td><?=$user['title']?></td>
				<td><a href='post_form.php?category=<?=$table_users?>&id=<?=$user['id']?>&action=change&title=изменить'><b><?=$user['phone']?></b></a></td>
				<td><?=$user['email']?></td>
				
				  <?
				     if($user['respect_points'] > 0){
					   $color = 'limegreen';
					 }elseif($src['respect_points'] < 0){
					   $color = '#ea0e0e';
					 }
					 else{
					   $color = '#EEEFF5';
					 }
				  
				  ?>
				<td></td>  
				<td style='background: <?=$color?> '>Новый</td>
			
		     <td>
		       <a  class='delete_post' href='javascript: void(0);' name='<?=$order['id']?>' title='<?=$_GET['type']?>' ><i class="fa fa-times" aria-hidden="true"></i></a>
		     </td> 
		   </tr>
	      </div>
  <? } ?>
       </table>    
     </div>  
  </div>

  
	
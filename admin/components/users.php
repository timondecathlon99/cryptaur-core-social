
  <div class='desktop'>
      <div class='desktop_form'>
	   <table class='order_table'>
          <tr>
            <th>Имя</th>
            <th style='width : 150px;'>Телефон</th>
            <th>E-mail</th>
            <th>IP-адрес</th>
			<th>Кол-во заказов</th>
			<th>Процент выкупа</th>
            <th>Очки лояльности</th>
			<th>Статус</th>
            <th>Дата регистрации</th>
          </tr>
<?php 
   //$sort_par = $_GET['sort_par'];  
   $sort_par = 'publ_time';
   (isset($_GET['member_group_id'])) ? $member_group_id= "%".$_GET['member_group_id']."%" : $member_group_id = "%%"; 
   //(isset($_GET['respect_points'])) ? $respect_points = "%".$_GET['respect_points']."%" : $respect_points = "%%";    
   $source = $pdo->prepare("SELECT * FROM $table_users WHERE member_group_id LIKE :member_group_id ORDER BY $sort_par DESC");		
   $source->bindParam(':member_group_id', $member_group_id);
   //$source->bindParam(':respect_points', $respect_points);
   $source->execute();
   while($src = $source->fetch()){ ?>
	    
	      <div class='desktop_form_unit'>
              <tr>   
			    <td><a href='post_form.php?category=<?=$table_users?>&id=<?=$src['id']?>&action=change&title=изменить'><b><?=$src['title']?></b></a></td>
				<td><b><?=$src['phone']?></b></td>
				<td><?=$src['email']?> <?=$src['publ_time']?></td>
				<td><?=$src['ip_address']?></td>
				<td><?=(int)$src['order_count']?></td>
				<td><?=($src['order_count'] > 0) ? (int)(round(($src['order_success_count'] / $src['order_count'])*100, 2)) : '' ?>%</td>
				<td><?=(int)$src['respect_points']?></td>
				
				  <?
				     if($src['respect_points'] > -1){
					   $color = '#93f409';
					 }elseif($src['respect_points'] < -1){
					   $color = '#ea0e0e';
					 }
					 else{
					   $color = '#EEEFF5';
					 }
				  
				  ?>
				<td style='background: <?=$color?> '></td>
				<td></td>
		    <!--<form class='del_form' action='delete_post.php' method='GET'>
		     <td>
			  <input name='category' type='hidden' value='<?=$table_users?>'></input>
		      <input type='hidden' name='id' value='<?=$src['id']?>'></input>
		      <button>Удалить</button>
			 </td> 
	       </form>-->
		     <td>
		       <a  class='delete_post' href='javascript: void(0);' name='<?=$src['id']?>' title='<?=$_GET['type']?>' ><i class="fa fa-times" aria-hidden="true"></i></a>
		     </td> 
		   </tr>
	      </div>
  <? } ?>
       </table>    
     </div>  
  </div>

  
	
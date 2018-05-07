
      <div class='' style='width: 100%; text-align: center;'> 
	   <table class='order_table' style='max-width: 1200px; display: inline-block; '>
          <tr>
            <th>telegram ID</th>
            <th>Имя в telegram</th>
            <th>Дата подписки</th>
          </tr>
  <? 
  $sql = $pdo->prepare("SELECT * FROM telegram_users WHERE bot_id='".$_GET['id']."' ");
  $sql->execute();
  while($tel_user = $sql->fetch()){?>
	      <div class='desktop_form_unit'>
              <tr>   
			    <td><b><?=$tel_user['room_id']?></b><input class='chat_check' type='checkbox'></input></td>
				<td><b><?=$tel_user['title']?></b></td>
				<td><?=$tel_user['publ_time']?></td>
		        <td>
		         <a  class='delete_post' href='javascript: void(0);' name='<?=$tel_user['id']?>' title='<?=$_GET['type']?>' ><i class="fa fa-times" aria-hidden="true"></i></a>
		        </td> 
		   </tr>
	      </div>
  <? } ?>
       </table>    
     </div>  

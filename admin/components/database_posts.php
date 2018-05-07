<div>
      <div class='desktop_form' style='max-width: 1200px;'>
	   <table class='order_table'>
          <tr>
            <th>id</th>
            <th>Заголовок</th>
            <th style='width : 150px;'>Содержимое</th>
			<th>Время публикации</th>
			<th>Статус</th>
          </tr>
<? 
   $source = $pdo->prepare("SELECT * FROM database_records");		
   $source->execute();
   while($src = $source->fetch()){ ?>
	    
	      <div class='desktop_form_unit'>
              <tr>   
			    <td><a href='post_form.php?category=database_records&id=<?=$src['id']?>&action=change&title=изменить'><b><?=$src['title']?></b></a></td>
				<td><b><?=$src['title']?></b></td>
				<td><?=mb_strimwidth($src['description'], 0, 50, "...")?> </td>
				<td><?=date_format_rus($src['publ_time'])?></td>
				<td>NEW</td>
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
         <tr>
		  
		 </tr>
       </table> 
        <div class='Btn orangeBtn button_small '>
		   <a  href='post_form.php?category=database_records&id=<?=$src['id']?>&action=change&title=изменить'><b>Создать</b></a>
		</div>	   
     </div>  
</div>
  
	
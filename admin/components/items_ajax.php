<? require_once('./../../global_pass.php') ;?>
<?   $sort_par = 'publ_time';
   (isset($_GET['color'])) ? $color = "%".$_GET['color']."%" : $color = "%%";				   
   (isset($_GET['itemtype'])) ? $type = "%".$_GET['itemtype']."%" : $type = "%%";				   
   (isset($_GET['collection'])) ?  $collection = "%".$_GET['collection']."%" : $collection = "%%";
   (isset($_GET['category'])) ?  $category = "%".$_GET['category']."%" : $category = "%%";
   (isset($_GET['articul'])) ? $articul = "%".$_GET['articul']."%" : $articul = "%%";
  
   $source = $pdo->prepare("SELECT * FROM $table_items $active AND articul LIKE :articul AND type LIKE :type AND color LIKE :color AND collection LIKE :collection AND category LIKE :category ORDER BY id ASC");		
   $source->bindParam(':articul', $articul);
   $source->bindParam(':color', $color);
   $source->bindParam(':type', $type);
   $source->bindParam(':collection', $collection);
   $source->bindParam(':category', $category);
   $source->execute();
   $i = 0;
   while($src = $source->fetch()){
        $photo = unserialize($src['photo']);
	    $i++;   ?>
              <tr> 
			    <td><?=$i?></td>
				<td><div class='item_preview' style=' background: url(<?=$domain.$photo[0]?>);'><a href='post_form.php?category=<?=$table_items?>&id=<?=$src['id']?>&action=change&title=изменить'></a><div><div class='item_preview_big'></div></td>
			    <td><a href='post_form.php?category=<?=$table_items?>&id=<?=$src['id']?>&action=change&title=изменить'><b><?=$src['articul']?></b></a></td>
			    <td><?=$src['title']?></td>
				<td><?=$src['color']?></td>
				<td><?=$src['amount']?></td>
				<td><?=$src['price']?> руб.</td>
				<td><?=$src['collection']?></td>
				<? 
				   $articul = $src['articul'];
				   $leftovers= $pdo->prepare("SELECT * FROM $table_leftovers $active AND articul = '$articul'");		
                   //$source->bindParam(':city', $city);
                   $leftovers->execute();
				   $num = $leftovers->fetch(); ?>
				<td>
		         <a  class='delete_post' href='javascript: void(0);' name='<?=$src['id']?>' title='<?=$_GET['type']?>' ><i class="fa fa-times" aria-hidden="true"></i></a>
		        </td> 
		      </tr>
	      
  <? } ?>
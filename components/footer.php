 

<? require_once('components/menu/footer/index.php'); ?> 
<? require_once('components/menu/modals/index.php'); ?>
 
<!--Place for footer embedded_code-->
<?
      $embed_sql = $pdo->prepare("SELECT * FROM embedded_code $active AND in_header!='1' ORDER BY order_row DESC");
	  $embed_sql->execute();
	  while($embed_code = $embed_sql->fetch()){
         echo $embed_code['description']; 
      }	    
?>	   
<!--Place for footer embedded_code-->     
  </body>
</html>
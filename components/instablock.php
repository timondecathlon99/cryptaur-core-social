<? 

		    // Инициализируем сеанс CURL
            $ch = curl_init($media_src['instagram']);
            //$ch = curl_init('https://www.instagram.com/timondecathlon/');
            // Указываем, что результат должен записаться в переменную
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           // Выполняем CURL запрос
           $data = curl_exec($ch);
		   //$var = explode('<script type="text/javascript">window._sharedData = ', $data);
		   //$bar = explode(";</script>", $var[1]);
		   //$bar = preg_replace('/<script type="text/javascript">window._sharedData =.*?;</script>/is', '', $old)
		   //echo $bar[0];
  		      $ParsJson=$bar[0];
  		      $ParsJson2=json_decode($ParsJson,true);
			  //var_dump($ParsJson2);
              $ParsJson3=json_decode($ParsJson);
			  //var_dump($ParsJson3);
			  //var_dump($ParsJson3->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges[0]->node->thumbnail_src); 
  	          for ($x=0; $x < 12; $x=$x+2) {
			   echo "<div>";
			   for ($y=$x; $y < $x + 2; $y++) {
 		        $img= $ParsJson3->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges[$y]->node->thumbnail_src;
				echo "<a href='".$media_src['instagram']."' target='_blank''><img src='".$img."'/></a>";
               }
               echo "</div>";			   
		      }

			  
           ?>
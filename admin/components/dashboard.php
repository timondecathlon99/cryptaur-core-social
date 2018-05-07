<div class='desktop'>
   <div class='desktop_form'>
     <?
	 $members = new Member(0 ,$pdo);
	 
	 ?>
<script>
$(document).ready(function() {

 $('.switch').change(function() {
  $.ajax({
                        url: "<?=$domain?>/admin/components/dashboard/switch/",
						type: "POST",
                        data: {"password": 1, "confirm": 1},
                        cache: false,
                        success: function(response){
                            if(response == 0){
                              //alert(response);								
                            }else{
						      //alert(response);
                              location.reload();						  
                            }
                         }
  }); 
 });
}); 
</script>   
    <div>
       <div style='margin: -20px 10px 0px 30px; display: inline-block; vertical-align: middle;'>Графики в реальном времени</div>
       <label class="switch">
        <input type="checkbox" <?=($media_src['online_graphics'] == 1 ) ? 'checked' : '';?>>  
        <span class="slider round"></span>
       </label>
    </div>
    <div class='two_third timBox_10 vertical_top'>
	 <div class='dashboard_module'>
	    <div class='dashboard_module_header'>
	      Регистрации
		  <ul>
		    <li><i class="fa fa-bars"></i></li>
		    <li><i class="fa fa-times" aria-hidden="true"></i></li>
		  </ul>
	    </div>
		<div class='dashboard_module_display'>
	      <div id="registrations" style="width: 100%; height: 100%; "></div>
	    </div>
	  </div>
	  
	  <div class='dashboard_module'>
	    <div class='dashboard_module_header'>
	      Местоположение пользователей
		  <ul>
		    <li><i class="fa fa-bars"></i></li>
		    <li><i class="fa fa-times" aria-hidden="true"></i></li>
		  </ul>
	    </div>
		<div class='dashboard_module_display'>
	      <div id="regions" style="width: 100%; height: 100%; "></div>
	    </div>
	  </div>
	 
	</div>
	<div class='one_third timBox_10 vertical_top'>
	  <div class='dashboard_module'>
	    <div  class='dashboard_module_header'>
	      Популярность страниц
		  <ul>
		    <li><i class="fa fa-bars"></i></li>
		    <li><i class="fa fa-times" aria-hidden="true"></i></li>
		  </ul>
	    </div>
		<div  class='dashboard_module_display'>
	      <div id="online" style="width: 600px; height: 400px; margin-left: -70px;"></div>
	    </div>
	  </div>
	  
	  <div class='dashboard_module'>
	    <div class='dashboard_module_header'>
	      Статистика
		  <ul>
		    <li><i class="fa fa-bars"></i></li>
		    <li><i class="fa fa-times" aria-hidden="true"></i></li>
		  </ul>
	    </div>
		<div class='dashboard_module_display'>
	      <ul>
		   <li>Количество пользователей: <b><?=$members->users_count() ?></b></li>
		   <li>Пользователей онлайн: <b><?=$members->online_users_count() ?></b></li>
		   <li>Посещений за сегодня: <b><?=$members->today_visits_count() ?></b></li>
		   <li>Посещений за месяц:  <b><?=$members->month_visits_count() ?></b></li>
		   <li>Необработанных заказов: <b>16</b></li>
		  </ul>
	    </div>
	  </div>
	  
	  <div class='dashboard_module'>
	    <div class='dashboard_module_header'>
	      Статистика
		  <ul>
		    <li><i class="fa fa-bars"></i></li>
		    <li><i class="fa fa-times" aria-hidden="true"></i></li>
		  </ul>
	    </div>
		<div class='dashboard_module_display'>
	      <div class="c100 p25">
           <span>25%</span>
           <div class="slice">
            <div class="bar"></div>  
           <div class="fill"></div>
           </div>
          </div>
	
        <div class="c100 p35 big green">
         <span>35%</span>
         <div class="slice">
          <div class="bar"></div>  
          <div class="fill"></div>
         </div>
       </div>
	   
	   <div class="c100 p35 small dark">
         <span>35% </span>
         <div class="slice">
          <div class="bar"></div>  
          <div class="fill"></div>
         </div>
       </div>
	    </div>
	  </div>
	</div>
 

 <?($media_src['online_graphics'] == 1 ) ? $isActive = "activity='1' AND" : $isActive ='';?>	   
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
<script type="text/javascript">
///////////Активность на пользователей по странам
      google.charts.load('current', {
        'packages':['geochart'],
        // Note: you will need to get a mapsApiKey for your project.
        // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
        'mapsApiKey': '<?=$media_src['g_maps_key']?>'  
      });
      google.charts.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() {
        var data = google.visualization.arrayToDataTable([
          ['Country', 'Посещения'],
          <?php
          $country_arr = array();
          $visitors_sql = $pdo->prepare("SELECT * FROM visitors ");
          $visitors_sql->execute();
          while($visitor = $visitors_sql->fetch()){
			  if(!in_array($visitor['country'], $country_arr)){
				  array_push($country_arr, $visitor['country']);
			  }
		  }
		  foreach($country_arr as $country){
			  $visitors_sql = $pdo->prepare("SELECT * FROM visitors WHERE $isActive  country='$country' ");
              $visitors_sql->execute();
              $visitors_num = $visitors_sql->rowCount();
			  echo "['$country', $visitors_num],";		  
		  }

?> 
          
        ]);

        var options = {};
        var chart = new google.visualization.GeoChart(document.getElementById('regions'));
        chart.draw(data, options);
      }
</script>
	  	 
<script type="text/javascript">
    ///////////График регистраций
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ['', 'Пользователи', { role: 'style' }],
		<?
        $days = 7;
        $hours = 24;
        $seconds = 3600;
		$now = time();
		$today_start = strtotime(date("d-m-Y", time())); 
        for($i = $today_start - $days*$hours*$seconds ; $i < $today_start ; $i = $i + $hours*$seconds){
		  $delta_right = $i + $hours*$seconds;  
	      $user_sql = $pdo->prepare("SELECT * FROM users WHERE publ_time >'$i' AND publ_time <'$delta_right' ");
          $user_sql->execute();
          $date = date("d M Y", $i);
          $num = $user_sql->rowCount();
          echo "['$date', $num, 'color: green'],";	   
        } 
	      $user_sql = $pdo->prepare("SELECT * FROM users WHERE publ_time >'$today_start' AND publ_time <'$now' ");
          $user_sql->execute();
          $date = date("d M Y", $i);
          $num = $user_sql->rowCount();
          echo "['$date', $num, 'color: green'],";	  ?>
      ]);

      var view = new google.visualization.DataView(data);
      var options = {
        legend: { position: "none" }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("registrations"));
      chart.draw(view, options);
  }
  </script>

   <script type="text/javascript">
   ///////////Активность на пользователей страницах
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          <?
	      $pages_sql = $pdo->prepare("SELECT * FROM pages");
          $pages_sql->execute();
          $date = date("d m Y", $i);
          while($page = $pages_sql->fetch()){
			 $user_sql = $pdo->prepare("SELECT * FROM visitors WHERE $isActive page_hash=:page_hash ");
             $user_sql->bindParam(':page_hash', $page['page_hash']);  
             $user_sql->execute();
			 $num = $user_sql->rowCount();  
			 $page_title = $page['title']; 
             echo "['$page_title', $num],";		 
		  }
          ?>
        ]);

        var options = {};
        var chart = new google.visualization.PieChart(document.getElementById('online'));

        chart.draw(data, options);
      }
    </script>


  
  </div>
</div>


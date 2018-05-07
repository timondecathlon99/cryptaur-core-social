<?


$month_array = array (
  1 => 'Январь',
  2 => 'Февраль',
  3 => 'Март',
  4 => 'Апрель', 
  5 => 'Май',
  6 => 'Июнь',   
  7 => 'Июль', 
  8 => 'Август', 
  9 => 'Сентябь',
  10 => 'Октябрь',
  11 => 'Ноябрь',
  12 => 'Декабрь'
);

$month = $_GET['month'];
$year = $_GET['year'];

$year_next = $year;
$year_prev = $year;

$month_next = $month +1;
$month_prev = $month - 1;

if($month > 12){
  $month = 1;
  $year = $year + 1;
}elseif($month < 1){
  $month = 12;
  $year = $year - 1;
}else{

}
if($month == 12){
  $month_next = 1;
  $year_next = $year + 1;
  $year_prev = $year;
}elseif($month == 1 ){
  $month_prev = 12;
  $year_prev = $year - 1;
  $year_next = $year;
}else{

}

function date_to_format($date){
 if($date < 10){
  $date = "0$date";	
 }
 return $date;
}


?>
<?/* Функция генерации календаря */
function draw_calendar($month,$year){
  include('../global_pass.php');
  /* Начало таблицы */
  $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
  /* Заглавия в таблице */
  $headings = array('Понедельник','Вторник','Среда','Четверг','Пятница','Субота','Воскресенье');
  $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
  /* необходимые переменные дней и недель... */
  $running_day = date('w',mktime(0,0,0,$month,1,$year));
  if($running_day == 0){
   $running_day = $running_day;
  }else{
   $running_day = $running_day - 1;
  }
  $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
  $days_in_this_week = 1;
  $day_counter = 0;
  $dates_array = array();  
  /* первая строка календаря */
  $calendar.= '<tr class="calendar-row">';
  /* вывод пустых ячеек в сетке календаря */
  for($x = 0; $x < $running_day; $x++):
    $calendar.= '<td class="calendar-day-np"> </td>';
    $days_in_this_week++;
  endfor;
  /* дошли до чисел, будем их писать в первую строку */
  for($list_day = 1; $list_day <= $days_in_month; $list_day++):
    $calendar.= '<td class="calendar-day">';
      /* Пишем номер в ячейку */
      $calendar.= '<div class="day-number"><a href="#">'.$list_day.'</a></div>';
	  //приводим текущую дату к формату
	  $date_to_find = $year.'-'.date_to_format($month).'-'.date_to_format($list_day);
	  $date_to_find_before = $year.'-'.date_to_format($month).'-'.date_to_format($list_day+1);
      /** ЗДЕСЬ МОЖНО СДЕЛАТЬ MySQL ЗАПРОС К БАЗЕ ДАННЫХ! ЕСЛИ НАЙДЕНО СОВПАДЕНИЕ ДАТЫ СОБЫТИЯ С ТЕКУЩЕЙ - ВЫВОДИМ! **/
	   $events_sql = $pdo->prepare("SELECT * FROM calendar_events WHERE start_time<'$date_to_find_before' AND end_time>='$date_to_find' "); 
       $events_sql->execute();	   
	   while($event = $events_sql->fetch()){
		 $calendar.= "<p><a href='$domain/?page=30&id=".$event['id']."'><b>".$event['title']."</b></a></p>";  
	   }  
      //$calendar.= str_repeat('<p>'.$msg.'</p>',2);
    $calendar.= '</td>';
    if($running_day == 6):
      $calendar.= '</tr>';
      if(($day_counter+1) != $days_in_month):
        $calendar.= '<tr class="calendar-row">';
      endif;
      $running_day = -1;
      $days_in_this_week = 0;
    endif;
    $days_in_this_week++; $running_day++; $day_counter++;
  endfor;
  /* Выводим пустые ячейки в конце последней недели */
  if($days_in_this_week < 8):
    for($x = 1; $x <= (8 - $days_in_this_week); $x++):
      $calendar.= '<td class="calendar-day-np"> </td>';
    endfor;
  endif;
  /* Закрываем последнюю строку */
  $calendar.= '</tr>';
  /* Закрываем таблицу */
  $calendar.= '</table>';
  
  /* Все сделано, возвращаем результат */
  return $calendar;
} ?>

<div class='calendar_switch' id='prev' >
 <div class='vertical_middle'><</div>
 <div class='vertical_middle'> 
  <div><?=$month_array[$month_prev]?></div><br>
  <div><?=$year_prev?></div>
 </div>
</div>
<div class='calendar_title'>
  <?=$month_array[$month]?> <?=$year?>
</div>
<div class='calendar_switch' id='next'>
  <div class='vertical_middle'> 
   <div><?=$month_array[$month_next]?></div><br>
   <div><?=$year_next?></div>
  </div>
  <div class='vertical_middle'>></div>  
</div><br>

<? echo draw_calendar($month, $year);?>	
<!--Форма быстрого заказа-->
	 <div id="modal_form_medium" class='quick_order_form'>
      <span id="modal_close"><i class="fa fa-times" aria-hidden="true"></i></span> <!-- Кнoпкa зaкрыть -->
	  <div class='modal_content'>
	    <div class='modal_title'>Быстрый заказ</div>
	    <img  src='<?=$photo[0]?>'/>
		<form action='/basket/post.php' method='GET'>
	     <p><?=$item['title']?></p><br>
	     <p><b>Цвет:</b> <?=$item['color']?></p>
	     <p><b>Размер:</b>
					    <select oninput='priceSwitch(this.value);' name='size'  id='quick_size_select'>
						  <option value='0'>Выбрать</option>
						<? foreach($size_arr as $size_item) {?>
						   <option value='<?=$size_item?>'><?=$size_item?></option>
						<? }?>
						</select>
		</p>
		<p><b>Цена:</b><span class='final_price_popup'><?=$item['price']?></span> <i class="fa fa-rub" aria-hidden="true"></i></p><br>
		<input name='id' type='hidden' value='<?=$item['id']?>'></input>
		<input name='quick' type='hidden' value='1'></input>
		<input name='price' class='this_card_price'  type='hidden' value=''></input>
		<input name='phone'  type='tel' required placeholder='+7 XXX XX XX'/></input>
		<input name='name'  type='text' required placeholder='ВАШ ЕИМЯ'></input><br>
		<button>Заказать</button>
		</form> 
	  </div>
     </div>
	 <!--Форма положить в корзину-->
	 <div id="modal_form_medium" class='to_basket_form'>
      <span id="modal_close"><i class="fa fa-times" aria-hidden="true"></i></span> <!-- Кнoпкa зaкрыть -->
	  <div class='modal_content'>
	    <div class='modal_title'>В корзину</div>
	    <img src='<?=$photo[0]?>'/>
		<form>
	     <p><?=$item['title']?></p><br>
	     <p><b>Цвет:</b> <?=$item['color']?></p>
		 <p><b>Размер:</b>
					    <select oninput='priceSwitch(this.value);' name='size'  id='basket_size_select'>
						  <option value='0'>Выбрать</option>
						  <? foreach($size_arr as $size_item) {?>
						   <option value='<?=$size_item?>'><?=$size_item?></option>
					      <? } ?>
						</select>
		 </p>
		 <p><b>Цена:</b> <span class='final_price_popup'><?=$item['price']?></span> <i class="fa fa-rub" aria-hidden="true"></i></p>
		 <input name='id' class='this_card_id' type='hidden' value='<?=$item['id']?>'></input>
		 <input name='price' class='this_card_price'  type='hidden' value=''></input>
		 <input name='quick' type='hidden' value='1'></input><br>
		 <div class='to_basket_accept'>В корзину</div>
		</form> 
	  </div>
     </div>
	 

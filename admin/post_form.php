<? require_once('components/header.php');?>
<?
$table = $_GET['category'];
$action = $_GET['action'];
$action_call = 'создать';
$id = $_GET['id'];
?>
    <script src='tinymce/tinymce.min.js'></script>

    <script type="text/javascript">
        $(document).ready(function(){
            //включение скрипта текстового редактора на текст
            $('#text_switch').click(function() {
                $('#mytextarea').addClass('mytextarea');
                tinymce.init({
                    selector: '.mytextarea',
                    theme: 'modern',
                    plugins: [
                        'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
                        'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                        'save table contextmenu directionality emoticons template paste textcolor'
                    ],
                    content_css: 'css/content.css',
                    document_base_url : '../index.php',
                    convert_urls : false,
                    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons'
                });
            });



//////делаем фотки сортируемыми
            $("#sublist").sortable();
            $("#sublist").disableSelection();

////////////////сохраняем порядок фото
            $('#photo_resort').click(function() {
                var photo = '';
                var id="<?=$_GET['id']?>";
                var table="<?=$_GET['category']?>";
                $(".thumb_tim_preload img").each(function(indx, element){
                    //скрытые фотки не учитываем
                    if($(element).css("display") == 'inline'){
                        photo = photo+$(element).attr("src").replace('/libellen/', "")+",";
                    }
                });
                //alert(id);
                //alert(table);
                //alert(photo);
                $.ajax({
                    url: "<?=$domain?>/admin/components/photo_resort.php",
                    type: "GET",
                    data: {"category": table, "id": id, "photo": photo, },
                    cache: false,
                    success: function(response){
                        if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                            alert("не удалось получить ответ от скрипта");
                        }else{
                            alert(response);
                            //$('.order_table').append(response);
                        }
                    }
                });

            });

///////показываем крестик
            $(".thumb_tim_preload").dblclick(function(){
                $(".thumb_tim_preload div").hide();
                $(this).find('div').show();
            });

///////удаляем фотку
            $(".thumb_tim_preload div").click(function(){
                var photo = $(this).closest('.thumb_tim_preload').find('img').attr("src").replace('/libellen/', "");
                $(this).closest('.thumb_tim_preload').hide(); //скрываем  весь блок
                $(this).closest('.thumb_tim_preload').find('img').hide(); //скрываем фотку для счета
                var id="<?=$_GET['id']?>";
                var table="<?=$_GET['category']?>";
                var act = 'delete';
                //alert(photo);
                $.ajax({
                    url: "<?=$domain?>/admin/components/photo_resort.php",
                    type: "GET",
                    data: {"category": table, "id": id, "photo": photo, "action": act},
                    cache: false,
                    success: function(response){
                        if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                            alert("не удалось получить ответ от скрипта");
                        }else{
                            alert(response);
                        }
                    }
                });
            });

        });
    </script>

    <div class="anketa">
        <form enctype="multipart/form-data" method="POST" action='<?=$domain?>/modules/posts/create.php?category=<?=$table?>&id=<?=$id?>&num=<?=$_GET['num']?>'>
            <?   include_once('../classes/Post.php');

            ($action == 'change') ? $action_call = 'сохранить' : '';
            $post = new Post($id);
            $post->getTable($table);
            $src = $post->show();
            $photo = $post->photos();
            ?>
            <div class='anketa_photo'>
                <br>
                <div class='post_preview_upload bitkit_box'>
                    <output id="list"><span id="sublist">
		    <? if($photo != NULL){
                foreach($photo as $photo_unit){
                    echo "<div  class='thumb_tim_preload  ui-widget ui-state-default'><img src='".$photo_unit."'/><div><i class='fa fa-times' aria-hidden='true'></i></div></div>";
                }

            }else{ ?>
                <div class='thumb_tim_preload'><i class="fa fa-file-image-o fa-5x" aria-hidden="true" ></i></div>
            <?} ?>
		   </span></output>
                    <?if($photo != NULL){ ?>
                        <div id='photo_resort'><div>Сохранить порядок</div></div>
                    <?} ?>

                    <br>
                    <br>
                    <? if($table == 'shops') {echo "<span style='opacity: 0.7;'>(Зажмите Ctrl чтобы выбрать несколько фото)</span><br><br>";} ?>
                    <!--Тут выбор фотографий-->
                    <?if($table != 'uploads') {?>
                        <input type="file" id="files" name="files[]" multiple="" accept="image/*,image/jpeg"><br>
                        <label for="files" class='file_select_label'><i class="fa fa-plus-circle" aria-hidden="true"></i> Выбрать фото</label><br>
                    <? } ?>
                </div>
                <!--Тут выбор фотографий-->
                <? if($table == 'brands' || $table == 'database_records' || $table == 'items' || $table == 'banners' || $table == 'themes' ||  $table == 'sales' || $table == 'news' || $table == 'users' ){ ?>
                    <div class='post_preview_upload bitkit_box'>
                        <output id="list_sec"><span><? if($src['photo_small']){echo "<img class='thumb_tim_preload_sec' src='".$src['photo_small']."'/>";}else{ echo "<i class='fa fa-file-image-o fa-5x' aria-hidden='true'></i>" ;} ?></span></output><br>
                        <input type="file" id="file" name="file"    accept="image/*,image/jpeg"><br><br>
                        <label for="file" class='file_select_label'><i class="fa fa-plus-circle" aria-hidden="true"></i> Выбрать обложку</label>
                    </div>
                <? } ?>

                <? if($table == 'uploads'){ ?>
                    <input type="file" id="file" name="file"   accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet , application/vnd.ms-excel"/><br>
                    <label for="file" class='file_select_label'><i class="fa fa-plus-circle" aria-hidden="true"></i> Выбрать файл</label>
                <? } ?>

                <div class='meta'>
                    <?if($table == 'themes' || $table == 'items' || $table == 'pages' || $table == 'database_records' || $table == 'categories'  || $table == 'collections'){?>
                        <div>Сео заголовок</div><input type='text' name='furl'  placeholder='' value='<?=($post->showField('furl')) ? $post->showField('furl') : ''; ?>'></input><br>
                        <div>Заголовок</div><input type='text' name='content_title'  placeholder='' value='<?=($post->showField('content_title')) ? $post->showField('content_title') : ''; ?>'></input><br>
                        <div>Описание</div><input type='text' name='content_description'  placeholder='' value='<?=($post->showField('content_description')) ? $post->showField('content_description') : ''; ?>'></input><br>
                        <div>Ключевые слова</div><input type='text' name='content_keywords'  placeholder='' value='<?=($post->showField('content_keywords')) ? $post->showField('content_keywords') : ''; ?>'></input><br>
                    <?}?>
                </div>
            </div>
            <div class='anketa_stats'>
                <? if($table == 'users'){ ?>
                    <div>Пароль</div><input type='password' name='password'   ></input><br>
                    <div>E-mail</div><input type='text' name='email'  placeholder='E-mail' value='<?=($post->showField('email')) ? $post->showField('email') : ''; ?>'></input><br>
                    <div>Количество заказов</div><input type='text' name='order_count'  placeholder='Количество заказов' value='<?=($src['order_count']) ? $src['order_count'] : ''; ?>'></input><br>
                    <div>Количество выкупленных заказов</div><input type='text' name='order_count'  placeholder='Количество выкупленных заказов' value='<?=($src['order_success_count']) ? $src['order_success_count'] : ''; ?>'></input><br>
                    <div>Процент выкупа</div><input type='text' disable placeholder='Процент выкупа' value='<?=($src['order_count']) ? $src['order_success_count'] / $src['order_count']: ''; ?>'></input><br>
                    <div>Очки лояльности</div><input type='text' name='respect_points'  placeholder='Очки лояльности' value='<?=($src['respect_points']) ? $src['respect_points']:''; ?>'></input><br>
                    <div>Instagram</div><input type='text' name='instagram'  placeholder='Ссылка на профиль' value='<?=($src['instagram']) ? $src['instagram']:''; ?>'></input><br>
                <? } ?>





                <? if($table == 'filters'){?>
                    <div class="field-unit box">
                        <div>
                            Поле сортировки
                        </div>
                        <div>
                            <select name='parametr'>
                                <?if($src['parametr']){
                                    echo "<option value='".$src['parametr']."'>".$src['parametr']."</option>";
                                }
                                $columns_sql = $pdo->prepare("SHOW COLUMNS FROM items");
                                $columns_sql->execute();
                                while($column = $columns_sql->fetch()){
                                    $name_sql = $pdo->prepare("SELECT * FROM fields WHERE title='".$column['Field']."' ");
                                    $name_sql->execute();
                                    $field_name = $name_sql->fetch()?>
                                    <option value='<?=$column['Field']?>'><?=$field_name['description']?></option>
                                <?}?>
                            </select>
                        </div>
                    </div>
                <? } ?>


                <? if($table == 'uploads'){?>
                    <div>
                        Тип данных
                    </div>
                    <select required  name='upload_type'>
                        <option value='<?=$src['upload_type']?>'><?=$src['upload_type']?></option>
                        <option value='xml'>xml</option>
                        <option value='excel'>excel</option>
                    </select><br>
                <? } ?>



                <? if($table == 'fields'){ ?>
                    <div class="field-unit box">
                        <div>
                            Тип поля
                        </div>
                        <div>
                            <select name='type'>
                                <option value='<?=$src['type']?>'><?=$src['type']?> (Выбрано)</option>
                                <option value='int(11)'>Числовое</option>
                                <option value='text'>Текстовое</option>
                                <option value='bool'>Да/Нет</option>
                                <option value='timestamp'>Временная метка</option>
                            </select>
                        </div>
                    </div>
                    <div class="field-unit box">
                        <div>
                            Тип ввода
                        </div>
                        <div>
                            <select name='insert_type'>
                                <option value='<?=$src['insert_type']?>'><?=$src['insert_type']?> (Выбрано)</option>
                                <option value='manual'>manual</option>
                                <option value='select'>select</option>
                                <option value='multiselect'>multiselect</option>
                                <option value='datalist'>datalist</option>
                                <option value='radio'>radio</option>
                                <option value='tumbler'>tumbler</option>
                            </select>
                        </div>
                    </div>
                    <div class="field-unit box">
                        <div>
                            Варианты
                        </div>
                        <div>
                            <input type="text" name="input_vars" value='<?=$src['input_vars']?>'  placeholder="Варианты через запятую(для radio и чекбокс)"/>
                        </div>
                    </div>
                    <div class="field-unit box">
                        <div>
                            Тип поля
                        </div>
                        <div>
                            <select name='input_type'>
                                <option value='<?=$src['input_type']?>'><?=$src['input_type']?> (Выбрано)</option>
                                <option value='text'>text</option>
                                <option value='number'>number</option>
                                <option value='email'>email</option>
                                <option value='datetime-local'>datetime-local</option>
                                <option value='tel'>tel</option>
                                <option value='color'>color</option>
                            </select>
                        </div>
                    </div>
                    <div class="field-unit box">
                        <div>
                            Обязательное поле
                        </div>
                        <div>
                            <select name='require_type'>
                                <option value='<?=$src['require_type']?>'><?=$src['require_type']?> (Выбрано)</option>
                                <option value='required'>required</option>
                                <option value='free'>free</option>
                            </select>
                        </div>
                    </div>
                    <div class="field-unit box">
                        <div>
                            Имя таблицы
                        </div>
                        <div>
                            <select name='linked_table'>
                                <option value='<?=$src['linked_table']?>'><?=$src['linked_table']?> (Выбрано)</option>
                                <?php
                                $WHITE_LIST = array();
                                $tables_sql= $pdo->prepare("SHOW TABLES FROM $db");
                                $tables_sql->execute();
                                $i = 0;
                                while($table = $tables_sql->fetch()){?>
                                    <option value='<?=$table["Tables_in_$db"]?>'><?=$table["Tables_in_$db"]?></option>
                                    <?   $i++;
                                } ?>
                            </select>
                        </div>
                    </div>

                <? } ?>











                ------||-----<br>
                <!--cvvvvvvvvvvvvvcvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv  -->
                <?
                //смотрим все поля у поста
                foreach($post->getTableColumnsNames() as $column){
                    $column_sql = $pdo->prepare("SELECT * FROM fields WHERE title=:field_name AND activity='1' ORDER BY order_row DESC" );
                    $column_sql->bindParam(':field_name', $column);
                    $column_sql->execute();
                    //для каждого поля смотрим его в таблице полей и получаем характеристики
                    while($field = $column_sql->fetch(PDO::FETCH_LAZY)){?>
                        <div class="field-unit box">
                            <?if($field->insert_type == 'manual'){ /*-----------если это ввод текста----------------------------------*/?>
                                <div><?=$field->description?></div>
                                <input type='<?=$field->input_type?>' name='<?=$field->title?>' <?=$field->require_type?>  placeholder='<?=$field->description?>' value='<?=($src[$field->title])? $src[$field->title] : ''; ?>'/>
                            <?}elseif($field->insert_type == 'select'){ /*---------------------если это селект-------------------------*/?>
                                <div><?=$field->description?></div>
                                <select name='<?=$field->title?>'>
                                    <?
                                    if($src[$field->title] != ''){
                                        $table_post = new Post($src[$field->title]);
                                        $table_post->getTable($field->linked_table);
                                        echo "<option value='".$table_post->postId()."'>".$table_post->title()."</option>";
                                    }
                                    $sql = $pdo->prepare("SELECT * FROM ".$field->linked_table);
                                    $sql->execute();
                                    while($sql_src =$sql->fetch(PDO::FETCH_LAZY)){
                                        echo "<option value='".$sql_src->id."'>".$sql_src->title."</option>";
                                    }
                                    ?>
                                </select>
                            <?}elseif($field->insert_type == 'multiselect'){ /* ------------если это множественный селект---------------*/?>
                                <div><?=$field->description?></div>
                                <select multiple name='<?=$field->title?>[]'>
                                    <?
                                    $sql = $pdo->prepare("SELECT * FROM ".$field->linked_table);
                                    $sql->execute();
                                    while($sql_src = $sql->fetch(PDO::FETCH_LAZY)){
                                        in_array($sql_src->id, json_decode($src[$field->title])) ? $selected = "style='background: rgba(220,220,220, 1);'" : $selected ='';
                                        echo "<option $selected value='".$sql_src->id."'>".$sql_src->title."</option>";
                                    }
                                    ?>
                                </select><br>
                            <?}elseif($field->insert_type == 'datalist'){ /* ------------если это множественный datalist---------------*/?>
                                <div><?=$field->description?></div>
                                <?php
                                if($src[$field->title] != ''){
                                    $table_post = new Post($src[$field->title]);
                                    $table_post->getTable($field->linked_table);
                                    $field_value = $table_post->title();
                                }
                                ?>
                                <input name="<?=$field->title?>" list="<?=$field->title?>" type="<?=$field->input_type?>" placeholder="<?=$field_value?>" value='<?=$src[$field->title]?>' />
                                <span>(<?=$field_value?>)</span>
                                <datalist id="<?=$field->title?>">
                                    <?
                                    $sql = $pdo->prepare("SELECT * FROM ".$field->linked_table);
                                    $sql->execute();
                                    while($sql_src = $sql->fetch(PDO::FETCH_LAZY)){?>
                                        <option label="<?=$sql_src->title?>" value="<?=$sql_src->id?>" />
                                    <? } ?>
                                </datalist>
                            <?}elseif($field->insert_type == 'radio'){ /* ------------если это множественный radio---------------*/?>
                                <div>
                                    <?=$field->description?>
                                </div>
                                <div class="box">
                                    <div class="box">
                                        <?
                                        $vars_arr = explode(',',$field->input_vars);
                                        foreach ($vars_arr as $radio_var){?>
                                            <label class="radio-container">
                                                <input <?=($src[$field->title] == $radio_var)? 'checked' : '';?> type="radio" value="<?=$radio_var?>" name="<?=$field->title?>" /><?=$radio_var?>
                                                <span class="checkmark"></span>
                                            </label>
                                        <?}?>
                                    </div>
                                </div>
                            <?}elseif($field->insert_type == 'tumbler'){?>
                                <div>
                                    <?=$field->description?>
                                </div>
                                <div class="box">
                            <span class="toggle-item box">
                                <span class="toggle-bg">
                                    <input type="radio" name="<?=$field->title?>" value="0">
                                    <input type="radio" name="<?=$field->title?>" <?=($src[$field->title] == 1 ) ? 'checked' : '';?> value="1">
                                    <span class="switch"></span>
                                </span>
                            </span>
                                </div>
                            <?}else{?>


                            <?}?>
                        </div>
                    <?} ?>
                <?  }  ?>


                ------||-----<br>
                <!--cvvvvvvvvvvvvvcvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv  -->



            </div><br>


            <div>Описание</div>
            <textarea  id='mytextarea' name='description' placeholder='Описание' value='' >
		    <? if($table == 'blocks' && $post->showField('block_type') == 'php'){
                echo file_get_contents('../blocks/'.$src['block_name']);
            }elseif($table == 'themes'){
                echo file_get_contents('../css/'.$src['css_name']);;
            }else{
                echo trim($src['description']);
            }?>
		</textarea><br>

            <input type="submit" id="go" class="" name="submit" value='<? echo $action_call ?>'>
        </form>

        <?if($table == 'bots' && $src['id']){
            include('components/chat/table.php');
            include('components/chat/index.php');
        }?>

        <?if($table == 'core_databases'){
            include('components/database_posts.php');
        }?>

        <?if($table == 'channels' && $src['id']){
            include('../channels/graph.php');
        }?>
    </div>
    <script type="text/javascript">
        function handleFileSelect(evt) {
            var files = evt.target.files; // FileList object

            document.getElementById('list').innerHTML = '';
            // Loop through the FileList and render image files as thumbnails.
            for (var i = 0, f; f = files[i]; i++) {

                // Only process image files.
                if (!f.type.match('image.*')) {
                    continue;
                }

                var reader = new FileReader();

                // Closure to capture the file information.
                reader.onload = (function(theFile) {
                    return function(e) {
                        // Render thumbnail.
                        var span = document.createElement('span');
                        span.innerHTML = ['<img class="thumb_tim_preload" src="', e.target.result,
                            '" title="', theFile.name, '"/>'].join('');
                        document.getElementById('list').insertBefore(span, null);
                    };
                })(f);

                // Read in the image file as a data URL.
                reader.readAsDataURL(f);
            }
        }

        document.getElementById('files').addEventListener('change', handleFileSelect, false);
    </script>

    <script type="text/javascript">
        function handleFileSelect(evt) {
            var files = evt.target.files; // FileList object

            document.getElementById('list_sec').innerHTML = '';
            // Loop through the FileList and render image files as thumbnails.
            for (var i = 0, f; f = files[i]; i++) {

                // Only process image files.
                if (!f.type.match('image.*')) {
                    continue;
                }

                var reader = new FileReader();

                // Closure to capture the file information.
                reader.onload = (function(theFile) {
                    return function(e) {
                        // Render thumbnail.
                        var span = document.createElement('span');
                        span.innerHTML = ['<img class="thumb_tim_preload_sec" src="', e.target.result,
                            '" title="', theFile.name, '"/>'].join('');
                        document.getElementById('list_sec').insertBefore(span, null);
                    };
                })(f);

                // Read in the image file as a data URL.
                reader.readAsDataURL(f);
            }
        }

        document.getElementById('file').addEventListener('change', handleFileSelect, false);
    </script>


<?php include('components/footer.php');?>
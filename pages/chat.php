<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 01.05.2018
 * Time: 11:17
 */
?>
<?
$partner = new Member($_GET['room_id']);?>
<div class="message-room">
    <div class="room-header box flex-box flex-between flex-vertical-center">
        <div class="">
            <a href="/inbox">
                < Назад
            </a>
        </div>
        <div class="message-partner-info">
            <div class="room-partner-name flex-box flex-around">
                <?=$partner->name()?>
            </div>
            <div class="message-partner-status flex-box flex-around">
                <?=$partner->last_was()?>
            </div>
        </div>
        <div class="message-partner-actions">
            Настройки
        </div>
    </div>
    <script>
        function loadAllMessages() {
            $.ajax({
                url: "<?=$domain?>pages/chat-wall.php",
                type: "GET",
                data: {"room_id": <?=$_GET['room_id']?>, "user_id": <?=$logedUser->member_id()?>},
                cache: false,
                success: function(response){
                    if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                        alert("не удалось получить ответ от скрипта");
                    }else{
                        //alert(response);
                        //alert(document.getElementById("messages-list").scrollHeight - document.getElementById("messages-list").scrollTop);
                        //alert(document.getElementById("messages-list").offsetHeight);
                        $('.message-room-list').html(response);
                        if(document.getElementById("messages-list").scrollHeight - document.getElementById("messages-list").scrollTop < 700){
                            document.getElementById("messages-list").scrollTop = document.getElementById("messages-list").scrollHeight;
                        }
                    }
                }
            });
        }
        setInterval(loadAllMessages, 1000);

        function addNewMessage() {
            var xhttp=new XMLHttpRequest();
            xhttp.open('GET',"<?=$domain?>pages/chat-wall.php?room_id=<?=$_GET['room_id']?>&user_id=<?=$logedUser->member_id()?>",false);
            xhttp.send();
            if (xhttp.readyState==4 && xhttp.status==200){
                document.getElementById("messages-list").innerHTML += xhttp.responseText;
                document.getElementById("messages-list").scrollTop = document.getElementById("messages-list").scrollHeight;
            }
        }

        function addMessage() {
            var new_msg = $('#text-box').val();
            if(new_msg != '' && new_msg != ' ' ){
                $('#text-box').val('')
                $.ajax({
                    url: "<?=$domain?>modules/messages/create.php",
                    type: "GET",
                    data: {"room_id": <?=$_GET['room_id']?>, "description": new_msg},
                    cache: false,
                    success: function(response){
                        if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                            alert("не удалось получить ответ от скрипта");
                        }else{
                            //alert(response);
                            //$('.message-room-list').html(response);
                        }
                    }
                });
                $.ajax({
                    url: "<?=$domain?>pages/chat-wall.php",
                    type: "GET",
                    data: {"room_id": <?=$_GET['room_id']?>, "user_id": <?=$logedUser->member_id()?>},
                    cache: false,
                    success: function(response){
                        if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                            alert("не удалось получить ответ от скрипта");
                        }else{
                            //alert(response);
                            $('.message-room-list').html(response);
                            document.getElementById("messages-list").scrollTop = document.getElementById("messages-list").scrollHeight;
                        }
                    }
                });
            }
        }

        $(document).ready(function(){

            $.ajax({
                url: "<?=$domain?>pages/chat-wall.php",
                type: "GET",
                data: {"room_id": <?=$_GET['room_id']?>, "user_id": <?=$logedUser->member_id()?>},
                cache: false,
                success: function(response){
                    if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                        alert("не удалось получить ответ от скрипта");
                    }else{
                        //alert(response);
                        $('.message-room-list').html(response);
                        document.getElementById("messages-list").scrollTop = document.getElementById("messages-list").scrollHeight;
                    }
                }
            });

            //Клик по отправить
            $(".btn-send").click(function(){
                addMessage();

            });
            //Клик по ENTER
            $('#text-box').on('keydown', function( e ) {
                if( e.keyCode === 13 ) {
                    addMessage();
                }
            });


        });




        /*
        function loadAllMessages() {
            var xhttp=new XMLHttpRequest();
            xhttp.open('GET',"<?=$domain?>pages/chat-wall.php?room_id=<?=$_GET['room_id']?>&user_id=<?=$logedUser->member_id()?>",false);
            xhttp.send();
            if (xhttp.readyState==4 && xhttp.status==200){
                //alert(xhttp.responseText);
                document.getElementById("messages-list").innerHTML = xhttp.responseText;
            }
        }
        setInterval(loadAllMessages, 1000);

        function addNewMessage() {
            var xhttp=new XMLHttpRequest();
            xhttp.open('GET',"<?=$domain?>pages/chat-wall.php?room_id=<?=$_GET['room_id']?>&user_id=<?=$logedUser->member_id()?>",false);
            xhttp.send();
            if (xhttp.readyState==4 && xhttp.status==200){
                document.getElementById("messages-list").innerHTML += xhttp.responseText;
                document.getElementById("messages-list").scrollTop = document.getElementById("messages-list").scrollHeight;
            }
        }

        function createMessage() {
            var new_msg = $('#text-box').val();
            if(new_msg != '' && new_msg != ' ' ){
                $('#text-box').val('');
                var xhttp=new XMLHttpRequest();
                xhttp.open('GET',"<?=$domain?>modules/messages/create.php?room_id=<?=$_GET['room_id']?>&description="+new_msg,false);
                xhttp.send();
                if (xhttp.readyState==4 && xhttp.status==200){
                    //alert(xhttp.responseText);
                }
            }
        }

        $(document).ready(function(){
            loadAllMessages();
            document.getElementById("messages-list").scrollTop = document.getElementById("messages-list").scrollHeight;

            //Клик по отправить
            $(".btn-send").click(function(){
                addNewMessage();
                createMessage();
            });
            //Клик по ENTER
            $('#text-box').on('keydown', function( e ) {
                if( e.keyCode === 13 ) {
                    e.preventDefault();
                    addMessage();
                }
            });
        });
         */
    </script>
    <div id="messages-list" class="message-room-list box">
        <div class="flex-box flex-around">
            <div>
                <img style="width: 200px" src="https://s.yimg.com/ny/api/res/1.2/81EFT_aygExFdnRjnpnC.A--/YXBwaWQ9aGlnaGxhbmRlcjtzbT0xO3c9ODAw/http://media.zenfs.com/en-US/homerun/the_zoe_report_fashion_166/00f649c6b13a6a1bb6ad971428d79d0a" />
            </div>
        </div>
    </div>
    <div class="room-form box">
        <form class="flex-box" action="<?=$domain?>modules/messages/create.php" method="GET">
            <input type='hidden' name='room_id' value='<?=$partner->member_id()?>'/>
            <textarea class="box" id="text-box" placeholder="Напишите сообщение..." name="description"></textarea>
            <div title="Отправить" class="flex-box flex-vertical-center right ">
                <div >
                    <div class="btn-send right" ><i class="fab fa-telegram-plane"></i></div>
                </div>
            </div>
        </form>
    </div>


</div>

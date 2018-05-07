<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 27.04.2018
 * Time: 13:02
 */
?>
<div class="record-add">
    <div id="record_add" class="record-add-button btn-arrow btn-blue">
        Добавить запись
    </div>
    <script>
        $(document).ready( function() {
            $("#record_add").click(function(){
                $(this).hide();
                $(".record-form").show();
            });
        });
    </script>
    <div class="record-form">
        <p>Запись</p>
        <form action="<?=$domain?>modules/records/create.php" method="POST">
            <div>
                <textarea name="description" class="box"> </textarea>
            </div>
            <div>
                <div>Заголовок</div>
                <input class="record-field" name="title"  type="text"/>
            </div>
            <div>
                <div>
                    Файл
                </div>
                <div id="file_input">
                    <input id="record_file" type="file"/>
                    <input id="file_name" type="text" placeholder="Файл не выбран"  disabled/>
                    <label class="btn-blue" for="record_file"><span><i class="far fa-save"></i></span></label>
                    <script>
                        $(document).ready( function() {
                            $("#record_file").change(function(){
                                var filename = $(this).val().replace(/.*\\/, "");
                                $("#file_name").val(filename);
                            });
                        });
                    </script>
                </div>

            </div>
            <div>
                <div>Товар / услуга</div>
                <input class="record-field" list="type"   type="text"/>
                <datalist id="type">
                    <option label="Москва" value="Москва, Россия" />
                    <option label="Санкт-Петербург" value="Санкт-Петербург, Россия" />
                    <option label="Новосибирск" value="Новосибирск, Новосибирская область, Россия" />
                </datalist>
            </div>
            <div>
                <div>
                    Кто видит
                </div>
                <select>
                    <option value="">Кто видит</option>
                    <option value="">Все</option>
                    <option value="">Друзья</option>
                    <option value="">Никто</option>
                </select>
            </div>
            <div>
                <div>
                </div>
                <button class="btn-arrow btn-blue">Отправить</button>
            </div>
        </form>
    </div>
</div>
<div class='records-list'>
    <?
    $records = new Record(0);
    foreach ($records->getAllUnitsReverse() as $record){
        $listRecord = new Record($record['id']);
        $author = new Member($listRecord->author());
        ?>
        <div class="record-unit">
            <div class="message-info flex-box">
                <div class="message-author-photo ">
                    <a href="<?=$domain?>user/<?=$author->member_id()?>/">
                        <img  src='<?=$author->photo()?>'/>
                    </a>
                </div>
                <div class="message-stats">
                    <div class="">
                        <?=$author->surName()?> <?=$author->name()?>
                    </div>
                    <div class="message-stats ghost">
                        <?=$listRecord->publ_time()?>
                    </div>
                </div>
            </div>
            <?if($listRecord->isRepost()){?>
            <? $originalRecord = new Record($listRecord->originalId()); ?>
            <? $originalAuthor = new Member($originalRecord->author()); ?>
                    <div class="box repost">
                        <div class="message-info flex-box">
                            <div class="message-author-photo ">
                                <a href="<?=$domain?>user/<?=$originalAuthor->member_id()?>/">
                                    <img  src='<?=$originalAuthor->photo()?>'/>
                                </a>
                            </div>
                            <div class="message-stats">
                                <div class="">
                                    <?=$originalAuthor->surName()?> <?=$originalAuthor->name()?>
                                </div>
                                <div class="message-stats ghost">
                                    <?=$originalRecord->publ_time()?>
                                </div>
                            </div>
                        </div>
                        <div class="record-header flex-box ">
                            <div class="record-title">
                                <a href='<?=$domain?>record/<?=$originalRecord->record_id()?>'>
                                    <?=$originalRecord->title()?>
                                </a>
                            </div>
                            <div class="record-time right">
                                <? //$listRecord->publ_time()?>
                            </div>
                        </div>
                        <div class="record-text">
                            <?=$originalRecord->preview()?>
                        </div>
                    </div>
            <?}else{?>
            <div class="record-header flex-box">
                <div class="record-title">
                    <a href='<?=$domain?>record/<?=$listRecord->record_id()?>'>
                        <?=$listRecord->title()?>
                    </a>
                </div>
                <div class="record-time right">
                    <? //$listRecord->publ_time()?>
                </div>
            </div>
            <div class="record-text">
                <?=$listRecord->preview()?>
            </div>
            <?}?>
            <? if($logedUser->member_id()){?>
            <div class="record-actions flex-box">
                <div class="record-social-actions flex-box">
                    <div class="action-like">
                        <form action='<?=$domain?>modules/likes/index.php' method='GET'>
                            <input type='hidden' name='action_type' value='like'/>
                            <input type='hidden' name='record_id' value='<?=$listRecord->record_id()?>'/>
                            <button class=""?>
                                <i class="fas fa-thumbs-up  <?=($listRecord->likedBy($logedUser->member_id())) ? '' : 'ghost-actions'; ?>"></i>
                            </button>
                            <span><?=$listRecord->getLikesAmount()?></span>
                        </form>
                    </div>
                    <div class="action-dislike">
                        <form action='<?=$domain?>modules/dislikes/index.php' method='GET'>
                            <input type='hidden' name='action_type' value='dislike'/>
                            <input type='hidden' name='record_id' value='<?=$listRecord->record_id()?>'/>
                            <button class=""?>
                                    <i class="fas fa-thumbs-down <?=($listRecord->dislikedBy($logedUser->member_id())) ? '' : 'ghost-actions'; ?>"></i>
                            </button>
                            <span><?=$listRecord->getDislikesAmount()?></span>
                        </form>
                    </div>
                    <div class="action-repost">
                        <form action='<?=$domain?>modules/reposts/index.php' method='GET'>
                            <input type='hidden' name='action_type' value='repost'/>
                            <input type='hidden' name='record_id' value='<?=$listRecord->record_id()?>'/>
                            <button class=""?>
                                    <i class="fas fa-share-square <?=($listRecord->repostedBy($logedUser->member_id())) ? '' : 'ghost-actions'; ?>"></i>
                            </button>
                            <span><?=$listRecord->getRepostsAmount()?></span>
                        </form>
                    </div>
                </div>
                <div class="record-author right">
                    <?if($listRecord->author() == $logedUser->member_id()){?>
                        <div>
                            <form action="<?=$domain?>modules/records/delete.php" method="POST">
                                <input type="hidden" name="record_id" value="<?=$listRecord->record_id()?>"/>
                                <button>Удалить</button>
                            </form>
                        </div>
                    <?}else{?>
                        <div><!--
                            <i class="fas fa-user"></i>
                            <a href="#"><?=$author->name()?></a>-->
                        </div>
                    <?}?>
                </div>
            </div>
            <?}?>
        </div>
    <?}?>
</div>

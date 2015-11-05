<?php if(Route::isAdmin()) :?>

<div class="panel panel-default admin-panel-left">
  <div class="panel-heading">Модули</div>
  <div class="panel-body">
    <i class="fa fa-user"></i>&nbsp;&nbsp;<a href="<?=Route::getUrl('?mode=admin&route=groupsusers')?>">Группы пользователей</a>
    <hr/>     
    <span class="glyphicon glyphicon-user"></span></i>&nbsp;&nbsp;<a href="<?=ROUTE::getUrl('?mode=admin&route=users')?>">Пользователи</a>
    <hr/>      
    <!--
    <i class="fa fa-comment"></i>&nbsp;&nbsp;<a href="<?=Route::getUrl('?mode=admin&route=comments')?>">Комментарии</a>
    <hr>
    -->
    <i class="fa fa-clipboard"></i>&nbsp;&nbsp;<a href="<?=Route::getUrl('?mode=admin&route=htmlsnippets')?>">HTML сниппеты</a>
    <hr/>
    <!--
    <i class="fa fa-question-circle"></i>&nbsp;&nbsp;<a href="<?=Route::getUrl('?mode=admin&route=faq')?>">F.A.Q (вопрос-ответ)</a>
    <hr>
    -->
  
    <i class="fa fa-camera"></i>&nbsp;&nbsp;<a href="<?=Route::getUrl('?mode=admin&route=galery')?>">Галереи</a>
    <hr/>
    <i class="fa fa-save"></i>&nbsp;&nbsp;<a href="<?=Route::getUrl('?mode=admin&route=uploads')?>">Загрузка файлов</a>
    <hr/>
    <i class="fa fa-gears"></i>&nbsp;&nbsp;<a href="<?=Route::getUrl('?mode=admin&route=css')?>">Файл стилей CSS</a>    
    <hr/>    
    <i class="fa fa-gears"></i>&nbsp;&nbsp;<a href="<?=Route::getUrl('?mode=admin&route=robots_txt')?>">Файл robots.txt</a>

    <hr>    
    <i class="fa fa-info-circle"></i>&nbsp;&nbsp;<a href="<?=Route::getUrl('?mode=admin&route=phpinfo')?>">Информация о PHP</a>    
        
  </div>
</div>

<?php endif;?>


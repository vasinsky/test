<?php if(Route::isAdmin()) :?>

<!-- navbar-fixed-top-->
<nav class="navbar navbar-inverse" role="navigation">
  <div class="navbar-header">
    <a class="navbar-brand" href="<?=ROUTE::getUrl('?mode=admin&route=pages');?>">Панель управления</a>
  </div>
 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
      <li <?=(in_array(ROUTE,array('pages','sections','deletedpages'))) ? 'class="active"' : '';?> class="dropdown">
        
       <a href="#" class="dropdown-toggle" data-toggle="dropdown">
       <i class="fa fa-files-o"></i>&nbsp;&nbsp;Страницы контента<span class="caret"></span>
       </a>
        
        <ul class="dropdown-menu">
          <li><a href="<?=ROUTE::getUrl('?mode=admin&route=addpage')?>">
          <span class="glyphicon glyphicon-plus-sign"></i></span>&nbsp;&nbsp;Создать</a></li>        
          <li><a href="<?=ROUTE::getUrl('?mode=admin&route=pages')?>">
          <i class="fa fa-file"></i></i>&nbsp;&nbsp;Активные</a></li>
          <li><a href="<?=ROUTE::getUrl('?mode=admin&route=deletedpages')?>">
          <span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;Удалённые</a></li>
          <li class="divider"></li>
          <li>
          <a href="<?=ROUTE::getUrl('?mode=admin&route=sections')?>">
         <span class="glyphicon glyphicon-th-list"></span>&nbsp;&nbsp;Разделы контента</a>
          </li>
        </ul>
         
      </li>

      <li <?=(ROUTE=='filemanager') ? 'class="active"' : '';?>>
        <a target="_blank" href="/app/js/elfinder/elfinder.html">
        <i class="fa fa-list-alt"></i>&nbsp;&nbsp;Файловый менеджер</a>
      </li> 

      <li <?=(ROUTE=='addmodule') ? 'class="active"' : '';?>>
        <a href="<?=ROUTE::getUrl('?mode=admin&route=addmodule')?>">
        <span class="glyphicon glyphicon-th"></span>&nbsp;&nbsp;Добавить модуль</a>
      </li>   

      <li>
        <a href="<?=ROUTE::getUrl('?mode=admin&route=action&logout=true')?>">
        <span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;Выйти</a>
      </li>       
         
</ul>
</div>

</nav>

<?php endif;?>
<center>
<div class="acontent">
<h3><i class="fa fa-clipboard"></i> HTML сниппеты</h3>
<hr class="ahr"/>

 <?php if(is_array($HtmlSnippetsList['data'])):?>
 
 <?php if($HtmlSnippetsList['paginate'] !== false):?>
 
  <ul class="pagination pagination-sm">
 
  <?php foreach($HtmlSnippetsList['paginate'] as $i=>$n):?>
       
    <?php if($i == 'active') :?> 
    
     <li class="active"><a href=""><?=$n;?></a></li>

   <?php else:?>
  
    <li><a href="<?=Route::getUrl('?mode=admin&route=htmlsnippets&page='.$n)?>"><?=$n;?></a></li>
    
   <?php endif;?>

  <?php endforeach;?>
 
  </ul> 
 <br/> <br/>
 <?php endif;?>

 <table  class="table table-hover table-striped">
 
 <tr>
  <th width="40px">#HSID</th>
  <th width="240px">#Псевдоним </th>
  <th width="200px">#Описание</th>
  <th></th>
  <th width="40px"></th>
  <th width="40px"></th>
  <th width="40px"></th>
 </tr>
 
 <?php foreach($HtmlSnippetsList['data'] as $k=>$hs):?>
 
  <tr>
   <td><?=$hs['hsid'];?></td>
   <td><span class="text-muted"><?=$hs['hsname'];?></span><br/><code class="mini">Snippet::<?=$hs['hsname'];?>()</code></td>
   <td><?=$hs['hsdescription'];?></td>
   <td><pre class="min"><?=htmlspecialchars($hs['code']);?></pre></td>
   <td>
      <a  target="_blank"  href="<?=ROUTE::getUrl('?mode=admin&route=viewhtmlsnippets&hsid='.$hs['hsid']);?>" title="Смотреть">
        <span class="glyphicon glyphicon-eye-open"></span>
      </a>   
   </td>
   <td>
      <a href="<?=ROUTE::getUrl('?mode=admin&route=edithtmlsnippets&hsid='.$hs['hsid']);?>" title="Редактировать">
<button type="button" class="btn btn-default  btn-xs"><span class="glyphicon glyphicon-pencil"></span>
  Редактировать</button>
      </a>   
   </td>
   <td>
  <a onclick="return confirm('Удалить сниппет?')" 
  href="<?=Route::getUrl('?mode=admin&route=htmlsnippets&delete='.$hs['hsid']);?>" title="Удалить">
  <button type="button" class="btn btn-danger  btn-xs"><span class="glyphicon glyphicon-trash"></span>
  Удалить</button>
  </a>    
   </td>
  </tr>
 
 <?php endforeach;?> 
 
 
 </table>


 <?php else:?>
  
  Сниппетов не обнаружено
 
 <?php endif;?>

 <?php if(is_array($HtmlSnippetsList['paginate'])):?>
 
  <ul class="pagination pagination-sm">
 
  <?php foreach($HtmlSnippetsList['paginate'] as $i=>$n):?>
       
    <?php if($i == 'active') :?> 
    
     <li class="active"><a href=""><?=$n;?></a></li>

   <?php else:?>
  
    <li><a href="<?=Route::getUrl('?mode=admin&route=htmlsnippets&page='.$n)?>"><?=$n;?></a></li>
    
   <?php endif;?>

  <?php endforeach;?>
 
  </ul> 
 
 <?php endif;?>

 <br/>
  <br/>
 <a href="<?=Route::getUrl('?mode=admin&route=addhtmlsnippets');?>">
 <button type="button" class="btn btn-primary">Создать новый сниппет</button>
 </a>
</div>
</center>
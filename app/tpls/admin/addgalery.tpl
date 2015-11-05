
<center>
<div class="acontent">
<h3><span class="glyphicon glyphicon-plus-sign"></span> Добавить галерею</h3>
<hr class="ahr"/>

    <?php if(isset($errors) && !empty($errors)) :?>
    
    <?php echo '<pre>' . print_r($errors, 1) . '</pre>';?>
    
     <div class="alert alert-danger fade in"> 
     <ul class="text-danger">
     
     <?php foreach($errors as $error):?>
    
    <li><?=$error?></li>
    
    
     <?php endforeach;?>
     
     </ul>
     </div>
    <?php endif;?>
    

    <?php if(isset($badfiles)) :?>
    
    <div class="alert alert-danger fade in"> 
      <h3>Следующие файлы не удалось перенести из временной директории</h3>
      <?php foreach($badfiles as $files):?>
      
       <img src="" class="<?=$files;?>" class="thumbnail" width="100px" height="100px" title="Путь: <?=$files;?>"/>
      
      <?php endforeach;?>
      
    </div>  
    
    <?php endif;?>

    
    <?=$imagesForm['begin'];?>

<div class="form-group">
 <label for="name_galery">Наименование</label> 
    <?=$imagesForm['name_galery'];?>
</div>    
<br/>
    
<div class="form-group">
 <label for="sinonim_galery">Синоним для url</label> 
    <?=$imagesForm['sinonim_galery'];?>
</div>    
<br/>

<div class="form-group">
 <label for="gdescription">Описание</label> 
<?=$imagesForm['gdescription'];?>
</div>
<br/>    

<div class="images_new_galery">    
<div class="form-group">
 <label for="images">Выберите картинки для загрузки</label>     
    <?=$imagesForm['images[]'];?>
</div>    
</div>
<table>
<tr>
 <td valign="top">
<div class="form-group">
<?=$imagesForm['resize'];?> <label for="resize">Резайз картинок</label> <br/> 

<div class="images_new_galery"> 

    <table>
    <tr>
     <td>
     width:<?=$imagesForm['resize_w'];?>
     </td>
     <td width="30px"></td>
     <td>
     height:<?=$imagesForm['resize_h'];?>
     </td>
    </tr>
    </table>
       
          <br/>  
         <span class="text-muted">* - автоматический ресайз:<br/>
          <b>по ширине:</b> указать width, height=0<br/>
          <b>по высоте:</b> указать height, width=0
          </span>
    <br/>      
    <br/> 
    <div class="form-group">
    <label for="image_quality">Качество изображений</label>
    <?=$imagesForm['image_quality'];?>
    </div>
</div>    
    </div>   
     </td>
     <td width="30px"></td>
     <td valign="top">
    <div class="form-group">
     <?=$imagesForm['preview'];?> <label for="preview">Превью картинок</label><br/> 
<div class="images_new_galery"> 
    <table>
    <tr>
     <td>
     width:<?=$imagesForm['preview_w'];?> 
     </td>
     <td width="30px"></td>
     <td>
     height:<?=$imagesForm['preview_h'];?>  
     </td>
    </tr>
    </table> 
    
</div>   
     </td>
    </tr>
    </table>

<br/>

<div class="form-group">
 <?=$imagesForm['watermark'];?> <label for="watermarfile">Водяной знак</label>  
<br/>
<div class="images_new_galery"> 
<table>
 <tr>
  <td>Расположение водяного знака на картинке</td>
  <td></td>
  <td><input type="radio" name="wm_type" value="wm_image" 
      <?=isset($_POST['wm_type']) && $_POST['wm_type'] == 'wm_image' ? 'checked="checked"' : ''?>>
      &nbsp;&nbsp;Картинка</td>
  <td></td>
  <td><input type="radio" name="wm_type" value="wm_text"
      <?=(isset($_POST['wm_type']) && $_POST['wm_type'] == 'wm_text') || !isset($_POST['wm_type']) ? 'checked="checked"' : ''?>>
      &nbsp;&nbsp;Текст</td>
 </tr>
 <tr>
  <td rospan="5" height="10px"></td>
 </tr>
 <tr>
  <td valign="top">
  <?=$imagesForm['wm_position'];?><br/>
  Прозрачность водяного знака
  <?=$imagesForm['alfa'];?>
  </td>
  <td width="30px"></td>
  <td valign="top"><?=$imagesForm['watermarkfile'];?></td>
  <td></td>
  <td><?=$imagesForm['wm_text'];?> цвет: 
  <?=$imagesForm['color'];?>
  размер:
  <?=$imagesForm['size'];?>
  <br/>

  </p>
  </td>
 </tr>
</table>    
</input>    
    <br/>
    

</div>  

<!-- color picker + hex to rgb converter -->

<input type="hidden" name="red" value="0" id="r"/>
<input type="hidden" name="green" value="0" id="g"/>
<input type="hidden" name="blue" value="0" id="b"/>

<!-- end -->

    <br/><br/>
    <?=$imagesForm['addimages'];?>
    <?=$imagesForm['end'];?>
    
</div>



</center>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="<?=isset($pagesData['description']) ? $pagesData['description'] : null;?>">
<meta name="description" content="<?=isset($pagesData['keywords']) ? $pagesData['keywords'] : null;?>">

<script src="/<?=PATH;?>app/js/jquery/jquery-1.10.2.min.js"></script>
<link rel="stylesheet" href="/<?=PATH;?>app/css/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/<?=PATH;?>app/css/font-awesome-4/css/font-awesome.css">
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

<script src="/<?=PATH;?>app/css/bootstrap/js/bootstrap.min.js"></script>
<title><?=isset($pagesData['title']) ? $pagesData['title'] : null;?></title>
<link rel="stylesheet" type="text/css" href="/<?=PATH;?>app/css/admin_style_not_rename.css">

<link rel="stylesheet" href="/<?=PATH;?>app/js/colorpicker/css/colorpicker.css" type="text/css" />
<script src="/<?=PATH;?>app/js/colorpicker/js/colorpicker.js"></script>

<script type="text/javascript" src="/<?=PATH;?>app/js/lightbox/js/modernizr.custom.js"></script>
<script type="text/javascript" src="/<?=PATH;?>app/js/lightbox/js/lightbox-2.6.min.js"></script>
<link href="/<?=PATH;?>app/js/lightbox/css/lightbox.css" rel="stylesheet" />





<script src="/<?=PATH;?>app/js/fw_js.js"></script>

<!-- FILE UPLOADER -->
<link rel="stylesheet" href="/<?=PATH;?>app/js/uploader/css/style.css">
<link rel="stylesheet" href="/<?=PATH;?>app/js/uploader/css/jquery.fileupload.css">
<link rel="stylesheet" href="/<?=PATH;?>app/js/uploader/css/jquery.fileupload-ui.css">
<noscript><link rel="stylesheet" href="/<?=PATH;?>app/js/uploader/css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="/<?=PATH;?>app/js/uploader/css/jquery.fileupload-ui-noscript.css"></noscript>
<!-- -->

<?php Files::load(ADMIN_TPLS_DIR.'/tinymce.tpl');?>

<script src="/<?=PATH;?>app/js/codemirror/lib/codemirror.js"></script>
<link href="/<?=PATH;?>app/js/codemirror/lib/codemirror.css" rel="stylesheet">
<script src="/<?=PATH;?>app/js/codemirror/mode/css/css.js"></script>


</head>
<body>
<?php Files::load(ADMIN_TPLS_DIR.'/static_tpls/top_horizontal_menu.tpl');?>
<?php Files::load(ADMIN_TPLS_DIR.'/static_tpls/left_vertical_menu.tpl');?>

<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="description" content="<?=$pagesData['description'];?>">
 <meta name="description" content="<?=$pagesData['keywords'];?>">
 <title><?=$pagesData['title'];?></title>
 <link href="/<?=PATH;?>app/css/normalize.css" rel="stylesheet" >
 <script src="https://www.google.com/recaptcha/api.js" async defer></script>
 <script src="/<?=PATH;?>app/js/jquery/jquery-1.10.2.min.js"></script>
 <link rel="stylesheet" href="/<?=PATH;?>app/css/bootstrap/css/bootstrap.min.css">
 <link rel="stylesheet" type="text/css" href="/<?=PATH;?>app/css/font-awesome-4/css/font-awesome.css">
 <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
 <script src="/<?=PATH;?>app/css/bootstrap/js/bootstrap.min.js"></script>
 <script src="/<?=PATH;?>app/js/jquery/jquery-1.10.2.min.js"></script>
 <script type="text/javascript" src="/<?=PATH;?>app/js/lightbox/js/modernizr.custom.js"></script>
 <script type="text/javascript" src="/<?=PATH;?>app/js/lightbox/js/lightbox-2.6.min.js"></script>
 <link href="/<?=PATH;?>app/js/lightbox/css/lightbox.css" rel="stylesheet" />
 <link href="/<?=PATH;?>app/css/public_general_style.css" rel="stylesheet" >
  <script type="text/javascript" src="/<?=PATH;?>app/js/public_js.js"></script>
</head>
<body>
<div class="wrapper">

    <header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
      <div class="container">
        <div class="navbar-header">
          <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#bs-navbar" aria-controls="bs-navbar" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <h2>Добро пожаловать на VK Wall</h2>
        </div>
        <nav id="bs-navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="http://vkwall.ru/">Главная</a></li>
            <li><a href="#" onclick="show('frame','forcapchaenter','blockformenter')">Вход</a></li>
            <li><a href="#" onclick="show('frame','forcapcharegistrate','blockformregistrate')">Регистрация</a></li>
          </ul>
        </nav>
      </div>
    </header>
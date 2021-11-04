<?php

session_start();

require 'db.inc.php';
require 'model/ulesrend.php';
$tanulo = new ulesrend;
require 'functions.inc.php';
$szoveg = "Belépés";
$link = "belepes";

if(!empty($_SESSION["id"])) {
    $szoveg = $_SESSION["nev"].": Kilépés";
    $link = "index.php?logout=1";
} 

$menupontok = array('index' => "Főoldal", 'ulesrend' => "Ülésrend", $link => $szoveg);
$page ='index';

if(isset($_REQUEST['page'])){
  if(file_exists('controller/'.$_REQUEST['page'].'.php')){
          $page =$_REQUEST['page'];
  }
}
$title = $menupontok[$page];

include 'htmlheader.inc.php';
?>
<body>
  <?php
include 'controller/'.$page.'.php';
?>
</body>
 
    
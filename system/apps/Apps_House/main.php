<?$appname=$_GET['appname'];$appid=$_GET['appid'];?>
<div id="<?echo $appname.$appid;?>" style="background-color:#f2f2f2; height:500px; max-height:95%; max-width:100%; width:800px; padding-top:10px; border-radius:0px 0px 5px 5px; overflow:auto;">
<?php
/*Application Store*/
//Подключаем библиотеки
include '../../core/library/filesystem.php';
include '../../core/library/bd.php';
//Инициализируем переменные
$click=$_GET['mobile'];
$appdownload=$_GET['appdownload'];
$type=$_GET['type'];
$folder=$_GET['destination'];
$fo = new filecalc;
//Запускаем сессию
session_start();
//Логика
if($appdownload!=''){
  if($type=="app_h"){$_SESSION['appversion']=$_GET['v'];?><script>makeprocess2('system/apps/installer/main.php','<?echo $appdownload; ?>','appdownload');</script><?}else{$link='walls/'.$appdownload; $l='.jpg';}
 $ch=curl_init('http://forest.hobbytes.com/media/os/'.$link.$l);
  if(!is_dir('./temp/')){mkdir('./temp/');}
  $temphash=md5(date('d.m.y.h.i.s').$appdownload);
  $fp=fopen('./temp/'.$appdownload.$temphash.$l,'wb');
  curl_setopt($ch, CURLOPT_FILE,$fp);
  curl_setopt($ch, CURLOPT_HEADER,0);
  curl_exec($ch);
  curl_close($ch);
  fclose($fp);
  if($type=="wall_h"){
if(copy('./temp/'.$appdownload.$temphash.$l,'../../core/design/walls/'.$appdownload.$l)){

  if(copy('../../../system/core/design/walls/'.$appdownload.$l.'','../../../system/users/'.$_SESSION["loginuser"].'/settings/etc/wall.jpg'))  {?>
  <script>
  function getRandomInt(min,max){
    return Math.floor(Math.random()*(max-min+1))+min;
  }
function wallchange(){
    document.body.style.backgroundImage='url("../../../system/users/<? echo $_SESSION["loginuser"];?>/settings/etc/wall.jpg?ran='+getRandomInt(1,1000)+'")';
};
wallchange();
  </script>
  <?
echo "Изображение для рабочего стола успешно загружено!";
}

}else{echo "Ошибка!";}
      }
  unlink('./temp/'.$appdownload.$temphash.$l);
}

$url='http://forest.hobbytes.com/media/os/app.php';
$file=file_get_contents($url);
$array=json_decode($file,TRUE);

$urlw='http://forest.hobbytes.com/media/os/wall.php';
$filew=file_get_contents($urlw);
$arrayw=json_decode($filew,TRUE);

$urlu='http://forest.hobbytes.com/media/os/update.php';
$fileu=file_get_contents($urlu);
$arrayu=json_decode($fileu,TRUE);

?>
<div id="tabs<?echo $appid;?>">
  <ul>
    <li><a href="#apptab<?echo $appid;?>">Приложения</a></li>
    <li><a href="#walltab<?echo $appid;?>">Фоновые изображения</a></li>
    <li><a href="#updatetab<?echo $appid;?>">Обновления</a></li>
  </ul>


  <div id="apptab<?echo $appid;?>">
<?
$appcounter=0;
$ini_array = parse_ini_file('../../core/appinstall.foc', true);
if($array!=''){
foreach ($array as $key)
{
$appcounter=$appcounter+1;
$fo->format($key['size']*1024);
if (array_key_exists($key['file'], $ini_array))
{$btncolor='777777';$btntext='Установлено';$btnaction='';}else{$btncolor='54c45c';$btntext='Установить';$btnaction='onClick="downloadapp(this,'.$key['version'].');"';}
$name=str_replace('_',' ',$key['file']);
echo '
<span class="ui-button ui-widget ui-corner-all" style="height:auto; width:200px; position:relative; text-align:center;  margin:5px;"> <span onClick="fullhouse'.$appid.'('.$appcounter.');" ><div style="background-image: url(http://forest.hobbytes.com/media/os/apps/'.$key['file'].'/app.png); background-size:cover; margin:auto; height:64px; width:64px;">
</div><div style="text-align:center;">'.$name.'<br><span style="font-size:10px;">версия: '.$key['version'].'<br>размер: '.$format.'</span></div></span><br><div id="'.$key['file'].'" class="app_h" '.$btnaction.' style="background-color:#'.$btncolor.'; color:#fff; font-size:13px; padding:5px;">'.$btntext.'</div></span><div class="apphouseinfohide" id="'.$appid.'apphouseinfo'.$appcounter.'" style="height:250px; width:97%; position:relative; background-color:#d4d4d4; padding:10px; border:2px solid #415678; display:none;"><div style="background-image: url(http://forest.hobbytes.com/media/os/apps/'.$key['file'].'/app.png); background-size:cover; margin-bottom:10px; height:80px; width:80px;"></div>
  <span style="font-size:15px; font-weight:900; color:#363636; text-transform: uppercase;" >'.$key['name'].'</span><br><span style="font-size:13px; color:#464646;">'.$key['namelat'].' by '.$key['designer'].', version: '.$key['version'].'</span>
  <br><br><span style="font-size:13px; color:#464646; font-weight:600;">Описание</span><br><span style="font-size:13px; color:#464646;">'.$key['description'].'</span>
  </div>';
}}else{echo 'Приложения не найдены!';}?>
</div>

<div id="walltab<?echo $appid;?>">
  <?
  if($arrayw!=''){
  foreach ($arrayw as $key)
  {
  $name=$key['file'];
  echo '<span class="ui-button ui-widget ui-corner-all" style="height:100px; width:100px; background-image: url(http://forest.hobbytes.com/media/os/walls/thumb/litle_'.$key['file'].'.jpg); background-size:cover; margin:auto; position:relative; text-align:center;  margin:5px;"><div style="text-align:center; margin-top:75%;"><div id="'.$key['file'].'" class="wall_h" onClick="downloadapp(this);" style="background-color:#53547b; color:#fff; font-size:13px; padding:5px;">Скачать</div></div></span>';
}}else{echo 'Изображения не найдены!';}?>
</div>

<div id="updatetab<?echo $appid;?>">
  <?
  $upd_array = parse_ini_file('../../core/osinfo.foc', false);
  $appcounter=0;
  if($arrayu!=''){
  foreach ($arrayu as $key)
  {
    if($upd_array['revision']!=$key['file']){
      $fo->format($key['size']*1024);
      echo '
      <span class="ui-button ui-widget ui-corner-all" style="height:auto; width:90%; position:relative; text-align:left;  margin:5px;"> <span>
      <p style="text-align:left; background-image: url(http://forest.hobbytes.com/media/os/updates/uplogo.png); background-size:cover; height:80px; width:80px;"></p>
      <div style="text-align:left;">Обновление системы<br><br/>
        <span style="font-size:17px;"><b>Forest OS</b> '.$key['codename'].'</span><br>
      <span style="font-size:12px; font-weight:900; " >сборка: <span style="color:#363636; text-transform: uppercase;">'.$key['file'].'</span></span><br>
      <span style="font-size:12px; ">версия: '.$key['version'].'<br>размер: '.$format.'</span></div></span><span style="font-size:15px; color:#464646;">'.$key['description'].'</span>
      <div id="'.$key['file'].'" class="app_h" onClick="update'.$appid.'()" style="background-color:#245896; color:#fff; width:30%; margin: 10px auto 10px auto; font-size:13px; padding:5px; text-align:center;">Обновить</div></span>
      ';
      }
    }

}

  $ini_array = parse_ini_file('../../core/appinstall.foc', true);
  if($array!=''){
  foreach ($array as $key)
  {
    if (array_key_exists($key['file'], $ini_array))
    {
      $curversion=$ini_array[$key['file']]['version'];
      $newversion=$key['version'];
      if($newversion>$curversion){
        $appcounter=$appcounter+1;
        $fo->format($key['size']*1024);
        $name=str_replace('_',' ',$key['file']);
        echo '
        <span class="ui-button ui-widget ui-corner-all" style="height:auto; width:200px; position:relative; text-align:center;  margin:5px;"> <span onClick="fullhouseupd'.$appid.'('.$appcounter.');" ><div style="background-image: url(http://forest.hobbytes.com/media/os/apps/'.$key['file'].'/app.png); background-size:cover; margin:auto; height:64px; width:64px;"></div><div style="text-align:center;">'.$name.'<br><span style="font-size:10px;">версия: '.$key['version'].'<br>размер: '.$format.'</span></div></span><br><div id="'.$key['file'].'" class="app_h" onClick="downloadapp(this,'.$newversion.');" style="background-color:#245896; color:#fff; font-size:13px; padding:5px;">Обновить</div></span>
        <div class="apphouseinfohide" id="'.$appid.'apphouseinfoupd'.$appcounter.'" style="height:250px; width:97%; position:relative; background-color:#d4d4d4; padding:10px; border:2px solid #415678; display:none;"><div style="background-image: url(http://forest.hobbytes.com/media/os/apps/'.$key['file'].'/app.png); background-size:cover; margin-bottom:10px; height:80px; width:80px;"></div>
          <span style="font-size:15px; font-weight:900; color:#363636; text-transform: uppercase;" >'.$key['name'].'</span><br><span style="font-size:13px; color:#464646;">'.$key['namelat'].' by '.$key['designer'].', version: '.$key['version'].'</span>
          <br><br><span style="font-size:13px; color:#464646; font-weight:600;">Описание</span><br><span style="font-size:13px; color:#464646;">'.$key['description'].'</span>
          </div>';
      }
    }
  }}?>
</div>

</div>
</div>
<script>
$(function(){$("#tabs<?echo $appid;?>").tabs();});
function downloadapp(el,el3){$("#<?echo $appid;?>").load("<?echo $folder;?>main.php?appdownload="+el.id+"&v="+el3+"&type="+el.className+"&id=<?echo rand(0,10000).'&appname='.$appname.'&appid='.$appid.'&destination='.$folder;?>")};
function fullhouse<?echo $appid;?>(el2){$(".apphouseinfohide").css('display','none'); $("#<?echo $appid;?>apphouseinfo"+el2).show('clip',200); $("#<?echo $appid;?>apphouseinfo"+el2).css('display','block')};
function fullhouseupd<?echo $appid;?>(el4){$(".apphouseinfohide").css('display','none'); $("#<?echo $appid;?>apphouseinfoupd"+el4).show('clip',200); $("#<?echo $appid;?>apphouseinfoupd"+el4).css('display','block')};
function update<?echo $appid;?>(){
  makeprocess('update','main','','');
}
</script>
<?
unset($appid);
?>
<?
/*Application Store*/

$AppName = $_GET['appname'];
$AppID = $_GET['appid'];

require $_SERVER['DOCUMENT_ROOT'].'/system/core/library/Mercury/AppContainer.php';

/* Make new container */
$AppContainer = new AppContainer;

/* App Info */
$AppContainer->AppNameInfo = 'Apps House';
$AppContainer->SecondNameInfo = 'Магазин';
$AppContainer->VersionInfo = '1.0';
$AppContainer->AuthorInfo = 'Forest Media';

/* Library List */
$AppContainer->LibraryArray = Array('filesystem','bd');

/* Container Info */
$AppContainer->appName = $AppName;
$AppContainer->appID = $AppID;
$AppContainer->StartContainer();

//Инициализируем переменные
$click = $_GET['mobile'];
if(isset($_GET['appdownload'])){
	$appdownload = $_GET['appdownload'];
}
if(isset($_GET['type'])){
	$type = $_GET['type'];
}
$folder = $_GET['destination'];
$fo = new filecalc;
$fileaction = new fileaction;
//Загружаем файл локализации
$apphouse_lang  = parse_ini_file('app.lang');
$cl = $_SESSION['locale'];
//Логика
if(!empty($appdownload)){
  if($type=="app_h"){$_SESSION['appversion']=$_GET['v'];?><script>makeprocess('system/apps/installer/main.php','<?echo $appdownload;?>','appdownload','Installer');</script><?}else{$link='walls/'.$appdownload; $l='.jpg';}
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
  $wall_link = '../../../system/users/'.$_SESSION["loginuser"].'/settings/etc/wall.jpg';
  if(copy('../../../system/core/design/walls/'.$appdownload.$l, $wall_link))  {
      $wall_link = $fileaction->filehash($wall_link);
    ?>
  <script>
function wallchange(){
      $("#background-wall").attr("src", "<?echo $wall_link?>");
};
wallchange();
  </script>
  <?
echo $apphouse_lang[$cl.'_wall_msg'];
}

}else{echo $apphouse_lang[$cl.'_wall_error'];}
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
<div id="tabs<?echo $AppID?>">
  <ul>
    <li><a href="#apptab<?echo $AppID?>"><?echo $apphouse_lang[$cl.'_tab_apps']?></a></li>
    <li><a href="#walltab<?echo $AppID?>"><?echo $apphouse_lang[$cl.'_tab_walls']?></a></li>
    <li><a href="#updatetab<?echo $AppID?>"><?echo $apphouse_lang[$cl.'_tab_updates']?></a></li>
  </ul>


  <div id="apptab<?echo $AppID?>">
<?
$appcounter=0;
if($array!=''){
foreach ($array as $key)
{
$appcounter=$appcounter+1;
$fo->format($key['size']*1024);
if (is_file('../'.$key['file'].'/main.php')){
  $btncolor='3c83e8';
  $btntext=$apphouse_lang[$cl.'_card_button_2'];
  $btnaction='onClick="run(this);"';
}else{
  $btncolor='54c45c';
  $btntext=$apphouse_lang[$cl.'_card_button_1'];
  $btnaction='onClick="downloadapp(this,'.$key['version'].');"';
}
if($cl  ==  'en' || $cl != 'ru'){
  $name=str_replace('_',' ',$key['file']);
}else{
  $name = $key['name'];
}
echo '
<span class="ui-button ui-widget ui-corner-all" style="height:auto; width:200px; position:relative; text-align:center;  margin:5px;">
<span onClick="fullhouse'.$AppID.'('.$appcounter.');" >
<div style="background-image: url(http://forest.hobbytes.com/media/os/apps/'.$key['file'].'/app.png); background-size:cover; margin:auto; height:64px; width:64px;">
</div>
<div style="text-align:center;">'.$name.'<br>
<span style="font-size:10px;">'.$apphouse_lang[$cl.'_card_version'].': '.$key['version'].'<br>'.$apphouse_lang[$cl.'_card_size'].': '.$format.'</span>
</div>
</span><br>
<div id="'.$key['file'].'" class="ui-forest-blink" t="app_h" '.$btnaction.' style="background-color:#'.$btncolor.'; color:#fff; font-size:13px; padding:5px; border-radius:5px;">'.$btntext.'</div></span>
<div class="apphouseinfohide" id="'.$AppID.'apphouseinfo'.$appcounter.'">
<div style="background-image: url(http://forest.hobbytes.com/media/os/apps/'.$key['file'].'/app.png); background-size:cover; margin-bottom:10px; height:80px; width:80px;"></div>
<span style="font-size:15px; font-weight:900; color:#363636; text-transform: uppercase;" >'.$name.'</span><br>
<span style="font-size:13px; color:#464646;">'.$name.' by '.$key['designer'].', version: '.$key['version'].'</span>
<br><br>
<span style="font-size:13px; color:#464646; font-weight:600;">'.$apphouse_lang[$cl.'_card_description'].'</span><br><span style="font-size:13px; color:#464646;">'.$key['description'].'</span>
</div>';
}}else{echo $apphouse_lang[$cl.'_card_error'];}?>
</div>

<div id="walltab<?echo $AppID;?>">
  <?
  if($arrayw!=''){
  foreach ($arrayw as $key)
  {
  $name=$key['file'];
  echo '<span class="ui-button ui-widget ui-corner-all" style="height:100px; width:100px; background-image: url(http://forest.hobbytes.com/media/os/walls/thumb/litle_'.$key['file'].'.jpg); background-size:cover; margin:auto; position:relative; text-align:center;  margin:5px;"><div style="text-align:center; margin-top:75%;"><div id="'.$key['file'].'" t="wall_h" class="ui-forest-blink" onClick="downloadapp(this);" style="background-color:#a54343; color:#fff; font-size:13px; padding:5px; border-radius:5px;">'.$apphouse_lang[$cl.'_wall_button'].'</div></div></span>';
}}else{echo $apphouse_lang[$cl.'_wall_error_2'];}?>
</div>

<div id="updatetab<?echo $AppID;?>">
  <?
  $upd_array = parse_ini_file('../../core/osinfo.foc', false);
  $appcounter=0;
  if($arrayu!=''){
  foreach ($arrayu as $key)
  {
    if($upd_array['subversion']!=$key['subversion']){
      $fo->format($key['size']*1024);
      echo '
      <span class="ui-button ui-widget ui-corner-all" style="height:auto; width:90%; position:relative; text-align:left;  margin:5px;"> <span>
      <p style="text-align:left; background-image: url(http://forest.hobbytes.com/media/os/updates/uplogo.png); background-size:cover; height:80px; width:80px;"></p>
      <div style="text-align:left;">'.$apphouse_lang[$cl.'_upd_label'].'<br><br/>
      <span style="font-size:17px;"><b>Forest OS</b> '.$key['codename'].'</span><br>
      <span style="font-size:12px; font-weight:900; " >'.$apphouse_lang[$cl.'_upd_revision'].': <span style="color:#363636; text-transform: uppercase;">'.$key['file'].'</span></span><br>
      <span style="font-size:12px; ">'.$apphouse_lang[$cl.'_card_version'].': '.$key['version'].'<br>'.$apphouse_lang[$cl.'_upd_subversion'].': '.$key['subversion'].'<br>'.$apphouse_lang[$cl.'_card_size'].': '.$format.'</span></div></span>
      <br><b>'.$apphouse_lang[$cl.'_card_description'].':</b><br><span style="font-size:15px; color:#464646; white-space:pre-wrap;">'.$key['description'].'</span>
      <div id="'.$key['file'].'" class="ui-forest-blink" t="app_h" onClick="update'.$AppID.'()" style="background-color:#962439; color:#fff; width:30%; margin: 10px auto 10px auto; font-size:13px; padding:5px; border-radius:5px; text-align:center;">'.$apphouse_lang[$cl.'_upd_button'].'</div></span>
      ';
      }
    }

}

  if($array != ''){
  foreach ($array as $key)
  {
	$checkApp = '../'.$key['file'].'/main.php';
    if (is_file($checkApp))
    {
			$info = file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/system/apps/'.$key['file'].'/main.php?getinfo=true&h='.md5(date('dmyhis')));
			$arrayInfo = json_decode($info);
	    $curversion	=	$arrayInfo->{'version'};;
			if(empty($curversion)){
				$curversion = '1.0';
			}
      $newversion=$key['version'];
      if($newversion>$curversion){
        $appcounter=$appcounter+1;
        $fo->format($key['size']*1024);
        if($cl  ==  'en' || $cl != 'ru'){
          $name=str_replace('_',' ',$key['file']);
        }else{
          $name = $key['name'];
        }
        echo '
        <span class="ui-button ui-widget ui-corner-all" style="height:auto; width:200px; position:relative; text-align:center;  margin:5px;"> <span onClick="fullhouseupd'.$AppID.'('.$appcounter.');" ><div style="background-image: url(http://forest.hobbytes.com/media/os/apps/'.$key['file'].'/app.png); background-size:cover; margin:auto; height:64px; width:64px;"></div><div style="text-align:center;">'.$name.'<br>
        <span style="font-size:10px;">'.$apphouse_lang[$cl.'_card_version'].': '.$key['version'].'<br>'.$apphouse_lang[$cl.'_card_size'].': '.$format.'</span></div></span><br><div id="'.$key['file'].'" class="ui-forest-blink" t="app_h" onClick="downloadapp(this,'.$newversion.');" style="background-color:#245896; color:#fff; font-size:13px; padding:5px; border-radius:5px;">'.$apphouse_lang[$cl.'_upd_button'].'</div></span>
        <div class="apphouseinfohide" id="'.$AppID.'apphouseinfoupd'.$appcounter.'"><div style="background-image: url(http://forest.hobbytes.com/media/os/apps/'.$key['file'].'/app.png); background-size:cover; margin-bottom:10px; height:80px; width:80px;"></div>
          <span style="font-size:15px; font-weight:900; color:#363636; text-transform: uppercase;" >'.$name.'</span><br><span style="font-size:13px; color:#464646;">'.$name.' by '.$key['designer'].', version: '.$key['version'].'</span>
          <br><br><span style="font-size:13px; color:#464646; font-weight:600;">'.$apphouse_lang[$cl.'_card_description'].':</span><br><span style="font-size:13px; color:#464646; white-space:pre-wrap;">'.$key['description'].'</span>
          </div>';
      }
    }
  }}?>
</div>

</div>
<?
$AppContainer->EndContainer()
?>
<script>
function downloadapp(el,el3){$("#<?echo $AppID;?>").load("<?echo $folder;?>main.php?appdownload="+el.id+"&v="+el3+"&type="+$("#"+el.id).attr("t")+"&id=<?echo rand(0,10000).'&appname='.$AppName.'&appid='.$AppID.'&destination='.$folder;?>")};
function fullhouse<?echo $AppID;?>(el2){$(".apphouseinfohide").css('display','none'); $("#<?echo $AppID;?>apphouseinfo"+el2).show('clip',200); $("#<?echo $AppID;?>apphouseinfo"+el2).css('display','block')};
function fullhouseupd<?echo $AppID;?>(el4){$(".apphouseinfohide").css('display','none'); $("#<?echo $AppID;?>apphouseinfoupd"+el4).show('clip',200); $("#<?echo $AppID;?>apphouseinfoupd"+el4).css('display','block')};
function run(app){
  makeprocess('system/apps/'+app.id+'/main.php','','',app.id);
}
$(function(){
  $("#tabs<?echo $AppID;?>").tabs();
});
function update<?echo $AppID;?>(){
  makeprocess('system/apps/update/main.php','','','Update');
}
</script>

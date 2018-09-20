#!/usr/bin/php
<?php
/** Config **/
$master_path	= '/storage/local/htdocs/boards2/';
/** Allow download content **/
/** Set true to allow **/

/** Iamages **/
$allow_downlaod_img	= true;
/** Videos **/
$allow_download_vid	= true;

/** Is you need to chan the owner and group of each file, set it here **/

$change_owner	= true;
$user_id	= 1001;
$group_id	= 1001;

/** USER AGENT **/

$user_agent	= 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1';

/** End of config file **/

$cache		= $master_path.'cache/';
$urls		= $master_path.'urls/';
$img_path	= $master_path.'images/';
$vid_path	= $master_path.'videos/';

if(!file_exists($cache)){
  mkdir($cache);
  if($change_owner){
    chown($cache, $user_id);
    chgrp($cache, $group_id);
  }
}

if(!file_exists($urls)){
  mkdir($urls);
  if($change_owner){
    chown($urls, $user_id);
    chgrp($urls, $group_id);
  }
}

if(!file_exists($img_path)){
  mkdir($img_path);
  if($change_owner){
    chown($img_path, $user_id);
    chgrp($img_path, $group_id);
  }
}

if(!file_exists($vid_path)){
  mkdir($vid_path);
  if($change_owner){
    chown($vid_path, $user_id);
    chgrp($vid_path, $group_id);
  }
}

/** Functions **/

function download_file($url,$path,$sequence){
  global $cache;
  global $change_owner;
  global $user_id;
  global $group_id;
  global $user_agent;

  $sequence_length	= 8;
  $sequence_final	= substr("00000000{$sequence}", -$sequence_length);

  $file_name	= $sequence_final.'_'.basename($url);
  $file_save	= $path.'/'.$file_name;

  $file_cache	= $cache.sha1($url);

  if(file_exists($file_cache)){
      return true;
    }

  $fp = fopen ($file_save, 'w+') or die('Unable to write a file');
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_FILE, $fp);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
  curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
  curl_setopt($ch, CURLOPT_VERBOSE, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_exec($ch);
  curl_close($ch);
  fclose($fp);

  if($change_owner){
    chown($file_save, $user_id);
    chgrp($file_save, $group_id);
  }
  touch($file_cache);
}

/** End Functions **/

/** GET URL FROM ARGUMENTS **/
if(empty($argv[1])){
    echo 'URL missed'."\n\r";
    echo 'Use: '.$argv[0].' http://your_url'."\n\r";
    exit();
}

$get_url	= $argv['1'];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $get_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);
$url = curl_exec($curl);
curl_close($curl);

//echo $url;
//exit();
//$url 	= file_get_contents($argv['1']);
$path4img	= $img_path.sha1($url);
$path4vid	= $vid_path.sha1($url);

if(!file_exists($path4img)){
  mkdir($path4img);
  if($change_owner){
    chown($path4img, $user_id);
    chgrp($path4img, $group_id);
  }
}

if(!file_exists($path4vid)){
  mkdir($path4vid);
  if($change_owner){
    chown($path4vid, $user_id);
    chgrp($path4vid, $group_id);
  }
}

//Sequense
$sequence	= 1;

$dom = new DOMDocument;
@$dom->loadHTML($url);
$links = $dom->getElementsByTagName('a');
foreach ($links as $link){
  $ext = pathinfo($link->getAttribute('href'), PATHINFO_EXTENSION);
  if( $ext == 'gif' || $ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' ) {
    download_file($link->getAttribute('href'),$path4img,$sequence);
    $sequence++;
  }elseif($ext == 'mp4' || $ext == 'webm'){
    download_file($link->getAttribute('href'),$path4vid,$sequence);
    //echo $link->getAttribute('href')." ###Download video \n\r";
    $sequence++;
  }
}

$urls_sha1	= sha1($argv['1']);
$save_urls	= fopen($urls.$urls_sha1, 'a');
fwrite($save_urls, $argv['1']);
fclose($save_urls);
?>

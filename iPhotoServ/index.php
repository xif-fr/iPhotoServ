<?php

include "conf.php";
include "PlistParser.inc";

$AlbumDataXmlCacheName = "cache/AlbumData.xml.".md5_file($_IPHOTOSERV['albumdata_xml_path']).".srz";
if (file_exists($AlbumDataXmlCacheName)) {
	$AlbumDataXml = unserialize(file_get_contents($AlbumDataXmlCacheName));
} else {
	$PlistParser = new plistParser();
	$AlbumDataXml = $PlistParser->parseFile($_IPHOTOSERV['albumdata_xml_path']);
	file_put_contents($AlbumDataXmlCacheName, serialize($AlbumDataXml));
}

$TitleFolder = "";

if (isset($_GET['folder'])) {
	$Roll = $AlbumDataXml['List of Rolls'][array_search(intval($_GET['folder']), array_column($AlbumDataXml['List of Rolls'], 'RollID'))];
	$FolderName = $Roll['RollName'];
	$TitleFolder = " - ".$FolderName;
}

?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8"/>
		<title><?=$_IPHOTOSERV['title']?><?=$TitleFolder?></title>
		<style>
			html {
				height: 100%;
			}
			body {
				background-color: #444;
				margin: 0px;
				padding: 0px;
				font-family: "Lucida Grande", "Lucida Sans", "Lucida", "Verdana", "Geneva", "DejaVu Sans", "FreeSans", "Helvetica", sans-serif;
			}
			#main {
				padding-top: 35px;
				padding-bottom: 40px;
				max-width: 1180px;
				margin: auto;
			}
			img.photo {
				max-height: 240px;
				max-width: 240px;
				margin: 20px;
				box-shadow: 1px 1px 7px rgba(0,0,0, 0.6);
				border-radius: 3px;
			}
			a.folder, a.folder figure {
				display: inline-block;
				margin: 20px 20px 30px 20px;
				height: 150px;
				width: 150px;
			}
			a.folder thumb {
				display: block;
				overflow: hidden;
				margin: 0px;
				height: 150px;
				width: 150px;
				border-radius: 8px;
				border: 1px solid #1A1A1A;
				border-top: 1px solid rgba(255,255,255, 0.5);
				background-color: #222;
				vertical-align: middle;
				box-shadow: 1px 1px 7px rgba(0,0,0, 0.6);
			}
			a.folder thumb img {
				margin: auto;
				display: block;
			}
			a.folder:hover thumb {
				display: table-cell;
				box-shadow: none;
				border: 1px solid #1A1A1A;
			}
			a.folder:hover thumb img {
				max-height: 150px;
				max-width: 150px;
			}
			a.folder figcaption {
				margin-top: 8px;
				width: 100%;
				text-align: center;
				font-size: 13px;
				color: #EEE;
			}
			nav {
				height: 35px;
				border-bottom: 1px solid #0A0A0A;
				width: 100%;
				background: -webkit-linear-gradient(top, #2A2A2A 0%,#151515 100%);
				background:    -moz-linear-gradient(top, #2A2A2A 0%,#151515 100%);
				background:     -ms-linear-gradient(top, #2A2A2A 0%,#151515 100%);
				background:         linear-gradient(to bottom, #2A2A2A 0%,#151515 100%);
				position: fixed;
				top: 0px;
				text-align: center;
				color: #DADADA;
				font-size: 8px;
				display: table-cell;
				z-index: 1;
			}
			nav a#return {
				position: absolute;
				top: 5px;
				left: 4px;
				color: #DADADA;
				text-decoration: none;
				font-size: 12px;
				height: 25px;
				-webkit-border-image: url(/return_btn/return_btn_black.png) 0 6 0 16 / 0px 6px 0px 16px repeat;
				   -moz-border-image: url(/return_btn/return_btn_black.png) 0 6 0 16 / 0px 6px 0px 16px repeat;
				    -ms-border-image: url(/return_btn/return_btn_black.png) 0 6 0 16 / 0px 6px 0px 16px repeat;
				        border-image: url(/return_btn/return_btn_black.png) 0 6 0 16 / 0px 6px 0px 16px repeat;
			}
			nav a#return:active {
				-webkit-border-image: url(/return_btn/return_btn_black_push.png) 0 6 0 16 / 0px 6px 0px 16px repeat;
				   -moz-border-image: url(/return_btn/return_btn_black_push.png) 0 6 0 16 / 0px 6px 0px 16px repeat;
				    -ms-border-image: url(/return_btn/return_btn_black_push.png) 0 6 0 16 / 0px 6px 0px 16px repeat;
				        border-image: url(/return_btn/return_btn_black_push.png) 0 6 0 16 / 0px 6px 0px 16px repeat;
			}
			nav a#return > span {
				position: relative;
				top: 4px;
			}
			nav h1 {
				display: inline-block;
				margin-right: 10px;
			}
			nav photocount {
				display: inline-block;
				color: #999;
			}
		<?php if (isset($_GET['pict'])) { ?>
			body {
				height: 100%;
			}
			#main {
				position: absolute;
				top: 0px;
				bottom: 0px;
				right: 0px;
				left: 0px;
				padding-top: 45px;
				padding-bottom: 12px;
			}
		<?php } ?>
			img.big {
				margin: 0px auto;
				display: block;
				box-shadow: 1px 2px 7px rgba(0,0,0, 0.6);
				border-radius: 4px;
				height: 100%;
			}
		</style>
		<script>document.createElement('img').src = "/return_btn/return_btn_black_push.png";</script>
	</head>
	<body>
		<nav>
			<?php 
				if (isset($_GET['folder']) && !isset($_GET['pict'])) {
					$ReturnBtnTitle = "Tous les événements";
					$ReturnBtnPath = "/";
					echo '<h1>'.$FolderName.'</h1>';
					echo '<photocount><output>'.$Roll['PhotoCount'].'</output> photos</photocount>';
				}
				if (isset($_GET['pict']) && isset($_GET['folder'])) {
					$ReturnBtnTitle = $FolderName;
					$ReturnBtnPath = "/".$Roll['RollID'];
				} elseif (isset($_GET['pict'])) {
					$ReturnBtnTitle = "Tous les événements";
					$ReturnBtnPath = "/";
				}
				if (isset($ReturnBtnTitle)) {
					echo '<a id="return" href="'.$ReturnBtnPath.'" alt="return"><span>'.$ReturnBtnTitle.'</span></a>';
				}
			?>
		</nav>
		<div id="main">
			<?php
				if (isset($_GET['folder']) && !isset($_GET['pict'])) {
					foreach ($Roll['KeyList'] as $Photo) {
						$Image = $AlbumDataXml['Master Image List'][$Photo];
						$ThumbPath = str_replace($_IPHOTOSERV['iphoto_lib_path'], $_IPHOTOSERV['iphoto_lib_web_path'], $Image['ThumbPath']);
						$ImagePath = str_replace($_IPHOTOSERV['iphoto_lib_path'], $_IPHOTOSERV['iphoto_lib_web_path'], $Image['ImagePath']);
						echo '<a href="/'.$Roll['RollID'].'/'.$Photo.'"><img class="photo" src="'.$ThumbPath.'"></a>';
					}
				} elseif (isset($_GET['pict'])) {
					$Image = $AlbumDataXml['Master Image List'][$_GET['pict']];
					$ImagePath = str_replace($_IPHOTOSERV['iphoto_lib_path'], $_IPHOTOSERV['iphoto_lib_web_path'], $Image['ImagePath']);
					echo '<img class="big" src="'.$ImagePath.'"/>';
				} else {
					foreach ($AlbumDataXml['List of Rolls'] as $Roll) {
						echo '<a class="folder" href="/'.$Roll['RollID'].'/"><figure>';
						$KeyImage = $AlbumDataXml['Master Image List'][$Roll['KeyPhotoKey']];
						$ThumbPath = str_replace($_IPHOTOSERV['iphoto_lib_path'], $_IPHOTOSERV['iphoto_lib_web_path'], $KeyImage['ThumbPath']);
						echo '<thumb><img src="'.$ThumbPath.'"/></thumb>';
						echo '<figcaption>'.$Roll['RollName'].'</figcaption>';
						echo '</figure></a>';
					}
				}
			?>
		<div>
	</body>
</html>

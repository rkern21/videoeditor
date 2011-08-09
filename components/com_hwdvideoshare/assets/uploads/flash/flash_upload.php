<?php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('JQUPLOADER') ? null : define('JQUPLOADER', dirname(__FILE__) );
defined('_JEXEC') ? null : define('_JEXEC', 1 );

if(substr(PHP_OS, 0, 3) == "WIN") {

  define('JPATH_BASE', str_replace("\\components\\com_hwdvideoshare\\assets\\uploads\\flash", "", JQUPLOADER));

} else {

  defined('JPATH_BASE') ? null : define('JPATH_BASE', str_replace("/components/com_hwdvideoshare/assets/uploads/flash", "", JQUPLOADER) );

}
require_once ( JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php' );
$c = hwd_vs_Config::get_instance();

$uploadDir = JPATH_BASE.DS.'tmp'.DS;
$uploadFile = $uploadDir . date('YmdHis') . basename($_FILES['Filedata']['name']);
$uploadSize = (isset($_FILES['Filedata']['size']) ? $_FILES['Filedata']['size'] : "0");
$allowedSize = $c->maxupld*1024*1024;
$allowedType = null;

$file_ext = substr($_FILES['Filedata']['name'], strrpos($_FILES['Filedata']['name'], '.') + 1);
if ($c->ft_mpg == "on" && ( $file_ext == "mpg" || $file_ext == "MPG" ) ) { $allowedType = true; }
if ($c->ft_mpeg == "on" && ( $file_ext == "mpeg" || $file_ext == "MPEG" ) ) { $allowedType = true; }
if ($c->ft_avi == "on" && ( $file_ext == "avi"  || $file_ext == "AVI" ) ) { $allowedType = true; }
if ($c->ft_divx == "on" && ( $file_ext == "divx" || $file_ext == "DIVX" ) ) { $allowedType = true; }
if ($c->ft_mp4 == "on" && ( $file_ext == "mp4" || $file_ext == "MP4" )) { $allowedType = true; }
if ($c->ft_flv == "on" && ( $file_ext == "flv" || $file_ext == "FLV" ) ) { $allowedType = true; }
if ($c->ft_wmv == "on" && ( $file_ext == "wmv" || $file_ext == "WMV" ) ) { $allowedType = true; }
if ($c->ft_rm == "on" && ( $file_ext == "rm" || $file_ext == "RM" ) ) { $allowedType = true; }
if ($c->ft_mov == "on" && ( $file_ext == "mov" || $file_ext == "MOV" ) ) { $allowedType = true; }
if ($c->ft_moov == "on" && ( $file_ext == "moov" || $file_ext == "MOOV" ) ) { $allowedType = true; }
if ($c->ft_asf == "on" && ( $file_ext == "asf" || $file_ext == "ASF" ) ) { $allowedType = true; }
if ($c->ft_swf == "on" && ( $file_ext == "swf" || $file_ext == "SWF" ) ) { $allowedType = true; }
if ($c->ft_vob == "on" && ( $file_ext == "vob" || $file_ext == "VOB" ) ) { $allowedType = true; }

$oformats = explode(",", $c->oformats);
if (in_array($file_ext, $oformats )) {
	$allowedType = true;
}

if ($_GET['jqUploader'] == 1) {
	if ($_FILES['Filedata']['name']) {
		if (!file_exists($uploadFile)) {
			if ($uploadSize <= $allowedSize) {
				if ($allowedType == true) {
					if (move_uploaded_file ($_FILES['Filedata']['tmp_name'], $uploadFile)) {
						print("Done");
						return $uploadFile;
					}
				}
			}
		}
	} else {
		if ($_FILES['Filedata']['error']) {
			return $_FILES['Filedata']['error'];
		}
	}
}

?>

<?php
class hwd_vs_SConfig{ 

  var $instanceConfig = null;

  // Member variables
  var $ffmpegpath = '/usr/local/bin/ffmpeg';
  var $flvtool2path = '/usr/bin/flvtool2';
  var $mencoderpath = '/usr/local/bin/mencoder';
  var $phppath = '/usr/bin/php';
  var $wgetpath = '/usr/bin/wget';
  var $qtfaststart = '/usr/local/bin/qt-faststart';

  function get_instance(){
    $instanceConfig = new hwd_vs_SConfig;
    return $instanceConfig;
  }

}
?>
<?php

   //$mtime = microtime();
   //$mtime = explode(" ",$mtime);
   //$mtime = $mtime[1] + $mtime[0];
   //$starttime = $mtime;

// define a safe filepath named constant
define( 'CHECKPATH', dirname(__FILE__) );
$filename = CHECKPATH."/../../../../cache/check_wget2.file";
$handler = fopen($filename, 'w') or die("can't open file");
fclose($handler);
chmod($filename,0755);

   //$mtime = microtime();
   //$mtime = explode(" ",$mtime);
   //$mtime = $mtime[1] + $mtime[0];
   //$endtime = $mtime;
   //$totaltime = ($endtime - $starttime);
   //echo "This page was created in ".$totaltime." seconds";

?>
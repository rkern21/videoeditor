<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: srprestore.php 632 2011-05-22 20:44:46Z nikosdion $
 * @since 3.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.model');
akimport('helpers.escape', true);

/**
 * Integrated restoration Model
 *
 */
class AkeebaModelSrprestore extends JModel
{
	private $data;
	private $extension;
	private $path;

	public $id;
	public $info;

	function getId()
	{
		$id = null;
		$cid = JRequest::getVar('cid', array(), 'default', 'array');
		if(!empty($cid))
		{
			$id = intval($cid[0]);
			if($id <= 0) $id = null;
		}
		if(empty($id)) $id = JRequest::getInt('id', -1);
		if($id <= 0) $id = null;

		if( empty($id) )
		{
			return null;
		}

		return $id;
	}

	/**
	 * Validates the data passed to the request.
	 * @return mixed True if all is OK, an error string if something is wrong
	 */
	function validateRequest()
	{
		// Does the ID exist?
		$id = $this->getId();
		if(empty($id))
		{
			return JText::_('RESTORE_ERROR_INVALID_RECORD');
		}

		// Is this a valid backup entry?
		$data = AEPlatform::get_statistics($id);
		if(empty($data))
		{
			return JText::_('RESTORE_ERROR_INVALID_RECORD');
		}

		// Is this a complete backup?
		if($data['status'] != 'complete')
		{
			return JText::_('RESTORE_ERROR_INVALID_RECORD');
		}
		
		// Is it a restoration point backup?
		if($data['tag'] != 'restorepoint') {
			return JText::_('RESTORE_ERROR_NOT_AN_SRP');
		}
		
		$rawDataParts = explode("\n", $data['comment']);
		$this->info = json_decode($rawDataParts[1]);
		$this->info->srpdate = $data['backupstart'];

		// Load the profile ID (so that we can find out the output directory)
		$profile_id = $data['profile_id'];
		AEPlatform::load_configuration($profile_id);

		$path = $data['absolute_path'];
		$exists = @file_exists($path);
		if(!$exists)
		{
			// Let's try figuring out an alternative path
			$config =& AEFactory::getConfiguration();
			$path = $config->get('akeeba.basic.output_directory', '').DS.$data['archivename'];
			$exists = @file_exists($path);
		}

		if(!$exists)
		{
			return JText::_('RESTORE_ERROR_ARCHIVE_MISSING');
		}

		$filename = basename($path);
		$lastdot = strrpos($filename, '.');
		$extension = strtoupper( substr($filename, $lastdot+1) );
		if( !in_array($extension, array('JPA','ZIP')) )
		{
			return JText::_('RESTORE_ERROR_INVALID_TYPE');
		}

		$this->data =& $data;
		$this->path = $path;
		$this->extension = $extension;

		return true;
	}

	public function setRestorationParameters()
	{
		// Do we have to use FTP?
		$procengine = JRequest::getCmd('procengine','direct');

		// Get the absolute path to site's root
		$siteroot = JPATH_SITE;
		
		$restoration_setup = array(
			'kickstart.enabled' => false,
			'kickstart.tuning.max_exec_time' => '5',
			'kickstart.tuning.run_time_bias' => '75',
			'kickstart.tuning.min_exec_time' => '0',
			'kickstart.procengine' => $procengine,
			'kickstart.setup.sourcefile' => $this->path,
			'kickstart.setup.destdir' => $siteroot,
			'kickstart.setup.restoreperms' => '0',
			'kickstart.setup.filetype' => $this->extension,
			'kickstart.setup.dryrun' => '0'
		);

		if($procengine == 'ftp')
		{
			$ftp_host	= JRequest::getVar('ftp_host','');
			$ftp_port	= JRequest::getVar('ftp_port', '21');
			$ftp_user	= JRequest::getVar('ftp_user', '');
			$ftp_pass	= JRequest::getVar('ftp_pass', '', 'default', 'none', 2); // Password should be allowed as raw mode, otherwise !@<sdf34>43H% would be trimmed to !@43H% which is plain wrong :@
			$ftp_root	= JRequest::getVar('ftp_root', '');
			$tempdir	= JRequest::getVar('tmp_path', '');
			
			$extraOptions = array(
				'kickstart.ftp.ssl'		=> '0',
				'kickstart.ftp.passive' => '1',
				'kickstart.ftp.host'	=> $ftp_host,
				'kickstart.ftp.port'	=> $ftp_port,
				'kickstart.ftp.user'	=> $ftp_user,
				'kickstart.ftp.pass'	=> $ftp_pass,
				'kickstart.ftp.dir'		=> $ftp_root,
				'kickstart.ftp.tempdir' => $tempdir
			);
			
			$restoration_setup = array_merge($restoration_setup, $extraOptions);
		}
		
		$json = json_encode($restoration_setup);
		
		$session = JFactory::getSession();
		$session->set('restoration_setup', $json, 'akeeba');
	}
	
	public function getRestorationParameters()
	{
		$session = JFactory::getSession();
		$json = $session->get('restoration_setup', null, 'akeeba');
		
		if(!empty($json)) {
			return json_decode($json, true);
		} else {
			return array();
		}
	}

	function getFTPParams()
	{
		$config =& JFactory::getConfig();
		return array(
			'procengine'	=> $config->get('config.ftp_enable', 0) ? 'ftp' : 'direct',
			'ftp_host'		=> $config->get('config.ftp_host', 'localhost'),
			'ftp_port'		=> $config->get('config.ftp_port', '21'),
			'ftp_user'		=> $config->get('config.ftp_user', ''),
			'ftp_pass'		=> $config->get('config.ftp_pass', ''),
			'ftp_root'		=> $config->get('config.ftp_root', ''),
			'tempdir'		=> $config->get('tmp_path', '')
		);
	}

	function getExtractionModes()
	{
		$options = array();
		$options[] = JHTML::_('select.option', 'direct', JText::_('RESTORE_LABEL_EXTRACTIONMETHOD_DIRECT'));
		$options[] = JHTML::_('select.option', 'ftp', JText::_('RESTORE_LABEL_EXTRACTIONMETHOD_FTP'));
		return $options;
	}
	
	function doAjax()
	{
		$ajax = $this->getState('ajax');
		switch($ajax)
		{
			// FTP Connection test for DirectFTP
			case 'testftp':
				// Grab request parameters
				$config = array(
					'host' => JRequest::getVar('host'),
					'port' => JRequest::getVar('port'),
					'user' => JRequest::getVar('user'),
					'pass' => JRequest::getVar('pass'),
					'initdir' => JRequest::getVar('initdir'),
					'usessl' => JRequest::getVar('usessl') == 'true',
					'passive' => JRequest::getVar('passive') == 'true'
				);

				// Perform the FTP connection test
				$test = new AEArchiverDirectftp();
				$test->initialize('', $config);
				$errors = $test->getError();
				if(empty($errors))
				{
					$result = true;
				}
				else
				{
					$result = $errors;
				}
				break;
				
			case 'restoreFilesPing':
			case 'restoreFilesStart':
			case 'restoreFilesStep':
			case 'restoreFilesFinalize':
				global $restoration_setup;
				$restoration_setup = $this->getRestorationParameters();
				
				define('KICKSTART',1);
				
				include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'restore.php');
				akeebaTinyHackForRestorationObserver();
				
				masterSetup();
				
				
				$retArray = array(
					'status'	=> true,
					'message'	=> null
				);
				
				switch($ajax) {
					case 'restoreFilesPing':
						// ping task - realy does nothing!
						$timer =& AKFactory::getTimer();
						$timer->enforce_min_exec_time();
						break;
					
					case 'restoreFilesStart':
						AKFactory::nuke(); // Reset the factory
						
					case 'restoreFilesStep':
						$config =& JFactory::getConfig();
						$override = array(
							'rename_dirs' => array('sql' => rtrim($config->getValue('tmp_path', ''),'/\\').'/sql')
						);
						$engine =& AKFactory::getUnarchiver($override); // Get the engine
						$observer = new RestorationObserver(); // Create a new observer
						$engine->attach($observer); // Attach the observer
						$engine->tick();
						$ret = $engine->getStatusArray();

						if( $ret['Error'] != '' )
						{
							$retArray['status'] = false;
							$retArray['done'] = true;
							$retArray['message'] = $ret['Error'];
						}
						elseif( !$ret['HasRun'] )
						{
							$retArray['files'] = $observer->filesProcessed;
							$retArray['bytesIn'] = $observer->compressedTotal;
							$retArray['bytesOut'] = $observer->uncompressedTotal;
							$retArray['status'] = true;
							$retArray['done'] = true;
						}
						else
						{
							$retArray['files'] = $observer->filesProcessed;
							$retArray['bytesIn'] = $observer->compressedTotal;
							$retArray['bytesOut'] = $observer->uncompressedTotal;
							$retArray['status'] = true;
							$retArray['done'] = false;
							$retArray['factory'] = AKFactory::serialize();
						}
						break;
						
					case 'restoreFilesFinalize':
						$root = AKFactory::get('kickstart.setup.destdir');
						// Remove the sql dump directory
						$config =& JFactory::getConfig();
						$sqldir = rtrim($config->getValue('tmp_path', ''),'/\\').'/sql';
						recursive_remove_directory( $sqldir );
						break;
				}
				
				return $retArray;
				
				break;

			case 'dbRestoreStart':
				$this->dbRestorationInit();
			case 'dbRestore':
				$result = $this->dbRestore();
				break;
				
			// Unrecognized AJAX task
			default:
				$result = false;
				break;
		}
		
		return $result;
	}
	
	private function dbRestorationInit()
	{
		// Get the path to SQL files
		$config =& JFactory::getConfig();
		$path = rtrim($config->getValue('tmp_path', ''),'/\\').'/sql';
		$sqlfile = 'joomla.sql';
		
		$totalSize = 0;
		$runSize = 0;
		$partsMap = array();
		$found = true;
		$index = 0;
		
		while($found) {
			if($index == 0)
			{
				$basename = $sqlfile;
			}
			else
			{
				$basename = substr($sqlfile, 0, -4).'.s'.sprintf('%02u', $index);
			}

			$file = $path.DS.$basename;
			if(!file_exists($file)) {
				$found = false;
				break;
			}
			
			$filesize = @filesize($file) ;
			$totalSize += intval($filesize);
			$partsMap[] = $file;
			
			$index++;
		}
		
		$parts = count($partsMap);
		
		$dbInfo = (object)array(
			'totalSize'		=> $totalSize,
			'runSize'		=> $runSize,
			'parts'			=> $parts,
			'partsMap'		=> $partsMap,
			'curpart'		=> 0,
			'foffset'		=> 0,
			'file'			=> null,
			'start'			=> 0
		);
		
		$session = JFactory::getSession();
		$session->clear('restoration_setup', 'akeeba');
		$session->set('dbinfo', $dbInfo, 'akeeba');
	}
	
	private function dbGetInfo()
	{
		$session = JFactory::getSession();
		return $session->get('dbinfo', (object)array(), 'akeeba');
	}
	
	private function dbSetInfo($dbInfo)
	{
		$session = JFactory::getSession();
		$session->set('dbinfo', $dbInfo, 'akeeba');
	}
	
	private function dbGetNextFile()
	{
		$dbInfo = $this->dbGetInfo();
		
		if( $dbInfo->curpart >= ($dbInfo->parts - 1) ) return false;

		$dbInfo->curpart++;
		$dbInfo->foffset = 0;
		
		$this->dbSetInfo($dbInfo);

		return $this->dbOpenFile();
	}
	
	private function dbOpenFile()
	{
		$dbInfo = $this->dbGetInfo();

		$filename = $dbInfo->partsMap[$dbInfo->curpart];

		if ( !$dbInfo->file = @fopen($filename, "rt") ) {
			$this->setError("Could not open SQL dump file $filename");
			return false;
		}
		else
		{
			// Get the file size
			if (fseek($dbInfo->file, 0, SEEK_END) == 0) {
				$filesize = ftell($dbInfo->file);
			} else {
				$this->setError("Could not determine the file size of the SQL dump file");
				return false;
			}
		}

		// Check start and foffset are numeric values
		if (!is_numeric($dbInfo->start) || !is_numeric($dbInfo->foffset))
		{
			$this->setError("Invalid parameters");
			return false;
		}

		$dbInfo->start = floor($dbInfo->start);
		$dbInfo->foffset = floor($dbInfo->foffset);

		// Check $foffset upon $filesize
		if ($dbInfo->foffset > $filesize)
		{
			$this->setError("File offset is after EOF");
			return false;
		}

		// Set file pointer to $foffset
		if (fseek($dbInfo->file, $dbInfo->foffset) != 0)
		{
			$this->setError("Can not seek to specified file offset");
			return false;
		}
		
		$this->dbSetInfo($dbInfo);

		return true;
	}
	
	private function getTimeRemaining($reset = false, $newmax = 5)
	{
		static $start = 0;
		static $maxAllowed = 5;

		if($reset) {
			$start = microtime(true);
			$maxAllowed = $newmax;
		}

		return $maxAllowed - (microtime(true) - $start);
	}
	
	private function dbRestore()
	{
		$dbInfo = $this->dbGetInfo();
		
		define('DATA_CHUNK_LENGTH',	65536);			// How many bytes to read per step
		define('MAX_QUERY_LINES',	300);			// How many lines may be considered to be one query (except text lines)
		
		$skipMySQLErrorNumbers = array(
			1262,
			1263,
			1264,
			1265,	// "Data truncated" warning
			1266,
			1287,
			1299
			// , 1406	// "Data too long" error
		);		

		$comment = array('#','-- ','---','/*!');
		
		$this->getTimeRemaining(true, 3);
		
		$file = false;

		if(!$this->dbOpenFile()) return array('percent' => 0, 'message' => '', 'error' => $this->getError(), 'done' => 0);

		$db = JFactory::getDBO();
		$db->setQuery('SET FOREIGN_KEY_CHECKS = 0');
		$db->query();

		$query = "";
		$queries = 0;
		$dbInfo->totalqueries = empty($dbInfo->totalqueries) ? 0 : $dbInfo->totalqueries;
		$dbInfo->linenumber = $dbInfo->start;
		$totalsizeread = 0;

		while ( $this->getTimeRemaining() > 0 ) {
			// Read one line (1 line = 1 query)
			$query = "";
			while (!feof($dbInfo->file) && (strpos($query, "\n") === false) ) {
				$query .= fgets($dbInfo->file, DATA_CHUNK_LENGTH);
			}

			// An empty query is EOF. Are we done or should I skip to the next file?
			if(empty($query) || ($query === false))
			{
				if($dbInfo->curpart >= ($dbInfo->parts - 1)) {
					break;
				} else {
					// Register the bytes read
					$current_foffset = @ftell($dbInfo->file);
					$dbInfo->runSize += $current_foffset - $dbInfo->foffset;
					// Get the next file
					if(!$this->dbGetNextFile())
						return array('percent' => 0, 'message' => '', 'error' => $this->getError(), 'done' => 0);
					// Rerun the fetcher
					continue;
				}
			}

			if(substr($query,-1) != "\n")
			{
				// WTF? We read more data than we should?! Roll back the file
				$rollback = strlen($query) - strpos($query, "\n");
				fseek($dbInfo->file, -$rollback, SEEK_CUR);
				// And chop the line
				$query = substr($query, 0, $rollback);
			}

			// Handle DOS linebreaks
			$query = str_replace("\r\n", "\n", $query);
			$query = str_replace("\r", "\n", $query);

			// Skip comments and blank lines only if NOT in parents
			$skipline = false;
			reset($comment);
			foreach ($comment as $comment_value) {
				if (trim($query) == "" || strpos($query, $comment_value) === 0) {
					$skipline = true;
					break;
				}
			}
			if ($skipline) {
				$linenumber++;
				continue;
			}

			$query = trim($query, " \n");
			$query = rtrim($query, ';');

			// CREATE TABLE query pre-processing
			$replaceAll = false;
			$changeEncoding = false;
			if( substr($query, 0, 12) == 'CREATE TABLE')
			{
				// Yes, try to get the table name
				$restOfQuery = trim(substr($query, 12, strlen($query)-12 )); // Rest of query, after CREATE TABLE
				// Is there a backtick?
				if(substr($restOfQuery,0,1) == '`')
				{
					// There is... Good, we'll just find the matching backtick
					$pos = strpos($restOfQuery, '`', 1);
					$tableName = substr($restOfQuery,1,$pos - 1);
				}
				else
				{
					// Nope, let's assume the table name ends in the next blank character
					$pos = strpos($restOfQuery, ' ', 1);
					$tableName = substr($restOfQuery,1,$pos - 1);
				}
				unset($restOfQuery);

				// Try to drop the table anyway
				$dropQuery = 'DROP TABLE IF EXISTS `'.$tableName.'`;';
				$db->setQuery(trim($dropQuery), false);
				if (!$db->query()) {
					$this->setError($db->getError());
					return array('percent' => 0, 'message' => '', 'error' => $this->getError(), 'done' => 0);
				}
			} else
			// CREATE VIEW query pre-processing
			// In any case, drop the view before attempting to create it. (Views can't be renamed)
			if( (substr($query, 0, 7) == 'CREATE ') && (strpos($query, ' VIEW ') !== false) )
			{
				// Yes, try to get the view name
				$view_pos = strpos($query, ' VIEW ');
				$restOfQuery = trim( substr($query, $view_pos + 6) ); // Rest of query, after VIEW string
				// Is there a backtick?
				if(substr($restOfQuery,0,1) == '`')
				{
					// There is... Good, we'll just find the matching backtick
					$pos = strpos($restOfQuery, '`', 1);
					$tableName = substr($restOfQuery,1,$pos - 1);
				}
				else
				{
					// Nope, let's assume the table name ends in the next blank character
					$pos = strpos($restOfQuery, ' ', 1);
					$tableName = substr($restOfQuery,1,$pos - 1);
				}
				unset($restOfQuery);

				// Try to drop the view anyway
				$dropQuery = 'DROP VIEW IF EXISTS `'.$tableName.'`;';
				$db->setQuery(trim($dropQuery), false);
				if (!$db->query()) {
					$this->setError($db->getError());
					return array('percent' => 0, 'message' => '', 'error' => $this->getError(), 'done' => 0);
				}
			}

			if(!empty($query)) {
				$db->setQuery(trim($query));
				if (!$db->query()) {
					// Skip over errors we can safely ignore...
					if( in_array($db->errno, $skipMySQLErrorNumbers) ) continue;

					$this->setError($db->getError());
					return array('percent' => 0, 'message' => '', 'error' => $this->getError(), 'done' => 0);
				}

			}

			$totalsizeread += strlen($query);
			$dbInfo->totalqueries++;
			$queries++;
			$query = "";
			$linenumber++;
		}

		// Get the current file position
		$current_foffset = ftell($dbInfo->file);
		if ($current_foffset === false) {
			if ($dbInfo->file) fclose($dbInfo->file);
			$this->setError("Can not read file pointer");
			return array('percent' => 0, 'message' => '', 'error' => $this->getError(), 'done' => 0);
		}
		else
		{
			$dbInfo->runSize += $current_foffset - $dbInfo->foffset;
			$dbInfo->foffset = $current_foffset;
		}

		// Return statistics
		$pct_done = ceil($dbInfo->runSize / $dbInfo->totalSize * 100);
		$bytes_done = $dbInfo->runSize;
		$bytes_tota = $dbInfo->totalSize;
		$bytes_togo = $dbInfo->totalSize - $dbInfo->runSize;
		$kbytes_done = round($bytes_done / 1024, 2);
		$kbytes_tota = round($bytes_tota / 1024, 2);

		// Check for global EOF
		if( ($dbInfo->curpart >= ($dbInfo->parts-1)) && feof($dbInfo->file) ) $bytes_togo = 0;

		if ($bytes_togo == 0) {
			// Clear stored variables if we're finished
			$lines_togo = '0';
			$lines_tota = $linenumber -1;
			$queries_togo = '0';
			$queries_tota = $dbInfo->totalqueries;
		}
		else
		{
			$this->dbSetInfo($dbInfo);
		}

		// Close the file
		if ($dbInfo->file) fclose($dbInfo->file);

		// Return meaningful data to AJAX
		$ret = array(
			'percent'			=> $pct_done,
			'message'			=> sprintf('Restored %1.2f of %1.2f Kilobytes (%02.1f %) ', $kbytes_done, $kbytes_tota, $pct_done),
			'error'				=> '',
			'done'				=> ($bytes_togo == 0) ? '1' : '0'
		);
		return $ret;
	}
	
}

function akeebaTinyHackForRestorationObserver()
{
	// The observer class, used to report number of files and bytes processed
	class RestorationObserver extends AKAbstractPartObserver
	{
		public $compressedTotal = 0;
		public $uncompressedTotal = 0;
		public $filesProcessed = 0;

		public function update($object, $message)
		{
			if(!is_object($message)) return;

			if( !array_key_exists('type', get_object_vars($message)) ) return;

			if( $message->type == 'startfile' )
			{
				$this->filesProcessed++;
				$this->compressedTotal += $message->content->compressed;
				$this->uncompressedTotal += $message->content->uncompressed;
			}
		}

		public function __toString()
		{
			return __CLASS__;
		}

	}
}
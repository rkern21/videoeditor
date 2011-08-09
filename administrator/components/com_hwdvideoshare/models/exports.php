<?php
/**
 *    @version [ Wainuiomata ]
 *    @package hwdVideoShare
 *    @copyright (C) 2007 - 2009 Highwood Design
 *    @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 ***
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class hwdvids_BE_exports
{
   /**
	* backup tables
	*/
	function backuptables()
	{
		hwdvids_HTML::backuptables();
	}

	/**
	*  The backup process controller
	*/
	function botJombackup()
	{
		$app = & JFactory::getApplication();
		$config = new JConfig;

		$jb_abspath		= JPATH_SITE;
		$jb_host		= $config->host;
		$jb_user		= $config->user;
		$jb_password	= $config->password;
		$jb_db			= $config->db;
		$jb_mailfrom	= $config->mailfrom;
		$jb_fromname	= $config->fromname;
		$jb_livesite	= JURI::root();

		$bkuparray = array(
		'testing' => 1,
		'deletefile' => true,
		'compress' => 1,
		'backuppath' => 0,
		'recipient' => '',
		'subject' => 'Mysql backup',
		'fromname' => $jb_fromname,
		'body' => 'Mysql backup from '.$jb_fromname,
		'drop_tables' => 1,
		'create_tables' => 1,
		'struct_only' => 0,
		'locks' => 0,
		'comments' => 1,
		);

		$testing 			= $bkuparray['testing'];
		$compress			= $bkuparray['compress'];
		$deletefile			= $bkuparray['deletefile'];
		$drop_tables 		= $bkuparray['drop_tables'];
		$create_tables 		= $bkuparray['create_tables'];
		$struct_only 		= $bkuparray['struct_only'];
		$locks 				= $bkuparray['locks'];
		$comments 			= $bkuparray['comments'];
		$ToEmail 			= $bkuparray['recipient'];
		$Subject 			= $bkuparray['subject'];
		$Body 				= $bkuparray['body'];
		$backuppath			= $bkuparray['backuppath'];
		$FromName 			= $bkuparray['fromname'];

		$mediaPath          = JPATH_SITE.DS."media";
		$checkfileName      = "jombackup_checkfile_";
		$today              = date("Y-m-d");
		$dateCheckFile      = $checkfileName.$today;
		$okToContinue       = true;

		if ($okToContinue)
		{
			require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'mysql_backup.class.php');

			$backup_obj = new Jombackup_MySQL_DB_Backup();
			$result = hwdvids_BE_exports::jombackupBackup($backup_obj,$jb_host,$jb_user,$jb_password,$jb_db,$bkuparray,$mediaPath,$jb_fromname,$compress,$backuppath);
			$backupFile = $backup_obj->jombackup_file_name;

			$EmailResult=hwdvids_BE_exports::jombackupEmail($bkuparray,$jb_mailfrom,$jb_fromname,$backupFile,$result['output'],$jb_livesite);
			if (!empty($backupFile))
			{
				$msg = _HWDVIDS_EXPORT_SUCCESS;
				unlink($backupFile);
				$app->enqueueMessage($msg);
				$app->redirect( JURI::root( true ) . '/administrator/index.php?option=com_hwdvideoshare&task=export' );
				return;
			}
		}
	}

	function jombackupEmail($bkuparray,$jb_mailfrom,$jb_fromname,$Attachment,$Body,$jb_livesite)
	{
		$ToEmail 			= Jrequest::getVar( 'recipient', $jb_mailfrom );
		$Subject 			= Jrequest::getVar( 'subject', 'hwdVideoShare SQL Backup' );
		$Body 				= Jrequest::getVar( 'body', 'hwdVideoShare backup completed successfully at ' );
		$Body 				= $Body." ".$jb_fromname;
		$FromName 			= $bkuparray['fromname'];
		if (empty($ToEmail)) {$ToEmail=$jb_mailfrom;}
		if (empty($Subject)) {$Subject="hwdVideoShare SQL Backup";}
		if (empty($Body)) 	 {$Body="hwdVideoShare backup completed successfully.";}
		JUtility::sendMail($jb_mailfrom, $FromName, $ToEmail, $Subject.' '.$jb_livesite, $Body, $mode=0, $cc=NULL, $bcc=NULL, $Attachment);
	}

	function jombackupBackup(&$backup_obj,$jb_host,$jb_user,$jb_password,$jb_db,$bkuparray,$mediaPath,$jb_fromname,$compress,$backuppath)
	{
		$config = new JConfig;

		$Body 				= $bkuparray['body'];
		$drop_tables 		= $bkuparray['drop_tables'];
		$create_tables 		= $bkuparray['create_tables'];
		$struct_only 		= $bkuparray['struct_only'];
		$locks 				= $bkuparray['locks'];
		$comments 			= $bkuparray['comments'];
		if (!empty($backuppath) && is_dir($backuppath) && @is_writable($backuppath)  )
			$backup_dir 		= $backuppath;
		else
			$backup_dir 		= $mediaPath;

		//----------------------- EDIT - REQUIRED SETUP VARIABLES -----------------------
		$backup_obj->server 	= $jb_host;
		$backup_obj->port 		= 3306;
		$backup_obj->username 	= $jb_user;
		$backup_obj->password 	= $jb_password;
		$backup_obj->database 	= $jb_db;

		//Tables you wish to backup. All tables in the database will be backed up if this array is null.
		$backup_obj->tables = array(
		$config->dbprefix.'hwdvidsantileech',
		$config->dbprefix.'hwdvidscategories',
		$config->dbprefix.'hwdvidschannels',
		$config->dbprefix.'hwdvidsfavorites',
		$config->dbprefix.'hwdvidsflagged_groups',
		$config->dbprefix.'hwdvidsflagged_videos',
		$config->dbprefix.'hwdvidsgroups',
		$config->dbprefix.'hwdvidsgroup_membership',
		$config->dbprefix.'hwdvidsgroup_videos',
		$config->dbprefix.'hwdvidsgs',
		$config->dbprefix.'hwdvidslogs_archive',
		$config->dbprefix.'hwdvidslogs_favours',
		$config->dbprefix.'hwdvidslogs_views',
		$config->dbprefix.'hwdvidslogs_votes',
		$config->dbprefix.'hwdvidsplaylists',
		$config->dbprefix.'hwdvidsrating',
		$config->dbprefix.'hwdvidsss',
		$config->dbprefix.'hwdvidssubs',
		$config->dbprefix.'hwdvidsvideos'
		);
		//----------------------- END - REQUIRED SETUP VARIABLES ------------------------

		//----------------------- OPTIONAL PREFERENCE VARIABLES -------------------------
		//Add DROP TABLE IF EXISTS queries before CREATE TABLE in backup file.
		$backup_obj->drop_tables = $drop_tables;
		//No table structure will be backed up if false
		$backup_obj->create_tables = $create_tables;
		//Only structure of the tables will be backed up if true.
		$backup_obj->struct_only = $struct_only;
		//Add LOCK TABLES before data backup and UNLOCK TABLES after
		$backup_obj->locks = $locks;
		//Include comments in backup file if true.
		$backup_obj->comments = $comments;
		//Directory on the server where the backup file will be placed. Used only if task parameter equals MSX_SAVE.
		$backup_obj->backup_dir = $backup_dir.'/';
		//Default file name format.
		$backup_obj->fname_format = 'd_m_Y';
		//Values you want to be intrerpreted as NULL
		$backup_obj->null_values = array( );

		$savetask = MSX_SAVE;
		//Optional name of backup file if using 'MSX_APPEND', 'MSX_SAVE' or 'MSX_DOWNLOAD'. If nothing is passed, the default file name format will be used.
		$filename = '';
		//----------------------- END - REQUIRED EXECUTE VARIABLES ----------------------
		$result_bk = $backup_obj->Execute($savetask, $filename, $compress);

		if (!$result_bk)
		{
			$output = $backup_obj->error;
		}
		else
		{
			$output = $Body.': ' . strftime('%A %d %B %Y  - %T ') . ' ';
		}
		return array('result'=>$result_bk,'output'=>$output);
	}
}
?>
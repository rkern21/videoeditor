<?php

class AEFinalizationSrpquotas extends AEAbstractObject
{
	public function __construct() {
		// This empty function is required for direct instantiation of the
		// object, as this is forbidden in the base class' constructor
	}
	
	public function apply_srp_quotas($parent) {
		$parent->relayStep('Applying quotas');
		$parent->relaySubstep('');
		
		// If no quota settings are enabled, quit
		$registry =& AEFactory::getConfiguration();
		$srpQuotas = $registry->get('akeeba.quota.srp_size_quota');
		
		if($srpQuotas <= 0)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "No restore point quotas were defined; old restore point files will be kept intact" );
			return true; // No quota limits were requested
		}
		
		// Get valid-looking backup ID's
		$validIDs =& AEPlatform::get_valid_backup_records(true, array('restorepoint'));
		
		$statistics =& AEFactory::getStatistics();
		$latestBackupId = $statistics->getId();
		
		// Create a list of valid files
		$allFiles = array();
		if(count($validIDs))
		{
			foreach($validIDs as $id)
			{
				$stat = AEPlatform::get_statistics($id);
				// Multipart processing
				$filenames = AEUtilStatistics::get_all_filenames($stat, true);
				if(!is_null($filenames))
				{
					// Only process existing files
					$filesize = 0;
					foreach($filenames as $filename)
					{
						$filesize += @filesize($filename);
					}
					$allFiles[] = array('id' => $id, 'filenames' => $filenames, 'size' => $filesize);
				}
			}
		}
		unset($validIDs);
		
		// If there are no files, exit early
		if(count($allFiles) == 0)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "There were no old restore points to apply quotas on" );
			return true;
		}
		
		// Init arrays
		$killids = array();
		$ret = array();
		$leftover = array();
		
		// Do we need to apply size quotas?
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Processing restore point size quotas" );
		// OK, let's start counting bytes!
		$runningSize = 0;
		while(count($allFiles) > 0)
		{
			// Each time, remove the last element of the backup array and calculate
			// running size. If it's over the limit, add the archive to the return array.
			$def = array_pop($allFiles);
			$runningSize += $def['size'];
			if($runningSize >= $srpQuotas)
			{
				if($latestBackupId == $def['id'])
				{
					$runningSize -= $def['size'];
				}
				else
				{
					$ret[] = $def['filenames'];
					$killids[] = $def['filenames'];
				}
			}
		}
		
		// Convert the $ret 2-dimensional array to single dimensional
		$quotaFiles = array();
		foreach($ret as $temp)
		{
			foreach($temp as $filename)
			{
				$quotaFiles[] = $filename;
			}
		}
		
		// Update the statistics record with the removed remote files
		if(!empty($killids)) foreach($killids as $id) {
			$data = array('filesexist' => '0');
			AEPlatform::set_or_update_statistics($id, $data, $parent);
		}
		
		// Apply quotas
		if(count($quotaFiles) > 0)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Applying quotas" );
			jimport('joomla.filesystem.file');
			foreach($quotaFiles as $file)
			{
				if(!@AEPlatform::unlink($file))
				{
					$parent->setWarning("Failed to remove old system restore point file ".$file );
				}
			}
		}
		
		return true;
	}
}
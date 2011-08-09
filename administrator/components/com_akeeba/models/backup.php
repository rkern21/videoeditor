<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: backup.php 641 2011-05-23 17:23:38Z nikosdion $
 * @since 3.2.5
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

// Load framework base classes
jimport('joomla.application.component.model');

/**
 * The back-end backup model
 */
class AkeebaModelBackup extends JModel
{
	public function runBackup()
	{
		$ret_array = array();
		
		$ajaxTask = $this->getState('ajax');

		switch($ajaxTask)
		{
			case 'start':
				// Description is passed through a strict filter which removes HTML
				$description = $this->getState('description');
				// The comment is passed through the Safe HTML filter (note: use 2 to force no filtering)
				$comment = $this->getState('comment');
				$jpskey = $this->getState('jpskey');

				$tag = $this->getState('tag');

				// Try resetting the engine
				AECoreKettenrad::reset();

				// Remove any stale memory files left over from the previous step

				if(empty($tag)) $tag = AEPlatform::get_backup_origin();
				AEUtilTempvars::reset($tag);
				
				$kettenrad =& AECoreKettenrad::load($tag);

				// Take care of System Restore Point setup
				if($tag == 'restorepoint') {
					// Fetch the extension's version information
					require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'liveupdate'.DS.'classes'.DS.'xmlslurp.php';
					$slurp = new LiveUpdateXMLSlurp();
					$exttype = $this->getState('type');
					switch($exttype) {
						case 'component':
							$extname = 'com_';
							break;
						case 'module':
							$extname = 'mod_';
							break;
						case 'plugin':
							$extname = 'plg_';
							break;
						case 'template':
							$extname = 'tpl_';
							break;
					}
					$extname .= $this->getState('name');
					$info = $slurp->getInfo($extname, '');
					
					// Get the configOverrides for this extension
					AEPlatform::$configOverrides = $this->getConfigOverridesForSRP($extname, $info);
					
					// Create an SRP descriptor
					$srpdescriptor = array(
						'type'			=> $this->getState('type'),
						'name'			=> $this->getState('name'),
						'group'			=> $this->getState('group'),
						'version'		=> $info['version'],
						'date'			=> $info['date']
					);

					// Set the description and comment
					$description = "System Restore Point - ".JText::_($exttype).": $extname";
					$comment = "---BEGIN SRP---\n".json_encode($srpdescriptor)."\n---END SRP---";
					$jpskey = '';
					
					// Set a custom finalization action queue
					AEPlatform::$configOverrides['volatile.core.finalization.action_handlers'] = array(
						new AEFinalizationSrpquotas()						
					);
					AEPlatform::$configOverrides['volatile.core.finalization.action_queue'] = array(
						'remove_temp_files',
						'update_statistics',
						'update_filesizes',
						'apply_srp_quotas'
					);
				}
				
				$options = array(
					'description'	=> $description,
					'comment'		=> $comment,
					'jpskey'		=> $jpskey
				);
				$kettenrad->setup($options);
				$kettenrad->tick();
				if( ($kettenrad->getState() != 'running') && ($tag == 'restorepoint') ) {
					$kettenrad->tick();
				}
				$ret_array  = $kettenrad->getStatusArray();
				$kettenrad->resetWarnings(); // So as not to have duplicate warnings reports
				AECoreKettenrad::save($tag);
				break;

			case 'step':
				$tag = $this->getState('tag');
				$kettenrad =& AECoreKettenrad::load($tag);
				$kettenrad->tick();
				$ret_array  = $kettenrad->getStatusArray();
				$kettenrad->resetWarnings(); // So as not to have duplicate warnings reports
				AECoreKettenrad::save($tag);

				if($ret_array['HasRun'] == 1)
				{
					// Clean up
					AEFactory::nuke();
					AEUtilTempvars::reset($tag);
				}
				break;

			default:
				break;
		}
		
		return $ret_array;
	}
	
	/**
	 * Gets the configuration overrides for a System Restore Point backup
	 * 
	 * @param string $extname The extension shortname, e.g. com_foobar
	 * @param array $info The structure returned by Live Update's XMLSlurp class
	 * 
	 * @return array
	 */
	private function getConfigOverridesForSRP($extname, $info)
	{
		// Get the defaults from the URL
		$config = array(
			'akeeba.basic.archive_name'				=> 'restore-point-[DATE]-[TIME]',
			'akeeba.basic.backup_type'				=> 'full',
			'akeeba.basic.backup_type'				=> 'full',
			'akeeba.advanced.archiver_engine'		=> 'jpa',
			'akeeba.advanced.proc_engine'			=> 'none',
			'akeeba.advanced.embedded_installer'	=> 'none',
			'engine.archiver.common.dereference_symlinks'	=> true, // hopefully no extension has symlinks inside its own directories...
			'core.filters.srp.type'					=> $this->getState('type'),
			'core.filters.srp.group'				=> $this->getState('group'),
			'core.filters.srp.name'					=> $this->getState('name'),
			'core.filters.srp.customdirs'			=> $this->getState('customdirs'),
			'core.filters.srp.customfiles'			=> $this->getState('customfiles'),
			'core.filters.srp.extraprefixes'		=> $this->getState('extraprefixes'),
			'core.filters.srp.customtables'			=> $this->getState('customtables'),
			'core.filters.srp.langfiles'			=> $this->getState('langfiles')
		);
		
		// Parse a local file stored in (backend)/assets/srpdefs/$extname.xml
		jimport('joomla.filesystem.file');
		$filename = JPATH_COMPONENT_ADMINISTRATOR.'/assets/srpdefs/'.$extname.'.xml';
		if(JFile::exists($filename)) {
			$xml = JFactory::getXMLParser('simple');
			if($xml->loadFile($filename)) {
				$extraConfig = $this->parseRestorePointXML($xml->document);
				if($extraConfig !== false) $this->mergeSRPConfig($config, $extraConfig);
			}
			unset($xml);
		}
		
		// Parse the extension's manifest file and look for a <restorepoint> tag
		if(!empty($info['xmlfile'])) {
			$xml = JFactory::getXMLParser('simple');
			if($xml->loadFile($info['xmlfile'])) {
				$restorepoint = $xml->document->getElementByPath('restorepoint');
				if($restorepoint) {
					$extraConfig = $this->parseRestorePointXML($restorepoint);
					if($extraConfig !== false) $this->mergeSRPConfig($config, $extraConfig);
				}
			}
			unset($restorepoint);
			unset($xml);
		}
		
		return $config;
	}
	
	private function parseRestorePointXML($xml)
	{
		if(!count($xml->children())) return false;
		
		$ret = array();
		
		// 1. Group name -- core.filters.srp.group
		$group = $xml->getElementByPath('group');
		if($group) {
			$ret['core.filters.srp.group'] = $group->data();
		}
		
		// 2. Custom dirs -- core.filters.srp.customdirs
		$customdirs = $xml->getElementByPath('customdirs');
		if($customdirs) {
			$stack = array();
			if(count($customdirs->children())) {
				$children = $customdirs->children();
				foreach($children as $child) {
					if($child->name() == 'dir') {
						$stack[] = $child->data();
					}
				}
			}
			if(!empty($stack)) $ret['core.filters.srp.customdirs'] = $stack;
		}
		
		// 3. Extra prefixes -- core.filters.srp.extraprefixes
		$extraprefixes = $xml->getElementByPath('extraprefixes');
		if($extraprefixes) {
			$stack = array();
			if(count($extraprefixes->children())) {
				$children = $extraprefixes->children();
				foreach($children as $child) {
					if($child->name() == 'prefix') {
						$stack[] = $child->data();
					}
				}
			}
			if(!empty($stack)) $ret['core.filters.srp.extraprefixes'] = $stack;
		}
		
		// 4. Custom tables -- core.filters.srp.customtables
		$customtables = $xml->getElementByPath('customtables');
		if($customtables) {
			$stack = array();
			if(count($customtables->children())) {
				$children = $customtables->children();
				foreach($children as $child) {
					if($child->name() == 'table') {
						$stack[] = $child->data();
					}
				}
			}
			if(!empty($stack)) $ret['core.filters.srp.customtables'] = $stack;
		}
		
		// 5. Language files -- core.filters.srp.langfiles
		$langfiles = $xml->getElementByPath('langfiles');
		if($langfiles) {
			$stack = array();
			if(count($langfiles->children())) {
				$children = $langfiles->children();
				foreach($children as $child) {
					if($child->name() == 'lang') {
						$stack[] = $child->data();
					}
				}
			}
			if(!empty($stack)) $ret['core.filters.srp.langfiles'] = $stack;
		}
		
		// 6. Custom files -- core.filters.srp.customfiles
		$customfiles = $xml->getElementByPath('customfiles');
		if($customfiles) {
			$stack = array();
			if(count($customfiles->children())) {
				$children = $customfiles->children();
				foreach($children as $child) {
					if($child->name() == 'file') {
						$stack[] = $child->data();
					}
				}
			}
			if(!empty($stack)) $ret['core.filters.srp.customfiles'] = $stack;
		}
		
		if(empty($ret)) return false;
		
		return $ret;
	}
	
	private function mergeSRPConfig(&$config, $extraConfig)
	{
		foreach($config as $key => $value) {
			if(array_key_exists($key, $extraConfig)) {
				if(is_array($value) && is_array($extraConfig[$key])) {
					$config[$key] = array_merge($extraConfig[$key], $value);
				}
			}
		}
	}
}
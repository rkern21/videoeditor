<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2011 Nicholas K. Dionysopoulos
 * @license GNU GPL version 3 or, at your option, any later version
 * @package akeebaengine
 * @version $Id: platform.php 677 2011-05-30 21:10:45Z nikosdion $
 */

// Protection against direct access
defined('AKEEBAENGINE') or die('Restricted access');

class AEPlatform
{
	static $configOverrides = array();
	
	/**
	 * Registers Akeeba's class autoloader with Joomla!
	 */
	public static function register_autoloader()
	{
		// Try to register AEAutoloader with SPL, or fall back to making use of JLoader
		// Obviously, performance is better with SPL, but not all systems support it.
		if( function_exists('spl_autoload_register') )
		{
			// Joomla! is using its own autoloader function which has to be registered first...
			if(function_exists('__autoload')) spl_autoload_register('__autoload');
			// ...and then register ourselves.
			spl_autoload_register('AEAutoloader');
		}
		else
		{
			// We can't redefine __autoload, since Joomla! is already using that. So, we'll just push
			// our classes to JLoader's stack using JLoader::register($class, $file). In order to do
			// that, we'll have to scan Akeeba Engine's directories for available class files.

			// -- Run 0: Register the configuration class
			$path_prefix =  JPATH_ADMINISTRATOR.DS.'components'.DS.'com_akeeba'.DS.'akeeba';
			JLoader::register('AEConfiguration', $path_prefix.DS.'configuration.php' );

			// -- Run 1: Import plugins first (as this is a FIFO list and plugins must override core classes)
			$path_prefix =  JPATH_ADMINISTRATOR.DS.'components'.DS.'com_akeeba'.DS.'akeeba'.DS.'plugins';
			AEPlatform::register_akeeba_engine_classes($path_prefix);

			// -- Run 2: Core Akeeba Engine classes
			$path_prefix =  JPATH_ADMINISTRATOR.DS.'components'.DS.'com_akeeba'.DS.'akeeba';
			AEPlatform::register_akeeba_engine_classes($path_prefix);

		}
	}
	
	/**
	 * Saves the current configuration to the database table
	 * @param	int		$profile_id	The profile where to save the configuration to, defaults to current profile
	 * @return	bool	True if everything was saved properly
	 */
	public static function save_configuration($profile_id = null)
	{
		// Load Joomla! database class
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );

		// Get the active profile number, if no profile was specified
		if(is_null($profile_id))
		{
			$profile_id = self::get_active_profile();
		}

		// Get an INI format registry dump
		$registry =& AEFactory::getConfiguration();
		$dump_profile = $registry->exportAsINI();
		
		// Encrypt the registry dump if required
		$dump_profile = AEUtilSecuresettings::encryptSettings($dump_profile);

		// Write the local profile's configuration data
		$sql = 'UPDATE '.$db->nameQuote('#__ak_profiles').' SET '.
			$db->nameQuote('configuration').' = '.$db->Quote($dump_profile)
			.' WHERE '.
			$db->nameQuote('id').' = '.	$db->Quote($profile_id);
		$db->setQuery($sql);
		if($db->query() === false)
		{
			return false;
			//JError::raiseError(500,'Can\'t save Akeeba Configuration','SQL Query<br/>'.$db->getQuery().'<br/>SQL Error:'.$db->getError());
		}

		return true;
	}

	/**
	 * Loads the current configuration off the database table
	 * @param	int		$profile_id	The profile where to read the configuration from, defaults to current profile
	 * @return	bool	True if everything was read properly
	 */
	public static function load_configuration($profile_id = null)
	{
		// Load Joomla! database class
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );

		// Get the active profile number, if no profile was specified
		if(is_null($profile_id))
		{
			$profile_id = self::get_active_profile();
		}

		// Initialize the registry
		$registry =& AEFactory::getConfiguration();
		$registry->reset();

		// Load the INI format local configuration dump off the database
		$sql = "SELECT ".$db->nameQuote('configuration').' FROM '.$db->nameQuote('#__ak_profiles')
		.' WHERE '.
		$db->nameQuote('id').' = '.$db->Quote($profile_id);
		$db->setQuery($sql);
		$ini_data_local = $db->loadResult();
		if( empty($ini_data_local) || is_null($ini_data_local) )
		{
			// No configuration was saved yet - store the defaults
			self::save_configuration($profile_id);
		}
		else
		{
			// Configuration found. Convert to array format.
			if(function_exists('get_magic_quotes_runtime'))
			{
				if(@get_magic_quotes_runtime())
				{
					$ini_data_local = stripslashes($ini_data_local);
				}
			}
			// Decrypt the data if required
			$ini_data_local = AEUtilSecuresettings::decryptSettings($ini_data_local);

			$ini_data_local = AEUtilINI::parse_ini_file_php($ini_data_local, true, true);
			$ini_data = array();
			foreach($ini_data_local as $section => $row)
			{
				if(!empty($row))
				{
					foreach($row as $key => $value)
					{
						$ini_data["$section.$key"] = $value;
					}
				}
			}
			unset($ini_data_local);

			// Import the configuration array
			$registry->mergeArray($ini_data, false, false);
		}
		
		// Apply config overrides
		if(is_array(self::$configOverrides) && !empty(self::$configOverrides)) {
			$registry->mergeArray(self::$configOverrides, false, false);
		}

		$registry->activeProfile = $profile_id;
	}

	/**
	 * Returns an associative array of stock platform directories
	 * @return array
	 */
	public static function get_stock_directories()
	{
		static $stock_directories = array();

		if(empty($stock_directories))
		{
			if(defined('AKEEBACLI'))
			{
				$tmpdir = AEUtilJconfig::getValue('tmp_path');
			}
			else
			{
				$jreg =& JFactory::getConfig();
				$tmpdir = $jreg->getValue('config.tmp_path');
			}
			$stock_directories['[SITEROOT]'] = self::get_site_root();
			$stock_directories['[ROOTPARENT]'] = @realpath(self::get_site_root().DS.'..');
			$stock_directories['[SITETMP]'] = $tmpdir;
			$stock_directories['[DEFAULT_OUTPUT]'] = self::get_site_root().DS.'administrator'.DS.'components'.DS.'com_akeeba'.DS.'backup';
		}

		return $stock_directories;
	}

	/**
	 * Returns the absolute path to the site's root
	 * @return string
	 */
	public static function get_site_root()
	{
		static $root = null;

		if( empty($root) || is_null($root) )
		{
			$root = JPATH_ROOT;

			if(empty($root) || ($root == DS) || ($root == '/'))
			{
				// Try to get the current root in a different way
				if(function_exists('getcwd')) {
					$root = getcwd();
				}
				if(class_exists('JFactory'))
				{
					$app =& JFactory::getApplication();
					if( $app->isAdmin() )
					{
						if(empty($root)) {
							$root = '../';
						} else {
							$adminPos = strpos($root, 'administrator');
							if($adminPos !== false) {
								$root = substr($root, 0, $adminPos);
							} else {
								$root = '../';
							}
							// Degenerate case where $root = 'administrator'
							// without a leading slash before entering this
							// if-block
							if(empty($root)) $root = '../';
						}
					}
					else
					{
						if(empty($root) || ($root == DS) || ($root == '/') ) {
							$root = './';
						}
					}
				}
				else
				{
					// JFactory doesn't exist - we are on native backup mode
					$adminPos = strpos($root, 'administrator');
					if($adminPos !== false) {
						$root = substr($root, 0, $adminPos);
					} else {
						// Normally, this should never happen!
						$root = '../';
					}
				}
			}
		}
		return $root;
	}

	/**
	 * Returns the absolute path to the installer images directory
	 * @return string
	 */
	public static function get_installer_images_path()
	{
		return JPATH_ADMINISTRATOR.DS.'components'.DS.'com_akeeba'.DS.'assets'.DS.'installers';
	}

	/**
	 * Returns the active profile number
	 * @return int
	 */
	public static function get_active_profile()
	{
		if( defined('AKEEBA_PROFILE') )
		{
			return AKEEBA_PROFILE;
		}
		else
		{
			$session =& JFactory::getSession();
			return $session->get('profile', null, 'akeeba');
		}
	}

	/**
	 * Returns the selected profile's name. If no ID is specified, the current
	 * profile's name is returned.
	 * @return string
	 */
	public static function get_profile_name($id = null)
	{
		if(empty($id)) $id = self::get_active_profile();
		$id = (int)$id;

		$sql = 'SELECT `description` FROM `#__ak_profiles` WHERE `id` = '.$id;
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );
		$db->setQuery($sql);
		return $db->loadResult();
	}

	/**
	 * Returns the backup origin
	 * @return string Backup origin: backend|frontend
	 */
	public static function get_backup_origin()
	{
		if(defined('AKEEBA_BACKUP_ORIGIN')) return AKEEBA_BACKUP_ORIGIN;

		if(JFactory::getApplication()->isAdmin()) {
			return 'backend';
		} else {
			return 'frontend';
		}
	}

	/**
	 * Returns a MySQL-formatted timestamp out of the current date
	 * @param string $date[optional] The timestamp to use. Omit to use current timestamp.
	 * @return string
	 */
	public static function get_timestamp_mysql($date = 'now')
	{
		if( !class_exists('JObject') )
		{
			require_once JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'base'.DS.'object.php';
			require_once JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'utilities'.DS.'date.php';
		}
		else
		{
			jimport('joomla.utilities.date');
		}
		$jdate = new JDate($date);
		return $jdate->toMySQL();
	}

	/**
	 * Returns the current timestamp, taking into account any TZ information,
	 * in the format specified by $format.
	 * @param string $format Timestamp format string (standard PHP format string)
	 * @return string
	 */
	public static function get_local_timestamp($format)
	{
		if( !class_exists('JObject') )
		{
			require_once JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'base'.DS.'object.php';
			require_once JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'utilities'.DS.'date.php';
		}
		else
		{
			jimport('joomla.utilities.date');
		}

		if( defined('AKEEBACLI') )
		{
			$tz = AEUtilJconfig::getValue('offset');
			$format = str_replace( '%A', date('%A'), $format );
		}
		else
		{
			$jregistry =& JFactory::getConfig();
			$tzDefault = $jregistry->getValue('config.offset');
			$user =& JFactory::getUser();
			$tz = $user->getParam('timezone', $tzDefault);
		}

		$dateNow = new JDate();
		$dateNow->setOffset($tz);

		return $dateNow->toFormat($format);
	}

	/**
	 * Returns the current host name
	 * @return string
	 */
	public static function get_host()
	{
		if(defined('AKEEBACLI'))
		{
			require_once JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'environment'.DS.'uri.php';
			$url = AEPlatform::get_platform_configuration_option('siteurl','');
			$oURI = new JURI($url);
			return $oURI->getHost();
		}
		$uri =& JURI::getInstance();
		return $uri->getHost();
	}

	/**
	 * Creates or updates the statistics record of the current backup attempt
	 * @param int $id Backup record ID, use null for new record
	 * @param array $data The data to store
	 * @param AEAbstractObject $caller The calling object
	 * @return int|null|bool The new record id, or null if this doesn't apply, or false if it failed
	 */
	public static function set_or_update_statistics( $id = null, $data = array(), &$caller )
	{
		if(!is_array($data)) return null; // No valid data?
		if( empty($data) ) return null; // No data at all?

		$db =& AEFactory::getDatabase( self::get_platform_database_options() );

		if( is_null($id) )
		{
			// Create a new record
			$sql_fields = '';
			$sql_values = '';
			foreach($data as $key => $value)
			{
				$sql_fields .= ( !empty($sql_fields) ? ',' : '' ) . $db->nameQuote($key);
				$sql_values .= ( !empty($sql_values) ? ',' : '' ) . $db->Quote($value);
			}
			$sql = 'INSERT INTO '.$db->nameQuote('#__ak_stats').' ('.$sql_fields.') VALUES ('.
				$sql_values.')';
			$db->setQuery($sql);
			if($db->query() == false)
			{
				$db->propagateToObject($caller);
				return false;
			}
			return $db->insertid();
		}
		else
		{
			$sql_set = '';
			foreach($data as $key => $value)
			{
				if($key == 'id') continue;
				$sql_set .= ( !empty($sql_set) ? ',' : '' );
				$sql_set .= $db->nameQuote($key).'='.$db->Quote($value);
			}
			$sql = 'UPDATE '.$db->nameQuote('#__ak_stats').' SET '.$sql_set.' WHERE '.
				$db->nameQuote('id').'='.$db->Quote($id);
			$db->setQuery($sql);
			$ret = $db->query();

			$db->propagateToObject($caller);
			return null;
		}
	}

	/**
	 * Loads and returns a backup statistics record as a hash array
	 * @param int $id Backup record ID
	 * @return array
	 */
	public static function get_statistics($id)
	{
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );
		$query = 'SELECT * FROM '.$db->nameQuote('#__ak_stats').' WHERE '.
			$db->nameQuote('id').' = '.$db->Quote($id);
		$db->setQuery($query);
		return $db->loadAssoc(true);
	}

	/**
	 * Completely removes a backup statistics record
	 * @param int $id Backup record ID
	 * @return bool True on success
	 */
	public static function delete_statistics($id)
	{
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );
		$query = 'DELETE FROM '.$db->nameQuote('#__ak_stats').' WHERE '.
			$db->nameQuote('id').' = '.$db->Quote($id);
		$db->setQuery($query);
		$result = $db->query();
		return !($result === false);
	}

	/**
	 * Returns a list of backup statistics records, respecting the pagination
	 * 
	 * @param	int		$limitstart	Offset in the recordset to start from
	 * @param	int		$limit		How many records to return at once
	 * @param	array	$filters	An array of filters to apply to the results. Alternatively you can just pass a profile ID to filter by that profile.
	 * @param	array	$order		Record ordering information (by and ordering) 
	 * 
	 * @return array
	 */
	function &get_statistics_list($limitstart = null, $limit = null, $filters = null, $order = null)
	{
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );
		
		$whereArray = array();
		if(!empty($filters))
		{
			if(is_array($filters)) {
				if(!empty($filters)) {
					// Parse the filters array
					foreach($filters as $f)
					{
						$clause = $db->nameQuote($f['field']);
						if(array_key_exists('operand', $f)) {
							$clause .= ' '.strtoupper($f['operand']).' ';
						} else {
							$clause .= ' = ';
						}
						if($f['operand'] == 'BETWEEN') {
							$clause .= $db->Quote($f['value']) . ' AND ' . $db->Quote($f['value2']);
						} elseif($f['operand'] == 'LIKE') {
							$clause .= '\'%'.$db->getEscaped($f['value']).'%\'';
						} else {
							$clause .= $db->Quote($f['value']);
						}
						$whereArray[] = "($clause)";
					}
				}
			} else {
				// Legacy mode: profile ID given
				$whereArray[] = '('.$db->nameQuote('profile_id').' = '.$db->Quote($filters).')';
			}
		}
		if(empty($whereArray)) {
			$where = '';
		} else {
			$where = implode(' AND ', $whereArray);
		}
		
		if(empty($order) || !is_array($order)) {
			$order = array(
				'by'		=> 'id',
				'order'		=> 'DESC'
			);
		}
		
		$query = "SELECT * FROM ".$db->nameQuote('#__ak_stats').
			(empty($where) ? '' : ' WHERE '.$where).
			" ORDER BY ".$db->nameQuote($order['by'])." ".strtoupper($order['order']);
		$db->setQuery($query, $limitstart, $limit);

		$list = $db->loadAssocList();

		return $list;
	}
	/**
	 * Return the total number of statistics records
	 * 
	 * @param	array	$filters	An array of filters to apply to the results. Alternatively you can just pass a profile ID to filter by that profile.
	 * 
	 * @return int
	 */
	function get_statistics_count($filters = null)
	{
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );
		
		$whereArray = array();
		if(!empty($filters))
		{
			if(is_array($filters)) {
				if(!empty($filters)) {
					// Parse the filters array
					foreach($filters as $f)
					{
						$clause = $db->nameQuote($f['field']);
						if(array_key_exists('operand', $f)) {
							$clause .= ' '.strtoupper($f['operand']).' ';
						} else {
							$clause .= ' = ';
						}
						if($f['operand'] == 'BETWEEN') {
							$clause .= $db->Quote($f['value']) . ' AND ' . $db->Quote($f['value2']);
						} elseif($f['operand'] == 'LIKE') {
							$clause .= '\'%'.$db->getEscaped($f['value']).'%\'';
						} else {
							$clause .= $db->Quote($f['value']);
						}
						$whereArray[] = "($clause)";
					}
				}
			} else {
				// Legacy mode: profile ID given
				$whereArray[] = '('.$db->nameQuote('profile_id').' = '.$db->Quote($filters).')';
			}
		}
		if(empty($whereArray)) {
			$where = '';
		} else {
			$where = implode(' AND ', $whereArray);
		}
		
		$query = 'SELECT COUNT(*) FROM '.$db->nameQuote('#__ak_stats'). (empty($where) ? '' : ' WHERE '.$where);
		$db->setQuery($query);
		return $db->loadResult();
	}

	/**
	 * Returns an array with the specifics of running backups
	 * @return unknown_type
	 */
	public static function get_running_backups($tag = null)
	{
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );
		$query = "SELECT * FROM ".$db->nameQuote('#__ak_stats') .
			' WHERE ('.$db->nameQuote('status').' = '.$db->Quote('run').') AND '.
			'NOT('.$db->nameQuote('archivename').' = '.$db->Quote('').')';
		if(!empty($tag)) {
			$query .= ' AND ('.$db->nameQuote('origin').'='.$db->Quote($tag).')';
		}
		$db->setQuery($query);
		return $db->loadAssocList();
	}

	/**
	 * Multiple backup attempts can share the same backup file name. Only
	 * the last backup attempt's file is considered valid. Previous attempts
	 * have to be deemed "obsolete". This method returns a list of backup
	 * statistics ID's with "valid"-looking names. IT DOES NOT CHECK FOR THE
	 * EXISTENCE OF THE BACKUP FILE!
	 * @param bool $useprofile If true, it will only return backup records of the current profile
	 * @param array $tagFilters Which tags to include; leave blank for all. If the first item is "NOT", then all tags EXCEPT those listed will be included.
	 * @return array A list of ID's for records w/ "valid"-looking backup files
	 */
	public static function &get_valid_backup_records($useprofile = false, $tagFilters = array())
	{
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );

		$query =
			'SELECT '.$db->nameQuote('id').' FROM '.$db->nameQuote('#__ak_stats').
			' WHERE '.
			'`filesexist` = 1 AND'.
			$db->nameQuote('id').' IN ('.
				'SELECT MAX('.$db->nameQuote('id').') AS '.$db->nameQuote('id').
				' FROM '.$db->nameQuote('#__ak_stats').' WHERE '.
				$db->nameQuote('status').' = '.$db->Quote('complete').' GROUP BY '.
				$db->nameQuote('absolute_path').
			') AND NOT ('.$db->nameQuote('absolute_path').' = '.$db->Quote('').')';
		if($useprofile)
		{
			$profile_id = self::get_active_profile();
			$query .= " AND (".$db->nameQuote('profile_id')." = ".$db->Quote($profile_id).")";
		}
		if(!empty($tagFilters)) {
			$operator = '';
			$first = array_shift($tagFilters);
			if($first == 'NOT') {
				$operator = 'NOT';
			} else {
				array_unshift($tagFilters, $first);
			}
			
			$quotedTags = array();
			foreach($tagFilters as $tag) $quotedTags[] = $db->Quote($tag);
			$filter = implode(', ', $quotedTags);
			unset($quotedTags);
			$query .= " AND $operator (".$db->nameQuote('tag').' IN ('.$filter.'))';
		}
		$query .= ' ORDER BY '.$db->nameQuote('id').' DESC';
		$db->setQuery($query);
		$array = $db->loadResultArray();
		return $array;
	}

	/**
	 * Invalidates older records sharing the same $archivename
	 * @param string $archivename
	 */
	public static function remove_duplicate_backup_records($archivename)
	{
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG,"Removing any old records with $archivename filename");
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );

		$query = 'SELECT '.$db->nameQuote('id').' FROM '.$db->nameQuote('#__ak_stats').
			' WHERE '.
			$db->nameQuote('archivename').' = '.$db->Quote($archivename).
			' ORDER BY '.$db->nameQuote('id').' DESC';
		$db->setQuery($query);
		$array = $db->loadResultArray();

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG,count($array)." records found");

		// No records?! Quit.
		if(empty($array)) return;
		// Only one record. Quit.
		if(count($array) == 1) return;

		// Shift the first (latest) element off the array
		$currentID = array_shift($array);

		// Invalidate older records
		self::invalidate_backup_records($array);
	}

	/**
	 * Marks the specified backup records as having no files
	 * @param array $ids Array of backup record IDs to ivalidate
	 */
	public static function invalidate_backup_records($ids)
	{
		if(empty($ids)) return false;
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );
		$list = implode(',', $ids);
		$sql = 'UPDATE `#__ak_stats` SET `filesexist` = 0 WHERE `id` IN ('.$list.')';
		$db->setQuery($sql);
		return $db->query();
	}
	
	/**
	 * Gets a list of records with remotely stored files in the selected remote storage
	 * provider and profile.
	 * 
	 * @param $profile int (optional) The profile to use. Skip or use null for active profile.
	 * @param $engine string (optional) The remote engine to looks for. Skip or use null for the active profile's engine.
	 * @return array
	 */
	public static function get_valid_remote_records($profile = null, $engine = null)
	{
		$config =& AEFactory::getConfiguration();
		$result = array();
		
		if(is_null($profile)) $profile = self::get_active_profile();
		if(is_null($engine)) $engine = $config->get('akeeba.advanced.proc_engine','');
		
		if(empty($engine)) return $result;
		
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );
		$sql = 'SELECT * FROM '.$db->nameQuote('#__ak_stats').' WHERE '.
			$db->nameQuote('profile_id').' = '.$db->Quote($profile).' AND '.
			$db->nameQuote('remote_filename').' LIKE \''.$db->getEscaped($engine).'://%\' ORDER BY '.
			$db->nameQuote('id').' ASC';
		$db->setQuery($sql);
		return $db->loadAssocList();
	}

	/**
	 * Returns the filter data for the entire filter group collection
	 * @return array
	 */
	public static function &load_filters()
	{
		// Load the filter data from the database
		$profile_id = self::get_active_profile();
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );

		// Load the INI format local configuration dump off the database
		$sql = "SELECT ".$db->nameQuote('filters').' FROM '.$db->nameQuote('#__ak_profiles')
			.' WHERE '.
			$db->nameQuote('id').' = '.$db->Quote($profile_id);
		$db->setQuery($sql);
		$all_filter_data = $db->loadResult();

		if(is_null($all_filter_data) || empty($all_filter_data))
		{
			$all_filter_data = array();
		}
		else
		{
			if(function_exists('get_magic_quotes_runtime'))
			{
				if(@get_magic_quotes_runtime())
				{
					$all_filter_data = stripslashes($all_filter_data);
				}
			}
			$all_filter_data = @unserialize($all_filter_data);
			if(empty($all_filter_data)) $all_filter_data = array(); // Catch unserialization errors
		}

		return $all_filter_data;
	}

	/**
	 * Saves the nested filter data array $filter_data to the database
	 * @param	array	$filter_data	The filter data to save
	 * @return	bool	True on success
	 */
	public static function save_filters(&$filter_data)
	{
		$profile_id = self::get_active_profile();
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );

		// Load the INI format local configuration dump off the database
		$sql = "UPDATE ".$db->nameQuote('#__ak_profiles').' SET '.
			$db->nameQuote('filters').'='.$db->Quote(serialize($filter_data))
			.' WHERE '.
			$db->nameQuote('id').' = '.$db->Quote($profile_id);
		$db->setQuery($sql);
		$db->query();

		$errors = $db->getError();
		return empty($errors);
	}

	/**
	 * Gets the best matching database driver class, according to CMS settings
	 * @param bool $use_platform If set to false, it will forcibly try to assign one of the primitive type (AEDriverMySQL/AEDriverMySQLi) and NEVER tell you to use an AEPlatformDriver* class
	 * @return string
	 */
	public static function get_default_database_driver( $use_platform = true )
	{
		if( defined('AKEEBACLI') )
		{
			$driver = AEUtilJconfig::getValue('dbtype');
		}
		else
		{
			$jconfig =& JFactory::getConfig();
			$driver = $jconfig->getValue('config.dbtype');
		}

		// Let's see what driver Joomla! uses...
		if( $use_platform )
		{
			$hasNookuContent = file_exists(JPATH_ROOT.'/plugins/system/nooku.php');
			switch($driver)
			{
				// MySQL or MySQLi drivers are known to be working; use their
				// Akeeba Engine extended version, AEDriverPlatformJoomla
				case 'mysql':
					if($hasNookuContent) {
						return 'AEDriverMysql';
					} else {
						return 'AEDriverPlatformJoomla';
					}
					break;

				case 'mysqli':
					if($hasNookuContent) {
						return 'AEDriverMysqli';
					} else {
						return 'AEDriverPlatformJoomla';
					}
					break;

				// Some custom driver. Uh oh!
				default:
					break;
			}
		}

		// Is this a subcase of mysqli or mysql drivers?
		if( strtolower(substr($driver, 0, 6)) == 'mysqli' )
		{
			return 'AEDriverMysqli';
		}
		elseif( strtolower(substr($driver, 0, 5)) == 'mysql' )
		{
			return 'AEDriverMysql';
		}

		// If we're still here, we have to guesstimate the correct driver. All bets are off.
		if(function_exists('mysqli_connect'))
		{
			// MySQLi available. Let's use it.
			return 'AEDriverMysqli';
		}
		else
		{
			// MySQLi is not available; let's use standard MySQL.
			return 'AEDriverMysql';
		}
	}

	/**
	 * Returns a set of options to connect to the default database of the current CMS
	 * @return array
	 */
	public static function get_platform_database_options()
	{
		static $options;

		if(empty($options))
		{
			if(defined('AKEEBACLI'))
			{
				$options = array(
					'host'		=> AEUtilJconfig::getValue('host'),
					'user'		=> AEUtilJconfig::getValue('user'),
					'password'	=> AEUtilJconfig::getValue('password'),
					'database'	=> AEUtilJconfig::getValue('db'),
					'prefix'	=> AEUtilJconfig::getValue('dbprefix')
				);
			}
			else
			{
				$conf =& JFactory::getConfig();
				$options = array(
					'host'		=> $conf->getValue('config.host'),
					'user'		=> $conf->getValue('config.user'),
					'password'	=> $conf->getValue('config.password'),
					'database'	=> $conf->getValue('config.db'),
					'prefix'	=> $conf->getValue('config.dbprefix')
				);
			}
		}

		return $options;
	}

	/**
	 * Provides a platform-specific translation function
	 * @param string $key The translation key
	 * @return string
	 */
	public static function translate($key)
	{
		if(defined('AKEEBACLI'))
		{
			if(class_exists('AEUtilTranslate'))
			{
				return AEUtilTranslate::_($key); // Doing so forces autoloading of the custom translator class
			}
		}
		return JText::_($key);
	}

	/**
	 * Populates global constants holding the Akeeba version
	 */
	public static function load_version_defines()
	{
		if(file_exists(JPATH_COMPONENT_ADMINISTRATOR.DS.'version.php'))
		{
			require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'version.php');
		}

		if(!defined('AKEEBA_VERSION')) define("AKEEBA_VERSION", "svn");
		if(!defined('AKEEBA_PRO')) define('AKEEBA_PRO', false);
		if(!defined('AKEEBA_DATE')) {
			jimport('joomla.utilities.date');
			$date = new JDate();
			define( "AKEEBA_DATE", $date->toFormat('%Y-%m-%d') );
		}
	}

	/**
	 * Returns the platform name and version
	 * @param string $platform_name Name of the platform, e.g. Joomla!
	 * @param string $version Full version of the platform
	 */
	public static function getPlatformVersion( &$platform_name, &$version )
	{
		if( !class_exists('JVersion') )
		{
			$path = JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'version.php';
			if(file_exists($path)) {
				require_once $path;
			} else {
				$path = JPATH_SITE.DS.'includes'.DS.'version.php';
				require_once $path;
			}
		}

		$platform_name = "Joomla!";
		$v = new JVersion();
		$version = $v->getLongVersion();
	}

	/**
	 * Logs platform-specific directories with _AE_LOG_INFO log level
	 */
	public static function log_platform_special_directories()
	{
		AEUtilLogger::WriteLog(_AE_LOG_INFO, "JPATH_BASE         :" . JPATH_BASE );
		AEUtilLogger::WriteLog(_AE_LOG_INFO, "JPATH_SITE         :" . JPATH_SITE );
		AEUtilLogger::WriteLog(_AE_LOG_INFO, "JPATH_ROOT         :" . JPATH_ROOT );
		AEUtilLogger::WriteLog(_AE_LOG_INFO, "JPATH_CACHE        :" . JPATH_CACHE );
		AEUtilLogger::WriteLog(_AE_LOG_INFO, "Computed root      :" . self::get_site_root() );
	}

	/**
	 * Loads a platform-specific software configuration option
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public static function get_platform_configuration_option($key, $default)
	{
		// Get the component configuration option WITHOUT using the bloody ever-changing Joomla! API...
		return AEUtilComconfig::getValue($key, $default);
	}

	/**
	 * Returns a list of emails to the Super Administrators
	 * @return unknown_type
	 */
	public static function get_administrator_emails()
	{
		$db =& AEFactory::getDatabase( self::get_platform_database_options() );
		$query = 'SELECT name, email FROM #__users'.
				' WHERE usertype = \'Super Administrator\' ';
		$db->setQuery($query);
		$superAdmins =& $db->loadObjectList();

		$mails = array();
		if(!empty($superAdmins))
		{
			foreach($superAdmins as $admin)
			{
				$mails[] = $admin->email;
			}
		}

		return $mails;
	}

	/**
	 * Sends a very simple email using the platform's emailer facility
	 * @param string $to
	 * @param string $subject
	 * @param string $body
	 */
	public static function send_email($to, $subject, $body, $attachFile = null)
	{
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG,"-- Fetching mailer object" );
		
		$mailer =& self::getMailer();
		
		if(!is_object($mailer)) {
			AEUtilLogger::WriteLog(_AE_LOG_WARNING,"Could not send email to $to - Reason: Mailer object is not an object; please check your system settings");
			return false;
		}
		
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG,"-- Creating email message");
		
		$recipient = array($to);
		$mailer->addRecipient($recipient);
		$mailer->setSubject($subject);
		$mailer->setBody($body);

		if(!empty($attachFile))
		{
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, "-- Attaching $attachFile");
			
			if(!file_exists($attachFile) || !(is_file($attachFile) || is_link($attachFile))) {
				AEUtilLogger::WriteLog(_AE_LOG_WARNING, "The file does not exist, or it's not a file; no email sent");
				return false;
			}
			
			if(!is_readable($attachFile)) {
				AEUtilLogger::WriteLog(_AE_LOG_WARNING, "The file is not readable; no email sent");
				return false;
			}
			
			$filesize = @filesize($attachFile);
			if($filesize) {
				// Check that we have AT LEAST 2.5 times free RAM as the filesize (that's how much we'll need)
				if(!function_exists('ini_get')) {
					// Assume 8Mb of PHP memory limit (worst case scenario)
					$totalRAM = 8388608;
				} else {
					$totalRAM = ini_get('memory_limit');
					if(strstr($totalRAM, 'M')) {
						$totalRAM = (int)$totalRAM * 1048576;
					} elseif(strstr($totalRAM, 'K')) {
						$totalRAM = (int)$totalRAM * 1024;
					} elseif(strstr($totalRAM, 'G')) {
						$totalRAM = (int)$totalRAM * 1073741824;
					} else {
						$totalRAM = (int)$totalRAM;
					}
					if($totalRAM <= 0) {
						// No memory limit? Cool! Assume 1Gb of available RAM (which is absurdely abundant as of March 2011...)
						$totalRAM = 1086373952;
					}
				}
				if(!function_exists('memory_get_usage')) {
					$usedRAM = 8388608;
				} else {
					$usedRAM = memory_get_usage();
				}
				
				$availableRAM = $totalRAM - $usedRAM;
				
				if($availableRAM < 2.5*$filesize) {
					AEUtilLogger::WriteLog(_AE_LOG_WARNING, "The file is too big to be sent by email. Please use a smaller Part Size for Split Archives setting.");
					AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Memory limit $totalRAM bytes -- Used memory $usedRAM bytes -- File size $filesize -- Attachment requires approx. ".(2.5*$filesize)." bytes");
					return false;
				}
			} else {
				AEUtilLogger::WriteLog(_AE_LOG_WARNING, "Your server fails to report the file size of $attachFile. If the backup crashes, please use a smaller Part Size for Split Archives setting");
			}
			
			$mailer->addAttachment($attachFile);
		}
		
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG,"-- Sending message");

		$result = $mailer->Send();

		if($result instanceof JException)
		{
			AEUtilLogger::WriteLog(_AE_LOG_WARNING,"Could not email $to:");
			AEUtilLogger::WriteLog(_AE_LOG_WARNING,$result->message);
			$ret = $result->message;
			unset($result);
			unset($mailer);
			return $ret;
		}
		else
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG,"-- Email sent");
			return true;
		}
	}

	/**
	 * Deletes a file from the local server using direct file access or FTP
	 * @param string $file
	 * @return bool
	 */
	public static function unlink($file)
	{
		if(function_exists('jimport')) {
			jimport('joomla.filesystem.file');
			$result = JFile::delete($file);
			if(!$result) $result = @unlink($file);
		} else {
			$result = @unlink($file);
		}
		return $result;
	}

	/**
	 * Moves a file around within the local server using direct file access or FTP
	 * @param string $from
	 * @param string $to
	 * @return bool
	 */
	public static function move($from, $to)
	{
		if(function_exists('jimport')) {
			jimport('joomla.filesystem.file');
			$result = JFile::move($from, $to);
			// JFile failed. Let's try rename()
			if(!$result)
			{
				$result = @rename($from, $to);
			}
			// Rename failed, too. Let's try copy/delete
			if(!$result)
			{
				// Try copying with JFile. If it fails, use copy().
				$result = JFile::copy($from, $to);
				if(!$result) $result = @copy($from, $to);
	
				// If the copy succeeded, try deleting the original with JFile. If it fails, use unlink().
				if($result)
				{
					$result = self::unlink($from);
				}
			}
		} else {
			$result = @rename($from, $to);
			if(!$result) {
				$result = @copy($from, $to);
				if($result) {
					$result = self::unlink($from);
				}
			}
		}
		return $result;
	}

	/**
	 * Registers Akeeba Engine's core classes with JLoader
	 * @param string $path_prefix The path prefix to look in
	 */
	protected static function register_akeeba_engine_classes($path_prefix)
	{
		global $Akeeba_Class_Map;
		jimport('joomla.filesystem.folder');
		foreach($Akeeba_Class_Map as $class_prefix => $path_suffix)
		{
			// Bail out if there is such directory, so as not to have Joomla! throw errors
			if(!@is_dir($path_prefix.DS.$path_suffix)) continue;

			$file_list = JFolder::files( $path_prefix.DS.$path_suffix, '.*\.php' );
			if(is_array($file_list) && !empty($file_list)) foreach($file_list as $file)
			{
				$class_suffix = ucfirst(basename($file, '.php'));
				JLoader::register($class_prefix.$class_suffix, $path_prefix.DS.$path_suffix.DS.$file );
			}
		}
	}

	/**
	 * Joomla!-specific function to get an instance of the mailer class
	 * @return JMail
	 */
	private static function &getMailer()
	{
		if(!defined('AKEEBACLI'))
		{
			$mailer =& JFactory::getMailer();
			if(!is_object($mailer)) {
				AEUtilLogger::WriteLog(_AE_LOG_WARNING,"Fetching Joomla!'s mailer was impossible; imminent crash!");
			} else {
				$emailMethod = $mailer->Mailer;
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG,"-- Joomla!'s mailer is using $emailMethod mail method.");
			}
			return $mailer;
		}

		jimport('joomla.mail.mail');

		$sendmail 	= AEUtilJconfig::getValue('sendmail');
		$smtpauth 	= AEUtilJconfig::getValue('smtpauth');
		$smtpuser 	= AEUtilJconfig::getValue('smtpuser');
		$smtppass  	= AEUtilJconfig::getValue('smtppass');
		$smtphost 	= AEUtilJconfig::getValue('smtphost');
		$smtpsecure	= AEUtilJconfig::getValue('smtpsecure');
		$smtpport	= AEUtilJconfig::getValue('smtpport');
		$mailfrom 	= AEUtilJconfig::getValue('mailfrom');
		$fromname 	= AEUtilJconfig::getValue('fromname');
		$mailer 	= AEUtilJconfig::getValue('mailer');

		// Create a JMail object
		$mail 		=& JMail::getInstance();

		// Default mailer is to use PHP's mail function
		switch ($mailer)
		{
			case 'smtp' :
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG,"-- Using SMTP");
				$mail->useSMTP($smtpauth, $smtphost, $smtpuser, $smtppass, $smtpsecure, $smtpport);
				break;
			case 'sendmail' :
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG,"-- Using sendmail");
				$mail->useSendmail($sendmail);
				break;
			default :
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG,"-- Using PHP email()");
				$mail->IsMail();
				break;
		}

		$mail->Encoding = '8bit';
		$mail->CharSet = 'utf-8';

		// Set default sender
		$mail->setSender(array ($mailfrom, $fromname));

		return $mail;
	}

}

if(defined('AKEEBACLI'))
{
	// Load the JLoader class
	define('JPATH_PLATFORM',1);
	require_once(JPATH_SITE.DS.'libraries'.DS.'loader.php');

	// Load the JError and JException classes
	require_once(JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'base'.DS.'object.php');
	require_once(JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'error'.DS.'exception.php');
	require_once(JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'error'.DS.'error.php');

	// Custom callback for fatal Joomla! API errors (i.e. when an E_ERROR is raised)
	class AkeebaCustomError
	{
		function customErrorPage(& $error)
		{
			echo "\n\n";
			echo "-------------------------------------------------------------------------------\n";
			echo "JOOMLA! FRAMEWORK FATAL ERROR {$error->code}\n";
			echo $error->message."\n";
			echo "-------------------------------------------------------------------------------\n";

			$backtrace	= $error->getTrace();
			if( is_array( $backtrace ) )
			{
				echo "Stack Dump for Debugging (#/function/file):\n";
				$j	=	1;
				for( $i = count( $backtrace )-1; $i >= 0 ; $i-- )
				{
					echo "$j\t";
					if( isset( $backtrace[$i]['class'] ) ) {
						echo "\t".$backtrace[$i]['class'].$backtrace[$i]['type'].$backtrace[$i]['function'].'()';
					} else {
						echo "\t".$backtrace[$i]['function'].'()';
					}
					if( isset( $backtrace[$i]['file'] ) ) {
						echo "\t".$backtrace[$i]['file'].':'.$backtrace[$i]['line'];
					}
					echo "\n";
					$j++;
				}
				echo "-------------------------------------------------------------------------------\n";
			}

			echo "\nThe backup process has failed.\n";
			die();
		}
	}
	$GLOBALS['_JERROR_HANDLERS'][E_ERROR] = array( 'mode' => 'callback', 'options' => array('AkeebaCustomError','customErrorPage') );

	// Simulates JApplication::enqueueMessage() for the command-line clients
	class AkeebaCustomPseudoapp
	{
		public function enqueueMessage($message, $type)
		{
			switch($type)
			{
				case 'error':
					echo "*** ERROR: ";
					break;

				case 'warning':
					echo "*** WARNING: ";
					break;

				default:
					echo "*** NOTICE: ";
					break;
			}

			echo "$message\n";
		}
	}
	global $mainframe;
	$mainframe = new AkeebaCustomPseudoapp();

	// A simplistic implementation of JClientHelper to return FTP options (used by JFile's methods)
	if(!class_exists('JClientHelper'))
	{
		class JClientHelper
		{
			public static function getCredentials($client, $force = false)
			{
				$options = array(
					'enabled'	=> AEUtilJconfig::getValue('ftp_enable'),
					'host'		=> AEUtilJconfig::getValue('ftp_host'),
					'port'		=> AEUtilJconfig::getValue('ftp_port'),
					'user'		=> AEUtilJconfig::getValue('ftp_user'),
					'pass'		=> AEUtilJconfig::getValue('ftp_pass'),
					'root'		=> AEUtilJconfig::getValue('ftp_root')
				);
				return $options;
			}
		}
	}
}
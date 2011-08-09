<?php
defined('_JEXEC') or die('Restricted access');

global $mainframe;

$err_status = false;

$db =& JFactory::getDBO();

$cogs = array();

$cogs_tag =& $this->manifest->getElementByPath('cogs');

/**
	Get the XML tag and convert the sub tags to an array for processing
**/
if (is_a($cogs_tag, 'JSimpleXMLElement') && count($cogs_tag->children())) {
    $cogs_sub_tags =& $cogs_tag->children();
	reset($cogs_sub_tags);
	while (list($key, $value) = each($cogs_sub_tags)) {
		$cog =& $cogs_sub_tags[$key]; 
		$cogs[] = array( 
						'name' => $cog->attributes('name'),
						'group' => $cog->attributes('group'), 
						'title' => $cog->data(),
						'type' => strtolower($cog->name()), 
						'folder' => $this->parent->getPath('source').DS.$cog->attributes('folder'), 
						'installer' => new JInstaller(), 
						'status' => false, 
						'published' => ( $cog->attributes('published') != null) ?(strtolower($cog->attributes('published')) == 'true') ? true : false : false,
						'core' => ( $cog->attributes('core') != null) ? (strtolower($cog->attributes('core')) == 'true') ? true : false : false,
						'enabled' =>  ($cog->attributes('enabled') != null) ?(strtolower($cog->attributes('enabled')) == 'true') ? true : false : false,
						'access' => ( $cog->attributes('access') != null) ? $cog->attributes('access') : 0
						);
	}
}

/**
	Run the installer for each sub component
**/
if (!empty($cogs)) { 
	for ($i = 0; $i < count($cogs); $i++) {
		$cog =& $cogs[$i];
		if ($cog['installer']->install($cog['folder']) && _adjust_settings($db, $cog)) {
			$cog['status'] = true;
		} else {
			$err_status = true;
			break;
		}
	}
}

/**
 * Copy the html override for the admin template menu item form.
 */

$dest_dir = JPath::clean(JPATH_THEMES.DS.$mainframe->getTemplate().'/html/com_menus');
$override_dir = JPath::clean($this->parent->getPath('extension_administrator').'/overrides/com_menus');
if (!JFolder::copy($override_dir, $dest_dir, '', true)) {
	JError::raiseWarning(1, JText::_('Unable to copy menu template override to ' . $dest_dir . ' due to unknown error.'));
	$err_status = true;		
}

/**
	Rollback on error
**/
if ($err_status) {
	$this->parent->abort(JText::_('Component').' '.JText::_('Install').': '.JText::_('Error'), 'component');
	for ($i = 0; $i < count($cogs); $i++) { 
		if ($cogs[$i]['status']) {
			$cogs[$i]['installer']->abort(JText::_($cogs[$i]['type']).' '.JText::_('Install').': '.JText::_('Error'), $cogs[$i]['type']);
			$cogs[$i]['status'] = false;
		}
	}
}
 
function _adjust_settings(&$db, &$cog) {
	if ($cog['type'] == 'plugin' || $cog['type'] == 'module' || $cog['type'] == 'component'){ 
		switch ($cog['type']) {
			case 'plugin':
				$query = 'SELECT * FROM #__plugins WHERE element='.$db->Quote($cog['name']) . ' and folder=' . $db->Quote($cog['group']); 
				break;
			case 'module':
				$query = 'SELECT * FROM #__modules WHERE module='.$db->Quote($cog['name']);
				break;
			case 'component':
				$query = 'SELECT * FROM #__components WHERE name='.$db->Quote($cog['title']);
				break;
		}
				
		// query extension id and client id
		$db->setQuery($query);
		$db_cog = $db->loadObject();

		switch ($cog['type']) {
			case 'plugin':
				$published  =  ($cog['published'])?1:0;
				$core =  ($cog['core'])?1:0;
				$query = 'UPDATE #__plugins set published=' . $published . ', iscore = ' . $core . ', access = ' . $cog['access'] . ' where id = ' . $db_cog->id;
				break;
			case 'module':
				$published  =  ($cog['published'])? 'true':'false';
				$core =  ($cog['core'])?1:0;
				$query = 'UPDATE #__modules set published = ' . $published . ', iscore = ' . $core . ', access = ' . $cog['access'] . ' where id = ' . $db_cog->id;
				break;
			case 'component':
				$enabled  =  ($cog['enabled'])?1:0;
				$core =  ($cog['core'])?1:0;
				$query = 'UPDATE #__components set enabled = ' . $published . ', iscore = ' . $core . ' where id = ' . $db_cog->id;
				break;
		}
		// query extension id and client id
		$db->setQuery($query);
		if ($db->query() == false)
			return false;
	}
	return true;
}


function com_install() { 
	global $err_status;
	return !$err_status;
}

?>
<h3><?php echo JText::_('Bundled Installations'); ?></h3>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title"><?php echo JText::_('Unit'); ?></th>
			<th width="60%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</tfoot>
	<tbody>
		<?php 
			reset($cogs);
			while (list($key, $value) = each($cogs)):
				$cog =&$cogs[$key]; 
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="key"><?php echo $cog['title']; ?> (<?php echo JText::_($cog['type']); ?>)</td>
				<td>
					<?php $style = $cog['status'] ? 'font-weight: bold; color: green;' : 'font-weight: bold; color: red;'; ?>
					<span style="<?php echo $style; ?>"><?php echo $cog['status'] ? JText::_('Installed') : JText::_('NOT Installed'); ?></span>
				</td>
			</tr>
		<?php endwhile; ?>
	</tbody>
</table>

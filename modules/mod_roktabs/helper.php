<?php
/**
 * RokTabs Module
 *
 * @package RocketTheme
 * @subpackage roktabs
 * @version   1.16 November 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
// no direct access
defined( '_JEXEC' ) or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modRokTabsHelper
{
	
	function getList($params)
	{
		global $mainframe;

		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$userId		= (int) $user->get('id');
		
		$count		    = 15; 
		$catid		    = trim( $params->get('catid') );
		$secid		    = trim( $params->get('secid') );
		$show_front	    = $params->get('show_front', 1);
		$aid		    = $user->get('aid', 0);
		$content_type   = $params->get('content_type','joomla');
		$ordering       = $params->get('itemsOrdering');
		$cid            = $params->get('category_id', NULL);
        $text_length    = intval($params->get( 'preview_count', 200) );

		$contentConfig = &JComponentHelper::getParams( 'com_content' );
		$access		= !$contentConfig->get('shownoauth');

		$nullDate	= $db->getNullDate();

		$date =& JFactory::getDate();
		$now = $date->toMySQL();

        $where = '';
		
		// ensure should be published
		$where .= " AND ( a.publish_up = ".$db->Quote($nullDate)." OR a.publish_up <= ".$db->Quote($now)." )";
		$where .= " AND ( a.publish_down = ".$db->Quote($nullDate)." OR a.publish_down >= ".$db->Quote($now)." )";
		
	    // ordering
		switch ($ordering) {
			case 'date' :
				$orderby = 'a.created ASC';
				break;
			case 'rdate' :
				$orderby = 'a.created DESC';
				break;
			case 'alpha' :
				$orderby = 'a.title';
				break;
			case 'ralpha' :
				$orderby = 'a.title DESC';
				break;
			case 'order' :
				$orderby = 'a.ordering';
				break;
			default :
				$orderby = 'a.id DESC';
				break;
		}

		// content specific stuff
        if ($content_type=='joomla') {
            // start Joomla specific
            
            $catCondition = '';
            $secCondition = '';

            if ($show_front != 2) {
        		if ($catid)
        		{
        			$ids = explode( ',', $catid );
        			JArrayHelper::toInteger( $ids );
        			$catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $ids ) . ')';
        		}
        		if ($secid)
        		{
        			$ids = explode( ',', $secid );
        			JArrayHelper::toInteger( $ids );
        			$secCondition = ' AND (s.id=' . implode( ' OR s.id=', $ids ) . ')';
        		}
        	}
		
    		// Content Items only
    		$query = 'SELECT a.*, ' .
    			' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
    			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug,'.
                ' CHAR_LENGTH( a.fulltext ) AS readmore ' .
    			' FROM #__content AS a' .
    			($show_front == '0' ? ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
    			($show_front == '2' ? ' INNER JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
    			' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
    			' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
    			' WHERE a.state = 1'. $where .' AND s.id > 0' .
    			($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
    			($catid && $show_front != 2 ? $catCondition : '').
    			($secid && $show_front != 2 ? $secCondition : '').
    			($show_front == '0' ? ' AND f.content_id IS NULL ' : '').
    			' AND s.published = 1' .
    			' AND cc.published = 1' .
    			' ORDER BY '. $orderby;
    		// end Joomla specific
	    } else {
		    // start K2 specific
		    require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
		    
    		$query = "SELECT a.*, c.name as categoryname,c.id as categoryid, c.alias as categoryalias, c.params as categoryparams, CHAR_LENGTH( a.fulltext ) AS readmore " .
    		" FROM #__k2_items as a".
    		" LEFT JOIN #__k2_categories c ON c.id = a.catid";
	
    		$query .= " WHERE a.published = 1"
    		." AND a.access <= {$aid}"
    		." AND a.trash = 0"
    		." AND c.published = 1"
    		." AND c.access <= {$aid}"
    		." AND c.trash = 0"
    		;
	
   		    if ($params->get('catfilter')){
    			if (!is_null($cid)) {
    				if (is_array($cid)) {
    					$query .= " AND a.catid IN(".implode(',', $cid).")";
    				}
    				else {
    					$query .= " AND a.catid={$cid}";
    				}
    			}
    		}

    		if ($params->get('FeaturedItems')=='0')
    			$query.= " AND a.featured != 1";
	
    		if ($params->get('FeaturedItems')=='2')
    			$query.= " AND a.featured = 1";
    			
    		$query .= $where . ' ORDER BY ' . $orderby;
    		// end K2 specific
		}
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();

		// Process the prepare content plugins
		JPluginHelper::importPlugin('content');

		$i		= 0;
		$lists	= array();
		foreach ( $rows as $row )
		{


            $user		=& JFactory::getUser();
			$dispatcher   =& JDispatcher::getInstance();
			$results = @$dispatcher->trigger('onPrepareContent', array (& $row, & $params, 0));
			
			$text = JHTML::_('content.prepare',$row->introtext,$contentConfig);
			
			$lists[$i]->id = $row->id;
			$lists[$i]->created = $row->created;
			$lists[$i]->modified = $row->modified;
            if ($row->access >  $user->get('aid', 0)){
                $lists[$i]->link = JRoute::_("index.php?option=com_user&view=login");
                $lists[$i]->readmore_register = true;
            }
			else if ($content_type=='joomla') {
			    $lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
                $lists[$i]->readmore_register = false;
			} else {
			    $lists[$i]->link = JRoute::_(K2HelperRoute::getItemRoute($row->id.':'.$row->alias, $row->catid.':'.$row->categoryalias));
                $lists[$i]->readmore_register = false;
			}
			$lists[$i]->title = htmlspecialchars( $row->title );
            $lists[$i]->readmode = $row->readmore;
			$lists[$i]->introtext = modRokTabsHelper::prepareContent( $text, $text_length, $params->get('show_readmore'));

            if ($params->get('show_readmore') && $row->readmore){
                ob_start();
                ?>
                  <a href="<?php echo $lists[$i]->link; ?>" class="readon"><span>
                    <?php if ($lists[$i]->readmore_register) :
                      echo JText::_('Register to read more...');
                    elseif ($readmore = $params->get('readmore')) :
                      echo $readmore;
                    else :
                      echo JText::sprintf('Read more...');
                    endif; ?></span></a>
                <?php
                $readmore_html = ob_get_clean();

                $lists[$i]->introtext .= $readmore_html;
            }
			$i++;
		}
		
		return $lists;
	}
    
	function write_tabs($tabs, $tabs_position, &$list, $tabs_title, $tabs_incremental, $tabs_hideh6, $params = null) {
	
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		
		$app =& JFactory::getApplication();

        if (empty($params)){
            $params = new JParameter("");
        }
		
		$tabs_prefixed		= $params->get('tabs_prefixed', 0);
		$navscrolling		= $params->get('navscrolling', 1);
		
		$tabs_hideh6		= $params->get('tabs_hideh6', 1);
		$tabs_title 		= $params->get('tabs_title', 'content'); // content | h6 | incremental

        $tabs_showicons		= $params->get('tabs_showicons',0);
		$tabs_iconpath		= $params->get('tabs_iconpath', '__module__/images');
		$tabs_icon			= $params->get('tabs_icon');
		$tabs_iconside		= $params->get('tabs_iconside','left');
		
		// replace template token
		$tabs_iconpath = str_replace('__template__','templates'.DS.$app->getTemplate(), $tabs_iconpath);
		$tabs_iconpath = str_replace('__module__', 'modules'.DS.'mod_roktabs', $tabs_iconpath);
		
		$tabs_icons = explode(',',$tabs_icon);
		$iconpath = JPATH_SITE.DS.$tabs_iconpath;
			
		$iconpath_exists = JFolder::exists($iconpath);
	
		if ($tabs_position == 'hidden') $tabstyle = 'style="display: none;"'; 
		else $tabstyle = '';

		$return = '';
		$return .= "<div class='roktabs-links".(!$navscrolling ? ' roktabs-links-noscroll' : '')."' $tabstyle>\n";
		$return .= "<ul class='roktabs-$tabs_position'>\n";
				$tabs = intval($tabs);

				if ($tabs == 0 || $tabs > count($list)) $tabs = count($list);
								
				for($i = 0; $i < $tabs; $i++) {
					if ($list[$i]->introtext != '') {
						$class = '';
						if (!$i) $class = 'first active';
						if ($i == $tabs - 1) $class = 'last';

						switch($tabs_title) {
							case 'incremental':
								if (strlen($tabs_incremental) > 0) $title = $tabs_incremental . '' . ($i + 1);
								else $title = $tabs_incremental . '' . ($i + 1);						
								break;
							case 'h6':
								$regex = '/<h6(?:.+)?>(.+?)<\/h6>/is';
								preg_match($regex, $list[$i]->introtext, $matches);
								if (count($matches) && strlen($matches[1]) > 0) {
									$title = $matches[1];
									if ($tabs_hideh6 == "1") {
										$list[$i]->introtext = str_replace($matches[0], '', $list[$i]->introtext);
									}
									break; 
								}
							default: 
								$title = $list[$i]->title;
						}

						$icon = '';
						if ($tabs_showicons == 1 && $iconpath_exists && isset($tabs_icons[$i]) && $tabs_icon[$i]!='__none__') {
	
							$thisicon = $tabs_icons[$i];
							$thisiconpath = $iconpath.DS.$thisicon;
							if (JFile::exists($thisiconpath)) {
								$thisiconuri = JURI::root(true)."/".$tabs_iconpath."/".$thisicon;
								$icon = '<img src="'.$thisiconuri.'" class="tab-icon" alt="'.$list[$i]->title.'" />';
							}
						}
						
						// set icons on the correct side
						if ($tabs_iconside == 'left') {
							$title = $icon.$title;
							$class.= ' icon-left';
						} else {
							$title = $title.$icon;
							$class.= ' icon-right';
						}
						
						if ($tabs_prefixed) $return .= "<li class=\"$class\"><span class=\"tabnumber\"><span class=\"tabnumber2\">".($i+1)."</span></span><span>$title</span></li>\n";
						else $return .= "<li class=\"$class\"><span>$title</span></li>\n";
					}
				}

		$return .= "</ul>\n";
		$return .= "</div>\n";


		return $return;
	}
	
	function _getJSVersion() {
		if (version_compare(JVERSION, '1.5', '>=') && version_compare(JVERSION, '1.6', '<')){
			if (JPluginHelper::isEnabled('system', 'mtupgrade')){
				return "-mt1.2";
			} else {
				return "";
			}
		} else {
			return "";
		}
	}
	
	function prepareContent( $text, $length=200, $show_readmore=false) {
		// strips tags won't remove the actual jscript
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );

		// cut off text at word boundary if required
		if (strlen($text) > $length) {
			//$text = substr($text, 0, strpos($text, ' ', $length)) . "..." ;
		}
		return $text;
	}
}

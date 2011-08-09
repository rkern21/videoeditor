<?php
/**
 * @package     gantry
 * @subpackage  admin.elements
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();

gantry_import('core.gantryelement');
/**
 * Renders a toggle element
 *
 * @package     gantry
 * @subpackage  admin.elements
 */
class JElementToggle extends GantryElement
{

    static $instances = array();

	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Toggle';

	function fetchElement($name, $value, &$node, $control_name='params')
	{
		global $gantry;
		$hidden = '<input type="hidden" name="'.$name.'" value="_" />';
		
		$options = array ();
        $options[] = array('value'=>1,'text'=>'On/Off','id'=>$name);

		if (!defined('GANTRY_TOGGLE')) {
			$this->template = end(explode(DS, $gantry->templatePath));
			
            gantry_addScript($gantry->gantryUrl.'/admin/widgets/toggle/js/touch.js');
            gantry_addScript($gantry->gantryUrl.'/admin/widgets/toggle/js/toggle.js');
            define('GANTRY_TOGGLE',1);
        }


		//gantry_addInlineScript($this->toggleInit($name));
		
		$checked = ($value == 0) ? '' : 'checked="checked"';
		if ($value == 0) $toggleStatus = 'unchecked';
		else $toggleStatus = 'checked';
		
		return '
		<div class="wrapper">'."\n".'
			<div class="toggle">'."\n".'
				<div class="toggle-container toggle-'.$toggleStatus.'">'."\n".'
					<div class="toggle-sides">'."\n".'
						<div class="toggle-wrapper">'."\n".'
							<div class="toggle-switch"></div>'."\n".'
							<input type="hidden" name="'.$control_name.'['.$name.']" value="'.$value.'" />'."\n".'
							<input type="checkbox" class="toggle-input" id="params'.$name.'" value="'.$value.'" '.$checked.' />'."\n".'
						</div>'."\n".'
						<div class="toggle-button"></div>'."\n".'
					</div>'."\n".'
				</div>'."\n".'
			</div>'."\n".'
		</div>'."\n".'
		';
		
		return "
		<div class='wrapper'>
			<input name='".$control_name."[".$name."]' value='$value' type='hidden' />
			<input type='checkbox' class='toggle' id='params$name' $checked />
		</div>
		";
    }

	function toggleInit($id) {
        // self::$instances[] = $id; // add id to static array

		global $gantry;
		
		$dollar = ($gantry->platform->jslib == 'mootools' && $gantry->platform->jslib_version == '1.1') ? "$" : "document.id";
		
		$js = "
			window.addEvent('domready', function() {
				window.toggle".str_replace("-", "", $id)." = new Toggle('params".$id."', {
					focus: true, 
					onChange: function(state) {
						var value = (state) ? 1 : 0;
						this.container.getPrevious().value = value;
						
						if (Gantry.MenuItemHead) {
							var cache = Gantry.MenuItemHead.Cache[Gantry.Selection];
							if (!cache) cache = new Hash({});
							cache.set('".$id."', value.toString()); 
						}
						
						if (this.container.getParent().getParent() != this.container.getParent().getParent().getParent().getFirst()) return;
						
						var nexts = this.container.getParent().getParent().getParent().getChildren();
						
						if (nexts.length) {
							nexts.each(function(chain) {
								var cls = chain.className.split(' '), type = '';
								cls.each(function(val) {
									if (val.contains('chain-')) type = val.replace('chain-', '');
								});
								
								if (['position', 'groupedselection', 'showmax', 'animation', 'dateformats', 'menuids', 'selectbox', 'category', 'section'].contains(type)) {
									var select = chain.getElement('select');
									if (".$dollar."(select).fireEvent('detach')) {
										if (value) select.fireEvent('attach');
										else select.fireEvent('detach');
									}
								}
								if (['text'].contains(type)) {
									var text = chain.getElement('input[type=\"text\"]');
									if (".$dollar."(text).fireEvent('detach')) {
										if (value) text.fireEvent('attach');
										else text.fireEvent('detach');
									}
								}
								if (['toggle'].contains(type) && chain != this.container.getParent().getParent().getParent().getFirst()) {
									var checkbox = chain.getElement('input[type=checkbox]');
									if (checkbox) {
										(function() {
										if (value) checkbox.fireEvent('attach');
										else checkbox.fireEvent('detach');
										}).delay(10);
									}
								}
							}, this);
						}
					}
				});
			});
		";
		
		return $js;
	}

    static function finalize(){
        //Do something here for the finalize
//        foreach (self::$instances as $instance ){
//
//            echo $instance;
//        }
// 
		gantry_addInlineScript("window.addEvent('domready', function(){ window.gantryToggles = new Toggle(); });");
    }
}

<?php
/**
 * @package     gantry
 * @subpackage  admin.elements
 * @version        3.1.10 March 5, 2011
 * @author        RocketTheme http://www.rockettheme.com
 * @copyright     Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();
/**
 * @package     gantry
 * @subpackage  admin.elements
 */
class JElementOverlays extends JElement {


    function fetchElement($name, $value, &$node, $control_name) {
        global $gantry;
        $output = '';

        $this->template = $gantry->templateName;

        $class = $node->attributes('class') ? $node->attributes('class') : '';
        $preview = $node->attributes('preview') ? $node->attributes('preview') : "false";
        $path = ($node->attributes('path')) ? $node->attributes('path') : false;
        $this->default = ($node->attributes('default')) ? $node->attributes('default') : 'none';

        if (!$path) return "No path set in templateDetails.xml";

        if ($preview == 'true') $class .= " overlay-slider";

        if (!defined('GANTRY_CSS')) {
            gantry_addStyle($gantry->gantryUrl . '/admin/widgets/gantry.css');
            define('GANTRY_CSS', 1);
        }
        if (!defined('GANTRY_SLIDER')) {
            gantry_addScript($gantry->gantryUrl . '/admin/widgets/slider/js/slider.js');
            if (!defined('GANTRY_SLIDER')) define('GANTRY_SLIDER', 1);
        }
        if (!defined('GANTRY_OVERLAYS')) {
            gantry_addInlineScript('var GantryOverlays = {};');
            define('GANTRY_OVERLAYS', 1);
        }

        $this->value = $value;

        $rootPath = str_replace("__TEMPLATE__", $gantry->templatePath, $path);
        $urlPath = str_replace("__TEMPLATE__", $gantry->templateUrl, $path);

        $this->_loadOverlays($name, $rootPath);

        $overlays = array();

        $__overlays = $gantry->retrieveTemp('overlays', 'overlays', array());
        $__paths = $gantry->retrieveTemp('overlays', 'paths', array());

        $overlays[$name] = "'none': {'file': 'overlay-off.png', 'value': 'none', 'name': 'Off', 'path': '" . $gantry->gantryUrl . "/admin/widgets/overlays/images/overlay-off.png'}, ";
        foreach ($__overlays[$name] as $title => $file) {
            $overlays[$name] .= "'" . $file['name'] . "': {'file': '" . $file['file'] . "', 'value': '" . $file['name'] . "', 'name': '" . $title . "', 'path': '" . $urlPath . $file['file'] . "'}, ";
        }

        $overlays[$name] = substr($overlays[$name], 0, -2);

        gantry_addInlineScript('GantryOverlays["' . $name . '"] = new Hash({' . $overlays[$name] . '});');

        $init_function = "sliderInit_" . $gantry->platform->getJSInit();
        $scriptinit = $this->$init_function($name);
        gantry_addInlineScript($scriptinit);

        $output = '
		<div class="wrapper">
		';


        $output .= '<div class="overlay-tip">
			<div class="overlay-tip-left"></div>
			<div class="overlay-tip-mid"><span>Example</span></div>
			<div class="overlay-tip-right"></div>
		</div>';

        if ($preview == 'true') {
            $output .= '<div class="overlay-preview"><div></div></div>';
        }

        $output .= '
		<div id="' . $name . '" class="' . $class . '">
			<div class="slider">
			    <div class="slider2"></div>
				<div class="knob"></div>
			</div>
			<input type="hidden" id="params' . $name . '" class="slider" name="' . $control_name . '[' . $name . ']' . '" value="' . $this->value . '" />
		</div>
		</div>
		';

        $gantry->addTemp('overlays', 'overlays', $__overlays);
        $gantry->addTemp('overlays', 'paths', $__paths);

        return $output;
    }

    function _loadOverlays($elementName, $path) {
        global $gantry;

        $overlays = $gantry->retrieveTemp('overlays', 'overlays', array());
        $__paths = $gantry->retrieveTemp('overlays', 'paths', array());

        $limit = $gantry->get('overlays_list_limit');

        $counter = 0;
        if (is_dir($path) && !isset($__paths[$path])) {
            if ($dh = opendir($path)) {
                $overlays[$elementName] = array();
                while (($file = readdir($dh)) !== false) {
                    if (filetype($path . $file) == 'file' && $this->_isImage($file)) {
                        if ($counter >= $limit) continue;

                        $ext = substr($file, strrpos($file, '.') + 1);
                        $name = substr($file, 0, strrpos($file, '.'));

                        $title = str_replace("-", " ", $name);
                        $title = ucwords($title);

                        $overlays[$elementName][$title] = array('name' => $name, 'ext' => $ext, 'file' => $name . "." . $ext);

                        $counter++;
                    }
                }
                closedir($dh);
                $__paths[$path] = $overlays[$elementName];
            }
        } else {
            $overlays[$elementName] = $__paths[$path];
        }

        ksort($overlays[$elementName]);

        $gantry->addTemp('overlays', 'overlays', $overlays);
        $gantry->addTemp('overlays', 'paths', $__paths);

        return $overlays;
    }

    function _isImage($file) {
        $extension = strtolower(substr($file, -4));

        return ($extension == '.jpg' || $extension == '.bmp' || $extension == '.gif' || $extension == '.png');
    }


    function sliderInit_mt_1_1($name) {
        global $gantry;

        $name2 = str_replace("-", "_", $name);
        $valueName = $this->value;

        $current = $this->value;
        if ($current === false) $current = "none";

        $slider = "$('$name').getElement('.slider')";
        $knob = "$('$name').getElement('.knob')";
        $hidden = "$('params$name')";
        $children = 'GantryOverlays["' . $name . '"].keys();';

        $__overlays = $__overlays = $gantry->retrieveTemp('overlays', 'overlays', array());

        $steps = count($__overlays[$name]);
        $default = '"' . $this->default . '"';

        $js = "
		window.addEvent('domready', function() {
			var current = GantryOverlays['" . $name . "'].keys().indexOf('" . $valueName . "');
			$hidden.addEvents({
				'set': function(value) {
					var slider = window.slider$name2;
					var index = slider.list.indexOf(value);
					slider.set(index);
				}
			});
			window.slider" . $name2 . " = new RokSlider(" . $slider . ", " . $knob . ", {
				steps: GantryOverlays['" . $name . "'].keys().length - 1,
				snap: true,
				onComplete: function() {
					this.knob.removeClass('down');
					
					if (Gantry.MenuItemHead) {
						var cache = Gantry.MenuItemHead.Cache[Gantry.Selection];
						if (!cache) cache = new Hash({});
						cache.set('" . $name . "', this.list[this.step]);
					}
				},
				onDrag: function(now) {
					this.element.getFirst().setStyle('width', now + 10);
					
					var data = GantryOverlays['" . $name . "'].get(this.list[this.step]), width = 0;
					if (this.preview && this.preview.hasClass('overlay-preview')) {
						this.preview.setStyle('background-image', 'url('+data['path']+')');
						width = this.preview.getSize().size.x / 2;
					} else {
						width = " . $slider . ".getSize().size.x / 2;
					}
					
					this.tiptitle.getElement('span').innerHTML = data['name'];
					var x = this.tiptitle.getSize().size.x;
					
					this.tiptitle.setStyle('left', width - x / 2);
				},
				onChange: function(step) {
					" . $hidden . ".setProperty('value', this.list[step]);
					
					if (Gantry.MenuItemHead) {
						Gantry.MenuItemHead.Cache[Gantry.Selection].set('" . $name . "', this.list[step]);
					}
				},
				onTick: function(position) {
					if(this.options.snap) position = this.toPosition(this.step);
					this.knob.setStyle(this.property, position);
					this.fireEvent('onDrag', position);
				}
			});
			var s = window.slider" . $name2 . ";
			s.list = " . $children . ";
			s.preview = $('" . $name . "').getPrevious();
			
			if (s.preview && s.preview.hasClass('overlay-preview')) s.tiptitle = s.preview.getPrevious();
			else s.tiptitle = " . $slider . ".getParent().getPrevious();
			
			s.set(current);
			
			if (s.preview && s.preview.hasClass('overlay-preview')) {
				var tmpColors = ['#fff', '#ddd', '#333', '#000'];
				var data = GantryOverlays['" . $name . "'].get(s.list[s.step]);
				
				s.preview.setStyle('background-image', 'url('+data['path']+')');
				
				s.preview.addEvent('click', function() {
					if (!\$chk(this.indexColor)) this.indexColor = 0;
					else {
						this.indexColor += 1;
						if (this.indexColor > tmpColors.length - 1) this.indexColor = 0;
					}
					
					this.setStyle('background-color', tmpColors[this.indexColor]);
					
				});
			}
			
			if (s.tiptitle) {
				" . $slider . ".addEvents({
					'mouseenter': function() {
						var pattern = GantryOverlays['" . $name . "'].get(s.list[s.step]);
						var name = pattern['name'];

						s.tiptitle.getElement('span').innerHTML = name;
						var x = s.tiptitle.getSize().size.x;

						if (s.preview && s.preview.hasClass('overlay-preview')) {
							s.tiptitle.setStyles({
								'visibility': 'visible',
								'top': -55,
								'left': (s.preview.getSize().size.x / 2) - x / 2
							});
						} else {
							s.tiptitle.setStyles({
								'visibility': 'visible',
								'top': -60,
								'left': (" . $slider . ".getSize().size.x / 2) - x / 2
							});
						}
					},
					'mouseleave': function() {
						s.tiptitle.setStyle('visibility', 'hidden');
					}
				});
			}
			
			$knob.addEvents({
				'mousedown': function() {this.addClass('down');},
				'mouseup': function() {this.removeClass('down');}
			});
		});
		";

        return $js;
    }

    function sliderInit_mt_1_2($name) {
        global $gantry;

        $name2 = str_replace("-", "_", $name);
        $valueName = $this->value;

        $current = $this->value;
        if ($current === false) $current = "none";

        $slider = "document.id('$name').getElement('.slider')";
        $knob = "document.id('$name').getElement('.knob')";
        $hidden = "document.id('params$name')";
        $children = 'GantryOverlays["' . $name . '"].getKeys();';

        $__overlays = $__overlays = $gantry->retrieveTemp('overlays', 'overlays', array());

        $steps = count($__overlays[$name]);
        $default = '"' . $this->default . '"';

        $js = "
			window.addEvent('domready', function() {
				var current = GantryOverlays['" . $name . "'].getKeys().indexOf('" . $valueName . "');
				$hidden.addEvents({
					'set': function(value) {
						var slider = window.slider$name2;
						var index = slider.list.indexOf(value);
						slider.set(index);
					}
				});
				window.slider" . $name2 . " = new RokSlider(" . $slider . ", " . $knob . ", {
					steps: GantryOverlays['" . $name . "'].getKeys().length - 1,
					snap: true,
					onComplete: function() {
						this.knob.removeClass('down');

						if (Gantry.MenuItemHead) {
							var cache = Gantry.MenuItemHead.Cache[Gantry.Selection];
							if (!cache) cache = new Hash({});
							cache.set('" . $name . "', this.list[this.step]);
						}
					},
					onDrag: function(now) {
						this.element.getFirst().setStyle('width', now + 10);

						var data = GantryOverlays['" . $name . "'].get(this.list[this.step]), width = 0;
						if (this.preview && this.preview.hasClass('overlay-preview')) {
							this.preview.setStyle('background-image', 'url('+data['path']+')');
							width = this.preview.getSize().x / 2;
						} else {
							width = " . $slider . ".getSize().x / 2;
						}

						this.tiptitle.getElement('span').innerHTML = data['name'];
						var x = this.tiptitle.getSize().x;

						this.tiptitle.setStyle('left', width - x / 2);
					},
					onChange: function(step) {
						" . $hidden . ".setProperty('value', this.list[step]);

						if (Gantry.MenuItemHead) {
							Gantry.MenuItemHead.Cache[Gantry.Selection].set('" . $name . "', this.list[step]);
						}
					},
					onTick: function(position) {
						if(this.options.snap) position = this.toPosition(this.step);
						this.knob.setStyle(this.property, position);
						this.fireEvent('onDrag', position);
					}
				});
				var s = window.slider" . $name2 . ";
				s.list = " . $children . ";
				s.preview = document.id('" . $name . "').getPrevious();
				s.hiddenEl = ".$hidden.";
				
				if (s.preview && s.preview.hasClass('overlay-preview')) s.tiptitle = s.preview.getPrevious();
				else s.tiptitle = " . $slider . ".getParent().getPrevious();

				s.set(current);

				if (s.preview && s.preview.hasClass('overlay-preview')) {
					var tmpColors = ['#fff', '#ddd', '#333', '#000'];
					var data = GantryOverlays['" . $name . "'].get(s.list[s.step]);

					s.preview.setStyle('background-image', 'url('+data['path']+')');

					s.preview.addEvent('click', function() {
						if (!\$chk(this.indexColor)) this.indexColor = 0;
						else {
							this.indexColor += 1;
							if (this.indexColor > tmpColors.length - 1) this.indexColor = 0;
						}

						this.setStyle('background-color', tmpColors[this.indexColor]);

					});
				}

				if (s.tiptitle) {
					" . $slider . ".addEvents({
						'mouseenter': function() {
							var pattern = GantryOverlays['" . $name . "'].get(s.list[s.step]);
							var name = pattern['name'];

							s.tiptitle.getElement('span').innerHTML = name;
							var x = s.tiptitle.getSize().x;

							if (s.preview && s.preview.hasClass('overlay-preview')) {
								s.tiptitle.setStyles({
									'visibility': 'visible',
									'top': -55,
									'left': (s.preview.getSize().x / 2) - x / 2
								});
							} else {
								s.tiptitle.setStyles({
									'visibility': 'visible',
									'top': -60,
									'left': (" . $slider . ".getSize().x / 2) - x / 2
								});
							}
						},
						'mouseleave': function() {
							s.tiptitle.setStyle('visibility', 'hidden');
						}
					});
				}

				$knob.addEvents({
					'mousedown': function() {this.addClass('down');},
					'mouseup': function() {this.removeClass('down');}
				});
			});
			";

        return $js;
    }
}

?>
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

gantry_import('core.gantrypositions');

/**
 * @package     gantry
 * @subpackage  admin.elements
 */
class JElementPositions extends JElement {
	
	var $maxGrid = GRID_SYSTEM; 
    var $schemas = array("1", "2", "3", "4", "5", "6"), $words = array("2", "3", "4", "5", "6", "7", "8", "9"), $combinations, $customCombinations, $settings, $keyName = "";

	function fetchElement($name, $value, &$node, $control_name)
	{
		global $gantry;

		$output = ''; $lis = ''; $currentScheme = '';
		$this->template = end(explode(DS, $gantry->templatePath));
		
		$this->name = $name;
		$this->value = $value;
		
		$this->default = explode(',', str_replace(' ', '', $node->attributes('default')));
		$this->defaultCount = count($this->default);
		
		// [0] => schemas | [1] => words | [2] => maxgrid | [3] => type
		$opts = $node->children();
        
		$this->maxGrid = (int) $gantry->get('grid_system');
		if (!$this->maxGrid) $this->maxGrid = 12;
		
		if ($node->get('grid')){
			$g = $node->get('grid');
			$this->maxGrid = (int) $g[0]->data();
		} else {
			$this->maxGrid = (int) $gantry->get('grid_system');
		}
		$this->type = "regular";
		if (count($opts)) {
			foreach($opts as $opt) {
				$optName = $opt->name();
				if ($optName == 'words') {
					$this->words = explode(",", $opt->data());
				}
			
				if ($optName == 'schemas') {
					$this->schemas = explode(",", $opt->data());
				}
			
				if ($optName == 'type') {
					$this->type = $opt->data();
				}
			}
		}
		
		$this->keyName = '';
		if ($this->type == 'custom') {
			$tmpName = str_replace("Position", "Schemas", $this->name);
			$tmpSchema = $this->$tmpName;
			$this->keyName = key($tmpSchema[1][0]);
		}
		
		$this->layoutSchemas = $gantry->layoutSchemas[$this->maxGrid];
		$this->defaultMainbodySchemas = $gantry->mainbodySchemas;
		$this->mainbodySchemas = $gantry->mainbodySchemasCombos[$this->maxGrid];
		
        if (!defined('GANTRY_CSS')) {
			gantry_addStyle($gantry->gantryUrl.'/admin/widgets/gantry.css');
			define('GANTRY_CSS', 1);
		}
		
		if (!defined('POSITIONS')) {
			
			if (!defined('GANTRY_SLIDER')) {
				gantry_addScript($gantry->gantryUrl.'/admin/widgets/slider/js/slider.js');
				define('GANTRY_SLIDER', 1);
			}
			gantry_addScript($gantry->gantryUrl.'/admin/widgets/slider/js/unserialize.js');
            $functions = "generalFunctions_" . $gantry->platform->getJSInit();
			gantry_addInlineScript($this->$functions());

			$this->settings = array("words" => $this->words, "schemas" => $this->schemas, "maxGrid" => $this->maxGrid);
			
			if ($this->type == 'custom') $this->customCombinations = $this->getCombinations();
			else $this->combinations = $this->getCombinations();
			
			define('POSITIONS', 1);
		}
		
		$posName = ($name == "mainbodyPosition") ? "sidebar" : str_replace("Position", "", $name);
		$realCount = $gantry->countModules($posName);
		if ($posName == 'sidebar') $realCount += 1;
		if ($realCount > 0) {
			if (!in_array($realCount, $this->schemas)) $realCount = $this->schemas[0];
			$this->default = $this->oneCharConversion($this->layoutSchemas[$realCount]);
			$this->defaultCount = $realCount;
		}
		
		// if the same type of combinations are requested, use the cached ones, otherwise get the new set
		if ($this->type != "custom") {
			$this->combinations = $this->getCombinations();
		}
		
		if ($this->type == "custom") $this->customCombinations = $this->getCombinations();

		if (!in_array((string)$this->defaultCount, $this->schemas)) $this->defaultCount = (int)$this->schemas[0];

		foreach($this->schemas as $scheme) {
			$active = "";
			if ((int)$scheme == $this->defaultCount) {
				$active = ' class="active"';
				$currentLayout = $scheme;
			}
			$lis .= '<li'.$active.'><a href="#"><span>'.$scheme.'</span></a></li>';
		}

		$init_function = "sliderInit_" . $gantry->platform->getJSInit();
		gantry_addInlineScript( $this->$init_function($name));
		
		
		$output = '
		<div class="wrapper">
		<div id="'.$name.'" class="g-position">
			<div class="navigation">
				<span class="title">Positions:</span>
				<ul class="list">'.$lis.'</ul>
			</div>
			<div class="clr"></div>
			<div id="'.$name.'-wrapper" class="col'.$this->maxGrid.' miniatures">
				<div class="mini-container layout-grid-'.$currentLayout.'">
					<div class="mini-grid mini-grid-2">a</div>
					<div class="mini-grid mini-grid-2">b</div>
					<div class="mini-grid mini-grid-2">c</div>
					<div class="mini-grid mini-grid-2">d</div>
					<div class="mini-grid mini-grid-2">e</div>
					<div class="mini-grid mini-grid-2">f</div>
				</div>
				<div class="clr"></div>
				<div class="position">
					<div class="position2"></div>
					<div class="knob"></div>
				</div>
			</div>
			<div class="current-positions">
				<span class="title">'.str_replace('%d', "<strong class='".$name."-currentPosition countPositions'>".$realCount."</strong>", JText::_('CURRENT_POSITIONS')).'</span>
			</div>
			<div class="debug" style="display: none;">
				<span class="title">DEBUG</span>
				<div id="output"></div>
			</div>
			<input type="hidden" id="params'.$name.'" name="'.$control_name.'['.$name.']'.'" value=\'';
			$output .= $this->value;
			$output .= '\' />
		</div>
		</div>
		';
		return $output;
	}
	
	
	function permutations($letters, $num, $filter = 12) {
		// hardcoded cases for speed optimization
		$letter0 = base_convert($letters{0}, 24, 10);
		$letter1 = base_convert($this->lastchar($letters), 24, 10);
		if ($letter0 + $letter1 > $filter) return array();
		if ($filter == 12 && $num == 6) return array("222222");
		if ($num == 1) return $this->oneCharConversion(array($filter));
		
		$last = str_repeat($letters{0}, $num);
		$result = array();
		
		while($last != str_repeat($this->lastchar($letters), $num)) {
			$tmp = 0;
			for ($i = 0; $i < strlen($last); $i++) $tmp += base_convert($last[$i], 24, 10);
			if ($tmp == $filter) $result[] = $last;

			$last = $this->char_add($letters, $last ,$num-1);
		}

		$tmp = 0;
		for ($i = 0; $i < strlen($last); $i++) $tmp += base_convert($last[$i], 24, 10);
		if ($tmp == $filter) $result[] = $last;
				
		return $result;
	}
	
	function char_add($digits, $string, $char) {
	    if ($string{$char} <> $this->lastchar($digits)) {
			$string{$char} = $digits{strpos($digits, $string{$char}) + 1};
			return $string;
	    } else {
			$string = $this->changeall($string, $digits{0}, $char);
			return $this->char_add($digits, $string, $char - 1);
	    }
	} 
	
	function lastchar($string) {
		return $string{strlen($string)-1};
	} 
	
	function changeall($string, $char, $start = 0, $end = 0) {
	    if ($end == 0) $end = strlen($string) - 1;
	    for ($i = $start; $i <= $end; $i++) {
			$string{$i} = $char;
		}
		
		return $string;
	}
	
	function tryCache($implode, $scheme, $words, $grid = 12) {
		global $gantry;
		
		$md5 = md5($grid . implode("", $words) . $scheme);

        $data = $gantry->positions[$grid]->get($md5);

        if (null == $data) {
			$permutation = $this->permutations($implode, (int) $scheme, $grid);
			$save = array();
			$save[$grid][$implode][$scheme] = $permutation;

			//file_put_contents($file, serialize($save));
            $gantry->positions[$grid]->set($md5, serialize($save));
			return $permutation;
		} else {
			$unserial = unserialize($data);
			return $unserial[$grid][$implode][$scheme];
		}
	}
	
	function getCombinations() {
		global $gantry;

		if ($this->type == 'custom') return $this->getCustomCombinations();
		
		$grid = $this->maxGrid;
		$words = $this->words;
		$sets = $this->schemas;
		
		$result = "{";

		$words = $this->oneCharConversion($words);
		
		foreach($sets as $set) {
			$implode = implode("", $words);
			$output[$grid][$implode][$set] = $this->tryCache($implode, (int) $set, $words, $grid);
			$current = $output;

			$tmp = $current[$grid][$implode][$set];			
			sort($tmp);
			$result .= "'$set': ['".implode("', '", $tmp)."'],";
		}
		$result = substr($result, 0, -1) . "}";
		return $result;
	}
	
	function getCustomCombinations() {
		$sets = $this->schemas;
		$name = str_replace("Position", "Schemas", $this->name);
		
		$results = "{";
		$keysref = "{";

		foreach($this->$name as $key => $set) {
			$results .= "'$key': [";
			$keysref .= "'$key': [";

			foreach($set as $combination) {
				$combination = $this->oneCharConversion($combination);

				$results .= "'" . implode("", $combination) . "', ";
				$keysref .= "['" . implode("', '", array_keys($combination)) . "'], ";
			}
			$results = substr($results, 0, -2) . "],";
			$keysref = substr($keysref, 0, -2) . "],";
		}
		$results = substr($results, 0, -1) . "}";
		$keysref = substr($keysref, 0, -1) . "}";
		
		return array($results, $keysref);
	}
	
	function oneCharConversion($words, $decode = false) {
		$dummy = array();
		
		foreach($words as $key => $word) {
			if (!$decode) $dummy[$key] = base_convert((int) $word, 10, 24);
			else $dummy[$key] = base_convert((int) $word, 24, 10);
		}
		
		return $dummy;
	}
	
	function outputCombinations() {
		if (!is_array($this->combinations) && $this->type != 'custom') return $this->combinations;

		return $this->customCombinations[0] . '; this.RT.keys = ' . $this->customCombinations[1];
	}
	
	function getLoadValue() {
		$defaultValue = array($this->defaultCount => $this->default);

		if ($this->type == 'custom') {
			$defaultValue = array($this->defaultCount => $this->defaultMainbodySchemas[$this->maxGrid][$this->defaultCount]);
		}

		if (preg_match("/{/", $this->value)) {
			$value = unserialize($this->value);
            if (isset($value[$this->maxGrid]))
			    $value = $value[$this->maxGrid];
            else
                $value = $defaultValue;
		} else {
			$value = $defaultValue;
		}
		
		$merge = $value + $this->layoutSchemas;

		$result = "{";
		
		$keynames = '';

		if ($this->type == 'custom') {
			foreach($this->defaultMainbodySchemas[$this->maxGrid] as $key => $defaults) {
				if (!array_key_exists($key, $value)) {
					$value[$key] = $defaults;
				}
			}

			foreach($value as $key => $array) {
				$array = $this->oneCharConversion($array);
				$result .= $key . ': {';
				$result .= "'values': ['" . implode("", $array) . "'], ";
				$result .= "'keys': [";
				foreach($array as $mb => $arr) {
					$result .= '"'. $mb . '", ';
				}
				$result = substr($result, 0, -2);
				$result .= "]}, ";
			}
			
		} else {
			foreach($merge as $key => $array) {
				$array = $this->oneCharConversion($array);
				$result .= $key . ': [';
				$result .= "'".implode("", $array)."'";
				$result .= "], ";	
			}
		}
		
		$result = substr($result, 0, -2);
		$result .= "}";
		
		return $result;
	}
	
	function sliderInit_mt_1_1($name, $max = 12) {
		$name2 = str_replace("-", "_", $name);
		$slider = "$('$name').getElement('.position')";
		$knob = "$('$name').getElement('.knob')";
		$hidden = "$('params$name')";

		$js = "
		window.addEvent('domready', function() {
			
			var tip = createTip('positions-tip');
			$hidden.addEvent('set', function(value) {

				var slider = window.slider$name2.RT;
				
				if (!value.contains('{')) value = serialize(value.replace(/\s/g, '').split(','));

				value = value.unserialize();

				if (!value[slider.gridSize]) return;
				else value = new Hash(value[slider.gridSize]);
				
				var arrayMulti = {};
				var arraySingle = {};
				
				value.each(function(wrapper_value, key) {
					arrayMulti[key] = [];
					arraySingle[key] = [];
					if (slider.type == 'custom') {
						arrayMulti[key] = {};
						arraySingle[key] = {};
						arrayMulti[key]['keys'] = [];
						arrayMulti[key]['values'] = [];
						arraySingle[key]['keys'] = [];
						arraySingle[key]['values'] = [];
					}
					
					\$H(wrapper_value).each(function(inner_value, inner_key) {
						var val = inner_value.toString().dec2hex();
						if (slider.type != 'custom') {
							arrayMulti[key].push(val);
							arraySingle[key].push(val);
						} else {
							arrayMulti[key]['keys'].push(inner_key);
							arraySingle[key]['keys'].push(inner_key);
							arrayMulti[key]['values'].push(val);
							arraySingle[key]['values'].push(val);
						}

					});
					if (slider.type != 'custom') arraySingle[key] = [arraySingle[key].join('')];
					else arraySingle[key]['values'] = [arraySingle[key]['values'].join('')];
				});
				
				slider.defaults = \$merge(slider.defaults, arraySingle);
				
				if (slider.type != 'custom') {
					var cur = arraySingle[slider.current];
					if (cur) {
						var current = slider.list[slider.current].indexOf(cur[0]) || 0;
						window.slider$name2.set(current).fireEvent('onComplete');
					}
				} else {
					var defaults = slider.defaults[slider.current];
					var keys = slider.keys[slider.current];
					var tests = [];
					
					keys.each(function(key, i) {
						if (key.compareArrays(defaults.keys)) tests.push(i);
					});

					var list = slider.list[slider.current];
					
					tests.each(function(test, j) {
						if (list[test] == defaults.values[0]) {
							slider$name2.set(test).fireEvent('onComplete');
						}
					});
				}
			});
			window.slider$name2 = new RokSlider($slider, $knob, {
				offset: 5,
				snap: true,
				initialize: function() {
					this.RT = {};
					this.RT.current = $$('#$name .list .active a')[0].getFirst().innerHTML.toInt();
					this.RT.list = ".$this->outputCombinations().";
					this.RT.navigation = $('$name').getElement('.list').getChildren();
					this.RT.blocks = $('$name').getElements('.mini-grid');
					this.RT.settings = {};
					this.RT.gridSize = ".$this->maxGrid.";
					this.RT.defaults = ".$this->getLoadValue().";
					this.RT.keyName = '".$this->keyName."' || '';
					this.RT.type = '".$this->type."';
					this.RT.store = {};
					
					this.options.steps = this.RT.list[this.RT.current].length - 1;
					this.setOptions(this.options);

					var current = this.RT.current, navigation = this.RT.navigation, blocks = this.RT.blocks;
					var settings = this.RT.settings;
					navigation.each(function(nav, i) {
						settings[current] = [];
						nav.addEvent('click', function(event) {
							if (event) new Event(event).stop();
							navigation.removeClass('active');
							this.addClass('active');
							
							updateSlider(window.slider$name2, this.getFirst().getFirst().innerHTML.toInt());

							var value = slider$name2.RT.defaults[slider$name2.RT.current][0];
							if (slider$name2.RT.type == 'custom') {
								var defaults = slider$name2.RT.defaults[slider$name2.RT.current];
								var keys = slider$name2.RT.keys[slider$name2.RT.current];
								var tests = [];
								keys.each(function(key, i) {
									if (key.compareArrays(defaults.keys)) tests.push(i);
								});
								var list = slider$name2.RT.list[slider$name2.RT.current];
								
								tests.each(function(test, j) {
									if (list[test] == defaults.values[0]) {
										slider$name2.set(test);
									}
								});
								
							} else {
								slider$name2.set(slider$name2.RT.list[slider$name2.RT.current].indexOf(value));
							}
						});
					});
					updateBlocks(this, current);
				},
				
				onComplete: function() {
					this.knob.removeClass('down');
					$hidden.setProperty('value', serializeSettings(this, new Hash(this.RT.settings)));
					var setting = '';
					var step = Math.round(this.step);
					for (i = 0, len = this.RT.current; i < len; i++) {
						setting += this.RT.list[this.RT.current][(isNaN(step) || step < 0) ? 0 : step].toString().charAt(i);
					}
					if (this.RT.type != 'custom') this.RT.defaults[this.RT.current] = [setting];
					else {
						this.RT.defaults[this.RT.current].values = [setting];
						var keys = [];
						for (i=0,l=this.RT.current;i<l;i++) {
							keys.push(this.RT.blocks[i].innerHTML);
						}
						this.RT.defaults[this.RT.current].keys = keys;
					}
					
					if (Gantry.MenuItemHead) {
						var cache = Gantry.MenuItemHead.Cache[Gantry.Selection];
						if (!cache) cache = new Hash({});
						cache.set('".$name."', ".$hidden.".value);
					}
				},
				onDrag: function(now) {
					this.element.getFirst().setStyle('width', now + 10);
					var step = this.step;
					
					var layout = this.RT.list[this.RT.current][Math.round(step, 0)], output = '';
					if (!layout) return;
					
					layout = layout.toString();
					this.RT.settings[this.RT.current] = [];
					this.RT.store[this.RT.current] = [];
					for (i = 0, len = this.RT.current; i < len; i++) {
					    output += layout.charAt(i).hex2dec() + ((i == len - 1) ? '' : ' | ');
						
						if (this.RT.type == 'custom') {
							this.RT.settings[this.RT.current].push(layout.charAt(i));
							this.RT.store[this.RT.current].push(this.RT.keys[this.RT.current][Math.round(step)][i]);
							
						} else {
							this.RT.settings[this.RT.current].push(layout.charAt(i));
						}
						if (this.RT.keys) {
							var keyIndex = this.RT.keys[this.RT.current][Math.round(step,0)][i];
							this.RT.blocks[i].setText(keyIndex);
						}
					}
					tip.setHTML(output);
					
					updateBlocks(window.slider$name2, this.RT.current, step);
				},
				onChange: function(position) {
					if(this.options.snap) position = this.toPosition(this.step);
					position = position || 0;
					this.knob.setStyle(this.property, position);
					this.fireEvent('onDrag', position);
				}
			});

			slider$name2.RT.navigation[".array_search((string)$this->defaultCount, $this->schemas)."].fireEvent('click');
			
			$knob.addEvents({
				'mousedown': function() {this.addClass('down');},
				'mouseup': function() {this.removeClass('down');}
			});
			
			$('$name-wrapper').addEvents({
				'mouseenter': function() {
					var container = this.getElement('.mini-container');
					var pos = container.getCoordinates();
					tip.setStyles({
						'left': pos.left + pos.width + 5,
						'top': pos.top - 5
					});
					tip.setHTML(updateTip(slider$name2));
					tip.fx.start(1);
				},
				'mouseleave': function() {
					tip.fx.start(0);
				}
			});
		});
		";
		return $js;
	}
	
	function generalFunctions_mt_1_1() {
		$js = "
			Array.prototype.compareArrays = function(arr) {
				if (!arr) return false;
			    if (this.length != arr.length) return false;
			    for (var i = 0; i < arr.length; i++) {
			        if (this[i].compareArrays) { //likely nested array
			            if (!this[i].compareArrays(arr[i])) return false;
			            else continue;
			        }
			        if (this[i] != arr[i]) return false;
			    }
			    return true;
			}
			
			String.extend({
				baseConversion: function(from, to) {
					var num = this;
					if(isNaN(from) || from < 2 || from > 36 || isNaN(to) || to < 2 || to > 36)
						throw (new RangeError('Illegal radix. Radices must be integers between 2 and 36, inclusive.'));
					num = parseInt(num, from);
					num = num.toString(to);

					return num;
				},
				
				hex2dec: function() {
					if (!isNaN(this.toInt())) return this;
					return this.baseConversion(24, 10);
				},
				
				dec2hex: function() {
					return this.baseConversion(10, 24);					
				}
			});
			
			var createTip = function(id) {
				var el = $(id);
				if (el) return el;
				
				el = new Element('div', {'id': id}).inject(document.body).setText('2 | 2 | 2 | 2 | 2 | 2');
				el.fx = new Fx.Style(el, 'opacity', {duration: 200, wait: false}).set(0);
				
				return el;				
			};
			
			var updateTip = function(slider) {
				var blocks = slider.RT.blocks, output = '';
				blocks.each(function(block, i) {
					if (block.style.display != 'none') {
						var grid = block.className.split(' ')[1].replace('mini-grid-', '');
						output += grid.hex2dec() + ' | ';
					}
				});
				
				output = output.substring(0, output.length - 2);
				
				return output;
			};
			
			var updateSlider = function(slider, range) {
				var x = slider;
				range = range;
				
				x.min = 0; 
				x.max = slider.RT.list[range].length - 1;
				x.range = x.max - x.min;
				x.steps = x.max;
				x.stepSize = Math.abs(x.range) / x.steps;
				x.stepWidth = Number((x.stepSize * x.full / Math.abs(x.range)).toFixed(4));

				var grid = (x.stepWidth == Infinity) ? x.full : x.stepWidth;
				x.drag.options.grid = grid;
				
				if (!x.steps) x.drag.detach();
				else x.drag.attach();
				
				slider.RT.current = range;
			};
		
			var updateBlocks = function(slider, amount, step) {
				if (!step) step = 0;
				var blocks = slider.RT.blocks;
				var current = slider.RT.list[slider.RT.current];
				amount = amount;
				blocks.removeClass('main');
				blocks.each(function(block, i) {
					
					if (i < slider.RT.current) blocks[i].setStyle('display', 'block');
					else blocks[i].setStyle('display', 'none');
					var grid = slider.RT.list[amount][Math.round(step, 0)].toString();
					blocks[i].className = '';
					
					var chr = (amount == 1) ? slider.RT.gridSize : grid.charAt(i).hex2dec();
					blocks[i].addClass('mini-grid').addClass('mini-grid-' + chr);

					var keyValue = blocks[i].innerHTML;
					if (keyValue == slider.RT.keyName && (keyValue != '')) blocks[i].addClass('main');
				});
			};
			
			var serializeSettings = function(slider, settings) {
				var serial = '';
				
				// grid size
				serial += 'a:1:{i:' + slider.RT.gridSize + ';';
				
				// main index
				serial += 'a:' + settings.length + ':{';
				settings.each(function(val, key) {
					// values of index
					serial += 'i:' + key + ';a:' + val.length + ':{';
					
					for (i = 0, l = val.length; i < l; i++) {
						if (slider.RT.type == 'custom') {
							var tmp = slider.RT.store[key][i];
							serial += 's:' + tmp.length + ':\"' + tmp + '\";i:' + val[i].hex2dec() + ';';
						} else {
							serial += 'i:' + i + ';i:' + val[i].hex2dec() + ';';
						}
					}

					serial += '}';
				});
				
				serial += '}}';

				return serial;
			};
		";
		
		return $js;
	}
	
	
	function sliderInit_mt_1_2($name, $max = 12) {
		$name2 = str_replace("-", "_", $name);
		$slider = "document.id('$name').getElement('.position')";
		$knob = "document.id('$name').getElement('.knob')";
		$hidden = "document.id('params$name')";
		
		$js = "
		window.addEvent('domready', function() {
			
			var tip = createTip('positions-tip');
			$hidden.addEvent('set', function(value) {
				var baseValue = value;
				var slider = window.slider$name2.RT;
				
				if (!value.contains('{')) value = serialize(value.replace(/\s/g, '').split(','));
				
				value = value.unserialize();

				if (!value[slider.gridSize]) return;
				else value = new Hash(value[slider.gridSize]);
				
				var arrayMulti = {};
				var arraySingle = {};
				
				value.each(function(wrapper_value, key) {
					arrayMulti[key] = [];
					arraySingle[key] = [];
					if (slider.type == 'custom') {
						arrayMulti[key] = {};
						arraySingle[key] = {};
						arrayMulti[key]['keys'] = [];
						arrayMulti[key]['values'] = [];
						arraySingle[key]['keys'] = [];
						arraySingle[key]['values'] = [];
					}
					
					\$H(wrapper_value).each(function(inner_value, inner_key) {
						var val = inner_value.toString().dec2hex();
						if (slider.type != 'custom') {
							arrayMulti[key].push(val);
							arraySingle[key].push(val);
						} else {
							arrayMulti[key]['keys'].push(inner_key);
							arraySingle[key]['keys'].push(inner_key);
							arrayMulti[key]['values'].push(val);
							arraySingle[key]['values'].push(val);
						}

					});
					if (slider.type != 'custom') arraySingle[key] = [arraySingle[key].join('')];
					else arraySingle[key]['values'] = [arraySingle[key]['values'].join('')];
				});
				
				slider.defaults = \$merge(slider.defaults, arraySingle);
				
				if (slider.type != 'custom') {
					var cur = arraySingle[slider.current];
					if (cur) {
						var current = slider.list[slider.current].indexOf(cur[0]) || 0;
						window.slider$name2.set(current);//.fireEvent('onComplete', [current, true]);
					}
				} else {
					var defaults = slider.defaults[slider.current];
					var keys = slider.keys[slider.current];
					var tests = [];
					
					keys.each(function(key, i) {
						if (key.compareArrays(defaults.keys)) tests.push(i);
					});

					var list = slider.list[slider.current];
					
					tests.each(function(test, j) {
						if (list[test] == defaults.values[0]) {
							slider$name2.set(test);//.fireEvent('onComplete', [test, true]);
						}
					});
				}
			 	this.set('value', baseValue);
			});
			window.slider$name2 = new RokSlider($slider, $knob, {
				offset: 5,
				snap: true,
				initialize: function() {
					this.hiddenEl = $hidden;
					this.RT = {};
					this.RT.current = $$('#$name .list .active a')[0].getFirst().innerHTML.toInt();
					this.RT.list = ".$this->outputCombinations().";
					this.RT.navigation = document.id('$name').getElement('.list').getChildren();
					this.RT.blocks = document.id('$name').getElements('.mini-grid');
					this.RT.settings = {};
					this.RT.gridSize = ".$this->maxGrid.";
					this.RT.defaults = ".$this->getLoadValue().";
					this.RT.keyName = '".$this->keyName."' || '';
					this.RT.type = '".$this->type."';
					this.RT.store = {};
					
					this.options.steps = this.RT.list[this.RT.current].length - 1;
					this.setOptions(this.options);

					var current = this.RT.current, navigation = this.RT.navigation, blocks = this.RT.blocks;
					var settings = this.RT.settings;
					navigation.each(function(nav, i) {
						settings[current] = [];
						nav.addEvent('click', function(event) {
							if (event) new Event(event).stop();
							navigation.removeClass('active');
							this.addClass('active');
							
							updateSlider(window.slider$name2, this.getFirst().getFirst().innerHTML.toInt());

							var value = slider$name2.RT.defaults[slider$name2.RT.current][0];
							if (slider$name2.RT.type == 'custom') {
								var defaults = slider$name2.RT.defaults[slider$name2.RT.current];
								var keys = slider$name2.RT.keys[slider$name2.RT.current];
								var tests = [];
								keys.each(function(key, i) {
									if (key.compareArrays(defaults.keys)) tests.push(i);
								});
								var list = slider$name2.RT.list[slider$name2.RT.current];
								
								tests.each(function(test, j) {
									if (list[test] == defaults.values[0]) {
										slider$name2.set(test);
									}
								});
								
							} else {
								slider$name2.set(slider$name2.RT.list[slider$name2.RT.current].indexOf(value));
							}
						});
					});
					updateBlocks(this, current);
				},
				
				onComplete: function(value, force) {
					this.knob.removeClass('down');
					var serialize = serializeSettings(this, new Hash(this.RT.settings));
					if (!force) $hidden.set('value', serialize);
					var setting = '';
					var step = Math.round(this.step);
					for (i = 0, len = this.RT.current; i < len; i++) {
						setting += this.RT.list[this.RT.current][(isNaN(step) || step < 0) ? 0 : step].toString().charAt(i);
					}
					if (this.RT.type != 'custom') this.RT.defaults[this.RT.current] = [setting];
					else {
						this.RT.defaults[this.RT.current].values = [setting];
						var keys = [];
						for (i=0,l=this.RT.current;i<l;i++) {
							keys.push(this.RT.blocks[i].innerHTML);
						}
						this.RT.defaults[this.RT.current].keys = keys;
					}
					
					if (Gantry.MenuItemHead) {
						var cache = Gantry.MenuItemHead.Cache[Gantry.Selection];
						if (!cache) cache = new Hash({});
						
						cache.set('".$name."', ".$hidden.".value);
					}
					
				},
				onDrag: function(now) {
					this.element.getFirst().setStyle('width', now + 10);
					var step = this.step;
					
					var layout = this.RT.list[this.RT.current][Math.round(step, 0)], output = '';
					if (!layout) return;
					
					layout = layout.toString();
					this.RT.settings[this.RT.current] = [];
					this.RT.store[this.RT.current] = [];
					for (i = 0, len = this.RT.current; i < len; i++) {
					    output += layout.charAt(i).hex2dec() + ((i == len - 1) ? '' : ' | ');
						
						if (this.RT.type == 'custom') {
							this.RT.settings[this.RT.current].push(layout.charAt(i));
							this.RT.store[this.RT.current].push(this.RT.keys[this.RT.current][Math.round(step,0)][i]);
							
						} else {
							this.RT.settings[this.RT.current].push(layout.charAt(i));
						}
						if (this.RT.keys) {
							var keyIndex = this.RT.keys[this.RT.current][Math.round(step,0)][i];
							this.RT.blocks[i].set('text', keyIndex);
						}
					}
					tip.set('html', output);
					
					updateBlocks(window.slider$name2, this.RT.current, step);
				},
				onChange: function(position) {
					if(this.options.snap) position = this.toPosition(this.step);
					position = position || 0;
					this.knob.setStyle(this.property, position);
					this.fireEvent('onDrag', position);
				}
			});

			slider$name2.RT.navigation[".array_search((string)$this->defaultCount, $this->schemas)."].fireEvent('click');
			
			$knob.addEvents({
				'mousedown': function() {this.addClass('down');},
				'mouseup': function() {this.removeClass('down');}
			});
			
			document.id('$name-wrapper').addEvents({
				'mouseenter': function() {
					var container = this.getElement('.mini-container');
					var pos = container.getCoordinates();
					tip.setStyles({
						'left': pos.left + pos.width + 5,
						'top': pos.top - 5
					});
					tip.set('html', updateTip(slider$name2));
					tip.fx.start('opacity', 1);
				},
				'mouseleave': function() {
					tip.fx.start('opacity', 0);
				}
			});
		});
		";
		return $js;
	}
	
	function generalFunctions_mt_1_2() {
		$js = "
			Array.prototype.compareArrays = function(arr) {
				if (!arr) return false;
			    if (this.length != arr.length) return false;
			    for (var i = 0; i < arr.length; i++) {
			        if (this[i].compareArrays) { //likely nested array
			            if (!this[i].compareArrays(arr[i])) return false;
			            else continue;
			        }
			        if (this[i] != arr[i]) return false;
			    }
			    return true;
			}
			
			String.implement({
				baseConversion: function(from, to) {
					var num = this;
					if(isNaN(from) || from < 2 || from > 36 || isNaN(to) || to < 2 || to > 36)
						throw (new RangeError('Illegal radix. Radices must be integers between 2 and 36, inclusive.'));
					num = parseInt(num, from);
					num = num.toString(to);

					return num;
				},
				
				hex2dec: function() {
					if (!isNaN(this.toInt())) return this;
					return this.baseConversion(24, 10);
				},
				
				dec2hex: function() {
					return this.baseConversion(10, 24);					
				}
			});
			
			var createTip = function(id) {
				var el = document.id(id);
				if (el) return el;
				
				el = new Element('div', {'id': id}).inject(document.body).set('text', '2 | 2 | 2 | 2 | 2 | 2');
				el.fx = new Fx.Tween(el, {duration: 200, link: 'cancel'}).set('opacity', 0);
				
				return el;				
			};
			
			var updateTip = function(slider) {
				var blocks = slider.RT.blocks, output = '';
				blocks.each(function(block, i) {
					if (block.style.display != 'none') {
						var grid = block.className.split(' ')[1].replace('mini-grid-', '');
						output += grid.hex2dec() + ' | ';
					}
				});
				
				output = output.substring(0, output.length - 2);
				
				return output;
			};
			
			var updateSlider = function(slider, range) {
				var x = slider;
				range = range;
				
				x.min = 0; 
				x.max = slider.RT.list[range].length - 1;
				x.range = x.max - x.min;
				x.steps = x.max;
				x.stepSize = Math.abs(x.range) / x.steps;
				x.stepWidth = Number((x.stepSize * x.full / Math.abs(x.range)).toFixed(4));

				var grid = (x.stepWidth == Infinity) ? x.full : x.stepWidth;
				x.drag.options.grid = grid;
				
				if (!x.steps) x.drag.detach();
				else x.drag.attach();
				
				slider.RT.current = range;
			};
		
			var updateBlocks = function(slider, amount, step) {
				if (!step) step = 0;
				var blocks = slider.RT.blocks;
				var current = slider.RT.list[slider.RT.current];
				amount = amount;
				blocks.removeClass('main');
				blocks.each(function(block, i) {
					
					if (i < slider.RT.current) blocks[i].setStyle('display', 'block');
					else blocks[i].setStyle('display', 'none');
					var grid = slider.RT.list[amount][Math.round(step, 0)].toString();
					blocks[i].className = '';
					
					var chr = (amount == 1) ? slider.RT.gridSize : grid.charAt(i).hex2dec();
					blocks[i].addClass('mini-grid').addClass('mini-grid-' + chr);

					var keyValue = blocks[i].innerHTML;
					if (keyValue == slider.RT.keyName && (keyValue != '')) blocks[i].addClass('main');
				});
			};
			
			var serializeSettings = function(slider, settings) {
				var serial = '';
				
				// grid size
				serial += 'a:1:{i:' + slider.RT.gridSize + ';';
				
				// main index
				serial += 'a:' + settings.getLength() + ':{';
				settings.each(function(val, key) {
					// values of index
					serial += 'i:' + key + ';a:' + val.length + ':{';
					
					for (i = 0, l = val.length; i < l; i++) {
						if (slider.RT.type == 'custom') {
							var tmp = slider.RT.store[key][i];
							serial += 's:' + tmp.length + ':\"' + tmp + '\";i:' + val[i].hex2dec() + ';';
						} else {
							serial += 'i:' + i + ';i:' + val[i].hex2dec() + ';';
						}
					}

					serial += '}';
				});
				
				serial += '}}';

				return serial;
			};
		";
		
		return $js;
	}
}

?>
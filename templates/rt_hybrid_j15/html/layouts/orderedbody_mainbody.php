<?php
/**
 * @package   gantry
 * @subpackage html.layouts
 * @version   1.5.4 November 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();

gantry_import('core.gantrylayout');

/**
 *
 * @package gantry
 * @subpackage html.layouts
 */
class GantryLayoutOrderedBody_MainBody extends GantryLayout {
    var $render_params = array(
        'schema'        =>  null,
        'pushPull'      =>  null,
        'classKey'      =>  null,
        'sidebars'      =>  array(),
        'contentTop'    =>  null,
        'contentBottom' =>  null
    );
    function render($params = array()){
        global $gantry;

        $fparams = $this-> _getParams($params);

        // logic to determine if the component should be displayed
		$display_mainbody = !($gantry->get("mainbody-enabled",true)==false && JRequest::getVar('view') == 'frontpage');
        $display_component = !($gantry->get("component-enabled",true)==false && JRequest::getVar('view') == 'frontpage');
        $positions = array_keys($fparams->sidebars);
        $sbcount = 0;
        ob_start();
// XHTML LAYOUT
?>
		<?php if ($display_mainbody) : ?>
		<div id="rt-main" class="<?php echo $fparams->classKey; ?>">
            <div class="rt-main-inner">
				<?php if ($gantry->get('bodywidth') == 'full'): ?>
				<div class="rt-container">
				<?php endif; ?>
					<div class="rt-section-surround">
						<div class="rt-row-surround">
                            <?php foreach($fparams->schema as $position => $value): ?>
                                <?php if ($position != 'mb'): ?>
                                    <?php echo $fparams->sidebars[$positions[$sbcount]]; ?>
                                    <?php $sbcount++; ?>
                                <?php elseif($position == 'mb'): ?>
                                    <div class="rt-grid-<?php echo $fparams->schema['mb']; ?> <?php echo $fparams->pushPull[0]; ?>">
                                        <?php if (isset($fparams->contentTop)) : ?>
                                        <div id="rt-content-top">
                                            <?php echo $fparams->contentTop; ?>
                                            <div class="clear"></div>
                                        </div>
                                        <?php endif; ?>
                                        <div class="rt-block">
                                        <?php if ($display_component) : ?>
                                        <div class="<?php echo $gantry->get('articlestyle'); ?>">
                                            <div id="rt-mainbody">
                                                <jdoc:include type="component" />
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <?php endif; ?>
                                        </div>
                                        <?php if (isset($fparams->contentBottom)) : ?>
                                        <div id="rt-content-bottom">
                                            <?php echo $fparams->contentBottom; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
		                    <div class="clear"></div>
						</div>
					</div>
					<?php if ($gantry->get('bodywidth') == 'full'): ?>
					</div>
					<?php endif; ?>
               </div>
           </div>
		<?php endif; ?>
<?php
        return ob_get_clean();
    }
}
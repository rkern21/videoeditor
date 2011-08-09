<?php
/**
 * @package Gantry Template Framework - RocketTheme
 * @version 1.5.4 November 16, 2010
 * @author RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );

// load and inititialize gantry class
require_once('lib/gantry/gantry.php');
$gantry->init();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $gantry->language; ?>" lang="<?php echo $gantry->language;?>" >
<head>
	<?php 
		$gantry->displayHead();
		$gantry->addStyles(array('template.css','joomla.css','typography.css'));

		if ($gantry->get('fixedfooter')) $gantry->addScript('rt-fixedfooter.js');
	?>
</head>
	<body <?php echo $gantry->displayBodyTag(array('backgroundlevel','bodyLevel')); ?>>
		<div id="rt-page-background">
			<?php if ($gantry->get('headerwidth') == 'wrapped'): ?>
			<div class="rt-container">
			<?php endif; ?>
				<div id="rt-header-surround" <?php echo $gantry->displayClassesByTag('rt-header-surround'); ?>><div id="rt-header-bg"><div id="rt-header-overlay">
					<?php /** Begin Drawer **/ if ($gantry->countModules('drawer')) : ?>
					<div id="rt-drawer">
						<?php if ($gantry->get('headerwidth') == 'full'): ?>
						<div class="rt-container">
						<?php endif; ?>
							<?php echo $gantry->displayModules('drawer','standard','standard'); ?>
							<div class="clear"></div>
						<?php if ($gantry->get('headerwidth') == 'full'): ?>
						</div>
						<?php endif; ?>
					</div>
					<?php /** End Drawer **/ endif; ?>
					<?php /** Begin Top **/ if ($gantry->countModules('top')) : ?>
					<div id="rt-top">
						<?php if ($gantry->get('headerwidth') == 'full'): ?>
						<div class="rt-container">
						<?php endif; ?>
							<?php echo $gantry->displayModules('top','standard','standard'); ?>
							<div class="clear"></div>
						<?php if ($gantry->get('headerwidth') == 'full'): ?>
						</div>
						<?php endif; ?>
					</div>
					<?php /** End Top **/ endif; ?>
					<?php /** Begin Navigation **/ if ($gantry->countModules('navigation')) : ?>
					<div id="rt-navigation">
						<?php if ($gantry->get('headerwidth') == 'full'): ?>
						<div class="rt-container">
						<?php endif; ?>
							<?php echo $gantry->displayModules('navigation','basic','basic'); ?>
					    	<div class="clear"></div>
						<?php if ($gantry->get('headerwidth') == 'full'): ?>
						</div>
						<?php endif; ?>
					</div>
					<?php /** End Navigation **/ endif; ?>
					<?php /** Begin Header **/ if ($gantry->countModules('header')) : ?>
					<div id="rt-header">
						<?php if ($gantry->get('headerwidth') == 'full'): ?>
						<div class="rt-container">
						<?php endif; ?>
							<?php echo $gantry->displayModules('header','standard','standard'); ?>
							<div class="clear"></div>
						<?php if ($gantry->get('headerwidth') == 'full'): ?>
						</div>
						<?php endif; ?>
					</div>
					<?php /** End Header **/ endif; ?>
					<?php /** Begin Showcase **/ if ($gantry->countModules('showcase')) : ?>
					<div id="rt-showcase">
						<?php if ($gantry->get('headerwidth') == 'full'): ?>
						<div class="rt-container">
						<?php endif; ?>
							<?php echo $gantry->displayModules('showcase','standard','standard'); ?>
							<div class="clear"></div>
						<?php if ($gantry->get('headerwidth') == 'full'): ?>
						</div>
						<?php endif; ?>
					</div>
					<?php /** End Showcase **/ endif; ?>
					<?php /** Begin Feature **/ if ($gantry->countModules('feature')) : ?>
					<div id="rt-feature" class="<?php echo (!$gantry->countModules('showcase')) ? 'flipped-panel' : 'normal-panel' ?>">
						<?php
							$defaultStatus = $gantry->get('featurepanel-default') == 'open' ? 'true' : 'false';
							$featureCookie = JRequest::getVar('rt-feature-hybrid', $defaultStatus, 'cookie');
							$featureCookie = ($featureCookie == 'true') ? false : true;
							$state = array(
								'class' => $featureCookie ? ' close' : ' open',
								'text' => $featureCookie ? JText::_('FEATURE_PANEL_OPEN') : JText::_('FEATURE_PANEL_CLOSE'),
								'style' => $featureCookie ? ' style="margin: 0px; position: static; overflow: hidden; height: 0px;"' : ''
							);
							if (!$gantry->get('featurepanel-enabled')){
								$state = array('class' => ' open', 'text' => '', 'style' => '');
							} elseif (!$gantry->countModules('showcase')){
								$state['class'] = $featureCookie ? ' open' : ' close';
							}
						?>
						<div class="rt-panel-wrapper">
							<div id="rt-feature-wrapper"<?php echo $state['style'];?>>
								<?php if ($gantry->get('headerwidth') == 'full'): ?>
								<div class="rt-container">
								<?php endif; ?>
									<?php echo $gantry->displayModules('feature','standard','standard'); ?>
									<div class="clear"></div>
								<?php if ($gantry->get('headerwidth') == 'full'): ?>
								</div>
								<?php endif; ?>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
						<?php if ($gantry->get('featurepanel-enabled')): ?>
						<a href="#" class="panel-control<?php echo $state['class']; ?>"><span><?php echo $state['text']; ?></span></a>
						<?php endif; ?>
					</div>
					<?php /** End Feature **/ endif; ?>
				</div></div></div>
			<?php if ($gantry->get('headerwidth') == 'wrapped'): ?>
			</div>
			<?php endif; ?>
			<?php if ($gantry->get('bodywidth') == 'wrapped'): ?>
			<div class="rt-container">
			<?php endif; ?>
				<div id="rt-main-background" <?php echo $gantry->displayClassesByTag('rt-main-background'); ?>><div id="rt-main-accent" <?php echo $gantry->displayClassesByTag('rt-main-accent'); ?>>
					<?php /** Begin Utility **/ if ($gantry->countModules('utility')) : ?>
					<div id="rt-utility">
						<?php if ($gantry->get('bodywidth') == 'full'): ?>
						<div class="rt-container">
						<?php endif; ?>
							<?php echo $gantry->displayModules('utility','standard','standard'); ?>
							<div class="clear"></div>
						<?php if ($gantry->get('bodywidth') == 'full'): ?>
						</div>
						<?php endif; ?>
					</div>
					<?php /** End Utility **/ endif; ?>
					<?php /** Begin Breadcrumbs **/ if ($gantry->countModules('breadcrumb')) : ?>
					<div id="rt-breadcrumbs">
						<?php if ($gantry->get('bodywidth') == 'full'): ?>
						<div class="rt-container">
						<?php endif; ?>
							<?php echo $gantry->displayModules('breadcrumb','basic','breadcrumbs'); ?>
							<div class="clear"></div>
						<?php if ($gantry->get('bodywidth') == 'full'): ?>
						</div>
						<?php endif; ?>
					</div>
					<?php /** End Breadcrumbs **/ endif; ?>
					<?php /** Begin Main Top **/ if ($gantry->countModules('maintop')) : ?>
					<div id="rt-maintop">
						<?php if ($gantry->get('bodywidth') == 'full'): ?>
						<div class="rt-container">
						<?php endif; ?>
							<?php echo $gantry->displayModules('maintop','standard','standard'); ?>
							<div class="clear"></div>
						<?php if ($gantry->get('bodywidth') == 'full'): ?>
						</div>
						<?php endif; ?>
					</div>
					<?php /** End Main Top **/ endif; ?>
					<?php /** Begin Main Body **/ ?>
				    <?php echo $gantry->displayOrderedMainbody('mainbody','sidebar','standard','standard','standard','standard','standard'); ?>
					<?php /** End Main Body **/ ?>
					<?php /** Begin Main Bottom **/ if ($gantry->countModules('mainbottom')) : ?>
					<div id="rt-mainbottom">
						<?php if ($gantry->get('bodywidth') == 'full'): ?>
						<div class="rt-container">
						<?php endif; ?>
							<?php echo $gantry->displayModules('mainbottom','standard','standard'); ?>
							<div class="clear"></div>
						<?php if ($gantry->get('bodywidth') == 'full'): ?>
						</div>
						<?php endif; ?>
					</div>
					<?php /** End Main Bottom **/ endif; ?>
				</div></div>
			<?php if ($gantry->get('bodywidth') == 'wrapped'): ?>
			</div>
			<?php endif; ?>
			<?php /** Begin Bottom Section **/ if ($gantry->countModules('bottom') or $gantry->countModules('footer') or $gantry->countModules('copyright')) : ?>
			<?php if ($gantry->get('footerwidth') == 'wrapped'): ?>
			<div class="rt-container">
			<?php endif; ?>
				<div id="rt-bottom-surround" <?php echo $gantry->displayClassesByTag('rt-bottom-surround'); ?>><div id="rt-bottom-bg"><div id="rt-bottom-overlay">
					<?php /** Begin Bottom **/ if ($gantry->countModules('bottom')) : ?>
					<div id="rt-bottom">
						<?php if ($gantry->get('footerwidth') == 'full'): ?>
						<div class="rt-container">
						<?php endif; ?>
							<div class="rt-section-surround">
								<div class="rt-row-surround">
									<?php echo $gantry->displayModules('bottom','standard','standard'); ?>
									<div class="clear"></div>
								</div>
							</div>
						<?php if ($gantry->get('footerwidth') == 'full'): ?>	
						</div>
						<?php endif; ?>
					</div>
					<?php /** End Bottom **/ endif; ?>
					<?php /** Begin Lower Panel **/ if ($gantry->countModules('lowerpanel')) : ?>
					<?php if ($gantry->get('footerwidth') == 'full'): ?>
					<div class="rt-container">
					<?php endif; ?>
						<div id="rt-lowerpanel">
							<?php
								$defaultStatus = $gantry->get('lowerpanel-default') == 'open' ? 'true' : 'false';
								$featureCookie = JRequest::getVar('rt-lowerpanel-hybrid', $defaultStatus, 'cookie');
								$featureCookie = ($featureCookie == 'true') ? false : true;
								$state = array(
									'class' => $featureCookie ? ' close' : ' open',
									'text' => $featureCookie ? JText::_('LOWER_PANEL_OPEN') : JText::_('LOWER_PANEL_CLOSE'),
									'style' => $featureCookie ? ' style="margin: 0px; position: static; overflow: hidden; height: 0px;"' : ''
								);
								if (!$gantry->get('lowerpanel-enabled')){
									$state = array('class' => ' open', 'text' => '', 'style' => '');
								}
							?>
							<div class="rt-panel-wrapper">
								<div id="rt-lowerpanel-wrapper"<?php echo $state['style'];?>>
									<?php echo $gantry->displayModules('lowerpanel','standard','standard'); ?>
									<div class="clear"></div>
								</div>
								<div class="clear"></div>
							</div>
						
							<?php if ($gantry->get('lowerpanel-enabled')): ?>
							<a href="#" class="panel-control<?php echo $state['class']; ?>"><span><?php echo $state['text']; ?></span></a>
							<?php endif; ?>
						</div>
					<?php if ($gantry->get('footerwidth') == 'full'): ?>
					</div>
					<?php endif; ?>
					<?php /** End Lower Panel **/ endif; ?>
					<?php /** Begin Copyright **/ if ($gantry->countModules('copyright')) : ?>
					<div id="rt-copyright">
						<?php if ($gantry->get('footerwidth') == 'full'): ?>
						<div class="rt-container">
						<?php endif; ?>
							<?php echo $gantry->displayModules('copyright','standard','standard'); ?>
							<div class="clear"></div>
						<?php if ($gantry->get('footerwidth') == 'full'): ?>
						</div>
						<?php endif; ?>
					</div>
					<?php /** End Copyright **/ endif; ?>
					<?php /** Begin Debug **/ if ($gantry->countModules('debug')) : ?>
					<div id="rt-debug">
						<?php if ($gantry->get('footerwidth') == 'full'): ?>
						<div class="rt-container">
						<?php endif; ?>
							<?php echo $gantry->displayModules('debug','standard','standard'); ?>
							<div class="clear"></div>
						<?php if ($gantry->get('footerwidth') == 'full'): ?>
						</div>
						<?php endif; ?>
					</div>
					<?php /** End Debug **/ endif; ?>
					<?php /** Begin Footer **/ if ($gantry->countModules('footer')) : ?>
					<div id="rt-footerbar">
						<?php if ($gantry->get('footerwidth') == 'full'): ?>
						<div class="rt-container">
						<?php endif; ?>
							<div id="rt-footer">
								<?php echo $gantry->displayModules('footer','standard','standard'); ?>
								<div class="clear"></div>
							</div>
						<?php if ($gantry->get('footerwidth') == 'full'): ?>
						</div>
						<?php endif; ?>
					</div>
					<?php /** End Footer **/ endif; ?>
				</div></div></div>
			<?php if ($gantry->get('footerwidth') == 'wrapped'): ?>
			</div>
			<?php endif; ?>
			<?php /** End Bottom Section **/ endif; ?>
		</div>
		<?php /** Begin Popups **/ 
		echo $gantry->displayModules('popup','popup','popup');
		echo $gantry->displayModules('login','login','popup'); 
		/** End Popup s**/ ?>
		<?php /** Begin Analytics **/ if ($gantry->countModules('analytics')) : ?>
		<?php echo $gantry->displayModules('analytics','basic','basic'); ?>
		<?php /** End Analytics **/ endif; ?>
	</body>
</html>
<?php
$gantry->finalize();
?>
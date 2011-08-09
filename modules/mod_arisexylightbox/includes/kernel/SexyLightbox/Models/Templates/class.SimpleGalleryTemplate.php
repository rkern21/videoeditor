<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

define('ASEXYBOX_SIMPLEGALLERYTEMPLATE',
<<<SIMPLEGALLERY
{repeater}
	{headertemplate}
		<table width="100%" class="asexy-table {\$mainClass}">
	{/headertemplate}
	{rowtemplate itemCount="{\$itemCount}" rowClass="{\$rowClasses}"}
		<tr class="{\$rowClass}">
			{celltemplate}
				<td>
					<div class="asexybox-outer">
						<div class="asexybox-shadow">&nbsp;</div>
						<div class="asexybox-inner">
							<div class="asexybox-image">{\$data:sexyimage}<div class="asexybox-cap"></div></div>
						</div>
					</div>
					<div class="asexybox-title">{\$GalleryCaption}</div>
				</td>
        	{/celltemplate}
        	{emptycelltemplate}
				<td>&nbsp;</td>
			{/emptycelltemplate}
		</tr>
	{/rowtemplate}
	{footertemplate}
		</table>
	{/footertemplate}
	{emptytemplate}
		{\$emptyText}
	{/emptytemplate}
{/repeater}
SIMPLEGALLERY
);

define('ASEXYBOX_SINGLEIMAGEGALLERYTEMPLATE',
<<<SINGLEGALLERY
{repeater}
	{headertemplate}
		<div class="asexybox-singleimage" style="display: none;">
	{/headertemplate}
	{rowtemplate}
		{celltemplate}{\$data:sexyimage}{/celltemplate}
	{/rowtemplate}
	{footertemplate}
		</div>
	{/footertemplate}
{/repeater}
SINGLEGALLERY
);
define('ASEXYBOX_HIDDENITEMSTEMPLATE',
<<<HIDDENITEMS
{repeater}
	{headertemplate}
		<div style="display: none;">
	{/headertemplate}
	{rowtemplate}
		{celltemplate}{\$data:sexyimage}{/celltemplate}
	{/rowtemplate}
	{footertemplate}
		</div>
	{/footertemplate}
{/repeater}
HIDDENITEMS
);
define('ASEXYBOX_SLICKGALLERYTEMPLATE',
<<<SLICKGALLERY
{repeater}
	{rowtemplate}
		{celltemplate}<div class="arislickgallery-item"{\$data:style}>{\$data:sexyimage}<div class="arislickgallery-title">{\$data:Title}</div></div>{/celltemplate}
	{/rowtemplate}
{/repeater}
SLICKGALLERY
);
?>
<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
// IMPORTANT!
// Supported Tags: h1, h2, h3, h4, h5, h6, b, u, i, a, img, p, br, strong, em, font, blockquote, li, ul, ol, hr, td, th, tr, table, sup, sub, small
?>
<table border="1" width="100%">
<?php
$recsSize = count($recs);
for($r = 0; $r < $recsSize; $r++) {
	$rec = $recs[$r];
?>
<tr>
<td colspan="2" bgcolor="#cccccc" align="left" valign="middle">
	<h2><?php echo $rec->submitted; ?>, <?php echo $rec->id; ?></h2>
</td>
</tr>
<tr>
<td>
	<strong><?php echo BFText::_('COM_BREEZINGFORMS_ID') ?>:</strong>
</td>
<td>
	<?php echo $rec->id; ?> 
</td>
</tr>
<tr>
<td>
	<strong><?php echo BFText::_('COM_BREEZINGFORMS_PROCESS_SUBMITTEDAT') ?>:</strong>
</td>
<td>
	<?php echo $rec->submitted; ?> 
</td>
</tr>
<tr>
<td>
	<strong><?php echo BFText::_('COM_BREEZINGFORMS_IP') ?>:</strong>
</td>
<td>
	<?php echo $rec->ip; ?> 
</td>
</tr>
<tr>
<td>
	<strong><?php echo BFText::_('COM_BREEZINGFORMS_PROCESS_SUBMITTERUSERNAME') ?>:</strong>
</td>
<td>
	<?php echo htmlentities($rec->username, ENT_QUOTES, 'UTF-8');  ?> 
</td>
</tr>
<tr>
<td>
	<strong><?php echo BFText::_('COM_BREEZINGFORMS_PROCESS_SUBMITTERFULLNAME') ?>:</strong>
</td>
<td>
	<?php echo htmlentities($rec->user_full_name, ENT_QUOTES, 'UTF-8'); ?> 
</td>
</tr>
<tr>
<td>
	<strong><?php echo BFText::_('COM_BREEZINGFORMS_PROCESS_SUBMITTERID') ?>:</strong>
</td>
<td>
	<?php echo $rec->user_id; ?> 
</td>
</tr>
<tr>
<td>
	<strong><?php echo BFText::_('COM_BREEZINGFORMS_BROWSER') ?>:</strong>
</td>
<td>
	<?php echo htmlentities($rec->browser, ENT_QUOTES, 'UTF-8');  ?> 
</td>
</tr>
<tr>
<td>
	<strong><?php echo BFText::_('COM_BREEZINGFORMS_PROCESS_OPSYS') ?>:</strong>
</td>
<td>
	<?php echo htmlentities($rec->opsys, ENT_QUOTES, 'UTF-8');  ?> 
</td>
</tr>
<tr>
<td>
	<strong><?php echo BFText::_('COM_BREEZINGFORMS_PAYMENT_TRANSACTION_ID') ?>:</strong>
</td>
<td>
	<?php echo $rec->paypal_tx_id; ?> 
</td>
</tr>
<tr>
<td>
	<strong><?php echo BFText::_('COM_BREEZINGFORMS_PAYMENT_TRANSACTION_DATE') ?>:</strong>
</td>
<td>
	<?php echo $rec->paypal_payment_date; ?> 
</td>
</tr>
<tr>
<td>
	<strong><?php echo BFText::_('COM_BREEZINGFORMS_PAYMENT_TESTACCOUNT') ?>:</strong>
</td>
<td>
	<?php echo $rec->paypal_testaccount == 1 ? BFText::_('COM_BREEZINGFORMS_YES') : BFText::_('COM_BREEZINGFORMS_NO'); ?>
</td>
</tr>
<tr>
<td>
	<strong><?php echo BFText::_('COM_BREEZINGFORMS_PAYMENT_DOWNLOAD_TRIES') ?>:</strong>
</td>
<td>
	<?php echo $rec->paypal_download_tries; ?> 
</td>
</tr>
<tr>
<td colspan="2" bgcolor="#cccccc">
	<strong><?php echo BFText::_('COM_BREEZINGFORMS_DATA') ?>:</strong>
</td>
</tr>
<?php
$subs = $this->getSubrecords($rec->id);
$subsSize = count($subs);
for($s = 0; $s < $subsSize; $s++) {
	$sub = $subs[$s];
?>
<tr>
<td>
	<strong><?php echo htmlentities(wordwrap($sub->title, 50, '<br/>', true), ENT_QUOTES, 'UTF-8'); ?>:</strong>
</td>
<td>
	<?php echo nl2br(htmlentities(substr($sub->value,0,10000), ENT_QUOTES, 'UTF-8')); ?> 
</td>
</tr>
<?php
}
?>
<?php	
}
?>
</table>
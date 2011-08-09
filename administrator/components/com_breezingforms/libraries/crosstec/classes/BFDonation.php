<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
class BFDonation{
	
	public static function getDonationButton() {
		
		return '
					<style>
					#crosstec-donate {
					float: right;
					width: 250px;
					padding: 5px;
					text-align: center;
					background: #d9e3ee;
					-moz-border-radius: 5px;
					-webkit-border-radius: 5px;
					border-radius: 5px;
					}
					</style>
		
					<span id="crosstec-donate" style="text-align: center;" align="right">
						Decide for yourself how much our software & support is worth to you!
						<br/>
						<br/>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1">
						<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
						">
						</form>
					</span>';
		
	}
	
}
?>
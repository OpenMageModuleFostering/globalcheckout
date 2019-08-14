<?php

/**
 * AnnaConcept
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * It is also available through the world-wide-web at this URL:
 * https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @category    AnnaConcept
 * @package     AnnaConcept_GlobalCheckout
 * @copyright   Copyright (c) 2015 AnnaConcept. (http://annaconcept.com)
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 */

/**
 * GlobalCheckout Standard payment method model
 *
 * @category    AnnaConcept
 * @package     AnnaConcept_GlobalCheckout
 * @author      Sebastian Pruteanu <sebi.pruteanu@gmail.com>
 */

class AnnaConcept_GlobalCheckout_Model_Standard extends Mage_Payment_Model_Method_Abstract
{
	protected $_code					= 'globalcheckout';
	protected $_isInitializeNeeded		= true;
	protected $_canUseInternal			= true;
	protected $_canUseForMultishipping 	= true;
	
	protected $_formBlockType = 'globalcheckout/form';

	public function getCheckoutRedirectUrl() {
		return Mage::getUrl('globalcheckout/index/redirect', array('_secure' => true));
	}
}
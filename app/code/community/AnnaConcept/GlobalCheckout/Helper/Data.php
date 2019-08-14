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
 * Data helper
 *
 * @category    AnnaConcept
 * @package     AnnaConcept_GlobalCheckout
 * @author      Sebastian Pruteanu <sebi.pruteanu@gmail.com>
 */

class AnnaConcept_GlobalCheckout_Helper_Data extends Mage_Payment_Helper_Data

{
	const XML_PATH_EMAIL_RECIPIENT  = 'payment/globalcheckout/notification_email';
    const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'payment/globalcheckout/email_template';
	const XML_PATH_PAYMENT_LINK		= 'payment/globalcheckout/payment_url';
	
	public function payment_link_email_notification($orderId, $amount, $shipping) {
        $amount = round($amount, 2);
        $shipping = round($shipping, 2);
		$link = Mage::getStoreConfig(self::XML_PATH_PAYMENT_LINK);

		$link = preg_replace('/{order}/', $orderId, $link);
		$link = preg_replace('/{amount}/', $amount, $link);
		$link = preg_replace('/{shipping}/', $shipping, $link);
		
		$translate = Mage::getSingleton('core/translate');
		$mailTemplate = Mage::getModel('core/email_template');
                
        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
            ->setReplyTo(Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER))
            ->sendTransactional(
                Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                null,
                array(
                	'link' 		=> $link,
                	'order'		=> $orderId,
                	'amount'	=> $amount,
                	'shipping'	=> $shipping
				)
        );
	}
}
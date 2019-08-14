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
 * Observer model
 *
 * @category    AnnaConcept
 * @package     AnnaConcept_GlobalCheckout
 * @author      Sebastian Pruteanu <sebi.pruteanu@gmail.com>
 */

class AnnaConcept_GlobalCheckout_Model_Observer {

    public function paymentMethodIsActive($observer)
    {
        if(Mage::getStoreConfig("payment/globalcheckout/gc_specificcountry"))
        {
            $method = $observer->getMethodInstance();
            $result = $observer->getResult();
            $quote  = Mage::getSingleton('checkout/session')->getQuote();
            $shipping_address = $quote->getShippingAddress();

            $countries = array();
            if(Mage::getStoreConfig("payment/globalcheckout/gc_allowspecific")) {
                $countries = explode(",", Mage::getStoreConfig("payment/globalcheckout/gc_specificcountry"));
            } else {
                $countries = explode(",", Mage::getStoreConfig("general/country/allow"));
            }

            if($method->getCode() == 'globalcheckout'){
                if(!in_array($shipping_address->getCountry(), $countries)) {
                    $result->isAvailable = false;
                }
            } elseif(Mage::getStoreConfig("payment/globalcheckout/disable_other_methods")) {
                if(in_array($shipping_address->getCountry(), $countries)) {
                    $result->isAvailable = false;
                }
            }
        }
    }

}
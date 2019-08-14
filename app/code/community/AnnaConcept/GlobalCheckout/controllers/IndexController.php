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
 * Index controller
 *
 * @category    AnnaConcept
 * @package     AnnaConcept_GlobalCheckout
 * @author      Sebastian Pruteanu <sebi.pruteanu@gmail.com>
 */

class AnnaConcept_GlobalCheckout_IndexController extends Mage_Core_Controller_Front_Action 
{
	public function redirectAction() {
		if (Mage::getStoreConfig("payment/globalcheckout/live_url") == '' || Mage::getStoreConfig("payment/globalcheckout/debug_url") == '') {
			Mage::getSingleton('core/session')->addError($this->__('Global Checkout missconfigured. Please contact administrator !'));
		}
		
		if (!Mage::getStoreConfig("payment/globalcheckout/debug")) {
			$url = Mage::getStoreConfig("payment/globalcheckout/live_url");
		} else {
			$url = Mage::getStoreConfig("payment/globalcheckout/debug_url");
		}
		
		$form = new Varien_Data_Form();
		$form->setAction($url)	
            ->setId('globalcheckoutform')
            ->setName('globalcheckoutform')
            ->setMethod('POST')
            ->setUseContainer(true);

        /* @var $quote Mage_Sales_Model_Quote */
        $quote = Mage::getSingleton('checkout/cart')->getQuote();
		
		(int)$i = 0;
		foreach($quote->getItemsCollection() as $item)
		{
            if($item->getProductType() === 'simple') {
                $i += 1;
                $product = Mage::getModel('catalog/product')->load($item->getProductId());
                $form->addField('url' . $i, 'hidden', array('name' => 'url' . $i, 'value' => $product->getUrl()));
                $form->addField('sku' . $i, 'hidden', array('name' => 'sku' . $i, 'value' => $product->getSku()));
                $form->addField('prodname' . $i, 'hidden', array('name' => 'prodname' . $i, 'value' => $product->getName()));
                $form->addField('prodnumb' . $i, 'hidden', array('name' => 'prodnumb' . $i, 'value' => (int)$item->getQty()));
                $form->addField('unitprice' . $i, 'hidden', array('name' => 'unitprice' . $i, 'value' => $item->getPrice()));
                $form->addField('volumetricweight' . $i, 'hidden', array('name' => 'volumetricweight' . $i, 'value' => $product->getWeight()));
                $form->addField('imageurl' . $i, 'hidden', array('name' => 'imageurl' . $i, 'value' => $product->getImageUrl()));
                $form->addField('size' . $i, 'hidden', array('name' => 'size' . $i, 'value' => ''));
                $form->addField('color' . $i, 'hidden', array('name' => 'color' . $i, 'value' => ''));
                $form->addField('ormd' . $i, 'hidden', array('name' => 'ormd' . $i, 'value' => $product->getGcOrmd()));
            }
		}
		
        $totals = $quote->getTotals();
        
		$form->addField('itemNumber', 'hidden', array('name'=>'itemNumber', 'value'=>$quote->getItemsCollection()->count()));
		if(isset($totals['discount'])) {
			$form->addField('merchant_credit', 'hidden', array('name'=>'merchant_credit', 'value'=>abs($totals['discount']->getValue())));
		}
		
		$url = Mage::getBaseUrl();
		$parsed_url = parse_url($url);
		$merchant_url = $parsed_url['host'];
		
		$form->addField('cartlockurl', 'hidden', array('name'=>'cartlockurl', 'value'=>Mage::getBaseUrl().'globalcheckout/index/success'));
		$form->addField('domestichandling', 'hidden', array('name'=>'domestichandling', 'value'=>'0'));
		$form->addField('merchant', 'hidden', array('name'=>'merchant', 'value'=>$merchant_url));
		$form->addField('merchantShoppingCart', 'hidden', array('name'=>'merchantShoppingCart', 'value'=>Mage::getBaseUrl().'checkout/cart/'));
		
        //Billing address
        $billing_address = $quote->getBillingAddress();
        $form->addField('addressLine1', 'hidden', array('name'=>'addressLine1', 'value'=>$billing_address->getStreet1()));
        $form->addField('addressLine2', 'hidden', array('name'=>'addressLine2', 'value'=>$billing_address->getStreet2()));
        $form->addField('addressLine3', 'hidden', array('name'=>'addressLine3', 'value'=>$billing_address->getStreet3()));
        $form->addField('receiver', 'hidden', array('name'=>'receiver', 'value'=>$billing_address->getName()));
        $form->addField('receiverContact', 'hidden', array('name'=>'receiverContact', 'value'=>$billing_address->getTelephone()));
        $form->addField('country', 'hidden', array('name'=>'country', 'value'=>$billing_address->getCountryId()));
        $form->addField('province', 'hidden', array('name'=>'province', 'value'=>$billing_address->getRegion()));
        $form->addField('postcode', 'hidden', array('name'=>'postcode', 'value'=>$billing_address->getPostcode()));
        $form->addField('email', 'hidden', array('name'=>'email', 'value'=>$billing_address->getEmail()));
		
        //Shipping address
        $shipping_address = $quote->getShippingAddress();
        $form->addField('bAddressLine1', 'hidden', array('name'=>'bAddressLine1', 'value'=>$shipping_address->getStreet1()));
        $form->addField('bAddressLine2', 'hidden', array('name'=>'bAddressLine2', 'value'=>$shipping_address->getStreet2()));
        $form->addField('bAddressLine3', 'hidden', array('name'=>'bAddressLine3', 'value'=>$shipping_address->getStreet3()));
        $form->addField('bReceiver', 'hidden', array('name'=>'bReceiver', 'value'=>$shipping_address->getName()));
        $form->addField('bReceiverContact', 'hidden', array('name'=>'bReceiverContact', 'value'=>$shipping_address->getTelephone()));
        $form->addField('bCountry', 'hidden', array('name'=>'bCountry', 'value'=>$shipping_address->getCountryId()));
        $form->addField('bProvince', 'hidden', array('name'=>'bProvince', 'value'=>$shipping_address->getRegion()));
        $form->addField('bPostcode', 'hidden', array('name'=>'bPostcode', 'value'=>$shipping_address->getPostcode()));
        $form->addField('bEmail', 'hidden', array('name'=>'bEmail', 'value'=>$shipping_address->getEmail()));

        $form->addField('merchantaddress', 'hidden', array('name'=>'merchantaddress', 'value'=>"merchantaddress"));

		
		$html = '<html><body>';
        $html.= $this->__('You will be redirected to Global Checkout in a few seconds.');
        $html.= $form->toHtml();
        $html.= '<script type="text/javascript">document.getElementById("globalcheckoutform").submit();</script>';
        $html.= '</body></html>';
        Mage::log($html, null, 'GC.log');
		
		echo $html;
	}

	public function testAction() {
		echo Mage::helper('globalcheckout')->payment_link_email_notification(1,2,3);
	}

    public function successAction() {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $quote->collectTotals()->save();
        $service = Mage::getModel('sales/service_quote', $quote);
        $service->submitAll();
        $quote->setIsActive(false)->save();

        $order = $service->getOrder();

        $order->sendNewOrderEmail();
        $order->setEmailSent(true);
        $order->save();

        Mage::getSingleton('checkout/session')->setLastQuoteId($quote->getId())->setLastSuccessQuoteId($quote->getId());
        Mage::getSingleton('checkout/session')->setLastOrderId($order->getId())->setLastRealOrderId($order->getIncrementId());
        Mage::getSingleton('checkout/session')->unsQuoteId();

        Mage::dispatchEvent('gc_order_placed', array('order_ids' => array($order->getId())));

        Mage::log("SUCCESS notification URL called", null, 'GC.log');

        Mage::helper('globalcheckout')->payment_link_email_notification($order->getIncrementId(), $order->getData('grand_total'), $order->getData('shipping_amount'));
    }
}
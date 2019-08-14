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
 * Button block
 *
 * @category    AnnaConcept
 * @package     AnnaConcept_GlobalCheckout
 * @author      Sebastian Pruteanu <sebi.pruteanu@gmail.com>
 */

class AnnaConcept_GlobalCheckout_Block_Button extends Mage_Core_Block_Template {

	function buildItemDescription($item) {
	
		$crlf = "\n";		
		$valueSeperator = " - ";
		$output = "";
		$output .= $this->htmlEscape($item->getName()).$crlf;
		
		$options = $this->getProductOptions($item);
		if (count($options)) {
			for ($c=0; $c<count($options); $c++) {
				
				if (is_array($options[$c]["value"])) {
					$output .= " [ ". $options[$c]["label"].": ".strip_tags(implode($valueSeperator,$options[$c]["value"]))." ] ";				
				}
				else {
					$output .= " [ ".$options[$c]["label"].": ".strip_tags($options[$c]["value"])." ] ";
				}
				
				$output .= $crlf;
			}
		}
		
		// addition of links for downloadable products
		//
		if ($links = $this->getLinks($item)) {
			$output .= " [ " . strip_tags($this->getLinksTitle($item));
			foreach ($links as $link) {
				$output .= " ( " . strip_tags($link->getTitle()) . " ) ";
			}
			$output .= " ] ";
			$output .= $crlf;
		}
		
		
		return $output;
	}
	
	function getProductOptions($item)
    {
      $options = array();
      if ($optionIds = $item->getOptionByCode('option_ids')) {
          $options = array();
          foreach (explode(',', $optionIds->getValue()) as $optionId) {
              if ($option = $item->getProduct()->getOptionById($optionId)) {

                  $quoteItemOption = $item->getOptionByCode('option_' . $option->getId());

                  $group = $option->groupFactory($option->getType())
                      ->setOption($option)
                      ->setQuoteItemOption($quoteItemOption);

                  $options[] = array(
                      'label' => $option->getTitle(),
                      'value' => $group->getFormattedOptionValue($quoteItemOption->getValue()),
                      'print_value' => $group->getPrintableOptionValue($quoteItemOption->getValue()),
                      'option_id' => $option->getId(),
                      'option_type' => $option->getType(),
                      'custom_view' => $group->isCustomizedView()
                  );
              }
          }
      }
      if ($addOptions = $item->getOptionByCode('additional_options')) {
          $options = array_merge($options, unserialize($addOptions->getValue()));
      }
      
	  
	  if ($item->getProduct()->isConfigurable()) {
	  	$options = array_merge($this->getProductAttributes($item), $options); // configurable products
       }
	   
	   
	   if ($item->getProduct()->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
	   	$options = array_merge($this->_getBundleOptions($item), $options); // bundle products
	   }
	  
	  
	  return $options;
    }

	function getLinksTitle($item)
    {
        if ($item->getProduct()->getLinksTitle()) {
            return $item->getProduct()->getLinksTitle();
        }
        return Mage::getStoreConfig(Mage_Downloadable_Model_Link::XML_PATH_LINKS_TITLE);
    }
	
	function buttonClassName() {		
		
		$className = "";
		
		$version = Mage::getVersion();
		
		if ($version >= "1.4") {
			$className = "button btn-checkout";
		}
		else {
			$className = "form-button-alt";
		}
		
		return $className;
	}
	
	function getProductThumbnail($item)
    {
        
		// begin configurable
		 if ($item->getProduct()->isConfigurable()) {
			$product = $this->getChildProduct($item);
			if (!$product || !$product->getData('thumbnail')
				|| ($product->getData('thumbnail') == 'no_selection')
				|| (Mage::getStoreConfig(Mage_Checkout_Block_Cart_Item_Renderer_Configurable::CONFIGURABLE_PRODUCT_IMAGE) == Mage_Checkout_Block_Cart_Item_Renderer_Configurable::USE_PARENT_IMAGE)) {
				$product = $item->getProduct();
			}
			return Mage::helper('catalog/image')->init($product, 'thumbnail');
		}
		// end configurable
		
		// begin grouped
		if ($item->getProduct()->isGrouped()) {
			$product = $item->getProduct();
			if (!$product->getData('thumbnail')
				||($product->getData('thumbnail') == 'no_selection')
				|| (Mage::getStoreConfig(Mage_Checkout_Block_Cart_Item_Renderer_Grouped::GROUPED_PRODUCT_IMAGE) == Mage_Checkout_Block_Cart_Item_Renderer_Grouped::USE_PARENT_IMAGE)) {
				$product = $this->getGroupedProduct($item);
			}
			return Mage::helper('catalog/image')->init($product, 'thumbnail');
		}
		// end grouped
		
		
		return Mage::helper('catalog/image')->init($item->getProduct(), 'thumbnail');
		
    }
}

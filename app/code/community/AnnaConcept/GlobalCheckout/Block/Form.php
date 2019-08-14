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
 * Form block
 *
 * @category    AnnaConcept
 * @package     AnnaConcept_GlobalCheckout
 * @author      Sebastian Pruteanu <sebi.pruteanu@gmail.com>
 */

class AnnaConcept_GlobalCheckout_Block_Form extends Mage_Payment_Block_Form
{
    /**
     * Set template and redirect message
     */
    public function __construct()
    {
	    parent::__construct();
	    $this
		    ->setTemplate('annaconcept/globalcheckout/form.phtml')
			->setRedirectMessage(
				Mage::helper('globalcheckout')->__('You will be redirected to the GlobalCheckout website when you place an order.')
			);
    }
}

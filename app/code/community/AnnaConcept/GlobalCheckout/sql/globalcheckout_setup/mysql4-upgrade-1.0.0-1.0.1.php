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
 * Install script
 *
 * @category    AnnaConcept
 * @package     AnnaConcept_GlobalCheckout
 * @author      Sebastian Pruteanu <sebi.pruteanu@gmail.com>
 */

/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();

$installer->addAttribute('catalog_product', 'gc_ormd', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'GlobalCheckout ORMD',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'group'             => 'General',
    'sort'              => 99
));

$installer->endSetup();
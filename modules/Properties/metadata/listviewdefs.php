<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$listViewDefs['Properties'] = array(
    'NAME' => array(
        'width' => '20%',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
    ),
    'PROPERTY_TYPE' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PROPERTY_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'STATUS' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'default' => true,
    ),
    'PRICE' => array(
        'type' => 'currency',
        'label' => 'LBL_PRICE',
        'currency_format' => true,
        'width' => '12%',
        'default' => true,
    ),
    'PROPERTY_ADDRESS' => array(
        'type' => 'varchar',
        'label' => 'LBL_PROPERTY_ADDRESS',
        'width' => '15%',
        'default' => true,
    ),
    'BEDROOMS' => array(
        'type' => 'int',
        'label' => 'LBL_BEDROOMS',
        'width' => '8%',
        'default' => true,
    ),
    'BATHROOMS' => array(
        'type' => 'decimal',
        'label' => 'LBL_BATHROOMS',
        'width' => '8%',
        'default' => true,
    ),
    'SQUARE_FEET' => array(
        'type' => 'int',
        'label' => 'LBL_SQUARE_FEET',
        'width' => '10%',
        'default' => false,
    ),
    'MLS_NUMBER' => array(
        'type' => 'varchar',
        'label' => 'LBL_MLS_NUMBER',
        'width' => '10%',
        'default' => false,
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '10%',
        'label' => 'LBL_ASSIGNED_TO',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
    ),
); 
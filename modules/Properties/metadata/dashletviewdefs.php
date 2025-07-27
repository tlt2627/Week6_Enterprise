<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$dashletData['PropertiesDashlet']['searchFields'] = array(
    'name' => array(
        'default' => ''
    ),
    'property_type' => array(
        'default' => ''
    ),
    'status' => array(
        'default' => ''
    ),
    'assigned_user_id' => array(
        'type' => 'assigned_user_name',
        'default' => ''
    ),
);

$dashletData['PropertiesDashlet']['columns'] = array(
    'name' => array(
        'width' => '40',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true,
        'name' => 'name',
    ),
    'property_type' => array(
        'width' => '15',
        'label' => 'LBL_LIST_PROPERTY_TYPE',
        'default' => true,
        'name' => 'property_type',
    ),
    'status' => array(
        'width' => '15',
        'label' => 'LBL_LIST_STATUS',
        'default' => true,
        'name' => 'status',
    ),
    'price' => array(
        'width' => '15',
        'label' => 'LBL_LIST_PRICE',
        'default' => true,
        'name' => 'price',
        'type' => 'currency',
    ),
    'assigned_user_name' => array(
        'width' => '15',
        'label' => 'LBL_LIST_ASSIGNED_TO',
        'default' => false,
        'name' => 'assigned_user_name',
    ),
    'date_entered' => array(
        'width' => '15',
        'label' => 'LBL_DATE_ENTERED',
        'default' => false,
        'name' => 'date_entered',
    ),
); 
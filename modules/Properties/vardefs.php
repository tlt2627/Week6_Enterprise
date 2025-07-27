<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$dictionary['Properties'] = array(
    'table' => 'properties',
    'audited' => true,
    'unified_search' => true,
    'full_text_search' => true,
    'fields' => array(
        'id' => array('name' => 'id', 'vname' => 'LBL_ID', 'type' => 'id', 'required' => true),
        'name' => array('name' => 'name', 'vname' => 'LBL_NAME', 'type' => 'name', 'len' => '255', 'required' => true),
        'property_address' => array('name' => 'property_address', 'vname' => 'LBL_PROPERTY_ADDRESS', 'type' => 'varchar', 'len' => '255'),
        'price' => array('name' => 'price', 'vname' => 'LBL_PRICE', 'type' => 'currency', 'dbType' => 'decimal', 'precision' => 26, 'scale' => 6),
        'bedrooms' => array('name' => 'bedrooms', 'vname' => 'LBL_BEDROOMS', 'type' => 'int'),
        'bathrooms' => array('name' => 'bathrooms', 'vname' => 'LBL_BATHROOMS', 'type' => 'decimal', 'precision' => 4, 'scale' => 1),
        'square_feet' => array('name' => 'square_feet', 'vname' => 'LBL_SQUARE_FEET', 'type' => 'int'),
        'property_type' => array('name' => 'property_type', 'vname' => 'LBL_PROPERTY_TYPE', 'type' => 'enum', 'options' => 'property_type_list'),
        'status' => array('name' => 'status', 'vname' => 'LBL_STATUS', 'type' => 'enum', 'options' => 'property_status_list'),
        'photos' => array('name' => 'photos', 'vname' => 'LBL_PHOTOS', 'type' => 'image', 'len' => '255'),
        'mls_number' => array('name' => 'mls_number', 'vname' => 'LBL_MLS_NUMBER', 'type' => 'varchar', 'len' => '50'),
        'listing_date' => array('name' => 'listing_date', 'vname' => 'LBL_LISTING_DATE', 'type' => 'date'),
        'closing_date' => array('name' => 'closing_date', 'vname' => 'LBL_CLOSING_DATE', 'type' => 'date'),
        'commission_rate' => array('name' => 'commission_rate', 'vname' => 'LBL_COMMISSION_RATE', 'type' => 'decimal', 'precision' => 5, 'scale' => 2),
        'description' => array('name' => 'description', 'vname' => 'LBL_DESCRIPTION', 'type' => 'text'),
        'date_entered' => array('name' => 'date_entered', 'vname' => 'LBL_DATE_ENTERED', 'type' => 'datetime'),
        'date_modified' => array('name' => 'date_modified', 'vname' => 'LBL_DATE_MODIFIED', 'type' => 'datetime'),
        'assigned_user_id' => array('name' => 'assigned_user_id', 'vname' => 'LBL_ASSIGNED_USER_ID', 'type' => 'relate', 'table' => 'users', 'module' => 'Users'),
        'assigned_user_name' => array('name' => 'assigned_user_name', 'vname' => 'LBL_ASSIGNED_TO', 'type' => 'relate', 'source' => 'non-db', 'table' => 'users', 'id_name' => 'assigned_user_id', 'module' => 'Users'),
        'created_by' => array('name' => 'created_by', 'vname' => 'LBL_CREATED', 'type' => 'assigned_user_name', 'table' => 'users'),
        'deleted' => array('name' => 'deleted', 'vname' => 'LBL_DELETED', 'type' => 'bool', 'default' => '0')
    ),
    'indices' => array(
        array('name' => 'propertiespk', 'type' => 'primary', 'fields' => array('id')),
        array('name' => 'idx_properties_name', 'type' => 'index', 'fields' => array('name')),
        array('name' => 'idx_properties_status', 'type' => 'index', 'fields' => array('status')),
        array('name' => 'idx_properties_type', 'type' => 'index', 'fields' => array('property_type'))
    ),
    'relationships' => array(),
); 
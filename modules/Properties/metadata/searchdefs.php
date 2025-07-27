<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$searchdefs['Properties'] = array(
    'templateMeta' => array(
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => array('label' => '10', 'field' => '30'),
    ),
    'layout' => array(
        'basic_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'property_type' => array(
                'name' => 'property_type',
                'default' => true,
                'width' => '10%',
            ),
            'status' => array(
                'name' => 'status',
                'default' => true,
                'width' => '10%',
            ),
            'property_address' => array(
                'name' => 'property_address',
                'default' => true,
                'width' => '10%',
            ),
        ),
        'advanced_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'property_type' => array(
                'name' => 'property_type',
                'default' => true,
                'width' => '10%',
            ),
            'status' => array(
                'name' => 'status',
                'default' => true,
                'width' => '10%',
            ),
            'price' => array(
                'name' => 'price',
                'default' => true,
                'width' => '10%',
            ),
            'property_address' => array(
                'name' => 'property_address',
                'default' => true,
                'width' => '10%',
            ),
            'bedrooms' => array(
                'name' => 'bedrooms',
                'default' => true,
                'width' => '10%',
            ),
            'bathrooms' => array(
                'name' => 'bathrooms',
                'default' => true,
                'width' => '10%',
            ),
            'square_feet' => array(
                'name' => 'square_feet',
                'default' => true,
                'width' => '10%',
            ),
            'mls_number' => array(
                'name' => 'mls_number',
                'default' => true,
                'width' => '10%',
            ),
            'listing_date' => array(
                'name' => 'listing_date',
                'default' => true,
                'width' => '10%',
            ),
            'assigned_user_name' => array(
                'name' => 'assigned_user_name',
                'default' => true,
                'width' => '10%',
            ),
        ),
    ),
); 
<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$viewdefs['Properties']['DetailView'] = array(
    'templateMeta' => array(
        'form' => array(
            'buttons' => array(
                'EDIT',
                'DUPLICATE',
                'DELETE',
                'FIND_DUPLICATES',
            )
        ),
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30')
        ),
        'useTabs' => false,
    ),
    'panels' => array(
        'LBL_PROPERTY_INFORMATION' => array(
            array(
                'name',
                'assigned_user_name',
            ),
            array(
                'property_type',
                'status',
            ),
            array(
                'price',
                'commission_rate',
            ),
            array(
                'bedrooms',
                'bathrooms',
            ),
            array(
                'square_feet',
                'mls_number',
            ),
        ),
        'LBL_ADDRESS_INFORMATION' => array(
            array(
                array(
                    'name' => 'property_address',
                    'comment' => 'Property full address',
                    'label' => 'LBL_PROPERTY_ADDRESS',
                ),
            ),
        ),
        'LBL_ADDITIONAL_INFORMATION' => array(
            array(
                'listing_date',
                'closing_date',
            ),
            array(
                array(
                    'name' => 'description',
                    'comment' => 'Property description',
                    'label' => 'LBL_DESCRIPTION',
                ),
            ),
            array(
                'date_entered',
                'date_modified',
            ),
        ),
    )
); 
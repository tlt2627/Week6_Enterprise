<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$mod_strings = array(
    'LBL_MODULE_NAME' => 'Properties',
    'LBL_MODULE_TITLE' => 'Properties: Home',
    'LBL_SEARCH_FORM_TITLE' => 'Property Search',
    'LBL_LIST_FORM_TITLE' => 'Property List',
    'LBL_NEW_FORM_TITLE' => 'New Property',
    'LBL_NAME' => 'Property Name',
    'LBL_PROPERTY_ADDRESS' => 'Address',
    'LBL_PRICE' => 'Price',
    'LBL_BEDROOMS' => 'Bedrooms',
    'LBL_BATHROOMS' => 'Bathrooms',
    'LBL_SQUARE_FEET' => 'Square Feet',
    'LBL_PROPERTY_TYPE' => 'Property Type',
    'LBL_STATUS' => 'Status',
    'LBL_PHOTOS' => 'Photos',
    'LBL_MLS_NUMBER' => 'MLS Number',
    'LBL_LISTING_DATE' => 'Listing Date',
    'LBL_CLOSING_DATE' => 'Closing Date',
    'LBL_COMMISSION_RATE' => 'Commission Rate (%)',
    'LBL_DESCRIPTION' => 'Description',
    'LBL_DATE_ENTERED' => 'Date Created',
    'LBL_DATE_MODIFIED' => 'Date Modified',
    'LBL_ASSIGNED_USER_ID' => 'Assigned To',
    'LBL_ASSIGNED_TO' => 'Assigned To',
    'LBL_CREATED' => 'Created By',
    'LBL_DELETED' => 'Deleted',
    'LNK_NEW_RECORD' => 'Create Property',
    'LNK_LIST' => 'View Properties',
    'LNK_IMPORT_PROPERTIES' => 'Import Properties',
    
    // List view labels
    'LBL_LIST_NAME' => 'Property Name',
    'LBL_LIST_PRICE' => 'Price',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_PROPERTY_TYPE' => 'Type',
    'LBL_LIST_BEDROOMS' => 'Beds',
    'LBL_LIST_BATHROOMS' => 'Baths',
    'LBL_LIST_ASSIGNED_TO' => 'Agent',
    
    // Panel labels
    'LBL_PROPERTY_INFORMATION' => 'Property Information',
    'LBL_ADDRESS_INFORMATION' => 'Address Information',
    'LBL_FINANCIAL_INFORMATION' => 'Financial Information',
    'LBL_ADDITIONAL_INFORMATION' => 'Additional Information',
);

// Global module registration
$app_list_strings['moduleList']['Properties'] = 'Properties';

// Property type dropdown
$app_list_strings['property_type_list'] = array(
    '' => '',
    'House' => 'House',
    'Condo' => 'Condo',
    'Townhouse' => 'Townhouse',
    'Apartment' => 'Apartment',
    'Commercial' => 'Commercial',
    'Land' => 'Land',
    'Multi-Family' => 'Multi-Family',
    'Mobile Home' => 'Mobile Home',
    'Farm' => 'Farm/Ranch',
);

// Property status dropdown
$app_list_strings['property_status_list'] = array(
    '' => '',
    'Available' => 'Available',
    'Under Contract' => 'Under Contract',
    'Pending' => 'Pending',
    'Sold' => 'Sold',
    'Withdrawn' => 'Withdrawn',
    'Expired' => 'Expired',
    'Coming Soon' => 'Coming Soon',
); 
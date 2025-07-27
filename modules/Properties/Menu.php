<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $module_menu;
$module_menu = Array(
    Array("index.php?module=Properties&action=index", "Properties Dashboard", "Properties"),
    Array("index.php?module=Properties&action=EditView", "Create Property", "Properties"),
    Array("index.php?module=Properties&action=ListView", "Property Listings", "Properties"),
);
?> 
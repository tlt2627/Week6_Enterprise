<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('data/SugarBean.php');
require_once('include/utils.php');

class Properties extends SugarBean
{
    public $table_name = 'properties';
    public $object_name = 'Properties';
    public $module_dir = 'Properties';
    public $module_name = 'Properties';
    public $new_schema = true;
    public $importable = true;
    public $disable_row_level_security = true;
    public $acl_display_only = true;
    public $disable_custom_fields = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return false; // Disable ACL completely
        }
        return false;
    }

    public function getFormattedPrice()
    {
        if (!empty($this->price)) {
            return '$' . number_format($this->price, 0);
        }
        return '';
    }

    public function getStatusBadge()
    {
        switch ($this->status) {
            case 'Available': return 'badge-success';
            case 'Under Contract': return 'badge-warning';
            case 'Sold': return 'badge-danger';
            default: return 'badge-secondary';
        }
    }

    public function getPropertyTypeIcon()
    {
        switch ($this->property_type) {
            case 'House': return 'fa-home';
            case 'Condo': return 'fa-building';
            case 'Townhouse': return 'fa-city';
            case 'Land': return 'fa-tree';
            default: return 'fa-home';
        }
    }

    public function getFullAddress()
    {
        return $this->property_address ?? '';
    }

    public function calculateCommission($commission_rate = 6)
    {
        if (!empty($this->price)) {
            return ($this->price * $commission_rate) / 100;
        }
        return 0;
    }
} 
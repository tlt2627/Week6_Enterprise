<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('data/SugarBean.php');
require_once('include/utils.php');

class PropertySearch extends SugarBean
{
    public $table_name = 'property_searches';
    public $object_name = 'PropertySearch';
    public $module_dir = 'PropertySearch';
    public $module_name = 'PropertySearch';
    public $new_schema = true;
    public $importable = false;
    public $disable_row_level_security = true;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Search properties based on criteria
     */
    public function searchProperties($criteria = array())
    {
        global $db;
        
        $where_conditions = array();
        $params = array();
        
        // Price range
        if (!empty($criteria['min_price'])) {
            $where_conditions[] = "p.price >= ?";
            $params[] = floatval($criteria['min_price']);
        }
        if (!empty($criteria['max_price'])) {
            $where_conditions[] = "p.price <= ?";
            $params[] = floatval($criteria['max_price']);
        }
        
        // Bedrooms
        if (!empty($criteria['bedrooms'])) {
            $where_conditions[] = "p.bedrooms >= ?";
            $params[] = intval($criteria['bedrooms']);
        }
        
        // Bathrooms
        if (!empty($criteria['bathrooms'])) {
            $where_conditions[] = "p.bathrooms >= ?";
            $params[] = floatval($criteria['bathrooms']);
        }
        
        // Property type
        if (!empty($criteria['property_type'])) {
            $where_conditions[] = "p.property_type = ?";
            $params[] = $criteria['property_type'];
        }
        
        // Status
        if (!empty($criteria['status'])) {
            $where_conditions[] = "p.status = ?";
            $params[] = $criteria['status'];
        }
        
        // Location search
        if (!empty($criteria['location'])) {
            $where_conditions[] = "(p.property_address LIKE ? OR p.name LIKE ?)";
            $params[] = '%' . $criteria['location'] . '%';
            $params[] = '%' . $criteria['location'] . '%';
        }
        
        $where_clause = empty($where_conditions) ? "1=1" : implode(" AND ", $where_conditions);
        
        $query = "SELECT p.*, u.user_name as assigned_user_name 
                  FROM properties p 
                  LEFT JOIN users u ON p.assigned_user_id = u.id 
                  WHERE p.deleted = 0 AND $where_clause 
                  ORDER BY p.date_entered DESC 
                  LIMIT 50";
        
        $result = $db->query($query, $params);
        $properties = array();
        
        while ($row = $db->fetchByAssoc($result)) {
            $properties[] = $row;
        }
        
        return $properties;
    }
    
    /**
     * Get property search statistics
     */
    public function getSearchStats($criteria = array())
    {
        $properties = $this->searchProperties($criteria);
        
        $stats = array(
            'total_found' => count($properties),
            'avg_price' => 0,
            'min_price' => 0,
            'max_price' => 0,
            'property_types' => array()
        );
        
        if (!empty($properties)) {
            $prices = array_column($properties, 'price');
            $prices = array_filter($prices, function($p) { return $p > 0; });
            
            if (!empty($prices)) {
                $stats['avg_price'] = round(array_sum($prices) / count($prices));
                $stats['min_price'] = min($prices);
                $stats['max_price'] = max($prices);
            }
            
            // Count property types
            foreach ($properties as $property) {
                $type = $property['property_type'] ?: 'Unknown';
                $stats['property_types'][$type] = ($stats['property_types'][$type] ?? 0) + 1;
            }
        }
        
        return $stats;
    }
}
?> 
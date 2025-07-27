<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Properties/Properties.php');

class PropertiesDashlet extends DashletGeneric
{
    public function __construct($id, $def = null)
    {
        global $current_user, $app_strings;
        require('modules/Properties/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if (empty($def['title'])) {
            $this->title = '🏠 Properties Dashboard';
        }

        $this->searchFields = $dashletData['PropertiesDashlet']['searchFields'];
        $this->columns = $dashletData['PropertiesDashlet']['columns'];

        $this->seedBean = new Properties();
    }

    /**
     * Override parent display to add Real Estate specific content
     */
    public function display()
    {
        global $db;
        
        // Get property statistics
        $stats = $this->getPropertyStats();
        
        // Get recent properties
        $recentProperties = $this->getRecentProperties();

        // Custom display with stats and recent properties
        $html = '<div class="real-estate-dashlet">';
        
        // Statistics section
        $html .= '<div class="re-stats-section">';
        $html .= '<div class="re-stats-grid">';
        $html .= '<div class="re-stat-card">';
        $html .= '<div class="re-stat-value">' . $stats['total'] . '</div>';
        $html .= '<div class="re-stat-label">Total Properties</div>';
        $html .= '</div>';
        $html .= '<div class="re-stat-card">';
        $html .= '<div class="re-stat-value">' . $stats['available'] . '</div>';
        $html .= '<div class="re-stat-label">Available</div>';
        $html .= '</div>';
        $html .= '<div class="re-stat-card">';
        $html .= '<div class="re-stat-value">$' . number_format($stats['avg_price'], 0) . '</div>';
        $html .= '<div class="re-stat-label">Avg Price</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        // Recent properties section
        $html .= '<div class="re-recent-section">';
        $html .= '<h4 style="margin: 15px 0 10px 0; color: #2c3e50;">🕐 Recent Listings</h4>';
        
        if (!empty($recentProperties)) {
            foreach ($recentProperties as $property) {
                $html .= '<div class="re-property-item">';
                $html .= '<div class="re-property-main">';
                $html .= '<a href="index.php?module=Properties&action=DetailView&record=' . $property['id'] . '" class="re-property-link">';
                $html .= '<strong>' . htmlspecialchars($property['name']) . '</strong>';
                $html .= '</a>';
                $html .= '<div class="re-property-price">$' . number_format($property['price'], 0) . '</div>';
                $html .= '</div>';
                $html .= '<div class="re-property-details">';
                $html .= htmlspecialchars($property['property_type']) . ' • ';
                $html .= $property['bedrooms'] . ' bed • ';
                $html .= $property['bathrooms'] . ' bath';
                $html .= '</div>';
                $html .= '</div>';
            }
        } else {
            $html .= '<p style="color: #666; text-align: center; padding: 20px;">No properties yet. <a href="index.php?module=Properties&action=EditView">Create your first property</a></p>';
        }
        
        $html .= '</div>';

        // Action buttons
        $html .= '<div class="re-actions-section">';
        $html .= '<a href="modules/Properties/index.php" class="re-btn re-btn-primary">🏠 Dashboard</a>';
        $html .= '<a href="index.php?module=Properties&action=EditView" class="re-btn re-btn-success">➕ Add Property</a>';
        $html .= '<a href="modules/PropertySearch/index.php" class="re-btn re-btn-info">🔍 Search</a>';
        $html .= '</div>';
        
        $html .= '</div>';

        // Add CSS
        $html .= '<style>
        .real-estate-dashlet {
            font-family: Arial, sans-serif;
        }
        .re-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }
        .re-stat-card {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            border-left: 3px solid #667eea;
        }
        .re-stat-value {
            font-size: 1.5em;
            font-weight: bold;
            color: #2c3e50;
        }
        .re-stat-label {
            font-size: 0.85em;
            color: #666;
            margin-top: 4px;
        }
        .re-property-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .re-property-item:last-child {
            border-bottom: none;
        }
        .re-property-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .re-property-link {
            color: #2c3e50;
            text-decoration: none;
        }
        .re-property-link:hover {
            color: #667eea;
        }
        .re-property-price {
            font-weight: bold;
            color: #e74c3c;
        }
        .re-property-details {
            font-size: 0.9em;
            color: #666;
            margin-top: 4px;
        }
        .re-actions-section {
            margin-top: 15px;
            text-align: center;
        }
        .re-btn {
            display: inline-block;
            padding: 6px 12px;
            margin: 2px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }
        .re-btn-primary {
            background: #667eea;
            color: white;
        }
        .re-btn-success {
            background: #28a745;
            color: white;
        }
        .re-btn-info {
            background: #17a2b8;
            color: white;
        }
        .re-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        </style>';

        return $html;
    }

    private function getPropertyStats()
    {
        try {
            $db = DBManagerFactory::getInstance();
            
            $total_result = $db->query("SELECT COUNT(*) as total FROM properties WHERE deleted = 0");
            $total_row = $db->fetchByAssoc($total_result);
            $total = $total_row['total'] ?? 0;

            $available_result = $db->query("SELECT COUNT(*) as available FROM properties WHERE deleted = 0 AND status = 'Available'");
            $available_row = $db->fetchByAssoc($available_result);
            $available = $available_row['available'] ?? 0;

            $avg_price_result = $db->query("SELECT AVG(price) as avg_price FROM properties WHERE deleted = 0 AND price > 0");
            $avg_price_row = $db->fetchByAssoc($avg_price_result);
            $avg_price = $avg_price_row['avg_price'] ?? 0;

            return array(
                'total' => $total,
                'available' => $available,
                'avg_price' => $avg_price
            );
        } catch (Exception $e) {
            return array('total' => 0, 'available' => 0, 'avg_price' => 0);
        }
    }

    private function getRecentProperties()
    {
        try {
            $db = DBManagerFactory::getInstance();
            $result = $db->query("SELECT id, name, price, property_type, bedrooms, bathrooms FROM properties WHERE deleted = 0 ORDER BY date_entered DESC LIMIT 5");
            
            $properties = array();
            while ($row = $db->fetchByAssoc($result)) {
                $properties[] = $row;
            }
            
            return $properties;
        } catch (Exception $e) {
            return array();
        }
    }
} 
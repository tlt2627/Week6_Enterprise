<?php
if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');

echo '<!DOCTYPE html>
<html>
<head>
    <title>Property Analytics - Real Estate CRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; margin: -20px -20px 20px -20px; border-radius: 8px 8px 0 0; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; border-left: 4px solid #667eea; }
        .stat-value { font-size: 2.5em; font-weight: bold; color: #2c3e50; margin-bottom: 10px; }
        .stat-label { color: #666; font-size: 1.1em; }
        .chart-container { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .btn { background: #007cba; color: white; padding: 12px 24px; border: none; border-radius: 6px; text-decoration: none; display: inline-block; margin: 5px; transition: all 0.3s ease; }
        .btn:hover { background: #005a87; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Property Analytics</h1>
            <p>Real Estate Market Intelligence & Performance Metrics</p>
        </div>';

// Calculate analytics
try {
    $db = DBManagerFactory::getInstance();
    
    // Total properties
    $total_result = $db->query("SELECT COUNT(*) as total FROM properties WHERE deleted = 0");
    $total_row = $db->fetchByAssoc($total_result);
    $total_properties = $total_row['total'] ?? 0;
    
    // Available properties
    $available_result = $db->query("SELECT COUNT(*) as available FROM properties WHERE deleted = 0 AND status = 'Available'");
    $available_row = $db->fetchByAssoc($available_result);
    $available_properties = $available_row['available'] ?? 0;
    
    // Sold properties
    $sold_result = $db->query("SELECT COUNT(*) as sold FROM properties WHERE deleted = 0 AND status = 'Sold'");
    $sold_row = $db->fetchByAssoc($sold_result);
    $sold_properties = $sold_row['sold'] ?? 0;
    
    // Average price
    $avg_price_result = $db->query("SELECT AVG(price) as avg_price FROM properties WHERE deleted = 0 AND price > 0");
    $avg_price_row = $db->fetchByAssoc($avg_price_result);
    $avg_price = $avg_price_row['avg_price'] ?? 0;
    
    // Total value
    $total_value_result = $db->query("SELECT SUM(price) as total_value FROM properties WHERE deleted = 0 AND price > 0");
    $total_value_row = $db->fetchByAssoc($total_value_result);
    $total_value = $total_value_row['total_value'] ?? 0;
    
    // Commission potential
    $commission_potential = $total_value * 0.06; // Assuming 6% average commission
    
    echo '<div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">' . $total_properties . '</div>
            <div class="stat-label">Total Properties</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">' . $available_properties . '</div>
            <div class="stat-label">Available</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">' . $sold_properties . '</div>
            <div class="stat-label">Sold</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">$' . number_format($avg_price, 0) . '</div>
            <div class="stat-label">Average Price</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">$' . number_format($total_value, 0) . '</div>
            <div class="stat-label">Total Portfolio Value</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">$' . number_format($commission_potential, 0) . '</div>
            <div class="stat-label">Commission Potential</div>
        </div>
    </div>';
    
    // Property type breakdown
    $type_result = $db->query("SELECT property_type, COUNT(*) as count FROM properties WHERE deleted = 0 AND property_type IS NOT NULL AND property_type != '' GROUP BY property_type ORDER BY count DESC");
    
    echo '<div class="chart-container">
        <h3>📈 Properties by Type</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px;">';
    
    while ($type_row = $db->fetchByAssoc($type_result)) {
        $percentage = $total_properties > 0 ? round(($type_row['count'] / $total_properties) * 100, 1) : 0;
        echo '<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.5em; font-weight: bold; color: #667eea;">' . $type_row['count'] . '</div>
            <div style="font-size: 0.9em; color: #666; margin: 5px 0;">' . htmlspecialchars($type_row['property_type']) . '</div>
            <div style="font-size: 0.8em; color: #999;">' . $percentage . '%</div>
        </div>';
    }
    
    echo '</div></div>';
    
    // Status breakdown
    $status_result = $db->query("SELECT status, COUNT(*) as count FROM properties WHERE deleted = 0 AND status IS NOT NULL AND status != '' GROUP BY status ORDER BY count DESC");
    
    echo '<div class="chart-container">
        <h3>📊 Properties by Status</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px;">';
    
    $status_colors = array(
        'Available' => '#28a745',
        'Under Contract' => '#ffc107',
        'Sold' => '#dc3545',
        'Pending' => '#17a2b8'
    );
    
    while ($status_row = $db->fetchByAssoc($status_result)) {
        $percentage = $total_properties > 0 ? round(($status_row['count'] / $total_properties) * 100, 1) : 0;
        $color = $status_colors[$status_row['status']] ?? '#6c757d';
        
        echo '<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; border-left: 4px solid ' . $color . ';">
            <div style="font-size: 1.5em; font-weight: bold; color: ' . $color . ';">' . $status_row['count'] . '</div>
            <div style="font-size: 0.9em; color: #666; margin: 5px 0;">' . htmlspecialchars($status_row['status']) . '</div>
            <div style="font-size: 0.8em; color: #999;">' . $percentage . '%</div>
        </div>';
    }
    
    echo '</div></div>';
    
    // Recent activity
    $recent_result = $db->query("SELECT name, price, status, date_entered FROM properties WHERE deleted = 0 ORDER BY date_entered DESC LIMIT 5");
    
    echo '<div class="chart-container">
        <h3>🕐 Recent Activity</h3>
        <div style="margin-top: 20px;">';
    
    while ($recent_row = $db->fetchByAssoc($recent_result)) {
        $date = date('M j, Y', strtotime($recent_row['date_entered']));
        echo '<div style="padding: 12px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-weight: bold; color: #2c3e50;">' . htmlspecialchars($recent_row['name']) . '</div>
                <div style="font-size: 0.9em; color: #666;">' . $date . '</div>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: bold; color: #e74c3c;">$' . number_format($recent_row['price'], 0) . '</div>
                <div style="font-size: 0.9em; color: #666;">' . htmlspecialchars($recent_row['status']) . '</div>
            </div>
        </div>';
    }
    
    echo '</div></div>';
    
} catch (Exception $e) {
    echo '<div style="color: red; padding: 20px; background: #f8d7da; border-radius: 8px; margin: 20px 0;">
        <h3>Analytics Error</h3>
        <p>' . htmlspecialchars($e->getMessage()) . '</p>
    </div>';
}

echo '        <div style="text-align: center; margin-top: 30px;">
            <a href="modules/Properties/index.php" class="btn">🏠 Properties Dashboard</a>
            <a href="modules/PropertySearch/index.php" class="btn">🔍 Property Search</a>
            <a href="index.php?module=Properties&action=index" class="btn">📋 Properties List</a>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("📊 Property Analytics loaded");
        });
    </script>
</body>
</html>';
?> 
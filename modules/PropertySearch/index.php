<?php
if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');
require_once('modules/PropertySearch/PropertySearch.php');

global $current_user;
if (empty($current_user)) {
    require_once('modules/Users/authentication/SugarAuthenticate/SugarAuthenticate.php');
    $current_user = BeanFactory::newBean('Users');
    $current_user->retrieve('1');
}

// Handle search request
$search_criteria = array();
$properties = array();
$stats = array();

if ($_POST || $_GET) {
    $search_criteria = array(
        'min_price' => $_REQUEST['min_price'] ?? '',
        'max_price' => $_REQUEST['max_price'] ?? '',
        'bedrooms' => $_REQUEST['bedrooms'] ?? '',
        'bathrooms' => $_REQUEST['bathrooms'] ?? '',
        'property_type' => $_REQUEST['property_type'] ?? '',
        'status' => $_REQUEST['status'] ?? '',
        'location' => $_REQUEST['location'] ?? ''
    );
    
    $searcher = new PropertySearch();
    $properties = $searcher->searchProperties($search_criteria);
    $stats = $searcher->getSearchStats($search_criteria);
}

echo '<!DOCTYPE html>
<html>
<head>
    <title>🔍 Property Search Dashboard - Real Estate CRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 30px; border-radius: 12px; margin-bottom: 30px; text-align: center; }
        .search-form { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-weight: bold; margin-bottom: 5px; color: #2c3e50; }
        .form-group input, .form-group select { padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; }
        .form-group input:focus, .form-group select:focus { border-color: #28a745; outline: none; }
        .search-btn { background: #28a745; color: white; padding: 15px 30px; border: none; border-radius: 8px; font-size: 18px; cursor: pointer; transition: all 0.3s ease; }
        .search-btn:hover { background: #218838; transform: translateY(-2px); }
        .clear-btn { background: #6c757d; color: white; padding: 15px 30px; border: none; border-radius: 8px; font-size: 18px; cursor: pointer; margin-left: 10px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; }
        .stat-number { font-size: 2.5em; font-weight: bold; color: #28a745; }
        .stat-label { color: #6c757d; margin-top: 5px; }
        .results-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }
        .property-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: all 0.3s ease; }
        .property-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.2); }
        .property-header { padding: 20px; border-bottom: 1px solid #e9ecef; }
        .property-title { font-size: 1.3em; font-weight: bold; color: #2c3e50; margin-bottom: 5px; }
        .property-price { font-size: 1.5em; font-weight: bold; color: #28a745; }
        .property-details { padding: 20px; }
        .property-meta { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 15px; }
        .meta-item { text-align: center; padding: 10px; background: #f8f9fa; border-radius: 8px; }
        .meta-value { font-weight: bold; color: #2c3e50; }
        .meta-label { font-size: 0.9em; color: #6c757d; }
        .property-address { color: #6c757d; margin-bottom: 10px; }
        .status-badge { display: inline-block; padding: 5px 10px; border-radius: 20px; font-size: 0.9em; font-weight: bold; }
        .status-available { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-sold { background: #f8d7da; color: #721c24; }
        .btn-group { text-align: center; margin-top: 20px; }
        .btn { background: #007cba; color: white; padding: 12px 24px; border: none; border-radius: 6px; text-decoration: none; display: inline-block; margin: 5px; cursor: pointer; }
        .btn:hover { background: #005a87; }
        .no-results { text-align: center; padding: 60px; color: #6c757d; }
        .no-results h3 { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Property Search Dashboard</h1>
            <p>Advanced Real Estate Property Search & Discovery</p>
        </div>
        
        <div class="search-form">
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" placeholder="City, Address, or Property Name" value="' . htmlspecialchars($search_criteria['location']) . '">
                    </div>
                    <div class="form-group">
                        <label for="property_type">Property Type</label>
                        <select id="property_type" name="property_type">
                            <option value="">Any Type</option>
                            <option value="House"' . ($search_criteria['property_type'] == 'House' ? ' selected' : '') . '>House</option>
                            <option value="Condo"' . ($search_criteria['property_type'] == 'Condo' ? ' selected' : '') . '>Condo</option>
                            <option value="Townhouse"' . ($search_criteria['property_type'] == 'Townhouse' ? ' selected' : '') . '>Townhouse</option>
                            <option value="Land"' . ($search_criteria['property_type'] == 'Land' ? ' selected' : '') . '>Land</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="">Any Status</option>
                            <option value="Available"' . ($search_criteria['status'] == 'Available' ? ' selected' : '') . '>Available</option>
                            <option value="Under Contract"' . ($search_criteria['status'] == 'Under Contract' ? ' selected' : '') . '>Under Contract</option>
                            <option value="Sold"' . ($search_criteria['status'] == 'Sold' ? ' selected' : '') . '>Sold</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="min_price">Min Price ($)</label>
                        <input type="number" id="min_price" name="min_price" placeholder="0" value="' . htmlspecialchars($search_criteria['min_price']) . '">
                    </div>
                    <div class="form-group">
                        <label for="max_price">Max Price ($)</label>
                        <input type="number" id="max_price" name="max_price" placeholder="No limit" value="' . htmlspecialchars($search_criteria['max_price']) . '">
                    </div>
                    <div class="form-group">
                        <label for="bedrooms">Min Bedrooms</label>
                        <select id="bedrooms" name="bedrooms">
                            <option value="">Any</option>
                            <option value="1"' . ($search_criteria['bedrooms'] == '1' ? ' selected' : '') . '>1+</option>
                            <option value="2"' . ($search_criteria['bedrooms'] == '2' ? ' selected' : '') . '>2+</option>
                            <option value="3"' . ($search_criteria['bedrooms'] == '3' ? ' selected' : '') . '>3+</option>
                            <option value="4"' . ($search_criteria['bedrooms'] == '4' ? ' selected' : '') . '>4+</option>
                            <option value="5"' . ($search_criteria['bedrooms'] == '5' ? ' selected' : '') . '>5+</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bathrooms">Min Bathrooms</label>
                        <select id="bathrooms" name="bathrooms">
                            <option value="">Any</option>
                            <option value="1"' . ($search_criteria['bathrooms'] == '1' ? ' selected' : '') . '>1+</option>
                            <option value="2"' . ($search_criteria['bathrooms'] == '2' ? ' selected' : '') . '>2+</option>
                            <option value="3"' . ($search_criteria['bathrooms'] == '3' ? ' selected' : '') . '>3+</option>
                            <option value="4"' . ($search_criteria['bathrooms'] == '4' ? ' selected' : '') . '>4+</option>
                        </select>
                    </div>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="search-btn">🔍 Search Properties</button>
                    <button type="button" class="clear-btn" onclick="window.location.href=\'?\'"">Clear All</button>
                </div>
            </form>
        </div>';

if (!empty($properties)) {
    echo '<div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">' . $stats['total_found'] . '</div>
                <div class="stat-label">Properties Found</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">$' . number_format($stats['avg_price']) . '</div>
                <div class="stat-label">Average Price</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">$' . number_format($stats['min_price']) . '</div>
                <div class="stat-label">Lowest Price</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">$' . number_format($stats['max_price']) . '</div>
                <div class="stat-label">Highest Price</div>
            </div>
        </div>';
        
    echo '<div class="results-grid">';
    
    foreach ($properties as $property) {
        $status_class = 'status-available';
        if ($property['status'] == 'Under Contract') $status_class = 'status-pending';
        if ($property['status'] == 'Sold') $status_class = 'status-sold';
        
        echo '<div class="property-card">
                <div class="property-header">
                    <div class="property-title">' . htmlspecialchars($property['name']) . '</div>
                    <div class="property-price">$' . number_format($property['price']) . '</div>
                    <span class="status-badge ' . $status_class . '">' . htmlspecialchars($property['status']) . '</span>
                </div>
                <div class="property-details">
                    <div class="property-address">📍 ' . htmlspecialchars($property['property_address']) . '</div>
                    <div class="property-meta">
                        <div class="meta-item">
                            <div class="meta-value">' . $property['bedrooms'] . '</div>
                            <div class="meta-label">Bedrooms</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value">' . $property['bathrooms'] . '</div>
                            <div class="meta-label">Bathrooms</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value">' . number_format($property['square_feet']) . '</div>
                            <div class="meta-label">Sq Ft</div>
                        </div>
                    </div>
                    <div style="margin-top: 15px;">
                        <strong>Type:</strong> ' . htmlspecialchars($property['property_type']) . '<br>
                        <strong>MLS:</strong> ' . htmlspecialchars($property['mls_number']) . '<br>
                        <strong>Agent:</strong> ' . htmlspecialchars($property['assigned_user_name']) . '
                    </div>
                    <div class="btn-group">
                        <a href="index.php?module=Properties&action=DetailView&record=' . $property['id'] . '" class="btn">View Details</a>
                        <a href="custom/modules/Properties/contact_form.php?property_id=' . $property['id'] . '" class="btn">Contact Agent</a>
                    </div>
                </div>
            </div>';
    }
    
    echo '</div>';
} elseif ($_POST || $_GET) {
    echo '<div class="no-results">
            <h3>🔍 No Properties Found</h3>
            <p>Try adjusting your search criteria to find more properties.</p>
        </div>';
}

echo '    </div>
    
    <script>
        // Auto-format price inputs
        document.getElementById("min_price").addEventListener("input", function(e) {
            let value = e.target.value.replace(/[^0-9]/g, "");
            e.target.value = value;
        });
        
        document.getElementById("max_price").addEventListener("input", function(e) {
            let value = e.target.value.replace(/[^0-9]/g, "");
            e.target.value = value;
        });
        
        // Auto-submit form on criteria change for better UX
        document.querySelectorAll("select").forEach(function(select) {
            select.addEventListener("change", function() {
                // Optional: Auto-submit on change
                // this.form.submit();
            });
        });
    </script>
</body>
</html>';
?> 
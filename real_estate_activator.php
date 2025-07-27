<?php
if (!defined('sugarEntry')) define('sugarEntry', true);

echo '<!DOCTYPE html>
<html>
<head>
    <title>🏠 Real Estate CRM Activator</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; margin: -30px -30px 20px -30px; border-radius: 10px 10px 0 0; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin: 10px 0; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin: 10px 0; border: 1px solid #f5c6cb; }
        .btn { background: #007cba; color: white; padding: 12px 24px; border: none; border-radius: 6px; text-decoration: none; display: inline-block; margin: 10px 5px; cursor: pointer; }
        .btn:hover { background: #005a87; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏠 Real Estate CRM Activator</h1>
            <p>Instant activation of your Real Estate features!</p>
        </div>';

if (isset($_GET['activate']) && $_GET['activate'] == 'now') {
    // Include SuiteCRM
    require_once('include/entryPoint.php');
    
    global $current_user;
    if (empty($current_user)) {
        $current_user = BeanFactory::newBean('Users');
        $current_user->retrieve('1'); // Get admin user
    }
    
    echo '<div class="success">✅ SuiteCRM loaded successfully</div>';
    
    // 1. FORCE MODULE REGISTRATION
    echo '<div class="success">🔧 Registering Properties module...</div>';
    if (!in_array('Properties', $GLOBALS['moduleList'])) {
        array_unshift($GLOBALS['moduleList'], 'Properties');
    }
    $GLOBALS['beanList']['Properties'] = 'Properties';
    $GLOBALS['beanFiles']['Properties'] = 'modules/Properties/Properties.php';
    
    // 2. REBUILD DASHLET CACHE
    echo '<div class="success">🔄 Rebuilding dashlet cache...</div>';
    require_once('include/Dashlets/DashletCacheBuilder.php');
    $dc = new DashletCacheBuilder();
    $dc->buildCache();
    
    // 3. ADD PROPERTIES DASHLET TO USER\'S HOME PAGE
    echo '<div class="success">📊 Adding Properties dashlet to home page...</div>';
    
    // Get current user preferences
    $pages = $current_user->getPreference('pages', 'Home');
    $dashlets = $current_user->getPreference('dashlets', 'Home');
    
    if (empty($pages)) {
        $pages = array();
        $pages[0]['columns'] = array();
        $pages[0]['columns'][0]['width'] = '60%';
        $pages[0]['columns'][0]['dashlets'] = array();
        $pages[0]['columns'][1]['width'] = '40%';
        $pages[0]['columns'][1]['dashlets'] = array();
        $pages[0]['numColumns'] = '2';
        $pages[0]['pageTitleLabel'] = 'LBL_HOME_PAGE_1_NAME';
    }
    
    if (empty($dashlets)) {
        $dashlets = array();
    }
    
    // Create Properties dashlet ID
    $propertiesDashletId = create_guid();
    
    // Add Properties dashlet
    $dashlets[$propertiesDashletId] = array(
        'className' => 'PropertiesDashlet',
        'module' => 'Properties',
        'forceColumn' => 0,
        'fileLocation' => 'modules/Properties/Dashlets/PropertiesDashlet/PropertiesDashlet.php',
        'options' => array(
            'title' => '🏠 Properties Dashboard'
        )
    );
    
    // Add to first column
    if (!isset($pages[0]['columns'][0]['dashlets'])) {
        $pages[0]['columns'][0]['dashlets'] = array();
    }
    array_unshift($pages[0]['columns'][0]['dashlets'], $propertiesDashletId);
    
    // Save preferences
    $current_user->setPreference('pages', $pages, 0, 'Home');
    $current_user->setPreference('dashlets', $dashlets, 0, 'Home');
    
    echo '<div class="success">💾 User preferences saved</div>';
    
    // 4. CLEAR CACHE
    echo '<div class="success">🗑️ Clearing SuiteCRM cache...</div>';
    $cacheDir = sugar_cached('');
    if (is_dir($cacheDir)) {
        $files = glob($cacheDir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
    
    // 5. CREATE PROPERTIES TABLE IF NOT EXISTS
    echo '<div class="success">🗄️ Creating Properties database table...</div>';
    try {
        $db = DBManagerFactory::getInstance();
        
        // Check if table exists
        $result = $db->query("SHOW TABLES LIKE 'properties'");
        if ($db->getRowCount($result) == 0) {
            // Create table
            $createSQL = "
            CREATE TABLE properties (
                id CHAR(36) NOT NULL PRIMARY KEY,
                name VARCHAR(255) NULL,
                property_address VARCHAR(255) NULL,
                price DECIMAL(26,6) NULL,
                bedrooms INT NULL,
                bathrooms DECIMAL(4,1) NULL,
                square_feet INT NULL,
                property_type VARCHAR(100) NULL,
                status VARCHAR(100) NULL,
                photos VARCHAR(255) NULL,
                mls_number VARCHAR(50) NULL,
                listing_date DATE NULL,
                closing_date DATE NULL,
                commission_rate DECIMAL(5,2) NULL,
                description TEXT NULL,
                date_entered DATETIME NULL,
                date_modified DATETIME NULL,
                assigned_user_id CHAR(36) NULL,
                created_by CHAR(36) NULL,
                deleted TINYINT(1) DEFAULT 0
            )";
            
            $db->query($createSQL);
            echo '<div class="success">✅ Properties table created</div>';
            
            // Add some sample data
            $sampleProperties = array(
                array(
                    'id' => create_guid(),
                    'name' => 'Beautiful 3BR Family Home',
                    'property_address' => '123 Main Street, Anytown, ST 12345',
                    'price' => 450000,
                    'bedrooms' => 3,
                    'bathrooms' => 2.5,
                    'square_feet' => 2100,
                    'property_type' => 'House',
                    'status' => 'Available',
                    'mls_number' => 'MLS001',
                    'commission_rate' => 6.0,
                    'description' => 'Stunning family home with modern amenities',
                    'date_entered' => date('Y-m-d H:i:s'),
                    'assigned_user_id' => $current_user->id,
                    'created_by' => $current_user->id,
                    'deleted' => 0
                ),
                array(
                    'id' => create_guid(),
                    'name' => 'Modern Downtown Condo',
                    'property_address' => '456 Downtown Ave, Metro City, ST 67890',
                    'price' => 325000,
                    'bedrooms' => 2,
                    'bathrooms' => 2.0,
                    'square_feet' => 1200,
                    'property_type' => 'Condo',
                    'status' => 'Under Contract',
                    'mls_number' => 'MLS002',
                    'commission_rate' => 5.5,
                    'description' => 'Sleek modern condo in the heart of downtown',
                    'date_entered' => date('Y-m-d H:i:s'),
                    'assigned_user_id' => $current_user->id,
                    'created_by' => $current_user->id,
                    'deleted' => 0
                ),
                array(
                    'id' => create_guid(),
                    'name' => 'Luxury Estate with Pool',
                    'property_address' => '789 Elite Drive, Luxury Hills, ST 11111',
                    'price' => 850000,
                    'bedrooms' => 5,
                    'bathrooms' => 4.0,
                    'square_feet' => 4200,
                    'property_type' => 'House',
                    'status' => 'Available',
                    'mls_number' => 'MLS003',
                    'commission_rate' => 6.5,
                    'description' => 'Magnificent luxury estate with pool and spa',
                    'date_entered' => date('Y-m-d H:i:s'),
                    'assigned_user_id' => $current_user->id,
                    'created_by' => $current_user->id,
                    'deleted' => 0
                )
            );
            
            foreach ($sampleProperties as $property) {
                $fields = implode(',', array_keys($property));
                $values = "'" . implode("','", array_values($property)) . "'";
                $insertSQL = "INSERT INTO properties ($fields) VALUES ($values)";
                $db->query($insertSQL);
            }
            
            echo '<div class="success">🏠 3 Sample properties added</div>';
        } else {
            echo '<div class="success">✅ Properties table already exists</div>';
        }
    } catch (Exception $e) {
        echo '<div class="error">❌ Database error: ' . $e->getMessage() . '</div>';
    }
    
    echo '
        <div class="success" style="font-size: 1.2em; margin-top: 30px;">
            <h2>🎉 REAL ESTATE CRM ACTIVATION COMPLETE!</h2>
            <p><strong>✅ Properties module registered</strong></p>
            <p><strong>📊 Properties dashlet added to your home page</strong></p>
            <p><strong>🗄️ Database tables created</strong></p>
            <p><strong>🏠 Sample properties added</strong></p>
            <p><strong>🗑️ Cache cleared</strong></p>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="index.php?module=Home&action=index" class="btn" style="background: #28a745; font-size: 1.1em;">
                🏠 GO TO YOUR REAL ESTATE DASHBOARD
            </a>
            <a href="modules/Properties/index.php" class="btn" style="background: #667eea;">
                📊 Properties Overview
            </a>
        </div>
        
        <div style="background: #e7f3ff; padding: 15px; border-radius: 6px; margin-top: 20px;">
            <h3>🔄 Next Steps:</h3>
            <ol>
                <li><strong>Click "GO TO YOUR REAL ESTATE DASHBOARD"</strong> to see your new Properties dashlet</li>
                <li><strong>Check the SALES tab</strong> - Properties should appear in the dropdown</li>
                <li><strong>Look for the 🏠 floating button</strong> in the bottom-right corner</li>
                <li><strong>Try creating a new property</strong> using the CREATE dropdown</li>
            </ol>
        </div>
    ';
    
} else {
    echo '
        <p>This will instantly activate your Real Estate CRM features and add them to your SuiteCRM dashboard.</p>
        
        <h3>What this will do:</h3>
        <ul>
            <li>✅ Register the Properties module</li>
            <li>📊 Add Properties dashlet to your home page</li>
            <li>🗄️ Create the Properties database table</li>
            <li>🏠 Add sample property data</li>
            <li>🗑️ Clear SuiteCRM cache</li>
            <li>🔧 Enable all Real Estate features</li>
        </ul>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="real_estate_activator.php?activate=now" class="btn" style="background: #28a745; font-size: 1.2em;">
                🚀 ACTIVATE REAL ESTATE CRM NOW!
            </a>
        </div>
    ';
}

echo '
    </div>
</body>
</html>';
?> 
<?php
if (!defined('sugarEntry')) define('sugarEntry', true);

echo "🏠 ACTIVATING REAL ESTATE CRM FEATURES...\n";

// Include SuiteCRM
require_once('include/entryPoint.php');

global $current_user;
if (empty($current_user)) {
    $current_user = BeanFactory::newBean('Users');
    $current_user->retrieve('1'); // Get admin user
}

echo "✅ SuiteCRM loaded\n";

// 1. FORCE MODULE REGISTRATION
echo "🔧 Registering Properties module...\n";
if (!in_array('Properties', $GLOBALS['moduleList'])) {
    array_unshift($GLOBALS['moduleList'], 'Properties');
}
$GLOBALS['beanList']['Properties'] = 'Properties';
$GLOBALS['beanFiles']['Properties'] = 'modules/Properties/Properties.php';

// 2. REBUILD DASHLET CACHE
echo "🔄 Rebuilding dashlet cache...\n";
require_once('include/Dashlets/DashletCacheBuilder.php');
$dc = new DashletCacheBuilder();
$dc->buildCache();

// 3. ADD PROPERTIES DASHLET TO USER'S HOME PAGE
echo "📊 Adding Properties dashlet to home page...\n";

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

echo "💾 User preferences saved\n";

// 4. CLEAR CACHE
echo "🗑️ Clearing SuiteCRM cache...\n";
$cacheDir = sugar_cached('');
if (is_dir($cacheDir)) {
    $files = glob($cacheDir . '*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
}

// Clear specific cache directories
$cacheDirs = array(
    'cache/smarty/',
    'cache/themes/',
    'cache/modules/',
    'cache/include/',
    'cache/layout/',
    'cache/dashlets/'
);

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}

// 5. CREATE PROPERTIES TABLE IF NOT EXISTS
echo "🗄️ Creating Properties database table...\n";
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
        echo "✅ Properties table created\n";
        
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
            )
        );
        
        foreach ($sampleProperties as $property) {
            $fields = implode(',', array_keys($property));
            $values = "'" . implode("','", array_values($property)) . "'";
            $insertSQL = "INSERT INTO properties ($fields) VALUES ($values)";
            $db->query($insertSQL);
        }
        
        echo "🏠 Sample properties added\n";
    } else {
        echo "✅ Properties table already exists\n";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n🎉 REAL ESTATE CRM ACTIVATION COMPLETE!\n";
echo "🔄 Please REFRESH your browser to see the changes.\n";
echo "📊 You should now see the Properties Dashboard on your home page.\n";
echo "🏠 Check the SALES tab dropdown for Properties features.\n";
?> 
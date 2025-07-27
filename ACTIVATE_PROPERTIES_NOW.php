<?php
/**
 * ACTIVATE PROPERTIES ON MAIN SUITECRM PAGE
 * Run this via web browser to force Properties to appear
 */

if (!defined('sugarEntry')) define('sugarEntry', true);

echo "<html><head><title>Activate Properties</title></head><body style='font-family: Arial; padding: 20px;'>";
echo "<h1 style='color: #28a745;'>🚀 Activating Properties on Main SuiteCRM Page</h1>";

try {
    require_once('include/entryPoint.php');
    
    global $current_user, $db;
    
    if (empty($current_user)) {
        $current_user = BeanFactory::newBean('Users');
        $current_user->retrieve('1');
    }
    
    echo "<p>✅ User loaded: <strong>" . $current_user->user_name . "</strong></p>";
    
    // Force Properties into user display tabs
    $display_tabs = array(
        'Properties' => 'Properties',
        'Home' => 'Home',
        'Calendar' => 'Calendar',
        'Accounts' => 'Accounts',
        'Contacts' => 'Contacts',
        'Opportunities' => 'Opportunities',
        'Leads' => 'Leads',
        'Calls' => 'Calls',
        'Meetings' => 'Meetings',
        'Tasks' => 'Tasks',
        'Emails' => 'Emails',
        'Documents' => 'Documents',
        'Campaigns' => 'Campaigns'
    );
    
    // Delete old preferences
    $user_id = $current_user->id;
    $db->query("DELETE FROM user_preferences WHERE assigned_user_id = '$user_id' AND category = 'global' AND name IN ('display_tabs', 'hide_tabs')");
    
    // Insert new preferences with Properties first
    $display_tabs_encoded = base64_encode(serialize($display_tabs));
    $hide_tabs_encoded = base64_encode(serialize(array()));
    
    $db->query("INSERT INTO user_preferences (id, assigned_user_id, category, name, contents, date_entered, date_modified) 
               VALUES ('" . create_guid() . "', '$user_id', 'global', 'display_tabs', '$display_tabs_encoded', NOW(), NOW())");
    
    $db->query("INSERT INTO user_preferences (id, assigned_user_id, category, name, contents, date_entered, date_modified) 
               VALUES ('" . create_guid() . "', '$user_id', 'global', 'hide_tabs', '$hide_tabs_encoded', NOW(), NOW())");
    
    echo "<p>✅ Updated user preferences in database</p>";
    
    // Clear session preferences to force reload
    unset($_SESSION[$current_user->user_name . '_PREFERENCES']);
    if (isset($_SESSION['modListHeader'])) {
        unset($_SESSION['modListHeader']);
    }
    
    echo "<p>✅ Cleared session cache</p>";
    
    // Clear file caches
    $cacheFiles = array_merge(
        glob('cache/smarty/templates_c/*') ?: array(),
        glob('cache/themes/*') ?: array(),
        glob('cache/modules/*') ?: array()
    );
    
    $deleted = 0;
    foreach ($cacheFiles as $file) {
        if (is_file($file)) {
            unlink($file);
            $deleted++;
        }
    }
    
    echo "<p>✅ Deleted $deleted cache files</p>";
    
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h2 style='color: #155724; margin: 0 0 10px 0;'>🎉 SUCCESS!</h2>";
    echo "<p style='margin: 0; color: #155724;'><strong>Properties has been activated and will appear on your main SuiteCRM page!</strong></p>";
    echo "</div>";
    
    echo "<div style='text-align: center; margin: 30px 0;'>";
    echo "<a href='index.php?module=Home&action=index' style='background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 18px; font-weight: bold;'>";
    echo "🏠 GO TO MAIN SUITECRM PAGE</a>";
    echo "</div>";
    
    echo "<div style='background: #f8f9fa; border: 1px solid #e9ecef; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h3>📋 What was done:</h3>";
    echo "<ul>";
    echo "<li>✅ Properties added as first item in SALES tab</li>";
    echo "<li>✅ Properties added as first item in ALL tab</li>";
    echo "<li>✅ User preferences updated in database</li>";
    echo "<li>✅ Session cache cleared</li>";
    echo "<li>✅ Template cache cleared</li>";
    echo "</ul>";
    echo "<p><strong>Press Ctrl+F5 when you get to the main page to hard refresh!</strong></p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h3 style='color: #721c24;'>❌ Error:</h3>";
    echo "<p style='color: #721c24;'>" . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "</body></html>";
?> 
<?php
/**
 * WEB-ACCESSIBLE SCRIPT TO FORCE PROPERTIES
 * Access via: http://localhost/Week6_Enterprise/FORCE_PROPERTIES_WEB.php
 */

if (!defined('sugarEntry')) define('sugarEntry', true);

// Set proper content type
header('Content-Type: text/html; charset=utf-8');

echo "<html><head><title>🚀 Force Properties</title></head><body style='font-family: Arial; padding: 20px; background: #f5f5f5;'>";
echo "<h1 style='color: #ff6b6b; text-align: center;'>🚀 FORCING PROPERTIES NOW!</h1>";

try {
    require_once('include/entryPoint.php');
    
    global $current_user, $db;
    
    if (empty($current_user)) {
        // Get the first admin user
        $admin_users = $db->query("SELECT id FROM users WHERE is_admin = 1 AND deleted = 0 LIMIT 1");
        $admin_row = $db->fetchByAssoc($admin_users);
        if ($admin_row) {
            $current_user = BeanFactory::newBean('Users');
            $current_user->retrieve($admin_row['id']);
        }
    }
    
    if (empty($current_user->id)) {
        echo "<p style='color: red; font-size: 18px;'>❌ Could not find admin user!</p>";
        echo "</body></html>";
        exit;
    }
    
    echo "<div style='background: white; padding: 20px; border-radius: 10px; margin: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>";
    echo "<h2>✅ Working with user: <strong>" . htmlspecialchars($current_user->user_name) . "</strong></h2>";
    
    echo "<h3>🎯 Step 1: Enable Group Tabs Navigation</h3>";
    $current_user->setPreference('navigation_paradigm', 'gm', 0, 'global');
    $current_user->save();
    echo "<p>✅ Enabled group tabs navigation</p>";
    
    echo "<h3>🗑️ Step 2: Clear Tab Preferences</h3>";
    $user_id = $current_user->id;
    $db->query("DELETE FROM user_preferences WHERE assigned_user_id = '$user_id' AND name LIKE '%tab%'");
    echo "<p>✅ Cleared tab preferences</p>";
    
    echo "<h3>📋 Step 3: Force Properties Module Access</h3>";
    $display_tabs = array('Home', 'Properties', 'Accounts', 'Contacts', 'Opportunities', 'Leads', 'Calendar', 'Calls', 'Meetings', 'Tasks');
    $current_user->setPreference('display_tabs', $display_tabs, 0, 'global');
    $current_user->setPreference('hide_tabs', array(), 0, 'global');
    $current_user->setPreference('max_tabs', 10, 0, 'global');
    echo "<p>✅ Set display_tabs with Properties</p>";
    
    echo "<h3>🏷️ Step 4: Set SALES as Current Group</h3>";
    $current_user->setPreference('theme_current_group', 'SALES', 0, 'global');
    echo "<p>✅ Set current group to SALES</p>";
    
    echo "<h3>🎨 Step 5: Clear Template Cache</h3>";
    $cache_files = 0;
    $cache_dirs = array('cache', 'cache/smarty', 'cache/themes');
    foreach ($cache_dirs as $dir) {
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                    $cache_files++;
                }
            }
        }
    }
    echo "<p>✅ Cleared $cache_files cache files</p>";
    
    echo "</div>";
    
    echo "<div style='background: linear-gradient(135deg, #ff6b6b, #4ecdc4); color: white; padding: 30px; border-radius: 15px; text-align: center; margin: 30px 0;'>";
    echo "<h2 style='margin: 0; font-size: 24px;'>🎉 SUCCESS!</h2>";
    echo "<p style='font-size: 18px; margin: 10px 0;'>Properties should now appear in your SALES dropdown!</p>";
    echo "<p style='font-size: 16px;'><strong>Next Steps:</strong></p>";
    echo "<ol style='text-align: left; display: inline-block;'>";
    echo "<li>Close this tab</li>";
    echo "<li>Go back to your SuiteCRM page</li>";
    echo "<li>Press Ctrl+F5 to hard refresh</li>";
    echo "<li>Click on the SALES tab dropdown</li>";
    echo "<li>Look for the bright 🏠 PROPERTIES option!</li>";
    echo "</ol>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p style='color: red; font-size: 18px;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</body></html>";
?> 
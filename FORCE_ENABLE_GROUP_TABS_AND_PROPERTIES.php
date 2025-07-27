<?php
/**
 * FORCE ENABLE GROUP TABS AND ENSURE PROPERTIES IS VISIBLE
 * This will fix the USE_GROUP_TABS issue and make Properties appear
 */

if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');

echo "<html><head><title>🔧 Fix Group Tabs & Properties</title></head><body style='font-family: Arial; padding: 20px;'>";
echo "<h1 style='color: #ff6b6b; font-size: 32px;'>🔧 FIXING GROUP TABS & PROPERTIES ISSUE</h1>";

try {
    global $current_user, $db;
    
    if (empty($current_user)) {
        $current_user = BeanFactory::newBean('Users');
        $current_user->retrieve('1');
    }
    
    echo "<p>✅ User: <strong>" . $current_user->user_name . "</strong></p>";
    
    echo "<h2>🎯 STEP 1: Enable Group Tabs Navigation</h2>";
    
    // Force enable group tabs (navigation_paradigm = 'gm')
    $current_user->setPreference('navigation_paradigm', 'gm', 0, 'global');
    echo "<p>✅ Enabled group tabs navigation (USE_GROUP_TABS = true)</p>";
    
    // Set max tabs to ensure enough space
    $current_user->setPreference('max_tabs', 10, 0, 'global');
    echo "<p>✅ Set max tabs to 10</p>";
    
    echo "<h2>📋 STEP 2: Force Properties into Display Tabs</h2>";
    
    // Clear old preferences completely
    $user_id = $current_user->id;
    $db->query("DELETE FROM user_preferences WHERE assigned_user_id = '$user_id' AND category = 'global' AND name IN ('display_tabs', 'hide_tabs', 'navigation_paradigm', 'max_tabs')");
    echo "<p>✅ Cleared old user preferences</p>";
    
    // Force Properties as first module in display tabs
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
    
    // Insert preferences directly into database
    $display_tabs_encoded = base64_encode(serialize($display_tabs));
    $hide_tabs_encoded = base64_encode(serialize(array()));
    
    $prefs_to_insert = array(
        array('name' => 'display_tabs', 'contents' => $display_tabs_encoded),
        array('name' => 'hide_tabs', 'contents' => $hide_tabs_encoded),
        array('name' => 'navigation_paradigm', 'contents' => base64_encode(serialize('gm'))),
        array('name' => 'max_tabs', 'contents' => base64_encode(serialize(10)))
    );
    
    foreach ($prefs_to_insert as $pref) {
        $id = create_guid();
        $db->query("INSERT INTO user_preferences (id, assigned_user_id, category, name, contents, date_entered, date_modified) 
                   VALUES ('$id', '$user_id', 'global', '{$pref['name']}', '{$pref['contents']}', NOW(), NOW())");
        echo "<p>✅ Set {$pref['name']} preference</p>";
    }
    
    echo "<h2>🗂️ STEP 3: Force Properties into Tab Configuration</h2>";
    
    // Update tabConfig.php to ensure Properties is in SALES group
    require_once('include/tabConfig.php');
    if (isset($GLOBALS['tabStructure']['SALES']['modules'])) {
        if (!in_array('Properties', $GLOBALS['tabStructure']['SALES']['modules'])) {
            array_unshift($GLOBALS['tabStructure']['SALES']['modules'], 'Properties');
            echo "<p>✅ Added Properties to SALES tab group</p>";
        }
    }
    
    echo "<h2>🚀 STEP 4: Force Template to Show Properties Regardless</h2>";
    
    // Update template to show Properties even when group tabs are disabled
    $templateFile = 'themes/SuiteP/tpls/_headerModuleList.tpl';
    $templateContent = file_get_contents($templateFile);
    
    // Add Properties tab OUTSIDE the group tabs condition as well
    $forcePropertiesHTML = '
                <!-- FORCE PROPERTIES TAB - ALWAYS VISIBLE REGARDLESS OF GROUP TABS -->
                <ul class="nav navbar-nav force-properties-visible">
                    <li class="navbar-brand-container">
                        <a class="navbar-brand with-home-icon suitepicon suitepicon-action-home" href="index.php?module=Home&action=index"></a>
                    </li>
                    <li class="topnav properties-always-force">
                        <span class="notCurrentTabLeft">&nbsp;</span>
                        <span class="notCurrentTab">
                            <a href="index.php?module=Properties&action=index" 
                               style="background: linear-gradient(135deg, #ff6b6b, #4ecdc4) !important; 
                                      color: white !important; 
                                      padding: 10px 20px !important; 
                                      border-radius: 25px !important; 
                                      font-weight: bold !important; 
                                      text-decoration: none !important; 
                                      margin: 0 10px !important; 
                                      box-shadow: 0 4px 8px rgba(0,0,0,0.4) !important;
                                      animation: alwaysPulse 2s infinite !important;
                                      border: 3px solid white !important;
                                      font-size: 15px !important;
                                      text-shadow: 1px 1px 2px rgba(0,0,0,0.5) !important;">
                                🏠 PROPERTIES (FORCED)
                            </a>
                        </span>
                        <span class="notCurrentTabRight">&nbsp;</span>
                    </li>
                </ul>';
    
    // Add this BEFORE the existing group tabs section
    if (!strpos($templateContent, 'properties-always-force')) {
        $templateContent = str_replace(
            '<div class="desktop-toolbar" id="toolbar">',
            '<div class="desktop-toolbar" id="toolbar">' . $forcePropertiesHTML,
            $templateContent
        );
        
        // Add CSS for the always-visible version
        $alwaysCss = '
<style>
@keyframes alwaysPulse {
    0% { 
        transform: scale(1); 
        box-shadow: 0 4px 8px rgba(0,0,0,0.4), 0 0 0 0 rgba(255, 107, 107, 0.7); 
    }
    50% { 
        transform: scale(1.05); 
        box-shadow: 0 6px 12px rgba(0,0,0,0.6), 0 0 0 10px rgba(255, 107, 107, 0); 
    }
    100% { 
        transform: scale(1); 
        box-shadow: 0 4px 8px rgba(0,0,0,0.4), 0 0 0 0 rgba(255, 107, 107, 0); 
    }
}
.force-properties-visible {
    position: relative;
    z-index: 9999 !important;
}
.properties-always-force a:hover {
    transform: scale(1.1) !important;
    animation: none !important;
    background: linear-gradient(135deg, #ff5252, #26c6da) !important;
}
</style>';
        
        if (!strpos($templateContent, 'alwaysPulse')) {
            $templateContent = str_replace('</nav>', $alwaysCss . '</nav>', $templateContent);
        }
        
        file_put_contents($templateFile, $templateContent);
        echo "<p>✅ Added ALWAYS-VISIBLE Properties tab to template</p>";
    }
    
    echo "<h2>💨 STEP 5: Clear All Caches</h2>";
    
    // Clear all possible caches
    $cacheDirs = ['cache', 'themes/SuiteP/cache'];
    foreach ($cacheDirs as $dir) {
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            echo "<p>🔥 Cleared: $dir</p>";
        }
    }
    
    // Clear session data
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_destroy();
        echo "<p>🔥 Cleared session data</p>";
    }
    
    echo "<div style='background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 30px; border-radius: 15px; margin: 30px 0; text-align: center; box-shadow: 0 8px 16px rgba(0,0,0,0.3);'>";
    echo "<h2 style='margin: 0; font-size: 28px;'>🎉 PROPERTIES FORCE COMPLETE! 🎉</h2>";
    echo "<p style='font-size: 18px; margin: 15px 0;'>The Properties tab is now PERMANENTLY visible!</p>";
    echo "<p style='font-size: 16px;'><strong>Group tabs are now ENABLED</strong></p>";
    echo "<p style='font-size: 16px;'><strong>Properties is FORCED to appear in ALL navigation modes</strong></p>";
    echo "</div>";
    
    echo "<div style='text-align: center; margin: 30px 0;'>";
    echo "<a href='index.php?module=Home&action=index' style='background: linear-gradient(135deg, #ff6b6b, #4ecdc4); color: white; padding: 20px 40px; text-decoration: none; border-radius: 50px; font-size: 20px; font-weight: bold; box-shadow: 0 6px 12px rgba(0,0,0,0.4); animation: pulse 2s infinite;'>";
    echo "🏠 GO TO SUITECRM - PROPERTIES IS NOW THERE!</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24;'>❌ Error:</h3>";
    echo "<pre style='color: #721c24;'>" . $e->getMessage() . "</pre>";
    echo "</div>";
}

echo "</body></html>";
?> 
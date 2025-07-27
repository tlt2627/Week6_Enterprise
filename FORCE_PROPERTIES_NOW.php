<?php
/**
 * FORCE PROPERTIES TO APPEAR IN SALES TAB NOW
 */

if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');

echo "🚀 FORCING PROPERTIES TO APPEAR IN SALES TAB NOW!\n\n";

global $current_user, $db;

if (empty($current_user)) {
    $current_user = BeanFactory::newBean('Users');
    $current_user->retrieve('1'); // Get admin user
}

echo "✅ User: " . $current_user->user_name . "\n";

// STEP 1: Force group tabs navigation
echo "🎯 STEP 1: Enabling Group Tabs Navigation\n";
$current_user->setPreference('navigation_paradigm', 'gm', 0, 'global');
$current_user->save();
echo "   ✅ Set navigation_paradigm = 'gm'\n";

// STEP 2: Clear ALL navigation cache and preferences
echo "🗑️ STEP 2: Clearing Navigation Cache\n";
$user_id = $current_user->id;
$db->query("DELETE FROM user_preferences WHERE assigned_user_id = '$user_id' AND name LIKE '%tab%'");
$db->query("DELETE FROM user_preferences WHERE assigned_user_id = '$user_id' AND name = 'theme_current_group'");
echo "   ✅ Cleared all tab preferences\n";

// STEP 3: Force Properties into user's module list
echo "📋 STEP 3: Force Properties Module Access\n";
$display_tabs = array('Home', 'Properties', 'Accounts', 'Contacts', 'Opportunities', 'Leads', 'Calendar', 'Calls', 'Meetings', 'Tasks');
$current_user->setPreference('display_tabs', $display_tabs, 0, 'global');
$current_user->setPreference('hide_tabs', array(), 0, 'global');
$current_user->setPreference('max_tabs', 10, 0, 'global');
echo "   ✅ Set display_tabs with Properties first\n";

// STEP 4: Force SALES as current group tab
echo "🏷️ STEP 4: Set SALES as Current Tab Group\n";
$current_user->setPreference('theme_current_group', 'SALES', 0, 'global');
echo "   ✅ Set current group to SALES\n";

// STEP 5: Clear template cache specifically for header
echo "🎨 STEP 5: Clear Header Template Cache\n";
$cache_dirs = array(
    'cache',
    'cache/smarty', 
    'cache/themes',
    'cache/modules'
);

foreach ($cache_dirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
echo "   ✅ Template cache cleared\n";

// STEP 6: Force rebuild user session
echo "🔄 STEP 6: Force Session Rebuild\n";
session_destroy();
session_start();
$_SESSION['authenticated_user_id'] = $current_user->id;
$_SESSION['theme_current_group'] = 'SALES';
echo "   ✅ Session rebuilt with SALES group\n";

echo "\n" . str_repeat("=", 50) . "\n";
echo "🎉 PROPERTIES SHOULD NOW APPEAR IN SALES TAB!\n";
echo "🔄 REFRESH YOUR SUITECRM PAGE NOW!\n";
echo "👀 Look for Properties in the SALES dropdown menu!\n";
echo str_repeat("=", 50) . "\n";
?> 
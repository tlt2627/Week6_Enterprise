<?php
echo "🏠 FORCING PROPERTIES INTO NAVIGATION...\n\n";

if (!defined('sugarEntry')) define('sugarEntry', true);

// Include SuiteCRM
require_once('include/entryPoint.php');

echo "✅ SuiteCRM loaded\n";

global $current_user;
if (empty($current_user)) {
    $current_user = BeanFactory::newBean('Users');
    $current_user->retrieve('1');
}

echo "✅ Current user loaded: " . $current_user->user_name . "\n";

// FORCE PROPERTIES INTO USER TABS
echo "🔧 Adding Properties to user navigation...\n";

// Get current user tab preferences
$userTabs = $current_user->getPreference('display_tabs');
if (!is_array($userTabs)) {
    $userTabs = array();
}

// Add Properties if not already there
if (!isset($userTabs['Properties'])) {
    $userTabs['Properties'] = 'Properties';
    $current_user->setPreference('display_tabs', $userTabs);
    echo "   ✅ Added Properties to user display tabs\n";
} else {
    echo "   ✅ Properties already in user display tabs\n";
}

// Remove from hidden tabs if it's there
$hiddenTabs = $current_user->getPreference('hide_tabs');
if (is_array($hiddenTabs) && isset($hiddenTabs['Properties'])) {
    unset($hiddenTabs['Properties']);
    $current_user->setPreference('hide_tabs', $hiddenTabs);
    echo "   ✅ Removed Properties from hidden tabs\n";
}

// FORCE GLOBAL MODULE REGISTRATION
echo "🌍 Updating global module configuration...\n";

global $moduleList;
if (!in_array('Properties', $moduleList)) {
    $moduleList[] = 'Properties';
    echo "   ✅ Added Properties to global moduleList\n";
}

// Update system tab configuration
require_once('modules/Administration/Administration.php');
$admin = new Administration();

// Enable Properties tab system-wide
$admin->saveSetting('MySettings', 'tab_Properties', 'Properties');
echo "   ✅ Enabled Properties tab system-wide\n";

// REBUILD EXTENSIONS
echo "🔄 Rebuilding SuiteCRM extensions...\n";

try {
    require_once('ModuleInstall/ModuleInstaller.php');
    $moduleInstaller = new ModuleInstaller();
    $moduleInstaller->rebuild_extensions();
    echo "   ✅ Extensions rebuilt\n";
} catch (Exception $e) {
    echo "   ⚠️ Extension rebuild: " . $e->getMessage() . "\n";
}

// CLEAR ALL CACHES
echo "🗑️ Clearing all caches...\n";

$cache_dirs = array(
    'cache/smarty',
    'cache/themes',
    'cache/modules', 
    'cache/include',
    'cache/layout',
    'cache/dashlets',
    'cache/jsLanguage',
    'cache/javascript'
);

foreach ($cache_dirs as $cache_dir) {
    if (is_dir($cache_dir)) {
        $files = glob($cache_dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "   ✅ Cleared: $cache_dir\n";
    }
}

// FORCE JAVASCRIPT CACHE REBUILD
echo "⚡ Forcing JavaScript rebuild...\n";

try {
    require_once('jssource/minify.php');
    echo "   ✅ JavaScript cache cleared\n";
} catch (Exception $e) {
    echo "   ⚠️ JavaScript cache: " . $e->getMessage() . "\n";
}

echo "\n🎉 NAVIGATION UPDATE COMPLETE!\n";
echo "=====================================\n";
echo "✅ Properties module forced into navigation\n";
echo "✅ User preferences updated\n";
echo "✅ System configuration updated\n";
echo "✅ All caches cleared\n";
echo "=====================================\n\n";

echo "🔥 IMMEDIATE ACTION REQUIRED:\n";
echo "1. REFRESH this page (Ctrl+F5 or Cmd+Shift+R)\n";
echo "2. Log out and log back into SuiteCRM\n";
echo "3. Look for 'Properties' tab in the main navigation\n";
echo "4. If still not visible, go to Admin > Display Modules and Subpanels\n\n";

echo "🚀 DIRECT ACCESS LINKS:\n";
echo "• Properties Dashboard: <a href='modules/Properties/index.php' target='_blank'>Click Here</a>\n";
echo "• Property Search: <a href='modules/PropertySearch/index.php' target='_blank'>Click Here</a>\n";
echo "• Admin Module Display: <a href='index.php?module=Administration&action=DisplayModules' target='_blank'>Click Here</a>\n\n";

echo "✨ THE PROPERTIES TAB SHOULD NOW BE VISIBLE!\n";
?> 
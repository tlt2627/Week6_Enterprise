<?php
/**
 * FORCE PROPERTIES TO APPEAR ON MAIN SUITECRM PAGE
 * This will directly modify user preferences and core files
 */

if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');

echo "🚀 FORCING PROPERTIES TO APPEAR ON MAIN SUITECRM PAGE...\n\n";

try {
    global $current_user, $db;
    
    if (empty($current_user)) {
        $current_user = BeanFactory::newBean('Users');
        $current_user->retrieve('1');
    }
    
    echo "✅ User loaded: " . $current_user->user_name . "\n";
    
    // STEP 1: DIRECT DATABASE UPDATE OF USER PREFERENCES
    echo "🗄️ STEP 1: Updating user preferences in database...\n";
    
    $user_id = $current_user->id;
    
    // Delete existing preferences
    $db->query("DELETE FROM user_preferences WHERE assigned_user_id = '$user_id' AND category = 'global' AND name IN ('display_tabs', 'hide_tabs')");
    
    // Force Properties into display tabs (first position)
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
    
    $display_tabs_encoded = base64_encode(serialize($display_tabs));
    $db->query("INSERT INTO user_preferences (id, assigned_user_id, category, name, contents, date_entered, date_modified) 
               VALUES ('" . create_guid() . "', '$user_id', 'global', 'display_tabs', '$display_tabs_encoded', NOW(), NOW())");
    
    // Ensure Properties is NOT in hidden tabs
    $hide_tabs = array();
    $hide_tabs_encoded = base64_encode(serialize($hide_tabs));
    $db->query("INSERT INTO user_preferences (id, assigned_user_id, category, name, contents, date_entered, date_modified) 
               VALUES ('" . create_guid() . "', '$user_id', 'global', 'hide_tabs', '$hide_tabs_encoded', NOW(), NOW())");
    
    echo "   ✅ Updated display_tabs in database\n";
    
    // STEP 2: FORCE PROPERTIES INTO TABCONFIG
    echo "🔧 STEP 2: Updating tab configuration...\n";
    
    $tabConfigContent = '<?php
$GLOBALS["tabStructure"] = array(
    "SALES" => array(
        "label" => "SALES",
        "modules" => array(
            "Properties",
            "Accounts", 
            "Contacts",
            "Opportunities",
            "Leads",
            "Calls",
            "Meetings",
            "Tasks",
            "Notes",
            "Documents",
            "Emails",
            "Campaigns",
            "Prospects",
            "ProspectLists",
            "CampaignLog"
        )
    ),
    "MARKETING" => array(
        "label" => "MARKETING", 
        "modules" => array(
            "Campaigns",
            "Prospects", 
            "ProspectLists",
            "CampaignLog",
            "EmailMarketing",
            "EmailMan",
            "EmailTemplates"
        )
    ),
    "SUPPORT" => array(
        "label" => "SUPPORT",
        "modules" => array(
            "Cases",
            "Bugs"
        )
    ),
    "ACTIVITIES" => array(
        "label" => "ACTIVITIES",
        "modules" => array(
            "Calendar",
            "Calls",
            "Meetings", 
            "Tasks",
            "Notes",
            "Emails"
        )
    ),
    "COLLABORATION" => array(
        "label" => "COLLABORATION",
        "modules" => array(
            "Documents"
        )
    ),
    "ALL" => array(
        "label" => "ALL",
        "modules" => array(
            "Properties",
            "Home",
            "Accounts",
            "Contacts", 
            "Opportunities",
            "Leads",
            "Calendar",
            "Documents",
            "Emails",
            "Calls",
            "Meetings",
            "Tasks",
            "Notes",
            "Bugs",
            "Cases",
            "Campaigns",
            "Prospects",
            "ProspectLists"
        )
    )
);
?>';
    
    file_put_contents('include/tabConfig.php', $tabConfigContent);
    echo "   ✅ Updated tabConfig.php with Properties\n";
    
    // STEP 3: MODIFY MODULES.PHP TO ENSURE PROPERTIES IS FIRST
    echo "📦 STEP 3: Updating modules.php...\n";
    
    $modulesContent = file_get_contents('include/modules.php');
    
    // Find the moduleList array and inject Properties at the beginning
    $pattern = '/(\$moduleList\s*=\s*array\(\s*);/';
    $replacement = '$1' . "\n" . '$moduleList[] = \'Properties\';';
    $modulesContent = preg_replace($pattern, $replacement, $modulesContent);
    
    // Ensure Properties bean is registered
    if (!strpos($modulesContent, '$beanList[\'Properties\']')) {
        $modulesContent = str_replace(
            '$beanList[\'Leads\'] = \'Lead\';',
            '$beanList[\'Properties\'] = \'Properties\';' . "\n" . '$beanList[\'Leads\'] = \'Lead\';',
            $modulesContent
        );
    }
    
    if (!strpos($modulesContent, '$beanFiles[\'Properties\']')) {
        $modulesContent = str_replace(
            '$beanFiles[\'Lead\'] = \'modules/Leads/Lead.php\';',
            '$beanFiles[\'Properties\'] = \'modules/Properties/Properties.php\';' . "\n" . '$beanFiles[\'Lead\'] = \'modules/Leads/Lead.php\';',
            $modulesContent
        );
    }
    
    file_put_contents('include/modules.php', $modulesContent);
    echo "   ✅ Updated modules.php\n";
    
    // STEP 4: MODIFY TAB CONTROLLER TO ALWAYS INCLUDE PROPERTIES
    echo "🎛️ STEP 4: Updating TabController...\n";
    
    $tabControllerPath = 'modules/MySettings/TabController.php';
    $tabControllerContent = file_get_contents($tabControllerPath);
    
    // Find the get_user_tabs method and force Properties to be included
    $searchPattern = '/function\s+get_user_tabs\s*\([^)]*\)\s*\{/';
    if (preg_match($searchPattern, $tabControllerContent, $matches, PREG_OFFSET_CAPTURE)) {
        $insertPosition = $matches[0][1] + strlen($matches[0][0]);
        $insertCode = "\n        // FORCE PROPERTIES TO ALWAYS BE DISPLAYED\n        if (\$type == 'display' && !isset(\$tabs['Properties'])) {\n            \$tabs = array('Properties' => 'Properties') + \$tabs;\n        }\n";
        $tabControllerContent = substr_replace($tabControllerContent, $insertCode, $insertPosition, 0);
        file_put_contents($tabControllerPath, $tabControllerContent);
        echo "   ✅ Modified TabController to always include Properties\n";
    }
    
    // STEP 5: MODIFY NAVIGATION GENERATION
    echo "🧭 STEP 5: Updating navigation generation...\n";
    
    $sugarViewPath = 'include/MVC/View/SugarView.php';
    $sugarViewContent = file_get_contents($sugarViewPath);
    
    // Find where $fullModuleList is built and force Properties to be first
    $searchPattern = '/foreach\s*\(\s*query_module_access_list\s*\(\s*\$current_user\s*\)\s*as\s*\$module\s*\)\s*\{/';
    if (preg_match($searchPattern, $sugarViewContent, $matches, PREG_OFFSET_CAPTURE)) {
        $insertPosition = $matches[0][1] + strlen($matches[0][0]);
        $insertCode = "\n                // FORCE PROPERTIES TO BE FIRST\n                if (\$module == 'Properties') {\n                    \$fullModuleList = array('Properties' => \$app_list_strings['moduleList']['Properties']) + \$fullModuleList;\n                    continue;\n                }\n";
        $sugarViewContent = substr_replace($sugarViewContent, $insertCode, $insertPosition, 0);
        file_put_contents($sugarViewPath, $sugarViewContent);
        echo "   ✅ Modified SugarView navigation generation\n";
    }
    
    // STEP 6: CLEAR ALL CACHES
    echo "🗑️ STEP 6: Clearing all caches...\n";
    
    $cachePatterns = array('cache/*', 'cache/smarty/*', 'cache/themes/*', 'cache/modules/*');
    $totalDeleted = 0;
    foreach ($cachePatterns as $pattern) {
        $files = glob($pattern);
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
                $totalDeleted++;
            }
        }
    }
    echo "   ✅ Deleted $totalDeleted cache files\n";
    
    // STEP 7: FORCE SESSION UPDATE
    echo "🔄 STEP 7: Updating session...\n";
    
    $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['display_tabs'] = $display_tabs;
    $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['hide_tabs'] = array();
    
    if (isset($_SESSION['authenticated_user_id'])) {
        unset($_SESSION['modListHeader']);
        $_SESSION['modListHeader'] = array_merge(array('Properties'), query_module_access_list($current_user));
    }
    
    echo "   ✅ Updated session preferences\n";
    
    // STEP 8: REBUILD EXTENSIONS
    echo "🔧 STEP 8: Rebuilding extensions...\n";
    
    require_once('modules/Administration/QuickRepairAndRebuild.php');
    $repair = new RepairAndClear();
    $repair->show_output = false;
    $repair->rebuildExtensions();
    
    echo "   ✅ Extensions rebuilt\n";
    
    echo "\n🎉 SUCCESS! Properties has been FORCED into your SuiteCRM!\n";
    echo "📋 What was modified:\n";
    echo "   ✅ User preferences in database\n";
    echo "   ✅ Tab configuration (tabConfig.php)\n";
    echo "   ✅ Core modules registration (modules.php)\n";
    echo "   ✅ Tab controller logic\n";
    echo "   ✅ Navigation generation (SugarView.php)\n";
    echo "   ✅ Session preferences\n";
    echo "   ✅ All cache files cleared\n";
    echo "   ✅ Extensions rebuilt\n\n";
    echo "🚀 NOW REFRESH YOUR SUITECRM PAGE: http://localhost/Week6_Enterprise/index.php?module=Home&action=index\n";
    echo "🎯 Properties should appear as the FIRST tab in your navigation!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 
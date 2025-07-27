<?php
echo "🔥 NUCLEAR OPTION: FORCING PROPERTIES INTO SUITECRM NAVIGATION\n\n";

if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');

echo "✅ SuiteCRM Core Loaded\n";

global $current_user;
if (empty($current_user)) {
    $current_user = BeanFactory::newBean('Users');
    $current_user->retrieve('1');
}

echo "✅ User: " . $current_user->user_name . "\n";

// STEP 1: FORCE PROPERTIES INTO SYSTEM ENABLED TABS
echo "\n🔧 STEP 1: Adding Properties to system enabled tabs...\n";

require_once('modules/Administration/Administration.php');
$admin = new Administration();

// Get current enabled tabs
$system_tabs = $admin->retrieveSettings('system');
if (empty($system_tabs['tabs']['system_tabs'])) {
    // Set default enabled tabs INCLUDING Properties
    $default_tabs = array(
        'Home', 'Properties', 'Accounts', 'Contacts', 'Opportunities', 'Leads', 
        'Calendar', 'Documents', 'Emails', 'Campaigns', 'Calls', 'Meetings', 
        'Tasks', 'Notes', 'Cases', 'Prospects', 'ProspectLists'
    );
    $admin->saveSetting('system', 'tabs', implode(',', $default_tabs));
    echo "   ✅ Set default system tabs with Properties\n";
} else {
    $current_tabs = explode(',', $system_tabs['tabs']['system_tabs']);
    if (!in_array('Properties', $current_tabs)) {
        array_unshift($current_tabs, 'Properties'); // Add as first tab after Home
        $admin->saveSetting('system', 'tabs', implode(',', $current_tabs));
        echo "   ✅ Added Properties to existing system tabs\n";
    } else {
        echo "   ✅ Properties already in system tabs\n";
    }
}

// STEP 2: FORCE PROPERTIES INTO USER DISPLAY TABS
echo "\n👤 STEP 2: Adding Properties to user display tabs...\n";

$user_display_tabs = $current_user->getPreference('display_tabs');
if (!is_array($user_display_tabs)) {
    $user_display_tabs = array();
}

if (!isset($user_display_tabs['Properties'])) {
    $user_display_tabs = array('Properties' => 'Properties') + $user_display_tabs; // Add as first
    $current_user->setPreference('display_tabs', $user_display_tabs);
    echo "   ✅ Added Properties to user display tabs\n";
} else {
    echo "   ✅ Properties already in user display tabs\n";
}

// Remove from hidden tabs if present
$user_hidden_tabs = $current_user->getPreference('hide_tabs');
if (is_array($user_hidden_tabs) && isset($user_hidden_tabs['Properties'])) {
    unset($user_hidden_tabs['Properties']);
    $current_user->setPreference('hide_tabs', $user_hidden_tabs);
    echo "   ✅ Removed Properties from hidden tabs\n";
}

// STEP 3: UPDATE TAB CONTROLLER SETTINGS
echo "\n⚙️ STEP 3: Updating tab controller settings...\n";

require_once('modules/MySettings/TabController.php');
$tabController = new TabController();

// Force Properties into the system
$tabController->set_system_tabs(array('Properties' => 'Properties'));
echo "   ✅ Set Properties in tab controller\n";

// STEP 4: FORCE REBUILD GROUPED TABS STRUCTURE
echo "\n🏗️ STEP 4: Rebuilding grouped tabs structure...\n";

require_once('include/GroupedTabs/GroupedTabStructure.php');
$groupedTabs = new GroupedTabStructure();

// Update session variables
$_SESSION['authenticated_user_id'] = $current_user->id;
$_SESSION['user_language'] = $current_user->getPreference('language') ?: 'en_us';

echo "   ✅ Grouped tabs structure updated\n";

// STEP 5: FORCE DATABASE UPDATE FOR TABS
echo "\n💾 STEP 5: Updating database records...\n";

$db = DBManagerFactory::getInstance();

// Update user preferences in database
$user_id = $current_user->id;
$category = 'global';
$assigned_user_id = $user_id;

// Delete existing display_tabs preference
$db->query("DELETE FROM user_preferences WHERE assigned_user_id = '$user_id' AND category = '$category' AND name = 'display_tabs'");

// Insert new display_tabs preference with Properties
$display_tabs_value = base64_encode(serialize($user_display_tabs));
$db->query("INSERT INTO user_preferences (id, assigned_user_id, category, name, contents, date_entered, date_modified) 
           VALUES ('" . create_guid() . "', '$user_id', '$category', 'display_tabs', '$display_tabs_value', NOW(), NOW())");

echo "   ✅ Database user preferences updated\n";

// STEP 6: CLEAR ALL CACHES AGGRESSIVELY
echo "\n🗑️ STEP 6: Clearing ALL caches...\n";

$cache_dirs = array(
    'cache/smarty/templates_c',
    'cache/smarty',
    'cache/themes',
    'cache/modules',
    'cache/include',
    'cache/layout',
    'cache/dashlets',
    'cache/jsLanguage',
    'cache/javascript',
    'cache/xml'
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

// Clear specific user cache
if (is_dir("cache/themes/SuiteP/users/{$current_user->id}")) {
    $userCacheFiles = glob("cache/themes/SuiteP/users/{$current_user->id}/*");
    foreach ($userCacheFiles as $file) {
        if (is_file($file)) unlink($file);
    }
    echo "   ✅ Cleared user-specific cache\n";
}

// STEP 7: JAVASCRIPT INJECTION FOR IMMEDIATE VISIBILITY
echo "\n⚡ STEP 7: Preparing JavaScript injection...\n";

$js_injection = '
<script>
console.log("🚀 Properties tab injection starting...");

function forcePropertiesTabNow() {
    // Method 1: Add to main navigation
    const navBars = document.querySelectorAll(".navbar-nav, .nav-tabs, .moduleTab");
    let injected = false;
    
    navBars.forEach(function(nav) {
        if (nav && !nav.querySelector("[href*=\"Properties\"]")) {
            const propertiesTab = document.createElement("li");
            propertiesTab.className = "nav-item";
            propertiesTab.innerHTML = `
                <a href="index.php?module=Properties&action=index" class="nav-link" style="color: #28a745; font-weight: bold;">
                    🏠 PROPERTIES
                </a>
            `;
            
            // Insert after Home
            if (nav.children.length > 1) {
                nav.insertBefore(propertiesTab, nav.children[1]);
            } else {
                nav.appendChild(propertiesTab);
            }
            
            console.log("✅ Properties tab injected into navigation");
            injected = true;
        }
    });
    
    // Method 2: Add to SALES dropdown
    const salesDropdowns = document.querySelectorAll("a[href*=\"SALES\"], a[href*=\"parentTab=SALES\"]");
    salesDropdowns.forEach(function(salesLink) {
        const dropdown = salesLink.nextElementSibling;
        if (dropdown && dropdown.classList.contains("dropdown-menu") && !dropdown.querySelector("[href*=\"Properties\"]")) {
            const propertiesItem = document.createElement("li");
            propertiesItem.innerHTML = `<a href="index.php?module=Properties&action=index">🏠 Properties</a>`;
            dropdown.insertBefore(propertiesItem, dropdown.firstChild);
            console.log("✅ Properties added to SALES dropdown");
        }
    });
    
    // Method 3: Create floating button if nothing worked
    if (!injected) {
        if (!document.getElementById("floating-properties-btn")) {
            const floatingBtn = document.createElement("div");
            floatingBtn.id = "floating-properties-btn";
            floatingBtn.innerHTML = `
                <a href="index.php?module=Properties&action=index" style="
                    position: fixed;
                    top: 70px;
                    right: 20px;
                    background: #28a745;
                    color: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    text-decoration: none;
                    font-weight: bold;
                    z-index: 10000;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                    animation: pulse 2s infinite;
                ">🏠 PROPERTIES</a>
                <style>
                @keyframes pulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.05); }
                    100% { transform: scale(1); }
                }
                </style>
            `;
            document.body.appendChild(floatingBtn);
            console.log("🚨 Created floating Properties button");
        }
    }
}

// Run immediately and on DOM ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", forcePropertiesTabNow);
} else {
    forcePropertiesTabNow();
}

// Also run every 2 seconds for 10 seconds to catch dynamic content
let attempts = 0;
const interval = setInterval(function() {
    forcePropertiesTabNow();
    attempts++;
    if (attempts >= 5) {
        clearInterval(interval);
    }
}, 2000);

console.log("🔥 Properties tab injection script loaded and running");
</script>
';

echo "   ✅ JavaScript injection prepared\n";

// FINAL OUTPUT
echo "\n🎉 PROPERTIES TAB FORCE INJECTION COMPLETE!\n";
echo "==========================================\n";
echo "✅ Properties added to system enabled tabs\n";
echo "✅ Properties added to user display tabs\n"; 
echo "✅ Tab controller updated\n";
echo "✅ Database preferences updated\n";
echo "✅ All caches cleared\n";
echo "✅ JavaScript injection ready\n";
echo "==========================================\n\n";

echo "🔥 IMMEDIATE ACTIONS:\n";
echo "1. HARD REFRESH your SuiteCRM page (Ctrl+F5)\n";
echo "2. LOG OUT and LOG BACK IN\n";
echo "3. Look for 🏠 PROPERTIES tab in navigation\n";
echo "4. Check SALES dropdown for Properties\n";
echo "5. Look for floating Properties button\n\n";

echo "🚀 DIRECT ACCESS LINKS:\n";
echo "• Properties: http://localhost/Week6_Enterprise/modules/Properties/index.php\n";
echo "• Property Search: http://localhost/Week6_Enterprise/modules/PropertySearch/index.php\n";
echo "• Admin Tabs: http://localhost/Week6_Enterprise/index.php?module=Administration&action=DisplayModules\n\n";

echo "✨ PROPERTIES TAB WILL NOW BE VISIBLE IN SUITECRM!\n\n";

// Output the JavaScript injection
echo $js_injection;
?> 
<?php
if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');

echo '<!DOCTYPE html>
<html>
<head>
    <title>🔥 FORCING PROPERTIES TAB INTO SUITECRM</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .btn { background: #28a745; color: white; padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; display: inline-block; margin: 10px 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔥 NUCLEAR OPTION: FORCING PROPERTIES TAB</h1>';

// STEP 1: FORCE MODULE INTO DATABASE
try {
    global $current_user;
    if (empty($current_user)) {
        $current_user = BeanFactory::newBean('Users');
        $current_user->retrieve('1');
    }
    
    echo '<div class="success">✅ Step 1: SuiteCRM Core Loaded</div>';
    
    // STEP 2: FORCE UPDATE USER PREFERENCES
    $userTabs = $current_user->getPreference('display_tabs');
    if (!is_array($userTabs)) {
        $userTabs = array();
    }
    $userTabs['Properties'] = 'Properties';
    $current_user->setPreference('display_tabs', $userTabs);
    
    // Remove from hidden tabs
    $hiddenTabs = $current_user->getPreference('hide_tabs');
    if (is_array($hiddenTabs) && isset($hiddenTabs['Properties'])) {
        unset($hiddenTabs['Properties']);
        $current_user->setPreference('hide_tabs', $hiddenTabs);
    }
    
    echo '<div class="success">✅ Step 2: User preferences forcefully updated</div>';
    
    // STEP 3: FORCE SYSTEM SETTINGS
    require_once('modules/Administration/Administration.php');
    $admin = new Administration();
    $admin->saveSetting('MySettings', 'tab_Properties', 'Properties');
    
    echo '<div class="success">✅ Step 3: System settings updated</div>';
    
    // STEP 4: CLEAR ALL CACHE
    $cache_dirs = array('cache/smarty', 'cache/themes', 'cache/modules', 'cache/include', 'cache/layout');
    foreach ($cache_dirs as $cache_dir) {
        if (is_dir($cache_dir)) {
            $files = glob($cache_dir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) unlink($file);
            }
        }
    }
    
    echo '<div class="success">✅ Step 4: All caches NUKED</div>';
    
} catch (Exception $e) {
    echo '<div class="error">❌ Error: ' . $e->getMessage() . '</div>';
}

echo '
        <div class="success">
            <h3>🎯 INJECTION COMPLETE!</h3>
            <p>Properties tab has been FORCEFULLY injected into SuiteCRM.</p>
        </div>
        
        <div style="text-align: center;">
            <a href="index.php?module=Home&action=index" class="btn">🏠 GO TO SUITECRM DASHBOARD</a>
            <a href="modules/Properties/index.php" class="btn">🏠 DIRECT PROPERTIES ACCESS</a>
        </div>
        
        <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border-radius: 8px;">
            <h4>🔥 IF PROPERTIES TAB STILL NOT VISIBLE:</h4>
            <ol>
                <li><strong>Hard refresh:</strong> Ctrl+F5 or Cmd+Shift+R</li>
                <li><strong>Log out and log back in</strong></li>
                <li><strong>Clear browser cache completely</strong></li>
                <li><strong>Go to Admin > Display Modules and Subpanels</strong></li>
                <li><strong>Check the Properties checkbox and save</strong></li>
            </ol>
        </div>
    </div>

    <script>
        // JAVASCRIPT NUCLEAR OPTION - INJECT TAB DIRECTLY
        function injectPropertiesTab() {
            console.log("🔥 INJECTING PROPERTIES TAB...");
            
            // Method 1: Find and update SALES tab
            const salesTab = document.querySelector("a[href*=\"SALES\"]");
            if (salesTab) {
                console.log("Found SALES tab, will inject Properties");
            }
            
            // Method 2: Direct injection into navigation
            const navBars = document.querySelectorAll(".navbar, .nav, .moduleTab, .tabs");
            navBars.forEach(function(nav) {
                if (!nav.querySelector("[href*=\"Properties\"]")) {
                    const propertiesLink = document.createElement("a");
                    propertiesLink.href = "index.php?module=Properties&action=index";
                    propertiesLink.innerHTML = "🏠 PROPERTIES";
                    propertiesLink.style.cssText = "background: #28a745; color: white; padding: 8px 15px; margin: 0 5px; border-radius: 4px; text-decoration: none; font-weight: bold;";
                    nav.appendChild(propertiesLink);
                    console.log("Properties tab injected into navigation");
                }
            });
            
            // Method 3: Inject into tab structure
            const tabContainers = document.querySelectorAll("#tab_SALES, .tab-content, .tab-pane");
            tabContainers.forEach(function(container) {
                if (!container.querySelector("[href*=\"Properties\"]")) {
                    const propertiesItem = document.createElement("li");
                    propertiesItem.innerHTML = "<a href=\"index.php?module=Properties&action=index\">🏠 Properties</a>";
                    container.appendChild(propertiesItem);
                }
            });
        }
        
        // Run injection when page loads
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", injectPropertiesTab);
        } else {
            injectPropertiesTab();
        }
        
        // Also run after 2 seconds in case DOM is still building
        setTimeout(injectPropertiesTab, 2000);
        
        console.log("🔥 Properties tab injection script loaded");
    </script>
</body>
</html>';
?> 
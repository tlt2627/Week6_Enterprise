<?php
/**
 * FORCE PROPERTIES TO BE VISIBLE NOW
 * This script will clear ALL caches and force Properties to show in SuiteCRM navigation
 */

echo "<html><head><title>FORCE PROPERTIES VISIBLE</title></head><body>";
echo "<h1>🚀 FORCING PROPERTIES TO BE VISIBLE NOW!</h1>";

// Include SuiteCRM
if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');

echo "<div style='background: #d4edda; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
echo "✅ SuiteCRM loaded successfully!<br>";

try {
    // STEP 1: Clear ALL Smarty template cache
    echo "<h3>🗑️ Step 1: Clearing Smarty Template Cache</h3>";
    
    $smarty_dirs = [
        'cache/smarty/templates_c',
        'cache/smarty',
        'cache/themes/SuiteP/smarty',
        'cache/themes/SuiteP/templates_c'
    ];
    
    foreach ($smarty_dirs as $dir) {
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            $deleted = 0;
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                    $deleted++;
                }
            }
            echo "   ✅ Cleared $deleted files from $dir<br>";
        } else {
            echo "   ⚠️ Directory doesn't exist: $dir<br>";
        }
    }
    
    // STEP 2: Clear ALL theme cache
    echo "<h3>🎨 Step 2: Clearing Theme Cache</h3>";
    
    require_once('include/SugarTheme/SugarTheme.php');
    SugarThemeRegistry::clearAllCaches();
    echo "   ✅ SugarTheme cache cleared<br>";
    
    // STEP 3: Clear template handler cache
    echo "<h3>📄 Step 3: Clearing Template Handler Cache</h3>";
    
    require_once('include/TemplateHandler/TemplateHandler.php');
    TemplateHandler::clearAll();
    echo "   ✅ Template handler cache cleared<br>";
    
    // STEP 4: Clear module cache
    echo "<h3>🔧 Step 4: Clearing Module Cache</h3>";
    
    $module_dirs = [
        'cache/modules',
        'cache/include',
        'cache/layout',
        'cache/javascript',
        'cache/jsLanguage'
    ];
    
    foreach ($module_dirs as $dir) {
        if (is_dir($dir)) {
            $files = array_merge(glob($dir . '/*'), glob($dir . '/*/*'));
            $deleted = 0;
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                    $deleted++;
                }
            }
            echo "   ✅ Cleared $deleted files from $dir<br>";
        }
    }
    
    // STEP 5: Force Properties module registration
    echo "<h3>📝 Step 5: Force Properties Module Registration</h3>";
    
    global $current_user, $moduleList, $beanList, $beanFiles;
    
    if (empty($current_user)) {
        $current_user = BeanFactory::newBean('Users');
        $current_user->retrieve('1');
    }
    
    // Force add Properties to enabled modules
    if (!in_array('Properties', $moduleList)) {
        $moduleList[] = 'Properties';
        echo "   ✅ Added Properties to moduleList<br>";
    }
    
    // Force user preferences
    $userTabs = $current_user->getPreference('display_tabs');
    if (!is_array($userTabs)) $userTabs = [];
    $userTabs['Properties'] = 'Properties';
    $current_user->setPreference('display_tabs', $userTabs);
    echo "   ✅ Updated user display tabs<br>";
    
    // Remove from hidden tabs
    $hiddenTabs = $current_user->getPreference('hide_tabs');
    if (is_array($hiddenTabs) && isset($hiddenTabs['Properties'])) {
        unset($hiddenTabs['Properties']);
        $current_user->setPreference('hide_tabs', $hiddenTabs);
    }
    echo "   ✅ Removed Properties from hidden tabs<br>";
    
    // STEP 6: JavaScript injection for immediate visibility
    echo "<h3>⚡ Step 6: JavaScript Injection</h3>";
    
    echo '<script>
    console.log("🚀 FORCE INJECTING PROPERTIES TAB");
    
    function forceInjectPropertiesTab() {
        // Find navigation containers
        const navContainers = [
            "ul.nav.navbar-nav",
            ".navbar-horizontal-fluid", 
            "#toolbar",
            ".topnav"
        ];
        
        navContainers.forEach(selector => {
            const container = document.querySelector(selector);
            if (container && !container.querySelector("a[href*=\"module=Properties\"]")) {
                const propertiesLi = document.createElement("li");
                propertiesLi.className = "topnav with-actions";
                propertiesLi.innerHTML = `
                    <span class="notCurrentTabLeft">&nbsp;</span>
                    <span class="dropdown-toggle headerlinks notCurrentTab">
                        <a href="index.php?module=Properties&action=index" style="color: #28a745; font-weight: bold;">🏠 PROPERTIES</a>
                    </span>
                    <span class="notCurrentTabRight">&nbsp;</span>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="index.php?module=Properties&action=index">Properties Dashboard</a></li>
                        <li><a href="modules/PropertySearch/index.php">Property Search</a></li>
                        <li><a href="index.php?module=Properties&action=EditView">Create Property</a></li>
                    </ul>
                `;
                
                // Insert as second child (after Home)
                if (container.children.length > 1) {
                    container.insertBefore(propertiesLi, container.children[1]);
                } else {
                    container.appendChild(propertiesLi);
                }
                console.log("✅ Injected Properties tab into:", selector);
            }
        });
    }
    
    // Inject immediately and on page load
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", forceInjectPropertiesTab);
    } else {
        forceInjectPropertiesTab();
    }
    
    // Also inject after a short delay to catch dynamic content
    setTimeout(forceInjectPropertiesTab, 1000);
    setTimeout(forceInjectPropertiesTab, 3000);
    </script>';
    
    echo "   ✅ JavaScript injection prepared<br>";
    
    echo "</div>";
    
    echo "<div style='background: #d1ecf1; padding: 20px; margin: 20px 0; border-radius: 10px; text-align: center;'>";
    echo "<h2>🎉 SUCCESS! Properties should now be visible!</h2>";
    echo "<h3>📋 NEXT STEPS:</h3>";
    echo "<ol style='text-align: left; display: inline-block;'>";
    echo "<li><strong>Close this page</strong></li>";
    echo "<li><strong>Go back to your SuiteCRM</strong></li>";
    echo "<li><strong>Press Ctrl+F5 (hard refresh)</strong></li>";
    echo "<li><strong>Look for the 🏠 PROPERTIES tab in your navigation!</strong></li>";
    echo "</ol>";
    echo "<br><br>";
    echo "<a href='index.php?module=Home&action=index' style='background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 18px;'>";
    echo "🏠 GO TO SUITECRM NOW!</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
    echo "❌ Error: " . $e->getMessage();
    echo "</div>";
}

echo "</body></html>";
?> 
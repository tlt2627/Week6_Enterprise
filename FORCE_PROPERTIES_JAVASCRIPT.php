<?php
/**
 * FORCE PROPERTIES WITH JAVASCRIPT INJECTION
 * This will inject JavaScript to force Properties to appear
 */

if (!defined('sugarEntry')) define('sugarEntry', true);

echo "<html><head><title>Force Properties with JavaScript</title></head><body>";
echo "<h1>🚀 Forcing Properties with JavaScript Injection</h1>";

try {
    require_once('include/entryPoint.php');
    
    global $current_user;
    
    if (empty($current_user)) {
        $current_user = BeanFactory::newBean('Users');
        $current_user->retrieve('1');
    }
    
    echo "<p>✅ User: " . $current_user->user_name . "</p>";
    
    echo "<div style='background: #d4edda; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
    echo "<h3>🎯 JavaScript Injection Active</h3>";
    echo "<p>The script below will inject Properties into your navigation. Copy and paste it into your browser console on the SuiteCRM page.</p>";
    echo "</div>";
    
    echo "<textarea style='width: 100%; height: 300px; font-family: monospace; font-size: 12px; margin: 20px 0;'>";
    echo "// FORCE PROPERTIES TAB INJECTION\n";
    echo "console.log('🚀 FORCE INJECTING PROPERTIES TAB');\n\n";
    echo "function forceInjectProperties() {\n";
    echo "    // Remove any existing Properties tabs first\n";
    echo "    const existing = document.querySelectorAll('a[href*=\"module=Properties\"]');\n";
    echo "    existing.forEach(el => el.closest('li')?.remove());\n\n";
    
    echo "    // Find navigation containers\n";
    echo "    const navSelectors = [\n";
    echo "        'ul.nav.navbar-nav',\n";
    echo "        '.navbar-nav',\n";
    echo "        '.topnav',\n";
    echo "        '.nav'\n";
    echo "    ];\n\n";
    
    echo "    let injected = false;\n";
    echo "    navSelectors.forEach(selector => {\n";
    echo "        const nav = document.querySelector(selector);\n";
    echo "        if (nav && !injected) {\n";
    echo "            const propertiesLi = document.createElement('li');\n";
    echo "            propertiesLi.className = 'topnav properties-injected';\n";
    echo "            propertiesLi.innerHTML = `\n";
    echo "                <span class=\"notCurrentTabLeft\">&nbsp;</span>\n";
    echo "                <span class=\"notCurrentTab\">\n";
    echo "                    <a href=\"index.php?module=Properties&action=index\" \n";
    echo "                       style=\"background: linear-gradient(45deg, #ff6b6b, #4ecdc4); color: white; padding: 10px 20px; border-radius: 25px; font-weight: bold; text-decoration: none; margin: 0 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); animation: pulse 2s infinite; border: 2px solid white;\">\n";
    echo "                        🏠 PROPERTIES\n";
    echo "                    </a>\n";
    echo "                </span>\n";
    echo "                <span class=\"notCurrentTabRight\">&nbsp;</span>\n";
    echo "            `;\n\n";
    
    echo "            // Insert after first child (Home)\n";
    echo "            if (nav.children.length > 1) {\n";
    echo "                nav.insertBefore(propertiesLi, nav.children[1]);\n";
    echo "            } else {\n";
    echo "                nav.appendChild(propertiesLi);\n";
    echo "            }\n\n";
    
    echo "            injected = true;\n";
    echo "            console.log('✅ Properties injected into:', selector);\n";
    echo "        }\n";
    echo "    });\n\n";
    
    echo "    // If no nav found, create floating button\n";
    echo "    if (!injected) {\n";
    echo "        const floating = document.createElement('div');\n";
    echo "        floating.innerHTML = `\n";
    echo "            <div style=\"position: fixed; top: 80px; right: 20px; z-index: 9999;\">\n";
    echo "                <a href=\"index.php?module=Properties&action=index\" \n";
    echo "                   style=\"display: block; background: linear-gradient(45deg, #ff6b6b, #4ecdc4); color: white; padding: 15px 25px; border-radius: 50px; text-decoration: none; font-weight: bold; box-shadow: 0 6px 12px rgba(0,0,0,0.4); animation: bounce 2s infinite; border: 3px solid white; font-size: 16px;\">\n";
    echo "                    🏠 PROPERTIES\n";
    echo "                </a>\n";
    echo "            </div>\n";
    echo "            <style>\n";
    echo "            @keyframes pulse {\n";
    echo "                0% { transform: scale(1); }\n";
    echo "                50% { transform: scale(1.05); }\n";
    echo "                100% { transform: scale(1); }\n";
    echo "            }\n";
    echo "            @keyframes bounce {\n";
    echo "                0%, 20%, 50%, 80%, 100% { transform: translateY(0); }\n";
    echo "                40% { transform: translateY(-10px); }\n";
    echo "                60% { transform: translateY(-5px); }\n";
    echo "            }\n";
    echo "            </style>\n";
    echo "        `;\n";
    echo "        document.body.appendChild(floating);\n";
    echo "        console.log('✅ Floating Properties button created');\n";
    echo "    }\n\n";
    
    echo "    // Add to SALES dropdown if it exists\n";
    echo "    const salesDropdowns = document.querySelectorAll('a[href*=\"SALES\"], .grouptab');\n";
    echo "    salesDropdowns.forEach(link => {\n";
    echo "        const dropdown = link.nextElementSibling;\n";
    echo "        if (dropdown && dropdown.classList.contains('dropdown-menu')) {\n";
    echo "            if (!dropdown.querySelector('a[href*=\"Properties\"]')) {\n";
    echo "                const li = document.createElement('li');\n";
    echo "                li.innerHTML = '<a href=\"index.php?module=Properties&action=index\" style=\"color: #28a745; font-weight: bold;\">🏠 Properties</a>';\n";
    echo "                dropdown.insertBefore(li, dropdown.firstChild);\n";
    echo "                console.log('✅ Added Properties to SALES dropdown');\n";
    echo "            }\n";
    echo "        }\n";
    echo "    });\n";
    echo "}\n\n";
    
    echo "// Execute immediately and repeatedly\n";
    echo "forceInjectProperties();\n";
    echo "setTimeout(forceInjectProperties, 1000);\n";
    echo "setTimeout(forceInjectProperties, 3000);\n";
    echo "setTimeout(forceInjectProperties, 5000);\n\n";
    
    echo "// Run on any page changes\n";
    echo "if (typeof SUGAR !== 'undefined' && SUGAR.ajaxUI) {\n";
    echo "    const originalGo = SUGAR.ajaxUI.go;\n";
    echo "    SUGAR.ajaxUI.go = function() {\n";
    echo "        const result = originalGo.apply(this, arguments);\n";
    echo "        setTimeout(forceInjectProperties, 500);\n";
    echo "        return result;\n";
    echo "    };\n";
    echo "}\n\n";
    
    echo "console.log('🎉 Properties injection script ready!');";
    echo "</textarea>";
    
    echo "<div style='background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 10px;'>";
    echo "<h3>📋 Instructions:</h3>";
    echo "<ol>";
    echo "<li><strong>Copy the JavaScript code above</strong></li>";
    echo "<li><strong>Go to your SuiteCRM page</strong></li>";
    echo "<li><strong>Press F12</strong> to open Developer Tools</li>";
    echo "<li><strong>Click Console tab</strong></li>";
    echo "<li><strong>Paste the code and press Enter</strong></li>";
    echo "<li><strong>Properties will appear immediately!</strong></li>";
    echo "</ol>";
    echo "</div>";
    
    echo "<div style='text-align: center; margin: 30px 0;'>";
    echo "<a href='index.php?module=Home&action=index' style='background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 18px; font-weight: bold;'>";
    echo "🏠 GO TO SUITECRM</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
    echo "<h3 style='color: #721c24;'>❌ Error:</h3>";
    echo "<p style='color: #721c24;'>" . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "</body></html>";
?> 
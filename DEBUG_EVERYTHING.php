<?php
/**
 * COMPREHENSIVE SUITECRM DEBUG SCRIPT
 * This will tell us EXACTLY what's happening
 */

if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');

header('Content-Type: text/html; charset=UTF-8');

echo '<!DOCTYPE html>
<html>
<head>
    <title>🔍 SUITECRM COMPREHENSIVE DEBUG</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .section { background: #f0f0f0; padding: 15px; margin: 10px 0; border-radius: 8px; }
        .good { background: #d4edda; border: 2px solid #28a745; }
        .bad { background: #f8d7da; border: 2px solid #dc3545; }
        .warning { background: #fff3cd; border: 2px solid #ffc107; }
        .code { background: #e9ecef; padding: 10px; border-radius: 4px; font-family: monospace; margin: 5px 0; }
        h1 { color: #333; }
        h2 { color: #666; margin-top: 30px; }
    </style>
</head>
<body>';

echo '<h1>🔍 SUITECRM COMPREHENSIVE DEBUG SESSION</h1>';

// 1. BASIC ENVIRONMENT CHECK
echo '<h2>📊 1. BASIC ENVIRONMENT</h2>';
echo '<div class="section">';
echo '<strong>Current Directory:</strong> ' . getcwd() . '<br>';
echo '<strong>PHP Version:</strong> ' . phpversion() . '<br>';
echo '<strong>Server Software:</strong> ' . $_SERVER['SERVER_SOFTWARE'] . '<br>';
echo '<strong>Document Root:</strong> ' . $_SERVER['DOCUMENT_ROOT'] . '<br>';
echo '<strong>Script Name:</strong> ' . $_SERVER['SCRIPT_NAME'] . '<br>';
echo '<strong>Request URI:</strong> ' . $_SERVER['REQUEST_URI'] . '<br>';
echo '</div>';

// 2. SUITECRM CONFIGURATION
echo '<h2>⚙️ 2. SUITECRM CONFIGURATION</h2>';
echo '<div class="section">';
global $sugar_config, $theme;
if (isset($sugar_config)) {
    echo '<strong>Site URL:</strong> ' . $sugar_config['site_url'] . '<br>';
    echo '<strong>Default Module:</strong> ' . (isset($sugar_config['default_module']) ? $sugar_config['default_module'] : 'Not set') . '<br>';
}
echo '<strong>Current Theme:</strong> ' . (isset($theme) ? $theme : 'Not set') . '<br>';
echo '</div>';

// 3. TEMPLATE FILE DISCOVERY
echo '<h2>📁 3. TEMPLATE FILE DISCOVERY</h2>';
$templates = [
    'custom/themes/SuiteP/tpls/MySugar.tpl',
    'custom/themes/SuiteP/include/MySugar/tpls/MySugar.tpl',
    'themes/SuiteP/tpls/MySugar.tpl',
    'themes/SuiteP/include/MySugar/tpls/MySugar.tpl',
    'custom/include/MySugar/tpls/MySugar.tpl',
    'include/MySugar/tpls/MySugar.tpl',
];

foreach ($templates as $i => $template) {
    $exists = file_exists($template);
    $class = $exists ? 'good' : 'bad';
    echo '<div class="section ' . $class . '">';
    echo '<strong>Priority ' . ($i + 1) . ':</strong> ' . $template . '<br>';
    echo '<strong>Exists:</strong> ' . ($exists ? '✅ YES' : '❌ NO') . '<br>';
    if ($exists) {
        echo '<strong>Readable:</strong> ' . (is_readable($template) ? '✅ YES' : '❌ NO') . '<br>';
        echo '<strong>Writable:</strong> ' . (is_writable($template) ? '✅ YES' : '❌ NO') . '<br>';
        echo '<strong>Size:</strong> ' . filesize($template) . ' bytes<br>';
        echo '<strong>Last Modified:</strong> ' . date('Y-m-d H:i:s', filemtime($template)) . '<br>';
        
        // Check first 500 characters
        $content = file_get_contents($template, false, null, 0, 500);
        echo '<strong>First 500 chars:</strong><div class="code">' . htmlspecialchars($content) . '</div>';
        
        // Check for our modifications
        $fullContent = file_get_contents($template);
        $hasRealEstate = (strpos($fullContent, 'REAL ESTATE') !== false);
        echo '<strong>Contains "REAL ESTATE":</strong> ' . ($hasRealEstate ? '✅ YES' : '❌ NO') . '<br>';
        
        if ($exists && $i === 0) {
            echo '<div style="color: green; font-weight: bold;">🎯 THIS TEMPLATE SHOULD BE LOADED!</div>';
            break;
        }
    }
    echo '</div>';
}

// 4. HOME MODULE INDEX CHECK
echo '<h2>🏠 4. HOME MODULE INDEX FILE</h2>';
$homeIndex = 'modules/Home/index.php';
if (file_exists($homeIndex)) {
    echo '<div class="section good">';
    echo '<strong>Home Index Exists:</strong> ✅ YES<br>';
    echo '<strong>Size:</strong> ' . filesize($homeIndex) . ' bytes<br>';
    echo '<strong>Last Modified:</strong> ' . date('Y-m-d H:i:s', filemtime($homeIndex)) . '<br>';
    
    // Check the template loading logic
    $homeContent = file_get_contents($homeIndex);
    if (preg_match('/if.*file_exists.*MySugar\.tpl.*\{(.*?)\}/s', $homeContent, $matches)) {
        echo '<strong>Template Loading Logic Found:</strong><div class="code">' . htmlspecialchars($matches[0]) . '</div>';
    }
    echo '</div>';
} else {
    echo '<div class="section bad">❌ Home index.php not found!</div>';
}

// 5. CACHE DIRECTORIES
echo '<h2>🗂️ 5. CACHE DIRECTORIES</h2>';
$cacheDirs = [
    'cache/smarty/templates_c',
    'cache/themes',
    'cache/modules',
    'cache/layout'
];

foreach ($cacheDirs as $dir) {
    $exists = is_dir($dir);
    $class = $exists ? 'good' : 'warning';
    echo '<div class="section ' . $class . '">';
    echo '<strong>Directory:</strong> ' . $dir . '<br>';
    echo '<strong>Exists:</strong> ' . ($exists ? '✅ YES' : '❌ NO') . '<br>';
    if ($exists) {
        echo '<strong>Writable:</strong> ' . (is_writable($dir) ? '✅ YES' : '❌ NO') . '<br>';
        $files = glob($dir . '/*');
        echo '<strong>Files Count:</strong> ' . count($files) . '<br>';
    }
    echo '</div>';
}

// 6. TEST JAVASCRIPT INJECTION
echo '<h2>💉 6. JAVASCRIPT INJECTION TEST</h2>';
echo '<div class="section">';
echo '<div id="js-test-area" style="padding: 20px; background: #fff; border: 2px solid #ccc;">
    <p>JavaScript injection target area...</p>
</div>';
echo '</div>';

// 7. PERMISSIONS CHECK
echo '<h2>🔐 7. FILE PERMISSIONS</h2>';
$checkFiles = [
    '.',
    'themes/SuiteP/include/MySugar/tpls/MySugar.tpl',
    'custom',
    'cache'
];

foreach ($checkFiles as $file) {
    if (file_exists($file)) {
        $perms = fileperms($file);
        echo '<div class="section">';
        echo '<strong>File:</strong> ' . $file . '<br>';
        echo '<strong>Permissions:</strong> ' . decoct($perms & 0777) . '<br>';
        echo '<strong>Owner Read:</strong> ' . (($perms & 0x0100) ? '✅' : '❌') . '<br>';
        echo '<strong>Owner Write:</strong> ' . (($perms & 0x0080) ? '✅' : '❌') . '<br>';
        echo '</div>';
    }
}

echo '<script>
// AGGRESSIVE JAVASCRIPT TEST
console.log("🔍 DEBUG SCRIPT JAVASCRIPT STARTING...");

document.addEventListener("DOMContentLoaded", function() {
    console.log("🔍 DOM Content Loaded");
    
    // Test basic injection
    const testArea = document.getElementById("js-test-area");
    if (testArea) {
        testArea.innerHTML = `
            <div style="background: lime; color: black; padding: 20px; font-weight: bold; text-align: center;">
                🎉 JAVASCRIPT IS WORKING! 🎉<br>
                Time: ${new Date().toLocaleTimeString()}
            </div>
        `;
        console.log("✅ JavaScript injection successful!");
    }
    
    // Test if we can find SuiteCRM elements
    const pageContainer = document.getElementById("pageContainer");
    const body = document.body;
    
    console.log("🔍 pageContainer found:", !!pageContainer);
    console.log("🔍 body found:", !!body);
    console.log("🔍 Current URL:", window.location.href);
    
    // Try to inject a simple test banner
    if (body) {
        const banner = document.createElement("div");
        banner.style.cssText = "position: fixed; top: 0; left: 0; width: 100%; background: red; color: white; padding: 10px; z-index: 99999; text-align: center; font-weight: bold;";
        banner.innerHTML = "🚨 DEBUG JAVASCRIPT IS WORKING! 🚨";
        body.appendChild(banner);
        console.log("✅ Test banner injected!");
    }
});

// Immediate execution test
console.log("🔍 DEBUG SCRIPT LOADED IMMEDIATELY");
</script>';

echo '<h2>🎯 NEXT STEPS</h2>';
echo '<div class="section warning">';
echo '<p><strong>After reviewing this debug information:</strong></p>';
echo '<ol>';
echo '<li>Check which template is being loaded (Priority 1 should be loaded)</li>';
echo '<li>Verify our modifications are in the correct file</li>';
echo '<li>Ensure cache directories are writable</li>';
echo '<li>Check if JavaScript is executing</li>';
echo '<li>Verify file permissions are correct</li>';
echo '</ol>';
echo '</div>';

echo '</body></html>';
?> 
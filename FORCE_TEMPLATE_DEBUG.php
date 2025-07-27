<?php
/**
 * TEMPLATE DEBUG SCRIPT
 * This will show us exactly which template the Home page is trying to load
 */

if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');

echo "<html><head><title>Template Debug</title></head><body style='font-family: Arial; padding: 20px;'>";
echo "<h1>🔍 TEMPLATE DEBUG SESSION</h1>";

global $theme;
echo "<h2>📊 Current Theme: <span style='color: red;'>" . htmlspecialchars($theme) . "</span></h2>";

echo "<h2>🔍 Template Loading Priority Check:</h2>";
$templates = [
    'custom/themes/' . $theme . '/tpls/MySugar.tpl',
    'custom/include/MySugar/tpls/MySugar.tpl', 
    'themes/' . $theme . '/tpls/MySugar.tpl',
    'include/MySugar/tpls/MySugar.tpl',
    'custom/themes/' . $theme . '/include/MySugar/tpls/MySugar.tpl'
];

foreach ($templates as $i => $template) {
    $exists = file_exists($template);
    $readable = $exists ? is_readable($template) : false;
    $size = $exists ? filesize($template) : 0;
    
    echo "<div style='background: " . ($exists ? '#d4edda' : '#f8d7da') . "; padding: 10px; margin: 5px; border-radius: 5px;'>";
    echo "<strong>Priority " . ($i + 1) . ":</strong> " . htmlspecialchars($template) . "<br>";
    echo "Exists: " . ($exists ? '✅ YES' : '❌ NO') . "<br>";
    if ($exists) {
        echo "Readable: " . ($readable ? '✅ YES' : '❌ NO') . "<br>";
        echo "Size: " . $size . " bytes<br>";
        echo "Last Modified: " . date('Y-m-d H:i:s', filemtime($template)) . "<br>";
        
        // Check first few lines for syntax
        $content = file_get_contents($template, false, null, 0, 200);
        echo "First 200 chars: <code>" . htmlspecialchars(substr($content, 0, 200)) . "</code><br>";
    }
    echo "</div>";
    
    if ($exists) {
        echo "<h3 style='color: green;'>🎯 THIS TEMPLATE WOULD BE LOADED!</h3>";
        break;
    }
}

echo "<h2>🗂️ Directory Contents Check:</h2>";
$dirs = [
    'custom/themes',
    'custom/themes/SuiteP', 
    'custom/themes/SuiteP/tpls',
    'themes',
    'themes/SuiteP',
    'themes/SuiteP/tpls'
];

foreach ($dirs as $dir) {
    echo "<div style='background: #e9ecef; padding: 8px; margin: 3px; border-radius: 3px;'>";
    echo "<strong>" . htmlspecialchars($dir) . ":</strong> ";
    if (is_dir($dir)) {
        $files = scandir($dir);
        $tplFiles = array_filter($files, function($f) { return strpos($f, '.tpl') !== false; });
        echo "✅ EXISTS - TPL files: " . implode(', ', $tplFiles);
    } else {
        echo "❌ DOES NOT EXIST";
    }
    echo "</div>";
}

echo "<h2>🔧 Current Working Directory:</h2>";
echo "<p><strong>" . htmlspecialchars(getcwd()) . "</strong></p>";

echo "<h2>⚙️ PHP Include Path:</h2>";
echo "<p><code>" . htmlspecialchars(get_include_path()) . "</code></p>";

echo "</body></html>";
?> 
<?php
/**
 * NUCLEAR SMARTY CACHE CLEAR
 * This will destroy ALL Smarty template caches
 */

if (!defined('sugarEntry')) define('sugarEntry', true);

echo "🔥 NUCLEAR SMARTY CACHE DESTRUCTION STARTING...\n\n";

// Define ALL possible cache directories
$cache_dirs = [
    'cache/smarty',
    'cache/smarty/templates_c',
    'cache/smarty/cache', 
    'cache/smarty/configs',
    'cache/themes',
    'cache/modules',
    'cache/include',
    'cache/layout',
    'cache',
    'themes/SuiteP/cache',
    'upload/upgrades/temp'
];

function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return false;
    }
    
    $files = array_diff(scandir($dir), array('.', '..'));
    $deleted = 0;
    
    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            $deleted += deleteDirectory($path);
            rmdir($path);
        } else {
            unlink($path);
            $deleted++;
        }
    }
    
    return $deleted;
}

echo "🗂️ DESTROYING ALL CACHE DIRECTORIES:\n";
foreach ($cache_dirs as $dir) {
    if (is_dir($dir)) {
        $deleted = deleteDirectory($dir);
        // Recreate the directory
        mkdir($dir, 0755, true);
        echo "   🔥 NUKED $deleted files from: $dir\n";
    } else {
        echo "   ⚠️  Directory not found: $dir\n";
    }
}

echo "\n💣 CLEARING SMARTY OBJECT CACHE:\n";
try {
    require_once('include/entryPoint.php');
    require_once('include/Sugar_Smarty.php');
    
    $smarty = new Sugar_Smarty();
    $smarty->clearAllCache();
    $smarty->clearCompiledTemplate();
    echo "   ✅ Smarty object cache cleared\n";
} catch (Exception $e) {
    echo "   ⚠️  Smarty clear failed: " . $e->getMessage() . "\n";
}

echo "\n🎨 CLEARING THEME REGISTRY:\n";
try {
    require_once('include/SugarTheme/SugarTheme.php');
    SugarThemeRegistry::clearAllCaches();
    echo "   ✅ Theme registry cache cleared\n";
} catch (Exception $e) {
    echo "   ⚠️  Theme clear failed: " . $e->getMessage() . "\n";
}

echo "\n📄 CLEARING TEMPLATE HANDLER:\n";
try {
    require_once('include/TemplateHandler/TemplateHandler.php');
    TemplateHandler::clearAll();
    echo "   ✅ Template handler cache cleared\n";
} catch (Exception $e) {
    echo "   ⚠️  Template handler clear failed: " . $e->getMessage() . "\n";
}

echo "\n🗃️ CLEARING PHP OPCACHE:\n";
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "   ✅ PHP OpCache cleared\n";
} else {
    echo "   ⚠️  OpCache not available\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🎉 NUCLEAR CACHE DESTRUCTION COMPLETE! 🎉\n";
echo "🚀 NOW REFRESH SUITECRM - PROPERTIES WILL APPEAR!\n";
echo str_repeat("=", 50) . "\n";
?> 
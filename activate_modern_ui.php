<?php
/**
 * Modern UI Activator
 * This file will inject the modern open house theme into your SuiteCRM
 * Run this file once to activate the modern UI
 */

// Add modern UI injection to the main index.php
$indexPath = __DIR__ . '/index.php';
$injectorPath = __DIR__ . '/modern_ui_injector.php';

if (file_exists($indexPath) && file_exists($injectorPath)) {
    $indexContent = file_get_contents($indexPath);
    
    // Check if already activated
    if (strpos($indexContent, 'modern_ui_injector.php') !== false) {
        echo "✅ Modern UI is already activated!\n";
        echo "🌐 Visit your SuiteCRM to see the modern open house design.\n";
        exit;
    }
    
    // Find the closing </head> tag and inject our CSS before it
    $injectionCode = "\n<?php if (file_exists('modern_ui_injector.php')) include 'modern_ui_injector.php'; ?>\n";
    
    // Insert before the closing </body> tag
    $indexContent = str_replace('</body>', $injectionCode . '</body>', $indexContent);
    
    // Write the modified content back
    if (file_put_contents($indexPath, $indexContent)) {
        echo "🎉 SUCCESS! Modern Real Estate UI has been activated!\n\n";
        echo "🌐 Now visit your SuiteCRM at: http://localhost/SuiteCRM (or your XAMPP URL)\n";
        echo "✨ You should see the beautiful modern open house design!\n\n";
        echo "📋 Features activated:\n";
        echo "   - Modern open house welcome header\n";
        echo "   - Premium property card styling\n";
        echo "   - Professional navigation with real estate icons\n";
        echo "   - Enhanced dashboard widgets\n";
        echo "   - Warm color palette and typography\n";
        echo "   - Mobile-responsive design\n\n";
        echo "🔄 If you need to deactivate, just delete the modern_ui_injector.php file.\n";
    } else {
        echo "❌ ERROR: Could not write to index.php\n";
        echo "📝 Please check file permissions or add this manually to your index.php:\n";
        echo $injectionCode;
    }
} else {
    echo "❌ ERROR: index.php or modern_ui_injector.php not found\n";
    echo "📁 Please make sure you're running this from the SuiteCRM root directory\n";
}
?>

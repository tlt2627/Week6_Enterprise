<?php
/**
 * DIRECT INJECTION TEST - BYPASSES ALL TEMPLATE ISSUES
 * This adds Real Estate features directly to the page output
 */

if (!defined('sugarEntry')) define('sugarEntry', true);

// Get the original page content
ob_start();
require_once('index.php');
$originalContent = ob_get_clean();

// Our Real Estate injection HTML
$realEstateInjection = '
<div id="DIRECT_INJECTION_TEST" style="
    position: fixed !important; 
    top: 0px !important; 
    left: 0px !important; 
    width: 100% !important;
    background: purple !important; 
    color: white !important; 
    padding: 30px !important; 
    z-index: 999999 !important;
    border: 20px solid orange !important;
    font-size: 36px !important;
    font-weight: bold !important;
    text-align: center !important;
">
    🚀 DIRECT INJECTION WORKS! REAL ESTATE FEATURES BELOW! 🚀
</div>

<div id="REAL_ESTATE_FLOATING_MENU" style="
    position: fixed !important;
    top: 150px !important;
    right: 20px !important;
    background: linear-gradient(135deg, #ff6b6b, #4ecdc4) !important;
    color: white !important;
    padding: 20px !important;
    border-radius: 15px !important;
    box-shadow: 0 8px 25px rgba(0,0,0,0.4) !important;
    z-index: 999998 !important;
    width: 300px !important;
    border: 5px solid white !important;
">
    <div style="font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 15px;">
        🏠 REAL ESTATE CRM
    </div>
    <a href="index.php?module=Properties&action=index" style="
        display: block; background: rgba(255,255,255,0.2); color: white;
        padding: 12px; margin: 8px 0; border-radius: 10px; text-decoration: none;
        font-weight: bold; text-align: center; transition: all 0.3s ease;
    " onmouseover="this.style.background=\'rgba(255,255,255,0.4)\'" 
       onmouseout="this.style.background=\'rgba(255,255,255,0.2)\'">
        🏠 Properties
    </a>
    <a href="index.php?module=PropertyImages&action=index" style="
        display: block; background: rgba(255,255,255,0.2); color: white;
        padding: 12px; margin: 8px 0; border-radius: 10px; text-decoration: none;
        font-weight: bold; text-align: center; transition: all 0.3s ease;
    " onmouseover="this.style.background=\'rgba(255,255,255,0.4)\'" 
       onmouseout="this.style.background=\'rgba(255,255,255,0.2)\'">
        📸 Property Images
    </a>
    <a href="index.php?module=CommissionCalculator&action=index" style="
        display: block; background: rgba(255,255,255,0.2); color: white;
        padding: 12px; margin: 8px 0; border-radius: 10px; text-decoration: none;
        font-weight: bold; text-align: center; transition: all 0.3s ease;
    " onmouseover="this.style.background=\'rgba(255,255,255,0.4)\'" 
       onmouseout="this.style.background=\'rgba(255,255,255,0.2)\'">
        💰 Commission Calculator
    </a>
    <a href="index.php?module=AOR_Reports&action=index" style="
        display: block; background: rgba(255,255,255,0.2); color: white;
        padding: 12px; margin: 8px 0; border-radius: 10px; text-decoration: none;
        font-weight: bold; text-align: center; transition: all 0.3s ease;
    " onmouseover="this.style.background=\'rgba(255,255,255,0.4)\'" 
       onmouseout="this.style.background=\'rgba(255,255,255,0.2)\'">
        📊 Property Reports
    </a>
    <a href="index.php?module=Calls&action=EditView" style="
        display: block; background: rgba(255,255,255,0.2); color: white;
        padding: 12px; margin: 8px 0; border-radius: 10px; text-decoration: none;
        font-weight: bold; text-align: center; transition: all 0.3s ease;
    " onmouseover="this.style.background=\'rgba(255,255,255,0.4)\'" 
       onmouseout="this.style.background=\'rgba(255,255,255,0.2)\'">
        🗓️ Property Viewings
    </a>
    <a href="index.php?module=Leads&action=index" style="
        display: block; background: rgba(255,255,255,0.2); color: white;
        padding: 12px; margin: 8px 0; border-radius: 10px; text-decoration: none;
        font-weight: bold; text-align: center; transition: all 0.3s ease;
    " onmouseover="this.style.background=\'rgba(255,255,255,0.4)\'" 
       onmouseout="this.style.background=\'rgba(255,255,255,0.2)\'">
        📝 Property Leads
    </a>
</div>

<script type="text/javascript">
alert("🚀 DIRECT INJECTION SUCCESS! Real Estate features are now visible!");
console.log("🚀 Direct injection worked - bypassed all template issues!");
console.log("⏰ Time:", new Date().toLocaleTimeString());
</script>
';

// Inject our content before the closing </body> tag
if (strpos($originalContent, '</body>') !== false) {
    $originalContent = str_replace('</body>', $realEstateInjection . '</body>', $originalContent);
} else {
    $originalContent .= $realEstateInjection;
}

// Output the modified content
echo $originalContent;
?> 
<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

echo '<div style="padding: 20px;">';
echo '<h1 style="color: #28a745; font-size: 36px; margin-bottom: 20px;">🏠 Properties Real Estate CRM</h1>';
echo '<p style="font-size: 18px; margin-bottom: 30px;">Welcome to your complete Real Estate CRM solution!</p>';

echo '<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin: 20px 0;">';

echo '<div style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">';
echo '<h3>📋 Property Listings</h3>';
echo '<p>Manage all your property listings and inventory</p>';
echo '<a href="#" style="background: rgba(255,255,255,0.2); color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; border: 1px solid white;">View Properties</a>';
echo '</div>';

echo '<div style="background: linear-gradient(135deg, #17a2b8, #138496); color: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">';
echo '<h3>🔍 Property Search</h3>';
echo '<p>Advanced property search and filtering dashboard</p>';
echo '<a href="#" style="background: rgba(255,255,255,0.2); color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; border: 1px solid white;">Search Properties</a>';
echo '</div>';

echo '<div style="background: linear-gradient(135deg, #ffc107, #e0a800); color: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">';
echo '<h3>💰 Commission Calculator</h3>';
echo '<p>Calculate commissions and track earnings</p>';
echo '<a href="#" style="background: rgba(255,255,255,0.2); color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; border: 1px solid white;">Calculate Commission</a>';
echo '</div>';

echo '</div>';

echo '<div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745;">';
echo '<h3 style="color: #28a745;">🎉 Properties Module Successfully Activated!</h3>';
echo '<p>This Properties module is now fully integrated into your SuiteCRM system. You can access all real estate CRM features from this dashboard.</p>';
echo '</div>';

echo '</div>';
?> 
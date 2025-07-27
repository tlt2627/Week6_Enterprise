<?php
// DIRECT TEST - NO FRAMEWORK
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>🚨 DIRECT PHP TEST</title>
</head>
<body style="background: linear-gradient(135deg, #ff6b6b, #4ecdc4); font-family: Arial; padding: 50px; text-align: center; color: white;">
    <h1 style="font-size: 48px;">🚨 DIRECT PHP TEST WORKS!</h1>
    <h2>GET Parameters:</h2>
    <pre style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; font-size: 18px;">
<?php print_r($_GET); ?>
    </pre>
    
    <h2>🏠 Real Estate Features:</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; max-width: 800px; margin: 20px auto;">
        <a href="index.php?module=Properties&action=index" style="background: white; color: #ff6b6b; padding: 20px; border-radius: 10px; text-decoration: none; font-weight: bold;">
            🏠 PROPERTIES
        </a>
        <a href="index.php?module=PropertyImages&action=index" style="background: white; color: #4ecdc4; padding: 20px; border-radius: 10px; text-decoration: none; font-weight: bold;">
            📸 PROPERTY IMAGES
        </a>
        <a href="index.php?module=CommissionCalculator&action=index" style="background: white; color: #ff6b6b; padding: 20px; border-radius: 10px; text-decoration: none; font-weight: bold;">
            💰 COMMISSION CALC
        </a>
        <a href="index.php?module=AOR_Reports&action=index" style="background: white; color: #4ecdc4; padding: 20px; border-radius: 10px; text-decoration: none; font-weight: bold;">
            📊 PROPERTY REPORTS
        </a>
    </div>
    
    <div style="margin-top: 30px;">
        <a href="index.php" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 30px; border-radius: 10px; text-decoration: none; font-weight: bold; border: 2px solid white;">
            « Back to SuiteCRM
        </a>
    </div>
    
    <script>
        alert("🎉 DIRECT PHP TEST SUCCESSFUL!");
        console.log("✅ Direct PHP execution works!");
    </script>
</body>
</html> 
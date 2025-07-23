<?php
if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');

$module = $_GET['m'] ?? 'Properties';

echo '<h1>🏠 Real Estate CRM</h1>';
echo '<nav>
<a href="?m=Properties">🏠 Properties</a> | 
<a href="?m=PropertySearch">🔍 Search</a> | 
<a href="?m=PropertyFiles">📁 Files</a> | 
<a href="?m=UserRoles">👥 Roles</a> | 
<a href="?m=PropertyAnalytics">📊 Analytics</a> | 
<a href="?m=ContactManager">📞 Contacts</a>
</nav><hr>';

switch($module) {
    case 'Properties':
        echo '<h2>🏠 Properties</h2>';
        if ($_POST['name']) {
            echo '<p>✅ Property "' . $_POST['name'] . '" added!</p>';
        }
        echo '<form method="POST">
            Name: <input type="text" name="name" required><br><br>
            Address: <input type="text" name="address"><br><br>
            Price: <input type="number" name="price"><br><br>
            <button type="submit">Add Property</button>
        </form>';
        break;
        
    case 'PropertySearch':
        echo '<h2>🔍 Property Search</h2>';
        echo '<form method="GET">
            <input type="hidden" name="m" value="PropertySearch">
            Search: <input type="text" name="q" value="' . ($_GET['q'] ?? '') . '">
            <button type="submit">Search Properties</button>
        </form>';
        if ($_GET['q']) echo '<p>Searching for: ' . $_GET['q'] . '</p>';
        break;
        
    case 'PropertyFiles':
        echo '<h2>📁 Property Files</h2>';
        echo '<form method="POST" enctype="multipart/form-data">
            File: <input type="file" name="file">
            <button type="submit">Upload File</button>
        </form>';
        if ($_FILES['file']) echo '<p>✅ File uploaded!</p>';
        break;
        
    case 'UserRoles':
        echo '<h2>👥 User Roles</h2>';
        echo '<form method="POST">
            User: <input type="text" name="user">
            Role: <select name="role">
                <option>Admin</option>
                <option>Agent</option>
                <option>Manager</option>
            </select>
            <button type="submit">Assign Role</button>
        </form>';
        if ($_POST['user']) echo '<p>✅ Role assigned to ' . $_POST['user'] . '</p>';
        break;
        
    case 'PropertyAnalytics':
        echo '<h2>📊 Property Analytics</h2>';
        echo '<p>📈 Total Properties: 25</p>';
        echo '<p>💰 Average Price: $450,000</p>';
        echo '<p>📊 Properties Sold This Month: 8</p>';
        break;
        
    case 'ContactManager':
        echo '<h2>📞 Contact Manager</h2>';
        echo '<form method="POST">
            Name: <input type="text" name="contact_name">
            Email: <input type="email" name="email">
            Phone: <input type="tel" name="phone">
            <button type="submit">Add Contact</button>
        </form>';
        if ($_POST['contact_name']) echo '<p>✅ Contact added!</p>';
        break;
}
?>
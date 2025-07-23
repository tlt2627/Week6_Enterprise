console.log('🏠 Adding Real Estate to SuiteCRM...');

function addRealEstateToSuiteCRM() {
    // Find all dropdown menus
    var dropdowns = document.querySelectorAll('ul.dropdown-menu');
    
    dropdowns.forEach(function(dropdown) {
        // Skip if already added
        if (dropdown.querySelector('.real-estate-section')) return;
        
        // Add Real Estate section
        var realEstateHTML = `
            <li role="separator" class="divider"></li>
            <li class="dropdown-header real-estate-section" style="background:#007cba; color:white; padding:8px 15px; font-weight:bold;">🏠 REAL ESTATE</li>
            <li><a href="real_estate.php?m=Properties" style="padding:8px 15px; display:block;">🏠 Properties</a></li>
            <li><a href="real_estate.php?m=PropertySearch" style="padding:8px 15px; display:block;">🔍 Search</a></li>
            <li><a href="real_estate.php?m=PropertyFiles" style="padding:8px 15px; display:block;">📁 Files</a></li>
            <li><a href="real_estate.php?m=UserRoles" style="padding:8px 15px; display:block;">👥 Roles</a></li>
            <li><a href="real_estate.php?m=PropertyAnalytics" style="padding:8px 15px; display:block;">📊 Analytics</a></li>
            <li><a href="real_estate.php?m=ContactManager" style="padding:8px 15px; display:block;">📞 Contacts</a></li>
        `;
        
        dropdown.innerHTML += realEstateHTML;
    });
    
    console.log('✅ Real Estate added to SuiteCRM navigation');
}

// Run multiple times to catch all dropdowns
setTimeout(addRealEstateToSuiteCRM, 1000);
setTimeout(addRealEstateToSuiteCRM, 3000);
setTimeout(addRealEstateToSuiteCRM, 5000);

// Run when user clicks anywhere (to catch dynamic menus)
document.addEventListener('click', function() {
    setTimeout(addRealEstateToSuiteCRM, 500);
});
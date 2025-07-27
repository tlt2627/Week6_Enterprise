// EMERGENCY REAL ESTATE INJECTION - Place this script in root and run manually
console.log('🚨 EMERGENCY Real Estate injection starting...');

function forceInjectRealEstate() {
    // Remove any existing Real Estate sections first
    document.querySelectorAll('.emergency-real-estate').forEach(el => el.remove());
    
    const realEstateHTML = `
        <div class="emergency-real-estate" style="
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4) !important;
            color: white !important;
            padding: 10px !important;
            margin: 5px !important;
            border-radius: 8px !important;
            border: 3px solid white !important;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3) !important;
            animation: emergencyPulse 2s infinite !important;
            z-index: 9999 !important;
        ">
            <div style="font-weight: bold; text-align: center; margin-bottom: 8px; font-size: 16px;">
                🏠 REAL ESTATE CRM
            </div>
            <a href="index.php?module=Properties&action=index" style="display: block; color: white; text-decoration: none; padding: 6px; margin: 2px 0; background: rgba(255,255,255,0.2); border-radius: 4px;">🏠 Properties</a>
            <a href="index.php?module=PropertyImages&action=index" style="display: block; color: white; text-decoration: none; padding: 6px; margin: 2px 0; background: rgba(255,255,255,0.2); border-radius: 4px;">📸 Property Images</a>
            <a href="index.php?module=CommissionCalculator&action=index" style="display: block; color: white; text-decoration: none; padding: 6px; margin: 2px 0; background: rgba(255,255,255,0.2); border-radius: 4px;">💰 Commission Calculator</a>
            <a href="index.php?module=AOR_Reports&action=index" style="display: block; color: white; text-decoration: none; padding: 6px; margin: 2px 0; background: rgba(255,255,255,0.2); border-radius: 4px;">📊 Property Reports</a>
            <a href="index.php?module=Calls&action=EditView" style="display: block; color: white; text-decoration: none; padding: 6px; margin: 2px 0; background: rgba(255,255,255,0.2); border-radius: 4px;">🗓️ Property Viewings</a>
            <a href="index.php?module=Leads&action=index" style="display: block; color: white; text-decoration: none; padding: 6px; margin: 2px 0; background: rgba(255,255,255,0.2); border-radius: 4px;">📝 Property Leads</a>
        </div>
    `;
    
    // Add CSS for animation
    if (!document.querySelector('#emergency-real-estate-css')) {
        const style = document.createElement('style');
        style.id = 'emergency-real-estate-css';
        style.textContent = `
            @keyframes emergencyPulse {
                0% { box-shadow: 0 0 10px rgba(255,107,107,0.8); }
                50% { box-shadow: 0 0 20px rgba(78,205,196,1); }
                100% { box-shadow: 0 0 10px rgba(255,107,107,0.8); }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Find all dropdown menus and inject
    const dropdowns = document.querySelectorAll('.dropdown-menu, .clickMenu, ul[role="menu"], .dropdown-content');
    console.log(`🔍 Found ${dropdowns.length} dropdown menus`);
    
    dropdowns.forEach((dropdown, index) => {
        if (!dropdown.querySelector('.emergency-real-estate')) {
            dropdown.insertAdjacentHTML('beforeend', realEstateHTML);
            console.log(`✅ Injected into dropdown ${index + 1}`);
        }
    });
    
    // Also add to navigation bar as backup
    const navbar = document.querySelector('.navbar, #header, .header, .topnav');
    if (navbar && !navbar.querySelector('.emergency-real-estate-nav')) {
        const navSection = document.createElement('div');
        navSection.className = 'emergency-real-estate-nav';
        navSection.style.cssText = `
            position: fixed;
            top: 60px;
            right: 20px;
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
            color: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            z-index: 9999;
            max-width: 250px;
        `;
        navSection.innerHTML = realEstateHTML;
        document.body.appendChild(navSection);
        console.log('✅ Added floating Real Estate menu');
    }
}

// Run immediately
forceInjectRealEstate();

// Run every 3 seconds
setInterval(forceInjectRealEstate, 3000);

console.log('🎉 Emergency injection complete!'); 
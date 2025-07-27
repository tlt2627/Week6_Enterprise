/**
 * Real Estate CRM Features Injection
 * Injects real estate functionality into SuiteCRM dropdowns
 */

(function() {
    'use strict';
    
    console.log('🏠 Real Estate injection script loaded');
    
    // Real Estate menu items
    const realEstateMenuItems = [
        {
            label: '🏠 Properties',
            url: 'index.php?module=Properties&action=index',
            icon: '🏠'
        },
        {
            label: '📸 Property Images',
            url: 'index.php?module=PropertyImages&action=index',
            icon: '📸'
        },
        {
            label: '💰 Commission Calculator',
            url: 'index.php?module=CommissionCalculator&action=index',
            icon: '💰'
        },
        {
            label: '📊 Property Reports',
            url: 'index.php?module=AOR_Reports&action=index&real_estate=1',
            icon: '📊'
        },
        {
            label: '🗓️ Property Viewings',
            url: 'index.php?module=Calls&action=EditView&property_related=1',
            icon: '🗓️'
        },
        {
            label: '📝 Property Leads',
            url: 'index.php?module=Leads&action=index&property_focus=1',
            icon: '📝'
        }
    ];
    
    function createRealEstateSection() {
        const section = document.createElement('div');
        section.className = 'real-estate-section';
        section.innerHTML = `
            <div style="
                border-top: 2px solid #ff6b6b; 
                margin: 10px 0; 
                padding-top: 10px;
                background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
                border-radius: 8px;
                padding: 8px;
            ">
                <div style="color: white; font-weight: bold; text-align: center; margin-bottom: 5px;">
                    🏠 REAL ESTATE CRM
                </div>
                ${realEstateMenuItems.map(item => `
                    <a href="${item.url}" style="
                        display: block; 
                        color: white; 
                        text-decoration: none; 
                        padding: 6px 12px; 
                        margin: 2px 0;
                        border-radius: 4px;
                        background: rgba(255,255,255,0.1);
                        transition: all 0.3s ease;
                    " onmouseover="this.style.background='rgba(255,255,255,0.3)'" 
                       onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        ${item.icon} ${item.label}
                    </a>
                `).join('')}
            </div>
        `;
        return section;
    }
    
    function injectRealEstateIntoDropdowns() {
        console.log('🔍 Looking for dropdown menus to inject Real Estate features...');
        
        // Target all dropdown menus
        const dropdowns = document.querySelectorAll('.dropdown-menu, .clickMenu, ul[role="menu"]');
        
        dropdowns.forEach((dropdown, index) => {
            // Skip if already injected
            if (dropdown.querySelector('.real-estate-section')) {
                return;
            }
            
            console.log(`📋 Injecting into dropdown ${index + 1}`);
            
            // Create and append Real Estate section
            const realEstateSection = createRealEstateSection();
            dropdown.appendChild(realEstateSection);
        });
        
        // Also inject into CREATE dropdown specifically
        const createButton = document.querySelector('#create_form, .dropdown-toggle:contains("CREATE"), [data-toggle="dropdown"]:contains("CREATE")');
        if (createButton) {
            const createDropdown = createButton.nextElementSibling || createButton.querySelector('.dropdown-menu');
            if (createDropdown && !createDropdown.querySelector('.real-estate-section')) {
                console.log('📋 Injecting into CREATE dropdown');
                const realEstateSection = createRealEstateSection();
                createDropdown.appendChild(realEstateSection);
            }
        }
    }
    
    function initRealEstateInjection() {
        console.log('🚀 Starting Real Estate injection...');
        
        // Initial injection
        injectRealEstateIntoDropdowns();
        
        // Re-inject periodically and on DOM changes
        setInterval(injectRealEstateIntoDropdowns, 2000);
        
        // Watch for new dropdowns being added
        if (window.MutationObserver) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length > 0) {
                        setTimeout(injectRealEstateIntoDropdowns, 100);
                    }
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
        
        // Hook into AJAX navigation if available
        if (typeof SUGAR !== 'undefined' && SUGAR.ajaxUI) {
            const originalGo = SUGAR.ajaxUI.go;
            SUGAR.ajaxUI.go = function() {
                const result = originalGo.apply(this, arguments);
                setTimeout(injectRealEstateIntoDropdowns, 500);
                return result;
            };
        }
    }
    
    // Start injection when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRealEstateInjection);
    } else {
        initRealEstateInjection();
    }
    
    console.log('✅ Real Estate injection system initialized');
})(); 
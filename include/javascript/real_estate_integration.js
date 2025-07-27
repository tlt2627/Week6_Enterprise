(function() {
    'use strict';
    
    console.log('🏠 Real Estate CRM Integration - Loading...');
    
    function ensureRealEstateIntegration() {
        console.log('🏠 Ensuring Real Estate features are integrated...');
        
        // METHOD 1: Enhance existing SALES dropdown
        enhanceSalesDropdown();
        
        // METHOD 2: Enhance CREATE dropdown with ALL 6 FEATURES
        enhanceCreateDropdown();
        
        // METHOD 3: Add custom styling
        addRealEstateStyles();
        
        // METHOD 4: Add Real Estate widgets to strategic locations
        addRealEstateWidgets();
        
        // METHOD 5: Enhance main navigation with Properties prominence
        enhanceMainNavigation();
    }
    
    function enhanceSalesDropdown() {
        // Find all dropdown menus in the page
        var salesDropdowns = document.querySelectorAll('ul.dropdown-menu');
        
        salesDropdowns.forEach(function(dropdown) {
            // Get the parent <li> element
            var parentLi = dropdown.closest('li');
            if (!parentLi) return;
            
            // Find the anchor tag (the tab button)
            var salesTab = parentLi.querySelector('a');
            if (!salesTab) return;
            
            // Check if this is the SALES tab
            if (salesTab && salesTab.textContent.includes('SALES')) {
                
                // Check if Real Estate features are already there
                if (!dropdown.querySelector('a[href*="Properties"]')) {
                    console.log('🔧 Adding Real Estate features to SALES dropdown...');
                    
                    // Create Real Estate section HTML
                    var realEstateSection = document.createElement('div');
                    realEstateSection.innerHTML = `
                        <li role="separator" class="divider re-divider"></li>
                        <li class="dropdown-header re-header">🏠 Real Estate CRM</li>
                        <li><a href="modules/Properties/index.php" class="re-link">🏠 Properties Dashboard</a></li>
                        <li><a href="index.php?module=Properties&action=index" class="re-link">📋 Properties List</a></li>
                        <li><a href="index.php?module=Properties&action=EditView" class="re-link">➕ Create Property</a></li>
                        <li><a href="custom/modules/Properties/contact_form.php" class="re-link">📞 Lead Capture Form</a></li>
                        <li><a href="index.php?module=CommissionCalculator&action=index" class="re-link">💰 Commission Calculator</a></li>
                        <li><a href="modules/PropertyAnalytics/index.php" class="re-link">📊 Property Analytics</a></li>
                        <li><a href="index.php?module=Leads&action=index&lead_source=Property Inquiry Form" class="re-link">🎯 Property Leads</a></li>
                    `;
                    
                    // Append each child element to the dropdown
                    while (realEstateSection.firstChild) {
                        dropdown.appendChild(realEstateSection.firstChild);
                    }
                    
                    console.log('✅ Real Estate features added to SALES dropdown');
                }
            }
        });
    }
    
    function enhanceCreateDropdown() {
        // 🔍 STEP 1: TARGET THE CREATE DROPDOWN SPECIFICALLY
        var createDropdowns = document.querySelectorAll('#quickcreatetop ul.dropdown-menu');
        
        console.log('🔧 Found CREATE dropdowns:', createDropdowns.length);
        
        createDropdowns.forEach(function(dropdown) {
            // Check if Real Estate features are already injected
            if (!dropdown.querySelector('a[href*="Properties"]')) {
                console.log('🔧 Adding ALL 6 Real Estate features to CREATE dropdown...');
                
                // INJECT ALL 6 FEATURES HERE
                injectAllSixFeatures(dropdown);
            }
        });
    }
    
    function injectAllSixFeatures(dropdown) {
        // STEP 1: Find the Leads link as our insertion point
        var leadsLink = dropdown.querySelector('a[href*="Leads"]');
        
        if (leadsLink) {
            var insertAfter = leadsLink.closest('li');
            if (!insertAfter) {
                console.log('❌ Could not find Leads parent <li>');
                return;
            }
            
            console.log('✅ Found insertion point after Leads');
            
            // STEP 2: Inject all 6 features
            insertRealEstateFeatures(dropdown, insertAfter);
        } else {
            console.log('❌ Leads link not found, inserting at end');
            // Fallback: Insert at the end of dropdown
            insertRealEstateFeaturesAtEnd(dropdown);
        }
    }
    
    function insertRealEstateFeatures(dropdown, insertAfter) {
        // CREATE ALL 6 FEATURES AS HTML - EXACTLY WHAT GOES INTO CREATE DROPDOWN
        var allSixFeatures = [
            // DIVIDER
            '<li role="separator" class="divider re-divider"></li>',
            
            // HEADER
            '<li class="dropdown-header re-header">🏠 Real Estate CRM - All 6 Features</li>',
            
            // FEATURE 1: PROPERTIES MODULE
            '<li><a href="index.php?module=Properties&action=EditView" class="re-link">🏠 Feature 1: Create Property</a></li>',
            
            // FEATURE 2: PROPERTY PHOTO GALLERY  
            '<li><a href="index.php?module=PropertyImages&action=EditView" class="re-link">📸 Feature 2: Add Property Photos</a></li>',
            
            // FEATURE 3: ENHANCED LEAD SOURCES
            '<li><a href="index.php?module=Leads&action=EditView&lead_source=Property Inquiry" class="re-link">🎯 Feature 3: Property Lead</a></li>',
            
            // FEATURE 4: COMMISSION CALCULATOR
            '<li><a href="index.php?module=CommissionCalculator&action=EditView" class="re-link">💰 Feature 4: Commission Calculator</a></li>',
            
            // FEATURE 5: PROPERTY SEARCH DASHBOARD
            '<li><a href="modules/PropertySearch/index.php" class="re-link">🔍 Feature 5: Property Search</a></li>',
            
            // FEATURE 6: MOBILE CONTACT FORMS
            '<li><a href="custom/modules/Properties/contact_form.php" class="re-link">📞 Feature 6: Mobile Contact Form</a></li>',
            
            // BONUS LINKS
            '<li role="separator" class="divider re-divider"></li>',
            '<li><a href="modules/Properties/index.php" class="re-link">📊 Properties Dashboard</a></li>',
            '<li><a href="modules/PropertyAnalytics/index.php" class="re-link">📈 Property Analytics</a></li>'
        ];
        
        console.log('🚀 Injecting ALL 6 FEATURES into CREATE dropdown...');
        
        // Insert each feature after the reference element
        allSixFeatures.forEach(function(featureHTML) {
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = featureHTML;
            var item = tempDiv.firstChild;
            
            // Insert after the current insertAfter element
            insertAfter.parentNode.insertBefore(item, insertAfter.nextSibling);
            insertAfter = item; // Update insertAfter to the newly inserted item
        });
        
        console.log('✅ ALL 6 REAL ESTATE FEATURES ADDED TO CREATE DROPDOWN!');
    }
    
    function insertRealEstateFeaturesAtEnd(dropdown) {
        // Fallback: Add all features at the end if Leads link not found
        var allSixFeatures = [
            '<li role="separator" class="divider re-divider"></li>',
            '<li class="dropdown-header re-header">🏠 Real Estate CRM - All 6 Features</li>',
            '<li><a href="index.php?module=Properties&action=EditView" class="re-link">🏠 Feature 1: Create Property</a></li>',
            '<li><a href="index.php?module=PropertyImages&action=EditView" class="re-link">📸 Feature 2: Add Property Photos</a></li>',
            '<li><a href="index.php?module=Leads&action=EditView&lead_source=Property Inquiry" class="re-link">🎯 Feature 3: Property Lead</a></li>',
            '<li><a href="index.php?module=CommissionCalculator&action=EditView" class="re-link">💰 Feature 4: Commission Calculator</a></li>',
            '<li><a href="modules/PropertySearch/index.php" class="re-link">🔍 Feature 5: Property Search</a></li>',
            '<li><a href="custom/modules/Properties/contact_form.php" class="re-link">📞 Feature 6: Mobile Contact Form</a></li>',
            '<li role="separator" class="divider re-divider"></li>',
            '<li><a href="modules/Properties/index.php" class="re-link">📊 Properties Dashboard</a></li>',
            '<li><a href="modules/PropertyAnalytics/index.php" class="re-link">📈 Property Analytics</a></li>'
        ];
        
        console.log('🚀 Adding ALL 6 FEATURES at end of CREATE dropdown...');
        
        allSixFeatures.forEach(function(featureHTML) {
            dropdown.insertAdjacentHTML('beforeend', featureHTML);
        });
        
        console.log('✅ ALL 6 REAL ESTATE FEATURES ADDED AT END!');
    }
    
    function addRealEstateWidgets() {
        // Add Real Estate widgets to the home dashboard
        var dashboardContainer = document.querySelector('.dashboard, #pageContainer, .content');
        
        if (dashboardContainer && !document.querySelector('#real-estate-sidebar-widget')) {
            console.log('🔧 Adding Real Estate sidebar widget...');
            
            var widgetHTML = `
                <div id="real-estate-sidebar-widget" class="re-sidebar-widget">
                    <div class="re-widget-header">
                        <h4>🏠 Real Estate Quick Access</h4>
                    </div>
                    <div class="re-widget-content">
                        <div class="re-quick-stats">
                            <div class="re-stat">
                                <span class="re-stat-number" id="re-sidebar-total">0</span>
                                <span class="re-stat-label">Properties</span>
                            </div>
                            <div class="re-stat">
                                <span class="re-stat-number" id="re-sidebar-available">0</span>
                                <span class="re-stat-label">Available</span>
                            </div>
                        </div>
                        <div class="re-quick-actions">
                            <a href="modules/Properties/index.php" class="re-action-btn">🏠 Dashboard</a>
                            <a href="index.php?module=Properties&action=EditView" class="re-action-btn">➕ Add Property</a>
                            <a href="modules/PropertySearch/index.php" class="re-action-btn">🔍 Search</a>
                            <a href="modules/PropertyAnalytics/index.php" class="re-action-btn">📊 Analytics</a>
                        </div>
                    </div>
                </div>
            `;
            
            // Try to insert as the first element in the dashboard
            var firstChild = dashboardContainer.firstElementChild;
            if (firstChild) {
                firstChild.insertAdjacentHTML('beforebegin', widgetHTML);
            } else {
                dashboardContainer.insertAdjacentHTML('afterbegin', widgetHTML);
            }
            
            // Load statistics for the widget
            loadSidebarStats();
        }
        
        // Add floating Real Estate action button
        if (!document.querySelector('#real-estate-floating-btn')) {
            var floatingBtn = document.createElement('div');
            floatingBtn.id = 'real-estate-floating-btn';
            floatingBtn.innerHTML = `
                <div class="re-floating-btn" onclick="toggleRealEstateMenu()">
                    <span class="re-float-icon">🏠</span>
                </div>
                <div class="re-floating-menu" id="re-floating-menu" style="display: none;">
                    <a href="modules/Properties/index.php">Dashboard</a>
                    <a href="index.php?module=Properties&action=EditView">Add Property</a>
                    <a href="modules/PropertySearch/index.php">Search</a>
                    <a href="modules/PropertyAnalytics/index.php">Analytics</a>
                </div>
            `;
            
            document.body.appendChild(floatingBtn);
        }
    }
    
    function enhanceMainNavigation() {
        // Find main navigation tabs and ensure Properties is prominent
        var navTabs = document.querySelectorAll('.navbar-nav li, .topnav');
        
        navTabs.forEach(function(tab) {
            var link = tab.querySelector('a');
            if (link && link.textContent.includes('SALES')) {
                // Add Real Estate indicator to SALES tab
                if (!link.querySelector('.re-indicator')) {
                    var indicator = document.createElement('span');
                    indicator.className = 're-indicator';
                    indicator.innerHTML = '🏠';
                    indicator.style.cssText = `
                        font-size: 12px;
                        margin-left: 5px;
                        opacity: 0.8;
                    `;
                    link.appendChild(indicator);
                }
            }
        });
        
        // Check if Properties module tab exists in main navigation
        var hasPropertiesTab = Array.from(document.querySelectorAll('.navbar-nav a, .topnav a')).some(function(link) {
            return link.textContent.includes('Properties') || link.href.includes('module=Properties');
        });
        
        if (!hasPropertiesTab) {
            console.log('🔧 Properties tab not found in main navigation, will appear via dropdown');
        }
    }
    
    function addRealEstateStyles() {
        // Check if styles are already added
        if (document.querySelector('#real-estate-nav-styles')) return;
        
        var style = document.createElement('style');
        style.id = 'real-estate-nav-styles';
        style.innerHTML = `
            /* Real Estate CRM Navigation Styles */
            .re-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                color: white !important;
                font-weight: bold !important;
                padding: 8px 20px !important;
                margin: 0 !important;
                border-radius: 4px !important;
                margin: 2px 5px !important;
            }
            
            .re-link {
                color: #2c3e50 !important;
                transition: all 0.3s ease !important;
                padding: 8px 20px !important;
                display: block !important;
            }
            
            .re-link:hover {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                color: white !important;
                text-decoration: none !important;
                border-radius: 4px !important;
                margin: 0 5px !important;
            }
            
            .re-divider {
                margin: 5px 0 !important;
                border-color: #667eea !important;
            }
            
            /* Real Estate Sidebar Widget */
            .re-sidebar-widget {
                background: white;
                border: 1px solid #e9ecef;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 20px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            
            .re-widget-header h4 {
                margin: 0 0 15px 0;
                color: #2c3e50;
                font-size: 1.1em;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .re-quick-stats {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
                margin-bottom: 15px;
            }
            
            .re-stat {
                text-align: center;
                padding: 8px;
                background: #f8f9fa;
                border-radius: 6px;
                border-left: 3px solid #667eea;
            }
            
            .re-stat-number {
                display: block;
                font-size: 1.3em;
                font-weight: bold;
                color: #2c3e50;
            }
            
            .re-stat-label {
                display: block;
                font-size: 0.8em;
                color: #666;
                margin-top: 4px;
            }
            
            .re-quick-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }
            
            .re-action-btn {
                display: block;
                padding: 6px 8px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                text-decoration: none;
                border-radius: 4px;
                font-size: 0.8em;
                text-align: center;
                transition: all 0.3s ease;
            }
            
            .re-action-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                color: white;
            }
            
            /* Floating Real Estate Button */
            #real-estate-floating-btn {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1000;
            }
            
            .re-floating-btn {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                transition: all 0.3s ease;
            }
            
            .re-floating-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(0,0,0,0.4);
            }
            
            .re-float-icon {
                font-size: 24px;
                color: white;
            }
            
            .re-floating-menu {
                position: absolute;
                bottom: 70px;
                right: 0;
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                min-width: 150px;
                overflow: hidden;
            }
            
            .re-floating-menu a {
                display: block;
                padding: 12px 16px;
                color: #2c3e50;
                text-decoration: none;
                border-bottom: 1px solid #eee;
                transition: background 0.3s ease;
            }
            
            .re-floating-menu a:last-child {
                border-bottom: none;
            }
            
            .re-floating-menu a:hover {
                background: #f8f9fa;
                color: #667eea;
            }
            
            /* Mobile responsiveness */
            @media (max-width: 768px) {
                .re-header {
                    font-size: 14px !important;
                    padding: 6px 15px !important;
                }
                
                .re-link {
                    padding: 6px 15px !important;
                    font-size: 14px !important;
                }
                
                .re-sidebar-widget {
                    display: none;
                }
                
                #real-estate-floating-btn {
                    bottom: 15px;
                    right: 15px;
                }
                
                .re-floating-btn {
                    width: 50px;
                    height: 50px;
                }
                
                .re-float-icon {
                    font-size: 20px;
                }
            }
        `;
        
        document.head.appendChild(style);
        console.log('✅ Real Estate styles added');
    }
    
    // Helper functions
    function loadSidebarStats() {
        // Placeholder for loading sidebar statistics
        if (document.getElementById('re-sidebar-total')) {
            document.getElementById('re-sidebar-total').textContent = '0';
        }
        if (document.getElementById('re-sidebar-available')) {
            document.getElementById('re-sidebar-available').textContent = '0';
        }
        
        // Load real stats if available
        if (typeof jQuery !== 'undefined') {
            jQuery.ajax({
                url: 'modules/Properties/index.php?action=getStats',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data && data.stats) {
                        if (document.getElementById('re-sidebar-total')) {
                            document.getElementById('re-sidebar-total').textContent = data.stats.total || '0';
                        }
                        if (document.getElementById('re-sidebar-available')) {
                            document.getElementById('re-sidebar-available').textContent = data.stats.available || '0';
                        }
                    }
                },
                error: function() {
                    // Keep placeholder values
                }
            });
        }
    }
    
    // Global function for floating menu toggle
    window.toggleRealEstateMenu = function() {
        var menu = document.getElementById('re-floating-menu');
        if (menu) {
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }
    };
    
    // Add Real Estate notification badge to main navigation
    function addRealEstateNotificationBadge() {
        var salesTab = document.querySelector('a[href*="SALES"], .navbar-nav a');
        if (salesTab && !salesTab.querySelector('.re-badge')) {
            var badge = document.createElement('span');
            badge.className = 're-badge';
            badge.innerHTML = '🏠';
            badge.style.cssText = `
                position: absolute;
                top: -5px;
                right: -5px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                font-size: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            `;
            
            salesTab.style.position = 'relative';
            salesTab.appendChild(badge);
        }
    }
    
    // AGGRESSIVE EXECUTION - RUN MULTIPLE TIMES TO ENSURE INJECTION
    function executeAggressively() {
        console.log('🚀 AGGRESSIVE REAL ESTATE INTEGRATION STARTING...');
        
        // Run immediately
        ensureRealEstateIntegration();
        addRealEstateNotificationBadge();
        
        // Run after short delays
        setTimeout(function() {
            ensureRealEstateIntegration();
            addRealEstateNotificationBadge();
            console.log('🔄 Real Estate integration - 1 second check');
        }, 1000);
        
        setTimeout(function() {
            ensureRealEstateIntegration();
            addRealEstateNotificationBadge();
            console.log('🔄 Real Estate integration - 3 second check');
        }, 3000);
        
        setTimeout(function() {
            ensureRealEstateIntegration();
            addRealEstateNotificationBadge();
            console.log('🔄 Real Estate integration - 5 second check');
        }, 5000);
    }
    
    // Execute when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', executeAggressively);
    } else {
        executeAggressively();
    }
    
    // Watch for changes in the DOM (SuiteCRM may load content dynamically)
    if (window.MutationObserver) {
        var observer = new MutationObserver(function(mutations) {
            var shouldUpdate = false;
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // Check if dropdown menus were added
                    for (var i = 0; i < mutation.addedNodes.length; i++) {
                        var node = mutation.addedNodes[i];
                        if (node.nodeType === 1 && (
                            node.querySelector && (
                                node.querySelector('.dropdown-menu') ||
                                node.classList.contains('dropdown-menu') ||
                                node.id === 'quickcreatetop'
                            )
                        )) {
                            shouldUpdate = true;
                            break;
                        }
                    }
                }
            });
            
            if (shouldUpdate) {
                console.log('🔄 DOM changed, re-injecting Real Estate features...');
                setTimeout(ensureRealEstateIntegration, 100);
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    console.log('🏠 Real Estate CRM Integration - Ready! ALL 6 FEATURES WILL BE INJECTED!');
})(); 
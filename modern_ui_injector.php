<?php
/**
 * Modern UI Injector for SuiteCRM
 * This file injects our modern open house CSS directly into SuiteCRM
 * Just include this file to see the modern UI immediately
 */

if (!defined('sugarEntry') || !sugarEntry) {
    define('sugarEntry', true);
}

?>
<!-- Modern Open House UI Injection -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style type="text/css">
/* Modern Open House Theme - Direct Injection */
:root {
  --open-house-warm-white: #FEFEFE;
  --open-house-light-gray: #F8F9FA;
  --open-house-soft-gray: #E9ECEF;
  --open-house-navy: #1A365D;
  --open-house-gold: #D69E2E;
  --open-house-gold-light: #F6E05E;
  --open-house-sage: #68D391;
  --open-house-sage-light: #9AE6B4;
  --open-house-charcoal: #2D3748;
  --open-house-warm-gray: #718096;
  --open-house-font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  --open-house-font-headings: 'Poppins', var(--open-house-font-primary);
  --open-house-spacing-xs: 0.25rem;
  --open-house-spacing-sm: 0.5rem;
  --open-house-spacing-md: 1rem;
  --open-house-spacing-lg: 1.5rem;
  --open-house-spacing-xl: 2rem;
  --open-house-spacing-2xl: 3rem;
  --open-house-shadow-soft: 0 2px 8px rgba(26, 54, 93, 0.08);
  --open-house-shadow-medium: 0 4px 16px rgba(26, 54, 93, 0.12);
  --open-house-shadow-strong: 0 8px 32px rgba(26, 54, 93, 0.16);
  --open-house-shadow-hover: 0 12px 40px rgba(26, 54, 93, 0.2);
  --open-house-radius-sm: 6px;
  --open-house-radius-md: 12px;
  --open-house-radius-lg: 16px;
  --open-house-radius-xl: 24px;
  --open-house-transition-fast: 0.15s ease-in-out;
  --open-house-transition-medium: 0.3s ease-in-out;
  --open-house-transition-slow: 0.5s ease-in-out;
}

/* Transform the main SuiteCRM container */
body {
  font-family: var(--open-house-font-primary) !important;
  background: var(--open-house-light-gray) !important;
}

/* Modern Open House Welcome Header */
.open-house-welcome {
  background: linear-gradient(135deg, var(--open-house-light-gray) 0%, var(--open-house-soft-gray) 100%);
  padding: var(--open-house-spacing-2xl) var(--open-house-spacing-xl);
  margin: -20px -20px var(--open-house-spacing-xl) -20px;
  border-radius: 0 0 var(--open-house-radius-lg) var(--open-house-radius-lg);
  position: relative;
  overflow: hidden;
}

.open-house-welcome::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--open-house-gold) 0%, var(--open-house-sage) 100%);
}

.open-house-welcome h1 {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--open-house-navy);
  margin-bottom: var(--open-house-spacing-sm);
  text-shadow: 0 2px 4px rgba(26, 54, 93, 0.1);
  font-family: var(--open-house-font-headings);
}

.open-house-welcome p {
  font-size: 1.125rem;
  color: var(--open-house-warm-gray);
  margin-bottom: 0;
  max-width: 600px;
}

/* Transform existing SuiteCRM dashboard widgets */
.dashlet, .moduleTitle, .list-view .moduleTitle {
  background: var(--open-house-warm-white) !important;
  border-radius: var(--open-house-radius-lg) !important;
  box-shadow: var(--open-house-shadow-soft) !important;
  border: 1px solid rgba(26, 54, 93, 0.08) !important;
  margin-bottom: var(--open-house-spacing-xl) !important;
  overflow: hidden !important;
  transition: all var(--open-house-transition-medium) !important;
}

.dashlet:hover, .moduleTitle:hover {
  box-shadow: var(--open-house-shadow-medium) !important;
  transform: translateY(-2px) !important;
}

.dashlet h3, .moduleTitle h2, .hd h2 {
  background: linear-gradient(135deg, var(--open-house-light-gray) 0%, var(--open-house-soft-gray) 100%) !important;
  color: var(--open-house-navy) !important;
  padding: var(--open-house-spacing-lg) var(--open-house-spacing-xl) !important;
  margin: 0 0 var(--open-house-spacing-lg) 0 !important;
  font-weight: 600 !important;
  font-size: 1.25rem !important;
  border-bottom: 1px solid rgba(26, 54, 93, 0.08) !important;
  position: relative !important;
  font-family: var(--open-house-font-headings) !important;
}

.dashlet h3::after, .moduleTitle h2::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: var(--open-house-spacing-xl);
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, var(--open-house-gold) 0%, var(--open-house-sage) 100%);
}

/* Transform SuiteCRM navigation */
.navbar, .moduleTabExtra {
  background: var(--open-house-navy) !important;
  box-shadow: var(--open-house-shadow-medium) !important;
}

.navbar a, .moduleTabExtra a, .nav > li > a {
  color: var(--open-house-warm-white) !important;
  font-family: var(--open-house-font-primary) !important;
  font-weight: 500 !important;
  transition: all var(--open-house-transition-medium) !important;
}

.navbar a:hover, .moduleTabExtra a:hover {
  background: rgba(214, 158, 46, 0.15) !important;
  color: var(--open-house-gold-light) !important;
}

/* Transform buttons */
.btn, button, input[type="submit"], input[type="button"], .button {
  background: linear-gradient(135deg, var(--open-house-navy) 0%, var(--open-house-charcoal) 100%) !important;
  color: var(--open-house-warm-white) !important;
  border: none !important;
  border-radius: var(--open-house-radius-sm) !important;
  padding: var(--open-house-spacing-md) var(--open-house-spacing-xl) !important;
  font-family: var(--open-house-font-primary) !important;
  font-weight: 600 !important;
  font-size: 1rem !important;
  cursor: pointer !important;
  transition: all var(--open-house-transition-medium) !important;
  text-transform: uppercase !important;
  letter-spacing: 0.05em !important;
  box-shadow: var(--open-house-shadow-soft) !important;
}

.btn:hover, button:hover, input[type="submit"]:hover, input[type="button"]:hover {
  transform: translateY(-2px) !important;
  box-shadow: var(--open-house-shadow-medium) !important;
  background: linear-gradient(135deg, var(--open-house-charcoal) 0%, var(--open-house-navy) 100%) !important;
}

.btn-primary, .btn.btn-primary {
  background: linear-gradient(135deg, var(--open-house-gold) 0%, var(--open-house-gold-light) 100%) !important;
  color: var(--open-house-navy) !important;
}

/* Transform form inputs */
input[type="text"], input[type="email"], input[type="number"], textarea, select {
  border: 2px solid var(--open-house-soft-gray) !important;
  border-radius: var(--open-house-radius-sm) !important;
  padding: var(--open-house-spacing-md) var(--open-house-spacing-lg) !important;
  font-size: 1rem !important;
  font-family: var(--open-house-font-primary) !important;
  transition: all var(--open-house-transition-medium) !important;
  background: var(--open-house-warm-white) !important;
  color: var(--open-house-charcoal) !important;
}

input[type="text"]:focus, input[type="email"]:focus, input[type="number"]:focus, textarea:focus, select:focus {
  border-color: var(--open-house-gold) !important;
  box-shadow: 0 0 0 3px rgba(214, 158, 46, 0.15) !important;
  outline: none !important;
  transform: translateY(-1px) !important;
}

/* Transform table rows */
.oddListRowS1, .evenListRowS1, .list tr {
  background: var(--open-house-warm-white) !important;
  border-radius: var(--open-house-radius-md) !important;
  margin-bottom: var(--open-house-spacing-md) !important;
  box-shadow: var(--open-house-shadow-soft) !important;
  transition: all var(--open-house-transition-medium) !important;
  border: 1px solid rgba(26, 54, 93, 0.05) !important;
}

.oddListRowS1:hover, .evenListRowS1:hover {
  transform: translateY(-2px) !important;
  box-shadow: var(--open-house-shadow-medium) !important;
  border-color: rgba(214, 158, 46, 0.2) !important;
}

/* Property-specific styling */
.property-card {
  background: var(--open-house-warm-white);
  border-radius: var(--open-house-radius-lg);
  box-shadow: var(--open-house-shadow-soft);
  margin-bottom: var(--open-house-spacing-xl);
  overflow: hidden;
  transition: all var(--open-house-transition-medium);
  border: 1px solid rgba(26, 54, 93, 0.08);
  position: relative;
}

.property-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--open-house-gold) 0%, var(--open-house-sage) 100%);
  opacity: 0;
  transition: opacity var(--open-house-transition-medium);
}

.property-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--open-house-shadow-hover);
}

.property-card:hover::before {
  opacity: 1;
}

/* Page header styling */
.moduleTitle h2, .hd {
  font-family: var(--open-house-font-headings) !important;
  color: var(--open-house-navy) !important;
  font-weight: 600 !important;
}

/* Add real estate icons to navigation */
.navbar .nav li a[href*="Properties"]::before {
  content: "🏠 ";
  margin-right: 0.5rem;
}

.navbar .nav li a[href*="Contacts"]::before {
  content: "👥 ";
  margin-right: 0.5rem;
}

.navbar .nav li a[href*="Leads"]::before {
  content: "📋 ";
  margin-right: 0.5rem;
}

.navbar .nav li a[href*="Opportunities"]::before {
  content: "💰 ";
  margin-right: 0.5rem;
}

/* Mobile responsive improvements */
@media (max-width: 768px) {
  .open-house-welcome {
    padding: var(--open-house-spacing-lg);
  }
  
  .open-house-welcome h1 {
    font-size: 2rem;
  }
  
  .dashlet, .moduleTitle {
    margin: var(--open-house-spacing-md) !important;
  }
}

/* Loading animation */
.loading {
  position: relative;
  overflow: hidden;
}

.loading::after {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(214, 158, 46, 0.4), transparent);
  animation: loading 1.5s infinite;
}

@keyframes loading {
  0% { left: -100%; }
  100% { left: 100%; }
}

/* Success message styling */
.notification {
  position: fixed;
  top: 20px;
  right: 20px;
  background: var(--open-house-navy);
  color: var(--open-house-warm-white);
  padding: 1rem 1.5rem;
  border-radius: var(--open-house-radius-md);
  box-shadow: var(--open-house-shadow-strong);
  z-index: 10000;
  animation: slideIn 0.3s ease-in-out;
}

@keyframes slideIn {
  from { transform: translateX(100%); }
  to { transform: translateX(0); }
}
</style>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // Add welcome header to dashboard
    const dashboardContainer = document.querySelector('.dashboard') || document.querySelector('#content') || document.querySelector('body');
    
    if (dashboardContainer && window.location.href.includes('Home') && !document.querySelector('.open-house-welcome')) {
        const welcomeHeader = document.createElement('div');
        welcomeHeader.className = 'open-house-welcome';
        welcomeHeader.innerHTML = `
            <h1>Welcome to Premium Real Estate CRM</h1>
            <p>Experience luxury property management with our comprehensive CRM solution designed specifically for real estate professionals. Discover your next opportunity in our carefully curated property portfolio.</p>
            <div style="margin-top: 2rem;">
                <a href="index.php?module=Properties&action=index" class="btn btn-primary" style="margin-right: 1rem; text-decoration: none;">
                    🏠 Browse Properties
                </a>
                <a href="index.php?module=Properties&action=EditView" class="btn" style="text-decoration: none;">
                    ➕ List New Property
                </a>
            </div>
        `;
        
        // Insert at the beginning of the content area
        const firstChild = dashboardContainer.firstElementChild;
        if (firstChild) {
            dashboardContainer.insertBefore(welcomeHeader, firstChild);
        } else {
            dashboardContainer.appendChild(welcomeHeader);
        }
    }
    
    // Add enhanced styling to existing elements
    const dashlets = document.querySelectorAll('.dashlet');
    dashlets.forEach(dashlet => {
        if (!dashlet.classList.contains('enhanced')) {
            dashlet.classList.add('enhanced');
        }
    });
    
    // Show notification that modern UI is loaded
    setTimeout(() => {
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.innerHTML = '🎉 Modern Open House UI Loaded!';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }, 1000);
    
    console.log('✅ Modern Real Estate CRM UI Loaded Successfully!');
});
</script>

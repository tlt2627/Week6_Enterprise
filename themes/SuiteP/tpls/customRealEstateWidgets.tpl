{*
Real Estate CRM - Custom Widgets Template
*}

{literal}
<div id="real-estate-widgets" class="real-estate-integration">
    <!-- Real Estate Quick Stats Widget -->
    <div class="re-quick-stats" id="re-quick-stats">
        <div class="re-widget-header">
            <h4>🏠 Real Estate Overview</h4>
        </div>
        <div class="re-stats-container">
            <div class="re-stat-item">
                <span class="re-stat-number" id="re-total-properties">0</span>
                <span class="re-stat-label">Properties</span>
            </div>
            <div class="re-stat-item">
                <span class="re-stat-number" id="re-available-properties">0</span>
                <span class="re-stat-label">Available</span>
            </div>
            <div class="re-stat-item">
                <span class="re-stat-number" id="re-avg-price">$0</span>
                <span class="re-stat-label">Avg Price</span>
            </div>
        </div>
        <div class="re-quick-actions">
            <a href="index.php?module=Properties&action=EditView" class="re-quick-btn">➕ Add Property</a>
            <a href="modules/PropertySearch/index.php" class="re-quick-btn">🔍 Search</a>
        </div>
    </div>

    <!-- Real Estate Quick Navigation -->
    <div class="re-quick-nav" id="re-quick-nav">
        <div class="re-nav-item">
            <a href="modules/Properties/index.php">
                <span class="re-nav-icon">🏠</span>
                <span class="re-nav-label">Dashboard</span>
            </a>
        </div>
        <div class="re-nav-item">
            <a href="index.php?module=Properties&action=index">
                <span class="re-nav-icon">📋</span>
                <span class="re-nav-label">Properties</span>
            </a>
        </div>
        <div class="re-nav-item">
            <a href="modules/PropertyAnalytics/index.php">
                <span class="re-nav-icon">📊</span>
                <span class="re-nav-label">Analytics</span>
            </a>
        </div>
        <div class="re-nav-item">
            <a href="index.php?module=CommissionCalculator&action=index">
                <span class="re-nav-icon">💰</span>
                <span class="re-nav-label">Commission</span>
            </a>
        </div>
    </div>
</div>

<style>
.real-estate-integration {
    margin: 15px 0;
}

.re-quick-stats {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.re-widget-header h4 {
    margin: 0 0 15px 0;
    color: #2c3e50;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.re-stats-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 15px;
}

.re-stat-item {
    text-align: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 6px;
    border-left: 3px solid #667eea;
}

.re-stat-number {
    display: block;
    font-size: 1.5em;
    font-weight: bold;
    color: #2c3e50;
}

.re-stat-label {
    display: block;
    font-size: 0.9em;
    color: #666;
    margin-top: 4px;
}

.re-quick-actions {
    text-align: center;
}

.re-quick-btn {
    display: inline-block;
    padding: 8px 15px;
    margin: 0 5px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.9em;
    transition: all 0.3s ease;
}

.re-quick-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    color: white;
}

.re-quick-nav {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.re-nav-item {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 12px;
    text-align: center;
    transition: all 0.3s ease;
}

.re-nav-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.re-nav-item:hover .re-nav-icon,
.re-nav-item:hover .re-nav-label {
    color: white;
}

.re-nav-item a {
    text-decoration: none;
    color: inherit;
}

.re-nav-icon {
    display: block;
    font-size: 1.5em;
    margin-bottom: 5px;
}

.re-nav-label {
    display: block;
    font-size: 0.9em;
    color: #666;
    font-weight: 500;
}

@media (max-width: 768px) {
    .re-stats-container {
        grid-template-columns: 1fr;
    }
    
    .re-quick-nav {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<script>
// Load Real Estate statistics
function loadRealEstateStats() {
    // This would normally make an AJAX call to get real-time stats
    // For now, we'll use placeholder values
    document.getElementById('re-total-properties').textContent = '0';
    document.getElementById('re-available-properties').textContent = '0';
    document.getElementById('re-avg-price').textContent = '$0';
    
    // Try to fetch real data if available
    if (typeof jQuery !== 'undefined') {
        jQuery.ajax({
            url: 'modules/Properties/index.php?action=getStats',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data && data.stats) {
                    document.getElementById('re-total-properties').textContent = data.stats.total || '0';
                    document.getElementById('re-available-properties').textContent = data.stats.available || '0';
                    document.getElementById('re-avg-price').textContent = '$' + (data.stats.avg_price ? Number(data.stats.avg_price).toLocaleString() : '0');
                }
            },
            error: function() {
                // Keep placeholder values
            }
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', loadRealEstateStats);
</script>
{/literal} 
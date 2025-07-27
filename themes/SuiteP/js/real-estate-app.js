/**
 * Real Estate CRM - Modern ES6+ Application Architecture
 * Comprehensive JavaScript framework for real estate CRM functionality
 */

// Base API Client Class
class APIClient {
    constructor(baseURL = '/Api/V8') {
        this.baseURL = baseURL;
        this.token = null;
        this.cache = new Map();
    }

    async authenticate() {
        // Implementation would handle OAuth2 authentication
        try {
            const response = await fetch(`${this.baseURL}/oauth/token`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    grant_type: 'client_credentials',
                    client_id: 'your_client_id',
                    client_secret: 'your_client_secret'
                })
            });
            const data = await response.json();
            this.token = data.access_token;
            return this.token;
        } catch (error) {
            console.error('Authentication failed:', error);
            throw error;
        }
    }

    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const config = {
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${this.token}`,
                ...options.headers
            },
            ...options
        };

        try {
            const response = await fetch(url, config);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error(`API request failed: ${endpoint}`, error);
            throw error;
        }
    }

    // Generic GET request with caching
    async get(endpoint, useCache = true) {
        if (useCache && this.cache.has(endpoint)) {
            const cached = this.cache.get(endpoint);
            if (Date.now() - cached.timestamp < 300000) { // 5 minutes
                return cached.data;
            }
        }

        const data = await this.request(endpoint);
        
        if (useCache) {
            this.cache.set(endpoint, {
                data,
                timestamp: Date.now()
            });
        }

        return data;
    }

    // Generic POST request
    async post(endpoint, data) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    // Generic PATCH request
    async patch(endpoint, data) {
        return this.request(endpoint, {
            method: 'PATCH',
            body: JSON.stringify(data)
        });
    }

    // Generic DELETE request
    async delete(endpoint) {
        return this.request(endpoint, {
            method: 'DELETE'
        });
    }
}

// Property Management Service
class PropertyService extends EventTarget {
    constructor(apiClient) {
        super();
        this.api = apiClient;
        this.properties = new Map();
    }

    async getProperty(id) {
        try {
            const response = await this.api.get(`/module/Properties/${id}`);
            const property = response.data;
            this.properties.set(id, property);
            return property;
        } catch (error) {
            this.dispatchEvent(new CustomEvent('error', {
                detail: { message: 'Failed to fetch property', error }
            }));
            throw error;
        }
    }

    async searchProperties(criteria) {
        const params = new URLSearchParams();
        Object.entries(criteria).forEach(([key, value]) => {
            if (value) params.append(key, value);
        });

        try {
            const response = await this.api.get(`/module/Properties?${params}`);
            const properties = response.data || [];
            
            // Cache properties
            properties.forEach(property => {
                this.properties.set(property.id, property);
            });

            this.dispatchEvent(new CustomEvent('searchComplete', {
                detail: { properties, criteria }
            }));

            return properties;
        } catch (error) {
            this.dispatchEvent(new CustomEvent('error', {
                detail: { message: 'Property search failed', error }
            }));
            throw error;
        }
    }

    async updateProperty(id, updates) {
        try {
            const response = await this.api.patch(`/module/Properties/${id}`, {
                data: {
                    type: 'Properties',
                    id: id,
                    attributes: updates
                }
            });

            const updatedProperty = response.data;
            this.properties.set(id, updatedProperty);

            this.dispatchEvent(new CustomEvent('propertyUpdated', {
                detail: { property: updatedProperty }
            }));

            return updatedProperty;
        } catch (error) {
            this.dispatchEvent(new CustomEvent('error', {
                detail: { message: 'Failed to update property', error }
            }));
            throw error;
        }
    }

    async getPropertyImages(propertyId) {
        try {
            const response = await this.api.get(`/module/Properties/${propertyId}/relationships/property_images`);
            return response.data || [];
        } catch (error) {
            console.error('Failed to fetch property images:', error);
            return [];
        }
    }
}

// Commission Calculator Service
class CommissionService {
    constructor(config = {}) {
        this.defaultSettings = {
            listingAgentSplit: config.listingAgentSplit || 50,
            sellingAgentSplit: config.sellingAgentSplit || 50,
            brokerFeePercentage: config.brokerFeePercentage || 10
        };
    }

    calculate(salePrice, commissionRate, options = {}) {
        const settings = { ...this.defaultSettings, ...options };
        
        const totalCommission = (salePrice * commissionRate) / 100;
        const listingSide = totalCommission * (settings.listingAgentSplit / 100);
        const sellingSide = totalCommission * (settings.sellingAgentSplit / 100);

        const listingBrokerFee = listingSide * (settings.brokerFeePercentage / 100);
        const sellingBrokerFee = sellingSide * (settings.brokerFeePercentage / 100);
        const totalBrokerFee = listingBrokerFee + sellingBrokerFee;

        const listingAgentCommission = listingSide - listingBrokerFee;
        const sellingAgentCommission = sellingSide - sellingBrokerFee;
        const netCommission = listingAgentCommission + sellingAgentCommission;

        return {
            salePrice,
            commissionRate,
            totalCommission,
            listingSide,
            sellingSide,
            listingBrokerFee,
            sellingBrokerFee,
            totalBrokerFee,
            listingAgentCommission,
            sellingAgentCommission,
            netCommission,
            breakdown: this.generateBreakdown({
                salePrice,
                commissionRate,
                totalCommission,
                listingAgentCommission,
                sellingAgentCommission,
                totalBrokerFee,
                netCommission
            })
        };
    }

    generateBreakdown(data) {
        return [
            { label: 'Sale Price', value: this.formatCurrency(data.salePrice) },
            { label: 'Commission Rate', value: `${data.commissionRate}%` },
            { label: 'Total Commission', value: this.formatCurrency(data.totalCommission) },
            { label: 'Listing Agent', value: this.formatCurrency(data.listingAgentCommission) },
            { label: 'Selling Agent', value: this.formatCurrency(data.sellingAgentCommission) },
            { label: 'Broker Fee', value: this.formatCurrency(data.totalBrokerFee) },
            { label: 'Net Commission', value: this.formatCurrency(data.netCommission), highlight: true }
        ];
    }

    formatCurrency(amount, currency = 'USD', locale = 'en-US') {
        return new Intl.NumberFormat(locale, {
            style: 'currency',
            currency: currency,
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
    }
}

// Property Search Component
class PropertySearchComponent {
    constructor(containerSelector, propertyService) {
        this.container = document.querySelector(containerSelector);
        this.propertyService = propertyService;
        this.currentFilters = {};
        this.debounceTimer = null;
        this.resultsContainer = null;

        if (this.container) {
            this.init();
        }
    }

    init() {
        this.createSearchInterface();
        this.bindEvents();
        this.setupPropertyServiceListeners();
    }

    createSearchInterface() {
        this.container.innerHTML = `
            <div class="property-search-enhanced">
                <div class="search-header">
                    <h3>Find Your Perfect Property</h3>
                    <button class="search-toggle" aria-label="Toggle advanced search">
                        <span>Advanced Search</span>
                    </button>
                </div>
                
                <form class="search-form">
                    <div class="search-row search-basic">
                        <div class="form-field-wrapper">
                            <input type="text" name="location" placeholder=" " />
                            <label>Location</label>
                        </div>
                        <div class="form-field-wrapper">
                            <select name="property_type">
                                <option value="">Any Type</option>
                                <option value="Residential">Residential</option>
                                <option value="Commercial">Commercial</option>
                                <option value="Land">Land</option>
                                <option value="Multi-Family">Multi-Family</option>
                            </select>
                            <label>Property Type</label>
                        </div>
                        <div class="form-field-wrapper">
                            <input type="number" name="min_price" placeholder=" " />
                            <label>Min Price</label>
                        </div>
                        <div class="form-field-wrapper">
                            <input type="number" name="max_price" placeholder=" " />
                            <label>Max Price</label>
                        </div>
                    </div>
                    
                    <div class="search-row search-advanced" style="display: none;">
                        <div class="form-field-wrapper">
                            <input type="number" name="bedrooms" min="0" placeholder=" " />
                            <label>Bedrooms</label>
                        </div>
                        <div class="form-field-wrapper">
                            <input type="number" name="bathrooms" min="0" step="0.5" placeholder=" " />
                            <label>Bathrooms</label>
                        </div>
                        <div class="form-field-wrapper">
                            <input type="number" name="min_sqft" placeholder=" " />
                            <label>Min Sq Ft</label>
                        </div>
                        <div class="form-field-wrapper">
                            <select name="property_status">
                                <option value="">Any Status</option>
                                <option value="Available">Available</option>
                                <option value="Under Contract">Under Contract</option>
                                <option value="Pending">Pending</option>
                                <option value="Sold">Sold</option>
                            </select>
                            <label>Status</label>
                        </div>
                    </div>
                    
                    <div class="search-actions">
                        <button type="submit" class="btn btn-primary">
                            <span>Search Properties</span>
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <span>Clear All</span>
                        </button>
                    </div>
                </form>
                
                <div class="search-results">
                    <div class="results-header" style="display: none;">
                        <h4 class="results-count"></h4>
                        <div class="results-actions">
                            <button class="sort-toggle" data-sort="price">Sort by Price</button>
                            <button class="view-toggle" data-view="grid">Grid View</button>
                        </div>
                    </div>
                    <div class="results-container"></div>
                </div>
            </div>
        `;

        this.resultsContainer = this.container.querySelector('.results-container');
    }

    bindEvents() {
        // Form submission
        const searchForm = this.container.querySelector('.search-form');
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.performSearch();
        });

        // Input changes with debouncing
        const inputs = searchForm.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                this.handleInputChange();
            });
        });

        // Advanced search toggle
        const advancedToggle = this.container.querySelector('.search-toggle');
        advancedToggle.addEventListener('click', () => {
            this.toggleAdvancedSearch();
        });

        // Results actions
        const sortToggle = this.container.querySelector('.sort-toggle');
        if (sortToggle) {
            sortToggle.addEventListener('click', (e) => {
                this.handleSort(e.target.dataset.sort);
            });
        }

        // Form reset
        searchForm.addEventListener('reset', () => {
            this.clearResults();
            this.currentFilters = {};
        });
    }

    setupPropertyServiceListeners() {
        this.propertyService.addEventListener('searchComplete', (event) => {
            this.displayResults(event.detail.properties);
        });

        this.propertyService.addEventListener('error', (event) => {
            this.displayError(event.detail.message);
        });
    }

    handleInputChange() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
            this.collectFilters();
        }, 300);
    }

    collectFilters() {
        const formData = new FormData(this.container.querySelector('.search-form'));
        this.currentFilters = {};
        
        for (let [key, value] of formData.entries()) {
            if (value.trim()) {
                this.currentFilters[key] = value.trim();
            }
        }
    }

    async performSearch() {
        this.collectFilters();
        
        if (Object.keys(this.currentFilters).length === 0) {
            this.clearResults();
            return;
        }

        try {
            this.showLoading();
            await this.propertyService.searchProperties(this.currentFilters);
        } catch (error) {
            this.displayError('Search failed. Please try again.');
        }
    }

    displayResults(properties) {
        this.hideLoading();
        
        const resultsHeader = this.container.querySelector('.results-header');
        const resultsCount = this.container.querySelector('.results-count');
        
        if (properties.length === 0) {
            this.resultsContainer.innerHTML = `
                <div class="no-results">
                    <h4>No properties found</h4>
                    <p>Try adjusting your search criteria</p>
                </div>
            `;
            resultsHeader.style.display = 'none';
            return;
        }

        // Update results count
        resultsCount.textContent = `${properties.length} Properties Found`;
        resultsHeader.style.display = 'flex';

        // Render property cards
        this.resultsContainer.innerHTML = properties.map(property => 
            this.renderPropertyCard(property)
        ).join('');

        // Add interaction listeners
        this.bindResultsEvents();
    }

    renderPropertyCard(property) {
        const attributes = property.attributes || property;
        const price = this.formatPrice(attributes.listing_price || attributes.sale_price);
        const status = attributes.property_status || 'Available';
        const address = this.formatAddress(attributes);

        return `
            <div class="property-card modern" data-property-id="${property.id}">
                <div class="property-card-header">
                    <div class="property-price">${price}</div>
                    <div class="property-address">${address}</div>
                    <div class="property-status property-status--${status.toLowerCase().replace(/\s+/g, '-')}">${status}</div>
                </div>
                <div class="property-card-body">
                    <div class="property-features">
                        ${this.renderPropertyFeatures(attributes)}
                    </div>
                    <div class="property-actions">
                        <button class="btn btn-outline view-details" data-property-id="${property.id}">
                            View Details
                        </button>
                        <button class="btn btn-primary contact-agent" data-property-id="${property.id}">
                            Contact Agent
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    renderPropertyFeatures(attributes) {
        const features = [
            { value: attributes.bedrooms || 'N/A', label: 'Bedrooms' },
            { value: attributes.bathrooms || 'N/A', label: 'Bathrooms' },
            { value: attributes.square_footage ? `${attributes.square_footage.toLocaleString()}` : 'N/A', label: 'Sq Ft' },
            { value: attributes.year_built || 'N/A', label: 'Built' }
        ];

        return features.map(feature => `
            <div class="property-feature">
                <div class="property-feature-value">${feature.value}</div>
                <div class="property-feature-label">${feature.label}</div>
            </div>
        `).join('');
    }

    formatAddress(attributes) {
        const parts = [
            attributes.property_address_street,
            attributes.property_address_city,
            attributes.property_address_state
        ].filter(Boolean);
        
        return parts.join(', ') || 'Address not available';
    }

    formatPrice(price) {
        if (!price) return 'Price on request';
        
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(price);
    }

    bindResultsEvents() {
        // View details buttons
        const viewButtons = this.resultsContainer.querySelectorAll('.view-details');
        viewButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const propertyId = e.target.dataset.propertyId;
                this.viewPropertyDetails(propertyId);
            });
        });

        // Contact agent buttons
        const contactButtons = this.resultsContainer.querySelectorAll('.contact-agent');
        contactButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const propertyId = e.target.dataset.propertyId;
                this.contactAgent(propertyId);
            });
        });
    }

    async viewPropertyDetails(propertyId) {
        try {
            const property = await this.propertyService.getProperty(propertyId);
            // This would open a modal or navigate to detail page
            console.log('Viewing property details:', property);
        } catch (error) {
            this.displayError('Failed to load property details');
        }
    }

    contactAgent(propertyId) {
        // This would open a contact form modal
        console.log('Contacting agent for property:', propertyId);
    }

    toggleAdvancedSearch() {
        const advancedRow = this.container.querySelector('.search-advanced');
        const toggleButton = this.container.querySelector('.search-toggle span');
        const isVisible = advancedRow.style.display !== 'none';
        
        advancedRow.style.display = isVisible ? 'none' : 'flex';
        toggleButton.textContent = isVisible ? 'Advanced Search' : 'Basic Search';
    }

    showLoading() {
        this.resultsContainer.innerHTML = `
            <div class="loading-state">
                <div class="loading-spinner"></div>
                <p>Searching properties...</p>
            </div>
        `;
    }

    hideLoading() {
        const loading = this.resultsContainer.querySelector('.loading-state');
        if (loading) {
            loading.remove();
        }
    }

    displayError(message) {
        this.hideLoading();
        this.resultsContainer.innerHTML = `
            <div class="error-state">
                <h4>Search Error</h4>
                <p>${message}</p>
                <button class="btn btn-primary retry-search">Try Again</button>
            </div>
        `;

        const retryButton = this.resultsContainer.querySelector('.retry-search');
        retryButton.addEventListener('click', () => {
            this.performSearch();
        });
    }

    clearResults() {
        this.resultsContainer.innerHTML = '';
        const resultsHeader = this.container.querySelector('.results-header');
        resultsHeader.style.display = 'none';
    }
}

// Commission Calculator Component
class CommissionCalculatorComponent {
    constructor(containerSelector, commissionService) {
        this.container = document.querySelector(containerSelector);
        this.commissionService = commissionService;

        if (this.container) {
            this.init();
        }
    }

    init() {
        this.createCalculatorInterface();
        this.bindEvents();
    }

    createCalculatorInterface() {
        this.container.innerHTML = `
            <div class="commission-calculator-enhanced">
                <div class="calculator-header">
                    <h3>Commission Calculator</h3>
                    <p>Calculate agent commissions and broker fees</p>
                </div>
                
                <form class="calculator-form">
                    <div class="form-row">
                        <div class="form-field-wrapper">
                            <input type="number" name="sale_price" step="1000" min="0" required placeholder=" " />
                            <label>Sale Price ($)</label>
                        </div>
                        <div class="form-field-wrapper">
                            <input type="number" name="commission_rate" step="0.1" min="0" max="100" required placeholder=" " />
                            <label>Commission Rate (%)</label>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-field-wrapper">
                            <input type="number" name="listing_agent_split" step="1" min="0" max="100" value="50" placeholder=" " />
                            <label>Listing Agent Split (%)</label>
                        </div>
                        <div class="form-field-wrapper">
                            <input type="number" name="selling_agent_split" step="1" min="0" max="100" value="50" placeholder=" " />
                            <label>Selling Agent Split (%)</label>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-field-wrapper">
                            <input type="number" name="broker_fee_percentage" step="0.1" min="0" max="100" value="10" placeholder=" " />
                            <label>Broker Fee (%)</label>
                        </div>
                    </div>
                    
                    <div class="calculator-actions">
                        <button type="submit" class="btn btn-primary">Calculate Commission</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
                
                <div class="calculation-results" style="display: none;">
                    <div class="results-header">
                        <h4>Commission Breakdown</h4>
                    </div>
                    <div class="results-content"></div>
                    <div class="results-actions">
                        <button class="export-report btn btn-outline">Export Report</button>
                        <button class="save-calculation btn btn-primary">Save Calculation</button>
                    </div>
                </div>
            </div>
        `;
    }

    bindEvents() {
        const form = this.container.querySelector('.calculator-form');
        
        // Form submission
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.calculateCommission();
        });

        // Real-time calculation on input
        const inputs = form.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                if (this.hasValidInputs()) {
                    this.calculateCommission();
                }
            });
        });

        // Form reset
        form.addEventListener('reset', () => {
            this.hideResults();
        });

        // Export report
        this.container.addEventListener('click', (e) => {
            if (e.target.classList.contains('export-report')) {
                this.exportReport();
            } else if (e.target.classList.contains('save-calculation')) {
                this.saveCalculation();
            }
        });
    }

    hasValidInputs() {
        const form = this.container.querySelector('.calculator-form');
        const salePrice = parseFloat(form.sale_price.value);
        const commissionRate = parseFloat(form.commission_rate.value);
        
        return salePrice > 0 && commissionRate > 0;
    }

    calculateCommission() {
        const form = this.container.querySelector('.calculator-form');
        const formData = new FormData(form);
        
        const salePrice = parseFloat(formData.get('sale_price') || 0);
        const commissionRate = parseFloat(formData.get('commission_rate') || 0);
        const listingAgentSplit = parseFloat(formData.get('listing_agent_split') || 50);
        const sellingAgentSplit = parseFloat(formData.get('selling_agent_split') || 50);
        const brokerFeePercentage = parseFloat(formData.get('broker_fee_percentage') || 10);

        if (salePrice <= 0 || commissionRate <= 0) {
            this.hideResults();
            return;
        }

        const result = this.commissionService.calculate(salePrice, commissionRate, {
            listingAgentSplit,
            sellingAgentSplit,
            brokerFeePercentage
        });

        this.displayResults(result);
    }

    displayResults(result) {
        const resultsContainer = this.container.querySelector('.calculation-results');
        const resultsContent = this.container.querySelector('.results-content');
        
        resultsContent.innerHTML = `
            <div class="commission-breakdown">
                ${result.breakdown.map(item => `
                    <div class="commission-item ${item.highlight ? 'highlight' : ''}">
                        <span class="label">${item.label}:</span>
                        <strong class="value">${item.value}</strong>
                    </div>
                `).join('')}
            </div>
            
            <div class="commission-details">
                <h5>Detailed Breakdown</h5>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span>Listing Side Commission:</span>
                        <strong>${this.commissionService.formatCurrency(result.listingSide)}</strong>
                    </div>
                    <div class="detail-item">
                        <span>Selling Side Commission:</span>
                        <strong>${this.commissionService.formatCurrency(result.sellingSide)}</strong>
                    </div>
                    <div class="detail-item">
                        <span>Listing Broker Fee:</span>
                        <strong>${this.commissionService.formatCurrency(result.listingBrokerFee)}</strong>
                    </div>
                    <div class="detail-item">
                        <span>Selling Broker Fee:</span>
                        <strong>${this.commissionService.formatCurrency(result.sellingBrokerFee)}</strong>
                    </div>
                </div>
            </div>
        `;
        
        resultsContainer.style.display = 'block';
        this.currentResult = result;
    }

    hideResults() {
        const resultsContainer = this.container.querySelector('.calculation-results');
        resultsContainer.style.display = 'none';
        this.currentResult = null;
    }

    exportReport() {
        if (!this.currentResult) return;
        
        const reportContent = this.generateReportContent(this.currentResult);
        const blob = new Blob([reportContent], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        
        const a = document.createElement('a');
        a.href = url;
        a.download = `commission-report-${new Date().toISOString().split('T')[0]}.txt`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    generateReportContent(result) {
        return `
Commission Calculation Report
Generated: ${new Date().toLocaleString()}
========================================

Sale Price: ${this.commissionService.formatCurrency(result.salePrice)}
Commission Rate: ${result.commissionRate}%
Total Commission: ${this.commissionService.formatCurrency(result.totalCommission)}

Agent Commission Breakdown:
---------------------------
Listing Agent Commission: ${this.commissionService.formatCurrency(result.listingAgentCommission)}
Selling Agent Commission: ${this.commissionService.formatCurrency(result.sellingAgentCommission)}

Broker Fees:
------------
Listing Broker Fee: ${this.commissionService.formatCurrency(result.listingBrokerFee)}
Selling Broker Fee: ${this.commissionService.formatCurrency(result.sellingBrokerFee)}
Total Broker Fee: ${this.commissionService.formatCurrency(result.totalBrokerFee)}

Net Commission: ${this.commissionService.formatCurrency(result.netCommission)}
`;
    }

    saveCalculation() {
        if (!this.currentResult) return;
        
        // This would typically save to the backend
        console.log('Saving commission calculation:', this.currentResult);
        
        // Show success message
        const notification = document.createElement('div');
        notification.className = 'notification success';
        notification.textContent = 'Commission calculation saved successfully!';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}

// Main Application Class
class RealEstateCRMApp {
    constructor() {
        this.apiClient = new APIClient();
        this.propertyService = new PropertyService(this.apiClient);
        this.commissionService = new CommissionService();
        this.components = new Map();
        this.initialized = false;
    }

    async init() {
        if (this.initialized) return;
        
        try {
            // Initialize API authentication
            await this.initializeAPI();
            
            // Initialize components
            this.initializeComponents();
            
            // Set up global event listeners
            this.setupGlobalEvents();
            
            // Set up error handling
            this.setupErrorHandling();
            
            this.initialized = true;
            console.log('Real Estate CRM App initialized successfully');
            
            // Dispatch app ready event
            document.dispatchEvent(new CustomEvent('realEstateCRMReady', {
                detail: { app: this }
            }));
            
        } catch (error) {
            console.error('Failed to initialize Real Estate CRM App:', error);
            this.showInitializationError(error);
        }
    }

    async initializeAPI() {
        // In a real implementation, this would handle authentication
        try {
            // await this.apiClient.authenticate();
            console.log('API client ready');
        } catch (error) {
            console.warn('API authentication failed, using mock data');
        }
    }

    initializeComponents() {
        // Initialize Property Search Component
        const searchContainer = document.querySelector('.property-search-dashboard');
        if (searchContainer) {
            this.components.set('propertySearch', 
                new PropertySearchComponent('.property-search-dashboard', this.propertyService)
            );
        }

        // Initialize Commission Calculator Component
        const calculatorContainer = document.querySelector('.commission-calculator');
        if (calculatorContainer) {
            this.components.set('commissionCalculator',
                new CommissionCalculatorComponent('.commission-calculator', this.commissionService)
            );
        }

        // Initialize other components as needed
        this.initializePropertyCards();
        this.initializeForms();
    }

    initializePropertyCards() {
        const propertyCards = document.querySelectorAll('.property-card');
        propertyCards.forEach(card => {
            this.enhancePropertyCard(card);
        });
    }

    enhancePropertyCard(card) {
        // Add interaction enhancements
        card.addEventListener('mouseenter', () => {
            card.classList.add('hover-enhanced');
        });

        card.addEventListener('mouseleave', () => {
            card.classList.remove('hover-enhanced');
        });

        // Add click handling
        card.addEventListener('click', (e) => {
            if (!e.target.closest('button')) {
                const propertyId = card.dataset.propertyId;
                if (propertyId) {
                    this.handlePropertyCardClick(propertyId);
                }
            }
        });
    }

    initializeForms() {
        const forms = document.querySelectorAll('form[data-real-estate]');
        forms.forEach(form => {
            this.enhanceForm(form);
        });
    }

    enhanceForm(form) {
        // Add modern form enhancements
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            // Add floating label effects
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('focused');
                if (input.value.trim() === '') {
                    input.parentElement.classList.remove('has-value');
                } else {
                    input.parentElement.classList.add('has-value');
                }
            });

            // Set initial state
            if (input.value.trim() !== '') {
                input.parentElement.classList.add('has-value');
            }
        });
    }

    setupGlobalEvents() {
        // Property service events
        this.propertyService.addEventListener('propertyUpdated', (event) => {
            console.log('Property updated globally:', event.detail.property);
            this.refreshPropertyDisplays(event.detail.property);
        });

        this.propertyService.addEventListener('error', (event) => {
            this.showError(event.detail.message);
        });

        // Window events
        window.addEventListener('resize', () => {
            this.handleResize();
        });

        // Navigation events
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-navigate]')) {
                e.preventDefault();
                this.handleNavigation(e.target.dataset.navigate);
            }
        });
    }

    setupErrorHandling() {
        // Global error handling
        window.addEventListener('error', (event) => {
            console.error('Global error:', event.error);
        });

        window.addEventListener('unhandledrejection', (event) => {
            console.error('Unhandled promise rejection:', event.reason);
            event.preventDefault(); // Prevent browser console error
        });
    }

    handlePropertyCardClick(propertyId) {
        console.log('Property card clicked:', propertyId);
        // This would typically navigate to property details or open a modal
    }

    refreshPropertyDisplays(property) {
        // Update all property displays with new data
        const propertyCards = document.querySelectorAll(`[data-property-id="${property.id}"]`);
        propertyCards.forEach(card => {
            // Update card content
            this.updatePropertyCardData(card, property);
        });
    }

    updatePropertyCardData(card, property) {
        // Update property card with new data
        const priceElement = card.querySelector('.property-price');
        if (priceElement) {
            priceElement.textContent = this.formatPrice(property.attributes.listing_price);
        }

        const statusElement = card.querySelector('.property-status');
        if (statusElement) {
            statusElement.textContent = property.attributes.property_status;
            statusElement.className = `property-status property-status--${property.attributes.property_status.toLowerCase().replace(/\s+/g, '-')}`;
        }
    }

    formatPrice(price) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(price);
    }

    handleResize() {
        // Handle responsive adjustments
        const components = this.components.values();
        for (const component of components) {
            if (component.handleResize) {
                component.handleResize();
            }
        }
    }

    handleNavigation(path) {
        // Handle in-app navigation
        console.log('Navigating to:', path);
    }

    showError(message) {
        // Show user-friendly error message
        const notification = this.createNotification('error', message);
        document.body.appendChild(notification);
    }

    showInitializationError(error) {
        console.error('Initialization error:', error);
        // Show initialization error to user
    }

    createNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;

        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.remove();
        });

        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);

        return notification;
    }

    // Public API methods
    getComponent(name) {
        return this.components.get(name);
    }

    getPropertyService() {
        return this.propertyService;
    }

    getCommissionService() {
        return this.commissionService;
    }
}

// Initialize the application when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Create global app instance
    window.realEstateCRM = new RealEstateCRMApp();
    
    // Initialize the app
    window.realEstateCRM.init().then(() => {
        console.log('Real Estate CRM is ready!');
    }).catch(error => {
        console.error('Failed to initialize Real Estate CRM:', error);
    });
});

// Export for module usage if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        RealEstateCRMApp,
        PropertyService,
        CommissionService,
        PropertySearchComponent,
        CommissionCalculatorComponent
    };
}

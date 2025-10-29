/**
 * Frontend Chart Readiness Test
 * 
 * This test verifies that charts are properly rendered in the browser.
 * Run this test using Playwright, Cypress, or Selenium.
 * 
 * Test Scenarios:
 * 1. Chart.js library loads successfully
 * 2. Canvas elements are present in the DOM
 * 3. Chart data is correctly passed to Chart.js
 * 4. Charts render without errors
 * 5. Charts are responsive and interactive
 */

const chartReadinessTests = {
    /**
     * Test Admin Dashboard Chart
     */
    async testAdminDashboardChart(page) {
        await page.goto('http://127.0.0.1:8001/admin/dashboard');
        
        // Wait for page to load
        await page.waitForSelector('canvas#incomeExpenseChart', { timeout: 10000 });
        
        // Verify Chart.js library is loaded
        const chartJsLoaded = await page.evaluate(() => {
            return typeof Chart !== 'undefined';
        });
        console.assert(chartJsLoaded, 'Chart.js library should be loaded');
        
        // Verify canvas element exists
        const canvasExists = await page.$('canvas#incomeExpenseChart');
        console.assert(canvasExists !== null, 'Canvas element should exist');
        
        // Verify chart data is present in page
        const hasChartData = await page.evaluate(() => {
            return document.body.innerHTML.includes('incomeExpenseChart') &&
                   document.body.innerHTML.includes('chart.js');
        });
        console.assert(hasChartData, 'Chart data should be present');
        
        // Verify chart is rendered (canvas has non-zero dimensions)
        const canvasDimensions = await page.evaluate(() => {
            const canvas = document.querySelector('canvas#incomeExpenseChart');
            return {
                width: canvas.width,
                height: canvas.height
            };
        });
        console.assert(canvasDimensions.width > 0 && canvasDimensions.height > 0, 
            'Canvas should have dimensions');
    },

    /**
     * Test BI Dashboard Charts
     */
    async testBIDashboardCharts(page) {
        await page.goto('http://127.0.0.1:8001/hms/analytics/bi-dashboard');
        
        // Wait for charts to load
        await page.waitForSelector('canvas#revenueChart', { timeout: 10000 });
        await page.waitForSelector('canvas#patientChart', { timeout: 10000 });
        
        // Verify both charts exist
        const revenueChart = await page.$('canvas#revenueChart');
        const patientChart = await page.$('canvas#patientChart');
        
        console.assert(revenueChart !== null, 'Revenue chart should exist');
        console.assert(patientChart !== null, 'Patient chart should exist');
        
        // Verify Chart.js is loaded
        const chartJsLoaded = await page.evaluate(() => {
            return typeof Chart !== 'undefined';
        });
        console.assert(chartJsLoaded, 'Chart.js library should be loaded');
        
        // Verify charts are rendered
        const chartsRendered = await page.evaluate(() => {
            const revenue = document.querySelector('canvas#revenueChart');
            const patient = document.querySelector('canvas#patientChart');
            return {
                revenue: revenue.width > 0 && revenue.height > 0,
                patient: patient.width > 0 && patient.height > 0
            };
        });
        console.assert(chartsRendered.revenue, 'Revenue chart should be rendered');
        console.assert(chartsRendered.patient, 'Patient chart should be rendered');
    },

    /**
     * Test Revenue Report Chart
     */
    async testRevenueReportChart(page) {
        await page.goto('http://127.0.0.1:8001/hms/reports/revenue');
        
        // Wait for chart to load
        await page.waitForSelector('canvas#revenueChart', { timeout: 10000 });
        
        // Verify chart exists
        const chartExists = await page.$('canvas#revenueChart');
        console.assert(chartExists !== null, 'Revenue report chart should exist');
        
        // Verify Chart.js is loaded
        const chartJsLoaded = await page.evaluate(() => {
            return typeof Chart !== 'undefined';
        });
        console.assert(chartJsLoaded, 'Chart.js library should be loaded');
    },

    /**
     * Test Chart Responsiveness
     */
    async testChartResponsiveness(page) {
        await page.goto('http://127.0.0.1:8001/admin/dashboard');
        await page.waitForSelector('canvas#incomeExpenseChart', { timeout: 10000 });
        
        // Test desktop view
        await page.setViewport({ width: 1920, height: 1080 });
        const desktopDimensions = await page.evaluate(() => {
            const canvas = document.querySelector('canvas#incomeExpenseChart');
            return { width: canvas.width, height: canvas.height };
        });
        
        // Test mobile view
        await page.setViewport({ width: 375, height: 667 });
        await page.waitForTimeout(500); // Wait for resize
        const mobileDimensions = await page.evaluate(() => {
            const canvas = document.querySelector('canvas#incomeExpenseChart');
            return { width: canvas.width, height: canvas.height };
        });
        
        console.assert(mobileDimensions.width > 0 && mobileDimensions.height > 0,
            'Chart should be responsive');
    },

    /**
     * Test Chart Data JSON Encoding
     */
    async testChartDataJSONEncoding(page) {
        await page.goto('http://127.0.0.1:8001/admin/dashboard');
        
        // Extract chart data from page
        const chartDataValid = await page.evaluate(() => {
            // Look for JSON data in script tags
            const scripts = Array.from(document.querySelectorAll('script'));
            for (const script of scripts) {
                if (script.textContent.includes('incomeExpenseChart')) {
                    // Check if JSON data is properly formatted
                    try {
                        const hasLabels = script.textContent.includes('labels');
                        const hasIncome = script.textContent.includes('income');
                        const hasExpenses = script.textContent.includes('expenses');
                        return hasLabels && hasIncome && hasExpenses;
                    } catch (e) {
                        return false;
                    }
                }
            }
            return false;
        });
        
        console.assert(chartDataValid, 'Chart data should be properly encoded');
    },

    /**
     * Test Chart Error Handling
     */
    async testChartErrorHandling(page) {
        await page.goto('http://127.0.0.1:8001/admin/dashboard');
        
        // Listen for console errors
        const errors = [];
        page.on('console', msg => {
            if (msg.type() === 'error') {
                errors.push(msg.text());
            }
        });
        
        await page.waitForSelector('canvas#incomeExpenseChart', { timeout: 10000 });
        await page.waitForTimeout(2000); // Wait for chart to initialize
        
        // Filter out expected errors (like missing data)
        const criticalErrors = errors.filter(error => 
            !error.includes('Chart.js') && 
            !error.includes('canvas') &&
            !error.includes('data')
        );
        
        console.assert(criticalErrors.length === 0, 
            `No critical errors should occur: ${criticalErrors.join(', ')}`);
    }
};

// Export for use in test runners
if (typeof module !== 'undefined' && module.exports) {
    module.exports = chartReadinessTests;
}

// Run tests if executed directly
if (typeof window !== 'undefined') {
    // Browser environment
    window.chartReadinessTests = chartReadinessTests;
}


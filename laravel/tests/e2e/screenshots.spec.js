import { test, expect } from '@playwright/test';

/**
 * Screenshot Automation Script
 * Captures screenshots of all main application pages for the User Manual
 */

// Configuration
const BASE_URL = process.env.BASE_URL || 'http://localhost:8000';
const SCREENSHOT_DIR = './screenshots';

// Admin credentials
const ADMIN_EMAIL = 'admin@library.test';
const ADMIN_PASSWORD = 'password123';

// Helper function to take full page screenshot
async function captureScreenshot(page, name, description = '') {
  await page.screenshot({
    path: `${SCREENSHOT_DIR}/${name}.png`,
    fullPage: true
  });
  console.log(`✓ Captured: ${name} - ${description}`);
}

// Helper function to login
async function login(page) {
  await page.goto('/login');
  await page.getByLabel('Email').fill(ADMIN_EMAIL);
  await page.getByLabel('Password').fill(ADMIN_PASSWORD);
  await page.getByRole('button', { name: /Masuk|Log/i }).click();
  await page.waitForURL(/\/dashboard/, { timeout: 5000 });
  await page.waitForLoadState('networkidle');
}

test.describe('Screenshot Automation', () => {
  test.beforeAll(async ({ browser }) => {
    console.log('\n📸 Starting Screenshot Capture...');
    console.log(`   Base URL: ${BASE_URL}`);
    console.log(`   Output: ${SCREENSHOT_DIR}/\n`);
  });

  test('capture all screenshots', async ({ page }) => {
    // Set larger viewport for better screenshots
    await page.setViewportSize({ width: 1920, height: 1080 });

    // ========== PUBLIC PAGES ==========

    console.log('\n📖 PUBLIC PAGES');

    // Homepage / OPAC
    await page.goto(`${BASE_URL}/opac`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '01-opac-homepage', 'OPAC Homepage');

    // OPAC Search
    await page.getByPlaceholder(/Cari|Search/i).fill('test');
    await page.keyboard.press('Enter');
    await page.waitForTimeout(2000);
    await captureScreenshot(page, '02-opac-search-results', 'OPAC Search Results');

    // OPAC Advanced Search
    await page.goto(`${BASE_URL}/opac/advanced`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '03-opac-advanced-search', 'OPAC Advanced Search');

    // Digital Library
    await page.goto(`${BASE_URL}/digital-library`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '04-digital-library', 'Digital Library Page');

    // Repository
    await page.goto(`${BASE_URL}/repository`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '05-repository', 'Institutional Repository');

    // Login Page
    await page.goto(`${BASE_URL}/login`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '06-login-page', 'Login Page');

    // ========== ADMIN DASHBOARD ==========

    console.log('\n🎛️  ADMIN DASHBOARD');

    // Login
    await login(page);
    await captureScreenshot(page, '07-dashboard', 'Admin Dashboard');

    // Dashboard Statistics
    await captureScreenshot(page, '08-dashboard-stats', 'Dashboard Statistics Cards');

    // Charts Section
    const chartsSection = page.locator('.grid').filter({ hasText: 'Tren Sirkulasi' });
    await captureScreenshot(page, '09-dashboard-charts', 'Dashboard Charts');

    // ========== NAVIGATION ==========

    console.log('\n📋 NAVIGATION MENU');

    // Open sidebar menu if on mobile
    await page.setViewportSize({ width: 1440, height: 900 });

    // ========== COLLECTIONS ==========

    console.log('\n📚 COLLECTIONS');

    await page.goto(`${BASE_URL}/collections`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '10-collections-list', 'Collections List');

    // Collections Search
    await page.getByPlaceholder(/Cari|Search/i).fill('buku');
    await page.keyboard.press('Enter');
    await page.waitForTimeout(1500);
    await captureScreenshot(page, '11-collections-search', 'Collections Search Results');

    // Create Collection Form
    await page.goto(`${BASE_URL}/collections/create`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '12-collections-create', 'Create Collection Form');

    // ========== MEMBERS ==========

    console.log('\n👥 MEMBERS');

    await page.goto(`${BASE_URL}/members`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '13-members-list', 'Members List');

    // Member Types Filter
    await page.getByLabel(/Tipe|Type/i).selectOption('student');
    await page.waitForTimeout(1000);
    await captureScreenshot(page, '14-members-filter', 'Members Filter by Type');

    // Create Member Form
    await page.goto(`${BASE_URL}/members/create`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '15-members-create', 'Create Member Form');

    // ========== LOANS ==========

    console.log('\n📖 LOANS & CIRCULATION');

    await page.goto(`${BASE_URL}/loans`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '16-loans-list', 'Loans List');

    // Active Loans
    const activeTab = page.getByRole('tab', { name: /Aktif|Active/i });
    if (await activeTab.count() > 0) {
      await activeTab.click();
      await page.waitForTimeout(1000);
    }
    await captureScreenshot(page, '17-loans-active', 'Active Loans');

    // Overdue Loans
    const overdueTab = page.getByRole('tab', { name: /Terlambat|Overdue/i });
    if (await overdueTab.count() > 0) {
      await overdueTab.click();
      await page.waitForTimeout(1000);
    }
    await captureScreenshot(page, '18-loans-overdue', 'Overdue Loans');

    // Create Loan Form
    await page.goto(`${BASE_URL}/loans/create`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '19-loans-create', 'Create Loan Form');

    // ========== RESERVATIONS ==========

    console.log('\n🔖 RESERVATIONS');

    await page.goto(`${BASE_URL}/reservations`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '20-reservations-list', 'Reservations List');

    // Pending Reservations
    await page.getByRole('button', { name: /Pending/i }).click();
    await page.waitForTimeout(1000);
    await captureScreenshot(page, '21-reservations-pending', 'Pending Reservations');

    // Create Reservation Form
    await page.goto(`${BASE_URL}/reservations/create`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '22-reservations-create', 'Create Reservation Form');

    // ========== BRANCHES ==========

    console.log('\n🏢 BRANCHES');

    await page.goto(`${BASE_URL}/branches`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '23-branches-list', 'Branches List');

    // Create Branch Form
    await page.goto(`${BASE_URL}/branches/create`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '24-branches-create', 'Create Branch Form');

    // ========== COLLECTION TYPES ==========

    console.log('\n📕 COLLECTION TYPES');

    await page.goto(`${BASE_URL}/collection-types`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '25-collection-types-list', 'Collection Types List');

    // ========== LOAN RULES ==========

    console.log('\n📋 LOAN RULES');

    await page.goto(`${BASE_URL}/loan-rules`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '26-loan-rules-list', 'Loan Rules List');

    // Create Loan Rule Form
    await page.goto(`${BASE_URL}/loan-rules/create`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '27-loan-rules-create', 'Create Loan Rule Form');

    // ========== TRANSFERS ==========

    console.log('\n🚚 TRANSFERS');

    await page.goto(`${BASE_URL}/transfers`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '28-transfers-list', 'Transfer Requests List');

    // Create Transfer Form
    await page.goto(`${BASE_URL}/transfers/create`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '29-transfers-create', 'Create Transfer Form');

    // ========== REPORTS ==========

    console.log('\n📊 REPORTS');

    await page.goto(`${BASE_URL}/reports/dashboard`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '30-reports-dashboard', 'Reports Dashboard');

    // Loans Report
    await page.goto(`${BASE_URL}/reports/loans`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '31-reports-loans', 'Loans Report');

    // Overdue Report
    await page.goto(`${BASE_URL}/reports/overdue`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '32-reports-overdue', 'Overdue Report');

    // Fines Report
    await page.goto(`${BASE_URL}/reports/fines`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '33-reports-fines', 'Fines Report');

    // Collections Report
    await page.goto(`${BASE_URL}/reports/collections`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '34-reports-collections', 'Collections Report');

    // Members Report
    await page.goto(`${BASE_URL}/reports/members`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '35-reports-members', 'Members Report');

    // Branch Comparison Report
    await page.goto(`${BASE_URL}/reports/branches`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '36-reports-branches', 'Branch Comparison Report');

    // ========== DIGITAL LIBRARY (ADMIN) ==========

    console.log('\n💾 DIGITAL LIBRARY');

    await page.goto(`${BASE_URL}/digital-files`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '37-digital-files-list', 'Digital Files List (Admin)');

    // Create Digital File Form
    await page.goto(`${BASE_URL}/digital-files/create`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '38-digital-files-create', 'Upload Digital File Form');

    // ========== REPOSITORY (ADMIN) ==========

    console.log('\n📚 REPOSITORY (ADMIN)');

    await page.goto(`${BASE_URL}/repositories`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '39-repositories-list', 'Repository Submissions List');

    // Pending Submissions
    await page.getByRole('button', { name: /Pending/i }).click();
    await page.waitForTimeout(1000);
    await captureScreenshot(page, '40-repositories-pending', 'Pending Repository Submissions');

    // ========== USERS ==========

    console.log('\n👤 USERS');

    await page.goto(`${BASE_URL}/users`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '41-users-list', 'Users List');

    // Create User Form
    await page.goto(`${BASE_URL}/users/create`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '42-users-create', 'Create User Form');

    // ========== SETTINGS ==========

    console.log('\n⚙️  SETTINGS');

    await page.goto(`${BASE_URL}/settings`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '43-settings', 'Settings Page');

    // Library Info Settings
    await page.getByRole('tab', { name: /Info Perpustakaan|Library Info/i }).click();
    await page.waitForTimeout(500);
    await captureScreenshot(page, '44-settings-library-info', 'Library Info Settings');

    // Loan Rules Settings
    await page.getByRole('tab', { name: /Aturan Peminjaman|Loan Rules/i }).click();
    await page.waitForTimeout(500);
    await captureScreenshot(page, '45-settings-loan-rules', 'Loan Rules Settings');

    // Fine Settings
    await page.getByRole('tab', { name: /Denda|Fine/i }).click();
    await page.waitForTimeout(500);
    await captureScreenshot(page, '46-settings-fines', 'Fine Settings');

    // Reservation Settings
    await page.getByRole('tab', { name: /Reservasi|Reservation/i }).click();
    await page.waitForTimeout(500);
    await captureScreenshot(page, '47-settings-reservations', 'Reservation Settings');

    // OPAC Settings
    await page.getByRole('tab', { name: /OPAC/i }).click();
    await page.waitForTimeout(500);
    await captureScreenshot(page, '48-settings-opac', 'OPAC Settings');

    // Email Settings
    await page.getByRole('tab', { name: /Email/i }).click();
    await page.waitForTimeout(500);
    await captureScreenshot(page, '49-settings-email', 'Email Settings');

    // ========== MOBILE RESPONSIVE ==========

    console.log('\n📱 MOBILE RESPONSIVE');

    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 812 });

    // Mobile Dashboard
    await page.goto(`${BASE_URL}/dashboard`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '50-mobile-dashboard', 'Mobile Dashboard');

    // Mobile Collections
    await page.goto(`${BASE_URL}/collections`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '51-mobile-collections', 'Mobile Collections List');

    // Mobile Members
    await page.goto(`${BASE_URL}/members`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '52-mobile-members', 'Mobile Members List');

    // Mobile Menu (if applicable)
    const menuButton = page.getByRole('button').filter({ hasText: /Menu|Hamburger/i });
    if (await menuButton.count() > 0) {
      await menuButton.click();
      await page.waitForTimeout(500);
      await captureScreenshot(page, '53-mobile-menu', 'Mobile Navigation Menu');
    }

    // ========== PROFILE ==========

    console.log('\n👤 PROFILE');

    await page.setViewportSize({ width: 1920, height: 1080 });

    await page.goto(`${BASE_URL}/profile`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '54-profile', 'User Profile Page');

    // ========== NOTIFICATIONS ==========

    console.log('\n🔔 NOTIFICATIONS');

    await page.goto(`${BASE_URL}/notifications`);
    await page.waitForLoadState('networkidle');
    await captureScreenshot(page, '55-notifications', 'Notifications List');

    console.log('\n✅ All screenshots captured successfully!');
    console.log(`   Location: ${SCREENSHOT_DIR}/`);
    console.log(`   Total: 55+ screenshots\n`);
  });
});

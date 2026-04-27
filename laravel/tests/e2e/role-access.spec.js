import { test, expect } from '@playwright/test';

/**
 * Role-Based Access Testing & Screenshot Capture
 * Test each role and capture accessible menus
 */

const BASE_URL = process.env.BASE_URL || 'http://localhost:8000';
const SCREENSHOT_DIR = './screenshots/roles';

// User credentials for each role
const USERS = {
  super_admin: { email: 'admin@kampus.ac.id', password: 'super123', name: 'Super Admin' },
  admin: { email: 'admin@library.test', password: 'password123', name: 'Administrator' },
  branch_admin: { email: 'pusat@kampus.ac.id', password: 'branch123', name: 'Branch Admin' },
  circulation_staff: { email: 'lib-fkip@kampus.ac.id', password: 'circulation123', name: 'Circulation Staff' },
  catalog_staff: { email: 'catalog@library.test', password: 'catalog123', name: 'Catalog Staff' },
  report_viewer: { email: 'report@library.test', password: 'report123', name: 'Report Viewer' },
};

async function capture(page, name) {
  await page.screenshot({ path: `${SCREENSHOT_DIR}/${name}.png`, fullPage: true });
  console.log(`  ✓ ${name}`);
}

async function login(page, email, password) {
  await page.goto(`${BASE_URL}/login`);
  await page.getByLabel('Email').fill(email);
  await page.getByLabel('Password').fill(password);
  await page.getByRole('button', { name: 'Masuk' }).click();
  await page.waitForURL(/\/dashboard/, { timeout: 10000 });
  await page.waitForLoadState('networkidle');
}

async function checkAccessibleMenus(page) {
  const menus = [];

  // Check sidebar/navigation menus
  const menuItems = await page.locator('nav a, aside a, [role="navigation"] a').allTextContents();

  // Check if main sections are accessible
  const sections = {
    'Dashboard': await page.locator('a[href*="dashboard"]').count() > 0,
    'Members': await page.locator('a[href*="members"]').count() > 0,
    'Collections': await page.locator('a[href*="collections"]').count() > 0,
    'Loans': await page.locator('a[href*="loans"]').count() > 0,
    'Reservations': await page.locator('a[href*="reservations"]').count() > 0,
    'Reports': await page.locator('a[href*="reports"]').count() > 0,
    'Settings': await page.locator('a[href*="settings"]').count() > 0,
    'Users': await page.locator('a[href*="users"]').count() > 0,
    'Branches': await page.locator('a[href*="branches"]').count() > 0,
    'Digital Files': await page.locator('a[href*="digital-files"]').count() > 0,
    'Repositories': await page.locator('a[href*="repositories"]').count() > 0,
    'Transfers': await page.locator('a[href*="transfers"]').count() > 0,
    'Loan Rules': await page.locator('a[href*="loan-rules"]').count() > 0,
  };

  return sections;
}

async function testPageAccess(page, path, roleName) {
  try {
    await page.goto(`${BASE_URL}${path}`);
    await page.waitForLoadState('networkidle');

    // Check if accessible or forbidden
    const isForbidden = await page.getByText(/403|forbidden|unauthorized/i).count() > 0;
    const isNotFound = await page.getByText(/404|not found/i).count() > 0;

    if (isForbidden) {
      return 'forbidden';
    } else if (isNotFound) {
      return 'not_found';
    } else {
      return 'accessible';
    }
  } catch (e) {
    return 'error';
  }
}

test.describe('Super Admin Role', () => {
  test('capture super admin view', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });
    console.log('\n🔑 Testing: Super Admin');

    await login(page, USERS.super_admin.email, USERS.super_admin.password);
    await capture(page, 'role-super-admin-dashboard');

    // Check each page
    const pages = [
      '/users',
      '/branches',
      '/settings',
      '/reports/loans',
    ];

    for (const path of pages) {
      const access = await testPageAccess(page, path, 'super_admin');
      console.log(`  ${path}: ${access}`);
    }
  });
});

test.describe('Admin Role', () => {
  test('capture admin view', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });
    console.log('\n🔑 Testing: Admin');

    await login(page, USERS.admin.email, USERS.admin.password);
    await capture(page, 'role-admin-dashboard');
  });
});

test.describe('Branch Admin Role', () => {
  test('capture branch admin view', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });
    console.log('\n🔑 Testing: Branch Admin');

    await login(page, USERS.branch_admin.email, USERS.branch_admin.password);
    await capture(page, 'role-branch-admin-dashboard');

    // Check accessible menus
    const menus = await checkAccessibleMenus(page);
    console.log('  Accessible menus:', Object.keys(menus).filter(k => menus[k]));
  });
});

test.describe('Circulation Staff Role', () => {
  test('capture circulation staff view', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });
    console.log('\n🔑 Testing: Circulation Staff');

    await login(page, USERS.circulation_staff.email, USERS.circulation_staff.password);
    await capture(page, 'role-circulation-staff-dashboard');

    // Try to access loans
    await page.goto(`${BASE_URL}/loans`);
    await page.waitForLoadState('networkidle');
    await capture(page, 'role-circulation-staff-loans');

    // Try to access members
    await page.goto(`${BASE_URL}/members`);
    await page.waitForLoadState('networkidle');
    await capture(page, 'role-circulation-staff-members');
  });
});

test.describe('Catalog Staff Role', () => {
  test('capture catalog staff view', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });
    console.log('\n🔑 Testing: Catalog Staff');

    await login(page, USERS.catalog_staff.email, USERS.catalog_staff.password);
    await capture(page, 'role-catalog-staff-dashboard');

    // Access collections
    await page.goto(`${BASE_URL}/collections`);
    await page.waitForLoadState('networkidle');
    await capture(page, 'role-catalog-staff-collections');

    // Check accessible menus
    const menus = await checkAccessibleMenus(page);
    console.log('  Accessible menus:', Object.keys(menus).filter(k => menus[k]));
  });
});

test.describe('Report Viewer Role', () => {
  test('capture report viewer view', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });
    console.log('\n🔑 Testing: Report Viewer');

    await login(page, USERS.report_viewer.email, USERS.report_viewer.password);
    await capture(page, 'role-report-viewer-dashboard');

    // Try to access reports
    await page.goto(`${BASE_URL}/reports/loans`);
    await page.waitForLoadState('networkidle');
    await capture(page, 'role-report-viewer-reports');

    // Try to access collections (should be accessible for viewing)
    await page.goto(`${BASE_URL}/collections`);
    await page.waitForLoadState('networkidle');
    const isAccessible = await page.locator('a[href*="collections"]').count() > 0;
    console.log(`  Collections access: \${isAccessible ? 'accessible' : 'limited'}`);
  });
});

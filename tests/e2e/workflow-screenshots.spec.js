import { test, expect } from '@playwright/test';

/**
 * Additional Screenshot Capture for Manual Book
 * Capture specific workflows and forms
 */

const BASE_URL = 'http://localhost:8000';
const SCREENSHOT_DIR = './screenshots/workflows';

// User credentials
const USERS = {
  super_admin: { email: 'admin@kampus.ac.id', password: 'super123', name: 'Super Admin' },
  admin: { email: 'admin@library.test', password: 'password123', name: 'Administrator' },
  branch_admin: { email: 'pusat@kampus.ac.id', password: 'branch123', name: 'Branch Admin' },
  circulation_staff: { email: 'lib-fkip@kampus.ac.id', password: 'circulation123', name: 'Circulation Staff' },
  catalog_staff: { email: 'catalog@library.test', password: 'catalog123', name: 'Catalog Staff' },
  report_viewer: { email: 'report@library.test', password: 'report123', name: 'Report Viewer' },
};

async function capture(page, path, filename) {
  await page.screenshot({
    path: `${SCREENSHOT_DIR}/${path}/${filename}.png`,
    fullPage: true
  });
  console.log(`  ✓ ${filename}`);
}

async function login(page, email, password) {
  await page.goto(`${BASE_URL}/login`);
  await page.getByLabel('Email').fill(email);
  await page.getByLabel('Password').fill(password);
  await page.getByRole('button', { name: /Masuk|Log/i }).click();
  await page.waitForURL(/\/dashboard/, { timeout: 10000 });
  await page.waitForLoadState('networkidle');
}

test.describe('Workflow Screenshots', () => {

  test.beforeAll(async () => {
    const { execSync } = require('child_process');
    const dirs = ['login-workflow', 'member-workflow', 'circulation-workflow', 'catalog-workflow'];
    for (const dir of dirs) {
      execSync(`mkdir -p ${SCREENSHOT_DIR}/${dir}`, { encoding: 'utf-8' });
    }
  });

  // Login Workflow
  test.describe('Login Workflow', () => {
    test('capture login process', async ({ page }) => {
      await page.setViewportSize({ width: 1920, height: 1080 });
      console.log('\n🔷 Capturing: Login Workflow');

      // Login page
      await page.goto(`${BASE_URL}/login`);
      await capture(page, 'login-workflow', '01-login-page');

      // Fill credentials
      await page.getByLabel('Email').fill(USERS.admin.email);
      await page.getByLabel('Password').fill(USERS.admin.password);
      await capture(page, 'login-workflow', '02-login-filled');

      // Click login
      await page.getByRole('button', { name: /Masuk|Log/i }).click();
      await page.waitForLoadState('networkidle');
      await capture(page, 'login-workflow', '03-dashboard-after-login');

      // Show sidebar menu
      await capture(page, 'login-workflow', '04-sidebar-menu');
    });
  });

  // Member Workflow
  test.describe('Member Workflow', () => {
    test('capture member features', async ({ page }) => {
      await page.setViewportSize({ width: 1920, height: 1080 });
      console.log('\n🔷 Capturing: Member Workflow');

      // Use regular member login if available
      const memberUser = { email: USERS.admin.email, password: USERS.admin.password };

      await login(page, memberUser.email, memberUser.password);
      await capture(page, 'member-workflow', '01-dashboard');

      // Profile page
      await page.goto(`${BASE_URL}/profile`);
      await page.waitForLoadState('networkidle');
      await capture(page, 'member-workflow', '02-profile-page');

      // Try to access collections (should work)
      try {
        await page.goto(`${BASE_URL}/collections`);
        await page.waitForLoadState('networkidle');
        await capture(page, 'member-workflow', '03-collections-view');
      } catch (e) {
        console.log(`  Note: Collections may not be accessible`);
      }
    });
  });

  // Circulation Workflow
  test.describe('Circulation Workflow', () => {
    test('capture circulation process', async ({ page }) => {
      await page.setViewportSize({ width: 1920, height: 1080 });
      console.log('\n🔷 Capturing: Circulation Workflow');

      await login(page, USERS.circulation_staff.email, USERS.circulation_staff.password);
      await capture(page, 'circulation-workflow', '01-dashboard');

      // Loans list
      await page.goto(`${BASE_URL}/loans`);
      await page.waitForLoadState('networkidle');
      await capture(page, 'circulation-workflow', '02-loans-list');

      // Create loan page
      await page.goto(`${BASE_URL}/loans/create`);
      await page.waitForLoadState('networkidle');
      await capture(page, 'circulation-workflow', '03-loans-create-form');

      // Fill member search
      await page.getByPlaceholder(/Cari anggota|Search member/i).fill('test');
      await page.waitForTimeout(500);
      await capture(page, 'circulation-workflow', '04-loans-search-member');

      // Reservations
      await page.goto(`${BASE_URL}/reservations`);
      await page.waitForLoadState('networkidle');
      await capture(page, 'circulation-workflow', '05-reservations-list');

      // Create reservation
      await page.goto(`${BASE_URL}/reservations/create`);
      await page.waitForLoadState('networkidle');
      await capture(page, 'circulation-workflow', '06-reservations-create-form');
    });
  });

  // Catalog Workflow
  test.describe('Catalog Workflow', () => {
    test('capture catalog process', async ({ page }) => {
      await page.setViewportSize({ width: 1920, height: 1080 });
      console.log('\n🔷 Capturing: Catalog Workflow');

      await login(page, USERS.catalog_staff.email, USERS.catalog_staff.password);
      await capture(page, 'catalog-workflow', '01-dashboard');

      // Collections list
      await page.goto(`${BASE_URL}/collections`);
      await page.waitForLoadState('networkidle');
      await capture(page, 'catalog-workflow', '02-collections-list');

      // Create collection page
      await page.goto(`${BASE_URL}/collections/create`);
      await page.waitForLoadState('networkidle');
      await capture(page, 'catalog-workflow', '03-collections-create-form');

      // Fill some form fields
      await page.getByLabel('Judul').fill('Test Book Title');
      await page.getByLabel('Penulis').fill('Test Author');
      await page.waitForTimeout(500);
      await capture(page, 'catalog-workflow', '04-collections-form-filled');

      // Digital files
      await page.goto(`${BASE_URL}/digital-files`);
      await page.waitForLoadState('networkidle');
      await capture(page, 'catalog-workflow', '05-digital-files-list');

      // Create digital file
      await page.goto(`${BASE_URL}/digital-files/create`);
      await page.waitForLoadState('networkidle');
      await capture(page, 'catalog-workflow', '06-digital-files-create-form');
    });
  });

});

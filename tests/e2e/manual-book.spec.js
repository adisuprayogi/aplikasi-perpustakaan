import { test, expect } from '@playwright/test';

/**
 * Manual Book Screenshot Capture
 * Capture screenshots for each role and all accessible pages
 */

const BASE_URL = 'http://localhost:8000'; // Ganti dengan URL aplikasi Anda
const SCREENSHOT_DIR = './screenshots/manual-book';

// User credentials untuk setiap role
const USERS = {
  super_admin: { email: 'admin@kampus.ac.id', password: 'super123', name: 'Super Admin' },
  admin: { email: 'admin@library.test', password: 'password123', name: 'Administrator' },
  branch_admin: { email: 'pusat@kampus.ac.id', password: 'branch123', name: 'Branch Admin' },
  circulation_staff: { email: 'lib-fkip@kampus.ac.id', password: 'circulation123', name: 'Circulation Staff' },
  catalog_staff: { email: 'catalog@library.test', password: 'catalog123', name: 'Catalog Staff' },
  report_viewer: { email: 'report@library.test', password: 'report123', name: 'Report Viewer' },
};

// Halaman-halaman untuk setiap role
const PAGES = {
  super_admin: [
    { path: '/dashboard', name: 'dashboard', title: 'Dashboard' },
    { path: '/users', name: 'users', title: 'Manajemen User' },
    { path: '/branches', name: 'branches', title: 'Manajemen Cabang' },
    { path: '/members', name: 'members', title: 'Manajemen Anggota' },
    { path: '/collections', name: 'collections', title: 'Koleksi' },
    { path: '/loans', name: 'loans', title: 'Peminjaman' },
    { path: '/loans/create', name: 'loans-create', title: 'Peminjaman Baru' },
    { path: '/reservations', name: 'reservations', title: 'Reservasi' },
    { path: '/digital-files', name: 'digital-files', title: 'Perpustakaan Digital' },
    { path: '/repositories', name: 'repositories', title: 'Repository' },
    { path: '/loan-rules', name: 'loan-rules', title: 'Aturan Peminjaman' },
    { path: '/transfers', name: 'transfers', title: 'Transfer Antar Cabang' },
    { path: '/settings', name: 'settings', title: 'Pengaturan' },
    { path: '/reports/loans', name: 'reports-loans', title: 'Laporan Peminjaman' },
    { path: '/reports/overdue', name: 'reports-overdue', title: 'Laporan Keterlambatan' },
    { path: '/reports/fines', name: 'reports-fines', title: 'Laporan Denda' },
  ],
  admin: [
    { path: '/dashboard', name: 'dashboard', title: 'Dashboard' },
    { path: '/users', name: 'users', title: 'Manajemen User' },
    { path: '/branches', name: 'branches', title: 'Manajemen Cabang' },
    { path: '/members', name: 'members', title: 'Manajemen Anggota' },
    { path: '/collections', name: 'collections', title: 'Koleksi' },
    { path: '/loans', name: 'loans', title: 'Peminjaman' },
    { path: '/reservations', name: 'reservations', title: 'Reservasi' },
    { path: '/digital-files', name: 'digital-files', title: 'Perpustakaan Digital' },
    { path: '/repositories', name: 'repositories', title: 'Repository' },
    { path: '/settings', name: 'settings', title: 'Pengaturan' },
    { path: '/reports/loans', name: 'reports-loans', title: 'Laporan Peminjaman' },
  ],
  branch_admin: [
    { path: '/dashboard', name: 'dashboard', title: 'Dashboard' },
    { path: '/members', name: 'members', title: 'Manajemen Anggota' },
    { path: '/members/create', name: 'members-create', title: 'Tambah Anggota' },
    { path: '/collections', name: 'collections', title: 'Koleksi' },
    { path: '/loans', name: 'loans', title: 'Peminjaman' },
    { path: '/loans/create', name: 'loans-create', title: 'Peminjaman Baru' },
    { path: '/reservations', name: 'reservations', title: 'Reservasi' },
    { path: '/reservations/create', name: 'reservations-create', title: 'Buat Reservasi' },
    { path: '/digital-files', name: 'digital-files', title: 'Perpustakaan Digital' },
    { path: '/repositories', name: 'repositories', title: 'Repository' },
    { path: '/transfers', name: 'transfers', title: 'Transfer Koleksi' },
    { path: '/reports/loans', name: 'reports-loans', title: 'Laporan Peminjaman' },
  ],
  circulation_staff: [
    { path: '/dashboard', name: 'dashboard', title: 'Dashboard' },
    { path: '/loans', name: 'loans', title: 'Peminjaman' },
    { path: '/loans/create', name: 'loans-create', title: 'Peminjaman Baru' },
    { path: '/reservations', name: 'reservations', title: 'Reservasi' },
    { path: '/reservations/create', name: 'reservations-create', title: 'Buat Reservasi' },
    { path: '/members', name: 'members', title: 'Daftar Anggota (View)' },
    { path: '/collections', name: 'collections', title: 'Koleksi (View)' },
  ],
  catalog_staff: [
    { path: '/dashboard', name: 'dashboard', title: 'Dashboard' },
    { path: '/collections', name: 'collections', title: 'Koleksi' },
    { path: '/collections/create', name: 'collections-create', title: 'Tambah Koleksi' },
    { path: '/collections/labels', name: 'collections-labels', title: 'Generate Barcode' },
    { path: '/digital-files', name: 'digital-files', title: 'Perpustakaan Digital' },
    { path: '/digital-files/create', name: 'digital-files-create', title: 'Upload File Digital' },
    { path: '/repositories', name: 'repositories', title: 'Repository' },
    { path: '/repositories/create', name: 'repositories-create', title: 'Upload Repository' },
  ],
  report_viewer: [
    { path: '/dashboard', name: 'dashboard', title: 'Dashboard' },
    { path: '/reports/loans', name: 'reports-loans', title: 'Laporan Peminjaman' },
    { path: '/reports/overdue', name: 'reports-overdue', title: 'Laporan Keterlambatan' },
    { path: '/reports/fines', name: 'reports-fines', title: 'Laporan Denda' },
    { path: '/reports/collections', name: 'reports-collections', title: 'Laporan Koleksi' },
    { path: '/reports/members', name: 'reports-members', title: 'Laporan Anggota' },
  ],
};

async function capture(page, role, pageName, filename) {
  const dir = `${SCREENSHOT_DIR}/${role}`;
  await page.screenshot({
    path: `${dir}/${filename}.png`,
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

test.describe('Manual Book Screenshot Capture', () => {

  test.beforeAll(async () => {
    // Create screenshot directories
    const { execSync } = require('child_process');
    for (const role of Object.keys(USERS)) {
      execSync(`mkdir -p ${SCREENSHOT_DIR}/${role}`, { encoding: 'utf-8' });
    }
  });

  // Super Admin
  test.describe('Super Admin', () => {
    test('capture all pages', async ({ page }) => {
      await page.setViewportSize({ width: 1920, height: 1080 });
      console.log('\n🔷 Capturing: Super Admin');

      await login(page, USERS.super_admin.email, USERS.super_admin.password);
      await capture(page, 'super_admin', 'dashboard', '01-dashboard');

      for (const pageInfo of PAGES.super_admin.slice(1)) {
        try {
          await page.goto(`${BASE_URL}${pageInfo.path}`);
          await page.waitForLoadState('networkidle');
          await page.waitForTimeout(1000); // Wait for animations
          await capture(page, 'super_admin', pageInfo.name, `02-${pageInfo.name}`);
        } catch (e) {
          console.log(`  ✗ ${pageInfo.name}: ${e.message}`);
        }
      }
    });
  });

  // Admin
  test.describe('Admin', () => {
    test('capture all pages', async ({ page }) => {
      await page.setViewportSize({ width: 1920, height: 1080 });
      console.log('\n🔷 Capturing: Admin');

      await login(page, USERS.admin.email, USERS.admin.password);
      await capture(page, 'admin', 'dashboard', '01-dashboard');

      for (const pageInfo of PAGES.admin.slice(1)) {
        try {
          await page.goto(`${BASE_URL}${pageInfo.path}`);
          await page.waitForLoadState('networkidle');
          await page.waitForTimeout(1000);
          await capture(page, 'admin', pageInfo.name, `02-${pageInfo.name}`);
        } catch (e) {
          console.log(`  ✗ ${pageInfo.name}: ${e.message}`);
        }
      }
    });
  });

  // Branch Admin
  test.describe('Branch Admin', () => {
    test('capture all pages', async ({ page }) => {
      await page.setViewportSize({ width: 1920, height: 1080 });
      console.log('\n🔷 Capturing: Branch Admin');

      await login(page, USERS.branch_admin.email, USERS.branch_admin.password);
      await capture(page, 'branch_admin', 'dashboard', '01-dashboard');

      for (const pageInfo of PAGES.branch_admin.slice(1)) {
        try {
          await page.goto(`${BASE_URL}${pageInfo.path}`);
          await page.waitForLoadState('networkidle');
          await page.waitForTimeout(1000);
          await capture(page, 'branch_admin', pageInfo.name, `02-${pageInfo.name}`);
        } catch (e) {
          console.log(`  ✗ ${pageInfo.name}: ${e.message}`);
        }
      }
    });
  });

  // Circulation Staff
  test.describe('Circulation Staff', () => {
    test('capture all pages', async ({ page }) => {
      await page.setViewportSize({ width: 1920, height: 1080 });
      console.log('\n🔷 Capturing: Circulation Staff');

      await login(page, USERS.circulation_staff.email, USERS.circulation_staff.password);
      await capture(page, 'circulation_staff', 'dashboard', '01-dashboard');

      for (const pageInfo of PAGES.circulation_staff.slice(1)) {
        try {
          await page.goto(`${BASE_URL}${pageInfo.path}`);
          await page.waitForLoadState('networkidle');
          await page.waitForTimeout(1000);
          await capture(page, 'circulation_staff', pageInfo.name, `02-${pageInfo.name}`);
        } catch (e) {
          console.log(`  ✗ ${pageInfo.name}: ${e.message}`);
        }
      }
    });
  });

  // Catalog Staff
  test.describe('Catalog Staff', () => {
    test('capture all pages', async ({ page }) => {
      await page.setViewportSize({ width: 1920, height: 1080 });
      console.log('\n🔷 Capturing: Catalog Staff');

      await login(page, USERS.catalog_staff.email, USERS.catalog_staff.password);
      await capture(page, 'catalog_staff', 'dashboard', '01-dashboard');

      for (const pageInfo of PAGES.catalog_staff.slice(1)) {
        try {
          await page.goto(`${BASE_URL}${pageInfo.path}`);
          await page.waitForLoadState('networkidle');
          await page.waitForTimeout(1000);
          await capture(page, 'catalog_staff', pageInfo.name, `02-${pageInfo.name}`);
        } catch (e) {
          console.log(`  ✗ ${pageInfo.name}: ${e.message}`);
        }
      }
    });
  });

  // Report Viewer
  test.describe('Report Viewer', () => {
    test('capture all pages', async ({ page }) => {
      await page.setViewportSize({ width: 1920, height: 1080 });
      console.log('\n🔷 Capturing: Report Viewer');

      await login(page, USERS.report_viewer.email, USERS.report_viewer.password);
      await capture(page, 'report_viewer', 'dashboard', '01-dashboard');

      for (const pageInfo of PAGES.report_viewer.slice(1)) {
        try {
          await page.goto(`${BASE_URL}${pageInfo.path}`);
          await page.waitForLoadState('networkidle');
          await page.waitForTimeout(1000);
          await capture(page, 'report_viewer', pageInfo.name, `02-${pageInfo.name}`);
        } catch (e) {
          console.log(`  ✗ ${pageInfo.name}: ${e.message}`);
        }
      }
    });
  });

  // Public Pages (OPAC, Digital Library, Repository)
  test.describe('Public Pages', () => {
    test('capture public pages', async ({ page }) => {
      await page.setViewportSize({ width: 1920, height: 1080 });
      console.log('\n🔷 Capturing: Public Pages');

      const publicPages = [
        { path: '/opac', name: 'opac', title: 'OPAC' },
        { path: '/opac/search?q=laskar', name: 'opac-search', title: 'OPAC Search' },
        { path: '/digital-library', name: 'digital-library', title: 'Perpustakaan Digital' },
        { path: '/repository', name: 'repository', title: 'Repository' },
        { path: '/login', name: 'login', title: 'Halaman Login' },
      ];

      const dir = `${SCREENSHOT_DIR}/public`;
      page.waitForTimeout(1000);

      for (const pageInfo of publicPages) {
        try {
          await page.goto(`${BASE_URL}${pageInfo.path}`);
          await page.waitForLoadState('networkidle');
          await page.waitForTimeout(1500);

          await page.screenshot({
            path: `${dir}/${pageInfo.name}.png`,
            fullPage: true
          });
          console.log(`  ✓ ${pageInfo.name}`);
        } catch (e) {
          console.log(`  ✗ ${pageInfo.name}: ${e.message}`);
        }
      }
    });
  });

});

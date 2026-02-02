import { test, expect } from '@playwright/test';

/**
 * Dashboard Tests
 * Tests for the main dashboard functionality
 */

// Helper function to login
async function login(page) {
  await page.goto('/login');
  await page.getByLabel('Email').fill('admin@library.test');
  await page.getByLabel('Password').fill('password123');
  await page.getByRole('button', { name: /Masuk|Log/i }).click();
  await expect(page).toHaveURL(/\/dashboard/);
}

test.describe('Dashboard', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('should display dashboard with statistics cards', async ({ page }) => {
    // Check page title
    await expect(page).toHaveTitle(/Dashboard/);

    // Check for statistics cards
    await expect(page.getByText(/Total Anggota|Members/i)).toBeVisible();
    await expect(page.getByText(/Total Koleksi|Collections/i)).toBeVisible();
    await expect(page.getByText(/Peminjaman Aktif|Active Loans/i)).toBeVisible();
    await expect(page.getByText(/Terlambat|Overdue/i)).toBeVisible();
  });

  test('should display circulation trends chart', async ({ page }) => {
    // Check for chart canvas element
    const canvas = page.locator('#circulationChart');
    await expect(canvas).toBeVisible();
  });

  test('should display popular items', async ({ page }) => {
    // Check for popular items section
    await expect(page.getByText(/Koleksi Terpopuler|Popular Items/i)).toBeVisible();

    // Check if popular items list exists (may be empty if no data)
    const popularItemsSection = page.locator('text=/Koleksi Terpopuler|Popular Items/i');
    await expect(popularItemsSection).toBeVisible();
  });

  test('should navigate to reports section', async ({ page }) => {
    // Click on reports menu
    await page.getByText('Laporan').click();

    // Should show report links
    await expect(page.getByRole('link', { name: /Dashboard/i })).toBeVisible();
    await expect(page.getByRole('link', { name: /Peminjaman|Loans/i })).toBeVisible();
    await expect(page.getByRole('link', { name: /Keterlambatan|Overdue/i })).toBeVisible();
  });

  test('should display quick stats', async ({ page }) => {
    // Check for quick stats section
    await expect(page.getByText(/Ringkasan Cepat|Quick Stats/i)).toBeVisible();
    await expect(page.getByText(/Total Anggota/i)).toBeVisible();
    await expect(page.getByText(/Total Koleksi/i)).toBeVisible();
  });

  test('should be responsive on mobile', async ({ page }) => {
    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });

    // Reload page
    await page.reload();

    // Check that dashboard still loads
    await expect(page.getByText(/Total Anggota|Members/i)).toBeVisible();

    // Check mobile menu button
    const menuButton = page.getByRole('button').filter({ hasText: /Menu|Hamburger/i });
    // Mobile menu might exist, but we just check dashboard content is visible
    await expect(page.getByText(/Total Anggota|Members/i)).toBeVisible();
  });

  test('should display collection type distribution chart', async ({ page }) => {
    // Check for collection type chart
    const collectionChart = page.locator('#collectionTypeChart');
    await expect(collectionChart).toBeVisible();
  });

  test('should display member type distribution chart', async ({ page }) => {
    // Check for member type chart
    const memberChart = page.locator('#memberTypeChart');
    await expect(memberChart).toBeVisible();
  });

  test('should navigate to collections from dashboard', async ({ page }) => {
    // Click on collections menu
    await page.getByText('Koleksi').click();

    // Should navigate to collections page
    await expect(page).toHaveURL(/\/collections/);
  });

  test('should navigate to members from dashboard', async ({ page }) => {
    // Click on members menu
    await page.getByText('Anggota').click();

    // Should navigate to members page
    await expect(page).toHaveURL(/\/members/);
  });

  test('should navigate to loans from dashboard', async ({ page }) => {
    // Click on circulation/sirkulasi menu
    await page.getByText(/Sirkulasi|Circulation/i).click();

    // Should navigate to loans page
    await expect(page).toHaveURL(/\/loans/);
  });
});

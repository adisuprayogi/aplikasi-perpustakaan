import { test, expect } from '@playwright/test';

/**
 * Setup & Smoke Tests
 * Basic tests to verify the application is running correctly
 */

test.describe('Application Setup', () => {
  test('should load the homepage', async ({ page }) => {
    await page.goto('/');

    // Should redirect to either OPAC (public) or dashboard (authenticated)
    const currentUrl = page.url();
    expect(currentUrl).toMatch(/\/(opac|dashboard)/);
  });

  test('should display OPAC page', async ({ page }) => {
    await page.goto('/opac');

    // Check page title
    await expect(page).toHaveTitle(/OPAC|Pencarian|Katalog/);
  });

  test('should display search form on OPAC', async ({ page }) => {
    await page.goto('/opac');

    // Check for search input
    await expect(page.getByPlaceholder(/Cari|Search|Kata kunci/i)).toBeVisible();
  });

  test('should display digital library page', async ({ page }) => {
    await page.goto('/digital-library');

    // Check page loads
    await expect(page.getByText(/Perpustakaan Digital|Digital Library/i)).toBeVisible();
  });

  test('should display repository page', async ({ page }) => {
    await page.goto('/repository');

    // Check page loads
    await expect(page.getByText(/Repository|Institutional/i)).toBeVisible();
  });

  test('should display login page', async ({ page }) => {
    await page.goto('/login');

    // Check page title
    await expect(page).toHaveTitle(/Login|Masuk/);

    // Check form elements
    await expect(page.getByLabel('Email')).toBeVisible();
    await expect(page.getByLabel('Password')).toBeVisible();
    await expect(page.getByRole('button', { name: /Masuk|Log in/i })).toBeVisible();
  });

  test('should be responsive on mobile viewport', async ({ page }) => {
    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });

    await page.goto('/opac');

    // Check that mobile view loads
    await expect(page.getByPlaceholder(/Cari|Search/i)).toBeVisible();
  });

  test('should handle 404 page', async ({ page }) => {
    const response = await page.goto('/this-page-does-not-exist');

    // Should show 404 or redirect to 404 page
    expect(response?.status()).toBe(404);
  });
});

test.describe('Health Check', () => {
  test('should respond to health endpoint', async ({ page }) => {
    const response = await page.request.get('/up');

    expect(response.ok()).toBeTruthy();
  });
});

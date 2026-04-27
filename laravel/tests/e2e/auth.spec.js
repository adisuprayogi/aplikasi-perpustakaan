import { test, expect } from '@playwright/test';

/**
 * Authentication Tests
 * Tests for login, logout, and password reset functionality
 */

test.describe('Authentication', () => {
  test.beforeEach(async ({ page }) => {
    // Navigate to login page
    await page.goto('/login');
  });

  test('should display login form', async ({ page }) => {
    // Check page title
    await expect(page).toHaveTitle(/Login -/);

    // Check form elements
    await expect(page.getByLabel('Email')).toBeVisible();
    await expect(page.getByLabel('Password')).toBeVisible();
    await expect(page.getByRole('button', { name: /Masuk/i })).toBeVisible();
  });

  test('should show error with invalid credentials', async ({ page }) => {
    // Fill login form with invalid credentials
    await page.getByLabel('Email').fill('invalid@example.com');
    await page.getByLabel('Password').fill('wrongpassword');
    await page.getByRole('button', { name: /Masuk/i }).click();

    // Check for error message - use more specific selector
    await expect(page.locator('.text-red-600').filter({ hasText: /credentials/i })).toBeVisible();
  });

  test('should login successfully with valid credentials', async ({ page }) => {
    // Note: This requires a test user to exist in the database
    // You can create one using database seeders or factories

    // Fill login form
    await page.getByLabel('Email').fill('admin@library.test');
    await page.getByLabel('Password').fill('password123');
    await page.getByRole('button', { name: /Masuk/i }).click();

    // Should redirect to dashboard
    await expect(page).toHaveURL(/\/dashboard/);
    // Use more specific selector for dashboard
    await expect(page.locator('h1').filter({ hasText: /Selamat Datang/i })).toBeVisible();
  });

  test('should logout successfully', async ({ page }) => {
    // First login
    await page.getByLabel('Email').fill('admin@library.test');
    await page.getByLabel('Password').fill('password123');
    await page.getByRole('button', { name: /Masuk/i }).click();

    // Wait for dashboard
    await expect(page).toHaveURL(/\/dashboard/);

    // Click user dropdown button (the button with user avatar/name)
    await page.locator('header button').filter({ hasText: /admin/i }).click();

    // Wait for dropdown and click logout button
    await page.locator('form button[type="submit"]').filter({ hasText: /Keluar/i }).click();

    // Should redirect to login or opac page (depending on app behavior)
    await expect(page).toHaveURL(/\/(login|opac)/);
  });

  test('should redirect to dashboard when already authenticated', async ({ page }) => {
    // First login
    await page.getByLabel('Email').fill('admin@library.test');
    await page.getByLabel('Password').fill('password123');
    await page.getByRole('button', { name: 'Masuk' }).click();

    // Wait for dashboard
    await expect(page).toHaveURL(/\/dashboard/);

    // Navigate to login page again
    await page.goto('/login');

    // Should redirect back to dashboard
    await expect(page).toHaveURL(/\/dashboard/);
  });
});

test.describe('Password Reset', () => {
  test('should display password reset form', async ({ page }) => {
    await page.goto('/forgot-password');

    // Check page title
    await expect(page).toHaveTitle(/Lupa Password -/);

    // Check form elements
    await expect(page.getByLabel('Email')).toBeVisible();
    await page.getByRole('button', { name: /Email Password Reset Link/i }).click();
  });

  test('should send password reset link', async ({ page }) => {
    await page.goto('/forgot-password');

    // Fill email
    await page.getByLabel('Email').fill('admin@library.test');
    await page.getByRole('button', { name: /Email Password Reset Link/i }).click();

    // Check success message - status element
    await expect(page.locator('.text-green-600, .bg-green-50')).toBeVisible();
  });
});

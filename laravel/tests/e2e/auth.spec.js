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
    await expect(page).toHaveTitle(/Login/);

    // Check form elements
    await expect(page.getByLabel('Email')).toBeVisible();
    await expect(page.getByLabel('Password')).toBeVisible();
    await expect(page.getByRole('button', { name: /Masuk|Log/i })).toBeVisible();
  });

  test('should show error with invalid credentials', async ({ page }) => {
    // Fill login form with invalid credentials
    await page.getByLabel('Email').fill('invalid@example.com');
    await page.getByLabel('Password').fill('wrongpassword');
    await page.getByRole('button', { name: /Masuk|Log/i }).click();

    // Check for error message
    await expect(page.getByText(/credentials|email|password/i)).toBeVisible();
  });

  test('should login successfully with valid credentials', async ({ page }) => {
    // Note: This requires a test user to exist in the database
    // You can create one using database seeders or factories

    // Fill login form
    await page.getByLabel('Email').fill('admin@library.test');
    await page.getByLabel('Password').fill('password123');
    await page.getByRole('button', { name: /Masuk|Log/i }).click();

    // Should redirect to dashboard
    await expect(page).toHaveURL(/\/dashboard/);
    await expect(page.getByText('Dashboard')).toBeVisible();
  });

  test('should logout successfully', async ({ page }) => {
    // First login
    await page.getByLabel('Email').fill('admin@library.test');
    await page.getByLabel('Password').fill('password123');
    await page.getByRole('button', { name: /Masuk|Log/i }).click();

    // Wait for dashboard
    await expect(page).toHaveURL(/\/dashboard/);

    // Click logout button (usually in dropdown menu)
    await page.getByRole('button').filter({ hasText: /Admin|Profile/i }).click();
    await page.getByRole('link', { name: /Logout|Keluar/i }).click();

    // Should redirect to login page
    await expect(page).toHaveURL(/\/login/);
  });

  test('should redirect to dashboard when already authenticated', async ({ page }) => {
    // First login
    await page.getByLabel('Email').fill('admin@library.test');
    await page.getByLabel('Password').fill('password123');
    await page.getByRole('button', { name: /Masuk|Log/i }).click();

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
    await expect(page).toHaveTitle(/Lupa|Forgot|Reset/i);

    // Check form elements
    await expect(page.getByLabel('Email')).toBeVisible();
    await page.getByRole('button', { name: /Kirim|Send/i }).click();
  });

  test('should send password reset link', async ({ page }) => {
    await page.goto('/forgot-password');

    // Fill email
    await page.getByLabel('Email').fill('admin@library.test');
    await page.getByRole('button', { name: /Kirim|Send/i }).click();

    // Check success message
    await expect(page.getByText(/link|email|terkirim/i)).toBeVisible();
  });
});

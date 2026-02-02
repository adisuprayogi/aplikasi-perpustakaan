import { test, expect } from '@playwright/test';

/**
 * Screenshot Automation - Part 6: Settings & Admin
 */

const BASE_URL = process.env.BASE_URL || 'http://localhost:8000';
const SCREENSHOT_DIR = './screenshots';

async function capture(page, name) {
  await page.screenshot({ path: `${SCREENSHOT_DIR}/${name}.png`, fullPage: true });
  console.log(`✓ ${name}`);
}

async function login(page) {
  await page.goto(`${BASE_URL}/login`);
  await page.getByLabel('Email').fill('admin@library.test');
  await page.getByLabel('Password').fill('password123');
  await page.getByRole('button', { name: /Masuk|Log/i }).click();
  await page.waitForURL(/\/dashboard/, { timeout: 10000 });
  await page.waitForLoadState('networkidle');
}

test.describe('Settings Screenshots', () => {
  test.beforeAll(async () => {
    console.log('\n⚙️ SETTINGS & ADMIN');
  });

  test('capture settings and admin pages', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });

    // Login once
    await login(page);

    // Branches
    await page.goto(`${BASE_URL}/branches`);
    await page.waitForLoadState('networkidle');
    await capture(page, '25-branches-list');

    // Loan Rules
    await page.goto(`${BASE_URL}/loan-rules`);
    await page.waitForLoadState('networkidle');
    await capture(page, '26-loan-rules');

    // Settings
    await page.goto(`${BASE_URL}/settings`);
    await page.waitForLoadState('networkidle');
    await capture(page, '27-settings');

    // Users
    await page.goto(`${BASE_URL}/users`);
    await page.waitForLoadState('networkidle');
    await capture(page, '28-users-list');
  });
});

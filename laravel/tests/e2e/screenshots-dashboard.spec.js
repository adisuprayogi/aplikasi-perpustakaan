import { test, expect } from '@playwright/test';

/**
 * Screenshot Automation - Part 2: Admin Dashboard & Login
 */

const BASE_URL = process.env.BASE_URL || 'http://localhost:8000';
const SCREENSHOT_DIR = './screenshots';

async function capture(page, name) {
  await page.screenshot({ path: `${SCREENSHOT_DIR}/${name}.png`, fullPage: true });
  console.log(`✓ ${name}`);
}

async function login(page) {
  await page.goto(`${BASE_URL}/login`);
  await page.waitForLoadState('networkidle');
  await page.getByLabel('Email').fill('admin@library.test');
  await page.getByLabel('Password').fill('password123');
  await page.getByRole('button', { name: /Masuk|Log/i }).click();
  await page.waitForURL(/\/dashboard/, { timeout: 10000 });
  await page.waitForLoadState('networkidle');
}

test.describe('Admin Dashboard Screenshots', () => {
  test('capture admin dashboard', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });
    console.log('\n🎛️  ADMIN DASHBOARD');

    await login(page);
    await capture(page, '07-dashboard');
    await capture(page, '08-dashboard-stats');
    await capture(page, '09-dashboard-charts');
  });
});

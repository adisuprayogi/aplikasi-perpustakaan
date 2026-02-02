import { test, expect } from '@playwright/test';

/**
 * Screenshot Automation - Part 4: Loans & Circulation
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

test.describe('Loans Screenshots', () => {
  test.beforeAll(async () => {
    console.log('\n📚 LOANS & CIRCULATION');
  });

  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('capture loans pages', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });

    await page.goto(`${BASE_URL}/loans`);
    await page.waitForLoadState('networkidle');
    await capture(page, '16-loans-list');

    // Try filter by status
    const statusFilter = page.locator('select[name="status"]').first();
    if (await statusFilter.count() > 0) {
      await statusFilter.selectOption('active');
      await page.waitForTimeout(1000);
      await capture(page, '17-loans-filter-active');
    }

    await page.goto(`${BASE_URL}/loans/create`);
    await page.waitForLoadState('networkidle');
    await capture(page, '18-loans-create');
  });
});

import { test, expect } from '@playwright/test';

/**
 * Screenshot Automation - Part 5: Reports
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

test.describe('Reports Screenshots', () => {
  test.beforeAll(async () => {
    console.log('\n📊 REPORTS');
  });

  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('capture reports pages', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });

    // Loans Report
    await page.goto(`${BASE_URL}/reports/loans`);
    await page.waitForLoadState('networkidle');
    await capture(page, '19-reports-loans');

    // Overdue Report
    await page.goto(`${BASE_URL}/reports/overdue`);
    await page.waitForLoadState('networkidle');
    await capture(page, '20-reports-overdue');

    // Fines Report
    await page.goto(`${BASE_URL}/reports/fines`);
    await page.waitForLoadState('networkidle');
    await capture(page, '21-reports-fines');

    // Collections Report
    await page.goto(`${BASE_URL}/reports/collections`);
    await page.waitForLoadState('networkidle');
    await capture(page, '22-reports-collections');

    // Members Report
    await page.goto(`${BASE_URL}/reports/members`);
    await page.waitForLoadState('networkidle');
    await capture(page, '23-reports-members');

    // Branches Report
    await page.goto(`${BASE_URL}/reports/branches`);
    await page.waitForLoadState('networkidle');
    await capture(page, '24-reports-branches');
  });
});

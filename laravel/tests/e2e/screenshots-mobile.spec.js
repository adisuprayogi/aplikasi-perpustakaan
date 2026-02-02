import { test, expect } from '@playwright/test';

/**
 * Screenshot Automation - Part 8: Mobile Responsive Views
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

test.describe('Mobile Screenshots', () => {
  test.beforeAll(async () => {
    console.log('\n📱 MOBILE RESPONSIVE');
  });

  test('capture mobile views', async ({ page }) => {
    // Mobile viewport
    await page.setViewportSize({ width: 375, height: 812 }); // iPhone X

    // Public pages
    await page.goto(`${BASE_URL}/opac`);
    await page.waitForLoadState('networkidle');
    await capture(page, '34-mobile-opac-home');

    await page.goto(`${BASE_URL}/digital-library`);
    await page.waitForLoadState('networkidle');
    await capture(page, '35-mobile-digital-library');

    // Admin pages
    await login(page);
    await page.goto(`${BASE_URL}/dashboard`);
    await page.waitForLoadState('networkidle');
    await capture(page, '36-mobile-dashboard');

    await page.goto(`${BASE_URL}/collections`);
    await page.waitForLoadState('networkidle');
    await capture(page, '37-mobile-collections');

    await page.goto(`${BASE_URL}/loans`);
    await page.waitForLoadState('networkidle');
    await capture(page, '38-mobile-loans');
  });
});

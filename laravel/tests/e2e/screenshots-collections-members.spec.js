import { test, expect } from '@playwright/test';

/**
 * Screenshot Automation - Part 3: Collections & Members
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

test.describe('Collections Screenshots', () => {
  test.beforeAll(async () => {
    console.log('\n📚 COLLECTIONS');
  });

  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('capture collections pages', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });

    await page.goto(`${BASE_URL}/collections`);
    await page.waitForLoadState('networkidle');
    await capture(page, '10-collections-list');

    // Try search, skip if input not found
    const searchInput = page.locator('input[type="search"], input[placeholder*="Cari" i], input[placeholder*="cari" i], input[placeholder*="Search" i], input[name="search"]').first();
    if (await searchInput.count() > 0) {
      await searchInput.fill('test');
      await page.waitForTimeout(1500);
      await capture(page, '11-collections-search');
    }

    await page.goto(`${BASE_URL}/collections/create`);
    await page.waitForLoadState('networkidle');
    await capture(page, '12-collections-create');
  });
});

test.describe('Members Screenshots', () => {
  test.beforeAll(async () => {
    console.log('\n👥 MEMBERS');
  });

  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('capture members pages', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });

    await page.goto(`${BASE_URL}/members`);
    await page.waitForLoadState('networkidle');
    await capture(page, '13-members-list');

    // Try filter by type
    const typeFilter = page.locator('select[name="type"], select[name="member_type"]').first();
    if (await typeFilter.count() > 0) {
      await typeFilter.selectOption('student');
      await page.waitForTimeout(1000);
      await capture(page, '14-members-filter');
    }

    await page.goto(`${BASE_URL}/members/create`);
    await page.waitForLoadState('networkidle');
    await capture(page, '15-members-create');
  });
});

import { test, expect } from '@playwright/test';

/**
 * Screenshot Automation - Part 7: Digital Library & Repository
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

test.describe('Digital Library & Repository Screenshots', () => {
  test.beforeAll(async () => {
    console.log('\n📚 DIGITAL LIBRARY & REPOSITORY');
  });

  test.describe.configure({ mode: 'serial' });

  test('capture all pages', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });

    // Login once
    await login(page);

    // Admin Digital Files
    await page.goto(`${BASE_URL}/digital-files`);
    await page.waitForLoadState('networkidle');
    await capture(page, '29-digital-files-list');

    await page.goto(`${BASE_URL}/digital-files/create`);
    await page.waitForLoadState('networkidle');
    await capture(page, '30-digital-files-create');

    // Admin Repository
    await page.goto(`${BASE_URL}/repositories`);
    await page.waitForLoadState('networkidle');
    await capture(page, '31-repositories-list');

    await page.goto(`${BASE_URL}/repositories/create`);
    await page.waitForLoadState('networkidle');
    await capture(page, '32-repositories-create');
  });

  test('capture public repository pages', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });

    // Public Repository Detail (if exists)
    await page.goto(`${BASE_URL}/repository`);
    await page.waitForLoadState('networkidle');
    await capture(page, '33-repository-public');
  });
});

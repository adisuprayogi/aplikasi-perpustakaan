import { test, expect } from '@playwright/test';

/**
 * Screenshot Automation - Part 1: Public Pages
 */

const BASE_URL = process.env.BASE_URL || 'http://localhost:8000';
const SCREENSHOT_DIR = './screenshots';

async function capture(page, name) {
  await page.screenshot({ path: `${SCREENSHOT_DIR}/${name}.png`, fullPage: true });
  console.log(`✓ ${name}`);
}

test.describe('Public Pages Screenshots', () => {
  test('capture public pages', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 });
    console.log('\n📖 PUBLIC PAGES');

    await page.goto(`${BASE_URL}/opac`);
    await page.waitForLoadState('networkidle');
    await capture(page, '01-opac-homepage');

    await page.goto(`${BASE_URL}/opac/search?q=test`);
    await page.waitForLoadState('networkidle');
    await capture(page, '02-opac-search-results');

    await page.goto(`${BASE_URL}/opac/advanced`);
    await page.waitForLoadState('networkidle');
    await capture(page, '03-opac-advanced-search');

    await page.goto(`${BASE_URL}/digital-library`);
    await page.waitForLoadState('networkidle');
    await capture(page, '04-digital-library');

    await page.goto(`${BASE_URL}/repository`);
    await page.waitForLoadState('networkidle');
    await capture(page, '05-repository');

    await page.goto(`${BASE_URL}/login`);
    await page.waitForLoadState('networkidle');
    await capture(page, '06-login-page');
  });
});

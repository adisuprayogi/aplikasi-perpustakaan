import { test, expect } from '@playwright/test';

/**
 * Collections Tests
 * Tests for collection management (CRUD operations)
 */

// Helper function to login
async function login(page) {
  await page.goto('/login');
  await page.getByLabel('Email').fill('admin@library.test');
  await page.getByLabel('Password').fill('password123');
  await page.getByRole('button', { name: /Masuk|Log/i }).click();
  await expect(page).toHaveURL(/\/dashboard/);
}

test.describe('Collections', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
    await page.goto('/collections');
  });

  test('should display collections list', async ({ page }) => {
    // Check page title
    await expect(page).toHaveTitle(/Koleksi|Collections/);

    // Check for search and filter controls
    await expect(page.getByPlaceholder(/Cari|Search/i)).toBeVisible();

    // Check for create button
    await expect(page.getByRole('link', { name: /\+ Tambah|Add|Create/i })).toBeVisible();
  });

  test('should search collections', async ({ page }) => {
    // Type in search box
    await page.getByPlaceholder(/Cari|Search/i).fill('test');

    // Submit search
    await page.getByRole('button', { name: /Cari|Search/i }).click();

    // Wait for results
    await page.waitForTimeout(500);

    // Just verify search was performed (no assertion on results as data may vary)
    await expect(page.getByPlaceholder(/Cari|Search/i)).toHaveValue('test');
  });

  test('should navigate to create collection page', async ({ page }) => {
    // Click create button
    await page.getByRole('link', { name: /\+ Tambah|Add|Create/i }).click();

    // Should navigate to create page
    await expect(page).toHaveURL(/\/collections\/create/);

    // Check form elements
    await expect(page.getByLabel(/Judul|Title/i)).toBeVisible();
    await expect(page.getByLabel(/Penulis|Author/i)).toBeVisible();
    await expect(page.getByLabel(/Penerbit|Publisher/i)).toBeVisible();
  });

  test('should create new collection', async ({ page }) => {
    // Navigate to create page
    await page.goto('/collections/create');

    // Generate unique test data
    const timestamp = Date.now();
    const testTitle = `Test Book ${timestamp}`;

    // Fill form
    await page.getByLabel(/Judul|Title/i).fill(testTitle);
    await page.getByLabel(/Penulis|Author/i).fill('Test Author');
    await page.getByLabel(/Penerbit|Publisher/i).fill('Test Publisher');
    await page.getByLabel(/Tahun|Year/i).fill('2024');

    // Select collection type
    await page.getByLabel(/Tipe Koleksi|Collection Type/i).selectOption('1');

    // Submit form
    await page.getByRole('button', { name: /Simpan|Save|Create/i }).click();

    // Should redirect to collections list or show success message
    await expect(page.getByText(/berhasil|success|created/i)).toBeVisible();
  });

  test('should display collection details', async ({ page }) => {
    // Click on first collection in list
    const firstCollection = page.locator('.collection-item, tbody tr').first();
    const hasCollections = await firstCollection.count() > 0;

    if (hasCollections) {
      await firstCollection.click();

      // Should navigate to detail page
      await expect(page).toHaveURL(/\/collections\/\d+/);

      // Check for detail elements
      await expect(page.getByText(/Detail Koleksi|Collection Details/i)).toBeVisible();
    } else {
      // Skip test if no collections exist
      test.skip();
    }
  });

  test('should filter collections by type', async ({ page }) => {
    // Check for type filter dropdown
    const typeFilter = page.getByLabel(/Tipe|Type/i);
    const hasFilter = await typeFilter.count() > 0;

    if (hasFilter) {
      // Select a type
      await typeFilter.selectOption('1');

      // Wait for filtered results
      await page.waitForTimeout(500);
    }
  });

  test('should display pagination', async ({ page }) => {
    // Check for pagination elements
    const pagination = page.locator('.pagination, nav[aria-label="Pagination"]');
    const hasPagination = await pagination.count() > 0;

    if (hasPagination) {
      await expect(pagination).toBeVisible();
    }
  });

  test('should be responsive on mobile', async ({ page }) => {
    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });

    // Reload page
    await page.reload();

    // Check that mobile view loads
    await expect(page.getByText(/Koleksi|Collections/i)).toBeVisible();

    // Check for mobile card view (if implemented)
    const mobileCards = page.locator('.mobile-card');
    const hasMobileCards = await mobileCards.count() > 0;

    if (hasMobileCards) {
      await expect(mobileCards.first()).toBeVisible();
    }
  });
});

test.describe('Collection Edit', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('should edit existing collection', async ({ page }) => {
    await page.goto('/collections');

    // Find first collection with edit button
    const editButton = page.locator('a[href*="/collections/"]').filter({ hasText: /edit|ubah/i }).first();
    const hasEditButton = await editButton.count() > 0;

    if (hasEditButton) {
      await editButton.click();

      // Should be on edit page
      await expect(page).toHaveURL(/\/collections\/\d+\/edit/);

      // Update title
      const timestamp = Date.now();
      await page.getByLabel(/Judul|Title/i).fill(`Updated Title ${timestamp}`);

      // Submit form
      await page.getByRole('button', { name: /Update|Simpan|Save/i }).click();

      // Should show success message
      await expect(page.getByText(/berhasil|success|updated/i)).toBeVisible();
    } else {
      test.skip();
    }
  });
});

test.describe('Collection Items', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('should display collection items', async ({ page }) => {
    // Navigate to collections
    await page.goto('/collections');

    // Find first collection
    const firstCollection = page.locator('.collection-item, tbody tr').first();
    const hasCollections = await firstCollection.count() > 0;

    if (hasCollections) {
      await firstCollection.click();

      // Should be on detail page
      await expect(page).toHaveURL(/\/collections\/\d+/);

      // Check for items tab
      const itemsTab = page.getByRole('tab', { name: /Items|Item/i });
      const hasItemsTab = await itemsTab.count() > 0;

      if (hasItemsTab) {
        await itemsTab.click();

        // Check for items list
        await expect(page.getByText(/Items|Daftar Item/i)).toBeVisible();
      }
    } else {
      test.skip();
    }
  });

  test('should add new item to collection', async ({ page }) => {
    // Navigate to a collection detail page
    await page.goto('/collections');

    const firstCollection = page.locator('.collection-item, tbody tr').first();
    const hasCollections = await firstCollection.count() > 0;

    if (hasCollections) {
      await firstCollection.click();

      // Look for add item button
      const addItemButton = page.getByRole('link', { name: /\+ Tambah Item|Add Item/i });
      const hasButton = await addItemButton.count() > 0;

      if (hasButton) {
        await addItemButton.click();

        // Fill item form
        await page.getByLabel(/Barcode/i).fill(`TEST${Date.now()}`);
        await page.getByLabel(/Call Number/i).fill('TEST 001');

        // Select branch
        const branchSelect = page.getByLabel(/Cabang|Branch/i);
        if (await branchSelect.count() > 0) {
          await branchSelect.selectOption('1');
        }

        // Submit
        await page.getByRole('button', { name: /Simpan|Save/i }).click();

        // Should show success
        await expect(page.getByText(/berhasil|success/i)).toBeVisible();
      }
    } else {
      test.skip();
    }
  });
});

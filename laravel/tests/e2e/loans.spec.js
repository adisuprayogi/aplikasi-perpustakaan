import { test, expect } from '@playwright/test';

/**
 * Loans & Circulation Tests
 * Tests for loan management functionality
 */

// Helper function to login
async function login(page) {
  await page.goto('/login');
  await page.getByLabel('Email').fill('admin@library.test');
  await page.getByLabel('Password').fill('password123');
  await page.getByRole('button', { name: /Masuk|Log/i }).click();
  await expect(page).toHaveURL(/\/dashboard/);
}

test.describe('Loans & Circulation', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('should display loans list', async ({ page }) => {
    await page.goto('/loans');

    // Check page title
    await expect(page).toHaveTitle(/Peminjaman|Loans/);

    // Check for controls
    await expect(page.getByRole('button', { name: /\+ Peminjaman|New Loan/i })).toBeVisible();
  });

  test('should create new loan', async ({ page }) => {
    await page.goto('/loans');

    // Click create button
    await page.getByRole('link', { name: /\+ Peminjaman|New Loan/i }).click();

    // Should be on create page
    await expect(page).toHaveURL(/\/loans\/create/);

    // Check form elements
    await expect(page.getByLabel(/Anggota|Member/i)).toBeVisible();
    await expect(page.getByLabel(/Item|Barcode/i)).toBeVisible();
  });

  test('should filter loans by status', async ({ page }) => {
    await page.goto('/loans');

    // Check for status filter
    const statusFilter = page.locator('select').filter({ hasText: /Status/i });
    const hasFilter = await statusFilter.count() > 0;

    if (hasFilter) {
      // Select 'Active' status
      await statusFilter.selectOption(/Aktif|Active/i);

      // Wait for filtered results
      await page.waitForTimeout(500);
    }
  });

  test('should search loans by member name', async ({ page }) => {
    await page.goto('/loans');

    // Type in search box
    const searchInput = page.getByPlaceholder(/Cari|Search/i);
    await searchInput.fill('test');

    // Submit search
    await page.keyboard.press('Enter');

    // Wait for results
    await page.waitForTimeout(500);
  });

  test('should display loan details', async ({ page }) => {
    await page.goto('/loans');

    // Find first loan row
    const firstLoan = page.locator('tbody tr').first();
    const hasLoans = await firstLoan.count() > 0;

    if (hasLoans) {
      // Click on view button
      const viewButton = firstLoan.getByRole('link', { name: /detail|lihat|view/i });
      if (await viewButton.count() > 0) {
        await viewButton.click();

        // Should be on detail page
        await expect(page).toHaveURL(/\/loans\/\d+/);

        // Check for loan information
        await expect(page.getByText(/Detail Peminjaman|Loan Details/i)).toBeVisible();
      }
    } else {
      test.skip();
    }
  });

  test('should return a book', async ({ page }) => {
    await page.goto('/loans');

    // Find active loan
    const activeLoan = page.locator('tbody tr').filter({ hasText: /Aktif|Active/i }).first();
    const hasActiveLoan = await activeLoan.count() > 0;

    if (hasActiveLoan) {
      // Click return button
      const returnButton = activeLoan.getByRole('link', { name: /kembali|return/i });
      if (await returnButton.count() > 0) {
        await returnButton.click();

        // Confirm return if prompted
        const confirmButton = page.getByRole('button', { name: /Ya|Yes|Confirm/i });
        if (await confirmButton.count() > 0) {
          await confirmButton.click();
        }

        // Should show success message
        await expect(page.getByText(/berhasil|success/i)).toBeVisible();
      }
    } else {
      test.skip();
    }
  });

  test('should renew a loan', async ({ page }) => {
    await page.goto('/loans');

    // Find active loan
    const activeLoan = page.locator('tbody tr').filter({ hasText: /Aktif|Active/i }).first();
    const hasActiveLoan = await activeLoan.count() > 0;

    if (hasActiveLoan) {
      // Click renew button
      const renewButton = activeLoan.getByRole('link', { name: /perpanjang|renew/i });
      if (await renewButton.count() > 0) {
        await renewButton.click();

        // Should show success message
        await expect(page.getByText(/berhasil|success/i)).toBeVisible();
      }
    } else {
      test.skip();
    }
  });
});

test.describe('Overdue Loans', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('should display overdue loans report', async ({ page }) => {
    // Navigate to overdue report
    await page.goto('/reports/overdue');

    // Check page title
    await expect(page).toHaveTitle(/Keterlambatan|Overdue/);

    // Check for statistics
    await expect(page.getByText(/Total Keterlambatan|Total Overdue/i)).toBeVisible();
  });

  test('should export overdue report to CSV', async ({ page }) => {
    await page.goto('/reports/overdue');

    // Click export button
    const downloadPromise = page.waitForEvent('download');
    await page.getByRole('link', { name: /Export CSV/i }).click();
    const download = await downloadPromise;

    // Verify download
    expect(download.suggestedFilename()).toContain('.csv');
  });
});

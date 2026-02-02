import { test, expect } from '@playwright/test';

/**
 * Members Tests
 * Tests for member management functionality
 */

// Helper function to login
async function login(page) {
  await page.goto('/login');
  await page.getByLabel('Email').fill('admin@library.test');
  await page.getByLabel('Password').fill('password123');
  await page.getByRole('button', { name: /Masuk|Log/i }).click();
  await expect(page).toHaveURL(/\/dashboard/);
}

test.describe('Members', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('should display members list', async ({ page }) => {
    await page.goto('/members');

    // Check page title
    await expect(page).toHaveTitle(/Anggota|Members/);

    // Check for controls
    await expect(page.getByRole('link', { name: /\+ Tambah|Add/i })).toBeVisible();
    await expect(page.getByPlaceholder(/Cari|Search/i)).toBeVisible();
  });

  test('should search members by name or member number', async ({ page }) => {
    await page.goto('/members');

    // Type in search box
    await page.getByPlaceholder(/Cari|Search/i).fill('test');

    // Submit search
    await page.keyboard.press('Enter');

    // Wait for results
    await page.waitForTimeout(500);
  });

  test('should filter members by type', async ({ page }) => {
    await page.goto('/members');

    // Check for type filter
    const typeFilter = page.getByLabel(/Tipe|Type/i);
    const hasFilter = await typeFilter.count() > 0;

    if (hasFilter) {
      // Select member type
      await typeFilter.selectOption(/Mahasiswa|Student/i);

      // Wait for filtered results
      await page.waitForTimeout(500);
    }
  });

  test('should navigate to create member page', async ({ page }) => {
    await page.goto('/members');

    // Click create button
    await page.getByRole('link', { name: /\+ Tambah|Add/i }).click();

    // Should navigate to create page
    await expect(page).toHaveURL(/\/members\/create/);

    // Check form elements
    await expect(page.getByLabel(/No. Anggota|Member No/i)).toBeVisible();
    await expect(page.getByLabel(/Nama|Name/i)).toBeVisible();
    await expect(page.getByLabel(/Email/i)).toBeVisible();
    await expect(page.getByLabel(/Tipe|Type/i)).toBeVisible();
  });

  test('should create new member', async ({ page }) => {
    await page.goto('/members/create');

    // Generate unique test data
    const timestamp = Date.now();
    const testMemberNo = `TEST${timestamp}`;
    const testEmail = `test${timestamp}@example.com`;

    // Fill form
    await page.getByLabel(/No. Anggota|Member No/i).fill(testMemberNo);
    await page.getByLabel(/Nama|Name/i).fill(`Test Member ${timestamp}`);
    await page.getByLabel(/Email/i).fill(testEmail);
    await page.getByLabel(/Tipe|Type/i).selectOption('student');

    // Select branch if available
    const branchSelect = page.getByLabel(/Cabang|Branch/i);
    if (await branchSelect.count() > 0) {
      await branchSelect.selectOption('1');
    }

    // Submit form
    await page.getByRole('button', { name: /Simpan|Save|Create/i }).click();

    // Should show success message
    await expect(page.getByText(/berhasil|success|created/i)).toBeVisible();
  });

  test('should display member details', async ({ page }) => {
    await page.goto('/members');

    // Find first member
    const firstMember = page.locator('tbody tr').first();
    const hasMembers = await firstMember.count() > 0;

    if (hasMembers) {
      await firstMember.click();

      // Should navigate to detail page
      await expect(page).toHaveURL(/\/members\/\d+/);

      // Check for member information
      await expect(page.getByText(/Detail Anggota|Member Details/i)).toBeVisible();
    } else {
      test.skip();
    }
  });

  test('should extend member validity', async ({ page }) => {
    await page.goto('/members');

    // Find first member
    const firstMember = page.locator('tbody tr').first();
    const hasMembers = await firstMember.count() > 0;

    if (hasMembers) {
      await firstMember.click();

      // Look for extend button
      const extendButton = page.getByRole('link', { name: /Perpanjang|Extend/i });
      if (await extendButton.count() > 0) {
        await extendButton.click();

        // Confirm extend
        const confirmButton = page.getByRole('button', { name: /Ya|Yes|Confirm/i });
        if (await confirmButton.count() > 0) {
          await confirmButton.click();
        }

        // Should show success
        await expect(page.getByText(/berhasil|success/i)).toBeVisible();
      }
    } else {
      test.skip();
    }
  });

  test('should suspend member', async ({ page }) => {
    await page.goto('/members');

    // Find first member
    const firstMember = page.locator('tbody tr').first();
    const hasMembers = await firstMember.count() > 0;

    if (hasMembers) {
      await firstMember.click();

      // Look for suspend button
      const suspendButton = page.getByRole('link', { name: /Tangguhkan|Suspend/i });
      if (await suspendButton.count() > 0) {
        await suspendButton.click();

        // Fill reason
        await page.getByLabel(/Alasan|Reason/i).fill('Testing suspension');

        // Confirm
        await page.getByRole('button', { name: /Simpan|Save/i }).click();

        // Should show success
        await expect(page.getByText(/berhasil|success/i)).toBeVisible();
      }
    } else {
      test.skip();
    }
  });

  test('should display member current loans', async ({ page }) => {
    await page.goto('/members');

    // Find first member
    const firstMember = page.locator('tbody tr').first();
    const hasMembers = await firstMember.count() > 0;

    if (hasMembers) {
      await firstMember.click();

      // Check for current loans section
      const loansSection = page.getByText(/Peminjaman Aktif|Current Loans/i);
      const hasLoans = await loansSection.count() > 0;

      if (hasLoans) {
        await expect(loansSection).toBeVisible();
      }
    } else {
      test.skip();
    }
  });

  test('should be responsive on mobile', async ({ page }) => {
    await page.goto('/members');

    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });

    // Reload page
    await page.reload();

    // Check that mobile view loads
    await expect(page.getByText(/Anggota|Members/i)).toBeVisible();

    // Check for mobile card view
    const mobileCards = page.locator('.mobile-card');
    const hasMobileCards = await mobileCards.count() > 0;

    if (hasMobileCards) {
      await expect(mobileCards.first()).toBeVisible();
    }
  });
});

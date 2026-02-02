# Playwright Testing Guide
## Aplikasi Perpustakaan Kampus - End-to-End Testing Manual

---

## Table of Contents

1. [Introduction](#introduction)
2. [Installation & Setup](#installation--setup)
3. [Project Structure](#project-structure)
4. [Writing Tests](#writing-tests)
5. [Running Tests](#running-tests)
6. [Test Examples](#test-examples)
7. [Best Practices](#best-practices)
8. [Troubleshooting](#troubleshooting)

---

## Introduction

### What is Playwright?

[Playwright](https://playwright.dev/) is an end-to-end testing framework for web applications. It allows you to:

- **Automate browser interactions** - Click, type, navigate, and more
- **Run tests across browsers** - Chromium, Firefox, WebKit (Safari)
- **Test on mobile devices** - Emulate mobile viewports
- **Capture screenshots & videos** - Debug failing tests
- **Parallel test execution** - Run multiple tests simultaneously

### Why Playwright for This Project?

- ✅ **Modern & Fast** - Faster than Selenium with better API
- ✅ **Multi-browser Support** - Test on Chrome, Firefox, Safari
- ✅ **Mobile Testing** - Test responsive design
- ✅ **Auto-wait** - Automatically waits for elements to be ready
- ✅ **Network Interception** - Mock API responses
- ✅ **Trace Viewer** - Debug test failures with timeline

---

## Installation & Setup

### Prerequisites

```bash
# Node.js 18+ or 20+
node --version

# PHP 8.2+
php --version

# Composer
composer --version
```

### Installation Steps

1. **Install Playwright via npm:**

```bash
cd laravel
npm install -D @playwright/test
```

2. **Install Playwright browsers:**

```bash
npx playwright install chromium
```

For all browsers:
```bash
npx playwright install
```

3. **Verify installation:**

```bash
npx playwright --version
```

---

## Project Structure

```
laravel/
├── playwright.config.js          # Playwright configuration
├── package.json                  # Node.js dependencies
├── tests/
│   └── e2e/                      # End-to-end tests
│       ├── auth.spec.js          # Authentication tests
│       ├── dashboard.spec.js     # Dashboard tests
│       ├── collections.spec.js   # Collection management tests
│       ├── members.spec.js       # Member management tests
│       └── loans.spec.js         # Loan management tests
├── playwright-report/            # Test reports (generated)
└── test-results/                 # Screenshots & videos
```

---

## Writing Tests

### Basic Test Structure

```javascript
import { test, expect } from '@playwright/test';

test('my first test', async ({ page }) => {
  await page.goto('https://example.com');
  await expect(page).toHaveTitle(/Example/);
});
```

### Test Describe Blocks

Group related tests:

```javascript
test.describe('Authentication', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/login');
  });

  test('should login', async ({ page }) => {
    // Test code here
  });

  test('should logout', async ({ page }) => {
    // Test code here
  });
});
```

### Page Objects Pattern

Create reusable page objects:

```javascript
// pages/LoginPage.js
export class LoginPage {
  constructor(page) {
    this.page = page;
    this.emailInput = page.getByLabel('Email');
    this.passwordInput = page.getByLabel('Password');
    this.loginButton = page.getByRole('button', { name: /Masuk/i });
  }

  async login(email, password) {
    await this.emailInput.fill(email);
    await this.passwordInput.fill(password);
    await this.loginButton.click();
  }
}

// Usage in test
import { LoginPage } from './pages/LoginPage';

test('should login', async ({ page }) => {
  const loginPage = new LoginPage(page);
  await loginPage.login('user@example.com', 'password');
});
```

### Helper Functions

Create a helper file for common actions:

```javascript
// helpers/auth.js
export async function login(page, email = 'admin@library.test', password = 'password123') {
  await page.goto('/login');
  await page.getByLabel('Email').fill(email);
  await page.getByLabel('Password').fill(password);
  await page.getByRole('button', { name: /Masuk/i }).click();
  await expect(page).toHaveURL(/\/dashboard/);
}

// Usage
import { login } from './helpers/auth';

test('dashboard test', async ({ page }) => {
  await login(page);
  // Now test dashboard
});
```

---

## Running Tests

### Run All Tests

```bash
npx playwright test
```

### Run Specific Test File

```bash
npx playwright test tests/e2e/auth.spec.js
```

### Run Tests Matching Pattern

```bash
npx playwright test --grep "login"
```

### Run Tests in Specific Browser

```bash
# Chromium (default)
npx playwright test --project=chromium

# Firefox
npx playwright test --project=firefox

# WebKit (Safari)
npx playwright test --project=webkit

# Mobile Chrome
npx playwright test --project="Mobile Chrome"
```

### Run Tests in Headed Mode (Watch Browser)

```bash
npx playwright test --headed
```

### Debug Tests

```bash
# Run with debug mode
npx playwright test --debug

# Run with inspector UI
npx playwright test --ui
```

### View Test Reports

After running tests, view the HTML report:

```bash
npx playwright show-report
```

---

## Test Examples

### Authentication Tests

```javascript
import { test, expect } from '@playwright/test';

test.describe('Authentication', () => {
  test('should login successfully', async ({ page }) => {
    await page.goto('/login');

    await page.getByLabel('Email').fill('admin@library.test');
    await page.getByLabel('Password').fill('password123');
    await page.getByRole('button', { name: /Masuk/i }).click();

    await expect(page).toHaveURL(/\/dashboard/);
  });

  test('should show error with invalid credentials', async ({ page }) => {
    await page.goto('/login');

    await page.getByLabel('Email').fill('wrong@example.com');
    await page.getByLabel('Password').fill('wrongpass');
    await page.getByRole('button', { name: /Masuk/i }).click();

    await expect(page.getByText(/credentials/i)).toBeVisible();
  });
});
```

### Dashboard Tests

```javascript
test.describe('Dashboard', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.getByLabel('Email').fill('admin@library.test');
    await page.getByLabel('Password').fill('password123');
    await page.getByRole('button', { name: /Masuk/i }).click();
  });

  test('should display statistics', async ({ page }) => {
    await expect(page.getByText(/Total Anggota/i)).toBeVisible();
    await expect(page.getByText(/Total Koleksi/i)).toBeVisible();
    await expect(page.getByText(/Peminjaman Aktif/i)).toBeVisible();
  });

  test('should display charts', async ({ page }) => {
    await expect(page.locator('#circulationChart')).toBeVisible();
    await expect(page.locator('#collectionTypeChart')).toBeVisible();
    await expect(page.locator('#memberTypeChart')).toBeVisible();
  });
});
```

### CRUD Tests

```javascript
test.describe('Collections', () => {
  test('should create collection', async ({ page }) => {
    // Login
    await page.goto('/login');
    await page.getByLabel('Email').fill('admin@library.test');
    await page.getByLabel('Password').fill('password123');
    await page.getByRole('button', { name: /Masuk/i }).click();

    // Navigate to create page
    await page.goto('/collections/create');

    // Fill form
    await page.getByLabel(/Judul/i).fill('Test Book');
    await page.getByLabel(/Penulis/i).fill('Test Author');
    await page.getByLabel(/Tahun/i).fill('2024');

    // Submit
    await page.getByRole('button', { name: /Simpan/i }).click();

    // Verify
    await expect(page.getByText(/berhasil/i)).toBeVisible();
  });
});
```

### Mobile Responsive Tests

```javascript
test('should work on mobile', async ({ page }) => {
  // Set mobile viewport
  await page.setViewportSize({ width: 375, height: 667 });

  await page.goto('/');

  // Check mobile navigation
  await expect(page.getByRole('button', { name: /Menu/i })).toBeVisible();
});
```

### File Download Tests

```javascript
test('should export to CSV', async ({ page }) => {
  // Setup download handler
  const downloadPromise = page.waitForEvent('download');

  // Click export button
  await page.getByRole('link', { name: /Export CSV/i }).click();

  // Wait for download
  const download = await downloadPromise;

  // Verify filename
  expect(download.suggestedFilename()).toContain('.csv');
});
```

---

## Best Practices

### 1. Use Locators, Not Selectors

```javascript
// ❌ Bad - Brittle
await page.click('#submit-btn');

// ✅ Good - Robust
await page.getByRole('button', { name: 'Submit' }).click();
```

### 2. Wait for Elements

```javascript
// ❌ Bad - Fixed timeout
await page.waitForTimeout(5000);
await page.click('#button');

// ✅ Good - Auto-wait
await page.getByRole('button', { name: 'Submit' }).click();
// Playwright automatically waits for element to be ready
```

### 3. Use Test Data Factories

```javascript
// Generate unique test data
const timestamp = Date.now();
const testTitle = `Test Book ${timestamp}`;
const testEmail = `test${timestamp}@example.com`;
```

### 4. Clean Up Test Data

```javascript
test.afterEach(async ({ page }) => {
  // Clean up after each test
  // Delete test data, logout, etc.
});
```

### 5. Use Page Object Model

```javascript
// ✅ Good - Organized
test('should create collection', async ({ page }) => {
  const collectionPage = new CollectionPage(page);
  await collectionPage.goto();
  await collectionPage.createCollection({ title: 'Test' });
});
```

### 6. Test One Thing Per Test

```javascript
// ❌ Bad - Multiple assertions
test('should test everything', async ({ page }) => {
  await login(page);
  await createCollection(page);
  await deleteCollection(page);
  await logout(page);
});

// ✅ Good - Focused tests
test('should login', async ({ page }) => { /* ... */ });
test('should create collection', async ({ page }) => { /* ... */ });
```

### 7. Use Descriptive Test Names

```javascript
// ❌ Bad - Vague
test('test 1', async ({ page }) => { /* ... */ });

// ✅ Good - Descriptive
test('should display error when email is empty', async ({ page }) => { /* ... */ });
```

---

## Troubleshooting

### Tests Fail with "Element Not Found"

**Problem:** Test tries to interact with element before it's ready.

**Solution:** Playwright auto-waits, but you can add explicit waits:

```javascript
// Wait for element to be visible
await expect(page.getByText('Loading')).not.toBeVisible();

// Wait for network response
await page.waitForResponse('**/api/data');

// Wait for URL
await page.waitForURL('**/dashboard');
```

### Tests Fail in CI but Pass Locally

**Problem:** Environment differences (browser versions, screen sizes).

**Solution:** Use consistent browser versions:

```bash
# Use specific browser version
npx playwright install chromium@1160
```

### Slow Tests

**Problem:** Tests take too long to run.

**Solutions:**

1. **Run tests in parallel** (already enabled by default)
2. **Use test projects** to split tests
3. **Use `test.describe.configure()` to skip in CI:**

```javascript
test.describe.configure({ mode: 'parallel' });
test.describe('slow tests', () => {
  // These tests will run in parallel
});
```

### Flaky Tests (Sometimes Pass, Sometimes Fail)

**Problem:** Race conditions or timing issues.

**Solutions:**

1. **Use `waitForResponse`:**
```javascript
await page.waitForResponse('**/api/login');
```

2. **Use `waitForLoadState`:**
```javascript
await page.waitForLoadState('networkidle');
```

3. **Retry failed tests:**
```javascript
// In playwright.config.js
retries: 2,
```

---

## CI/CD Integration

### GitHub Actions Example

```yaml
name: Playwright Tests
on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3

      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: 20

      - name: Install dependencies
        run: npm ci

      - name: Install Playwright
        run: npx playwright install --with-deps

      - name: Run Playwright tests
        run: npx playwright test

      - name: Upload HTML report
        if: always()
        uses: actions/upload-artifact@v3
        with:
          name: playwright-report
          path: playwright-report/
```

---

## Quick Reference

### Common Locators

| Locator | Usage |
|---------|-------|
| `page.getByRole('button')` | Get button by role |
| `page.getByText('Hello')` | Get element by text |
| `page.getByLabel('Email')` | Get input by label |
| `page.getByPlaceholder('Search')` | Get input by placeholder |
| `page.getByAltText('Logo')` | Get image by alt text |
| `page.getByTitle('Close')` | Get element by title |
| `page.locator('#id')` | Get by CSS selector |
| `page.locator('.class')` | Get by CSS class |

### Common Assertions

```javascript
await expect(page).toHaveTitle('Title');
await expect(page).toHaveURL('**/dashboard');
await expect(element).toBeVisible();
await expect(element).toHaveText('Hello');
await expect(element).toHaveCount(5);
await expect(element).toHaveAttribute('href', '/home');
```

### Common Actions

```javascript
await page.goto('/path');
await page.click('button');
await page.fill('input', 'text');
await page.selectOption('select', 'value');
await page.check('input[type="checkbox"]');
await page.uncheck('input[type="checkbox"]');
await page.reload();
await page.goBack();
await page.goForward();
```

---

## Additional Resources

- [Playwright Documentation](https://playwright.dev/docs/intro)
- [Playwright for PHP](https://playwright.dev/php/docs/intro)
- [Best Practices](https://playwright.dev/docs/best-practices)
- [API Reference](https://playwright.dev/docs/api/class-playwright)

---

## Test Data Setup

Before running tests, ensure you have test data in your database:

```bash
# Run seeders
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=SettingsSeeder

# Or use test factories
php artisan tinker
>>> $user = \App\Models\User::factory()->create([
>>>     'email' => 'admin@library.test',
>>>     'password' => bcrypt('password123')
>>> ]);
```

---

**Version:** 1.0
**Last Updated:** February 2, 2026

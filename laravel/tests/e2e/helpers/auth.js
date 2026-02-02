/**
 * Authentication Helper Functions
 */

/**
 * Login with default admin credentials
 * @param {Page} page - Playwright page object
 * @param {string} email - User email (default: admin@library.test)
 * @param {string} password - User password (default: password123)
 */
export async function login(page, email = 'admin@library.test', password = 'password123') {
  await page.goto('/login');
  await page.getByLabel('Email').fill(email);
  await page.getByLabel('Password').fill(password);
  await page.getByRole('button', { name: /Masuk|Log in|Sign in/i }).click();
  await expect(page).toHaveURL(/\/dashboard/);
}

/**
 * Logout from the application
 * @param {Page} page - Playwright page object
 */
export async function logout(page) {
  // Click on user menu
  const userMenuButton = page.getByRole('button').filter({ hasText: /Admin|Profile|User/i });
  if (await userMenuButton.count() > 0) {
    await userMenuButton.click();
  }

  // Click logout
  const logoutLink = page.getByRole('link', { name: /Logout|Keluar|Sign out/i });
  if (await logoutLink.count() > 0) {
    await logoutLink.click();
  }

  await expect(page).toHaveURL(/\/login/);
}

/**
 * Navigate to a page with authentication check
 * @param {Page} page - Playwright page object
 * @param {string} path - Path to navigate to
 */
export async function navigateTo(page, path) {
  await page.goto(path);

  // If redirected to login, login and retry
  if (page.url().includes('/login')) {
    await login(page);
    await page.goto(path);
  }
}

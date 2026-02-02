import { test } from '@playwright/test';

/**
 * Convert Manual Book HTML to PDF
 */

test('convert manual book to pdf', async ({ browser }) => {
  const page = await browser.newPage();

  console.log('📄 Converting MANUAL_BOOK_WITH_IMAGES.html to PDF...');

  // Load the HTML file with embedded base64 images
  await page.goto('file:///home/zenbook/Documents/Data/Kerjaan/Yogi/aplikasi-perpustakaan/MANUAL_BOOK_WITH_IMAGES.html');

  // Wait for page to fully load
  await page.waitForLoadState('domcontentloaded');
  await page.waitForTimeout(3000);

  // Generate PDF
  await page.pdf({
    path: '/home/zenbook/Documents/Data/Kerjaan/Yogi/aplikasi-perpustakaan/MANUAL_BOOK.pdf',
    format: 'A4',
    printBackground: true,
    margin: {
      top: '20px',
      right: '20px',
      bottom: '20px',
      left: '20px'
    }
  });

  console.log('✅ PDF created: MANUAL_BOOK.pdf with all images embedded');

  await page.close();
});

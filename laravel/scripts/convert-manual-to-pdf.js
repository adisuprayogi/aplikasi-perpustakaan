const fs = require('fs');
const path = require('path');

// Read the HTML file
let html = fs.readFileSync('/home/zenbook/Documents/Data/Kerjaan/Yogi/aplikasi-perpustakaan/MANUAL_BOOK.html', 'utf8');

// Get all screenshot files
const screenshotsDir = '/home/zenbook/Documents/Data/Kerjaan/Yogi/aplikasi-perpustakaan/laravel/screenshots';
const files = fs.readdirSync(screenshotsDir).filter(f => f.endsWith('.png'));

console.log('Converting images to base64...');

// Replace each image src with base64 data
files.forEach(file => {
  const imgPath = path.join(screenshotsDir, file);
  const imgBuffer = fs.readFileSync(imgPath);
  const base64 = imgBuffer.toString('base64');
  const dataUrl = `data:image/png;base64,${base64}`;

  // Replace the src attribute
  const oldSrc = `src="laravel/screenshots/${file}"`;
  const newSrc = `src="${dataUrl}"`;
  html = html.replace(oldSrc, newSrc);

  console.log(`✓ ${file}`);
});

// Write the modified HTML
fs.writeFileSync('/home/zenbook/Documents/Data/Kerjaan/Yogi/aplikasi-perpustakaan/MANUAL_BOOK_WITH_IMAGES.html', html);

console.log('✅ Created MANUAL_BOOK_WITH_IMAGES.html with embedded images');

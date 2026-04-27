import { test, expect } from '@playwright/test';
import fs from 'fs';
import path from 'path';

const MANUAL_PATH = path.join(__dirname, '../../MANUAL_APLIKASI.md');
const OUTPUT_HTML_PATH = path.join(__dirname, '../../MANUAL_APLIKASI.html');
const OUTPUT_PDF_PATH = path.join(__dirname, '../../MANUAL_APLIKASI.pdf');

test('convert manual aplikasi to pdf', async ({ page }) => {
  console.log('📄 Reading MANUAL_APLIKASI.md...');

  const markdown = fs.readFileSync(MANUAL_PATH, 'utf-8');
  const htmlContent = convertMarkdownToHTML(markdown);

  fs.writeFileSync(OUTPUT_HTML_PATH, htmlContent, 'utf-8');
  console.log(`✅ HTML created: ${OUTPUT_HTML_PATH}`);

  await page.goto(`file://${OUTPUT_HTML_PATH}`);
  await page.waitForLoadState('networkidle');
  await page.waitForTimeout(2000);

  const pdfBuffer = await page.pdf({
    format: 'A4',
    printBackground: true,
    margin: {
      top: '0px',
      bottom: '0px',
      left: '0px',
      right: '0px'
    }
  });

  fs.writeFileSync(OUTPUT_PDF_PATH, pdfBuffer);
  console.log(`✅ PDF created: ${OUTPUT_PDF_PATH}`);

  const stats = fs.statSync(OUTPUT_PDF_PATH);
  console.log(`📊 PDF size: ${(stats.size / 1024 / 1024).toFixed(2)} MB`);
  expect(stats.size).toBeGreaterThan(10000);
});

function convertMarkdownToHTML(markdown) {
  let html = markdown;

  // First, preserve image references by replacing them with placeholders
  const images = [];
  html = html.replace(/!\[(.*?)\]\((screenshots\/.*?)\)/gim, (match, alt, imgPath) => {
    const fullPath = path.join(__dirname, '../../', imgPath);
    if (fs.existsSync(fullPath)) {
      const placeholder = `___IMG_${images.length}___`;
      images.push({ placeholder, alt, fullPath });
      return placeholder;
    }
    return match;
  });

  // Now do all markdown to HTML conversions
  const lines = html.split('\n');
  const result = [];
  let sectionNum = 0;
  let inList = false;
  let listItems = [];

  for (let line of lines) {
    // Skip empty lines
    if (line.trim() === '') {
      if (inList && listItems.length > 0) {
        result.push('<ul class="feature-list">' + listItems.join('') + '</ul>');
        listItems = [];
        inList = false;
      }
      result.push('<br>');
      continue;
    }

    // Main title
    if (line.match(/^# (.*)$/)) {
      if (inList && listItems.length > 0) {
        result.push('<ul class="feature-list">' + listItems.join('') + '</ul>');
        listItems = [];
        inList = false;
      }
      const title = line.replace(/^# (.*)$/, '$1');
      result.push(`<div class="title-page">
        <h1 class="main-title">${title}</h1>
        <p class="subtitle">Library Management System - User Guide</p>
        <div class="version">Versi 1.0.0</div>
        <div class="date">${new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</div>
      </div><div class="content-page">`);
      continue;
    }

    // Section title
    if (line.match(/^## (.*)$/)) {
      if (inList && listItems.length > 0) {
        result.push('<ul class="feature-list">' + listItems.join('') + '</ul>');
        listItems = [];
        inList = false;
      }
      sectionNum++;
      const title = line.replace(/^## (.*)$/, '$1');
      result.push(`</div><div class="content-page"><h2 class="section-title"><span class="section-num">${sectionNum}</span><span class="section-text">${title}</span></h2>`);
      continue;
    }

    // Subsection title
    if (line.match(/^### (.*)$/)) {
      if (inList && listItems.length > 0) {
        result.push('<ul class="feature-list">' + listItems.join('') + '</ul>');
        listItems = [];
        inList = false;
      }
      const title = line.replace(/^### (.*)$/, '$1');
      result.push(`<h3 class="subsection-title">${title}</h3>`);
      continue;
    }

    // List item
    if (line.match(/^\- (.*)$/)) {
      const itemText = line.replace(/^\- (.*)$/, '$1');
      // Apply inline formatting
      const formatted = formatInline(itemText);
      listItems.push(`<li>${formatted}</li>`);
      inList = true;
      continue;
    }

    // Regular paragraph - close list first if needed
    if (inList && listItems.length > 0) {
      result.push('<ul class="feature-list">' + listItems.join('') + '</ul>');
      listItems = [];
      inList = false;
    }

    // Apply inline formatting to regular text
    const formatted = formatInline(line);
    result.push(`<p class="content">${formatted}</p>`);
  }

  // Close any remaining list
  if (inList && listItems.length > 0) {
    result.push('<ul class="feature-list">' + listItems.join('') + '</ul>');
  }

  html = result.join('\n');

  // Now replace image placeholders with actual HTML
  for (const img of images) {
    html = html.replace(img.placeholder, `<figure class="screenshot">
      <img src="file://${img.fullPath}" alt="${img.alt}">
      <figcaption>${img.alt}</figcaption>
    </figure>`);
  }

  // Close the last content-page div and add footer
  html += `</div><div class="content-page">
    <div class="doc-footer">
      <p style="font-size: 11pt; font-weight: 600; color: #1e40af;">Dokumen Ini Dibuat Oleh Sistem Dokumentasi Otomatis</p>
      <p><strong>Library Management System</strong> - Aplikasi Perpustakaan Digital Terpadu</p>
      <p style="margin-top: 8px;">Tanggal: ${new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>
    </div>
  </div>`;

  return `<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manual Aplikasi Perpustakaan</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    @page {
      size: A4;
      margin: 0;
    }

    body {
      font-family: Arial, 'Segoe UI', sans-serif;
      font-size: 10pt;
      line-height: 1.5;
      color: #1f2937;
      background: #fff;
    }

    .content-page {
      position: relative;
      padding: 60px 20mm 40px 20mm;
      min-height: 750px;
      page-break-after: always;
    }

    .content-page::before {
      content: 'Manual Aplikasi Perpustakaan';
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 30px;
      background: #f8fafc;
      border-bottom: 2px solid #1e40af;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20mm;
      font-size: 8px;
      font-weight: 600;
      color: #1e40af;
      z-index: 1000;
    }

    .content-page::after {
      content: '© 2026 Perpustakaan';
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 25px;
      background: #f8fafc;
      border-top: 1px solid #e5e7eb;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 7px;
      color: #6b7280;
      z-index: 1000;
    }

    .title-page {
      text-align: center;
      padding: 100px 30px;
      min-height: 800px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      page-break-after: always;
    }

    .main-title {
      font-family: Georgia, serif;
      font-size: 32pt;
      font-weight: 700;
      color: #1e40af;
      margin-bottom: 20px;
    }

    .subtitle {
      font-size: 14pt;
      color: #6b7280;
      margin-bottom: 30px;
    }

    .version {
      background: #1e40af;
      color: white;
      padding: 10px 25px;
      border-radius: 25px;
      font-size: 11pt;
      font-weight: 600;
      margin: 15px 0;
    }

    .date {
      font-size: 10pt;
      color: #9ca3af;
      margin-top: 20px;
    }

    h2.section-title {
      font-family: Georgia, serif;
      font-size: 18pt;
      font-weight: 700;
      color: #1e40af;
      margin: 15px 0 15px 0;
      padding-bottom: 10px;
      border-bottom: 3px solid #1e40af;
      page-break-after: avoid;
    }

    .content-page > h2.section-title:first-child {
      margin-top: 10px;
    }

    .section-num {
      display: inline-block;
      background: #1e40af;
      color: white;
      width: 32px;
      height: 32px;
      line-height: 32px;
      text-align: center;
      border-radius: 50%;
      font-size: 13pt;
      margin-right: 8px;
      vertical-align: middle;
    }

    .section-text {
      vertical-align: middle;
    }

    h3.subsection-title {
      font-size: 13pt;
      font-weight: 600;
      color: #1e3a8a;
      margin: 20px 0 10px 0;
      padding-left: 12px;
      border-left: 4px solid #1e40af;
      page-break-after: avoid;
    }

    p.content {
      margin-bottom: 12px;
      text-align: justify;
      line-height: 1.6;
    }

    /* Add spacing after lists */
    ul.feature-list + p.content {
      margin-top: 12px;
    }

    /* Add spacing before subsections */
    p.content + h3.subsection-title {
      margin-top: 18px;
    }

    /* Add spacing after subsections */
    h3.subsection-title + p.content {
      margin-top: 8px;
    }

    ul.feature-list {
      margin: 12px 0 12px 20px;
      list-style: none;
    }

    ul.feature-list li {
      position: relative;
      margin-bottom: 10px;
      padding-left: 22px;
      line-height: 1.5;
    }

    ul.feature-list li:before {
      content: '✓';
      position: absolute;
      left: 0;
      color: #1e40af;
      font-weight: 700;
    }

    figure.screenshot {
      display: block;
      margin: 20px 0 25px 0;
      text-align: center;
      page-break-inside: avoid;
    }

    figure.screenshot img {
      display: block;
      max-width: 100%;
      max-height: 500px;
      height: auto;
      margin: 0 auto;
      border: 2px solid #d1d5db;
      border-radius: 8px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
    }

    figure.screenshot figcaption {
      display: block;
      margin-top: 12px;
      margin-bottom: 8px;
      font-size: 9pt;
      color: #6b7280;
      font-style: italic;
    }

    /* Add spacing after images */
    figure.screenshot + p.content {
      margin-top: 15px;
    }

    figure.screenshot + h3.subsection-title {
      margin-top: 20px;
    }

    code {
      background: #f3f4f6;
      color: #dc2626;
      padding: 2px 6px;
      border-radius: 3px;
      font-family: 'Courier New', monospace;
      font-weight: 600;
    }

    a {
      color: #2563eb;
      text-decoration: none;
      font-weight: 500;
    }

    strong {
      color: #1e40af;
      font-weight: 600;
    }

    .doc-footer {
      margin-top: 40px;
      padding-top: 20px;
      border-top: 2px solid #e5e7eb;
      text-align: center;
      font-size: 9pt;
      color: #6b7280;
    }

    .doc-footer p {
      margin: 8px 0;
    }

    @media print {
      .content-page {
        break-after: always;
      }
      figure.screenshot {
        break-inside: avoid;
      }
    }
  </style>
</head>
<body>
  ${html}
</body>
</html>`;
}

function formatInline(text) {
  // Escape HTML first
  let result = text
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');

  // Bold
  result = result.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

  // Italic
  result = result.replace(/\*(.*?)\*/g, '<em>$1</em>');

  // Code
  result = result.replace(/`([^`]+)`/g, '<code>$1</code>');

  // Links
  result = result.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank">$1</a>');

  return result;
}

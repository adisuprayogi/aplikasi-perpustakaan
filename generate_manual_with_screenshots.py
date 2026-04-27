#!/usr/bin/env python3
"""
Generate Manual Book HTML with Screenshots
"""
import os
import re
from pathlib import Path

# Base directory
BASE_DIR = Path("/home/zenbook/Documents/Data/Kerjaan/Yogi/aplikasi-perpustakaan")
SCREENSHOT_DIR = BASE_DIR / "screenshots"

# Manual books with their screenshot directories
MANUALS = [
    {
        "md_file": "MANUAL_SUPER_ADMIN.md",
        "html_file": "MANUAL_SUPER_ADMIN.html",
        "pdf_file": "MANUAL_SUPER_ADMIN.pdf",
        "title": "Panduan Super Admin",
        "role": "super_admin",
        "screenshot_dir": SCREENSHOT_DIR / "super_admin",
    },
    {
        "md_file": "MANUAL_ADMIN.md",
        "html_file": "MANUAL_ADMIN.html",
        "pdf_file": "MANUAL_ADMIN.pdf",
        "title": "Panduan Admin",
        "role": "admin",
        "screenshot_dir": SCREENSHOT_DIR / "admin",
    },
    {
        "md_file": "MANUAL_BRANCH_ADMIN.md",
        "html_file": "MANUAL_BRANCH_ADMIN.html",
        "pdf_file": "MANUAL_BRANCH_ADMIN.pdf",
        "title": "Panduan Branch Admin",
        "role": "branch_admin",
        "screenshot_dir": SCREENSHOT_DIR / "branch_admin",
    },
    {
        "md_file": "MANUAL_CIRCULATION_STAFF.md",
        "html_file": "MANUAL_CIRCULATION_STAFF.html",
        "pdf_file": "MANUAL_CIRCULATION_STAFF.pdf",
        "title": "Panduan Circulation Staff",
        "role": "circulation_staff",
        "screenshot_dir": SCREENSHOT_DIR / "circulation_staff",
    },
    {
        "md_file": "MANUAL_CATALOG_STAFF.md",
        "html_file": "MANUAL_CATALOG_STAFF.html",
        "pdf_file": "MANUAL_CATALOG_STAFF.pdf",
        "title": "Panduan Catalog Staff",
        "role": "catalog_staff",
        "screenshot_dir": SCREENSHOT_DIR / "catalog_staff",
    },
    {
        "md_file": "MANUAL_REPORT_VIEWER.md",
        "html_file": "MANUAL_REPORT_VIEWER.html",
        "pdf_file": "MANUAL_REPORT_VIEWER.pdf",
        "title": "Panduan Report Viewer",
        "role": "report_viewer",
        "screenshot_dir": SCREENSHOT_DIR / "report_viewer",
    },
    {
        "md_file": "MANUAL_USER.md",
        "html_file": "MANUAL_USER.html",
        "pdf_file": "MANUAL_USER.pdf",
        "title": "Panduan Anggota Perpustakaan",
        "role": "member",
        "screenshot_dir": SCREENSHOT_DIR / "public",  # Public screenshots for members
    },
]

# Mapping halaman ke file screenshot
PAGE_SCREENSHOTS = {
    "super_admin": {
        "dashboard": "01-dashboard.png",
        "users": "02-users.png",
        "branches": "02-branches.png",
        "members": "02-members.png",
        "collections": "02-collections.png",
        "loans": "02-loans.png",
        "loans-create": "02-loans-create.png",
        "reservations": "02-reservations.png",
        "digital-files": "02-digital-files.png",
        "repositories": "02-repositories.png",
        "loan-rules": "02-loan-rules.png",
        "transfers": "02-transfers.png",
        "settings": "02-settings.png",
    },
    "admin": {
        "dashboard": "01-dashboard.png",
        "users": "02-users.png",
        "branches": "02-branches.png",
        "members": "02-members.png",
        "collections": "02-collections.png",
        "loans": "02-loans.png",
        "reservations": "02-reservations.png",
        "digital-files": "02-digital-files.png",
        "repositories": "02-repositories.png",
        "settings": "02-settings.png",
    },
    "branch_admin": {
        "dashboard": "01-dashboard.png",
        "members": "02-members.png",
        "members-create": "02-members-create.png",
        "collections": "02-collections.png",
        "loans": "02-loans.png",
        "loans-create": "02-loans-create.png",
        "reservations": "02-reservations.png",
        "reservations-create": "02-reservations-create.png",
        "digital-files": "02-digital-files.png",
        "repositories": "02-repositories.png",
        "transfers": "02-transfers.png",
    },
    "circulation_staff": {
        "dashboard": "01-dashboard.png",
        "loans": "02-loans.png",
        "loans-create": "02-loans-create.png",
        "reservations": "02-reservations.png",
        "reservations-create": "02-reservations-create.png",
        "members": "02-members.png",
        "collections": "02-collections.png",
    },
    "catalog_staff": {
        "dashboard": "01-dashboard.png",
        "collections": "02-collections.png",
        "collections-create": "02-collections-create.png",
        "collections-labels": "02-collections-labels.png",
        "digital-files": "02-digital-files.png",
        "digital-files-create": "02-digital-files-create.png",
        "repositories": "02-repositories.png",
        "repositories-create": "02-repositories-create.png",
    },
    "report_viewer": {
        "dashboard": "01-dashboard.png",
        "reports-loans": "02-reports-loans.png",
        "reports-overdue": "02-reports-overdue.png",
        "reports-fines": "02-reports-fines.png",
        "reports-collections": "02-reports-collections.png",
        "reports-members": "02-reports-members.png",
    },
    "member": {
        "dashboard": "",  # No specific dashboard for members
        "opac": "opac.png",
        "opac-search": "opac-search.png",
        "digital-library": "digital-library.png",
        "repository": "repository.png",
        "login": "login.png",
    },
}

HTML_TEMPLATE = '''<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{title} - Aplikasi Perpustakaan Digital</title>
    <style>
        * {{
            box-sizing: border-box;
        }}

        body {{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
            color: #333;
            background: white;
        }}

        img {{
            max-width: 100%;
            height: auto;
            display: block;
            margin: 20px 0;
        }}

        h1 {{
            color: #1e40af;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 10px;
            page-break-after: avoid;
            font-size: 2em;
        }}

        h2 {{
            color: #1e40af;
            margin-top: 50px;
            page-break-after: avoid;
            font-size: 1.5em;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
        }}

        h3 {{
            color: #3b82f6;
            margin-top: 30px;
            page-break-after: avoid;
            font-size: 1.3em;
        }}

        h4 {{
            color: #64748b;
            margin-top: 20px;
            page-break-after: avoid;
            font-size: 1.1em;
        }}

        .subtitle {{
            font-size: 1.3em;
            color: #64748b;
            margin-bottom: 30px;
        }}

        hr {{
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 30px 0;
        }}

        .toc {{
            background: #f8fafc;
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
            border: 1px solid #e2e8f0;
        }}

        .toc h2 {{
            margin-top: 0;
            color: #1e40af;
            border-bottom: none;
            padding-bottom: 0;
        }}

        .toc ul {{
            list-style: none;
            padding-left: 0;
        }}

        .toc li {{
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }}

        .toc li:last-child {{
            border-bottom: none;
        }}

        .toc a {{
            color: #3b82f6;
            text-decoration: none;
            display: block;
        }}

        .toc a:hover {{
            text-decoration: underline;
        }}

        .toc ul ul {{
            padding-left: 25px;
            margin-top: 8px;
        }}

        .toc ul ul li {{
            border-bottom: none;
            padding: 4px 0;
        }}

        table {{
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            page-break-inside: avoid;
            font-size: 0.95em;
        }}

        th {{
            background: #1e40af;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }}

        td {{
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
        }}

        tr:nth-child(even) {{
            background: #f8fafc;
        }}

        pre {{
            background: #1e293b;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }}

        code {{
            background: #f1f5f9;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }}

        pre code {{
            background: transparent;
            padding: 0;
        }}

        .badge {{
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            margin: 0 2px;
        }}

        .badge-success {{
            background: #10b981;
            color: white;
        }}

        .badge-danger {{
            background: #ef4444;
            color: white;
        }}

        .badge-warning {{
            background: #f59e0b;
            color: white;
        }}

        .emoji {{
            font-size: 1.2em;
            margin-right: 5px;
        }}

        .screenshot-container {{
            margin: 30px 0;
            page-break-inside: avoid;
        }}

        .screenshot-container img {{
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }}

        .screenshot-caption {{
            font-style: italic;
            color: #64748b;
            text-align: center;
            margin-top: 10px;
            font-size: 0.9em;
        }}

        .info-box {{
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 8px 8px 0;
        }}

        .info-box.warning {{
            background: #fee2e2;
            border-left-color: #ef4444;
        }}

        .info-box.info {{
            background: #dbeafe;
            border-left-color: #3b82f6;
        }}

        .info-box.success {{
            background: #d1fae5;
            border-left-color: #10b981;
        }}

        .step {{
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            page-break-inside: avoid;
        }}

        .step h4 {{
            margin-top: 0;
            color: #3b82f6;
        }}

        .step-number {{
            display: inline-block;
            background: #3b82f6;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            font-weight: bold;
            margin-right: 10px;
        }}

        @media print {{
            body {{
                padding: 20px;
            }}

            h1, h2, h3, h4 {{
                page-break-after: avoid;
            }}

            table {{
                page-break-inside: avoid;
            }}

            pre {{
                page-break-inside: avoid;
            }}

            .screenshot-container {{
                page-break-inside: avoid;
            }}

            .step {{
                page-break-inside: avoid;
            }}
        }}

        .update-note {{
            background: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            font-style: italic;
        }}
    </style>
</head>
<body>
    {content}
</body>
</html>
'''

def markdown_to_html(md_content, role, screenshot_dir):
    """Convert markdown to HTML with screenshots"""
    html = md_content
    screenshot_base = f"../screenshots/{role}"

    # Headers
    html = re.sub(r'^# (.+)$', r'<h1>\1</h1>', html, flags=re.MULTILINE)
    html = re.sub(r'^## (.+)$', r'<h2 id="\1">\1</h2>', html, flags=re.MULTILINE)
    html = re.sub(r'^### (.+)$', r'<h3 id="\1">\1</h3>', html, flags=re.MULTILINE)
    html = re.sub(r'^#### (.+)$', r'<h4>\1</h4>', html, flags=re.MULTILINE)

    # Bold and italic
    html = re.sub(r'\*\*(.+?)\*\*', r'<strong>\1</strong>', html)
    html = re.sub(r'\*(.+?)\*', r'<em>\1</em>', html)

    # Horizontal rules
    html = re.sub(r'^---$', r'<hr>', html, flags=re.MULTILINE)

    # Code blocks
    html = re.sub(r'```(.+?)```', r'<pre><code>\1</code></pre>', html, flags=re.DOTALL)

    # Inline code
    html = re.sub(r'`([^`]+)`', r'<code>\1</code>', html)

    # Links
    html = re.sub(r'\[([^\]]+)\]\(([^)]+)\)', r'<a href="\2">\1</a>', html)

    # Images/Screenshots - Auto insert after relevant sections
    for page_name, screenshot_file in PAGE_SCREENSHOTS.get(role, {}).items():
        if screenshot_file:
            # Look for sections that mention this page and insert screenshot
            section_pattern = rf'(###+ .*?{re.escape(page_name.replace("-", " ").replace("_", " ").title())}.*?(?=\n##|\Z))'

            def add_screenshot(match):
                section_content = match.group(0)
                screenshot_html = f'''
<div class="screenshot-container">
    <img src="{screenshot_base}/{screenshot_file}" alt="{page_name}" loading="lazy">
    <div class="screenshot-caption">Gambar: Tampilan Halaman {page_name.replace("-", " ").title()}</div>
</div>
'''
                return section_content + screenshot_html

            html = re.sub(section_pattern, add_screenshot, html, flags=re.IGNORECASE | re.DOTALL)

    # Tables - simple conversion
    lines = html.split('\n')
    in_table = False
    result = []
    table_rows = []

    for line in lines:
        if '|' in line and line.strip().startswith('|'):
            if not in_table:
                in_table = True
                table_rows = []
            # Skip separator line
            if not re.match(r'^\|[\s\-:]+\|$', line):
                table_rows.append(line)
        else:
            if in_table:
                # Convert table to HTML
                if table_rows:
                    result.append('<table>')
                    for i, row in enumerate(table_rows):
                        cells = [cell.strip() for cell in row.split('|')[1:-1]]
                        tag = 'th' if i == 0 else 'td'
                        result.append('<tr>')
                        for cell in cells:
                            result.append(f'<{tag}>{cell}</{tag}>')
                        result.append('</tr>')
                    result.append('</table>')
                in_table = False
                table_rows = []
            result.append(line)

    html = '\n'.join(result)

    # Lists
    def convert_list(match):
        items = match.group(0).strip().split('\n')
        list_html = ['<ul>']
        for item in items:
            item = re.sub(r'^[\-\*]\s+', '', item.strip())
            item = re.sub(r'^\d+\.\s+', '', item.strip())
            # Convert inline markdown in list items
            item = re.sub(r'\*\*(.+?)\*\*', r'<strong>\1</strong>', item)
            item = re.sub(r'`([^`]+)`', r'<code>\1</code>', item)
            list_html.append(f'<li>{item}</li>')
        list_html.append('</ul>')
        return '\n'.join(list_html)

    # Unordered lists
    html = re.sub(r'((?:^[\-\*]\s.+?\n)+)', convert_list, html, flags=re.MULTILINE)

    # Ordered lists
    html = re.sub(r'((?:^\d+\.\s.+?\n)+)', convert_list, html, flags=re.MULTILINE)

    # Blockquotes - convert to info boxes
    html = re.sub(r'^> (.+)$', r'<div class="info-box">\1</div>', html, flags=re.MULTILINE)

    # Emojis
    emoji_map = {
        '✅': '<span class="emoji">✅</span>',
        '❌': '<span class="emoji">❌</span>',
        '⚠️': '<span class="emoji">⚠️</span>',
        '💡': '<span class="emoji">💡</span>',
        '📚': '<span class="emoji">📚</span>',
        '📖': '<span class="emoji">📖</span>',
        '📊': '<span class="emoji">📊</span>',
        '📈': '<span class="emoji">📈</span>',
        '👥': '<span class="emoji">👥</span>',
        '🔍': '<span class="emoji">🔍</span>',
        '📁': '<span class="emoji">📁</span>',
        '⚙️': '<span class="emoji">⚙️</span>',
        '📝': '<span class="emoji">📝</span>',
        '🔁': '<span class="emoji">🔁</span>',
        '💰': '<span class="emoji">💰</span>',
        '📅': '<span class="emoji">📅</span>',
        '🏛️': '<span class="emoji">🏛️</span>',
        '🏷️': '<span class="emoji">🏷️</span>',
        '📥': '<span class="emoji">📥</span>',
        '📤': '<span class="emoji">📤</span>',
    }
    for emoji, replacement in emoji_map.items():
        html = html.replace(emoji, replacement)

    # Paragraphs (lines not starting with special chars)
    lines = html.split('\n')
    result = []
    in_paragraph = False

    for line in lines:
        stripped = line.strip()
        if stripped and not stripped.startswith('<'):
            if in_paragraph:
                result[-1] = result[-1].rstrip() + ' ' + stripped
            else:
                result.append(f'<p>{stripped}</p>')
                in_paragraph = True
        else:
            in_paragraph = False
            result.append(line)

    html = '\n'.join(result)

    return html

def generate_manual_with_screenshots(manual):
    """Generate HTML manual with embedded screenshots"""
    md_path = BASE_DIR / manual["md_file"]
    html_path = BASE_DIR / manual["html_file"]
    pdf_path = BASE_DIR / manual["pdf_file"]

    print(f"Generating {manual['title']}...")

    # Read markdown
    with open(md_path, 'r', encoding='utf-8') as f:
        md_content = f.read()

    # Convert to HTML with screenshots
    content_html = markdown_to_html(md_content, manual["role"], manual["screenshot_dir"])

    # Add subtitle
    subtitle = f'<div class="subtitle">Aplikasi Perpustakaan Digital - {manual["title"]}</div>\n'

    # Create full HTML
    full_html = HTML_TEMPLATE.format(title=manual["title"], content=subtitle + content_html)

    # Write HTML
    with open(html_path, 'w', encoding='utf-8') as f:
        f.write(full_html)
    print(f"  ✓ Created {html_path.name}")

    # Generate PDF
    try:
        import subprocess
        subprocess.run([
            'google-chrome',
            '--headless',
            '--disable-gpu',
            '--print-to-pdf=' + str(pdf_path),
            '--no-margins',
            '--print-to-pdf-no-header',
            str(html_path)
        ], check=True, capture_output=True, timeout=30)
        print(f"  ✓ Created {pdf_path.name}")
    except Exception as e:
        print(f"  ✗ PDF generation failed: {e}")

def main():
    """Generate all manuals with screenshots"""
    print("=" * 60)
    print("Generating Manual Books with Screenshots")
    print("=" * 60)
    print()

    for manual in MANUALS:
        if manual["screenshot_dir"].exists():
            generate_manual_with_screenshots(manual)
        else:
            print(f"  ✗ {manual['title']}: Screenshots not found in {manual['screenshot_dir']}")
        print()

    print("=" * 60)
    print("Manual Books Generated!")
    print("=" * 60)
    print()
    print("Files created:")
    for manual in MANUALS:
        print(f"  - {manual['html_file']}")
        print(f"  - {manual['pdf_file']}")

if __name__ == '__main__':
    main()

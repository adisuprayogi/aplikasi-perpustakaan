#!/usr/bin/env python3
"""
Convert all manual markdown files to HTML and PDF
"""
import os
import re
import subprocess
from pathlib import Path

# Base directory
BASE_DIR = Path("/home/zenbook/Documents/Data/Kerjaan/Yogi/aplikasi-perpustakaan")

# Manual files with their titles
MANUALS = [
    ("MANUAL_SUPER_ADMIN.md", "Panduan Super Admin", "MANUAL_SUPER_ADMIN"),
    ("MANUAL_ADMIN.md", "Panduan Admin", "MANUAL_ADMIN"),
    ("MANUAL_BRANCH_ADMIN.md", "Panduan Branch Admin", "MANUAL_BRANCH_ADMIN"),
    ("MANUAL_CIRCULATION_STAFF.md", "Panduan Circulation Staff", "MANUAL_CIRCULATION_STAFF"),
    ("MANUAL_CATALOG_STAFF.md", "Panduan Catalog Staff", "MANUAL_CATALOG_STAFF"),
    ("MANUAL_REPORT_VIEWER.md", "Panduan Report Viewer", "MANUAL_REPORT_VIEWER"),
    ("MANUAL_USER.md", "Panduan Anggota Perpustakaan", "MANUAL_USER"),
]

# HTML template
HTML_TEMPLATE = '''<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{title} - Aplikasi Perpustakaan Digital</title>
    <style>
        body {{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            color: #333;
            background: white;
        }}

        h1 {{
            color: #1e40af;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 10px;
            page-break-after: avoid;
        }}

        h2 {{
            color: #1e40af;
            margin-top: 40px;
            page-break-after: avoid;
        }}

        h3 {{
            color: #3b82f6;
            margin-top: 25px;
            page-break-after: avoid;
        }}

        h4 {{
            color: #64748b;
            margin-top: 20px;
            page-break-after: avoid;
        }}

        .subtitle {{
            font-size: 1.5em;
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
        }}

        .toc h2 {{
            margin-top: 0;
            color: #1e40af;
        }}

        .toc ul {{
            list-style: none;
            padding-left: 0;
        }}

        .toc li {{
            padding: 5px 0;
        }}

        .toc a {{
            color: #3b82f6;
            text-decoration: none;
        }}

        .toc a:hover {{
            text-decoration: underline;
        }}

        .toc ul ul {{
            padding-left: 20px;
        }}

        table {{
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            page-break-inside: avoid;
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

        .emoji {{
            font-size: 1.2em;
            margin-right: 5px;
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
        }}
    </style>
</head>
<body>
    {content}
</body>
</html>
'''

def markdown_to_html(md_content):
    """Convert markdown to HTML"""
    html = md_content

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

    # Blockquotes
    html = re.sub(r'^> (.+)$', r'<p class="info-box">\1</p>', html, flags=re.MULTILINE)

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

def convert_manual(md_file, title, output_base):
    """Convert a single manual to HTML and PDF"""
    md_path = BASE_DIR / md_file

    print(f"Converting {md_file}...")

    # Read markdown
    with open(md_path, 'r', encoding='utf-8') as f:
        md_content = f.read()

    # Convert to HTML
    content_html = markdown_to_html(md_content)

    # Add subtitle if exists
    if '##' in md_content:
        content_html = f'<div class="subtitle">Aplikasi Perpustakaan Digital</div>\n' + content_html

    # Create full HTML
    full_html = HTML_TEMPLATE.format(title=title, content=content_html)

    # Write HTML
    html_path = BASE_DIR / f"{output_base}.html"
    with open(html_path, 'w', encoding='utf-8') as f:
        f.write(full_html)
    print(f"  ✓ Created {html_path.name}")

    # Generate PDF
    pdf_path = BASE_DIR / f"{output_base}.pdf"
    try:
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
    except subprocess.TimeoutExpired:
        print(f"  ✗ PDF generation timed out")
    except subprocess.CalledProcessError as e:
        print(f"  ✗ PDF generation failed: {e}")

def main():
    """Convert all manuals"""
    print("=" * 50)
    print("Converting Manuals to HTML and PDF")
    print("=" * 50)
    print()

    for md_file, title, output_base in MANUALS:
        convert_manual(md_file, title, output_base)
        print()

    print("=" * 50)
    print("Conversion complete!")
    print("=" * 50)

if __name__ == '__main__':
    main()

"""Membuat HTML siap cetak dari Handbook SIMPUS-Mini (Jobsheet 1-6). Jalankan dari folder repo."""
from pathlib import Path
import html
import re

root = Path(__file__).resolve().parents[1]
docs = root / "docs"
source = docs / "HANDBOOK-SIMPUS-JOBSHEET-1-6.md"


def inline(text):
    codes = []
    def save_code(match):
        codes.append("<code>" + html.escape(match[1]) + "</code>")
        return f"CODETOKEN{len(codes) - 1}END"
    text = re.sub(r"`([^`]+)`", save_code, text)
    text = html.escape(text)
    text = re.sub(r"\*\*(.+?)\*\*", r"<strong>\1</strong>", text)
    text = re.sub(r"\[([^\]]+)\]\(([^)]+)\)", r'<a href="\2">\1</a>', text)
    for n, code in enumerate(codes):
        text = text.replace(f"CODETOKEN{n}END", code)
    return text


parts, toc, code_lines = [], [], []
in_code = False
list_type = None
in_table = False
heading_count = 0

for line in source.read_text(encoding="utf-8").splitlines():
    if line.startswith("```"):
        if in_code:
            parts.append("<pre><code>" + html.escape("\n".join(code_lines)) + "</code></pre>")
            code_lines = []
        in_code = not in_code
        continue
    if in_code:
        code_lines.append(line)
        continue
    item = re.match(r"^(- |\d+\. )(.*)", line)
    next_list = ("ul" if item and item[1] == "- " else "ol") if item else None
    if list_type and next_list != list_type:
        parts.append(f"</{list_type}>")
        list_type = None
    if in_table and not line.startswith("|"):
        parts.append("</tbody></table></div>")
        in_table = False
    if line.startswith("|"):
        cells = [cell.strip() for cell in line.split("|")[1:-1]]
        if all(re.fullmatch(r"[- :]+", cell) for cell in cells):
            continue
        if not in_table:
            parts.append('<div class="table"><table><thead><tr>' +
                         "".join("<th>" + inline(c) + "</th>" for c in cells) +
                         "</tr></thead><tbody>")
            in_table = True
        else:
            parts.append("<tr>" + "".join("<td>" + inline(c) + "</td>" for c in cells) + "</tr>")
        continue
    if item:
        if not list_type:
            parts.append(f"<{next_list}>")
            list_type = next_list
        parts.append("<li>" + inline(item[2]) + "</li>")
        continue
    image = re.fullmatch(r"!\[([^\]]*)\]\(([^)]+)\)", line)
    if image:
        img_path = docs / image[2]
        if img_path.is_file():
            parts.append('<figure><img src="' + html.escape(image[2], quote=True) +
                         '" alt="' + html.escape(image[1], quote=True) + '"></figure>')
        continue
    heading = re.match(r"^(#{1,3}) (.+)", line)
    if heading:
        level = len(heading[1])
        heading_count += 1
        anchor = f"bagian-{heading_count}"
        if level == 2:
            toc.append(f'<a href="#{anchor}">{inline(heading[2])}</a>')
        parts.append(f'<h{level} id="{anchor}">{inline(heading[2])}</h{level}>')
        continue
    if line.strip():
        parts.append("<p>" + inline(line) + "</p>")

if list_type:
    parts.append(f"</{list_type}>")
if in_table:
    parts.append("</tbody></table></div>")

first_chapter = next((n for n, p in enumerate(parts) if p.startswith("<h2")), 1)
cover = '<section class="cover">' + "\n".join(parts[:first_chapter]) + "</section>"
navigation = '<nav aria-label="Daftar isi"><h2>Daftar Isi</h2>' + "".join(toc) + "</nav>"
style = """
* { box-sizing: border-box; }
body { margin: 0; background: #efefea; color: #293c4a; font: 15px/1.7 Arial, sans-serif; }
main { max-width: 940px; margin: 24px auto; background: white; padding: 48px; }
h1 { font: 34px/1.2 Georgia, serif; border-bottom: 4px solid #222831; padding-bottom: 20px; color: #222831; }
h2 { font-size: 23px; margin-top: 40px; color: #222831; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; }
h3 { font-size: 17px; margin-top: 24px; color: #393E46; }
a { color: #1d5b8a; }
code { background: #edf1f3; font: 0.9em Consolas, monospace; overflow-wrap: anywhere; padding: 2px 5px; border-radius: 3px; }
pre { padding: 16px; background: #222831; color: #EEEEEE; border-radius: 6px; overflow-x: auto; }
pre code { background: none; color: inherit; padding: 0; }
table { border-collapse: collapse; width: 100%; font-size: 13px; margin: 16px 0; }
th, td { padding: 9px 12px; border: 1px solid #cbd6dc; text-align: left; vertical-align: top; }
th { background: #e8eef1; font-weight: bold; }
.table { overflow-x: auto; margin: 16px 0; }
nav { padding: 20px; background: #eef2f4; margin: 32px 0; border-radius: 6px; }
nav h2 { margin-top: 0; border: none; padding: 0; }
nav a { display: block; padding: 5px 0; font-weight: 500; }
.toolbar { max-width: 940px; margin: 20px auto; display: flex; flex-wrap: wrap; gap: 10px; }
.toolbar a, button { background: #222831; color: white; border: 0; border-radius: 5px; padding: 10px 14px; font: inherit; text-decoration: none; cursor: pointer; }
.toolbar a:hover, button:hover { background: #393E46; }
@media(max-width:640px) { main { padding: 22px; margin: 0; } .toolbar { padding: 0 16px; } h1 { font-size: 26px; } }
@page { size: A4; margin: 18mm; }
@media print {
    body { background: white; font-size: 10pt; line-height: 1.5; }
    main { padding: 0; margin: 0; max-width: none; }
    .toolbar { display: none; }
    .cover { padding-top: 20mm; break-after: page; }
    nav { background: white; margin: 0; padding: 0; break-after: page; }
    h1 { font-size: 26pt; }
    h2 { font-size: 17pt; break-before: page; margin-top: 0; }
    h3 { font-size: 12pt; }
    h1, h2, h3 { break-after: avoid; }
    table { font-size: 9pt; }
    tr, pre { break-inside: avoid; }
    pre { background: #f8fafc; color: #1e293b; border: 1px solid #cbd5e1; }
    p, li { orphans: 3; widows: 3; }
    a { color: inherit; text-decoration: none; }
}
"""
document = (
    '<!doctype html><html lang="id"><head><meta charset="utf-8">'
    '<meta name="viewport" content="width=device-width,initial-scale=1">'
    '<title>Handbook SIMPUS-Mini (Jobsheet 1–6)</title><style>' + style +
    '</style></head><body><div class="toolbar">'
    '<button onclick="window.print()">Cetak / Simpan PDF</button>'
    '<a href="/docs/HANDBOOK-SIMPUS-JOBSHEET-1-6.md" target="_blank">Buka Sumber Markdown</a>'
    '<a href="/">Masterpage</a></div><main>' + cover + navigation +
    "\n".join(parts[first_chapter:]) + "</main></body></html>"
)
output_html = docs / "HANDBOOK-SIMPUS-JOBSHEET-1-6.html"
output_html.write_text(document, encoding="utf-8")
print(f"Handbook SIMPUS HTML berhasil dibuat: {output_html}")

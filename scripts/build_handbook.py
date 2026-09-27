"""Membuat HTML siap cetak dari handbook Markdown. Jalankan dari folder repo."""
from pathlib import Path
import html
import re

root = Path(__file__).resolve().parents[1]
docs = root / "docs"
source = docs / "HANDBOOK-GEPREK-KITA.md"


def inline(text):
    # Lindungi potongan kode sebelum memproses format teks lainnya.
    codes = []
    def save_code(match):
        codes.append("<code>" + html.escape(match[1]) + "</code>")
        return f"CODETOKEN{len(codes) - 1}END"
    text = re.sub(r"`([^`]+)`", save_code, text)
    text = html.escape(text)
    text = re.sub(r"\*\*(.+?)\*\*", r"<strong>\1</strong>", text)
    text = re.sub(r"\[([^\]]+)\]\((https://[^)]+)\)", r'<a href="\2">\1</a>', text)
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
    next_list = ("ul" if item[1] == "- " else "ol") if item else None
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
        assert (docs / image[2]).is_file(), f"Screenshot hilang: {image[2]}"
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

# Identitas dan pengantar menjadi halaman pembuka sebelum daftar isi.
first_chapter = next(n for n, p in enumerate(parts) if p.startswith("<h2"))
cover = '<section class="cover">' + "\n".join(parts[:first_chapter]) + "</section>"
navigation = '<nav aria-label="Daftar isi"><h2>Daftar Isi</h2>' + "".join(toc) + "</nav>"
style = """
* { box-sizing: border-box; }
body { margin: 0; background: #efefea; color: #293c4a; font: 15px/1.7 Arial, sans-serif; }
main { max-width: 940px; margin: 24px auto; background: white; padding: 48px; }
h1 { font: 38px/1.2 Georgia, serif; border-bottom: 4px solid #304b5e; padding-bottom: 24px; }
h2 { font-size: 25px; margin-top: 48px; color: #304b5e; }
h3 { font-size: 18px; margin-top: 28px; }
a { color: #315f7b; }
code { background: #edf1f3; font: 0.9em Consolas, monospace; overflow-wrap: anywhere; }
pre { padding: 16px; background: #edf1f3; white-space: pre-wrap; overflow-wrap: anywhere; }
table { border-collapse: collapse; width: 100%; font-size: 13px; }
th, td { padding: 8px; border: 1px solid #cbd6dc; text-align: left; vertical-align: top; }
th { background: #e8eef1; }
.table { overflow-x: auto; }
figure { margin: 24px 0; }
img { width: 100%; height: auto; border: 1px solid #d6dfe4; }
nav { padding: 20px; background: #eef2f4; margin-top: 32px; }
nav h2 { margin-top: 0; }
nav a { display: block; padding: 3px 0; }
.toolbar { max-width: 940px; margin: 20px auto; display: flex; flex-wrap: wrap; gap: 10px; }
.toolbar a, button { background: #304b5e; color: white; border: 0; border-radius: 5px; padding: 10px 14px; font: inherit; text-decoration: none; cursor: pointer; }
@media(max-width:640px) { main { padding: 22px; margin: 0; } .toolbar { padding: 0 16px; } h1 { font-size: 30px; } }
@page { size: A4; margin: 18mm; }
@media print {
    body { background: white; font-size: 10pt; line-height: 1.5; }
    main { padding: 0; margin: 0; max-width: none; }
    .toolbar { display: none; }
    .cover { padding-top: 20mm; break-after: page; }
    nav { background: white; margin: 0; padding: 0; }
    h1 { font-size: 28pt; }
    h2 { font-size: 18pt; break-before: page; margin-top: 0; }
    h3 { font-size: 12pt; }
    h1, h2, h3 { break-after: avoid; }
    table { font-size: 9pt; }
    tr, pre, figure { break-inside: avoid; }
    p, li { orphans: 3; widows: 3; }
    a { color: inherit; text-decoration: none; }
    img { max-height: 145mm; object-fit: contain; }
}
"""
document = (
    '<!doctype html><html lang="id"><head><meta charset="utf-8">'
    '<meta name="viewport" content="width=device-width,initial-scale=1">'
    '<title>Handbook Pembuatan Geprek Kita</title><style>' + style +
    '</style></head><body><div class="toolbar">'
    '<button onclick="window.print()">Cetak / Simpan PDF</button>'
    '<a href="/docs/HANDBOOK-GEPREK-KITA.pdf" target="_blank" rel="noopener">Buka PDF</a>'
    '<a href="/">Masterpage</a></div><main>' + cover + navigation +
    "\n".join(parts[first_chapter:]) + "</main></body></html>"
)
(docs / "HANDBOOK-GEPREK-KITA.html").write_text(document, encoding="utf-8")
print(f"Handbook HTML diperbarui: {len(toc)} bagian, {document.count('<figure>')} screenshot.")

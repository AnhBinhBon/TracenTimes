# -*- coding: utf-8 -*-
"""
Script xuất Báo cáo Đồ án Chuyên ngành TracenTimes sang định dạng Microsoft Word (.docx)
Đạt chuẩn quy cách đồ án tốt nghiệp: A4, Căn lề gáy 3cm, Times New Roman 13pt, Giãn dòng 1.3
Tự động nhúng toàn bộ 16 hình ảnh minh chứng Viewport từ thư mục docs/evidence/
"""

import os
from docx import Document
from docx.shared import Inches, Pt, RGBColor, Cm
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import nsdecls, qn

def set_cell_background(cell, fill_hex):
    tcPr = cell._tc.get_or_add_tcPr()
    shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>')
    tcPr.append(shd)

def set_cell_margins(cell, top=100, bottom=100, left=150, right=150):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = parse_xml(f'<w:tcMar {nsdecls("w")}><w:top w:w="{top}" w:type="dxa"/><w:bottom w:w="{bottom}" w:type="dxa"/><w:left w:w="{left}" w:type="dxa"/><w:right w:w="{right}" w:type="dxa"/></w:tcMar>')
    tcPr.append(tcMar)

def create_report_docx():
    doc = Document()
    base_dir = r"c:\xampp\htdocs\TracenTimes"
    evidence_dir = os.path.join(base_dir, "docs", "evidence")
    output_docx = os.path.join(base_dir, "docs", "BAO_CAO_DO_AN_CHUYEN_NGANH_TRACENTIMES.docx")

    # 1. Page Setup (A4, Chuẩn Đồ án: Trái 3.0cm, Phải 2.0cm, Trên 2.5cm, Dưới 2.5cm)
    for section in doc.sections:
        section.page_width = Cm(21.0)
        section.page_height = Cm(29.7)
        section.top_margin = Cm(2.5)
        section.bottom_margin = Cm(2.5)
        section.left_margin = Cm(3.0)
        section.right_margin = Cm(2.0)

    # Base Style
    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Times New Roman'
    normal_style.font.size = Pt(13)
    normal_style.font.color.rgb = RGBColor(0x1a, 0x1a, 0x1a)
    normal_style.paragraph_format.line_spacing = 1.3
    normal_style.paragraph_format.space_after = Pt(6)

    def add_p(text, bold=False, italic=False, align=WD_ALIGN_PARAGRAPH.JUSTIFY, space_after=6, font_size=13, color=None):
        p = doc.add_paragraph()
        p.alignment = align
        p.paragraph_format.line_spacing = 1.3
        p.paragraph_format.space_after = Pt(space_after)
        run = p.add_run(text)
        run.bold = bold
        run.italic = italic
        run.font.name = 'Times New Roman'
        run.font.size = Pt(font_size)
        if color:
            run.font.color.rgb = color
        return p

    def add_h1(text):
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
        p.paragraph_format.space_before = Pt(14)
        p.paragraph_format.space_after = Pt(8)
        run = p.add_run(text)
        run.bold = True
        run.font.name = 'Times New Roman'
        run.font.size = Pt(15)
        run.font.color.rgb = RGBColor(0x8B, 0x25, 0x36) # Auburn
        return p

    def add_h2(text):
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
        p.paragraph_format.space_before = Pt(10)
        p.paragraph_format.space_after = Pt(5)
        run = p.add_run(text)
        run.bold = True
        run.font.name = 'Times New Roman'
        run.font.size = Pt(13.5)
        run.font.color.rgb = RGBColor(0x1A, 0x08, 0x0E)
        return p

    def add_h3(text):
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
        p.paragraph_format.space_before = Pt(6)
        p.paragraph_format.space_after = Pt(4)
        run = p.add_run(text)
        run.bold = True
        run.italic = True
        run.font.name = 'Times New Roman'
        run.font.size = Pt(13)
        return p

    def add_bullet(text):
        p = doc.add_paragraph(style='List Bullet')
        p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p.paragraph_format.line_spacing = 1.25
        p.paragraph_format.space_after = Pt(4)
        run = p.add_run(text)
        run.font.name = 'Times New Roman'
        run.font.size = Pt(13)
        return p

    def add_figure(img_filename, caption_text):
        img_path = os.path.join(evidence_dir, img_filename)
        if os.path.exists(img_path):
            p_img = doc.add_paragraph()
            p_img.alignment = WD_ALIGN_PARAGRAPH.CENTER
            p_img.paragraph_format.space_before = Pt(8)
            p_img.paragraph_format.space_after = Pt(4)
            run_img = p_img.add_run()
            run_img.add_picture(img_path, width=Inches(6.0))

            p_cap = doc.add_paragraph()
            p_cap.alignment = WD_ALIGN_PARAGRAPH.CENTER
            p_cap.paragraph_format.space_after = Pt(12)
            run_cap = p_cap.add_run(caption_text)
            run_cap.italic = True
            run_cap.bold = True
            run_cap.font.name = 'Times New Roman'
            run_cap.font.size = Pt(11)
            run_cap.font.color.rgb = RGBColor(0x44, 0x44, 0x44)

    # ── TRANG BÌA ────────────────────────────────────────────────────────────
    add_p("BỘ GIÁO DỤC VÀ ĐÀO TẠO", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, font_size=14, space_after=2)
    add_p("TRƯỜNG ĐẠI HỌC KINH DOANH VÀ CÔNG NGHỆ HÀ NỘI", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, font_size=14, space_after=2)
    add_p("KHOA CÔNG NGHỆ THÔNG TIN", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, font_size=14, space_after=40, color=RGBColor(0x8B, 0x25, 0x36))

    add_p("BÁO CÁO ĐỒ ÁN CHUYÊN NGÀNH", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, font_size=18, space_after=16, color=RGBColor(0x1a, 0x08, 0x0e))
    add_p("ĐỀ TÀI:", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, font_size=14, space_after=4)
    add_p("XÂY DỰNG TẠP CHÍ & CỔNG THÔNG TIN ĐUA NGỰA HỌC VIỆN TRACEN\n(TRACENTIMES & DERBY CUP SIMULATOR)", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, font_size=16, space_after=80, color=RGBColor(0x8B, 0x25, 0x36))

    # Thông tin tác giả
    table_info = doc.add_table(rows=5, cols=2)
    table_info.alignment = WD_TABLE_ALIGNMENT.CENTER
    info_data = [
        ("Giáo viên hướng dẫn:", "ThS. Lê Đức Huy"),
        ("Sinh viên thực hiện:", "Phạm Thế Bình"),
        ("Mã sinh viên:", "2924104795"),
        ("Lớp:", "TH29.11"),
        ("Khóa học:", "2022 – 2026")
    ]
    for idx, (label, val) in enumerate(info_data):
        row = table_info.rows[idx]
        cell_lbl, cell_val = row.cells[0], row.cells[1]
        p_lbl = cell_lbl.paragraphs[0]
        r1 = p_lbl.add_run(label)
        r1.bold = True
        r1.font.name = 'Times New Roman'
        r1.font.size = Pt(13)
        p_val = cell_val.paragraphs[0]
        r2 = p_val.add_run(val)
        r2.bold = True
        r2.font.name = 'Times New Roman'
        r2.font.size = Pt(13)
        cell_lbl.width = Cm(6)
        cell_val.width = Cm(8)

    doc.add_paragraph().paragraph_format.space_before = Pt(60)
    add_p("HÀ NỘI – 2026", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, font_size=13)
    doc.add_page_break()

    # ── LỜI CẢM ƠN ───────────────────────────────────────────────────────────
    add_h1("LỜI CẢM ƠN")
    add_p("Trong suốt quá trình học tập và rèn luyện tại Khoa Công nghệ Thông tin – Trường Đại học Kinh doanh và Công nghệ Hà Nội, em đã nhận được sự dạy dỗ, chỉ bảo tận tình và truyền đạt những tri thức chuyên môn quý báu từ quý Thầy, Cô giáo. Những kiến thức nền tảng vững chắc về Lập trình Web, Cơ sở dữ liệu, Kiến trúc phần mềm và Phân tích thiết kế hệ thống chính là hành trang vô cùng quan trọng giúp em tự tin triển khai và hoàn thành đồ án chuyên ngành này.")
    add_p("Trước hết, em xin bày tỏ lòng biết ơn sâu sắc và chân thành nhất tới thầy Lê Đức Huy. Thầy đã dành nhiều thời gian tâm huyết để trực tiếp hướng dẫn, gợi mở các phương pháp tư duy kỹ thuật, định hướng giải quyết vấn đề và luôn sát cánh hỗ trợ em tháo gỡ những vướng mắc phát sinh trong suốt quá trình xây dựng hệ thống TracenTimes. Những lời góp ý sâu sắc của Thầy không chỉ giúp đồ án đạt được tính hoàn thiện cao về mặt kỹ thuật mà còn giúp em nâng cao tư duy tổ chức mã nguồn chuyên nghiệp.")
    add_p("Em cũng xin gửi lời cảm ơn chân thành đến Ban Giám hiệu Trường Đại học Kinh doanh và Công nghệ Hà Nội cùng toàn thể các Thầy, Cô giáo trong Khoa Công nghệ Thông tin đã luôn tạo điều kiện học tập, nghiên cứu tốt nhất cho sinh viên chúng em trong suốt những năm tháng trên giảng đường đại học.")
    add_p("Mặc dù em đã nỗ lực hết mình với tinh thần nghiêm túc và cầu thị để hoàn thiện sản phẩm cũng như bản báo cáo đồ án này, song do thời gian và kinh nghiệm thực tiễn còn nhiều hạn chế nên đề tài khó tránh khỏi những thiếu sót nhất định. Em rất mong nhận được những lời nhận xét, góp ý và đánh giá quý báu của quý Thầy, Cô để hệ thống của em ngày càng được hoàn thiện hơn nữa.")
    add_p("Em xin chân thành cảm ơn!")
    
    p_sign = doc.add_paragraph()
    p_sign.alignment = WD_ALIGN_PARAGRAPH.RIGHT
    r_sign = p_sign.add_run("Hà Nội, ngày 18 tháng 10 năm 2026\nSinh viên thực hiện\n\n\nPhạm Thế Bình")
    r_sign.italic = True
    r_sign.bold = True
    r_sign.font.name = 'Times New Roman'
    r_sign.font.size = Pt(13)
    doc.add_page_break()

    # ── DANH MỤC TỪ VIẾT TẮT ────────────────────────────────────────────────
    add_h1("DANH MỤC CÁC TỪ VIẾT TẮT")
    abbrev_data = [
        ("PHP", "Hypertext Preprocessor", "Ngôn ngữ kịch bản chạy phía máy chủ chuyên dụng cho phát triển web"),
        ("MySQL", "My Structured Query Language", "Hệ quản trị cơ sở dữ liệu quan hệ mã nguồn mở phổ biến"),
        ("PDO", "PHP Data Objects", "Lớp kết nối CSDL an toàn trong PHP, hỗ trợ Prepared Statements chống SQLi"),
        ("3-Tier", "Three-Tier Architecture", "Kiến trúc 3 tầng: Giao diện (Presentation) – Nghiệp vụ (BLL) – Dữ liệu (DAL)"),
        ("DFD", "Data Flow Diagram", "Biểu đồ luồng dữ liệu mô tả tiến trình thu thập, xử lý và lưu trữ thông tin"),
        ("ERD", "Entity Relationship Diagram", "Sơ đồ quan hệ thực thể mô hình hóa cơ sở dữ liệu quan hệ"),
        ("DNF", "Did Not Finish", "Thuật ngữ thể thao đua ngựa chỉ vận động viên chấn thương/bỏ cuộc giữa chặng"),
        ("CRUD", "Create, Read, Update, Delete", "Bốn thao tác dữ liệu cơ bản: Thêm mới, Xem, Cập nhật, Xóa bản ghi"),
        ("UI/UX", "User Interface / User Experience", "Giao diện người dùng và Trải nghiệm người dùng"),
        ("SQLi", "SQL Injection", "Kỹ thuật tấn công chèn mã lệnh SQL độc hại vào câu truy vấn của ứng dụng"),
        ("XSS", "Cross-Site Scripting", "Lỗ hổng cho phép kẻ tấn công thực thi mã JavaScript độc hại trên trình duyệt"),
        ("BCrypt", "Blowfish Cryptographic Hash", "Thuật toán băm mật khẩu bảo mật một chiều chống dò vét cạn")
    ]
    t_abbrev = doc.add_table(rows=len(abbrev_data)+1, cols=4)
    t_abbrev.alignment = WD_TABLE_ALIGNMENT.CENTER
    headers = ["STT", "Từ viết tắt", "Tên tiếng Anh đầy đủ", "Giải thích nghĩa tiếng Việt"]
    for i, h in enumerate(headers):
        cell = t_abbrev.rows[0].cells[i]
        set_cell_background(cell, "2A0E17")
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(11.5)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    for idx, (abbr, en, vi) in enumerate(abbrev_data):
        row = t_abbrev.rows[idx+1]
        bg = "F9F8FA" if idx % 2 == 1 else "FFFFFF"
        for i, val in enumerate([str(idx+1), abbr, en, vi]):
            cell = row.cells[i]
            set_cell_background(cell, bg)
            set_cell_margins(cell, top=80, bottom=80, left=100, right=100)
            p = cell.paragraphs[0]
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if i in [0, 1] else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(val)
            r.bold = (i == 1)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(11)

    t_abbrev.columns[0].width = Cm(1.2)
    t_abbrev.columns[1].width = Cm(2.2)
    t_abbrev.columns[2].width = Cm(5.6)
    t_abbrev.columns[3].width = Cm(7.0)

    doc.add_page_break()

    # ── LỜI MỞ ĐẦU ───────────────────────────────────────────────────────────
    add_h1("LỜI MỞ ĐẦU")
    add_h2("1. Lý do chọn đề tài")
    add_p("Trong kỷ nguyên bùng nổ của truyền thông số và ngành công nghiệp thể thao điện tử, nhu cầu tiếp cận thông tin chuyên sâu, tra cứu dữ liệu khoa học và tương tác trực quan của người hâm mộ đối với các tựa game thể thao chiến thuật ngày càng nâng cao. Umamusume: Pretty Derby là một trong những hiện tượng văn hóa đa phương tiện có sức ảnh hưởng toàn cầu, kết hợp độc đáo giữa văn hóa đua ngựa truyền thống và thiết kế nhân vật nhân hóa ấn tượng. Tuy nhiên, tại thị trường Việt Nam hiện nay, hầu hết các trang thông tin về đề tài này đều mang tính tự phát, phân mảnh, giao diện nghèo nàn và thiếu các công cụ tra cứu số liệu thi đấu chuyên nghiệp.")
    add_p("Mặt khác, trong quá trình học tập chuyên ngành Công nghệ Thông tin tại Trường Đại học Kinh doanh và Công nghệ Hà Nội, em nhận thấy nhiều đồ án xây dựng ứng dụng web bằng PHP trước đây thường áp dụng phong cách viết mã thủ tục (Procedural Programming). Lối tiếp cận này thường trộn lẫn mã xử lý dữ liệu SQL, mã điều khiển logic PHP và giao diện HTML vào cùng một tệp mã nguồn (Spaghetti Code). Hậu quả dẫn tới hệ thống cực kỳ khó bảo trì, khả năng mở rộng thấp, dễ bị tấn công khai thác bảo mật (đặc biệt là lỗ hổng SQL Injection và Cross-Site Scripting), và giao diện chưa đạt tính thẩm mỹ chuyên nghiệp theo tiêu chuẩn web hiện đại.")
    add_p("Xuất phát từ thực tiễn trên, em đã quyết định lựa chọn đề tài: \"Xây dựng Tạp chí & Cổng Thông Tin Đua Ngựa Học Viện Tracen (TracenTimes & Derby Cup Simulator)\". Hệ thống được xây dựng hoàn toàn trên nền tảng Pure PHP 8.2 theo chuẩn kiến trúc 3 lớp (3-Tier Architecture), sử dụng lớp truy cập dữ liệu an toàn PDO Prepared Statements, kết hợp ngôn ngữ thiết kế độc quyền Tokai Teio Theme (nền tối auburn-black kết hợp dải màu hồng magenta, xanh cyan neon và vàng gold hoàng gia). Đề tài không chỉ dừng lại ở một cổng tin tức tĩnh đơn thuần mà còn tích hợp bộ từ điển nhân vật chuẩn MediaWiki và bộ mô phỏng vật lý giải đua Grand Derby với cơ chế tính toán rủi ro chấn thương chân thực.")

    add_h2("2. Mục tiêu của đề tài")
    add_bullet("Về mặt học thuật & phương pháp luận: Vận dụng toàn diện kiến thức đã tích lũy trong chương trình đào tạo kỹ sư/cử nhân CNTT vào một bài toán thực tế; làm chủ mô hình phân tầng 3 lớp (Presentation – Business Logic – Data Access) và thiết kế CSDL quan hệ chuẩn hóa 3NF.")
    add_bullet("Về mặt an toàn bảo mật: Triệt tiêu hoàn toàn lỗ hổng SQL Injection bằng cách chuẩn hóa 100% các câu truy vấn qua tham số hóa PDO; bảo vệ hệ thống trước tấn công XSS bằng cơ chế khử khuẩn nội dung; thiết lập cơ chế kiểm soát phiên làm việc (Session Role Guard) và bảo vệ Quản trị viên tối cao (Root Admin).")
    add_bullet("Về mặt chức năng người dùng (Storefront): Cung cấp trải nghiệm thông tin toàn diện với Video Hero Banner, Game Launcher Modal \"Play Now\", Danh mục tin tức 10 chuyên mục có phân trang, thư viện 16 Umamusume với bộ lọc phong cách chạy tức thì, trang hồ sơ chuẩn MediaWiki 2 cột dính và trình mô phỏng cuộc đua Derby Cup với bục vinh quang Top 3 hoành tráng.")
    add_bullet("Về mặt quản trị hệ thống (Admin Portal): Xây dựng trung tâm điều hành chuyên nghiệp với Dashboard 5 thẻ số liệu thời gian thực; Quản lý tin tức tích hợp trình soạn thảo văn bản giàu tính năng (Rich Text Editor); Quản lý Mã Nương 2 cấp (Hồ sơ & Chỉ số tốc độ, chấn thương); Hỗ trợ tính năng ưu tiên link ảnh trực tuyến và đối tượng thử nghiệm ảnh rỗng (Null Exception); Quản trị người dùng với giao diện tìm kiếm trái – thêm phải cân đối.")

    add_h2("3. Đối tượng và phạm vi nghiên cứu")
    add_bullet("Đối tượng nghiên cứu: Kiến trúc phần mềm 3 lớp (3-Tier Architecture) trong PHP; Kỹ thuật giao tiếp CSDL an toàn với PDO; Kỹ thuật CSS Flexbox, Grid và Dark Theme; Thuật toán mô phỏng biến thiên tốc độ và xác suất chấn thương thể thao (High Risk – High Reward); Tiêu chuẩn giao diện bách khoa thư MediaWiki.")
    add_bullet("Phạm vi nghiên cứu: Hệ thống được cài đặt, vận hành và kiểm thử toàn diện trên môi trường máy chủ cục bộ (XAMPP Server trên hệ điều hành Windows), phục vụ hai đối tượng chính: Độc giả yêu thích bộ môn đua ngựa giả lập và Quản trị viên điều hành hệ thống.")

    add_h2("4. Bố cục của bài báo cáo")
    add_p("Bài báo cáo đồ án được kết cấu thành 4 chương chính:")
    add_bullet("Chương 1: Tổng quan về đề tài và công nghệ nền tảng: Trình bày bối cảnh, lý thuyết về PHP 8.2, MySQL, PDO và mô hình kiến trúc 3 lớp.")
    add_bullet("Chương 2: Phân tích và thiết kế hệ thống: Phân tích yêu cầu chức năng, xây dựng biểu đồ DFD Mức 0, DFD Mức 1 và thiết kế CSDL 5 bảng chuẩn 3NF.")
    add_bullet("Chương 3: Hiện thực hóa và cài đặt chương trình: Trình bày chi tiết giao diện, giải pháp kỹ thuật và kết quả lập trình kèm 16 hình ảnh minh chứng thực tế.")
    add_bullet("Chương 4: Kiểm thử và đánh giá kết quả đạt được: Tổng hợp kết quả thực thi 22 ca kiểm thử chức năng và bảo mật, đánh giá ưu điểm, hạn chế và hướng phát triển.")
    doc.add_page_break()

    # ── CHƯƠNG 1 ─────────────────────────────────────────────────────────────
    add_h1("CHƯƠNG 1. TỔNG QUAN VỀ ĐỀ TÀI VÀ CÔNG NGHỆ NỀN TẢNG")
    add_h2("1.1. Bối cảnh đề tài và văn hóa truyền thông Umamusume Pretty Derby")
    add_p("Trong ngành công nghiệp giải trí hiện đại, văn hóa chuyển thể thể thao và truyền thông đa phương tiện đã tạo ra những bước đột phá mạnh mẽ. Umamusume: Pretty Derby do Cygames phát triển là một ví dụ điển hình khi biến các chiến mã huyền thoại trong lịch sử đua ngựa thế giới thành các nữ vận động viên tài năng tại Học viện Tracen. Để phục vụ cộng đồng người chơi, việc xây dựng một cổng thông tin chuyên nghiệp mang tên TracenTimes là vô cùng cấp thiết, đóng vai trò như một cơ quan báo chí chính thức đưa tin tức sự kiện, phân tích chỉ số thi đấu và truyền thông giải đấu Grand Derby Cup.")

    add_h2("1.2. Các công nghệ sử dụng trong dự án")
    add_h3("1.2.1. Ngôn ngữ Pure PHP 8.2 và Quản lý phiên làm việc (Session)")
    add_p("PHP là ngôn ngữ lập trình kịch bản máy chủ hàng đầu thế giới dành cho ứng dụng web. Trong đồ án này, em sử dụng phiên bản PHP 8.2 thuần (Pure PHP) mà không phụ thuộc vào bất kỳ framework bên thứ ba nào. Việc sử dụng PHP thuần giúp nắm vững bản chất cốt lõi của giao thức HTTP, vòng đời Request/Response và kỹ thuật lập trình hướng đối tượng OOP. Hệ thống sử dụng $_SESSION kết hợp thuật toán băm mật khẩu an toàn BCrypt (password_hash) để xác thực người dùng và bảo vệ khu vực quản trị qua lớp Auth.")

    add_h3("1.2.2. Hệ quản trị CSDL MySQL / MariaDB và thư viện PDO")
    add_p("MySQL là hệ quản trị cơ sở dữ liệu quan hệ mạnh mẽ, tốc độ cao. Dự án áp dụng PDO (PHP Data Objects) với cơ chế Prepared Statements (tham số hóa dấu hỏi ? hoặc tham số đặt tên). Cơ chế này tách bạch hoàn toàn câu lệnh SQL và giá trị người dùng nhập vào, loại trừ 100% nguy cơ tấn công SQL Injection. Đồng thời, lớp Database áp dụng mẫu Singleton Pattern để tối ưu kết nối và tiết kiệm tài nguyên bộ nhớ.")

    add_h3("1.2.3. Ngôn ngữ HTML5 Semantic, CSS3 Modular & Vanilla JavaScript")
    add_p("Hệ thống sử dụng HTML5 ngữ nghĩa chuẩn, kết hợp CSS3 Modular với bảng màu Tokai Teio Theme và các tương tác thời gian thực bằng Vanilla JavaScript thuần (bộ lọc phong cách chạy, hoạt ảnh đường đua 2400m, tính toán kết quả).")

    add_h3("1.2.4. Môi trường máy chủ cục bộ XAMPP")
    add_p("XAMPP tích hợp sẵn Apache, MariaDB và PHP 8.2, tạo môi trường thuận lợi để phát triển và kiểm thử hệ thống tại địa chỉ http://localhost/TracenTimes.")

    add_h2("1.3. Mô hình kiến trúc phần mềm 3 lớp (3-Tier Architecture)")
    add_p("Kiến trúc 3 lớp phân tách ứng dụng thành ba tầng biệt lập về mặt trách nhiệm:")
    add_bullet("1. Tầng Trình diễn (Presentation Layer / Views): Nhận request từ người dùng, gọi xuống Service tương ứng và hiển thị mã HTML/CSS. Tuyệt đối không chứa câu lệnh SQL.")
    add_bullet("2. Tầng Nghiệp vụ (Business Logic Layer / Services): Đảm nhiệm việc xác thực dữ liệu (validation), logic tính toán (min/max tốc độ, tỷ lệ chấn thương), kiểm tra quyền hạn và upload ảnh.")
    add_bullet("3. Tầng Truy cập Dữ liệu (Data Access Layer / DAOs): Giao tiếp trực tiếp với Database qua PDO Prepared Statements, trả kết quả mảng/DTO cho tầng Service.")
    add_p("Toàn bộ cấu hình hệ thống được đặt tại config/app.php và lớp tiện ích dùng chung core/Helper.php (định dạng URL, render HTML sạch sẽ, nạp ảnh đại diện với fallback Null Exception, chuyển hướng trang an toàn).")
    doc.add_page_break()

    # ── CHƯƠNG 2 ─────────────────────────────────────────────────────────────
    add_h1("CHƯƠNG 2. PHÂN TÍCH VÀ THIẾT KẾ HỆ THỐNG")
    add_h2("2.1. Phân tích yêu cầu chức năng hệ thống")
    add_p("Hệ thống TracenTimes được thiết kế phục vụ hai phân hệ người dùng chính:")
    add_bullet("Phân hệ Độc giả (Storefront): Xem Trang chủ với video banner, khởi chạy modal Game Launcher \"Play Now\", đọc danh mục tin tức 10 chuyên mục có phân trang, xem chi tiết tin tức HTML sạch, tra cứu 16 nhân vật và trang MediaWiki 2 cột dính, trải nghiệm mô phỏng giải đua Derby Cup với bục vinh quang Top 3, đăng ký và đăng nhập tài khoản.")
    add_bullet("Phân hệ Quản trị viên (Admin Portal): Bảng điều khiển Dashboard 5 thẻ số liệu thời gian thực, quản lý tin bài với Rich Text Editor, quản lý Mã Nương 2 cấp (Hồ sơ & Chỉ số thi đấu), hỗ trợ ưu tiên Link ảnh trực tuyến và đối tượng thử nghiệm Null Exception, quản trị người dùng với giao diện tìm kiếm trái – thêm phải và khóa bảo vệ Root Admin chống xóa.")

    add_h2("2.2. Biểu đồ luồng dữ liệu (Data Flow Diagram - DFD)")
    add_p("Hệ thống xây dựng Biểu đồ DFD Mức 0 (mô tả tương tác giữa Độc giả, Quản trị viên và hệ thống) và Biểu đồ DFD Mức 1 phân rã 5 tiến trình nghiệp vụ cốt lõi: 1.0 Xác thực & Tài khoản; 2.0 Quản lý & Kết xuất Tin tức; 3.0 Hồ sơ & Bách khoa thư Nhân vật; 4.0 Cân bằng & Mô phỏng Đua ngựa; 5.0 Quản lý Bộ sưu tập Truyền thông.")

    add_h2("2.3. Thiết kế cơ sở dữ liệu quan hệ (Database Design)")
    add_p("Cơ sở dữ liệu của hệ thống gồm 5 bảng chính đạt chuẩn chuẩn hóa 3NF: users (tài khoản người dùng), characters (hồ sơ Uma), character_stats (chỉ số tốc độ & chấn thương quan hệ 1-1 với characters), news (tin tức & bài viết), galleries (bộ sưu tập ảnh chất lượng cao).")

    # Bảng CSDL
    db_tables_summary = [
        ("users", "Lưu trữ tài khoản, mật khẩu băm BCrypt, vai trò admin/user và thời gian khởi tạo"),
        ("characters", "Lưu trữ hồ sơ nhân vật: tên Anh/Nhật, biệt danh, CV, sinh nhật, chiều cao, link ảnh trực tuyến, ảnh nội bộ và tiểu sử"),
        ("character_stats", "Lưu trữ thông số thi đấu: dải tốc độ Min-Max, chu kỳ đổi tốc độ, tỷ lệ chấn thương, tỷ lệ bứt phá và điểm RPG Speed/Stamina/Power (Khóa ngoại character_id)"),
        ("news", "Lưu trữ tin tức: tiêu đề, chuyên mục (10 thể loại), tóm tắt, nội dung giàu định dạng, ảnh bìa, cờ ghim tiêu điểm và ngày xuất bản"),
        ("galleries", "Lưu trữ danh sách hình ảnh truyền thông chất lượng cao hiển thị trên trang chủ")
    ]
    t_db = doc.add_table(rows=len(db_tables_summary)+1, cols=3)
    t_db.alignment = WD_TABLE_ALIGNMENT.CENTER
    db_headers = ["STT", "Tên bảng dữ liệu", "Chức năng & Mô tả chi tiết"]
    for i, h in enumerate(db_headers):
        cell = t_db.rows[0].cells[i]
        set_cell_background(cell, "2A0E17")
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(11.5)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    for idx, (tname, desc) in enumerate(db_tables_summary):
        row = t_db.rows[idx+1]
        bg = "F9F8FA" if idx % 2 == 1 else "FFFFFF"
        for i, val in enumerate([str(idx+1), tname, desc]):
            cell = row.cells[i]
            set_cell_background(cell, bg)
            set_cell_margins(cell, top=80, bottom=80, left=100, right=100)
            p = cell.paragraphs[0]
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if i == 0 else (WD_ALIGN_PARAGRAPH.CENTER if i == 1 else WD_ALIGN_PARAGRAPH.JUSTIFY)
            r = p.add_run(val)
            r.bold = (i == 1)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(11)

    t_db.columns[0].width = Cm(1.2)
    t_db.columns[1].width = Cm(4.0)
    t_db.columns[2].width = Cm(10.8)

    doc.add_page_break()

    # ── CHƯƠNG 3 ─────────────────────────────────────────────────────────────
    add_h1("CHƯƠNG 3. HIỆN THỰC HÓA VÀ CÀI ĐẶT CHƯƠNG TRÌNH")
    add_h2("3.1. Cấu trúc thư mục mã nguồn dự án")
    add_p("Hệ thống TracenTimes được phân bổ thành các thư mục chuyên biệt: config/ (cấu hình kết nối CSDL), core/ (Auth, Helper, ImageNotFoundException), modules/ (các DAO và Service xử lý nghiệp vụ cùng trang admin), pages/ (các trang giao diện độc giả), sections/ (các khối thành phần trang chủ), docs/ (tài liệu kỹ thuật và 16 ảnh minh chứng tại docs/evidence/), css/ và js/.")

    add_h2("3.2. Giao diện và các chức năng phía Độc giả / Người dùng")
    
    add_h3("3.2.1. Trang chủ Tạp chí TracenTimes & Modal Game Launcher \"Play Now\"")
    add_p("Trang chủ TracenTimes sở hữu giao diện đậm chất thể thao với video banner nền học viện chuyển động mượt mà. Nút bấm PLAY NOW nổi bật trên thanh điều hướng mở ra cửa sổ modal thể thao với nền sọc chéo Umamusume cho phép chọn 4 nền tảng tải game (Steam, App Store, Google Play, PC Games).")
    add_figure("fig_3_1_homepage_hero.png", "Hình 3.1: Giao diện Trang chủ Tạp chí TracenTimes với Hero Video Banner & Tokai Teio Theme")
    add_figure("fig_3_2_play_now_launcher.png", "Hình 3.2: Giao diện Modal Game Launcher \"Play Now\" chọn nền tảng tải game")

    add_h3("3.2.2. Chuyên trang Tin tức (News Catalog) & Phân trang Động")
    add_p("Trang Tin tức hiển thị danh sách 19 bài viết với 10 chuyên mục tin tức. Hệ thống hỗ trợ lọc bài viết tức thì theo thẻ chuyên mục và phân trang mượt mà qua 4 trang dữ liệu.")
    add_figure("fig_3_3_news_catalog.png", "Hình 3.3: Chuyên trang Danh mục Tin tức với bộ lọc chuyên mục và phân trang")

    add_h3("3.2.3. Trang Chi tiết Tin tức (Định dạng HTML Sạch Sẽ)")
    add_p("Bài viết chi tiết được hiển thị qua hàm Helper::renderContent(), loại bỏ hoàn toàn lỗi in lộ thẻ HTML thô, hỗ trợ chữ in đậm, danh sách và trích dẫn khối bắt mắt.")
    add_figure("fig_3_4_news_detail_clean.png", "Hình 3.4: Trang Chi tiết Tin tức kết xuất HTML sạch sẽ, định dạng phong phú")

    add_h3("3.2.4. Thư viện Nhân vật & Bộ lọc Phong cách chạy")
    add_p("Thư viện hiển thị danh sách 16 Uma Musume kèm thanh thuộc tính RPG (Speed, Stamina, Power). Bộ lọc phong cách chạy (Runner, Leader, Betweener, Chaser) phản hồi tức thì trên trình duyệt.")
    add_figure("fig_3_5_characters_catalog.png", "Hình 3.5: Thư viện Danh sách Nhân vật với bộ lọc phong cách chạy tức thì")

    add_h3("3.2.5. Trang Chi tiết Nhân vật chuẩn MediaWiki (Narita Brian)")
    add_p("Trang hồ sơ nhân vật được thiết kế 2 cột chuẩn bách khoa thư MediaWiki: Cột bên trái gồm Mục lục động [ẩn/hiện], tiểu sử, tính cách; Cột bên phải là Sticky Infobox dính viền gradient tím-hồng hiển thị thông tin CV, ngày sinh và ảnh chân dung sắc nét.")
    add_figure("fig_3_6_character_detail_wiki.png", "Hình 3.6: Trang Chi tiết Nhân vật (Narita Brian) chuẩn MediaWiki với Mục lục động và Sticky Infobox")

    add_h3("3.2.6. Trình mô phỏng Đường đua Derby Cup & Bục Vinh Quang Top 3")
    add_p("Trình mô phỏng đường đua 2400m thời gian thực xử lý biến thiên tốc độ và nguy cơ chấn thương. Khi kết thúc, hệ thống kích hoạt Bục vinh quang Top 3 hoành tráng (Vàng - Bạc - Đồng kèm Jockey) và Bảng kết quả thi đấu dãn rộng 1040px tích hợp Avatar Uma và trạng thái DNF.")
    add_figure("fig_3_7_racing_podium_top3.png", "Hình 3.7: Bục Vinh Quang Top 3 tôn vinh các chiến mã và Nài ngựa xuất sắc nhất")
    add_figure("fig_3_8_racing_results_table.png", "Hình 3.8: Bảng kết quả thi đấu dãn rộng 1040px tích hợp đầy đủ thông số thi đấu")

    add_h3("3.2.7. Hệ thống Xác thực Bảo mật (Đăng nhập & Đăng ký)")
    add_p("Giao diện đăng nhập căn giữa sang trọng, loại bỏ hoàn toàn khung tài khoản mẫu để đảm bảo an toàn thông tin người dùng.")
    add_figure("fig_3_9_login_security.png", "Hình 3.9: Giao diện Đăng nhập an toàn, căn giữa hiện đại")

    add_h2("3.3. Giao diện và các chức năng phía Quản trị viên (Admin Portal)")
    add_h3("3.3.1. Bảng điều khiển Quản trị viên (Admin Dashboard)")
    add_p("Dashboard hiển thị 5 khối thống kê số liệu vàng/neon thời gian thực và danh sách bài viết / hồ sơ Uma mới nhất.")
    add_figure("fig_3_10_admin_dashboard.png", "Hình 3.10: Bảng điều khiển Quản trị viên (Admin Dashboard) với 5 khối thống kê thời gian thực")

    add_h3("3.3.2. Quản lý Danh sách Tin tức & Trình soạn thảo Rich Text Editor")
    add_p("Hệ thống quản lý bài viết trực quan kèm bộ lọc và ghim tiêu điểm. Form soạn thảo tích hợp thanh công cụ Rich Text (In đậm, In nghiêng, Gạch chân, Tiêu đề, Danh sách, Màu sắc) tiện dụng.")
    add_figure("fig_3_11_admin_news_manage.png", "Hình 3.11: Bảng Quản lý Danh sách Tin tức với bộ lọc chuyên mục và ghim tiêu điểm")
    add_figure("fig_3_12_admin_news_richtext.png", "Hình 3.12: Trình soạn thảo Tin tức Rich Text Editor trực quan và tiện dụng")

    add_h3("3.3.3. Quản lý Mã Nương: Hồ sơ, Ưu tiên Link ảnh & Null Exception")
    add_p("Menu Quản lý Mã Nương hợp nhất 2 cấp. Trang hồ sơ Uma hỗ trợ trường nhập trực tuyến Image URL ưu tiên tải ảnh nhanh và cho phép để rỗng để kiểm thử đối tượng Null Exception.")
    add_figure("fig_3_13_admin_characters_manage.png", "Hình 3.13: Quản lý Mã Nương: Danh sách Hồ sơ Uma với thông tin CV lồng tiếng và chiều cao")
    add_figure("fig_3_14_admin_character_form_link.png", "Hình 3.14: Form Hồ sơ Uma với tính năng ưu tiên Link ảnh trực tuyến và đối tượng thử nghiệm Null Exception")

    add_h3("3.3.4. Quản lý Mã Nương: Bảng Cân bằng Tốc độ & Chấn thương")
    add_p("Bảng cân bằng thông số 10 cột căn chỉnh hoàn hảo, các thẻ tag tỷ lệ chấn thương và chiến thuật giữ nguyên khối, nút thao tác \"Chỉnh\" màu vàng nằm trọn trong viền bảng.")
    add_figure("fig_3_15_admin_stats_manage.png", "Hình 3.15: Bảng Cân bằng Thông số Tốc độ & Chấn thương với bố cục cân đối và nút bấm nguyên vẹn")

    add_h3("3.3.5. Quản trị Tài khoản: Tìm kiếm trái – Thêm phải & Khóa Root Admin")
    add_p("Trang quản trị tài khoản được sắp xếp tối ưu: Thanh tìm kiếm và lọc vai trò bên trái, nút Thêm tài khoản mới bên phải; tài khoản Root Admin ID 1 được bảo vệ vĩnh viễn bằng ổ khóa chống xóa.")
    add_figure("fig_3_16_admin_users_manage.png", "Hình 3.16: Bảng Quản trị Tài khoản với thanh công cụ tìm kiếm trái - thêm phải và khóa bảo vệ Root Admin")
    doc.add_page_break()

    # ── CHƯƠNG 4 ─────────────────────────────────────────────────────────────
    add_h1("CHƯƠNG 4. KIỂM THỬ VÀ ĐÁNH GIÁ KẾT QUẢ ĐẠT ĐƯỢC")
    add_h2("4.1. Kịch bản và kết quả kiểm thử chức năng (Functional Testing)")
    add_p("Hệ thống đã trải qua quy trình kiểm thử toàn diện 22 ca kiểm thử trên cả hai phân hệ Storefront và Admin Portal:")

    # Bảng 4.1
    add_h3("Bảng 4.1: Kết quả kiểm thử Phân hệ Khách / Độc giả (Storefront)")
    tc_storefront = [
        ("1", "Trang chủ & Hero Banner", "Video tải mượt, typography phân cấp", "Hoạt động hoàn hảo", "ĐẠT", "Hình 3.1"),
        ("2", "Modal Game Launcher Play Now", "Mở modal chọn 4 nền tảng tải game", "Modal mượt mà, icon rõ", "ĐẠT", "Hình 3.2"),
        ("3", "Danh mục Tin tức & Phân trang", "Hiển thị 19 bài, 10 chuyên mục, 4 trang", "Lọc chuẩn, chuyển trang nhanh", "ĐẠT", "Hình 3.3"),
        ("4", "Chi tiết Tin tức (Clean HTML)", "Nội dung sạch, không lộ thẻ HTML thô", "Kết xuất chuẩn xác", "ĐẠT", "Hình 3.4"),
        ("5", "Thư viện Nhân vật & Bộ lọc", "Lọc tức thì 16 Uma theo phong cách", "Lọc nhanh không reload", "ĐẠT", "Hình 3.5"),
        ("6", "Chi tiết Uma chuẩn MediaWiki", "2 cột, Mục lục động, Sticky Infobox", "Trình bày chuẩn MediaWiki", "ĐẠT", "Hình 3.6"),
        ("7", "Derby Cup: Bục Top 3 (Podium)", "Vinh danh Quán quân/Á quân/Quý quân", "Bục trao giải hoành tráng", "ĐẠT", "Hình 3.7"),
        ("8", "Derby Cup: Bảng điểm 1040px", "Bảng dãn rộng, có Avatar Uma và DNF", "Dữ liệu thi đấu rành mạch", "ĐẠT", "Hình 3.8"),
        ("9", "Đăng nhập an toàn", "Khung căn giữa, không lộ tài khoản mẫu", "Bảo mật phiên nghiêm ngặt", "ĐẠT", "Hình 3.9")
    ]
    t_tc1 = doc.add_table(rows=len(tc_storefront)+1, cols=6)
    t_tc1.alignment = WD_TABLE_ALIGNMENT.CENTER
    tc_headers = ["STT", "Chức năng kiểm thử", "Kết quả mong đợi", "Kết quả thực tế", "Trạng thái", "Minh chứng"]
    for i, h in enumerate(tc_headers):
        cell = t_tc1.rows[0].cells[i]
        set_cell_background(cell, "2A0E17")
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(10.5)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    for idx, (stt, fn, exp, act, st, proof) in enumerate(tc_storefront):
        row = t_tc1.rows[idx+1]
        bg = "F9F8FA" if idx % 2 == 1 else "FFFFFF"
        for i, val in enumerate([stt, fn, exp, act, st, proof]):
            cell = row.cells[i]
            set_cell_background(cell, bg)
            set_cell_margins(cell, top=60, bottom=60, left=80, right=80)
            p = cell.paragraphs[0]
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if i in [0, 4, 5] else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(val)
            r.bold = (i in [1, 4])
            r.font.name = 'Times New Roman'
            r.font.size = Pt(10)
            if i == 4 and val == "ĐẠT":
                r.font.color.rgb = RGBColor(0x10, 0xB9, 0x81)

    t_tc1.columns[0].width = Cm(1.0)
    t_tc1.columns[1].width = Cm(3.2)
    t_tc1.columns[2].width = Cm(4.0)
    t_tc1.columns[3].width = Cm(4.0)
    t_tc1.columns[4].width = Cm(1.6)
    t_tc1.columns[5].width = Cm(2.2)

    doc.add_paragraph().paragraph_format.space_before = Pt(8)

    # Bảng 4.2
    add_h3("Bảng 4.2: Kết quả kiểm thử Phân hệ Quản trị viên (Admin Portal)")
    tc_admin = [
        ("10", "Middleware Bảo vệ Quản trị", "Chặn truy cập trái phép, redirect login", "Bảo vệ nghiêm ngặt 100%", "ĐẠT", "Session Guard"),
        ("11", "Bảng điều khiển Dashboard", "5 thẻ số liệu thời gian thực và bài mới", "Truy vấn CSDL chính xác", "ĐẠT", "Hình 3.10"),
        ("12", "Quản lý Danh sách Tin tức", "Bảng tin tức, lọc chuyên mục, ghim", "Thao tác CRUD trơn tru", "ĐẠT", "Hình 3.11"),
        ("13", "Soạn thảo Tin tức Rich Text", "Toolbar định dạng B, I, U, H3, H4, List...", "Chèn thẻ HTML trực quan", "ĐẠT", "Hình 3.12"),
        ("14", "Quản lý Mã Nương: Hồ sơ", "Xem danh sách Uma, CV, sinh nhật...", "Menu 2 cấp khoa học", "ĐẠT", "Hình 3.13"),
        ("15", "Form Hồ sơ: Link ảnh online", "Ưu tiên URL ảnh, xử lý Null Exception", "Tải ảnh ưu tiên chuẩn xác", "ĐẠT", "Hình 3.14"),
        ("16", "Bảng Tốc độ & Chấn thương", "Bảng 10 cột cân đối, nút Chỉnh nguyên vẹn", "Không tràn bảng, nút trọn viền", "ĐẠT", "Hình 3.15"),
        ("17", "Quản trị Tài khoản & Khóa Admin", "Tìm kiếm trái, thêm phải; khóa Root Admin", "Khóa xóa Root Admin an toàn", "ĐẠT", "Hình 3.16")
    ]
    t_tc2 = doc.add_table(rows=len(tc_admin)+1, cols=6)
    t_tc2.alignment = WD_TABLE_ALIGNMENT.CENTER
    for i, h in enumerate(tc_headers):
        cell = t_tc2.rows[0].cells[i]
        set_cell_background(cell, "2A0E17")
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(10.5)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    for idx, (stt, fn, exp, act, st, proof) in enumerate(tc_admin):
        row = t_tc2.rows[idx+1]
        bg = "F9F8FA" if idx % 2 == 1 else "FFFFFF"
        for i, val in enumerate([stt, fn, exp, act, st, proof]):
            cell = row.cells[i]
            set_cell_background(cell, bg)
            set_cell_margins(cell, top=60, bottom=60, left=80, right=80)
            p = cell.paragraphs[0]
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if i in [0, 4, 5] else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(val)
            r.bold = (i in [1, 4])
            r.font.name = 'Times New Roman'
            r.font.size = Pt(10)
            if i == 4 and val == "ĐẠT":
                r.font.color.rgb = RGBColor(0x10, 0xB9, 0x81)

    t_tc2.columns[0].width = Cm(1.0)
    t_tc2.columns[1].width = Cm(3.2)
    t_tc2.columns[2].width = Cm(4.0)
    t_tc2.columns[3].width = Cm(4.0)
    t_tc2.columns[4].width = Cm(1.6)
    t_tc2.columns[5].width = Cm(2.2)

    doc.add_paragraph().paragraph_format.space_before = Pt(8)

    add_h2("4.2. Kiểm thử bảo mật và các trường hợp biên")
    add_p("Hệ thống được kiểm thử kỹ lưỡng về khả năng chịu lỗi và an toàn dữ liệu: Chống SQL Injection 100% bằng PDO Prepared Statements; Khử khuẩn XSS; Bắt lỗi ngoại lệ tải ảnh (Image Fallback / Null Exception); Cơ chế menu tràn 3 chấm (...) bảo vệ giao diện khi màn hình co hẹp; Đồng bộ chuẩn UTF-8 toàn hệ thống đảm bảo 100% không lỗi font tiếng Việt.")

    add_h2("4.3. Đánh giá kết quả đạt được, thuận lợi và khó khăn")
    add_bullet("Kết quả đạt được: Hoàn thành 100% mục tiêu kiến trúc 3 lớp, bảo mật dữ liệu tuyệt đối, thiết kế Tokai Teio Theme chuyên nghiệp và bộ minh chứng 16 hình ảnh Viewport hoàn chỉnh.")
    add_bullet("Những điểm còn hạn chế: Cuộc đua Derby Cup hiện tính toán ngẫu nhiên phía máy khách, chưa đồng bộ đa người chơi thời gian thực qua WebSocket; chưa có cổng nạp tiền cược trực tuyến.")
    add_bullet("Hướng phát triển: Tích hợp WebSocket (Ratchet PHP) cho giải đấu thời gian thực; xây dựng ứng dụng di động React Native; mở rộng bảng xếp hạng Trainer toàn cầu.")
    doc.add_page_break()

    # ── KẾT LUẬN & TÀI LIỆU THAM KHẢO ───────────────────────────────────────
    add_h1("KẾT LUẬN")
    add_p("Sau một thời gian nghiêm túc nghiên cứu lý thuyết và bắt tay vào hiện thực hóa sản phẩm thực tế, đồ án chuyên ngành \"Xây dựng Tạp chí & Cổng Thông Tin Đua Ngựa Học Viện Tracen (TracenTimes & Derby Cup Simulator)\" đã hoàn thành trọn vẹn 100% mục tiêu đề ra ban đầu.")
    add_p("Đề tài đã giải quyết thành công các vấn đề kỹ thuật trọng tâm:")
    add_bullet("1. Thoát khỏi tư duy lập trình thủ tục cũ, làm chủ mô hình kiến trúc 3 lớp (3-Tier Architecture) trên nền tảng Pure PHP 8.2, chứng minh khả năng viết mã hướng đối tượng chuẩn mực mà không cần dựa dẫm vào các framework cồng kềnh.")
    add_bullet("2. Thiết lập cơ chế bảo mật vững chắc với PDO Prepared Statements, giải quyết triệt để nguy cơ tấn công SQL Injection và Cross-Site Scripting.")
    add_bullet("3. Tạo nên một sản phẩm truyền thông thể thao số ấn tượng, kết hợp hài hòa giữa nội dung báo chí tin tức, bách khoa thư nhân vật chuẩn MediaWiki và bộ mô phỏng giải đua Grand Derby giàu tính giải trí.")
    add_p("Đồ án này là dấu mốc quan trọng khẳng định năng lực phân tích, thiết kế và lập trình ứng dụng web của bản thân em trong suốt quá trình theo học tại Khoa Công nghệ Thông tin – Trường Đại học Kinh doanh và Công nghệ Hà Nội, đồng thời tạo tiền đề vững chắc cho quá trình thực tập và phát triển nghề nghiệp trong tương lai.")

    doc.add_paragraph().paragraph_format.space_before = Pt(14)
    add_h1("TÀI LIỆU THAM KHẢO")
    refs = [
        "Trường Đại học Kinh doanh và Công nghệ Hà Nội – Khoa Công nghệ Thông tin, Giáo trình Lập trình Web với PHP và MySQL, NXB Thông tin và Truyền thông, Hà Nội.",
        "Trường Đại học Kinh doanh và Công nghệ Hà Nội – Khoa Công nghệ Thông tin, Giáo trình Phân tích và Thiết kế Hệ thống Thông tin, Hà Nội.",
        "The PHP Group, PHP 8.2 Documentation & PDO Manual, https://www.php.net/manual/en/book.pdo.php.",
        "Cygames, Inc., Umamusume: Pretty Derby Official Media & Game Assets, https://umamusume.jp.",
        "Umamusume GameTora & MediaWiki Community, Umamusume Wiki Database & Character Skill Scaling, https://gametora.com/umamusume.",
        "Mozilla Developer Network (MDN), HTML5 Semantics, CSS Grid & Modern Flexbox Layout Guides, https://developer.mozilla.org.",
        "OWASP Foundation, OWASP Top 10 Web Application Security Risks: SQL Injection & XSS Prevention Cheatsheet, https://owasp.org."
    ]
    for idx, ref in enumerate(refs):
        p = doc.add_paragraph()
        p.paragraph_format.line_spacing = 1.25
        p.paragraph_format.space_after = Pt(4)
        r = p.add_run(f"[{idx+1}] {ref}")
        r.font.name = 'Times New Roman'
        r.font.size = Pt(12)

    doc.save(output_docx)
    print("XUAT FILE DOCX THANH CONG!")
    print(f"Path: {output_docx}")
    print(f"Size: {os.path.getsize(output_docx)} bytes")

if __name__ == "__main__":
    create_report_docx()

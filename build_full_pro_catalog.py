import os
from PIL import Image, ImageDraw, ImageFont, ImageFilter

OUTPUT_DIR = "storage/app/public/products"
os.makedirs(OUTPUT_DIR, exist_ok=True)

try:
    FONT_HERO = ImageFont.truetype("arialbd.ttf", 36)
    FONT_BRAND = ImageFont.truetype("arialbd.ttf", 28)
    FONT_TITLE = ImageFont.truetype("arialbd.ttf", 22)
    FONT_SUB = ImageFont.truetype("arialbd.ttf", 15)
    FONT_TEXT = ImageFont.truetype("arial.ttf", 14)
    FONT_MONO = ImageFont.truetype("courbd.ttf", 13)
    FONT_BADGE = ImageFont.truetype("arialbd.ttf", 12)
except:
    FONT_HERO = FONT_BRAND = FONT_TITLE = FONT_SUB = FONT_TEXT = FONT_MONO = FONT_BADGE = ImageFont.load_default()

def create_studio_canvas():
    size = (800, 800)
    canvas = Image.new('RGB', size, (255, 255, 255))
    bg = Image.new('RGB', size, (250, 251, 253))
    bdraw = ImageDraw.Draw(bg)
    bdraw.rectangle([(0, 560), (800, 800)], fill=(244, 246, 249))
    canvas.paste(bg, (0, 0))
    return canvas

def apply_shadows(canvas, shadow_ellipses):
    shadow_layer = Image.new('RGBA', canvas.size, (0, 0, 0, 0))
    sdraw = ImageDraw.Draw(shadow_layer)
    for coords, opacity in shadow_ellipses:
        sdraw.ellipse(coords, fill=(0, 0, 0, opacity))
    shadow_layer = shadow_layer.filter(ImageFilter.GaussianBlur(15))
    canvas.paste(shadow_layer, (0, 0), shadow_layer)

def apply_badges(canvas, pack_text, claim_text, theme_color=(15, 23, 42)):
    overlay = Image.new('RGBA', canvas.size, (0, 0, 0, 0))
    odraw = ImageDraw.Draw(overlay)
    
    pill_w = max(310, int(len(pack_text) * 11) + 45)
    odraw.rounded_rectangle([(30, 30), (pill_w, 85)], radius=12, fill=theme_color + (240,), outline=(255, 255, 255), width=2)
    odraw.text((45, 38), "WHOLESALE CASE", fill=(255, 255, 255), font=FONT_TITLE)
    odraw.text((47, 64), pack_text.upper(), fill=(255, 255, 255), font=FONT_SUB)
    
    odraw.rounded_rectangle([(550, 715), (770, 765)], radius=25, fill=(15, 23, 42, 235), outline=theme_color + (200,), width=2)
    odraw.text((570, 723), "100% FACTORY SEALED", fill=(255, 255, 255), font=FONT_BADGE)
    odraw.text((585, 742), claim_text.upper(), fill=(203, 213, 225), font=FONT_BADGE)
    
    canvas.paste(overlay, (0, 0), overlay)

def draw_halal_stamp(draw, x, y):
    draw.ellipse([(x, y), (x + 50, y + 50)], fill=(255, 255, 255), outline=(22, 163, 74), width=2)
    draw.text((x + 7, y + 18), "HALAL", fill=(22, 163, 74), font=FONT_BADGE)

# --- CATEGORY BUILDERS ---

# Product 6: DUTCH LADY FULL CREAM UHT 24*200ML
def gen_product_6():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(160, 680), (640, 750)], 60), ([(240, 690), (440, 740)], 90), ([(380, 690), (580, 740)], 90)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def pack(x, y, w, h, title):
        draw.rounded_rectangle([(x, y), (x + w, y + h)], radius=14, fill=(2, 132, 199))
        draw.polygon([(x, y + 30), (x + 30, y), (x + w - 30, y), (x + w, y + 30)], fill=(3, 105, 161))
        draw.ellipse([(x + w//2 - 25, y + 6), (x + w//2 + 25, y + 26)], fill=(255, 255, 255), outline=(186, 230, 253), width=2)
        draw.ellipse([(x + 30, y + 55), (x + w - 30, y + 175)], fill=(255, 255, 255))
        draw.ellipse([(x + 36, y + 61), (x + w - 36, y + 169)], outline=(2, 132, 199), width=3)
        draw.text((x + w//2 - 48, y + 95), "DUTCH", fill=(2, 132, 199), font=FONT_BRAND)
        draw.text((x + w//2 - 38, y + 125), "LADY", fill=(2, 132, 199), font=FONT_BRAND)
        draw.rectangle([(x, y + 240), (x + w, y + h)], fill=(255, 255, 255))
        draw.ellipse([(x - 20, y + 215), (x + w + 20, y + 270)], fill=(255, 255, 255))
        draw.text((x + 24, y + 280), title, fill=(2, 132, 199), font=FONT_TITLE)
        draw.text((x + 24, y + 312), "Pure Fresh UHT Milk", fill=(100, 116, 139), font=FONT_SUB)
        draw.text((x + 24, y + 342), "200ml Tetra Pak Carton", fill=(2, 132, 199), font=FONT_BADGE)
        draw_halal_stamp(draw, x + w - 65, y + 330)
    pack(210, 230, 220, 440, "FULL CREAM")
    pack(380, 190, 230, 480, "FULL CREAM")
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "24 x 200ML TETRA PAK", "FACTORY SEALED CARTON", (2, 132, 199))
    canvas.save(f"{OUTPUT_DIR}/product-6.png", 'PNG')

# Product 7: DUTCH LADY CHOCOLATE UHT 12*1L
def gen_product_7():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(160, 680), (640, 750)], 60), ([(240, 690), (440, 740)], 90), ([(380, 690), (580, 740)], 90)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def pack(x, y, w, h):
        draw.rounded_rectangle([(x, y), (x + w, y + h)], radius=14, fill=(120, 53, 15))
        draw.polygon([(x, y + 30), (x + 30, y), (x + w - 30, y), (x + w, y + 30)], fill=(69, 26, 3))
        draw.ellipse([(x + w//2 - 25, y + 6), (x + w//2 + 25, y + 26)], fill=(255, 255, 255), outline=(254, 215, 170), width=2)
        draw.ellipse([(x + 30, y + 55), (x + w - 30, y + 175)], fill=(255, 255, 255))
        draw.ellipse([(x + 36, y + 61), (x + w - 36, y + 169)], outline=(2, 132, 199), width=3)
        draw.text((x + w//2 - 48, y + 95), "DUTCH", fill=(2, 132, 199), font=FONT_BRAND)
        draw.text((x + w//2 - 38, y + 125), "LADY", fill=(2, 132, 199), font=FONT_BRAND)
        draw.rectangle([(x, y + 240), (x + w, y + h)], fill=(254, 243, 199))
        draw.ellipse([(x - 20, y + 215), (x + w + 20, y + 270)], fill=(254, 243, 199))
        draw.text((x + 24, y + 280), "CHOCOLATE", fill=(120, 53, 15), font=FONT_TITLE)
        draw.text((x + 24, y + 312), "Rich Cocoa Malt UHT", fill=(100, 116, 139), font=FONT_SUB)
        draw.text((x + 24, y + 342), "1.0 Litre Family Pack", fill=(120, 53, 15), font=FONT_BADGE)
        draw_halal_stamp(draw, x + w - 65, y + 330)
    pack(210, 200, 220, 480)
    pack(380, 160, 230, 520)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "12 x 1L TETRA BRIK CARTON", "RICH CHOCOLATE BULK PACK", (120, 53, 15))
    canvas.save(f"{OUTPUT_DIR}/product-7.png", 'PNG')

# Product 8: F&N SWEETENED CONDENSED MILK 48*500G
def gen_product_8():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 670), (660, 750)], 65), ([(200, 680), (600, 740)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def tin(x, y, w, h):
        draw.ellipse([(x, y), (x + w, y + 45)], fill=(226, 232, 240), outline=(148, 163, 184), width=3)
        draw.rectangle([(x, y + 22), (x + w, y + h)], fill=(30, 58, 138))
        draw.ellipse([(x, y + h - 22), (x + w, y + h + 22)], fill=(30, 58, 138), outline=(148, 163, 184), width=2)
        draw.rectangle([(x + 20, y + 22), (x + 45, y + h)], fill=(255, 255, 255, 50))
        draw.text((x + 35, y + 55), "F&N", fill=(250, 204, 21), font=FONT_HERO)
        draw.text((x + 35, y + 105), "SWEETENED CONDENSED", fill=(255, 255, 255), font=FONT_TITLE)
        draw.text((x + 35, y + 138), "SUSU PEKAT MANIS - 500G", fill=(191, 219, 254), font=FONT_SUB)
        draw_halal_stamp(draw, x + w - 65, y + 110)
    tin(160, 390, 250, 210)
    tin(390, 390, 250, 210)
    tin(275, 210, 250, 210)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "48 x 500G TIN CARTON", "PREMIUM TEA & COFFEE BLEND", (30, 58, 138))
    canvas.save(f"{OUTPUT_DIR}/product-8.png", 'PNG')

# Product 9: BURUH PURE COOKING OIL 4*5KG
def gen_product_9():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(160, 670), (640, 750)], 65), ([(220, 680), (450, 740)], 90), ([(380, 680), (610, 740)], 90)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def jug(x, y, w, h, brand, sub, cap_col, bg_col):
        draw.rounded_rectangle([(x + w//2 - 32, y), (x + w//2 + 32, y + 42)], radius=6, fill=cap_col)
        draw.rectangle([(x + w//2 - 24, y + 42), (x + w//2 + 24, y + 78)], fill=(245, 158, 11))
        draw.rounded_rectangle([(x, y + 78), (x + w, y + h)], radius=26, fill=(245, 158, 11))
        draw.rounded_rectangle([(x + 20, y + 90), (x + 50, y + h - 25)], radius=12, fill=(255, 255, 255, 80))
        draw.rounded_rectangle([(x + w - 28, y + 120), (x + w + 36, y + 320)], radius=18, fill=cap_col)
        draw.rounded_rectangle([(x + w - 8, y + 145), (x + w + 16, y + 295)], radius=12, fill=(255, 255, 255))
        ly = y + 160
        draw.rounded_rectangle([(x + 22, ly), (x + w - 22, ly + 210)], radius=14, fill=(255, 255, 255))
        draw.rectangle([(x + 22, ly), (x + w - 22, ly + 56)], fill=cap_col)
        draw.text((x + 38, ly + 10), brand, fill=(255, 255, 255), font=FONT_HERO)
        draw.text((x + 32, ly + 72), sub, fill=cap_col, font=FONT_TITLE)
        draw.text((x + 32, ly + 104), "MINYAK MASAK TULEN", fill=(71, 85, 105), font=FONT_SUB)
        draw.text((x + 32, ly + 135), "NET WEIGHT: 5.0 KG", fill=(15, 23, 42), font=FONT_BADGE)
        draw_halal_stamp(draw, x + w - 80, ly + 140)
    jug(170, 190, 270, 480, "BURUH", "PURE COOKING OIL", (220, 38, 38), (245, 158, 11))
    jug(340, 160, 290, 520, "BURUH", "PURE COOKING OIL", (220, 38, 38), (245, 158, 11))
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "4 x 5KG JUG CARTON", "100% PURE PALM OLEIN", (217, 119, 6))
    canvas.save(f"{OUTPUT_DIR}/product-9.png", 'PNG')

# Product 10: SERI MURNI COOKING OIL 4*5KG
def gen_product_10():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(160, 670), (640, 750)], 65), ([(220, 680), (450, 740)], 90), ([(380, 680), (610, 740)], 90)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def jug(x, y, w, h):
        draw.rounded_rectangle([(x + w//2 - 32, y), (x + w//2 + 32, y + 42)], radius=6, fill=(22, 163, 74))
        draw.rectangle([(x + w//2 - 24, y + 42), (x + w//2 + 24, y + 78)], fill=(245, 158, 11))
        draw.rounded_rectangle([(x, y + 78), (x + w, y + h)], radius=26, fill=(245, 158, 11))
        draw.rounded_rectangle([(x + 20, y + 90), (x + 50, y + h - 25)], radius=12, fill=(255, 255, 255, 80))
        draw.rounded_rectangle([(x + w - 28, y + 120), (x + w + 36, y + 320)], radius=18, fill=(22, 163, 74))
        draw.rounded_rectangle([(x + w - 8, y + 145), (x + w + 16, y + 295)], radius=12, fill=(255, 255, 255))
        ly = y + 160
        draw.rounded_rectangle([(x + 22, ly), (x + w - 22, ly + 210)], radius=14, fill=(255, 255, 255))
        draw.rectangle([(x + 22, ly), (x + w - 22, ly + 56)], fill=(22, 163, 74))
        draw.text((x + 34, ly + 10), "SERI MURNI", fill=(255, 255, 255), font=FONT_HERO)
        draw.text((x + 32, ly + 72), "DOUBLE FRACTIONATED", fill=(22, 163, 74), font=FONT_TITLE)
        draw.text((x + 32, ly + 104), "NATURAL VITAMIN E & D", fill=(71, 85, 105), font=FONT_SUB)
        draw.text((x + 32, ly + 135), "NET WEIGHT: 5.0 KG", fill=(15, 23, 42), font=FONT_BADGE)
        draw_halal_stamp(draw, x + w - 80, ly + 140)
    jug(170, 190, 270, 480)
    jug(340, 160, 290, 520)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "4 x 5KG JUG CARTON", "DOUBLE REFINED PURITY", (22, 163, 74))
    canvas.save(f"{OUTPUT_DIR}/product-10.png", 'PNG')

# Product 11: KNIFE BRAND BLENDED COOKING OIL 4*5KG
def gen_product_11():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(160, 670), (640, 750)], 65), ([(220, 680), (450, 740)], 90), ([(380, 680), (610, 740)], 90)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def jug(x, y, w, h):
        draw.rounded_rectangle([(x + w//2 - 32, y), (x + w//2 + 32, y + 42)], radius=6, fill=(234, 88, 12))
        draw.rectangle([(x + w//2 - 24, y + 42), (x + w//2 + 24, y + 78)], fill=(245, 158, 11))
        draw.rounded_rectangle([(x, y + 78), (x + w, y + h)], radius=26, fill=(245, 158, 11))
        draw.rounded_rectangle([(x + 20, y + 90), (x + 50, y + h - 25)], radius=12, fill=(255, 255, 255, 80))
        draw.rounded_rectangle([(x + w - 28, y + 120), (x + w + 36, y + 320)], radius=18, fill=(234, 88, 12))
        draw.rounded_rectangle([(x + w - 8, y + 145), (x + w + 16, y + 295)], radius=12, fill=(255, 255, 255))
        ly = y + 160
        draw.rounded_rectangle([(x + 22, ly), (x + w - 22, ly + 210)], radius=14, fill=(255, 255, 255))
        draw.rectangle([(x + 22, ly), (x + w - 22, ly + 56)], fill=(234, 88, 12))
        draw.text((x + 38, ly + 10), "KNIFE BRAND", fill=(255, 255, 255), font=FONT_HERO)
        draw.text((x + 32, ly + 72), "BLENDED COOKING OIL", fill=(234, 88, 12), font=FONT_TITLE)
        draw.text((x + 32, ly + 104), "RICH IN OMEGA 6 & 9", fill=(71, 85, 105), font=FONT_SUB)
        draw.text((x + 32, ly + 135), "NET WEIGHT: 5.0 KG", fill=(15, 23, 42), font=FONT_BADGE)
        draw_halal_stamp(draw, x + w - 80, ly + 140)
    jug(170, 190, 270, 480)
    jug(340, 160, 290, 520)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "4 x 5KG JUG CARTON", "HOUSEHOLD FAVORITE BLEND", (234, 88, 12))
    canvas.save(f"{OUTPUT_DIR}/product-11.png", 'PNG')

# Product 12: BURUH COOKING OIL 17KG TIN
def gen_product_12():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(160, 660), (640, 740)], 85), ([(220, 670), (580, 735)], 110)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    tx, ty, tw, th = 240, 190, 320, 480
    draw.rounded_rectangle([(tx, ty), (tx + tw, ty + th)], radius=16, fill=(203, 213, 225), outline=(148, 163, 184), width=3)
    draw.rounded_rectangle([(tx + 40, ty - 25), (tx + tw - 40, ty + 10)], radius=8, fill=(100, 116, 139))
    draw.rounded_rectangle([(tx + 70, ty - 18), (tx + tw - 70, ty)], radius=4, fill=(255, 255, 255))
    draw.ellipse([(tx + 45, ty + 15), (tx + 85, ty + 45)], fill=(220, 38, 38))
    draw.rectangle([(tx + 25, ty + 50), (tx + 55, ty + th - 20)], fill=(255, 255, 255, 90))
    ly = ty + 120
    draw.rounded_rectangle([(tx + 20, ly), (tx + tw - 20, ly + 280)], radius=12, fill=(255, 255, 255))
    draw.rectangle([(tx + 20, ly), (tx + tw - 20, ly + 80)], fill=(220, 38, 38))
    draw.text((tx + 55, ly + 18), "BURUH", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((tx + 35, ly + 95), "17KG COMMERCIAL TIN", fill=(185, 28, 28), font=FONT_TITLE)
    draw.text((tx + 35, ly + 135), "PURE PALM COOKING OIL", fill=(71, 85, 105), font=FONT_SUB)
    draw.text((tx + 35, ly + 170), "IDEAL FOR RESTAURANTS & CATERERS", fill=(15, 23, 42), font=FONT_BADGE)
    draw_halal_stamp(draw, tx + tw - 80, ly + 210)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "1 x 17KG COMMERCIAL TIN", "BULK CATERING SIZE", (220, 38, 38))
    canvas.save(f"{OUTPUT_DIR}/product-12.png", 'PNG')

# Product 13: MAGGI 2-MINUTE NOODLE CURRY 12*5*79G
def gen_product_13():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    draw.rounded_rectangle([(180, 210), (620, 660)], radius=24, fill=(250, 204, 21), outline=(234, 179, 8), width=3)
    draw.polygon([(220, 210), (420, 210), (400, 300), (200, 300)], fill=(220, 38, 38))
    draw.text((230, 225), "Maggi", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((220, 320), "2-MINIT MI KARI", fill=(185, 28, 28), font=FONT_HERO)
    draw.text((220, 375), "12 BUNDLES x 5 PACKS (60 PACKETS)", fill=(113, 63, 18), font=FONT_TITLE)
    draw.text((220, 410), "SAVORY AROMATIC CURRY TASTE", fill=(71, 85, 105), font=FONT_SUB)
    if os.path.exists('public/images/categories/rice-noodles-grains.png'):
        nb = Image.open('public/images/categories/rice-noodles-grains.png').convert('RGBA').resize((240, 240), Image.Resampling.LANCZOS)
        mask = Image.new('L', (240, 240), 0)
        ImageDraw.Draw(mask).ellipse([(0, 0), (240, 240)], fill=255)
        layer.paste(nb, (280, 440), mask)
        draw.ellipse([(280, 440), (520, 680)], outline=(220, 38, 38), width=4)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "60 PACKETS CARTON", "HOT & SPICY KARI SAVINGS", (234, 88, 12))
    canvas.save(f"{OUTPUT_DIR}/product-13.png", 'PNG')

# Product 14: MAGGI 2-MINUTE NOODLE CHICKEN 12*5*77G
def gen_product_14():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    draw.rounded_rectangle([(180, 210), (620, 660)], radius=24, fill=(22, 163, 74), outline=(21, 128, 61), width=3)
    draw.polygon([(220, 210), (420, 210), (400, 300), (200, 300)], fill=(220, 38, 38))
    draw.text((230, 225), "Maggi", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((220, 320), "2-MINIT MI AYAM", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((220, 375), "12 BUNDLES x 5 PACKS (60 PACKETS)", fill=(240, 253, 244), font=FONT_TITLE)
    draw.text((220, 410), "CLEAR ROASTED CHICKEN BROTH", fill=(187, 247, 208), font=FONT_SUB)
    if os.path.exists('public/images/categories/rice-noodles-grains.png'):
        nb = Image.open('public/images/categories/rice-noodles-grains.png').convert('RGBA').resize((240, 240), Image.Resampling.LANCZOS)
        mask = Image.new('L', (240, 240), 0)
        ImageDraw.Draw(mask).ellipse([(0, 0), (240, 240)], fill=255)
        layer.paste(nb, (280, 440), mask)
        draw.ellipse([(280, 440), (520, 680)], outline=(255, 255, 255), width=4)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "60 PACKETS CARTON", "HEARTY CHICKEN NOODLE BULK", (22, 163, 74))
    canvas.save(f"{OUTPUT_DIR}/product-14.png", 'PNG')

# Product 15: MAMEE CHEF LAKSA KARI 12*4*80G
def gen_product_15():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    draw.rounded_rectangle([(180, 210), (620, 660)], radius=24, fill=(88, 28, 135), outline=(107, 33, 168), width=3)
    draw.text((230, 240), "MAMEE CHEF", fill=(250, 204, 21), font=FONT_HERO)
    draw.text((230, 310), "LAKSA KARI", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((230, 365), "12 BUNDLES x 4 PACKS (48 PACKETS)", fill=(243, 232, 255), font=FONT_TITLE)
    draw.text((230, 400), "LA MIAN NOODLE TECHNOLOGY", fill=(216, 180, 254), font=FONT_SUB)
    if os.path.exists('public/images/categories/rice-noodles-grains.png'):
        nb = Image.open('public/images/categories/rice-noodles-grains.png').convert('RGBA').resize((240, 240), Image.Resampling.LANCZOS)
        mask = Image.new('L', (240, 240), 0)
        ImageDraw.Draw(mask).ellipse([(0, 0), (240, 240)], fill=255)
        layer.paste(nb, (280, 440), mask)
        draw.ellipse([(280, 440), (520, 680)], outline=(250, 204, 21), width=4)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "48 PACKETS CARTON", "GOURMET KARI LAKSA PACK", (88, 28, 135))
    canvas.save(f"{OUTPUT_DIR}/product-15.png", 'PNG')

# Product 16: JASMINE SUPER SPECIAL TEMPATAN 10KG (CARTON OF 3)
def gen_product_16():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 85), ([(200, 670), (600, 735)], 110)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    px, py, pw, ph = 210, 190, 380, 490
    draw.rounded_rectangle([(px, py), (px + pw, py + ph)], radius=32, fill=(254, 252, 232), outline=(234, 179, 8), width=3)
    draw.rectangle([(px, py + 120), (px + pw, py + 140)], fill=(220, 38, 38))
    draw.rectangle([(px, py + 145), (px + pw, py + 160)], fill=(22, 163, 74))
    for sx in range(px + 20, px + pw - 20, 14):
        draw.line([(sx, py + 16), (sx + 8, py + 24)], fill=(120, 53, 15), width=3)
    draw.text((px + 50, py + 45), "JASMINE", fill=(185, 28, 28), font=FONT_HERO)
    draw.text((px + 40, py + 180), "SUPER SPECIAL TEMPATAN", fill=(15, 23, 42), font=FONT_TITLE)
    draw.text((px + 40, py + 215), "BERAS PUTIH TEMPATAN 5% HANCUR", fill=(71, 85, 105), font=FONT_SUB)
    draw.text((px + 40, py + 250), "3 BAGS x 10KG (30KG WHOLESALE)", fill=(22, 163, 74), font=FONT_TITLE)
    draw.text((px + 40, py + 290), "GRED BERAS SUPER SPESIAL MALAYSIA", fill=(185, 28, 28), font=FONT_SUB)
    draw_halal_stamp(draw, px + pw - 80, py + 360)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "3 x 10KG BAGS (30KG BUNDLE)", "MALAYSIAN HARVEST GUARANTEE", (234, 179, 8))
    canvas.save(f"{OUTPUT_DIR}/product-16.png", 'PNG')

# Product 17: AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G
def gen_product_17():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 670), (660, 750)], 65), ([(200, 680), (600, 740)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def can(x, y, w, h):
        draw.ellipse([(x, y), (x + w, y + 45)], fill=(226, 232, 240), outline=(148, 163, 184), width=3)
        draw.rectangle([(x, y + 22), (x + w, y + h)], fill=(220, 38, 38))
        draw.ellipse([(x, y + h - 22), (x + w, y + h + 22)], fill=(220, 38, 38), outline=(148, 163, 184), width=2)
        draw.rectangle([(x + 20, y + 22), (x + 45, y + h)], fill=(255, 255, 255, 60))
        draw.rectangle([(x + 20, y + 40), (x + w - 20, y + 80)], fill=(250, 204, 21))
        draw.text((x + 35, y + 45), "AYAM BRAND", fill=(185, 28, 28), font=FONT_HERO)
        draw.text((x + 35, y + 95), "SARDINES IN TOMATO", fill=(255, 255, 255), font=FONT_TITLE)
        draw.text((x + 35, y + 130), "425G BIG CAN • WILD CAUGHT", fill=(254, 240, 138), font=FONT_SUB)
        draw_halal_stamp(draw, x + w - 65, y + 105)
    can(160, 390, 250, 210)
    can(390, 390, 250, 210)
    can(275, 210, 250, 210)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "24 x 425G BIG TIN CARTON", "OMEGA-3 WILD SARDINES", (220, 38, 38))
    canvas.save(f"{OUTPUT_DIR}/product-17.png", 'PNG')

# Product 18: AYAM BRAND TUNA FLAKES IN OIL 24*150G
def gen_product_18():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 670), (660, 750)], 65), ([(200, 680), (600, 740)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def can(x, y, w, h):
        draw.ellipse([(x, y), (x + w, y + 45)], fill=(226, 232, 240), outline=(148, 163, 184), width=3)
        draw.rectangle([(x, y + 22), (x + w, y + h)], fill=(2, 132, 199))
        draw.ellipse([(x, y + h - 22), (x + w, y + h + 22)], fill=(2, 132, 199), outline=(148, 163, 184), width=2)
        draw.rectangle([(x + 20, y + 22), (x + 45, y + h)], fill=(255, 255, 255, 60))
        draw.rectangle([(x + 20, y + 40), (x + w - 20, y + 80)], fill=(250, 204, 21))
        draw.text((x + 35, y + 45), "AYAM BRAND", fill=(185, 28, 28), font=FONT_HERO)
        draw.text((x + 35, y + 95), "TUNA FLAKES IN OIL", fill=(255, 255, 255), font=FONT_TITLE)
        draw.text((x + 35, y + 130), "150G EASY OPEN TIN", fill=(224, 242, 254), font=FONT_SUB)
        draw_halal_stamp(draw, x + w - 65, y + 105)
    can(160, 390, 250, 210)
    can(390, 390, 250, 210)
    can(275, 210, 250, 210)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "24 x 150G TIN CARTON", "HIGH PROTEIN TUNA BULK", (2, 132, 199))
    canvas.save(f"{OUTPUT_DIR}/product-18.png", 'PNG')

# Product 19: AYAM BRAND BAKED BEANS 24*425G
def gen_product_19():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 670), (660, 750)], 65), ([(200, 680), (600, 740)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def can(x, y, w, h):
        draw.ellipse([(x, y), (x + w, y + 45)], fill=(226, 232, 240), outline=(148, 163, 184), width=3)
        draw.rectangle([(x, y + 22), (x + w, y + h)], fill=(234, 88, 12))
        draw.ellipse([(x, y + h - 22), (x + w, y + h + 22)], fill=(234, 88, 12), outline=(148, 163, 184), width=2)
        draw.rectangle([(x + 20, y + 22), (x + 45, y + h)], fill=(255, 255, 255, 60))
        draw.rectangle([(x + 20, y + 40), (x + w - 20, y + 80)], fill=(250, 204, 21))
        draw.text((x + 35, y + 45), "AYAM BRAND", fill=(185, 28, 28), font=FONT_HERO)
        draw.text((x + 35, y + 95), "BAKED BEANS IN TOMATO", fill=(255, 255, 255), font=FONT_TITLE)
        draw.text((x + 35, y + 130), "425G WHOLE BEANS CARTON", fill=(254, 240, 138), font=FONT_SUB)
        draw_halal_stamp(draw, x + w - 65, y + 105)
    can(160, 390, 250, 210)
    can(390, 390, 250, 210)
    can(275, 210, 250, 210)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "24 x 425G TIN CARTON", "RICH TOMATO SAUCE RECIPE", (234, 88, 12))
    canvas.save(f"{OUTPUT_DIR}/product-19.png", 'PNG')

# Product 20: MAGGI TOMATO KETCHUP 12*475G
def gen_product_20():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 670), (660, 750)], 65), ([(200, 680), (600, 740)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def bottle(x, y, w, h):
        draw.rounded_rectangle([(x + w//2 - 20, y), (x + w//2 + 20, y + 35)], radius=4, fill=(220, 38, 38))
        draw.rectangle([(x + w//2 - 16, y + 35), (x + w//2 + 16, y + 75)], fill=(185, 28, 28))
        draw.rounded_rectangle([(x, y + 75), (x + w, y + h)], radius=20, fill=(185, 28, 28))
        draw.rounded_rectangle([(x + 12, y + 85), (x + 28, y + h - 15)], radius=8, fill=(255, 255, 255, 70))
        ly = y + 130
        draw.rounded_rectangle([(x + 10, ly), (x + w - 10, ly + 140)], radius=8, fill=(255, 255, 255))
        draw.rectangle([(x + 10, ly), (x + w - 10, ly + 40)], fill=(250, 204, 21))
        draw.text((x + 15, ly + 8), "Maggi", fill=(185, 28, 28), font=FONT_TITLE)
        draw.text((x + 15, ly + 50), "TOMATO", fill=(185, 28, 28), font=FONT_TITLE)
        draw.text((x + 15, ly + 78), "KETCHUP", fill=(185, 28, 28), font=FONT_SUB)
        draw.text((x + 15, ly + 105), "475g Glass", fill=(71, 85, 105), font=FONT_BADGE)
    bottle(210, 210, 160, 460)
    bottle(430, 210, 160, 460)
    bottle(320, 170, 160, 500)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "12 x 475G BOTTLE CARTON", "VINTAGE TOMATO RECIPE", (185, 28, 28))
    canvas.save(f"{OUTPUT_DIR}/product-20.png", 'PNG')

# Product 21: JULIE'S PEANUT BUTTER SANDWICH 24*360G
def gen_product_21():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    draw.rounded_rectangle([(180, 210), (620, 660)], radius=24, fill=(250, 204, 21), outline=(217, 119, 6), width=3)
    draw.text((230, 245), "Julie's", fill=(220, 38, 38), font=FONT_HERO)
    draw.text((230, 315), "PEANUT BUTTER", fill=(120, 53, 15), font=FONT_HERO)
    draw.text((230, 370), "SANDWICH BISCUITS", fill=(120, 53, 15), font=FONT_TITLE)
    draw.text((230, 405), "24 PACKETS x 360G WHOLESALE CARTON", fill=(71, 85, 105), font=FONT_SUB)
    draw.ellipse([(280, 450), (420, 590)], fill=(245, 158, 11), outline=(217, 119, 6), width=4)
    draw.ellipse([(380, 470), (520, 610)], fill=(245, 158, 11), outline=(217, 119, 6), width=4)
    draw.text((310, 505), "CRISPY", fill=(255, 255, 255), font=FONT_TITLE)
    draw.text((410, 525), "CREAMY", fill=(255, 255, 255), font=FONT_TITLE)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "24 PACKETS x 360G CARTON", "BAKED GOLDEN GOODNESS", (217, 119, 6))
    canvas.save(f"{OUTPUT_DIR}/product-21.png", 'PNG')

# Product 22: JULIE'S CHEESE SANDWICH 24*336G
def gen_product_22():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    draw.rounded_rectangle([(180, 210), (620, 660)], radius=24, fill=(2, 132, 199), outline=(3, 105, 161), width=3)
    draw.text((230, 245), "Julie's", fill=(220, 38, 38), font=FONT_HERO)
    draw.text((230, 315), "CHEESE SANDWICH", fill=(254, 240, 138), font=FONT_HERO)
    draw.text((230, 370), "RICH CHEDDAR BISCUITS", fill=(255, 255, 255), font=FONT_TITLE)
    draw.text((230, 405), "24 PACKETS x 336G WHOLESALE CARTON", fill=(224, 242, 254), font=FONT_SUB)
    draw.ellipse([(280, 450), (420, 590)], fill=(250, 204, 21), outline=(234, 179, 8), width=4)
    draw.ellipse([(380, 470), (520, 610)], fill=(250, 204, 21), outline=(234, 179, 8), width=4)
    draw.text((305, 505), "CHEDDAR", fill=(120, 53, 15), font=FONT_SUB)
    draw.text((410, 525), "CHEESE", fill=(120, 53, 15), font=FONT_SUB)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "24 PACKETS x 336G CARTON", "SAVORY CHEDDAR CHEESE", (2, 132, 199))
    canvas.save(f"{OUTPUT_DIR}/product-22.png", 'PNG')

# Product 23: OREO VANILLA COOKIES 24*119.6G
def gen_product_23():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 670), (660, 750)], 65), ([(200, 680), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    draw.rounded_rectangle([(180, 190), (620, 480)], radius=24, fill=(2, 132, 199), outline=(3, 105, 161), width=3)
    draw.text((240, 230), "OREO", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((240, 295), "VANILLA CREME SANDWICH", fill=(224, 242, 254), font=FONT_TITLE)
    draw.text((240, 335), "24 ROLLS WHOLESALE CASE CARTON", fill=(255, 255, 255), font=FONT_SUB)
    draw.text((240, 365), "TWIST, LICK, DUNK - SEALED BULK CASE", fill=(186, 230, 253), font=FONT_TEXT)
    if os.path.exists('assets_cache/oreo.png'):
        oreo_img = Image.open('assets_cache/oreo.png').convert('RGBA')
        ow, oh = oreo_img.size
        rw = 420
        rh = int(oh * rw / ow)
        layer.paste(oreo_img.resize((rw, rh), Image.Resampling.LANCZOS), (190, 390), oreo_img.resize((rw, rh), Image.Resampling.LANCZOS))
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "24 ROLLS WHOLESALE CASE", "ORIGINAL VANILLA CREME", (2, 132, 199))
    canvas.save(f"{OUTPUT_DIR}/product-23.png", 'PNG')

# Product 24: MUNCHY'S CRACKERS PLUS ORIGINAL 12*300G
def gen_product_24():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    draw.rounded_rectangle([(180, 210), (620, 660)], radius=24, fill=(220, 38, 38), outline=(185, 28, 28), width=3)
    draw.text((230, 245), "MUNCHY'S", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((230, 315), "CRACKERS PLUS", fill=(250, 204, 21), font=FONT_HERO)
    draw.text((230, 370), "ORIGINAL BUTTER CRACKERS", fill=(255, 255, 255), font=FONT_TITLE)
    draw.text((230, 405), "12 PACKS x 300G WHOLESALE CARTON", fill=(254, 226, 226), font=FONT_SUB)
    draw.rounded_rectangle([(270, 450), (410, 590)], radius=12, fill=(254, 240, 138))
    draw.rounded_rectangle([(390, 470), (530, 610)], radius=12, fill=(254, 240, 138))
    draw.text((290, 505), "CRISP", fill=(120, 53, 15), font=FONT_TITLE)
    draw.text((410, 525), "LAYERED", fill=(120, 53, 15), font=FONT_SUB)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "12 PACKS x 300G CARTON", "MELT-IN-MOUTH BUTTER CRACKERS", (220, 38, 38))
    canvas.save(f"{OUTPUT_DIR}/product-24.png", 'PNG')

# Product 25: NESCAFE CLASSIC 3IN1 ORIGINAL 10*25*18G
def gen_product_25():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    draw.rounded_rectangle([(180, 210), (620, 660)], radius=24, fill=(220, 38, 38), outline=(185, 28, 28), width=3)
    draw.text((230, 245), "NESCAFE", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((230, 315), "CLASSIC 3 IN 1", fill=(250, 204, 21), font=FONT_HERO)
    draw.text((230, 370), "ORIGINAL ROAST & BLEND", fill=(255, 255, 255), font=FONT_TITLE)
    draw.text((230, 405), "10 BAGS x 25 STICKS (250 STICKS CARTON)", fill=(254, 226, 226), font=FONT_SUB)
    draw.ellipse([(280, 450), (520, 640)], fill=(69, 26, 3))
    draw.ellipse([(310, 470), (490, 620)], fill=(120, 53, 15))
    draw.text((345, 525), "COFFEE", fill=(255, 255, 255), font=FONT_TITLE)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "250 STICKS BULK CARTON", "AROMATIC MORNING ENERGY", (220, 38, 38))
    canvas.save(f"{OUTPUT_DIR}/product-25.png", 'PNG')

# Product 26: NESCAFE GOLD BLEND SOLUBLE 6*200G
def gen_product_26():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(180, 670), (620, 745)], 75), ([(260, 680), (540, 735)], 105)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def jar(x, y, w, h):
        draw.rounded_rectangle([(x + 25, y), (x + w - 25, y + 55)], radius=8, fill=(234, 179, 8))
        draw.rectangle([(x + 35, y + 55), (x + w - 35, y + 80)], fill=(202, 138, 4))
        draw.rounded_rectangle([(x, y + 80), (x + w, y + h)], radius=24, fill=(45, 25, 15))
        draw.rectangle([(x + 18, y + 90), (x + 36, y + h - 20)], fill=(255, 255, 255, 60))
        ly = y + 140
        draw.rounded_rectangle([(x + 15, ly), (x + w - 15, ly + 220)], radius=12, fill=(255, 255, 255))
        draw.rectangle([(x + 15, ly), (x + w - 15, ly + 65)], fill=(234, 179, 8))
        draw.text((x + 22, ly + 14), "NESCAFE", fill=(15, 23, 42), font=FONT_TITLE)
        draw.text((x + 22, ly + 80), "GOLD BLEND", fill=(180, 83, 9), font=FONT_TITLE)
        draw.text((x + 22, ly + 115), "RICH & SMOOTH", fill=(71, 85, 105), font=FONT_SUB)
        draw.text((x + 22, ly + 145), "200g Glass Jar", fill=(15, 23, 42), font=FONT_BADGE)
        draw_halal_stamp(draw, x + w - 65, ly + 150)
    jar(210, 200, 200, 480)
    jar(390, 160, 210, 520)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "6 x 200G GLASS JARS CARTON", "PREMIUM ARABICA ROAST", (202, 138, 4))
    canvas.save(f"{OUTPUT_DIR}/product-26.png", 'PNG')

# Product 27: BOH CAMERON HIGHLANDS TEA BAGS 12*100'S
def gen_product_27():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    draw.rounded_rectangle([(180, 210), (620, 660)], radius=24, fill=(22, 101, 52), outline=(21, 128, 61), width=3)
    draw.text((230, 245), "BOH", fill=(250, 204, 21), font=FONT_HERO)
    draw.text((230, 315), "CAMERON HIGHLANDS", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((230, 370), "100% MALAYSIAN BLACK TEA", fill=(187, 247, 208), font=FONT_TITLE)
    draw.text((230, 405), "12 BOXES x 100 POTBAGS (1,200 BAGS CARTON)", fill=(240, 253, 244), font=FONT_SUB)
    draw.ellipse([(280, 460), (520, 630)], fill=(20, 83, 45))
    draw.text((345, 525), "TEABAGS", fill=(255, 255, 255), font=FONT_TITLE)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "1,200 BAGS WHOLESALE CARTON", "HIGHLAND FRESH BLACK TEA", (22, 101, 52))
    canvas.save(f"{OUTPUT_DIR}/product-27.png", 'PNG')

# Product 28: MILO ACTIV-GO REFILL PACK 12*1KG
def gen_product_28():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    draw.rounded_rectangle([(180, 200), (620, 660)], radius=24, fill=(22, 163, 74), outline=(21, 128, 61), width=3)
    draw.text((230, 240), "Nestle", fill=(255, 255, 255), font=FONT_TITLE)
    draw.text((230, 275), "MILO", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((230, 345), "ACTIV-GO REFILL PACK", fill=(250, 204, 21), font=FONT_HERO)
    draw.text((230, 405), "12 BAGS x 1KG (12KG WHOLESALE CASE)", fill=(240, 253, 244), font=FONT_TITLE)
    draw.text((230, 440), "CHOCOLATE MALT NOURISHING ENERGY", fill=(187, 247, 208), font=FONT_SUB)
    draw.ellipse([(290, 480), (510, 640)], fill=(120, 53, 15))
    draw.text((350, 545), "1.0 KG", fill=(255, 255, 255), font=FONT_TITLE)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "12 x 1KG BAGS (12KG CASE)", "ICONIC CHOCOLATE MALT", (22, 163, 74))
    canvas.save(f"{OUTPUT_DIR}/product-28.png", 'PNG')

# Product 29: BREEZE LIQUID POWER CLEAN DETERGENT 4*3.6KG
def gen_product_29():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(160, 670), (640, 750)], 65), ([(220, 680), (450, 740)], 90), ([(380, 680), (610, 740)], 90)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def jug(x, y, w, h):
        draw.rounded_rectangle([(x + w//2 - 32, y), (x + w//2 + 32, y + 42)], radius=6, fill=(2, 132, 199))
        draw.rectangle([(x + w//2 - 24, y + 42), (x + w//2 + 24, y + 78)], fill=(37, 99, 235))
        draw.rounded_rectangle([(x, y + 78), (x + w, y + h)], radius=26, fill=(37, 99, 235))
        draw.rounded_rectangle([(x + 20, y + 90), (x + 50, y + h - 25)], radius=12, fill=(255, 255, 255, 80))
        draw.rounded_rectangle([(x + w - 28, y + 120), (x + w + 36, y + 320)], radius=18, fill=(2, 132, 199))
        draw.rounded_rectangle([(x + w - 8, y + 145), (x + w + 16, y + 295)], radius=12, fill=(255, 255, 255))
        ly = y + 160
        draw.rounded_rectangle([(x + 22, ly), (x + w - 22, ly + 210)], radius=14, fill=(255, 255, 255))
        draw.rectangle([(x + 22, ly), (x + w - 22, ly + 56)], fill=(2, 132, 199))
        draw.text((x + 38, ly + 10), "BREEZE", fill=(255, 255, 255), font=FONT_HERO)
        draw.text((x + 32, ly + 72), "POWER CLEAN LIQUID", fill=(2, 132, 199), font=FONT_TITLE)
        draw.text((x + 32, ly + 104), "TOUGH STAIN REMOVAL", fill=(71, 85, 105), font=FONT_SUB)
        draw.text((x + 32, ly + 135), "NET WEIGHT: 3.6 KG", fill=(15, 23, 42), font=FONT_BADGE)
    jug(170, 190, 270, 480)
    jug(340, 160, 290, 520)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "4 x 3.6KG BOTTLE CARTON", "POWER CLEAN LAUNDRY BULK", (37, 99, 235))
    canvas.save(f"{OUTPUT_DIR}/product-29.png", 'PNG')

# Product 30: DYNAMO POWER GEL REGULAR 4*2.6KG
def gen_product_30():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(160, 670), (640, 750)], 65), ([(220, 680), (450, 740)], 90), ([(380, 680), (610, 740)], 90)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def jug(x, y, w, h):
        draw.rounded_rectangle([(x + w//2 - 32, y), (x + w//2 + 32, y + 42)], radius=6, fill=(30, 58, 138))
        draw.rectangle([(x + w//2 - 24, y + 42), (x + w//2 + 24, y + 78)], fill=(29, 78, 216))
        draw.rounded_rectangle([(x, y + 78), (x + w, y + h)], radius=26, fill=(29, 78, 216))
        draw.rounded_rectangle([(x + 20, y + 90), (x + 50, y + h - 25)], radius=12, fill=(255, 255, 255, 80))
        draw.rounded_rectangle([(x + w - 28, y + 120), (x + w + 36, y + 320)], radius=18, fill=(30, 58, 138))
        draw.rounded_rectangle([(x + w - 8, y + 145), (x + w + 16, y + 295)], radius=12, fill=(255, 255, 255))
        ly = y + 160
        draw.rounded_rectangle([(x + 22, ly), (x + w - 22, ly + 210)], radius=14, fill=(255, 255, 255))
        draw.rectangle([(x + 22, ly), (x + w - 22, ly + 56)], fill=(30, 58, 138))
        draw.text((x + 38, ly + 10), "DYNAMO", fill=(255, 255, 255), font=FONT_HERO)
        draw.text((x + 32, ly + 72), "POWER GEL REGULAR", fill=(30, 58, 138), font=FONT_TITLE)
        draw.text((x + 32, ly + 104), "2X BETTER STAIN REMOVAL", fill=(71, 85, 105), font=FONT_SUB)
        draw.text((x + 32, ly + 135), "NET WEIGHT: 2.6 KG", fill=(15, 23, 42), font=FONT_BADGE)
    jug(170, 190, 270, 480)
    jug(340, 160, 290, 520)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "4 x 2.6KG BOTTLE CARTON", "CONCENTRATED POWER GEL", (29, 78, 216))
    canvas.save(f"{OUTPUT_DIR}/product-30.png", 'PNG')

# Product 31: BREEZE COLOUR CARE DETERGENT POWDER 4*4.4KG
def gen_product_31():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    draw.rounded_rectangle([(180, 210), (620, 660)], radius=24, fill=(219, 39, 119), outline=(190, 24, 93), width=3)
    draw.text((230, 245), "BREEZE", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((230, 315), "COLOUR CARE POWDER", fill=(253, 224, 71), font=FONT_HERO)
    draw.text((230, 370), "KEEPS COLOURS BRIGHT & VIBRANT", fill=(255, 255, 255), font=FONT_TITLE)
    draw.text((230, 405), "4 SACKS x 4.4KG (17.6KG CARTON)", fill=(253, 242, 248), font=FONT_SUB)
    draw.ellipse([(280, 460), (520, 630)], fill=(190, 24, 93))
    draw.text((345, 525), "4.4 KG", fill=(255, 255, 255), font=FONT_TITLE)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "4 x 4.4KG SACK CARTON", "COLOUR PROTECTION DETERGENT", (219, 39, 119))
    canvas.save(f"{OUTPUT_DIR}/product-31.png", 'PNG')

# Product 32: DOWNY FABRIC SOFTENER SUNRISE FRESH 4*3.8L
def gen_product_32():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(160, 670), (640, 750)], 65), ([(220, 680), (450, 740)], 90), ([(380, 680), (610, 740)], 90)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def jug(x, y, w, h):
        draw.rounded_rectangle([(x + w//2 - 32, y), (x + w//2 + 32, y + 42)], radius=6, fill=(2, 132, 199))
        draw.rectangle([(x + w//2 - 24, y + 42), (x + w//2 + 24, y + 78)], fill=(14, 165, 233))
        draw.rounded_rectangle([(x, y + 78), (x + w, y + h)], radius=26, fill=(14, 165, 233))
        draw.rounded_rectangle([(x + 20, y + 90), (x + 50, y + h - 25)], radius=12, fill=(255, 255, 255, 80))
        draw.rounded_rectangle([(x + w - 28, y + 120), (x + w + 36, y + 320)], radius=18, fill=(2, 132, 199))
        draw.rounded_rectangle([(x + w - 8, y + 145), (x + w + 16, y + 295)], radius=12, fill=(255, 255, 255))
        ly = y + 160
        draw.rounded_rectangle([(x + 22, ly), (x + w - 22, ly + 210)], radius=14, fill=(255, 255, 255))
        draw.rectangle([(x + 22, ly), (x + w - 22, ly + 56)], fill=(2, 132, 199))
        draw.text((x + 38, ly + 10), "DOWNY", fill=(255, 255, 255), font=FONT_HERO)
        draw.text((x + 32, ly + 72), "SUNRISE FRESH", fill=(2, 132, 199), font=FONT_TITLE)
        draw.text((x + 32, ly + 104), "FABRIC SOFTENER & CONDITIONER", fill=(71, 85, 105), font=FONT_SUB)
        draw.text((x + 32, ly + 135), "NET VOLUME: 3.8 LITRES", fill=(15, 23, 42), font=FONT_BADGE)
    jug(170, 190, 270, 480)
    jug(340, 160, 290, 520)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "4 x 3.8L GALLON CARTON", "ALL-DAY SUNRISE FRESHNESS", (14, 165, 233))
    canvas.save(f"{OUTPUT_DIR}/product-32.png", 'PNG')

# Product 33: PREMIER FACIAL TISSUE 2-PLY 48*200'S
def gen_product_33():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def box(x, y, w, h, col):
        draw.rounded_rectangle([(x, y), (x + w, y + h)], radius=16, fill=col, outline=(255, 255, 255), width=2)
        draw.ellipse([(x + w//2 - 60, y + 25), (x + w//2 + 60, y + 65)], fill=(255, 255, 255), outline=(226, 232, 240), width=2)
        draw.polygon([(x + w//2 - 40, y + 35), (x + w//2, y - 25), (x + w//2 + 40, y + 35)], fill=(255, 255, 255))
        draw.text((x + 35, y + 75), "PREMIER", fill=(255, 255, 255), font=FONT_HERO)
        draw.text((x + 35, y + 120), "2-PLY FACIAL TISSUE", fill=(241, 245, 249), font=FONT_TITLE)
        draw.text((x + 35, y + 150), "200 SHEETS PER BOX", fill=(226, 232, 240), font=FONT_SUB)
    box(180, 420, 440, 200, (14, 165, 233))
    box(210, 230, 380, 190, (3, 105, 161))
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "48 BOXES WHOLESALE CASE", "100% VIRGIN PULP SOFTNESS", (14, 165, 233))
    canvas.save(f"{OUTPUT_DIR}/product-33.png", 'PNG')

# Product 34: PREMIER KITCHEN TOWEL 3-PLY 12*6 ROLLS
def gen_product_34():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    rx, ry, rw, rh = 210, 200, 380, 480
    draw.rounded_rectangle([(rx, ry), (rx + rw, ry + rh)], radius=24, fill=(248, 250, 252), outline=(203, 213, 225), width=3)
    draw.rectangle([(rx, ry + 80), (rx + rw, ry + 160)], fill=(22, 163, 74))
    draw.text((rx + 35, ry + 95), "PREMIER", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((rx + 35, ry + 180), "3-PLY KITCHEN TOWEL", fill=(15, 23, 42), font=FONT_HERO)
    draw.text((rx + 35, ry + 230), "SUPER ABSORBENT EMBOSSED WEAVE", fill=(22, 163, 74), font=FONT_TITLE)
    draw.text((rx + 35, ry + 270), "12 PACKS x 6 ROLLS (72 ROLLS CARTON)", fill=(71, 85, 105), font=FONT_SUB)
    draw.text((rx + 35, ry + 310), "FOOD SAFE & OIL ABSORBENT", fill=(15, 23, 42), font=FONT_BADGE)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "72 ROLLS WHOLESALE CASE", "HIGH ABSORPTION 3-PLY", (22, 163, 74))
    canvas.save(f"{OUTPUT_DIR}/product-34.png", 'PNG')

# Product 35: KLEENEX ULTRA SOFT TOILET TISSUE 4*20 ROLLS
def gen_product_35():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    rx, ry, rw, rh = 210, 200, 380, 480
    draw.rounded_rectangle([(rx, ry), (rx + rw, ry + rh)], radius=24, fill=(240, 249, 255), outline=(186, 230, 253), width=3)
    draw.rectangle([(rx, ry + 80), (rx + rw, ry + 160)], fill=(2, 132, 199))
    draw.text((rx + 35, ry + 95), "KLEENEX", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((rx + 35, ry + 180), "ULTRA SOFT BATH TISSUE", fill=(15, 23, 42), font=FONT_HERO)
    draw.text((rx + 35, ry + 230), "3-PLY CUSHIONY SOFT EMBOSSED", fill=(2, 132, 199), font=FONT_TITLE)
    draw.text((rx + 35, ry + 270), "4 BUNDLES x 20 ROLLS (80 ROLLS CARTON)", fill=(71, 85, 105), font=FONT_SUB)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "80 ROLLS WHOLESALE CASE", "ULTRA SOFT LUXURY TISSUE", (2, 132, 199))
    canvas.save(f"{OUTPUT_DIR}/product-35.png", 'PNG')

# Product 36: PREMIER COMPACT INTERFOLD HAND TOWEL 20*250'S
def gen_product_36():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(140, 660), (660, 740)], 65), ([(200, 670), (600, 735)], 95)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    rx, ry, rw, rh = 210, 200, 380, 480
    draw.rounded_rectangle([(rx, ry), (rx + rw, ry + rh)], radius=24, fill=(248, 250, 252), outline=(203, 213, 225), width=3)
    draw.rectangle([(rx, ry + 80), (rx + rw, ry + 160)], fill=(30, 58, 138))
    draw.text((rx + 35, ry + 95), "PREMIER", fill=(255, 255, 255), font=FONT_HERO)
    draw.text((rx + 35, ry + 180), "INTERFOLD HAND TOWEL", fill=(15, 23, 42), font=FONT_HERO)
    draw.text((rx + 35, ry + 230), "COMMERCIAL DISPENSER REFILL", fill=(30, 58, 138), font=FONT_TITLE)
    draw.text((rx + 35, ry + 270), "20 PACKS x 250 SHEETS (5,000 SHEETS)", fill=(71, 85, 105), font=FONT_SUB)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "5,000 SHEETS WHOLESALE CASE", "COMMERCIAL DISPENSER PACK", (30, 58, 138))
    canvas.save(f"{OUTPUT_DIR}/product-36.png", 'PNG')

# Product 37: DETTOL ANTISEPTIC DISINFECTANT LIQUID 12*750ML
def gen_product_37():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(160, 670), (640, 750)], 65), ([(220, 680), (450, 740)], 90), ([(380, 680), (610, 740)], 90)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def bottle(x, y, w, h):
        draw.rounded_rectangle([(x + w//2 - 25, y), (x + w//2 + 25, y + 40)], radius=4, fill=(255, 255, 255), outline=(203, 213, 225), width=2)
        draw.rectangle([(x + w//2 - 18, y + 40), (x + w//2 + 18, y + 80)], fill=(180, 83, 9))
        draw.rounded_rectangle([(x, y + 80), (x + w, y + h)], radius=22, fill=(217, 119, 6))
        draw.rounded_rectangle([(x + 18, y + 90), (x + 36, y + h - 20)], radius=8, fill=(255, 255, 255, 75))
        ly = y + 140
        draw.rounded_rectangle([(x + 14, ly), (x + w - 14, ly + 220)], radius=12, fill=(255, 255, 255))
        draw.rectangle([(x + 14, ly), (x + w - 14, ly + 65)], fill=(22, 101, 52))
        draw.text((x + 24, ly + 14), "Dettol", fill=(255, 255, 255), font=FONT_HERO)
        draw.text((x + 20, ly + 80), "ANTISEPTIC DISINFECTANT", fill=(22, 101, 52), font=FONT_SUB)
        draw.text((x + 20, ly + 115), "KILLS 99.9% OF GERMS", fill=(220, 38, 38), font=FONT_SUB)
        draw.text((x + 20, ly + 145), "750ml Hospital Grade", fill=(15, 23, 42), font=FONT_BADGE)
    bottle(210, 200, 200, 480)
    bottle(390, 160, 210, 520)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "12 x 750ML BOTTLE CARTON", "HOSPITAL GRADE DISINFECTANT", (22, 101, 52))
    canvas.save(f"{OUTPUT_DIR}/product-37.png", 'PNG')

# Product 38: CLOROX REGULAR BLEACH DISINFECTANT 6*4L
def gen_product_38():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(160, 670), (640, 750)], 65), ([(220, 680), (450, 740)], 90), ([(380, 680), (610, 740)], 90)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def jug(x, y, w, h):
        draw.rounded_rectangle([(x + w//2 - 32, y), (x + w//2 + 32, y + 42)], radius=6, fill=(30, 58, 138))
        draw.rectangle([(x + w//2 - 24, y + 42), (x + w//2 + 24, y + 78)], fill=(241, 245, 249))
        draw.rounded_rectangle([(x, y + 78), (x + w, y + h)], radius=26, fill=(241, 245, 249), outline=(203, 213, 225), width=2)
        draw.rounded_rectangle([(x + w - 28, y + 120), (x + w + 36, y + 320)], radius=18, fill=(30, 58, 138))
        draw.rounded_rectangle([(x + w - 8, y + 145), (x + w + 16, y + 295)], radius=12, fill=(255, 255, 255))
        ly = y + 160
        draw.rounded_rectangle([(x + 22, ly), (x + w - 22, ly + 210)], radius=14, fill=(255, 255, 255), outline=(30, 58, 138), width=2)
        draw.rectangle([(x + 22, ly), (x + w - 22, ly + 56)], fill=(30, 58, 138))
        draw.text((x + 38, ly + 10), "CLOROX", fill=(255, 255, 255), font=FONT_HERO)
        draw.text((x + 32, ly + 72), "REGULAR BLEACH", fill=(30, 58, 138), font=FONT_TITLE)
        draw.text((x + 32, ly + 104), "WHITENS & DISINFECTS", fill=(71, 85, 105), font=FONT_SUB)
        draw.text((x + 32, ly + 135), "COMMERCIAL 4.0 LITRES", fill=(15, 23, 42), font=FONT_BADGE)
    jug(170, 190, 270, 480)
    jug(340, 160, 290, 520)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "6 x 4L GALLON CARTON", "POWERFUL DISINFECTANT BLEACH", (30, 58, 138))
    canvas.save(f"{OUTPUT_DIR}/product-38.png", 'PNG')

# Product 39: SUNLIGHT DISHWASHING LIQUID LIME 6*4L
def gen_product_39():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(160, 670), (640, 750)], 65), ([(220, 680), (450, 740)], 90), ([(380, 680), (610, 740)], 90)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def jug(x, y, w, h):
        draw.rounded_rectangle([(x + w//2 - 32, y), (x + w//2 + 32, y + 42)], radius=6, fill=(101, 163, 13))
        draw.rectangle([(x + w//2 - 24, y + 42), (x + w//2 + 24, y + 78)], fill=(132, 204, 22))
        draw.rounded_rectangle([(x, y + 78), (x + w, y + h)], radius=26, fill=(132, 204, 22))
        draw.rounded_rectangle([(x + 20, y + 90), (x + 50, y + h - 25)], radius=12, fill=(255, 255, 255, 80))
        draw.rounded_rectangle([(x + w - 28, y + 120), (x + w + 36, y + 320)], radius=18, fill=(101, 163, 13))
        draw.rounded_rectangle([(x + w - 8, y + 145), (x + w + 16, y + 295)], radius=12, fill=(255, 255, 255))
        ly = y + 160
        draw.rounded_rectangle([(x + 22, ly), (x + w - 22, ly + 210)], radius=14, fill=(255, 255, 255))
        draw.rectangle([(x + 22, ly), (x + w - 22, ly + 56)], fill=(234, 179, 8))
        draw.text((x + 38, ly + 10), "SUNLIGHT", fill=(185, 28, 28), font=FONT_HERO)
        draw.text((x + 32, ly + 72), "DISHWASHING LIME", fill=(101, 163, 13), font=FONT_TITLE)
        draw.text((x + 32, ly + 104), "100x FASTER DEGREASING", fill=(71, 85, 105), font=FONT_SUB)
        draw.text((x + 32, ly + 135), "4.0 LITRES COMMERCIAL JUG", fill=(15, 23, 42), font=FONT_BADGE)
    jug(170, 190, 270, 480)
    jug(340, 160, 290, 520)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "6 x 4L COMMERCIAL GALLON", "REAL LIME GREASE CUTTER", (101, 163, 13))
    canvas.save(f"{OUTPUT_DIR}/product-39.png", 'PNG')

# Product 40: MR MUSCLE MULTI-SURFACE FLOOR CLEANER 6*3.7L
def gen_product_40():
    canvas = create_studio_canvas()
    apply_shadows(canvas, [([(160, 670), (640, 750)], 65), ([(220, 680), (450, 740)], 90), ([(380, 680), (610, 740)], 90)])
    layer = Image.new('RGBA', (800, 800), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)
    def jug(x, y, w, h):
        draw.rounded_rectangle([(x + w//2 - 32, y), (x + w//2 + 32, y + 42)], radius=6, fill=(234, 88, 12))
        draw.rectangle([(x + w//2 - 24, y + 42), (x + w//2 + 24, y + 78)], fill=(249, 115, 22))
        draw.rounded_rectangle([(x, y + 78), (x + w, y + h)], radius=26, fill=(249, 115, 22))
        draw.rounded_rectangle([(x + 20, y + 90), (x + 50, y + h - 25)], radius=12, fill=(255, 255, 255, 80))
        draw.rounded_rectangle([(x + w - 28, y + 120), (x + w + 36, y + 320)], radius=18, fill=(234, 88, 12))
        draw.rounded_rectangle([(x + w - 8, y + 145), (x + w + 16, y + 295)], radius=12, fill=(255, 255, 255))
        ly = y + 160
        draw.rounded_rectangle([(x + 22, ly), (x + w - 22, ly + 210)], radius=14, fill=(255, 255, 255))
        draw.rectangle([(x + 22, ly), (x + w - 22, ly + 56)], fill=(234, 88, 12))
        draw.text((x + 38, ly + 10), "MR MUSCLE", fill=(255, 255, 255), font=FONT_HERO)
        draw.text((x + 32, ly + 72), "MULTI-SURFACE CLEANER", fill=(234, 88, 12), font=FONT_TITLE)
        draw.text((x + 32, ly + 104), "ANTIBACTERIAL FLORAL", fill=(71, 85, 105), font=FONT_SUB)
        draw.text((x + 32, ly + 135), "NET VOLUME: 3.7 LITRES", fill=(15, 23, 42), font=FONT_BADGE)
    jug(170, 190, 270, 480)
    jug(340, 160, 290, 520)
    canvas.paste(layer, (0, 0), layer)
    apply_badges(canvas, "6 x 3.7L JUG CARTON", "MULTI-SURFACE ANTIBACTERIAL", (234, 88, 12))
    canvas.save(f"{OUTPUT_DIR}/product-40.png", 'PNG')

def main():
    print("Generating full catalog packshots for Products 6 through 40...")
    generators = [
        (6, gen_product_6),
        (7, gen_product_7),
        (8, gen_product_8),
        (9, gen_product_9),
        (10, gen_product_10),
        (11, gen_product_11),
        (12, gen_product_12),
        (13, gen_product_13),
        (14, gen_product_14),
        (15, gen_product_15),
        (16, gen_product_16),
        (17, gen_product_17),
        (18, gen_product_18),
        (19, gen_product_19),
        (20, gen_product_20),
        (21, gen_product_21),
        (22, gen_product_22),
        (23, gen_product_23),
        (24, gen_product_24),
        (25, gen_product_25),
        (26, gen_product_26),
        (27, gen_product_27),
        (28, gen_product_28),
        (29, gen_product_29),
        (30, gen_product_30),
        (31, gen_product_31),
        (32, gen_product_32),
        (33, gen_product_33),
        (34, gen_product_34),
        (35, gen_product_35),
        (36, gen_product_36),
        (37, gen_product_37),
        (38, gen_product_38),
        (39, gen_product_39),
        (40, gen_product_40),
    ]
    for pid, fn in generators:
        fn()
        print(f"Generated product-{pid}.png")

    print("ALL 40 PRODUCTS PACKSHOTS GENERATED SUCCESSFULLY!")

if __name__ == '__main__':
    main()

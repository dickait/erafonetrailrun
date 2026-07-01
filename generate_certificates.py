import os
import csv
import io
import re
import pypdf
from reportlab.pdfgen import canvas
from reportlab.lib.colors import HexColor

# ==========================================
# CONFIGURATION / COORDINATES (IN POINTS)
# ==========================================
# PDF Page dimensions: Width: 851.52, Height: 595.276 (A4 Landscape approximate)
# Coordinates (0,0) starts at the bottom-left corner of the page.

CONFIG = {
    # Path settings
    "template_path": "public/certificate-template-eratrailrun-2026.pdf",
    "output_dir": "public/certificates",
    
    # 1. Participant Name
    "name": {
        "x": 426,
        "y": 260,
        "font_name": "Helvetica-Bold",
        "font_size": 32,
        "color": "#E5C158",      # Gold text color
        "alignment": "center",   # "center", "left", "right"
    },
    
    # 2. BIB Number
    "bib": {
        "x": 156,
        "y": 58,
        "font_name": "Helvetica-Bold",
        "font_size": 16,
        "color": "#FFFFFF",
        "alignment": "center",
    },
    
    # 3. Gender
    "gender": {
        "x": 302,
        "y": 58,
        "font_name": "Helvetica-Bold",
        "font_size": 16,
        "color": "#FFFFFF",
        "alignment": "center",
        "transform": lambda val: "FEMALE" if val.upper() == "F" else ("MALE" if val.upper() == "M" else val.upper()),
    },
    
    # 4. Finish Time
    "finish_time": {
        "x": 482,
        "y": 58,
        "font_name": "Helvetica-Bold",
        "font_size": 16,
        "color": "#FFFFFF",
        "alignment": "center",
    },
    
    # 5. Distance
    "distance": {
        "x": 667,
        "y": 64,
        "font_name": "Helvetica-Bold",
        "font_size": 16,
        "color": "#FFFFFF",
        "alignment": "center",
        # Auto-extract distance from category, e.g. "15K Female Master" -> "15K"
        "transform": lambda val: val.split()[0] if val else "",
    },
    
    # 6. Category Detail (shown below distance)
    "category_detail": {
        "x": 667,
        "y": 48,
        "font_name": "Helvetica-Bold",
        "font_size": 10,
        "color": "#FFFFFF",
        "alignment": "center",
        # Auto-extract remaining part of category, e.g. "15K Female Master" -> "FEMALE MASTER"
        "transform": lambda val: " ".join(val.split()[1:]).upper() if val and len(val.split()) > 1 else "",
    }
}

# ==========================================
# GENERATION ENGINE
# ==========================================

def draw_text(can, text, spec):
    """Draw text on the canvas based on specification."""
    if not text:
        return
        
    # Apply transformation if specified
    if "transform" in spec:
        text = spec["transform"](text)
        
    can.setFont(spec["font_name"], spec["font_size"])
    can.setFillColor(HexColor(spec["color"]))
    
    align = spec.get("alignment", "left").lower()
    x, y = spec["x"], spec["y"]
    
    if align == "center":
        can.drawCentredString(x, y, text)
    elif align == "right":
        can.drawRightString(x, y, text)
    else:
        can.drawString(x, y, text)

_TEMPLATE_BYTES = None

def generate_single_certificate(data, output_filename):
    """Generate a single certificate PDF by overlaying text on the template."""
    global _TEMPLATE_BYTES
    template_path = CONFIG["template_path"]
    
    if _TEMPLATE_BYTES is None:
        if not os.path.exists(template_path):
            print(f"Error: Template PDF not found at '{template_path}'")
            return False
        with open(template_path, "rb") as f:
            _TEMPLATE_BYTES = f.read()
            
    # Read the template PDF from memory cache
    reader = pypdf.PdfReader(io.BytesIO(_TEMPLATE_BYTES))
    template_page = reader.pages[0]
    width = float(template_page.mediabox.width)
    height = float(template_page.mediabox.height)
    
    # Create text overlay in memory
    packet = io.BytesIO()
    can = canvas.Canvas(packet, pagesize=(width, height))
    
    # Draw each configured field
    draw_text(can, data.get("Name", ""), CONFIG["name"])
    draw_text(can, data.get("Bib", ""), CONFIG["bib"])
    draw_text(can, data.get("Gender", ""), CONFIG["gender"])
    draw_text(can, data.get("Finish Time", ""), CONFIG["finish_time"])
    draw_text(can, data.get("Category Distance", ""), CONFIG["distance"])
    draw_text(can, data.get("Category Distance", ""), CONFIG["category_detail"])
    
    can.save()
    packet.seek(0)
    
    # Load overlay
    overlay_pdf = pypdf.PdfReader(packet)
    overlay_page = overlay_pdf.pages[0]
    
    # Merge template page and text overlay page
    writer = pypdf.PdfWriter()
    template_page.merge_page(overlay_page)
    writer.add_page(template_page)
    
    # Save the output file
    os.makedirs(os.path.dirname(output_filename), exist_ok=True)
    with open(output_filename, "wb") as f:
        writer.write(f)
        
    print(f"Generated: {output_filename}")
    return True

def process_csv(csv_path, limit=None):
    """Process a CSV file and generate certificates."""
    print(f"\nProcessing CSV file: {csv_path}")
    if not os.path.exists(csv_path):
        print(f"Error: CSV file not found at '{csv_path}'")
        return
        
    with open(csv_path, mode="r", encoding="utf-8") as f:
        reader = csv.DictReader(f)
        
        count = 0
        for row in reader:
            # Clean dictionary keys and values (strip whitespace)
            cleaned_row = {k.strip(): v.strip() for k, v in row.items() if k}
            
            # Format filename using Bib number and Name
            bib_num = cleaned_row.get("Bib", f"unknown_{count}")
            raw_name = cleaned_row.get("Name", "participant").strip().lower()
            name_slug = re.sub(r'[^a-z0-9]+', '_', raw_name).strip('_')
            output_filename = os.path.join(CONFIG["output_dir"], f"certificate_{bib_num}_{name_slug}.pdf")
            
            # Generate the certificate
            generate_single_certificate(cleaned_row, output_filename)
            
            count += 1
            if limit and count >= limit:
                print(f"Reached limit of {limit} certificates.")
                break
                
        print(f"Finished. Generated {count} certificates.")
if __name__ == "__main__":
    import argparse
    import glob

    parser = argparse.ArgumentParser(description="Generate race finisher certificates.")
    parser.add_argument("--csv", type=str, help="Path to a specific results CSV file.")
    parser.add_argument("--limit", type=int, default=None, help="Limit the number of certificates to generate per CSV file.")
    
    args = parser.parse_args()
    
    if args.csv:
        process_csv(args.csv, limit=args.limit)
    else:
        csv_files = sorted(glob.glob("public/results-csv/*.csv"))
        if not csv_files:
            print("No CSV files found in public/results-csv/")
        else:
            for csv_file in csv_files:
                process_csv(csv_file, limit=args.limit)

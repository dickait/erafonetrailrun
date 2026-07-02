import os
import time
from concurrent.futures import ThreadPoolExecutor
from PIL import Image

# Configuration
INPUT_DIR = os.path.join("storage", "app", "public", "jpg")
OUTPUT_DIR = os.path.join("storage", "app", "public", "eratrailrun2026-photos")
WEBP_QUALITY = 80  # Default quality for WebP conversion
MAX_WORKERS = 8    # Number of concurrent threads for conversion

def convert_single_image(file_info):
    """Converts a single JPG image to WebP."""
    filename, index, total = file_info
    input_path = os.path.join(INPUT_DIR, filename)
    
    # Generate output filename (replace extension with .webp)
    base_name = os.path.splitext(filename)[0]
    output_filename = f"{base_name}.webp"
    output_path = os.path.join(OUTPUT_DIR, output_filename)
    
    try:
        start_time = time.time()
        
        # Get original file size
        orig_size = os.path.getsize(input_path)
        
        # Open and convert
        with Image.open(input_path) as img:
            # WebP does not support some color modes directly without conversion, 
            # but standard RGB/RGBA/L are well-supported.
            # Usually JPEG is in RGB format.
            img.save(output_path, "WEBP", quality=WEBP_QUALITY)
            
        new_size = os.path.getsize(output_path)
        savings = orig_size - new_size
        savings_pct = (savings / orig_size) * 100 if orig_size > 0 else 0
        elapsed = time.time() - start_time
        
        print(f"[{index}/{total}] Converted: {filename} -> {output_filename} "
              f"({orig_size/1024/1024:.2f}MB -> {new_size/1024/1024:.2f}MB, "
              f"Saved {savings_pct:.1f}% in {elapsed:.2f}s)")
        
        return {
            "success": True,
            "filename": filename,
            "orig_size": orig_size,
            "new_size": new_size,
            "savings": savings
        }
    except Exception as e:
        print(f"[{index}/{total}] ERROR converting {filename}: {str(e)}")
        return {
            "success": False,
            "filename": filename,
            "error": str(e)
        }

def format_size(bytes_val):
    """Format bytes to human-readable string."""
    for unit in ['B', 'KB', 'MB', 'GB']:
        if bytes_val < 1024.0:
            return f"{bytes_val:.2f} {unit}"
        bytes_val /= 1024.0
    return f"{bytes_val:.2f} TB"

def main():
    print("=" * 60)
    print(" JPG TO WEBP IMAGE CONVERTER ".center(60, "="))
    print("=" * 60)
    print(f"Input Directory:  {INPUT_DIR}")
    print(f"Output Directory: {OUTPUT_DIR}")
    print(f"WebP Quality:     {WEBP_QUALITY}%")
    print(f"Max Workers:      {MAX_WORKERS}")
    print("=" * 60)

    # Validate input directory
    if not os.path.exists(INPUT_DIR):
        print(f"Error: Input directory '{INPUT_DIR}' does not exist.")
        return

    # Create output directory
    if not os.path.exists(OUTPUT_DIR):
        print(f"Creating output directory: {OUTPUT_DIR}")
        os.makedirs(OUTPUT_DIR, exist_ok=True)
    else:
        print(f"Output directory already exists: {OUTPUT_DIR}")

    # Gather JPG files
    supported_extensions = ('.jpg', '.jpeg', '.png')  # Supporting PNG too just in case
    all_files = os.listdir(INPUT_DIR)
    image_files = [f for f in all_files if f.lower().endswith(supported_extensions)]
    
    total_images = len(image_files)
    if total_images == 0:
        print("No supported images (.jpg, .jpeg, .png) found in the input directory.")
        return

    print(f"Found {total_images} images to convert. Starting process...\n")
    
    # Prepare inputs for the thread pool
    tasks = [(filename, idx + 1, total_images) for idx, filename in enumerate(image_files)]
    
    start_time = time.time()
    
    # Run conversion in parallel
    results = []
    with ThreadPoolExecutor(max_workers=MAX_WORKERS) as executor:
        results = list(executor.map(convert_single_image, tasks))
    
    total_duration = time.time() - start_time
    
    # Compile statistics
    successful_converts = [r for r in results if r.get("success")]
    failed_converts = [r for r in results if not r.get("success")]
    
    total_orig_size = sum(r["orig_size"] for r in successful_converts)
    total_new_size = sum(r["new_size"] for r in successful_converts)
    total_savings = sum(r["savings"] for r in successful_converts)
    avg_savings_pct = (total_savings / total_orig_size) * 100 if total_orig_size > 0 else 0
    
    print("\n" + "=" * 60)
    print(" CONVERSION SUMMARY ".center(60, "="))
    print("=" * 60)
    print(f"Total time taken:      {total_duration:.2f} seconds")
    print(f"Successfully converted: {len(successful_converts)} / {total_images}")
    if failed_converts:
        print(f"Failed conversions:    {len(failed_converts)}")
        for f in failed_converts:
            print(f"  - {f['filename']}: {f['error']}")
    print(f"Total original size:   {format_size(total_orig_size)}")
    print(f"Total WebP size:       {format_size(total_new_size)}")
    print(f"Total space saved:     {format_size(total_savings)} ({avg_savings_pct:.1f}% reduction)")
    print("=" * 60)

if __name__ == "__main__":
    main()

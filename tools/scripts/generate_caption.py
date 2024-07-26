import sys
import torch
from PIL import Image
import logging
from transformers import BlipProcessor, BlipForConditionalGeneration

# Set logging level to ERROR to suppress warnings
logging.getLogger("transformers").setLevel(logging.ERROR)

# Load the model and processor
processor = BlipProcessor.from_pretrained("Salesforce/blip-image-captioning-large")
model = BlipForConditionalGeneration.from_pretrained("Salesforce/blip-image-captioning-large").to("cuda")

device = torch.device("cuda" if torch.cuda.is_available() else "cpu")
model.to(device)

def preprocess_image(image_path):
    # Load and preprocess the image
    image = Image.open(image_path).convert("RGB")
    return image

def generate_caption(image_paths):
    captions = []
    for image_path in image_paths:
        image = preprocess_image(image_path)
        inputs = processor(images=image, return_tensors="pt").to(device)

        # Generate the caption
        output = model.generate(**inputs, max_length=16, num_beams=4, early_stopping=True)
        
        # Decode the output ids to generate the caption
        caption = processor.decode(output[0], skip_special_tokens=True)
        captions.append(caption)
    return captions

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python generate_caption.py <image_path1> <image_path2> ... <image_pathN>")
        sys.exit(1)
    
    image_paths = sys.argv[1:]
    captions = generate_caption(image_paths)
    
    for caption in captions:
        print(f"{caption}")

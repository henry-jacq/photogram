from transformers import VisionEncoderDecoderModel, ViTImageProcessor, GPT2Tokenizer
import torch
from PIL import Image
import logging, sys

# Set logging level to ERROR to suppress warnings
logging.getLogger("transformers").setLevel(logging.ERROR)

# Load the model and processor
model = VisionEncoderDecoderModel.from_pretrained("nlpconnect/vit-gpt2-image-captioning")
image_processor = ViTImageProcessor.from_pretrained("nlpconnect/vit-gpt2-image-captioning")
tokenizer = GPT2Tokenizer.from_pretrained("nlpconnect/vit-gpt2-image-captioning")

# Set pad_token_id to eos_token_id to avoid padding issues
tokenizer.pad_token_id = tokenizer.eos_token_id

device = torch.device("cuda" if torch.cuda.is_available() else "cpu")
model.to(device)

def preprocess_image(image_path):
    # Load and preprocess the image
    image = Image.open(image_path).convert("RGB")
    encoding = image_processor(images=image, return_tensors="pt")
    return encoding

def generate_caption(image_path):
    encoding = preprocess_image(image_path)
    pixel_values = encoding.pixel_values.to(device)

    # Generate the caption
    output_ids = model.generate(pixel_values, max_length=16, num_beams=4, early_stopping=True)
    
    # Decode the output ids to generate the caption
    caption = tokenizer.decode(output_ids[0], skip_special_tokens=True)
    return caption

# Example usage
image_path = sys.argv[1]
caption = generate_caption(image_path)
print(caption)
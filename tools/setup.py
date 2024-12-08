import os
import logging
from transformers import BlipProcessor, BlipForConditionalGeneration

# Load root directory and cache directory from environment variables or set default
root_dir = os.path.abspath(os.path.join(os.getcwd(), os.pardir))
hf_home_dir = os.path.join(root_dir, 'storage', 'cache')

# Set environment variable for the cache directory
os.environ['HF_HOME'] = hf_home_dir

os.environ['TRANSFORMERS_CACHE'] = hf_home_dir

# Set logging level to ERROR to suppress warnings
logging.getLogger("transformers").setLevel(logging.ERROR)

def is_model_cached(model_name):
    """Check if the model is cached in the local directory."""
    cache_dir = hf_home_dir
    model_dir = os.path.join(cache_dir, 'hub', f'models--{model_name.replace("/", "--")}')
    print(f"Checking cache directory: {model_dir}")
    
    # Ensure the directory exists
    if not os.path.isdir(model_dir):
        return False
    
    return any(os.listdir(model_dir))

def check_and_install_model(model_name):
    """Check if the model is cached, if not, download it."""
    if is_model_cached(model_name):
        print(f"The model '{model_name}' is already cached.")
    else:
        print(f"The model '{model_name}' is not cached. Downloading...")
        try:
            # Attempt to load the model to trigger the download
            BlipForConditionalGeneration.from_pretrained(model_name)
            print(f"Model '{model_name}' downloaded successfully.")
        except Exception as e:
            print(f"Error downloading model '{model_name}': {e}")

if __name__ == "__main__":
    # Ensure the cache directory exists
    if not os.path.exists(hf_home_dir):
        os.makedirs(hf_home_dir)
    
    models_to_check = [
        "Salesforce/blip-image-captioning-large",
        # Add other models here as needed
    ]
    
    for model_name in models_to_check:
        check_and_install_model(model_name)

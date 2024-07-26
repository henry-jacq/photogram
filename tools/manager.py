import os
import sys
import subprocess
import logging
import warnings
from setup import root_dir, hf_home_dir

# Suppress specific FutureWarning related to TRANSFORMERS_CACHE
warnings.filterwarnings("ignore", category=FutureWarning, module="transformers.utils.hub")

# Define paths and environment
scripts_dir = os.path.join(os.path.dirname(__file__), 'scripts')
python_executable = os.path.join(root_dir, 'venv', 'bin', 'python3')

# Set environment variable for caching
os.environ['HF_HOME'] = hf_home_dir

# Set logging configuration
logging.basicConfig(level=logging.ERROR)
logger = logging.getLogger("manager")

def run_script(script_key, *args):
    try:
        script_name = script_key + '.py'
        script_path = os.path.join(scripts_dir, script_name)
        
        # Validate script path
        validate_file_path(script_path)
        
        command = [python_executable, script_path] + list(args)
        logger.info(f"Running command: {' '.join(command)}")
        
        result = subprocess.run(command, capture_output=True, text=True)
        
        if result.returncode != 0:
            logger.error(f"Error running script {script_name}: {result.stderr.strip()}")
            sys.exit(result.returncode)
        
        print(result.stdout.strip())
    except Exception as e:
        logger.exception(f"Exception: {str(e)}")
        sys.exit(1)
        
def validate_file_path(file_path):
    """Validate if the provided file path exists."""
    if not os.path.isfile(file_path):
        raise ValueError(f"File not found: {file_path}")
    return file_path

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(f"Usage: {python_executable} {sys.argv[0]} <script_key> [args]")
        sys.exit(1)
    
    script_key = sys.argv[1]
    args = sys.argv[2:]
    run_script(script_key, *args)

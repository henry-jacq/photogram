import os, sys, subprocess
from utilities.helper import validate_file_path, check_internet_connectivity

# Define constants or configuration here
python_executable = 'python3'
scripts_dir = os.path.join(os.path.dirname(__file__), 'scripts')
config_file = os.path.join(os.path.dirname(__file__), 'config.json')

def run_script(script_key, *args):
    try:
        script_name = script_key + '.py'
        script_path = os.path.join(scripts_dir, script_name)
        
        # Check internet connection
        if not check_internet_connectivity():
            print("Error: No internet connection. Please check your connection and try again.")
            sys.exit(1)
        
        # Validate script path
        validate_file_path(script_path)
        
        command = [python_executable, script_path] + list(args)
        result = subprocess.run(command, capture_output=True, text=True)
        
        if result.returncode != 0:
            print(f"Error running script {script_name}: {result.stderr.strip()}")
            sys.exit(result.returncode)
        
        print(result.stdout.strip())
    except Exception as e:
        print(f"Exception: {str(e)}")
        sys.exit(1)

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(f"Usage: {python_executable} {sys.argv[0]} <script_key> [args]")
        sys.exit(1)
    
    script_key = sys.argv[1]
    args = sys.argv[2:]
    run_script(script_key, *args)

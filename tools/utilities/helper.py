import os, json, requests

def validate_file_path(file_path):
    """Validate if the provided file path exists."""
    if not os.path.isfile(file_path):
        raise ValueError(f"File not found: {file_path}")
    return file_path

def check_internet_connectivity(url='http://www.google.com', timeout=5):
    try:
        response = requests.get(url, timeout=timeout)
        # Check if the request was successful
        return response.status_code == 200
    except requests.ConnectionError:
        return False
    except requests.Timeout:
        return False

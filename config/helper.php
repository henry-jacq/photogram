<?php

use Carbon\Carbon;
require_once __DIR__ . '/../config/constants.php';

// Helper functions

/**
 * Dump and Die
 */
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

/**
 * Return current date and time
 */
function now()
{
    return date('Y-m-d H:i:s');
}

/**
 * Return json encoded data
 */
function packJson(array $data)
{
    if (is_array($data)) {
        return json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    }
    return '{}';
}

/**
 * Return json encoded data
 */
function unpackJson(string $data)
{
    return json_decode($data);
}

/**
 * Return the length of the given data
 */
function getLength($data)
{
    if (is_array($data)) {
        return count($data);
    } else if (is_object($data)) {
        return count(get_object_vars($data));
    } else {
        return strlen((string) $data);
    }
}

/**
 * Validate given email
 */
function filterEmail(string $email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function dump($var)
{
    if (is_bool($var)) {
        $var = 'bool(' . ($var ? 'true' : 'false') . ')';
    }

    if (php_sapi_name() === 'cli') {
        print_r($var);
    } else {
        highlight_string("<?php\n" . var_export($var, true));
    }
}

function hashtag(string $text)
{
    $filteredText = preg_replace('/#(\w+)/', '<a href="/discover/tags/$1">#$1</a>', $text);
    return $filteredText;
}

function getHumanDiffTime(string $timestamp)
{
    $time = Carbon::parse($timestamp);
    return $time->diffForHumans();
}

function getBrowser($userAgent)
{
    $browser = "Unknown Browser";

    $browsers = [
        'MSIE' => 'Internet Explorer',
        'Trident' => 'Internet Explorer', // For IE 11+
        'Edge' => 'Microsoft Edge',
        'Firefox' => 'Mozilla Firefox',
        'Chrome' => 'Google Chrome',
        'Safari' => 'Apple Safari',
        'Opera' => 'Opera',
        'OPR' => 'Opera', // Opera's user agent string might contain 'OPR' instead of 'Opera'
    ];

    foreach ($browsers as $key => $value) {
        if (strpos($userAgent, $key) !== false) {
            $browser = $value;
            break;
        }
    }

    return $browser;
}

function getOS($userAgent)
{
    $osPlatform = "Unknown OS Platform";

    $osArray = [
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile',
    ];

    foreach ($osArray as $regex => $value) {
        if (preg_match($regex, $userAgent)) {
            $osPlatform = $value;
            break;
        }
    }

    return $osPlatform;
}

function getDeviceType($userAgent)
{
    $isMobile = preg_match('/mobile|android|kindle|silk|midp|phone|ipod|tablet/i', $userAgent);

    return $isMobile ? 'Phone' : 'PC';
}

// Convert the size to a more readable format (e.g., KB, MB)
function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

/**
 * Executes a Python script with the given script key and arguments.
 *
 * @param string $scriptKey The key corresponding to the Python script to execute.
 * @param array $args Arguments to pass to the Python script.
 * @return string The output of the Python script execution.
 * @throws Exception If Python 3 is not found or script execution fails.
 */
function pyToolExecuter($scriptKey, $args = [])
{
    $pythonScriptPath = TOOLS_PATH . DIRECTORY_SEPARATOR .'manager.py';

    // Determine the Python executable
    $pythonExecutable = ROOT_PATH . "/venv/bin/python3";

    // Set the environment variable for Hugging Face cache
    if (!file_exists(STORAGE_PATH."/cache")) {
        mkdir(STORAGE_PATH."/cache", 0777, true);
    }
    putenv("HF_HOME=". STORAGE_PATH."/cache");

    if (empty($pythonExecutable)) {
        throw new Exception('Python 3 executable not found.');
    }

    // Validate the script key
    if (str_contains($scriptKey, '.')) {
        throw new Exception('Invalid script key. No dots allowed in script key.');
    }

    // Construct the command
    $command = escapeshellcmd("$pythonExecutable $pythonScriptPath $scriptKey " . implode(' ', array_map('escapeshellarg', $args)));

    // Execute the command and capture the output
    $output = shell_exec($command);

    // Check for execution errors
    if ($output === null) {
        throw new Exception("Failed to execute command: $command");
    }

    // Optionally handle specific errors (depends on your Python script)
    if (str_contains($output, 'error:')) {
        throw new Exception("Python script returned an error: $output");
    }

    return trim($output);
}

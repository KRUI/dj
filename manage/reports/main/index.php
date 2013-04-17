<?php
    date_default_timezone_set('America/Chicago');
    $curl = curl_init();
    $time = time() - 604800;
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, "http://staff.krui.fm/api/playlist/main/items.json?limit=5000&after={$time}");
    $data = json_decode(curl_exec($curl), true);
    $today = gmdate('Y-m-d\TH:i:s\Z', time());
    $file = "main-weekly-{$today}.csv";
    $fp = fopen($file, 'w');
    fputcsv($fp, array('Song', 'Artist', 'Album', 'Requested', 'Time'));
    foreach ($data as $row) {
        fputcsv($fp, $row[0]['song']);
    }
    if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit();
}
?>
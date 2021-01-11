<?php

/**
 * 1. Grab cookies from web browser's active session and save to file.
 * 2. Pass cooke file as first argument
 * 3. Pass username as second argument
 * 4. Pass data type as third argument, default is 'activity'
 */

$cfile = @$argv[1];
$user  = @$argv[2];

if (!(is_file($cfile) && is_readable($cfile))) {
    echo("Cookie file not found or readable.\n");
    exit(1);
}

if (empty($user)) {
    echo("Username is required.\n");
    exit(1);
}

$cookie = trim(file_get_contents($cfile));

$types = ['activity', 'highlight', 'note', 'bookmark'];
$export = in_array(@$argv[3], $types) ? @$argv[3] : $types[0];

$save_dir = __DIR__ . "/data";
if(!is_dir($save_dir)) mkdir($save_dir);

$sfile = "$save_dir/".ucfirst($export) . "-".date('Ymd-His').".json";

$count = 0;
$cerror = null;
$content = "[";

while(true) {
    $output = get_pages(++$count, $export);
    $decoded = json_decode($output, true);

    if (!is_array($decoded)) {
        echo "Error parsing YouVersion data\n";
        if (!empty($cerror)) echo "$cerror\n";
        exit(1);
    }

    if (isset($decoded['error'])) {
        echo(" [DONE] \n");
        break;
    } else {
        if ($count > 1) $content .= ",";
        $output = ltrim($output, '[');
        $output = rtrim($output, ']');
        $content .= $output;

        echo "\rExported page $count...";
    }
}

$content .= "]";

file_put_contents($sfile, $content);

function get_pages($count, $type) {
    global $cookie;
    global $user;
    global $cerror;

    if ($type == "activity") {
        $url = "https://my.bible.com/users/{$user}/_cards.json?page=$count";
    } else {
        $url = "https://my.bible.com/users/{$user}/_cards.json?kind=$type&page=$count";
    }

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: $cookie"));

    $output = curl_exec($ch);
    $cerror = curl_error($ch);

    curl_close($ch);

    return $output;
}

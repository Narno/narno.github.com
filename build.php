#!/usr/local/bin/php
<?php
if (php_sapi_name() !== 'cli') {
    return;
}
date_default_timezone_set('Europe/Paris');
require_once 'vendor/autoload.php';
use PHPoole\PHPoole;
use Symfony\Component\Yaml\Yaml;

$getopt = getopt('e::p::');

$options = Yaml::parse(file_get_contents('.phpoole'));
$options_dev = [
    'site' => [
        'baseurl' => 'http://localhost:8000',
    ],
];

$prod = (isset($getopt['e']) && $getopt['e'] == 'prod') ? true : false;
$options = (!$prod) ? array_replace_recursive($options, $options_dev) : $options;

$phpoole = new PHPoole('./', null, $options);
$phpoole->build();

// run server
if (!$prod) {
    echo "Start server http://localhost:8000\n";
    echo "Ctrl-C to stop it\n";
    exec('php -S localhost:8000 -t _site');

} else {
// publish?
    if (isset($getopt['p'])) {
        echo "Publishing...\n";
        // @todo implement git push to the gh-pages branch
    }
}
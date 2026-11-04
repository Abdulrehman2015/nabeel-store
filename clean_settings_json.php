<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$allSettings = DB::table('settings')->get();
foreach ($allSettings as $s) {
    if (stripos($s->value, 'wholesale') !== false || stripos($s->key, 'wholesale') !== false) {
        echo "Key: {$s->key} => " . substr($s->value, 0, 150) . "\n";
        $cleanVal = str_ireplace('wholesale', 'mart', $s->value);
        DB::table('settings')->where('id', $s->id)->update(['value' => $cleanVal]);
        echo "  Updated to: " . substr($cleanVal, 0, 150) . "\n";
    }
}


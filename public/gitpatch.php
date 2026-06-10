<?php
// HAPUS FILE INI SETELAH DIGUNAKAN
$secret = 'margono2026';
$key    = $_GET['key'] ?? '';

if ($key !== $secret) {
    http_response_code(403);
    die('<h3>403 - Akses ditolak. Tambahkan ?key=margono2026 di URL.</h3>');
}

$base = '/home/parr4187/public_html/margonoandi-fanbase';

$commands = [
    'Fetch origin'      => "cd $base && git fetch origin 2>&1",
    'Checkout main'     => "cd $base && git checkout main 2>&1",
    'Status'            => "cd $base && git status 2>&1",
    'Branch list'       => "cd $base && git branch -a 2>&1",
];

echo '<!DOCTYPE html><html><head>
<meta charset="UTF-8">
<title>Git Patch — Margonoandi</title>
<style>
  body { font-family: monospace; background:#0b1520; color:#e8f4fa; padding:2rem; }
  h1   { color:#38A8CC; margin-bottom:1.5rem; }
  h3   { color:#F07040; margin:1.5rem 0 0.5rem; }
  pre  { background:#0f1e2e; border:1px solid rgba(56,168,204,0.2);
         padding:1rem; border-radius:8px; white-space:pre-wrap; word-break:break-all; }
  .ok  { color:#4ade80; }
  .err { color:#f87171; }
  .warn{ color:#facc15; margin-top:2rem; border:1px solid #F07040;
         padding:1rem; border-radius:8px; }
</style></head><body>';

echo '<h1>Git Patch — Margonoandi Fanbase</h1>';

foreach ($commands as $label => $cmd) {
    echo "<h3>$label</h3>";
    $output = shell_exec($cmd);
    if ($output === null) {
        echo '<pre class="err">shell_exec() tidak tersedia di server ini.</pre>';
    } else {
        $class = (str_contains($output, 'error') || str_contains($output, 'fatal')) ? 'err' : 'ok';
        echo '<pre class="' . $class . '">' . htmlspecialchars($output) . '</pre>';
    }
}

echo '<div class="warn">⚠️ PENTING: Hapus file <strong>public/gitpatch.php</strong> dari server setelah selesai!</div>';
echo '</body></html>';

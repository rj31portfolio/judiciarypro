<?php
// Prevent header already sent issues
if (!headers_sent()) {
    ob_start();
}

function config()
{
    static $config;
    if ($config === null) {
        $config = require __DIR__ . '/config.php';
    }
    return $config;
}

function db()
{
    static $pdo;
    if ($pdo === null) {
        $pdo = require __DIR__ . '/db.php';
    }
    return $pdo;
}

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function slugify($value)
{
    $value = trim(strtolower($value));
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value);
    $value = trim($value, '-');
    return $value !== '' ? $value : 'item';
}

function base_url()
{
    $config = config();
    $base = trim($config['site']['base_url'] ?? '');

    if ($base === '' || $base === 'auto') {
        return detect_base_url();
    }

    if (stripos($base, 'http://') === 0 || stripos($base, 'https://') === 0) {
        return rtrim($base, '/');
    }

    if ($base[0] === '/') {
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        if ($script !== '' && strpos($script, $base) !== 0) {
            return detect_base_url();
        }
        return rtrim($base, '/');
    }

    return rtrim($base, '/');
}

function detect_base_url()
{
    $docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
    $projectRoot = rtrim(str_replace('\\', '/', realpath(__DIR__ . '/..') ?: ''), '/');

    if ($docRoot !== '' && $projectRoot !== '' && strpos($projectRoot, $docRoot) === 0) {
        $relative = trim(substr($projectRoot, strlen($docRoot)), '/');
        return $relative === '' ? '' : '/' . $relative;
    }

    return '';
}

function url_for($path)
{
    $base = base_url();
    $path = '/' . ltrim($path, '/');

    return $base !== '' ? $base . $path : $path;
}

function redirect($path)
{
    $url = url_for($path);

    if (!headers_sent()) {
        header("Location: $url");
        exit;
    } else {
        echo "<script>window.location.href='$url';</script>";
        exit;
    }
}

function handle_upload($field, $destDir = 'uploads')
{
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extension = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
    $safeExtension = preg_replace('/[^a-z0-9]/i', '', $extension);

    $filename = uniqid('upload_', true) . ($safeExtension ? '.' . $safeExtension : '');

    $targetDir = __DIR__ . '/../' . trim($destDir, '/');

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $targetPath = $targetDir . '/' . $filename;

    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $targetPath)) {
        return null;
    }

    return trim($destDir, '/') . '/' . $filename;
}

function seo_get($pageKey)
{
    $stmt = db()->prepare("SELECT * FROM seo_meta WHERE page_key = ? LIMIT 1");
    $stmt->execute([$pageKey]);

    return $stmt->fetch() ?: [];
}

function seo_merge($pageKey, array $defaults)
{
    $seo = seo_get($pageKey);

    return array_merge(
        $defaults,
        array_filter($seo, function ($value) {
            return $value !== null && $value !== '';
        })
    );
}

function render_seo($pageKey, array $defaults)
{
    $seo = seo_merge($pageKey, $defaults);

    $title = $seo['meta_title'] ?? '';
    $description = $seo['meta_description'] ?? '';
    $keywords = $seo['meta_keywords'] ?? '';
    $robots = $seo['robots'] ?? config()['site']['default_robots'];
    $canonical = $seo['canonical_url'] ?? '';

    if ($title) {
        echo "<title>" . h($title) . "</title>\n";
    }

    if ($description) {
        echo '<meta name="description" content="' . h($description) . '">' . "\n";
    }

    if ($keywords) {
        echo '<meta name="keywords" content="' . h($keywords) . '">' . "\n";
    }

    if ($robots) {
        echo '<meta name="robots" content="' . h($robots) . '">' . "\n";
    }

    if ($canonical) {
        echo '<link rel="canonical" href="' . h($canonical) . '">' . "\n";
    }
}

function pagination_links($currentPage, $totalPages, $path, array $params = [])
{
    if ($totalPages <= 1) return '';

    $currentPage = max(1, min((int)$currentPage, $totalPages));

    unset($params['page']);

    $buildUrl = function ($page) use ($path, $params) {
        $query = $params;
        $query['page'] = $page;
        $qs = http_build_query($query);

        return $path . ($qs ? '?' . $qs : '');
    };

    $html = '<nav><ul class="pagination">';

    for ($i = 1; $i <= $totalPages; $i++) {

        $active = $i == $currentPage ? ' class="active"' : '';

        $html .= '<li'.$active.'>';
        $html .= '<a href="'.h($buildUrl($i)).'">'.$i.'</a>';
        $html .= '</li>';
    }

    $html .= '</ul></nav>';

    return $html;
}
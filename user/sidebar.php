<?php
// Ambil file saat ini
$currentFile = basename($_SERVER['PHP_SELF']);
$currentUri  = $_SERVER['REQUEST_URI'];

// Ambil base URL (tanpa /admin)
$scriptName = $_SERVER['SCRIPT_NAME'];
$baseUrl = substr($scriptName, 0, strpos($scriptName, '/admin'));

// Fungsi untuk menandai halaman aktif berdasarkan file
function isActive($files)
{
    global $currentFile;
    if (is_array($files)) {
        return in_array($currentFile, $files) ? 'bg-blue' : '';
    }
    return $currentFile === $files ? 'bg-blue' : '';
}

// Fungsi untuk menandai menu aktif berdasarkan path dan file
function isActiveMulti($filenames, $folder)
{
    global $currentFile, $currentUri;
    return (in_array($currentFile, $filenames) && strpos($currentUri, $folder) !== false) ? 'bg-blue' : '';
}
?>


<aside class="collapse show collapse-horizontal col-sm-2 p-3 border-end d-none d-lg-block" id="collapseWidthExample">
    <a href="<?= $baseUrl ?>/index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <img src="<?= $baseUrl ?>/assets/img/Logo.webp" alt="Logo" width="40" />
        <span class="d-print-block ms-2 fw-bold fs-5">Al Capone</span>
    </a>
    <br />
    <ul class="list-unstyled ps-0" id="sidebar">
        <li class="mb-2">
            <a href="<?= $baseUrl ?>/admin/dashboard.php"
                class="btn btn-toggle d-inline-flex align-items-center rounded border-0 w-100 <?= isActive('dashboard.php') ?>">
                <i class="bi bi-house-door-fill me-2"></i> Dashboard
            </a>
        </li>
    </ul>
</aside>
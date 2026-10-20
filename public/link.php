<?php
$target = __DIR__ . '/../storage/app/public';
$shortcut = __DIR__ . '/storage';
if (file_exists($shortcut)) {
    unlink($shortcut);
}
symlink($target, $shortcut);
echo "Symlink created successfully!";

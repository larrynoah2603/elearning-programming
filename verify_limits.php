<?php
echo "=== VÉRIFICATION DES LIMITES PHP APRÈS REDÉMARRAGE ===\n\n";
echo "post_max_size: " . ini_get('post_max_size') . " (" . (int)(ini_get('post_max_size')) * 1024 * 1024 . " octets)\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . " (" . (int)(ini_get('upload_max_filesize')) * 1024 * 1024 . " octets)\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";
echo "max_execution_time: " . ini_get('max_execution_time') . " secondes\n\n";

$fileSize = 49173187; // 47MB en octets
$postLimit = (int)(ini_get('post_max_size')) * 1024 * 1024;
$uploadLimit = (int)(ini_get('upload_max_filesize')) * 1024 * 1024;

echo "=== TEST POUR FICHIER DE 47MB ===\n";
echo "Taille du fichier: " . number_format($fileSize) . " octets\n";
echo "Limite POST: " . number_format($postLimit) . " octets\n";
echo "Limite upload: " . number_format($uploadLimit) . " octets\n\n";

if ($fileSize <= $postLimit && $fileSize <= $uploadLimit) {
    echo "✅ LE FICHIER PEUT ÊTRE UPLOADÉ !\n";
} else {
    echo "❌ LE FICHIER EST TROP VOLUMINEUX\n";
    if ($fileSize > $postLimit) echo "- Dépasse la limite POST\n";
    if ($fileSize > $uploadLimit) echo "- Dépasse la limite d'upload\n";
}
?>
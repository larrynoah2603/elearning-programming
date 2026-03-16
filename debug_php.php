<?php
echo "=== DIAGNOSTIC PHP ===\n\n";
echo "PHP.INI chargé: " . php_ini_loaded_file() . "\n\n";

echo "Vérification du contenu du fichier:\n";
$iniPath = php_ini_loaded_file();
if (file_exists($iniPath)) {
    $content = file_get_contents($iniPath);
    if (strpos($content, 'post_max_size = 100M') !== false) {
        echo "✅ post_max_size = 100M trouvé dans le fichier\n";
    } else {
        echo "❌ post_max_size = 100M NON trouvé dans le fichier\n";
        // Chercher la ligne actuelle
        if (preg_match('/post_max_size\s*=\s*(\w+)/', $content, $matches)) {
            echo "Valeur actuelle: {$matches[1]}\n";
        }
    }

    if (strpos($content, 'upload_max_filesize = 100M') !== false) {
        echo "✅ upload_max_filesize = 100M trouvé dans le fichier\n";
    } else {
        echo "❌ upload_max_filesize = 100M NON trouvé dans le fichier\n";
        if (preg_match('/upload_max_filesize\s*=\s*(\w+)/', $content, $matches)) {
            echo "Valeur actuelle: {$matches[1]}\n";
        }
    }
} else {
    echo "❌ Fichier php.ini introuvable\n";
}

echo "\n=== Vérification des extensions ===\n";
$extensions = get_loaded_extensions();
$wampExtensions = array_filter($extensions, function($ext) {
    return strpos($ext, 'wamp') !== false || strpos($ext, 'php_wampserver') !== false;
});
if (!empty($wampExtensions)) {
    echo "Extensions WAMP détectées: " . implode(', ', $wampExtensions) . "\n";
} else {
    echo "Aucune extension WAMP détectée\n";
}
?>
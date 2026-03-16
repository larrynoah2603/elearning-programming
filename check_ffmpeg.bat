@echo off
echo Vérification de FFmpeg...
ffmpeg -version >nul 2>&1
if %errorlevel% neq 0 (
    echo FFmpeg n'est pas installé ou n'est pas dans le PATH.
    echo.
    echo Pour installer FFmpeg :
    echo 1. Téléchargez depuis : https://ffmpeg.org/download.html#build-windows
    echo 2. Extrayez l'archive dans un dossier (ex: C:\ffmpeg)
    echo 3. Ajoutez C:\ffmpeg\bin au PATH système
    echo 4. Redémarrez votre terminal
    echo.
    pause
    exit /b 1
) else (
    echo FFmpeg est installé et fonctionne !
    ffmpeg -version | findstr "version"
)
echo.
echo Maintenant, vous pouvez reuploader vos vidéos dans l'admin.
echo Elles seront automatiquement converties au format web-compatible.
pause
@echo off

SET version=v1.0.1B

SET for_minecraft=1.10.2
SET for_realms=1.9.5

:: Minecraft path
SET minecraft=%appdata%\.minecraft

echo Sphinx Client Patch Installer %version%
echo For Minecraft %for_minecraft%.
echo For Realms %for_realms%.
echo.

:: Check to see if Minecraft can run Sphinx.
echo Checking system requirements...
if not exist "%minecraft%\versions\1.10.2" (
	echo Cannot install Sphinx!
	echo You must have Minecraft installed in the default location and Minecraft 1.10.2 installed.
	goto end
)

if exist "%minecraft%\versions\1.10.2-Sphinx" (
	echo Sphinx is already installed!
	goto end
)

:: Create tempoary directory. If it already exists, delete it and recreate it.
if exist tmp (
	del /F /S /Q tmp 1>nul
	rmdir /S /Q tmp
)
mkdir tmp

:: Download Sphinx version json file.
echo Downloading patch...
powershell -Command "(New-Object Net.WebClient).DownloadFile('https://raw.githubusercontent.com/mitchfizz05/Sphinx/master/Sphinx-Patch/patches/1.10.2/1.10.2-Sphinx.json', 'tmp/Sphinx.json')"

:: Install the patch.
echo Installing patch...

mkdir "%minecraft%\versions\1.10.2-Sphinx" >NUL
copy /Y "%minecraft%\versions\1.10.2\1.10.2.jar" "%minecraft%\versions\1.10.2-Sphinx\1.10.2-Sphinx.jar" >NUL
copy /Y "tmp\Sphinx.json" "%minecraft%\versions\1.10.2-Sphinx\1.10.2-Sphinx.json" >NUL

echo.
echo Patch successfully installed!

:: Remove tempoary directory. (with a short delay so files can be unlocked)
ping localhost -n 1 >NUL
del /F /S /Q tmp 1>NUL
rmdir /S /Q tmp >NUL

goto end

:end

echo.
pause


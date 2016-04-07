#!/bin/bash

VERSION="v1.0.0B"

FOR_MINECRAFT="1.9.2"
FOR_REALMS="1.8.15"

MINECRAFT="$HOME/.minecraft"

echo "Sphinx Client Patch Installer $VERSION (for Linux)."
echo "For Minecraft $FOR_MINECRAFT"
echo "For Realms $FOR_REALMS"
echo ""

# Checking if Minecraft Minecraft can run Sphinx
echo Checking system requirements...
if [ ! -d "$MINECRAFT/versions/1.9.2" ]; then
	echo "Cannot install Sphinx!"
	echo "You must have Minecraft installed in the default location and Minecraft 1.9.2 installed."
	exit
fi

if [ -d "$MINECRAFT/versions/1.9.2-Sphinx" ]; then
	echo "Sphinx is already installed!"
	exit
fi

# Remove tempoary files if they exist.
rm /tmp/sphinx.json 2>/dev/null

# Download Sphinx version.json file.
echo Downloading patch...
wget https://raw.githubusercontent.com/mitchfizz05/Sphinx/master/Sphinx-Patch/patches/1.9.2/1.9.2-Sphinx.json -O /tmp/sphinx.json --quiet
wget https://github.com/mitchfizz05/Sphinx/raw/master/Sphinx-Patch/dl/1.8.15/realms-1.8.15.jar -O /tmp/sphinx.jar --quiet

# Install the patch.
echo Installing patch...

mkdir $MINECRAFT/versions/1.9.2-Sphinx
cp $MINECRAFT/versions/1.9.2/1.9.2.jar $MINECRAFT/versions/1.9.2-Sphinx/1.9.2-Sphinx.jar
cp /tmp/sphinx.json $MINECRAFT/versions/1.9.2-Sphinx/1.9.2-Sphinx.json
mkdir $MINECRAFT/libraries/com/mojang/realms/1.8.15+sphinx
cp /tmp/sphinx.jar $MINECRAFT/libraries/com/mojang/realms/1.8.15+sphinx/realms-1.8.15+sphinx.jar

echo ""
echo "Patch successfully installed!"

# Remove temp files.
rm /tmp/sphinx.json
rm /tmp/sphinx.jar


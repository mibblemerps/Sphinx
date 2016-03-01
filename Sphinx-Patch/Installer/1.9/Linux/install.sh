#!/bin/bash

VERSION="v1.0.0"

FOR_MINECRAFT="1.9"
FOR_REALMS="1.8.3"

MINECRAFT="$HOME/.minecraft"

echo "Sphinx Client Patch Installer $VERSION (for Linux)."
echo "For Minecraft $FOR_MINECRAFT"
echo "For Realms $FOR_REALMS"
echo ""

# Checking if Minecraft Minecraft can run Sphinx
echo Checking system requirements...
if [ -f $MINECRAFT/versions/1.9 ]; then
	echo "Cannot install Sphinx!"
	echo "You must have Minecraft installed in the default location and Minecraft 1.9 installed."
	exit
fi

if [ -f $MINECRAFT/versions/1.9-Sphinx ]; then
	echo "Sphinx is already installed!"
	exit
fi

# Remove tempoary files if they exist.
rm /tmp/sphinx.json 2>/dev/null

# Download Sphinx version.json file.
echo Downloading patch...
wget https://raw.githubusercontent.com/mitchfizz05/Sphinx/master/Sphinx-Patch/patches/1.9/1.9-Sphinx.json -O /tmp/sphinx.json --quiet

# Install the patch.
echo Installing patch...

mkdir $MINECRAFT/versions/1.9-Sphinx
cp $MINECRAFT/versions/1.9/1.9.jar $MINECRAFT/versions/1.9-Sphinx/1.9-Sphinx.jar
cp /tmp/sphinx.json $MINECRAFT/versions/1.9-Sphinx/1.9-Sphinx.json

echo ""
echo "Patch successfully installed!"

# Remove temp file.
rm /tmp/sphinx.json


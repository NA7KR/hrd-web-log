#!/bin/bash

# Get the current working directory
current_path=$(pwd)

# Display a message before starting
echo "Setting permissions for directories under $current_path"
# Extract the parent directory path
parent_path=$(dirname "$current_path")

# Set the directories to be modified
directories=("Awards" "cards" "cards-out")

# Loop through each directory and set permissions
for dir in "${directories[@]}"; do
    sudo chmod -R 757 "$parent_path/$dir"
    sudo find "$parent_path/$dir" -type f -exec chmod 655 {} \;
done

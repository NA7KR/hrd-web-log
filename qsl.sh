#!/bin/bash

# Check if argument is provided
if [ $# -eq 0 ]; then
  echo "Usage: $0 0 to auto run or QSL number --help "
  exit 1
fi


cd /var/www/Working

# Run PHP script with argument
php qsl.php "$1"

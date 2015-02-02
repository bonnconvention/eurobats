#!/bin/bash

# Go to docroot/
cd docroot/

drush site-install -y

pre_update=  post_update=
while getopts b:a: opt; do
  case $opt in
  b)
      pre_update=$OPTARG
      ;;
  a)
      post_update=$OPTARG
      ;;
  esac
done

# Sync from staging
drush downsync_sql @eurobats.staging.sync @eurobats.local -y

if [ ! -z "$pre_update" ]; then
echo "Run pre update"
../$pre_update
fi

# Build the site
echo "Runing custom_build..."
drush custom_build -y

# Devify - development settings
drush devify --yes
# drush devify_solr

drush cc all

if [ ! -z "$post_update" ]; then
echo "Run post update"
../$post_update
fi

drush cc all

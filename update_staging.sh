#!/bin/bash

# Go to docroot/
cd /var/www/html/cms/eurobats.edw.ro

# Pulling the last Updates
git pull

# Sync from edw staging
drush downsync @eurobats.production @eurobats.staging -y

drush cc all

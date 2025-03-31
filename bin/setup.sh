#!/bin/bash

wp-env run cli wp theme activate twentytwentythree
wp-env run cli wp rewrite structure /%postname%
wp-env run cli wp option update blogname "Image Converter for WebP"
wp-env run cli wp option update blogdescription "Image Converter for WebP"

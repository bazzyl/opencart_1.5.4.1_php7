# OpenCart v1.5.4.1 (with vQmod) for PHP7.2+

This project is a fork of OpenCart version 1.5.4.1, modified to be compatible with **PHP 7.2+**. 
The modifications also include the integration of **vQmod**.

## Purpose

This fork is particularly useful for:

- **Improving performance** of older websites running on the original engine version.
- **Migrating** the website to modern hosting environments that require PHP 7.2 or higher.
- **Ensuring compatibility** with certain modules that need updated PHP extensions and secure encryption methods.

## Key Changes

- Replaced deprecated `mysql` extension with **`mysqli`**.
- Replaced deprecated `mcrypt` extension with **`openssl`**.

## Installation Instructions

1. Install OpenCart using the traditional method by following the documentation.
2. After installation, to activate **vQmod**, run the following script: https://your-store.com/vqmod/install/index.php (where `your-store.com` is the URL of your store.)

# My-first-blog-in-PHP
My first professional blog developed by me.

## Codacy Badge
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/a845df5a4fd24c1e87829a0b78eaaddc)](https://www.codacy.com/manual/Scratchy-Show/My-first-blog-in-PHP?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Scratchy-Show/My-first-blog-in-PHP&amp;utm_campaign=Badge_Grade)

## Install the project
  * Download the project clone.
------------------------------------------------------------------------------------------------------------------------------------------
  * Download and install WampServer (or equivalent: MampServer, XampServer, LampServer).
------------------------------------------------------------------------------------------------------------------------------------------
  * Put the project file in the www folder of WampServer.
------------------------------------------------------------------------------------------------------------------------------------------
  * Create a database in phpMyAdmin :
    * Database name: `my_website`
    * Classification: `utf8_general_ci`
------------------------------------------------------------------------------------------------------------------------------------------
  * In the root directory of the project, execute in a PowerShell window the commands : 
    * `composer install`
    * `vendor/bin/doctrine orm:schema-tool:update --force --dump-sql`
    * `vendor/bin/doctrine orm: generate-proxies`
------------------------------------------------------------------------------------------------------------------------------------------
  * Create a VirtualHost on WampServer :
    * Launch WampServer
    * Go to the address: http://localhost/add_vhost.php?Lang=french
    * Indicate the name of the VirtualHost (ex: myBlog)
    * Indicate the absolute path of the VirtualHost folder (ex: C:\wamp64\www\My-first-blog-in-PHP-master)
    * Click on "Start the creation of VirtualHost"
    * Restart the WampServer services
------------------------------------------------------------------------------------------------------------------------------------------ 
  * Create an administrator :
    * Register on the site
    * Access phpMyAdmin
    * Go to the “user” table
    * Change the role of the registered user from 0 to 1.
    * Identify yourself again on the site

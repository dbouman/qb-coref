<?php
$config = array();

/* Absolute path to web site - must end with a slash */
$config['abs_path'] = 'http://example.com/qb-coref/';

/* Gold standard  - which username to check accuracy against */
$config['gold_user'] = '';

/* Database settings */
$config['dbhost'] = 'localhost';
$config['dbuser'] = '';
$config['dbpass'] = '';
$config['dbtype'] = 'mysql';
$config['dbname'] = '';

/* Contact us email */
$config['contact_email'] = '';
$config['contact_name'] = '';

/* SMTP settings */
$config['smtp_host'] = '';
$config['smtp_port'] = '';
$config['smtp_user'] = '';
$config['smtp_pass'] = '';
$config['smtp_secure'] = 'tls';  // ssl also accepted
?>
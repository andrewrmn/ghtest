<?php

function gifted_autoload( $class_name ) {
    $filename = FL_CHILD_THEME_DIR . '/includes/' . str_replace('\\','/', $class_name) . '.php';
    if( file_exists( $filename )) {
        include_once $filename;
    }
}

spl_autoload_register('gifted_autoload');
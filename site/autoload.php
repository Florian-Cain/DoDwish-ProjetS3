<?php

if (file_exists('src')){
    spl_autoload_register(function ($class_name) {
		include 'src/' . $class_name . '.php';
	});
} 

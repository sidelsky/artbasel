<?php
/*
Plugin Name: Must use plugin loader
Description: Must use plugin loader - is a must have plugin which loads other must use plugins.
Author: Breakthrough Media
Version: 1.0
*/

// Opens the must-use plugins directory
$wpmu_plugin_dir = opendir(WPMU_PLUGIN_DIR);

// Lists all the entries in this directory
while (false !== ($entry = readdir($wpmu_plugin_dir))) {
	$path = WPMU_PLUGIN_DIR . '/' . $entry;

	// Is the current entry a subdirectory?
	if ($entry != '.' && $entry != '..' && is_dir($path)) {
		// Includes the corresponding plugin
		require($path . '/' . $entry . '.php');
	}
}

// Closes the directory
closedir($wpmu_plugin_dir);
?>

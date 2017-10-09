<?php
	//if uninstall not called from WordPress exit
	if (!defined('WP_UNINSTALL_PLUGIN'))
		exit();
	
	// delete option from options table
	delete_option('nam_hit_counter');
?>
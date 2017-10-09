<div class="wrap">
	<h2>Nam Hitcounter</h2>
	<div class="counter-meta">
		<img src="<?php echo plugins_url() .  '/nam-hitcounter-bp/images/cus-icon.png' ; ?>" alt="icon" /> 
		<p>Home page was visited : <span class="counter"><?php echo $data['counter']; ?></span> 
			<?php echo ( $data['counter'] > 1 ? "times." : "time." ); ?></p>
		<p>Active: <?php echo ( $data['active'] ? '<em class="enable">true</em>' : '<em class="disable">false</em>' ); ?></p>
		<?php if ( $data['time'] ): ?>
		<p>The latest access (Y-m-d h:m:s) : <em><?php echo $data['time']; ?></em></p>
		<?php endif; ?>
	</div>

	
	<!-- using admin_action_ . $_REQUEST['action'] hook in admin.php -->
	
	<form action="<?php echo admin_url( 'admin.php' ); ?>" method="post">
		<input type="hidden" name="action" value="nam_counter_action" />
		<p class="submit">
			<input id="reset" class="button button-primary" type="submit" value="Reset Counter" name="reset" />
			<?php if ( $data['active'] ) : ?>
			<input id="disable" class="button button-primary" type="submit" value="Disable Counter" name="disable" />
			<?php else : ?>
			<input id="enable" class="button" type="submit" value="Enable Counter" name="enable" />
			<?php endif; ?>
		</p>
	</form>
</div> <!-- end div.wrap -->
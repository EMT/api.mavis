<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2013, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>M.A.V.I.S. API</title>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body class="lithified">

	<div class="content">
		<?php echo $this->content(); ?>
	</div>

</body>
</html>
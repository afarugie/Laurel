<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Internal Server Error</title>
	</head>
	<body>
		<header>
			<h1><?=$uncaught_exception ? 'Internal Error: Uncaught Exception "'.get_class($backtrace).'"' : 'Internal Server Error '?></h1>
		</header>
		<main>
			<section class="error-description">
				<h2>
					<?=$error?>
				</h2>
				<p>
					<pre><?=$error_file?> on line <strong><?=$line?></strong></pre>
				</p>
			</section>	
			<section class="stack-trace">
				<h2>Stack Trace</h2>
				<p>
<pre>
<?php var_dump($backtrace); ?>
</pre>					
				</p>
			</section>
		</main>
		<footer>
			
		</footer>
	</body>
</html>

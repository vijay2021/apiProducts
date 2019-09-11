<html>
 <head>
  <title>Hello...</title>

  <meta charset="utf-8"> 

  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</head>
<body>
    <div class="container">
    <?php echo "<h1>Hi! Here we can find our rest api project.</h1>"; ?>
	<h2>urls's  are here:</h2>
	<ul>
		<li>For all products: http://localhost:8001/api/products/</li>
		<li>For Auth: http://localhost:8001/api/auth/</li>
		<li>For Auth User: http://localhost:8001/api/users/</li>
		<li>For Auth User Products GET Or POST: http://localhost:8001/api/users/products/ <b>here we have to send jwt value in header or body.</b></li>
		<li>For Auth User Products Delete: http://localhost:8001/api/users/products/?sku=skuProduct</li>
	
	</ul>
 </div>
</body>
</html>

<?php
	require('bv_inc.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Budvibes</title>
	<meta charset="utf-8" />
</head>
<body>
	<?php
		/*
		try{
			$pdo = new PDO(
					sprintf('mysql:host=%s;dbname=%s;port=%s;charset=%s',
							__DBHOST__,
							__DATABASE__,
							__DBPORT__,
							__DBCHARSET__
							),
							__DBUSER__,
							__DBPASS__
				);
		} catch(PDOException $e){
			echo 'Connection failed '.$e->getMessage();
			exit;
		}
		
		$sql = "SELECT id, username, slug, profile_pic, email, type, 
					  store_id, store_reg, store_state, verified 
					  FROM users WHERE slug= :user";
					  
			$statement = $pdo->prepare($sql);
			$statement->bindValue(':user', 'firefighternkc1', PDO::PARAM_STR);
			$statement->execute();
			$num = $statement->rowCount();
			$user = $statement->fetch(PDO::FETCH_ASSOC);
			echo $user['username'];
		*/	
	?>
	<h1>Hello</h1>
	<a href="/signup">Sign Up</a>
	<a href="/login">Log In</a>
</body>
</html>



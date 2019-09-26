<html>
	<head><title>Message Board</title></head>
	<body>
		<?php
			session_start();
			error_reporting(E_ALL);
			ini_set('display_errors','On');
			if(isset($_SESSION['user'])){				
				print 'Welcome '.$_SESSION['user'];
			} else{
				header("location: login.php");
			}
		?>
		<form method="GET" action="login.php">
			<input type="hidden" name="logout"/>
			<input type="submit" value="Logout"/>
		</form>
		<form method="POST" action="board.php">
			<textarea name="post" rows="5" cols="30"></textarea>
			</br>
			<input type="submit" name="postnew" value="New Post"/>
			</br></br>
			<table border="1">
				<?php
					try{
						$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
						$dbh->beginTransaction();
						if(isset($_POST['postnew'])){
							$dbh->exec('INSERT INTO posts VALUES("' .uniqid(). '","null","' . $_SESSION['user'] . '",now(),"' . $_POST["post"] . '")')
								or die(print_r($dbh->errorInfo(), true));
							$dbh->commit();
							header("Location: board.php");
							exit;
						}
						if(isset($_POST['reply'])){
							$dbh->exec('INSERT INTO posts VALUES("'.uniqid().'","'.$_GET["replyTo"].'","'.$_SESSION['user'].'",now(),"'.$_POST["post"].'")')
								or die(print_r($dbh->errorInfo(), true));
							$dbh->commit();
							header("location: board.php");
							exit;
						}
						$stmt = $dbh->prepare('SELECT * FROM posts, users WHERE posts.postedby = users.username ORDER BY posts.datetime DESC');
						$stmt->execute();
						print "<tr>";
						print "<th>Message ID</th>";
						print "<th>User Name</th>";
						print "<th>Full Name</th>";
						print "<th>Date</th>";
						print "<th>Time</th>";
						print "<th>Reply To</th>";
						print "<th>Message</th>";
						print "<th>Reply</th>";
						print "</tr>";
						while ($row = $stmt->fetch()) {
							print "<tr>";
							print "<td>".$row["id"]."</td>";
							print "<td>".$row["postedby"]."</td>";
							print "<td>".$row["fullname"]."</td>";
							print "<td>".explode(" ", $row["datetime"])[0]."</td>";
							print "<td>".explode(" ", $row["datetime"])[1]."</td>";
							print "<td>".$row["replyto"]."</td>";
							print "<td>".$row["message"]."</td>";
							print "<td><button type=\"submit\" name=\"reply\" formaction=\"board.php?replyTo=".$row["id"]."\">Reply</button></td>";
							print "</tr>";
						}
					} catch (PDOException $e) {
					  print "Error!: " . $e->getMessage() . "<br/>";
					  die();
					}
				?>
			</table>		
		</form>
	</body>
</html>

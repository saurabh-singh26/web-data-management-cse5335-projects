<!--
Project 6 - Submitted by Saurabh Singh - 1001568347 - sxs8347
References:
1. https://www.w3schools.com/php/php_file_upload.asp
-->
<?php

error_reporting(E_ALL);
ini_set('display_errors','On');

require_once 'demo-lib.php';
require_once  'DropboxClient.php';

$dropbox = new DropboxClient( array(
	// Put your key here
	'app_key'         => '',
	'app_secret'      => '',
	'app_full_access' => false,
) );

handle_dropbox_auth( $dropbox ); // see below

// Check if image has been posted to upload
if(isset($_POST["submit"])) {
	// print_r($_FILES);
	// Move the image from xampp/tmp folder to a temp dir, upload to dropbox and then remove the temp dir
	$tempDir = "__temp_image_dir__/";
	if(!is_dir($tempDir)){
		mkdir($tempDir);
	}
	// Move from xampp/temp to temp dir declared above
	if(!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $tempDir . $_FILES['fileToUpload']['name'])){
		die('Error uploading file');
	}
	$dropbox->UploadFile($tempDir . $_FILES["fileToUpload"]["name"]); // Upload to dropbox
	// Delete the file from the temp folder else rmdir will throw a warning
	unlink($tempDir . $_FILES['fileToUpload']['name']);
	// Now remove the temp dir
	rmdir($tempDir);
}

// Check if image has been posted to delete
if(isset($_POST["delete"])) {
	$dropbox->Delete($_POST["path"]); // Delete image from dropbox
	header("Location: album.php"); // Reload the same page to populate the updated list of images
}

function handle_dropbox_auth( DropboxClient $dropbox ) {
	/**
	 * Dropbox will redirect the user here
	 * @var string $return_url
	 */
	$return_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?auth_redirect=1";

	// first, try to load existing access token
	$bearer_token = demo_token_load( "bearer" );

	if ( $bearer_token ) {
		$dropbox->SetBearerToken( $bearer_token );
		// echo "loaded bearer token: " . json_encode( $bearer_token, JSON_PRETTY_PRINT ) . "\n";
	} elseif ( ! empty( $_GET['auth_redirect'] ) ) // are we coming from dropbox's auth page?
	{
		// get & store bearer token
		$bearer_token = $dropbox->GetBearerToken( null, $return_url );
		demo_store_token( $bearer_token, "bearer" );
	} elseif ( ! $dropbox->IsAuthorized() ) {
		// redirect user to Dropbox auth page
		$auth_url = $dropbox->BuildAuthorizeUrl( $return_url );
		die( "Authentication required. <a href='$auth_url'>Continue.</a>" );
	}
}

?>
<html>
	<head>
		<title>Photo Album App</title>
		<script>
			// Change the image source to dropbox generated URL and set display from none to block
			function display(imageSrc){
				document.getElementById("image").src = imageSrc;
				document.getElementById("image").style.display = "block";
			}
		</script>
	</head>
	<body>
		<!-- Form to upload an image -->
		<form action="" method="POST" enctype="multipart/form-data">
			Select image to upload:
			<input type="file" name="fileToUpload" id="fileToUpload">
			<input type="submit" value="Upload Image" name="submit">
		</form>
		</br>
		<!-- Image holder div -->
		<div><img id="image" height="250" width="250" style="display:none;"></div>
		</br>
		<?php
		// Retrieve file list from dropbox
		$files = $dropbox->GetFiles("",false);
		// print_r( $files );
		if ( ! empty( $files ) ) {
			echo "<b>Uploaded Files:</b></br></br>";
			echo "<table>";
			for ($x = 0; $x < count($files); $x++) {
				$name = $files[array_keys( $files )[$x]]->name; // Name of the file
				$path = $files[array_keys( $files )[$x]]->path; // Path of the file
				$imageSrc = $dropbox->GetLink($path , false); // Image URL of the file
				echo "<tr>";
				// Executing javascript using a href and passing the image URL to be displayed in the image section
				echo "<td style=\"vertical-align: top;\"><a href=\"javascript:display('$imageSrc');\">" . $name . "</span></td>";
				// Pass the image path to be deleted to album.php using form
				echo "<td><form method=\"post\"><input type='hidden' name='path' value='$path'><input type='submit' name='delete' value='Delete'></form>";
				echo "</td></tr>";
			}
			echo "</table>";
		}
		?>
	</body>
</html>
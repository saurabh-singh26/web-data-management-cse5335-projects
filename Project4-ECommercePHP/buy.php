<?php
	session_start();
	$api_key = "78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc";
	$trackingID = "7000610";	
	error_reporting(E_ALL);
	ini_set('display_errors','On');
?>

<html>
	<head>
		<title>Buy Products</title>
		<script>
			function getCategoryList(){
				<?php
				$listHost = "http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/json/CategoryTree?apiKey=".$api_key."&visitorUserAgent&visitorIPAddress&trackingId=".$trackingID."&categoryId=72&showAllDescendants=true";
				$category_list = file_get_contents($listHost);
				?>
				var categoryList = JSON.parse(<?php echo json_encode($category_list); ?>);
				console.log(categoryList.category)
				populateCategoryList(categoryList.category)
			}
			
			//We can create option and optgroup html tags inside this and add as innerHTML to obtain the required indentation
			function populateCategoryList(category){
				mainCategory = category.name
				subCategories = category.categories.category
				createOptionElementAndAdd(mainCategory, category.id, "products")
				createOptionElementAndAdd(mainCategory, category.id, "categories")
				for(var i=0;i<subCategories.length;i++){
					subCategoryName = subCategories[i].name
					subCategoryType = subCategories[i].contentType
					// console.log("Category >>>>> " + subCategoryName)
					createOptionElementAndAdd(subCategoryName, subCategories[i].id, "products")
					createOptionElementAndAdd(subCategoryName, subCategories[i].id, "categories")
					if(subCategoryType=="categories"){
						products = subCategories[i].categories.category
						for(var j=0;j<products.length;j++){
							productName = products[j].name
							// console.log("Product: " + productName)
							createOptionElementAndAdd(productName, products[j].id, "products")
						}
					}
				}
			}
			
			function createOptionElementAndAdd(text, value, type){
				if(type=="categories"){
					option = document.createElement("optgroup");
					option.label = text;
				} else {
					option = document.createElement("option");
					if(text=="Computers") option.selected = "selected"
					option.value = value;
					option.text = text; 
				}
				document.getElementById("categoryList").appendChild(option);
			}	
		</script>
	</head>
	
	<body onload=getCategoryList()>
		Shopping Basket: 
		</br>
		<table id="basketTable" border="1">
			<?php
				if (isset($_GET["buy"])){
					$buyID = $_GET["buy"];
					if(!isset($_SESSION["cart"])){
						$_SESSION["cart"]= array();
					}
					foreach ($_SESSION["search"] as $id => $product){
						if($id==$buyID){
							$_SESSION["cart"][(string)$buyID] = array("name"=>$product["name"], "price"=>$product["price"]);
						}
					}
				}
				
				if (isset($_GET["delete"])){
					$deleteID = $_GET["delete"];
					unset($_SESSION['cart'][$deleteID]);
				}
				
				if (isset($_GET["clear"])){
					$_SESSION['cart'] = array();
				}
				
				displayCart();
				
				function displayCart(){
					$price = 0.00;
					if(isset($_SESSION["cart"])){
						foreach($_SESSION["cart"] as $id => $product) {
							print "<tr><td>";
							print $product["name"]."</a></td><td>";
							print $product["price"]."$</td><td>";
							print "<a href=\"buy.php?delete=".$id."\">delete</a></td></tr>";
							$price += (float)$product['price'];
						}
					}
					
					$_SESSION["price"] = $price;
				}
			?>
		</table>
		</br>
		Total: $<?php print $_SESSION["price"]; ?>
		</br>
		<form method="GET">
			<input type="hidden" name="clear" value="1"/>
			<input type="submit" value="Empty Basket"/>
		</form>
		<form method="GET">
			<fieldset>
				<legend>Find products:</legend>
					<table>
						<tr>
							<td>Category:</td>
							<td>
								<select id="categoryList" name="category">
								</select>
							</td>
							<td>Search keywords: <input type="text" name="search" id="search"></td>
						</tr>
					</table>
				<input type="submit" value="Search">
			</fieldset>
		</form>
		</br>
		<table border="1">
			<?php
				if (isset($_GET["category"]) && isset($_GET["search"])){
					$categoryID = $_GET["category"];
					$query = urlencode($_GET["search"]);
					$host = "http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=".$api_key."&trackingId=".$trackingID."&categoryId=".$categoryID."&keyword=".$query."&numItems=20";
					// print $host;
					$searchResult = file_get_contents($host);
					$searchResultXml = new SimpleXMLElement($searchResult);
					$_SESSION["search"] = array();
					foreach ($searchResultXml->categories->category as $category){
						foreach ($category->items->offer as $item){
							print "<tr><td>";
							print "<a href=\"buy.php?buy=".$item['id']."\">".$item->name."</a></td><td>";
							print $item->basePrice."</td><td>";
							print $item->description."</td></tr>";
							$_SESSION['search'][(string)$item['id']] = array("name"=>(string)$item->name, "price"=>(float)$item->basePrice);
						}
					}
				}
			?>
		</table>
</body>
</html>

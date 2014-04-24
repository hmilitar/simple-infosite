<?php
error_reporting(E_ALL);
$page = $_GET['q'];
if(!isset($page)){
	$page = "contact.html";
}
$path = "./";
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Basic Info Site</title>
	<style>
	#sidebar {
	  position: fixed;
	  top: 40px;
	  left: 40px;
	  width: 300px;
	  background: #edf5fa;
	  border-right:1px solid #aaa;
	}
	#content {
	  margin: 0 40px 40px 380px;
	}
	li.listp ul{
		display:none;
	}
	.a_hand{
		color: #aaa;
	}
	</style>
	<script src="http://code.jquery.com/jquery-1.8.3.min.js" type="text/javascript"></script>
</head>
<body>

	<header>
		<figure>
			Put the Header Here
		</figure>

		<!--<nav>
			<ul>
				<li></li>
			</ul>
		</nav>-->
	</header>

<div id="sidebar">
<?php
$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
$dom = new DomDocument("1.0");
$list = $dom->createElement("ul");
$list->setAttribute('id',"root");
$list->setAttribute('class',"menu");
$dom->appendChild($list);
$node = $list;
$depth = 0;
$lictr = 0;
foreach($objects as $name => $object){
	$lictr++;
	if($object->getFilename() != "index.php" && (substr($object->getFilename(),0,1) != ".")){
	 	$p = $object->getPath();
	 	$p = ltrim($p, "./");
	 	$fileonly = trim(basename($object->getFilename(), ".html").PHP_EOL);
	 	$link = $dom->createElement('a',$fileonly);
	    if(substr($object->getFilename(),-4) == "html")
	        $link->setAttribute("href","?q=".$p."/".$object->getFilename());
	    else
	        $link->setAttribute("href","javascript:void(0);");
	    if ($objects->getDepth() == $depth){
	//the depth hasnt changed so just add another li
	        $li = $dom->createElement('li');
	        $li->setAttribute('class','listp');
	        $li->appendChild($link);
	        $node->appendChild($li);
	    }
	    elseif ($objects->getDepth() > $depth){
	//the depth increased, the last li is a non-empty folder
	        $li = $node->lastChild;
	        $ul = $dom->createElement('ul');
	        $li->appendChild($ul);
	        $childid = $dom->createElement('li');
	        //$childid->setAttribute('class','listp');
	        $childid->appendChild($link);
	        $ul->appendChild($childid);
	        $node = $ul;
	    }
	    else{
	//the depth decreased, going up $difference directories
	        $difference = $depth - $objects->getDepth();
	        for ($i = 0; $i < $difference; $difference--){
	            $node = $node->parentNode->parentNode;
	        }
	        $li = $dom->createElement('li');
	        $li->setAttribute('class','listp');
	        $li->appendChild($link);
	        $node->appendChild($li);
	    }
	    $depth = $objects->getDepth();
	}
}
/*
echo '<pre>';
print_r($dom->saveHtml());
echo '</pre>';
*/
echo $dom->saveHtml();
?>
</div>
<div id="content">
        <!-- MAIN CONTENT HERE -->
        <?php
        if(substr($page,-4) == "html"){
        	include('./'.$page);
        } else {
        	echo "You have selected a category named : ".strtoupper(substr($page,1));
        }
        ?>
</div>
</body>
<script type="text/javascript">
	jQuery(function () {
			$('li.listp').click(function() {
			   $('li.listp').not(this).find('ul').hide();
			   $(this).find('ul').toggle();
			});
	});
</script>
</html>

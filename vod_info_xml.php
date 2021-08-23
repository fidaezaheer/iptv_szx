<?php

	header("Content-Type: text/html; charset=utf-8");

	$id = $_GET["id"];
	$json = file_get_contents("http://hd.82ucc.com/iptvtools/movie?id=" . $id);
	$rows = json_decode($json);
	//echo $rows->list;
	
	//$rows = json_decode($rows->list);
	
	$doc = new DOMDocument('1.0', 'utf-8');  // 声明版本和编码 
	$doc->formatOutput = true; 
	$r = $doc->createElement("root"); 
	$doc->appendChild($r); 
 
   	 	$b = $doc->createElement("item"); 
 
 		$moive = $rows->movie;
    	
		$name = $doc->createElement("name"); 
    	$name->appendChild($doc->createTextNode($moive->name)); 
    	$b->appendChild($name); 
 
    	$image = $doc->createElement("image"); 
    	$image->appendChild($doc->createTextNode($moive->image)); 
    	$b->appendChild($image); 
		
		$updateTime = $doc->createElement("updateTime"); 
    	$updateTime->appendChild($doc->createTextNode($moive->updateTime)); 
    	$b->appendChild($updateTime);
		
		$filmCountry = $doc->createElement("filmCountry"); 
    	$filmCountry->appendChild($doc->createTextNode($moive->filmCountry)); 
    	$b->appendChild($filmCountry);

		$hits = $doc->createElement("hits"); 
    	$hits->appendChild($doc->createTextNode($moive->hits)); 
    	$b->appendChild($hits);
					
		$intro = $doc->createElement("intro"); 
    	$intro->appendChild($doc->createTextNode($moive->intro)); 
    	$b->appendChild($intro);
			
		$filmStar = $doc->createElement("filmStar"); 
    	$filmStar->appendChild($doc->createTextNode($moive->filmStar)); 
    	$b->appendChild($filmStar);
							
		$c = $doc->createElement("chapters"); 
    	$chapters = $rows->chapters;
		
		foreach($chapters as $chapter) 
		{
			$d = $doc->createElement("chapter"); 
			
			$tudouTv = $doc->createElement("tudouTv"); 
    		$tudouTv->appendChild($doc->createTextNode($chapter->tdIID)); 
    		$d->appendChild($tudouTv); 
			
			$sname = $doc->createElement("sname"); 
    		$sname->appendChild($doc->createTextNode($chapter->name)); 
    		$d->appendChild($sname); 
			
			$id = $doc->createElement("id"); 
    		$id->appendChild($doc->createTextNode($chapter->id)); 
    		$d->appendChild($id); 
			
			$c->appendChild($d);
		}
		
		
		
		
    	$b->appendChild($c);
						
    	$r->appendChild($b); 
		

	echo $doc->saveXML(); 

?>
<!DOCTYPE html>
<?php
include_once "cn_lang.php";
include_once "common.php";
$sql = new DbSql();
$sql->login();
?>

<?php

function xml_free($doc,$xml_file_array)
{
	$items = $doc->getElementsByTagName( "item" );
	foreach( $items as $item )
 	{
		$name = $item->getElementsByTagName("name");
		$namevalue = $name->item(0)->nodeValue;
	
		$mtime = $item->getElementsByTagName("mtime");
		$mtimevalue = $mtime->item(0)->nodeValue;

		//echo "$namevalue - $mtimevalue - \n";
		//echo "<br/>";
		$item_array = str_replace("../gemini-iptv/","",$namevalue);
		//echo $item_array;
		//echo "<br/>";
		$xml_file_array[$item_array] = $mtimevalue;
		
		if(strcmp($item_array,"admin/gemini.php") != 0 && strcmp($item_array,"admin/geminip.php") != 0 &&
			strcmp($item_array,"gemini.php") != 0 && strcmp($item_array,"geminip.php") != 0 && 
			strcmp($item_array,"admin/update_post.php") != 0 && strcmp($item_array,"admin/connect.php") != 0 && 
			(get_extension($item_array) == "php" || get_extension($item_array) == "html" || get_extension($item_array) == "htm" ||
				get_extension($item_array) == "js" || get_extension($item_array) == "xml" || get_extension($item_array) == "dat"
			|| get_extension($item_array) == "Dat" ))
		{
			
			{
			if(isset($xml_file_array[$item_array]))
			{
				if(intval($xml_file_array[trim($item_array)]) < intval($mtimevalue))
				{
					echo "<tr>";
					echo "<td style='align:center; vertical-align:middle; text-align:center;'>";
					echo "<div class='controls'>";
					echo "<input class='uniform_on' name='file_checkbox' type='checkbox' value='" . $item_array . "' checked/>";
					echo "</div>";
					echo "</td>";
					echo "<td style='vertical-align:middle; text-align:center;font-weight:bold;color:red;'>". $item_array ."</td>";
					echo "<td style='vertical-align:middle; text-align:center;font-weight:bold;color:red;'>" . date("Y-m-d H:i:s",intval($mtimevalue)) . "</td>";
					if(isset($xml_file_array[$item_array]))
						echo "<td style='vertical-align:middle; text-align:center;font-weight:bold;color:red;'>".date("Y-m-d H:i:s",intval($xml_file_array[trim($item_array)]))."</td>";
					else
						echo "<td style='vertical-align:middle; text-align:center;font-weight:bold;color:red'>" . $lang_array["update_list_text3"] . "</td>";
					echo "</tr>";
				}
				else
				{
					/*
					echo "<tr>";
					echo "<td>" ."<input name='file_checkbox' type='checkbox' value='" . $item_array . "' />" . "</td>";
					echo "<td>". $item_array ."</td>";
					echo "<td>" . date("Y-m-d H:i:s",intval($mtimevalue)) . "</td>";
					if(isset($xml_file_array[$item_array]))
						echo "<td>".date("Y-m-d H:i:s",intval($xml_file_array[trim($item_array)]))."</td>";
					else
						echo "<td>更新</td>";
					echo "</tr>";
					*/
				}
			}
			else
			{
				echo "<tr>";
				echo "<td style='align:center; vertical-align:middle; text-align:center;'>";
				echo "<div class='controls'>";
				echo "<input class='uniform_on' name='file_checkbox' type='checkbox' value='" . $item_array . "' checked/>";
				echo "</div>";
				echo "</td>";
				echo "<td style='vertical-align:middle; text-align:center;font-weight:bold;color:red;'>". $item_array ."</td>";
				echo "<td style='vertical-align:middle; text-align:center;font-weight:bold;color:red;'>" . date("Y-m-d H:i:s",intval($mtimevalue)) . "</td>";
				if(isset($xml_file_array[$item_array]))
					echo "<td style='vertical-align:middle; text-align:center;font-weight:bold;color:red;'>".date("Y-m-d H:i:s",intval($xml_file_array[trim($item_array)]))."</td>";
				else
					echo "<td style='vertical-align:middle; text-align:center;font-weight:bold;color:red;'>" . "file name" . "</td>";
				echo "</tr>";
			}
			
			}
		}
	}
	
}

$xml_file_array = array();

function tree($directory)
{
	$mydir = dir($directory);
	while($file = $mydir->read())
	{
		if((is_dir("$directory/$file")) AND ($file!=".") AND ($file!=".."))
		{
			tree("$directory/$file");
		}
		else
		{
			if($file!="." && $file!="..")
			{
				$directory1 = str_replace("..//","",$directory);
				$directory1 = str_replace("../","",$directory1);
				
				$file1 = str_replace("..//","",$file);
				$file1 = str_replace("../","",$file1);
				
				$ctime = filemtime($directory . "/" . $file);
				
				if(strlen($directory1) > 0)
					$item_array = $directory1 . "/" . $file1;
				else
					$item_array = $file1;
					
				global $xml_file_array;
				$xml_file_array[trim($item_array)] = $ctime;
				//echo $item_array . "@" . $xml_file_array[trim($item_array)] . "<br/>";
			}
		}
	}
	
	global $xml_file_array;
	return $xml_file_array;
}

?>

<html>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <head>
        <title><?php echo $lang_array["left_title"] ?></title>
        <!-- Bootstrap -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
        <link href="assets/styles.css" rel="stylesheet" media="screen">
        <link href="assets/DT_bootstrap.css" rel="stylesheet" media="screen">
        <script src="vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    </head>
    
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["update_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <div class="table-toolbar">
                                      <div class="btn-group">
                                         <a href="#"><button class="btn btn-success" onClick="update_file()"><?php echo $lang_array["update_list_text2"] ?></button></a>
                                      </div>
                                   </div>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
        									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["update_list_text6"] ?></th>
            								<th width="30%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["update_list_text3"] ?></th>
            								<th width="30%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["update_list_text4"] ?></th>
          									<th width="30%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["update_list_text5"] ?></th>
                                            </tr>
                                        </thead>
                                        
<?php
		set_zone();
		
		$xml_file_array = tree("../");
		
		$doc = new DOMDocument();  // 声明版本和编码 
		$xml = file_get_contents("http://www.gemini-iptv.com/update.php");
		$doc->loadXML($xml);

		echo "<tbody>";
		xml_free($doc,$xml_file_array);
		echo "</tbody>";
?>                                     
                                    </table>
                                    
                                    <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_add_text16"] ?>" onclick="selectAll()"/>
            						<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_add_text17"] ?>" onclick="noAll()"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <FORM name="authform_file" action="update_post.php" method="post">  
    	<input name="files" id="files" type="hidden" value=""/>
    	</Form>
        <!--/.fluid-container-->

        <link href="vendors/datepicker.css" rel="stylesheet" media="screen">
        <link href="vendors/uniform.default.css" rel="stylesheet" media="screen">
        <link href="vendors/chosen.min.css" rel="stylesheet" media="screen">

        <link href="vendors/wysiwyg/bootstrap-wysihtml5.css" rel="stylesheet" media="screen">
        
        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/jquery.uniform.min.js"></script>
        <script src="vendors/chosen.jquery.min.js"></script>
        <script src="vendors/bootstrap-datepicker.js"></script>

        <script src="vendors/wysiwyg/wysihtml5-0.3.0.js"></script>
        <script src="vendors/wysiwyg/bootstrap-wysihtml5.js"></script>

        <script src="vendors/wizard/jquery.bootstrap.wizard.min.js"></script>

		<script type="text/javascript" src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>
		<script src="assets/form-validation.js"></script>
        
		<script src="assets/scripts.js"></script>
        
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        
        <script>
		
		jQuery(document).ready(function() {   
	   		FormValidation.init();
		});
	

        $(function() {
            $(".datepicker").datepicker();
            $(".uniform_on").uniform();
            $(".chzn-select").chosen();
            $('.textarea').wysihtml5();

            $('#rootwizard').bootstrapWizard({onTabShow: function(tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index+1;
                var $percent = ($current/$total) * 100;
                $('#rootwizard').find('.bar').css({width:$percent+'%'});
                // If it's the last tab then hide the last button and show the finish instead
                if($current >= $total) {
                    $('#rootwizard').find('.pager .next').hide();
                    $('#rootwizard').find('.pager .finish').show();
                    $('#rootwizard').find('.pager .finish').removeClass('disabled');
                } else {
                    $('#rootwizard').find('.pager .next').show();
                    $('#rootwizard').find('.pager .finish').hide();
                }
            }});
            $('#rootwizard .finish').click(function() {
                alert('Finished!, Starting over!');
                $('#rootwizard').find("a[href*='tab1']").trigger('click');
            });
        });
		
        $(function() {
            
        });
		
		function get_type_checkbox_value()
		{
			var value = "";
			var value_array = new Array();
			var type_checkboxs = document.getElementsByName("file_checkbox");
			for(var i = 0; i < type_checkboxs.length; i++)
			{
				if(type_checkboxs[i].type == "checkbox" && type_checkboxs[i].checked)
				{
					value_array.push(type_checkboxs[i].value);
				}
			}
	
			for(var ii = 0; ii < value_array.length; ii++)
			{
				value = value + value_array[ii];
				if(ii < value_array.length - 1)
					value = value + "|";
			}
	
			//alert(value);
			return value;
		}
		
		function update_file()
		{
			if(confirm("<?php echo $lang_array["update_list_text8"] ?>") == true)
  			{
				document.authform_file.files.value = get_type_checkbox_value();
      			document.authform_file.submit()	
				
				//var url = "update_file.php?name=" + name;
				//window.location.href = url;
  			}
		}

		function selectAll() //全选
		{
			var objs = document.getElementsByName('file_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = true;
				}
			}
		} 

		function noAll() //全选
		{
			
			var objs = document.getElementsByName('file_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = false;
				}
			}
		}
		
        </script>
    </body>

</html>
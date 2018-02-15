<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$GLOBALS["page_array"] = array(["[jumbotron]","",""],["#Welcome to the site","![Welcome banner](http://daxushequ.com/data/out/24/img60426245.jpg)",""],["this site is all driven by a spreadsheet","",""],["","",""],["[card]","[card]","[card]"],["#Column 1","#Column 2","#Column 3"],["card heading","Some random text ","Some random text "],["###Sub heading","###Sub heading","###Sub heading"],["Some more information about the things","Some more information about the things","Some more information about the things"],["","",""],["","",""],["[card]","",""],["###Some other info","###Some other info on column 2",""],["Maxime doloribus, asperiores porta, fugiat cursus quam, nonummy habitasse ac in, quasi. Ultrices quo eaque? Quisque, sollicitudin architecto irure tristique, et, sit voluptatibus elementum, placerat luctus quisque, a voluptates viverra, magna feugiat scelerisque varius aliquid aliquid litora distinctio soluta dapibus penatibus molestias adipisci porro cumque sodales cupidatat! Autem! Dolorum aptent est molestiae porro debitis? Accumsan blanditiis, inventore, ligula est mollis mollitia tincidunt bibendum feugiat suspendisse pulvinar faucibus cum commodi fusce quibusdam porro, sapiente laoreet, perspiciatis.","Nesciunt accusamus fugit deleniti nec recusandae arcu sapiente massa? In excepturi nesciunt, curabitur penatibus tristique, exercitationem dolorem alias error aliquip habitant. Nibh erat illum cumque cumque pharetra aliquid, vero tempus malesuada sociis tempus. Odio rhoncus mollitia. Doloremque fugit cupiditate non! Praesent cupidatat? Est eos facere, minim corrupti, nihil repellendus convallis cumque quae? Architecto interdum in dis rutrum, torquent exercitationem? Commodo pretium hymenaeos molestie, modi repellendus euismod vestibulum dignissim consequatur perspiciatis aliquam interdum? Magnam impedit, scelerisque.",""]);

$GLOBALS["componentRows"] = array();

$GLOBALS["this_different"] = false;
$GLOBALS["next_different"] = false;
$GLOBALS["this_same"] = false;
$GLOBALS["next_same"] = false;
$GLOBALS["is_this_last"] = false;

$GLOBALS["result"] = "";

function main(){
 setComponentRows();
    for ($i = 0; $i < count($GLOBALS["componentRows"]); $i++) {
        //start container, row and col
        //start component, row 
        //start col -- awesome step
        //print everything in current col
        //close col
        //if in current component row next col is empty 
        //close col and start next one -- then goto awesome step
        //else
        //close col, row, component -- then goto awesome step
        //close col, row, container

        //--------------
        $GLOBALS["result"] .= $section_start . $row_start;

        for ($j = 0; $j < count($page_array[0]); $j++) {//page_array[0] means number of columns in array
            setDeciders($GLOBALS["componentRows"][$i],$j);
            if ($GLOBALS["this_different"] && $GLOBALS["next_different"]) {
                $GLOBALS["result"] .= $col_start;
                $GLOBALS["result"] .= component_start($GLOBALS["componentRows"][$i],$j);
                $GLOBALS["result"] .= $row_start;
                print_col_content($i,$j);
                $GLOBALS["result"] .= $row_end;
                $GLOBALS["result"] .= component_end($GLOBALS["componentRows"][$i],$j);
                $GLOBALS["result"] .= $col_end;
            }
            if ($GLOBALS["this_different"] && $GLOBALS["next_same"]) {
                $GLOBALS["result"] .= $col_start;
                $GLOBALS["result"] .= component_start($GLOBALS["componentRows"][$i],$j);
                $GLOBALS["result"] .= $row_start;
                print_col_content($i,$j);
            }
            if ($GLOBALS["this_same"] && $GLOBALS["next_same"]) {
                print_col_content($i,$j);
            }
            if ($GLOBALS["this_same"] && $GLOBALS["next_different"]) {
                print_col_content($i,$j);
                $GLOBALS["result"] .= $row_end;
                $GLOBALS["result"] .= component_end($GLOBALS["componentRows"][$i],$j);
                $GLOBALS["result"] .= $col_end;
            }
        }
        $GLOBALS["result"] .= $row_end . $section_end;
    }
    echo $GLOBALS["result"];
}

function print_col_content($i,$j){
 if (getComponentsHTML(componentRows[$i],$j)!="") {
     $GLOBALS["result"] .= $col_start;
     $GLOBALS["result"] .= getComponentsHTML(componentRows[$i],$j);
     $GLOBALS["result"] .= $col_end;
 }
}

function setDeciders($row,$col) {

 //first check if its the last column
 $no_of_cols = count(page_array[$row]);
 if($col == $no_of_cols - 1){//-1 because col started from zero
  $GLOBALS["is_this_last"] = true;
  $GLOBALS["this_different"] = false;
  $GLOBALS["next_different"] = false;
  $GLOBALS["this_same"] = false;
  $GLOBALS["next_same"] = false;
 }
 else{
  $GLOBALS["is_this_last"] = false;
 }

 $this_col = $GLOBALS["page_array[$row][$col]"];
 $next_col = $page_array[$row][$col+1];

 if (isThisComponentName($this_col)) {
  $GLOBALS["this_different"] =true;
  $GLOBALS["this_same"] = false;
 }
 else{
  $GLOBALS["this_different"] =false;
  $GLOBALS["this_same"] = true;
 }
 if ($GLOBALS["is_this_last"] || isThisComponentName($next_col)) {
  $GLOBALS["next_different"] =true;
  $GLOBALS["next_same"] = false;
 }
 else{
  $GLOBALS["next_different"] =false;
  $GLOBALS["next_same"] = true;
 }
}

function getComponentsHTML($row, $col) {
 $local_result = "";
 $row++;
 while (count($GLOBALS["page_array"]) != $row && $GLOBALS["page_array[$row][$col]"]!="" ) {
  $local_result .= $GLOBALS["page_array[$row][$col]"];
  $row++;
 }
 return $local_result;
}

function setComponentRows() {
 for ($i = 0; $i < count($GLOBALS["page_array"]); $i++) {
     if (isThisComponentName($page_array[$i][0])) {
      array_push($GLOBALS["componentRows"],$i);
     }
 }
}

function isThisComponentName($string) {
 echo $string;
 if (substr($string,0,1)=="[" && substr($string,-1)=="]") {
     return true;
 }
 return false;
}

function component_start($row, $col) {
 return eval($GLOBALS["page_array[$row][$col]"].slice(1,-1)."_start");
}

function component_end($row, $col) {
 if($GLOBALS["this_same"]){
  $col--;
     while ($GLOBALS["page_array[$row][$col]"]=="") {
      $col--;
     }
 }
 return eval($GLOBALS["page_array[$row][$col]"].slice(1,-1)."_end");
}

main();
?>
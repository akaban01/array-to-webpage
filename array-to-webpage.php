<?php
echo "h1";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$GLOBALS["page_array"] = array(["[jumbotron]","",""],["#Welcome to the site","![Welcome banner](http://daxushequ.com/data/out/24/img60426245.jpg)",""],["this site is all driven by a spreadsheet","",""],["","",""],["[card]","[card]","[card]"],["#Column 1","#Column 2","#Column 3"],["card heading","Some random text ","Some random text "],["###Sub heading","###Sub heading","###Sub heading"],["Some more information about the things","Some more information about the things","Some more information about the things"],["","",""],["","",""],["[card]","",""],["###Some other info","###Some other info on column 2",""],["Maxime doloribus, asperiores porta, fugiat cursus quam, nonummy habitasse ac in, quasi. Ultrices quo eaque? Quisque, sollicitudin architecto irure tristique, et, sit voluptatibus elementum, placerat luctus quisque, a voluptates viverra, magna feugiat scelerisque varius aliquid aliquid litora distinctio soluta dapibus penatibus molestias adipisci porro cumque sodales cupidatat! Autem! Dolorum aptent est molestiae porro debitis? Accumsan blanditiis, inventore, ligula est mollis mollitia tincidunt bibendum feugiat suspendisse pulvinar faucibus cum commodi fusce quibusdam porro, sapiente laoreet, perspiciatis.","Nesciunt accusamus fugit deleniti nec recusandae arcu sapiente massa? In excepturi nesciunt, curabitur penatibus tristique, exercitationem dolorem alias error aliquip habitant. Nibh erat illum cumque cumque pharetra aliquid, vero tempus malesuada sociis tempus. Odio rhoncus mollitia. Doloremque fugit cupiditate non! Praesent cupidatat? Est eos facere, minim corrupti, nihil repellendus convallis cumque quae? Architecto interdum in dis rutrum, torquent exercitationem? Commodo pretium hymenaeos molestie, modi repellendus euismod vestibulum dignissim consequatur perspiciatis aliquam interdum? Magnam impedit, scelerisque.",""]);

$componentRows = array();

$this_different = false;
$next_different = false;
$this_same = false;
$next_same = false;
$is_this_last = false;

$result = "";

function main(){
 echo "in main";
 setComponentRows();
    for ($i = 0; $i < count($componentRows); $i++) {
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
        $result .= $section_start . $row_start;

        for ($j = 0; $j < count($page_array[0]); $j++) {//page_array[0] means number of columns in array
            setDeciders($componentRows[$i],$j);
            if ($this_different && $next_different) {
                $result .= $col_start;
                $result .= component_start($componentRows[$i],$j);
                $result .= $row_start;
                print_col_content($i,$j);
                $result .= $row_end;
                $result .= component_end($componentRows[$i],$j);
                $result .= $col_end;
            }
            if ($this_different && $next_same) {
                $result .= $col_start;
                $result .= component_start($componentRows[$i],$j);
                $result .= $row_start;
                print_col_content($i,$j);
            }
            if ($this_same && $next_same) {
                print_col_content($i,$j);
            }
            if ($this_same && $next_different) {
                print_col_content($i,$j);
                $result .= $row_end;
                $result .= component_end($componentRows[$i],$j);
                $result .= $col_end;
            }
        }
        $result .= $row_end . $section_end;
    }
    echo $result;
}

function print_col_content($i,$j){
 if (getComponentsHTML(componentRows[$i],$j)!="") {
     $result .= $col_start;
     $result .= getComponentsHTML(componentRows[$i],$j);
     $result .= $col_end;
 }
}

function setDeciders($row,$col) {

 //first check if its the last column
 $no_of_cols = count(page_array[$row]);
 if($col == $no_of_cols - 1){//-1 because col started from zero
  $is_this_last = true;
  $this_different = false;
  $next_different = false;
  $this_same = false;
  $next_same = false;
 }
 else{
  $is_this_last = false;
 }

 $this_col = $GLOBALS["page_array[$row][$col]"];
 $next_col = $page_array[$row][$col+1];

 if (isThisComponentName($this_col)) {
  $this_different =true;
  $this_same = false;
 }
 else{
  $this_different =false;
  $this_same = true;
 }
 if ($is_this_last || isThisComponentName($next_col)) {
  $next_different =true;
  $next_same = false;
 }
 else{
  $next_different =false;
  $next_same = true;
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
      array_push($componentRows,$i);
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
 if($this_same){
  $col--;
     while ($GLOBALS["page_array[$row][$col]"]=="") {
      $col--;
     }
 }
 return eval($GLOBALS["page_array[$row][$col]"].slice(1,-1)."_end");
}

main();
?>
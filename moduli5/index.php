<?php
 $dog=array(
    array('Husky','Mexico',20),
    array('Bulldog','Siberia',15)
 );
//  echo $dog[0][0] . " Origin: " . $dog[0][1]. " Life Span:" . $dog[0][2]."<br>";
//  echo $dog[1][0] . " Origin: " . $dog[1][1]. " Life Span:" . $dog[1][2]."<br>";

 for($row=0;$row<2;$row++){
    echo "<p><b> Row Number $row</b></p>";
    echo "<ul>";
    for($col=0;$col<3;$col++){
        echo "<li>".$dog[$row][$col]."</li>";
    }
    echo "</ul>";
 }
 
 $arrays=array(
    array(1,2,3),
    array(1,2,3),
    array(1,2,3)
 );

 for($i=1;$i<4;$i++){
    for($j=1;$j<4;$j++){
        echo "Array:$i Element:$j <br>";
    }
 }

 for($i=1;$i<4;$i++){
    for($j=1;$j<4;$j++){ 
        echo "*";
    }
    echo "<br>";
 }

 $grade=array(
    "Matematike"=>"5"
    "Art"=>"5"
    "Histori"=>"5"
    "Muzik"=>"5"
 );
 echo "Math grade is:" . $grade['Math']. '<br>';

 foreach($grade as $subject=>$grade){
    echo "Subject:" . $subject. ",Grade:"$grade;
    echo "<br>";
 }
?>
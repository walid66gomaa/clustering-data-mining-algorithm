
<style type="text/css">
  
           body {
  background: #EAEBEC;
  background-size: 100% 100%;

  background-image: url(Mining.jpg);
  background-attachment: fixed;
  background-repeat: no-repeat;
}

.div1 {
             width: 100%;
             font-family: "Andale Mono",monospace;
             margin-top: 40px ;
             font-size: 20px;
             border: 1px solid red;
              color: #fff;
             padding: 10px;
             
             border: solid 1px #3A4655;
  box-shadow: 0 8px 50px -7px black;
   background: #3A4655;
         }
         span
         {
        border-right: 1px solid black;
        box-shadow: 0 8px 50px -7px black;
    padding: 2px;
  margin: 5px;
  margin-top: 100px;
         }

 .outlier
 {
  border: 1px solid black;
  padding: 2px;
  margin: 2px;
  box-shadow: 0 8px 50px -7px black;
 }
</style>

<div class="div1">
    
<?php

class Cluster 
{ 
	public $cent=array();
	public $points=array();	
}
    
function distance($point1,$point2)
{  
	$size=sizeof($point1);


	$sum=0;
	for ($i=1; $i <$size ; $i++) { 
		$sum+=abs($point1[$i]-$point2[$i]);
		
	}
	return $sum;
}

//////////////////////////////////////////////////////////////////////////////
function findMeaanOfSomePoint($points,$ind,$allData)
{  $sum=0;
	//echo "string ".$points[2];
  for ($i=0; $i <sizeof($points) ; $i++) { 

  	    $sum+=$allData[$points[$i]-1][$ind]; 
  	       
  }
  if(sizeof($points)==0)
  {
  	return $sum;
  }
  return $sum/sizeof($points);

}

function findNewMean($clustersArr2,$allData)
		{
			

			$size=sizeof($clustersArr2[0]->cent); //101

           for ($i=0; $i <sizeof($clustersArr2) ; $i++) { 
           	   $points=$clustersArr2[$i]->points;
           	     for ($j=1; $j <$size ; $j++) { 
                   
       $clustersArr2[$i]->cent[$j]=findMeaanOfSomePoint($points,$j,$allData);

           	     	
           	     }
           	
           }
       return $clustersArr2;
		}

	function MakeTwoarrayEquals($clustersArr,&$clustersArr2)
	{
			for ($i=0; $i <sizeof($clustersArr) ; $i++) { 
				  	$cluster1=new Cluster();
				  	$cluster1->cent=$clustersArr[$i]->cent;
				  	$cluster1->points=$clustersArr[$i]->points;
				  	array_push($clustersArr2, $cluster1);

				  }
	}
//////////////////////////////////////////////////////////////////////////////////
$NumOfCluster=$_POST['NumOfCluster'];
$allData=array();
$file = fopen("Genes Data Set.csv","r");

while(! feof($file))
  {

  	$line=fgetcsv($file);
  
  array_push($allData,$line );
  }

fclose($file);

$randomCenID=array_rand($allData,$NumOfCluster);
//$randomCenID=array(6,12,20,32,40,41,49,51,88,94);

$clustersArr=array();	 //add centroied first time
	for ($i=0; $i < $NumOfCluster; $i++) { 
			
			$cluster=new Cluster();
			$cluster->cent=$allData[$randomCenID[$i]];
			array_push($clustersArr, $cluster);

	}
$numofitration=1;

while (true) {
	
	

   $size=sizeof($allData); // find cluster 
  
  for ($i=0; $i<$size ; $i++) { 
  
  	     $disarr=array();
         $point1=$allData[$i];
         for ($j=0; $j <$NumOfCluster; $j++) { 
         	
        $point2=$clustersArr[$j]->cent;
             

        $dis=distance($point1,$point2);
        array_push($disarr, $dis);

         }
         	
         min($disarr);
         $centNum=array_search(min($disarr), $disarr);
         array_push($clustersArr[$centNum]->points, $point1[0]);//$point1[0] first(ID)
	
  }
  array_pop($clustersArr[0]->points);
$clustersArr2=array();
  
   MakeTwoarrayEquals($clustersArr,$clustersArr2);

  
  $clustersArr2=findNewMean($clustersArr2,$allData);
  
       
  if ($clustersArr == $clustersArr2) {
  	echo "Number Of Itration = ".$numofitration."<br>";
  	break;
  }
  else
   {
    $numofitration++;
  $clustersArr=array();
   	MakeTwoarrayEquals($clustersArr2,$clustersArr);
   	for ($i=0; $i <sizeof($clustersArr) ; $i++) { 
				  $clustersArr[$i]->points=array();

				  }
   }
 
  }
  //////////////////////////////////////////////////////////////////
  //print outlier 
  $min=sizeof($clustersArr2[0]->points);
  $index=0;
  for ($i=0; $i <sizeof($clustersArr2 ); $i++) { 
         if($min>sizeof($clustersArr2[$i]->points))
         {
          $min=sizeof($clustersArr2[$i]->points);
          $index=$i;
          
         }
  }


   echo "outlier persons' records <br><br> ";
 
  for ($i=0; $i <$min; $i++) { 
    $id= $clustersArr2[$index]->points[$i] ;

    echo("person  ".$id."<br>");
    echo "<div class='outlier'>";
    echo "<span>".$id."</span>";
    for ($j=1; $j <sizeof($allData[$id-1]) ; $j++) { 
      echo "<span>". $allData[$id-1][$j]."</span>";
     
    }
   echo "</div>";
  }
   
	
?>

</div>
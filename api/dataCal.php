<?php

function analyzeComputeString($result)
{
  $resultt = shell_exec("python /vagrant/public/MedCalculatorUFP/api/getCredits.py ");
  $jsonCred=json_decode($resultt);
  $credits=array();

  foreach ($jsonCred as $key => $cre)
  {
    $credits[$key]=$cre;
  }
  $totalCredits=0;
  $tempmed=0;
  $jsonD= json_decode($result);
  $definitivas=$jsonD->grade->definitivo;
  for($i=0;$i<count($definitivas);$i++)
  {
    if(strcmp($definitivas[$i]->Grau,"Licenciatura")==0)
    {
      foreach ($credits as $key => $cred)
      {
        if(strcasecmp(trim($definitivas[$i]->Unidade),trim($key))==0)
        {
          $tempmed+=$definitivas[$i]->Nota*$cred;
          $totalCredits+=$cred;
          break;
        }
      }
    }
  }
  return $tempmed/$totalCredits;
}
function reSize($data,$i,$counter)
{
  $dataDummie=array();
  if($i%2==0)// siginifica que é uma Nota
  {
    for($j=0;$j<$counter;$j++)
    {
      if($j==$i)
      {
        $j++;
      }
      else {
        $dataDummie[]=$data[$j];
      }
    }
  }
  else { // siginifica que é um credito
    for($j=0;$j<$counter;$j++)
    {
      if($j==$i-1)
      {
        $j++;
      }
      else {
        $dataDummie[]=$data[$j];
      }

    }
  }
  return $dataDummie;

}
function analyzeArray($data)
{
  if(empty($data))
  {
    return false;
  }
  $counter=count($data);
  for($i=0; $i<$counter;$i++)
  {
    if(empty($data[$i]))
    {
      $data=reSize($data,$i,$counter);
    }
  }
  $counter=count($data);
  for($i=0; $i< $counter;$i++)
  {
    if(!is_numeric($data[$i]))
    {
      return false;
    }
    if($data[$i] < -1 && $data[$i] > 21){
      return false;
    }
  }
  return $data;
}
function getTotalCredits($data)
{
  $count=0;
  for($i=1; $i<count($data);$i+=2)
  {
    $count+=$data[$i];
  }
  return $count;
}
function computeAverage($data)
{
  $temp=0;
  $average=0;
  $totalCredits=getTotalCredits($data);
  for($i=0;$i<count($data);$i+=2)
  {
    $temp=$data[$i]*$data[$i+1];
    $average+=$temp;
  }
  return $average/$totalCredits;
}
?>

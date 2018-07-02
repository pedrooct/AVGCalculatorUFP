<?php

function analyzeComputeString($result)
{
  $credits=array("Física"=>"7",
  "Gramática da comunicação"=>"3",
  "Inglês"=>"3",
  "Introdução à algoritmia e programação"=>"6",
  "Matemática I 7Sistemas de informação"=>"4",
  "Análise de sistemas"=>"6",
  "Eletrónica aplicada"=>"7",
  "Estatística aplicada"=>"7",
  "Matemática II"=>"7",
  "Opção I"=>"3",
  "Algoritmos e estruturas de dados I"=>"6",
  "Análise numérica"=>"5",
  "Arquitetura de computadores"=>"6",
  "Linguagens de programação I"=>"7",
  "Sistemas digitais"=>"6",
  "Algoritmos e estruturas de dados II"=>"6",
  "Hardware e sensores"=>	"6",
  "Investigação operacional"=>"4",
  "Linguagens de programação II"=>"7",
  "Sistemas operativos"=>"7",
  "Bases de dados"=>"6",
  "Engenharia de software"=>"6",
  "Laboratório de programação"=>"5",
  "Multimédia I"=>"6",
  "Redes de computadores I"=>"7",
  "Laboratório de projeto integrado"=>"7",
  "Multimédia II"=>"6",
  "Opcão II"=>"4",
  "Redes de computadores II"=>"7",
  "Sistemas distribuídos"=>"6"
);
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
        if(strcmp($definitivas[$i]->Unidade,$key)==0)
        {
          $tempmed+=$definitivas[$i]->Nota*$cred;
          $totalCredits+=$cred;
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

<?php
/*
Funçao que vai executar o bot em python para verificar e retornar notas do utilizador.
Recebe como parametros Numero e palavra-passe do utilizador
Retorna false se o login for invalido , Retorna as notas do utilziador se este for válido!
Saber mais no bot loginPython.py...
*/
function verifyGetGrades($numero,$password)
{
  return shell_exec("python /vagrant/public/MedCalculatorUFP/api/loginPython.py ".$numero." ".$password);
}


/*
Função que vai calcular a media , caso o utilizador escolha usar o login da UFP.
Esta função vai primeiro , buscar os creditos de as disciplinas da licenciatura (Saber mais no bot getCredits.py)
Após isto prepara um array associativo com as cadeiras como chaves e os creditos como valores.
As notas do utilizador são enviadas por parametro na interface.
*/
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
  $definitivas=$jsonD->grade->definitivo;// só usa notas definitivas para o calculo
  for($i=0;$i<count($definitivas);$i++)
  {
    if(strcmp($definitivas[$i]->Grau,"Licenciatura")==0) // só usa cadeiras da licenciatura
    {
      foreach ($credits as $key => $cred) // precorremos o array de cadeiras
      {
        if(strcasecmp(trim($definitivas[$i]->Unidade),trim($key))==0) // Comparamos com as cadeiras que o utilizador já finalizou
        {
          // calculo da média!
          $tempmed+=$definitivas[$i]->Nota*$cred;
          $totalCredits+=$cred;
          break;
        }
      }
    }
  }
  return $tempmed/$totalCredits;
}
//Esta função permite apagar uma entrada de informação se esta estiver incompleta!
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
/*
Esta função permite analizar o array de notas e creditos que o utilizador inseriu.
Esta função permite verificar se o array está vazio ,tem falhas na informação ,se tudos os valores são numericos e se estão compreendidos
entre os parametros necessários,impedindo assim exploits na API.
*/
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
// Função que permite obter o numero total de créditos do array
function getTotalCredits($data)
{
  $count=0;
  for($i=1; $i<count($data);$i+=2)
  {
    $count+=$data[$i];
  }
  return $count;
}
//Esta função permite computar a média do aluno com base nas notas que ele inseriu!
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

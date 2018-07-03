<?php
require_once "api/dataCal.php";
?>
<html lang="pt-PT">
<head>
  <meta charset="utf-8">
  <meta name="description" content="Registo">
  <title>Calculador de media UFP web page of &ndash; Pedro Costa  &ndash; PureCSS</title>

  <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-min.css">
  <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>

  <div class="content">
    <div class="pure-g">
      <div class=".pure-u-1-1 pure-u-sm-1-1 pure-u-md-1-1 pure-u-lg-1-1">
        <header>Calculador de media UFP</header>
        <p class="p">Login só para engenharia informatica!<p>
          <p class="p">Só aceita notas de cadeiras totais!<p>
            <form  method="POST">
              <div id="login">
                <input style="margin-top: 4%;" id="startlogin" name="login"  type="button"  value="Login UFP" onClick="loginUFP('login');">
              </div>
              <div id="SegT"></div>
              <div>
                <input style="margin-top: 4%;" name="addClass"  type="button"  value="Adicionar mais cadeiras" onClick="addClassSeg('SegT');">
              </div >
              <div>
                <input style="margin-top: 1%;" name="calculate"  type="submit"  value="Calcular!">
              </div>
              <?php
              if(isset($_POST['Enterlogin']))
              {
                if(!empty($_POST['num']) && !empty($_POST['pass']) )
                {
                  //shell_exec("python /vagrant/public/MedCalculatorUFP/api/loginPython.py")
                  $result = verifyGetGrades($_POST['num'],$_POST['pass']);
                  if(!$result)
                  {
                    echo '<div class="error">OOOPS! Aparenta ter dados Errados </div>';
                  }
                  else {
                    echo '<div class="success">Login com sucesso </div>';
                    $result=analyzeComputeString($result);
                    echo '<div class="info">Media Atual : '.round($result).' Valores </div>';
                  }
                }
              }
              if(isset($_POST['calculate'])){
                if(isset($_POST['data']))
                {
                  if($data=analyzeArray($_POST['data']))
                  {
                    $result=computeAverage($data);
                    echo '<div class="info">Media Atual : '.$result.' Valores </div>';
                  }
                  else {
                    echo '<div class="error">OOOPS! Aparenta ter inserido algo de errado </div>';
                  }
                }
                else {
                  echo '<div class="error">OOOPS! Insira dados! </div>';
                }
              } ?>
            </form>
          </div>
        </div>
      </div>

      <script type="text/javascript">
      var counterS = 0;
      var limitS = 31;
      function addClassSeg(divName){

        if (counterS == limitS)  {
          alert("Atingiu o máximo de Horas que pode inserir por dia na segunda feira");
        }
        else {
          var newdiv = document.createElement('div');
          newdiv.innerHTML = "<p>Nota:</p>"+"<input name='data[]' type='text' class='pure-input-rounded' >"+"<p>Créditos:</p>"+"<input name='data[]' type='text' class='pure-input-rounded'>";
          document.getElementById(divName).appendChild(newdiv);
          counterS++;
        }
      }
      function loginUFP(divName){
        document.getElementById("startlogin").style.visibility="hidden";
        var newdiv = document.createElement('div');
        newdiv.innerHTML = "<p id='pnumAluno'>Numero:</p>"+"<input id='numAluno' name='num' type='text' class='pure-input-rounded' >"+"<p id='ppassAluno'>Password:</p>"+"<input id='passAluno' name='pass' type='password' class='pure-input-rounded'>";
        newdiv.innerHTML+= '<input name="Enterlogin"  type="submit"  value="Login UFP">';
        document.getElementById(divName).appendChild(newdiv);

      }
      </script>

      <div class="footer">
        © 2018! Made By Pedro Oct Costa !!
      </div>
    </body>
    </html>

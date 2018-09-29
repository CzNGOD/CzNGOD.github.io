<html> 
    <head> 
        <title>Média Diária</title> 
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
         <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </head> 
 
    <body> 
        <center>
        <div class="alert alert-info" role="alert">
  <h2>Calculadora Básica de Média Diária</h2>
        </div>
        </center>
<div class="container">

 


    <form action="mediadiaria.php" method="POST">
  <div class="form-group">
    <center>
    <label for="formGroupExampleInput">Insira o Acumulado da Semana Atula</label>
    </center>
    <center>
    <input type="text" class="form-control" id="formGroupExampleInput" placeholder="" name="num1" value="0">
    </center>
  </div>
    <center>
    <button type="submit" class="btn btn-primary">Calcular</button>
    </center>
</form>
 </div>

<?php
$entrada  = 0;
if (isset($_POST["num1"]))
$entrada = $_POST["num1"];
$res = "";



if($entrada !=0 ){
   
   $res= ($entrada)/7; 
  
}else
?>
<div class="container">

<form>
<div class="form-group">
    <center>
    <label for="formGroupExampleInput">Média da Semana</label>
    <input type="text" class="form-control" id="formGroupExampleInput" placeholder="" name="res" value="<?php echo $res ?>">
    </center>
</form>

</div>

</body> 



</html>


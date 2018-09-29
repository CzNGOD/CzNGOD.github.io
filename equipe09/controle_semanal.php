<?php
  
  $bd = mysqli_connect("localhost","root","","hfw"); 

  if ($bd) {
  	mysqli_set_charset($bd, "utf8");
  } else {
	 echo "Não foi possível conectar o BD <br>";
	 echo "Mensagem de erro: ".mysqli_connect_error() ;
	 exit();
  }
  
  $btnExcluir = "fotos/excluir.png";
  $btnAlterar = "fotos/editar.png";


  $mensagem = "";

  $idsemana= "";
  $semana= "";
  $consumosemana="";
  $numerolote ="";
  
  
  
  if ( ! isset($_POST["acao"]) )
     $descr_acao = "Incluir"; 
  else {
	 
	 $acao = $_POST["acao"];
	 
	 if (strtoupper($acao) == "INCLUIR" || strtoupper($acao) == "SALVAR" ) {
	    $idsemana = mysqli_real_escape_string($bd, $_POST["idsemana"] ) ;
	    $semana  = mysqli_real_escape_string($bd, $_POST["semana"] );
	    $consumosemana  = mysqli_real_escape_string($bd, $_POST["consumosemana"] ) ;
	    $numerolote  = mysqli_real_escape_string($bd, $_POST["numerolote"] ) ;
	    

     }
     
     if (strtoupper($acao) == "INCLUIR") {
		 
		 $sql = "insert into consumo (idsemana, semana,consumosemana, numerolote)
		                values ('$idsemana','$semana', '$consumosemana', '$numerolote')";
		                
		 if ( ! mysqli_query($bd, $sql) ) {
			
			 if ( mysqli_errno($bd) == 1062 )
			    $mensagem = "O registro informado '$idsemana' já existe, tente outra!";
			 else
			    $mensagem = "<h3>Ocorreu um erro ao inserir os dados </h3>
			              <h3>Erro: ".mysqli_error($bd)."</h3>
			              <h3>SQL: $sql </h3>
			              <h3>Código: ".mysqli_errno($bd)."</h3>";
		   	 
		   	 $descr_acao = "Incluir";
		 } else 
		     $descr_acao = "Salvar";
	 }
	 
	 if (strtoupper($acao) == "SALVAR") {
		 
		 $descr_acao = "Salvar";
		 
		 $sql = " update consumo
		          set 
		              semana = '$semana',
		              consumosemana = '$consumosemana',
		              numerolote = '$numerolote'  
		          where
		              idsemana = '$idsemana' ";
		              
		 if ( ! mysqli_query($bd, $sql) ) {
			 
			 $mensagem = "<h3>Ocorreu um erro ao alterar os dados </h3>
			              <h3>Erro: ".mysqli_error($bd)."</h3>
			              <h3>SQL: $sql </h3>
			              <h3>Código: ".mysqli_errno($bd)."</h3>";
			 
		 }
	 }

     if (strtoupper($acao) == "EXCLUIR") {
        
        $idsemana = $_POST["idsemana"];

     	$descr_acao = "Incluir";

     	$sql = "delete from consumo where idsemana = '$idsemana' ";

     	if ( ! mysqli_query($bd, $sql) ) {
			 
			 $mensagem = "<h3>Ocorreu um erro ao excluir os dados </h3>
			              <h3>Erro: ".mysqli_error($bd)."</h3>
			              <h3>SQL: $sql </h3>
			              <h3>Código: ".mysqli_errno($bd)."</h3>";
			              
			
			 
			 
		 }

		 $iddoenca = " ";
     }

     if (strtoupper($acao) == "BUSCAR") {

        $idsemana = $_POST["idsemana"];
     	
     	$descr_acao = "Salvar";

     	$sql = "select idsemana, semana, consumosemana, numerolote
     		        from consumo 
     	        where idsemana = '$idsemana' ";

     	$resultado = mysqli_query($bd, $sql);

     	if (mysqli_num_rows($resultado) == 1) {

             $dados = mysqli_fetch_assoc($resultado);

             $idsemana = $dados["idsemana"];
             $semana = $dados["semana"];
             $consumosemana = $dados["consumosemana"];
             $numerolote = $dados['numerolote'];
             
     	}

     }

   }

   
	 
   $sql_listar = "select idsemana, semana, consumosemana, numerolote from consumo order by idsemana";
	 
   $lista = mysqli_query($bd, $sql_listar);
	 
   if ( mysqli_num_rows($lista) > 0 ) {
		
		$tabela = "";
		
		$tabela = $tabela."<div class='container-fluid' size='50px'><tr><th><div class='alert alert-success' role='alert'>Numero de registro</div></th>
		<th><div class='alert alert-success' role='alert'>SemanaAtual</div></th>
		<th><div class='alert alert-success' role='alert'>Consumo Acumulado da Semana</div></th>
		<th><div class='alert alert-success' role='alert'>Numero de Lote</div></th>
		<th><div class='alert alert-danger' role='alert'>Alterar</div></th>
		<th><div class='alert alert-danger' role='alert'>Excluir</div></th></tr>";
		 
		while ( $dados = mysqli_fetch_assoc($lista) ) {
		   
		   $vidsemana = $dados["idsemana"];
		   $vsemana  = $dados["semana"];
		   $vconsumosemana = $dados["consumosemana"];
		   $vnumerolote = $dados["numerolote"];		   
		   
		   $alterar = "<form method='post'>
		                  <input type='hidden' name='idsemana' value='$vidsemana'>
		                  <input type='hidden' name='acao' value='BUSCAR'>
		                  <input type='image' src='$btnAlterar'> 
		               </form>";
		   
		   $excluir = "<form method='post'>
		                  <input type='hidden' name='idsemana' value='$vidsemana'>
		                  <input type='hidden' name='acao' value='EXCLUIR'>
		                  <input type='image' src='$btnExcluir'> 
		               </form>";
		   
		   $tabela = $tabela."<tr><td>$vidsemana</td><td>$vsemana</td><td>$vconsumosemana</td><td>$vnumerolote</td>
		        <td>$alterar</td><td>$excluir</td></tr></div>";
		}
		
		$tabela = $tabela."</table>"; 
   } else 
	    $tabela = "não há dados para listar";
	    

?>

<html>

<head>
	<title>Controle de Consumo de Ração</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>

<body>
	<table align="center">
	<th>
		<div class="alert alert-primary" role="alert">Controle de Consumo de Ração</div>
	</th>
	
	
	<tr>
		<td>
<div class="container-fluid" height="50px">

		<form action="controle_semanal.php" method="post">
  <div class="form-group">
    <label for="formGroupExampleInput">Numero de registro:</label>
    <input type="text" class="form-control" id="formGroupExampleInput" placeholder="" name="idsemana" value="<?php echo $idsemana; ?>" size="50%">
  </div>
  <div class="form-group">
    <label for="formGroupExampleInput2">Semana Atual</label>
    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="" name="semana" value="<?php echo $semana; ?>" size="50%">
  </div>
  <div class="form-group">
    <label for="formGroupExampleInput2">Consumo Acumulado da Semana(Em Kg):</label>
    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="" name="consumosemana" value="<?php echo $consumosemana; ?>" size="50%">
  </div>
<div class="form-group">
    <label for="formGroupExampleInput2">Numero de Lote:</label>
    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="" name="numerolote" value="<?php echo $numerolote; ?>" size="50%">
  </div>
  <div>
  	<button type="submit" class="btn btn-primary" value="Novo">Novo</button>
  	<button type="submit" class="btn btn-primary" name="acao" value="<?php echo $descr_acao; ?>"><?php echo "$descr_acao"; ?></button>
  	<br><br>
  	<div class="alert alert-danger" role="alert">
  Para consultar os Valores de Referência <a href="consultatabela.html" class="alert-link">Clique aqui</a></div>
  </div><br>
  <div class="alert alert-danger" role="alert">
  Para calcular a Média Diária <a href="mediadiaria.php" class="alert-link">Clique aqui</a></div>
  </div>
  </div>

</form>
	
	</td>

</div>

	<td>


	</td>
</tr>	
<td>
	<legend>Cadastrados</legend>
	
	   <?php echo $tabela; ?>
	
	<?php echo $mensagem; ?>

	<br><br>
</td>


	</table>

</body>

<center>
<a href="index.html" class="badge badge-primary">Voltar a página inicial</a>
</center>

</html>

<?php
  
  mysqli_close($bd);

?>

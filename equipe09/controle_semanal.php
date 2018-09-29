<?php
  
  $bd = mysqli_connect("localhost","root","","hfw");  

  if ($bd) {
  	mysqli_set_charset($bd, "utf8");
  } else {
	 echo "Não foi possível conectar o BD <br>";
	 echo "Mensagem de erro: ".mysqli_connect_error() ;
	 exit();
  }
  
  $btnExcluir = "http://inf.fw.iffarroupilha.edu.br/~bruno/disciplinas/programacao_web1/excluir.png";
  $btnAlterar = "http://inf.fw.iffarroupilha.edu.br/~bruno/disciplinas/programacao_web1/alterar.png";


  $mensagem = "";

  $idsemana  = "";
  $semana  = "";
  $consumosemana  = "";
  
  
  
   
  if ( ! isset($_POST["acao"]) )
     $descr_acao = "Incluir";
  else {
	 
	 $acao = $_POST["acao"];
	 
	 if (strtoupper($acao) == "INCLUIR" || strtoupper($acao) == "SALVAR" ) {
	    $idsemana = mysqli_real_escape_string($bd, $_POST["idsemana"] ) ;
	    $semana  = mysqli_real_escape_string($bd, $_POST["semana"] ) ;
	    $consumosemana = mysqli_real_escape_string($bd, $_POST["consumosemana"] ) ;
	    

     }
     
     if (strtoupper($acao) == "INCLUIR") {
		 
		 $sql = "insert into consumo (idsemana, semana, consumosemana)
		                values ('$idsemana','$semana','$consumosemana')";
		                
		 if ( ! mysqli_query($bd, $sql) ) {
			
			 if ( mysqli_errno($bd) == 1062 )
			    $mensagem = "O numero registrado '$idsemana' já existe, tente outro!";
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
		              consumosemana = '$consumosemana'
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

		 $idsemana ="";
     }

     if (strtoupper($acao) == "BUSCAR") {

        $idsemana = $_POST["$idsemana"];
     	
     	$descr_acao = "Salvar";

     	$sql = "select idsemana, semana, consumosemana,  
     		        from consumo
     	        where idsemana = '$idsemana' ";

     	$resultado = mysqli_query($bd, $sql);

     	if (mysqli_num_rows($resultado) == 1) {

             $dados = mysqli_fetch_assoc($resultado);

             $idsemana = $dados["idsemana"];
             $semana = $dados["semana"];
             $consumosemana = $dados["consumosemana"];
            
     	}

     }

   }

   
   $sql_listar = "select idsemana, semana, consumosemana from consumo order by idsemana";
	 
   $lista = mysqli_query($bd, $sql_listar);
	 
   if ( mysqli_num_rows($lista) > 0 ) {
		
		$tabela = "<table border='4'>";
		
		$tabela = $tabela."<tr><th>Numero de Registro</th><th>Seamana Atual</th>
		             <th>Consumo Acumulado</th><th>Alterar</th><th>Excluir</th></tr>";
		 
		while ( $dados = mysqli_fetch_assoc($lista) ) {
		   
		   $vidsemana = $dados["idsemana"];
		   $vidsemana  = $dados["semana"];
		   $vconsumosemana  = $dados["consumosemana"];
		   

		   
		   $alterar = "<form method='post'>
		                  <input type='hidden' name='idsemana' value='$idsemana'>
		                  <input type='hidden' name='acao' value='BUSCAR'>
		                  <input type='image' src='$btnAlterar'> 
		               </form>";
		   
		   $excluir = "<form method='post'>
		                  <input type='hidden' name='idsemana' value='$idsemana'>
		                  <input type='hidden' name='acao' value='EXCLUIR'>
		                  <input type='image' src='$btnExcluir'> 
		               </form>";
		   
		   $tabela = $tabela."<tr><td>$vidsemana</td><td>$semana</td>
		        <td>$consumosemana</td><td>$alterar</td><td>$excluir</td></tr>";
		}
		
		$tabela = $tabela."</table>"; 
   } else 
	    $tabela = "não há dados para listar";
	    

?>

<html>

<head>
	<link rel="stylesheet" type="text/css" href="estilos.css">></li>
</head>

<body>
	<table class="tabelageral" > <!-- Tag da tabela principal que alinha os objetos-->


	<tr><!-- Itens da Direita da Tela-->
	<?php echo $mensagem; ?>
	
		<td class="dados">
		<h2>Dados do Usuário</h2>
		
		<form action="controle_semanal.php" method="post">
		Numero de Registro:  <input type="text" name="idsemana" value="<?php echo $idsemana; ?>"> <br>
		Semana Atual: <input type="text" name="semana" value="<?php echo $semana; ?>" size="40"> <br>
		Consumo Acumulado: <input type="text" name="consumosemana" value="<?php echo $consumosemana; ?>"> <br>
		<br><br>
		
		<input type="submit" value="Novo">
		<input type="submit" name="acao" value="<?php echo $descr_acao; ?>">      
		      
		</form>
		
		<legend>Dados Cadastrados</legend>
	
	   <?php echo $tabela; ?>
	
		</td>

		<td > <!-- Itens da Esquerda da Tela-->
		<table class="tabelareferencia" >
			<tr>
				<td>Semana</td>
				<td>Consumo Recomendado por Semana</td>
				<td>Consumo Minimo Recomendado por Semana</td>
			</tr>
			<tr>
				<td>Semana 1</td>
				<td>9,10 kg por Animal</td>
				<td>7,15 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 2</td>
				<td>19,16 kg por Animal</td>
				<td>17,76 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 3</td>
				<td>30,70 kg por Animal</td>
				<td>29,11 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 4</td>
				<td>43,03 kg por Animal</td>
				<td>40,27 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 5</td>
				<td>56,27 kg por Animal</td>
				<td>52,53 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 6</td>
				<td>70,27 kg por Animal</td>
				<td>67,30 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 7</td>
				<td>84,99 kg por Animal</td>
				<td>78,56 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 8</td>
				<td>100,30 kg por Animal</td>
				<td>93,64 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 9</td>
				<td>116,21 kg por Animal</td>
				<td>109,78 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 10</td>
				<td>132,70 kg por Animal</td>
				<td>127.50 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 11</td>
				<td>149,76 kg por Animal</td>
				<td>140,59 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 12</td>
				<td>167,32 kg por Animal</td>
				<td>159,30 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 13</td>
				<td>185,40 kg por Animal</td>
				<td>180,67 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 14</td>
				<td>204,01 kg por Animal</td>
				<td>192,34 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 15</td>
				<td>222,86 kg por Animal</td>
				<td>217,20 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 16</td>
				<td>241,76 kg por Animal</td>
				<td>234,57 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 17</td>
				<td>260,66 kg por Animal</td>
				<td>245,70 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 18</td>
				<td>279,56 kg por Animal</td>
				<td>253,20 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 19</td>
				<td>298,46 kg por Animal</td>
				<td>260,48 Kg por Animal</td>
			</tr>
			<tr>
				<td>Semana 20</td>
				<td>317,36 kg por Animal</td>
				<td>290,52 Kg por Animal</td>
			</tr>
		</table>


		</td>
	
	
	
	

	<br><br>

	</tr>
	</table>
	
</body>

</html>

<?php
  
  mysqli_close($bd);

?>


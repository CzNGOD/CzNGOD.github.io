
<!DOCTYPE html>
<html>
<head>
	<title>Calculadora de resultado Aproximado</title>
</head>
<body>
	<form action="resultado.php" method="POST">
		Informe o sexo do animal:<input type="text" name="macho"> <br>
		Informe o valor custo total da Ração:<input type="number" name="Vracao"> <br>
		Informe o Preco do Suino no dia:<input type="number" name="Psuinodia"> <br>
		Informe o percentual de conversão esperada:<input type="number" name="CARA" > <br>
		Informe o percentual de baixas no lote:<input type="number" name="MorReal" > <br>
		Informe o Valor do Leitao:<input type="number" name="Vleitao"><br>
		Informe o peso do Leitão na chegada:<input type="number" name="PesoMLeitao" > <br>
		Informe o peso esperado do suino na saída:<input type="number" name="PesoMSuino"> <br>
		Informe a expectativa de peso limpo do Suino: <input type="number" name="PVTSuino"><br>
		
		<input type="submit" name="Enviar">


	</form>
</body>
</html>


<?php

 const macho = 5.70;
 const misto = 5.80;
 const femea = 5.95;
$Vracao =$_POST["Vracao"];
$Psuinodia =$_POST["Psuinodia"];
 const CAPA = 0.75;
$CARA =$_POST["CARA"];
 const MorPrevista = 0.05;
$MorReal =$_POST["MorReal"];
$Vleitao =$_POST["Vleitao"];
$PesoMLeitao =$_POST["PesoMLeitao"];
$PesoMSuino =$_POST["PesoMSuino"]; 
$PVTSuino =$_POST["PVTSuino"];

$conta1 = "";

$conta1 = ((CAPA - CARA) * Vracao * 0.86)/Psuinodia *100;

echo "$conta1";


?>


$(document).ready(function() {
	$('.linhaTabela:even').addClass('zebra1');
	$('.linhaTabela:odd').addClass('zebra'); 

	$('input, textarea').focus(function() {
		$(this).addClass('marcado');
	});
	
	$('input, textarea').blur(function() {
		$(this).removeClass('marcado');
	});	

});

function limpar(){					
	document.getElementById("frmDados").reset();
	$("#MsgErroValida").empty();
	$("#MsgErroValida").attr("style", "display:none;");		
}


function uploadArquivo(valor){
	
	if (valor.lastIndexOf("\\") != -1){
		$("#file-falso").attr("value", valor.substr(valor.lastIndexOf("\\")+1,valor.lenght));
		$("#file-caminho").attr("value", valor);
		
	}else{
		$("#file-falso").attr("value", valor);
		$("#file-caminho").attr("value", valor);
	}	
	
}


function ehMenorQueHoje(dataComparacao){

	var date = new Date();
	var day = date.getDate();
	function month() {
	  return (date.getMonth() < 9 ? '0' : '') + (date.getMonth() + 1); // uma formatação para adicionar 0 ao mês, é uma função e não uma variável
	}
	var year = date.getFullYear(); // para retornar apenas os dois últimos dígitos do ano

	var hoje = new Date(year, parseInt(month()) - 1, day);
		
	var arrData = dataComparacao.split("/");
	var data = new Date(arrData[2], parseInt(arrData[1]) - 1, arrData[0]);
		
	// milliSegundos1: irá conter a quantidade de segundos corridos desde 1/1/1970 0h ate dt1
	milliSegundos1 = hoje.getTime();
		
	// milliSegundos2: irá conter a quantidade de segundos corridos desde 1/1/1970 0h ate dt2
	milliSegundos2 = data.getTime();
		
	// Comparando millisegundos para retornar a conclusão de quem é maior que quem...
	if(milliSegundos1 > milliSegundos2){
		return true;
	}else{
		return false;
	}	
}

function dt2MaiorIgualDt1(dt1, dt2){
	
	var arrData1 = dt1.split("/");
	var data1 = new Date(arrData1[2], parseInt(arrData1[1]) - 1, arrData1[0]);
	
	var arrData2 = dt2.split("/");
	var data2 = new Date(arrData2[2], parseInt(arrData2[1]) - 1, arrData2[0]);
	
	// milliSegundos1: irá conter a quantidade de segundos corridos desde 1/1/1970 0h ate dt1
	milliSegundos1 = data1.getTime();
	
	// milliSegundos2: irá conter a quantidade de segundos corridos desde 1/1/1970 0h ate dt2
	milliSegundos2 = data2.getTime();
	
	// Comparando millisegundos para retornar a conclusão de quem é maior que quem...
	if(milliSegundos2 >= milliSegundos1){
		return true;
	}else{
		return false;
	}
}


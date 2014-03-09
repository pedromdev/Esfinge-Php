// JavaScript Document


function validaCadastro(form){
	var msg = '';
	var erros = 0;
	
	if(form.email.value == ""){
		erros++;
		msg += 'Preencha o campo do e-mail\n';
	}
	
	if(form.nome.value == ""){
		erros++;
		msg += 'Preencha o campo do nome\n';
	}
	
	if(form.senha.value == "" || form.confirma.value == ""){
		erros++;
		msg += 'Preencha o campo da senha\n';
	} else {
		if(form.senha.value != form.confirma.value){
			erros++;
			msg += 'Senha confirmada incorretamente\n';
		}
	}
	
	if(erros > 0){
		alert(msg);
		return;
	}
	
	form.submit();
}

function validaPerfil(form){
	var nome = form.nome.value;
	var idade = form.idade.value;
	var grau_estudo = form.grau_estudo.value;
	
	if(nome == "" && idade == "" && grau_estudo == ""){
		alert("Preencha os campos");
		return false;
	} else if(nome == ""){
		alert("Informe o seu nome");
		return false;
	}
	
	return true;
}

function validaResumo(form){
	if(form.titulo.value == ""){
		var titulo = document.getElementById("titulo").innerHTML;
		document.getElementById("titulo").innerHTML = titulo.replace('class="', 'class="campo-erro ');
		alert("Por favor, adicionar um título ao seu resumo");
		return;
	}
	
	form.submit();
}
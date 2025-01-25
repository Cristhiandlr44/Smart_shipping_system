function buscarUser(usuario){
    var validador = document.querySelector("#userValidation");
    $.ajax({
        type: "POST",
        url: "CRUD/consulta.php", // Nome do arquivo PHP que irá processar a solicitação
        data: { usuario: usuario }, // Envia o valor da variável "usuario" para o PHP
        success: function(response) {
            var resposta = response; // A resposta do PHP será exibida no console
            //console.log(resposta);
            if(resposta === "false"){
                validador.classList.remove("hide");
                verificado(document.querySelector("#usuario"), "", 1);

            } else{
                validador.classList.add("hide");
                verificado(document.querySelector("#usuario"), usuario, 1);
            }
        }
    });
}

function validarForm() {
    var nome = document.getElementById("nome").value;
    var sobrenome = document.getElementById("sobrenome").value;
    var usuario = document.getElementById("usuario").value;
    var email = document.getElementById("email").value;
    var senha = document.getElementById("senha").value;
    var confirmSenha = document.getElementById("confirmarSenha").value;

    if (nome.length < 1 || sobrenome.length < 4 || usuario.length < 4 || email.length < 1) {
        alert("Por favor, preencha todos os campos corretamente.");
        return false;
    }
    if(senha.length < 8 || senha != confirmSenha){
        alert("Verique se a senha possui mais de 4 dígitos e se sua confirmação são iguais.");
        return false;
    }
    return true;
}
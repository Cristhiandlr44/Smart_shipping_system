function formatData(dataInput){
    formData.append('inputData', dataInput.value);
}

function removerMascaraCPF(cpf) {
    var novo = cpf.value.replace(/[.-]/g, '');
    cpf.value = novo;
}

function formatarCPF(input) {
    // Remove tudo que não é número
    var cpf = input.value.replace(/\D/g, "");

    // Limita o CPF a 11 dígitos
    if (cpf.length > 11) {
        cpf = cpf.slice(0, 11);
    }

    // Formata o CPF (XXX.XXX.XXX-XX)
    if (cpf.length <= 11) {
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
        cpf = cpf.replace(/(\d{3})(\d{2})$/, "$1-$2");
    }

    // Define o valor formatado de volta no campo
    input.value = cpf;
}


function sobre(element, op){
    if(op == 1){
        element.classList.add('active');
        element.classList.remove('text-black');     
    } else{
        element.classList.remove('active');
        element.classList.add('text-black');     
    }
}

function verificado(element, conteudo, tipo){
    //1 -> valido
    if(tipo == 1){
        if(conteudo != ''){
            element.classList.add('is-valid');
            element.classList.remove('is-invalid');
        } else{
            element.classList.remove('is-valid');
            element.classList.add('is-invalid');
        }
    }

    //2 -> esconder
    if(tipo == 2){
        if(conteudo != ''){
            element.classList.add('hide');
        } else{
            element.classList.remove('hide');
        }
    }
}

function verificaSenha(atualSenha){
    var validador = document.querySelector("#passwordValidation");
    var senha1 = document.getElementById('senha').value;

    if(atualSenha == senha1 || atualSenha == ""){
        validador.classList.add("hide");
    } else{
        validador.classList.remove("hide");
    }
}

function Exibir() {
    var valor = document.getElementById('inputGroupSelect03').value;

    // Verifica se algum dos elementos já teve a classe "hide" removida
    var elementos = ['Aurora', 'Cruzeiro', 'Plena', 'Suinco'];
    var algumElementoExibido = elementos.some(function (elemento) {
        var el = document.getElementById(elemento);
        return el && el.classList.contains('hide');
    });

    // Adiciona a classe "hide" aos elementos que já foram exibidos
    if (algumElementoExibido) {
        elementos.forEach(function (elemento) {
            var el = document.getElementById(elemento);
            if (el && elemento !== valor) {
                el.classList.add('hide');
            }
        });
        document.getElementById("hr").classList.add('hide');
        op = document.getElementById('output');
    }

    // Remove a classe "hide" do elemento atual
    var elementoAtual = document.getElementById(valor);
    if (elementoAtual) {
        elementoAtual.classList.remove('hide');
        document.getElementById("hr").classList.remove('hide');
    }
}
function mostrarNotas(cargaId) {
    let notasRow = document.getElementById('itens-' + cargaId);
    if (notasRow.style.display === 'none' || notasRow.style.display === '') {
        notasRow.style.display = 'table-row';
    } else {
        notasRow.style.display = 'none';
    }
}
function gerarXlsCarga(){
    fetch('gerar_Xls_cargas.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
        
    })
    .then(response => response.json())
    .then(data => {
        if (data.erro) {
            alert("Erro: " + data.erro);
            return;
        }

        // Criar planilha com SheetJS
        let ws_data = [["Carga","Nome","CNPJ", "Rua", "Numero", "Bairro", "Cidade", "NF", "Peso"]];
        data.forEach(produto => {
            ws_data.push([
                produto.carga,
                produto.nome,
                produto.CNPJ,
                produto.rua,
                produto.numero,
                produto.bairro,
                produto.cidade,
                produto.n_nota,
                produto.peso_bruto,
                
            ]);
        });

        let wb = XLSX.utils.book_new();
        let ws = XLSX.utils.aoa_to_sheet(ws_data);
        XLSX.utils.book_append_sheet(wb, ws, "Mapa de Cargas");

        XLSX.writeFile(wb, `Mapa_Cargas.xlsx`);
    })
    .catch(error => {
        console.error("Erro ao gerar planilha:", error);
    });

}

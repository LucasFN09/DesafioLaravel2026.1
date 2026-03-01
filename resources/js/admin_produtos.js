
const modalProduto = document.getElementById('modalProduto');
const formProduto = document.getElementById('formProduto');
const metodoPut = document.getElementById('metodoPut');

// Função central para buscar dados via AJAX
window.carregarDadosModal = function (id, tipo) {
    
    fetch(`/admin_produtos/dados/${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Erro ao buscar dados');
            return response.json();
        })
        .then(produto => {
            if (tipo === 'editar') {
                preencherModalEditar(produto);
            } else {
                preencherModalVisualizar(produto);
            }
        })
        .catch(error => {
            alert('Erro ao carregar os dados da peça. Tente novamente.');
            console.error(error);
        });
}

// Preenche o formulário de edição
function preencherModalEditar(produto) {
    const modal = document.getElementById('modalProduto');
    const form = document.getElementById('formProduto');

    document.getElementById('modalTitulo').innerText = "Editar Peça";
    form.action = `/admin_produtos/${produto.id_produto}`;
    document.getElementById('metodoPut').innerHTML = `<input type="hidden" name="_method" value="PUT">`;

    document.getElementById('inputNome').value = produto.nome;
    document.getElementById('inputPreco').value = produto.preco;
    document.getElementById('inputCategoria').value = produto.categoria;
    document.getElementById('inputFoto').value = produto.foto;
    document.getElementById('inputDescricao').value = produto.descricao;

    modal.classList.remove('hidden');
}

// Preenche o modal de visualização
function preencherModalVisualizar(produto) {
    document.getElementById('visuNome').innerText = produto.nome;
    document.getElementById('visuFoto').src = produto.foto || 'https://via.placeholder.com/300';
    document.getElementById('visuPreco').innerText = 'R$ ' + parseFloat(produto.preco).toLocaleString('pt-br', { minimumFractionDigits: 2 });
    document.getElementById('visuCategoria').innerText = produto.categoria;
    document.getElementById('visuDescricao').innerText = produto.descricao || 'Sem descrição disponível.';

    document.getElementById('modalVisualizar').classList.remove('hidden');
}


function abrirModalCriar() {
    document.getElementById('modalTitulo').innerText = "Anunciar Peça";
    formProduto.action = "{{ route('produtos.store') }}";
    formProduto.reset();
    metodoPut.innerHTML = ""; // Limpa o PUT
    modalProduto.classList.remove('hidden');
}

function abrirModalEditar(produto) {
    document.getElementById('modalTitulo').innerText = "Editar Peça";
    formProduto.action = `/produtos/${produto.id_produto}`;
    metodoPut.innerHTML = `<input type="hidden" name="_method" value="PUT">`;

    // Preenche os campos
    document.getElementById('inputNome').value = produto.nome;
    document.getElementById('inputPreco').value = produto.preco;
    document.getElementById('inputCategoria').value = produto.categoria;
    document.getElementById('inputFoto').value = produto.foto;
    document.getElementById('inputDescricao').value = produto.descricao;

    modalProduto.classList.remove('hidden');
}

function abrirModalVisualizar(produto) {
    document.getElementById('visuNome').innerText = produto.nome;
    document.getElementById('visuFoto').src = produto.foto || 'https://via.placeholder.com/300';
    document.getElementById('visuPreco').innerText = 'R$ ' + parseFloat(produto.preco).toLocaleString('pt-br', {
        minimumFractionDigits: 2
    });
    document.getElementById('visuCategoria').innerText = produto.categoria;
    document.getElementById('visuDescricao').innerText = produto.descricao || 'Sem descrição.';

    document.getElementById('modalVisualizar').classList.remove('hidden');
}

function confirmarExclusao(id) {
    document.getElementById('formExcluir').action = `/produtos/${id}`;
    document.getElementById('modalExcluir').classList.remove('hidden');
}

function fecharModal(id) {
    document.getElementById(id).classList.add('hidden');
}
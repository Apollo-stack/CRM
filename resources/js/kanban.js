import Sortable from 'sortablejs';

document.addEventListener('DOMContentLoaded', () => {
    const columns = [
        { id: 'kanban-new', status: 'new' },
        { id: 'kanban-negotiation', status: 'negotiation' },
        { id: 'kanban-won', status: 'won' }
    ];

    columns.forEach(col => {
        const el = document.getElementById(col.id);
        if (!el) return;

        new Sortable(el, {
            group: 'kanban', // Permite arrastar entre colunas do mesmo grupo
            animation: 150,
            ghostClass: 'bg-gray-700', // Classe do placeholder enquanto arrasta
            dragClass: 'opacity-50', // Classe do item enquanto arrasta

            onEnd: function (evt) {
                const itemEl = evt.item;
                const newColumnId = evt.to.id;
                const oldColumnId = evt.from.id;

                // Se soltou na mesma coluna, não faz nada
                if (newColumnId === oldColumnId) return;

                // Descobre o novo status baseado na coluna onde soltou
                const targetColumn = columns.find(c => c.id === newColumnId);
                const newStatus = targetColumn.status;

                const leadId = itemEl.getAttribute('data-id');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                // Envia requisição AJAX para atualizar
                fetch(`/leads/${leadId}`, {
                    method: 'POST', // Usamos POST com _method=PUT (padrão Laravel)
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'PUT',
                        status: newStatus
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Status atualizado:', newStatus);

                            // Substitui o conteúdo do card pelo novo HTML renderizado pelo servidor
                            if (data.html) {
                                itemEl.innerHTML = data.html;
                            }
                        } else {
                            // Se der erro, volta o card (recarrega a pagina ou alerta)
                            alert('Erro ao atualizar. Recarregando...');
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro de conexão. Card voltará para posição original.');
                        window.location.reload();
                    });
            }
        });
    });
});

function updateCardVisuals(card, status) {
    // Remove bordas antigas
    card.classList.remove('border-gray-500', 'border-blue-500', 'border-green-500', 'border-red-500');

    // Adiciona nova borda
    if (status === 'new') card.classList.add('border-gray-500');
    if (status === 'negotiation') card.classList.add('border-blue-500');
    if (status === 'won') card.classList.add('border-green-500');
    if (status === 'lost') card.classList.add('border-red-500');
}

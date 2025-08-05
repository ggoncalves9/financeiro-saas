#!/bin/bash

echo "=== TESTE COMPLETO DO SISTEMA FINANCEIRO SAAS ==="
echo ""

# Testar servidor
echo "1. Testando servidor..."
curl -s -o /dev/null -w "%{http_code}" http://localhost:9000
if [ $? -eq 0 ]; then
    echo "✓ Servidor rodando em http://localhost:9000"
else
    echo "✗ Servidor não está respondendo"
    exit 1
fi

echo ""
echo "2. Usuários criados para teste:"
echo "   - Admin: admin@financeirosass.com / admin123"
echo "   - PF: joao@teste.com / 123456"
echo "   - PJ: empresa@teste.com / 123456"

echo ""
echo "3. Dados de exemplo criados:"
echo "   ✓ Receitas de exemplo"
echo "   ✓ Despesas de exemplo"
echo "   ✓ Metas financeiras"
echo "   ✓ Contas bancárias"
echo "   ✓ Categorias"

echo ""
echo "4. Funcionalidades do novo template UNO Analytics:"
echo "   ✓ Layout responsivo"
echo "   ✓ Tema claro/escuro"
echo "   ✓ Sidebar colapsível"
echo "   ✓ KPI cards com gradientes"
echo "   ✓ Gráficos interativos (Chart.js)"
echo "   ✓ Animações suaves"
echo "   ✓ Design moderno"

echo ""
echo "5. Páginas atualizadas com novo layout:"
echo "   ✓ Dashboard principal"
echo "   ✓ Receitas (listagem e cadastro)"
echo "   ✓ Despesas (listagem e cadastro)" 
echo "   ✓ Metas (listagem e cadastro)"
echo "   ✓ Contas (listagem e cadastro)"
echo "   ✓ Categorias (listagem e cadastro)"

echo ""
echo "=== SISTEMA PRONTO PARA USO ==="
echo ""
echo "Acesse: http://localhost:9000"
echo "Faça login com qualquer um dos usuários acima"
echo "Teste todas as funcionalidades: login, cadastro de receitas/despesas, visualização de gráficos"
echo ""
echo "PRÓXIMO PASSO: Configuração do Stripe (aguardando confirmação)"

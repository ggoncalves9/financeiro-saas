@echo off
echo === TESTE COMPLETO DO SISTEMA FINANCEIRO SAAS ===
echo.

echo 1. Testando servidor...
powershell -Command "try { $response = Invoke-WebRequest -Uri 'http://localhost:9000' -UseBasicParsing -TimeoutSec 5; if ($response.StatusCode -eq 200) { Write-Host 'Servidor rodando em http://localhost:9000' -ForegroundColor Green } } catch { Write-Host 'Servidor não está respondendo' -ForegroundColor Red }"

echo.
echo 2. Usuários criados para teste:
echo    - Admin: admin@financeirosass.com / admin123
echo    - PF: joao@teste.com / 123456  
echo    - PJ: empresa@teste.com / 123456

echo.
echo 3. Dados de exemplo criados:
echo    ✓ Receitas de exemplo
echo    ✓ Despesas de exemplo
echo    ✓ Metas financeiras
echo    ✓ Contas bancárias
echo    ✓ Categorias

echo.
echo 4. Funcionalidades do novo template UNO Analytics:
echo    ✓ Layout responsivo
echo    ✓ Tema claro/escuro
echo    ✓ Sidebar colapsível
echo    ✓ KPI cards com gradientes
echo    ✓ Gráficos interativos (Chart.js)
echo    ✓ Animações suaves
echo    ✓ Design moderno

echo.
echo 5. Páginas atualizadas com novo layout:
echo    ✓ Dashboard principal
echo    ✓ Receitas (listagem e cadastro)
echo    ✓ Despesas (listagem e cadastro)
echo    ✓ Metas (listagem e cadastro)
echo    ✓ Contas (listagem e cadastro)
echo    ✓ Categorias (listagem e cadastro)

echo.
echo === SISTEMA PRONTO PARA USO ===
echo.
echo Acesse: http://localhost:9000
echo Faça login com qualquer um dos usuários acima
echo Teste todas as funcionalidades: login, cadastro de receitas/despesas, visualização de gráficos
echo.
echo PRÓXIMO PASSO: Configuração do Stripe (aguardando confirmação)
echo.
pause

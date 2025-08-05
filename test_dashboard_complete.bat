@echo off
echo ====================================
echo TESTE COMPLETO DO DASHBOARD
echo ====================================

echo.
echo 1. Iniciando servidor...
start /b C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe artisan serve --host=0.0.0.0 --port=9015

echo.
echo 2. Aguardando servidor inicializar...
timeout /t 3 >nul

echo.
echo 3. Verificando se servidor está rodando...
netstat -an | findstr :9015

echo.
echo 4. Limpando cache...
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe artisan view:clear

echo.
echo 5. Testando conexão com dashboard...
curl -s -o nul -w "Status HTTP: %%{http_code}\n" http://localhost:9015/dashboard

echo.
echo 6. Abrindo navegador para teste manual...
start http://localhost:9015/dashboard

echo.
echo ====================================
echo TESTE COMPLETO - DASHBOARD PRONTO!
echo ====================================
echo.
echo INSTRUÇÕES PARA TESTE:
echo 1. O navegador deve abrir automaticamente
echo 2. Faça login se necessário
echo 3. Teste os botões de "Nova Receita" e "Nova Despesa"
echo 4. Verifique se as contas aparecem nos selects
echo 5. Teste no celular e no computador
echo.
pause

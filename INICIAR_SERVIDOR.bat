@echo off
title Servidor Laravel - Financeiro SaaS
cls
echo.
echo ==========================================
echo      SERVIDOR LARAVEL - FINANCEIRO SAAS
echo ==========================================
echo.
echo Aguarde, iniciando servidor...
echo.

cd /d "c:\laragon\www\financeiro_saas"

Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd 'c:\laragon\www\financeiro_saas'; Start-Process -FilePath 'C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe' -ArgumentList 'artisan','serve','--host=0.0.0.0','--port=9015' -WorkingDirectory 'c:\laragon\www\financeiro_saas' -WindowStyle Normal"

timeout /t 3 >nul

echo ==========================================
echo    SERVIDOR INICIADO COM SUCESSO!
echo ==========================================
echo.
echo ACESSO LOCAL:
echo   http://localhost:9015
echo   http://127.0.0.1:9015
echo   http://192.168.15.183:9015
echo.
echo ACESSO EXTERNO:
echo   http://189.46.172.45:9015
echo.
echo PAGINAS PRINCIPAIS:
echo   Login: /login
echo   Dashboard: /dashboard
echo   Planos: /plans
echo.
echo USUARIOS DE TESTE:
echo   PF: pf@teste.com / 123456
echo   PJ: pj@teste.com / 123456
echo   Admin: admin@teste.com / 123456
echo.
echo ==========================================
echo  Para parar o servidor, feche o terminal
echo ==========================================
echo.
echo Pressione qualquer tecla para sair...
pause >nul

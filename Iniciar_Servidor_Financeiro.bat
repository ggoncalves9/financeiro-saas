@echo off
title Servidor Laravel - Sistema Financeiro SaaS
color 0A
echo.
echo ================================================
echo        SERVIDOR LARAVEL - SISTEMA FINANCEIRO
echo        Projeto: Financeiro SaaS
echo        Acesso Local e Externo
echo ================================================
echo.

cd /d "c:\laragon\www\financeiro_saas"

echo [INFO] Procurando instalacao do PHP...
echo.

REM Verificar PHP no Laragon (versao detectada)
if exist "C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe" (
    echo [OK] PHP 8.3.16 encontrado no Laragon
    set "PHP_CMD=C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe"
    goto :menu
)

REM Verificar XAMPP
if exist "C:\xampp\php\php.exe" (
    echo [OK] PHP encontrado no XAMPP
    set "PHP_CMD=C:\xampp\php\php.exe"
    goto :menu
)

REM Verificar WAMP
if exist "C:\wamp64\bin\php\php8.3.0\php.exe" (
    echo [OK] PHP encontrado no WAMP
    set "PHP_CMD=C:\wamp64\bin\php\php8.3.0\php.exe"
    goto :menu
)

REM Verificar se PHP esta no PATH
php -v >nul 2>nul
if %errorlevel% equ 0 (
    echo [OK] PHP encontrado no PATH do sistema
    set "PHP_CMD=php"
    goto :menu
)

REM Se chegou aqui, PHP nao foi encontrado
echo [ERRO] PHP nao encontrado!
echo.
echo O PHP nao foi encontrado em nenhum local conhecido.
echo.
echo Solucoes:
echo 1. Instale o Laragon: https://laragon.org/download/
echo 2. Instale o XAMPP: https://www.apachefriends.org/download.html
echo 3. Baixe o PHP: https://windows.php.net/download/
echo.
echo Locais verificados:
echo - C:\laragon\bin\php\ (varias versoes)
echo - C:\xampp\php\
echo - C:\wamp64\bin\php\
echo - PATH do Windows
echo.
echo Pressione qualquer tecla para sair...
pause >nul
exit /b 1

:menu
cls
echo.
echo ================================================
echo        SERVIDOR LARAVEL - SISTEMA FINANCEIRO
echo        Projeto: Financeiro SaaS
echo ================================================
echo.
echo Escolha uma opcao:
echo.
echo [1] Iniciar servidor LOCAL (porta 9015)
echo     Acesso: http://localhost:9015
echo.
echo [2] Iniciar servidor EXTERNO (porta 9015)
echo     Acesso: http://189.46.172.45:9015
echo.
echo [3] Sair
echo.
set /p choice="Digite sua opcao (1, 2 ou 3): "

if "%choice%"=="1" goto :local_server
if "%choice%"=="2" goto :external_server
if "%choice%"=="3" goto :exit
echo.
echo Opcao invalida! Digite 1, 2 ou 3.
echo.
pause
goto :menu

:local_server
cls
echo.
echo ================================================
echo   INICIANDO SERVIDOR LOCAL
echo ================================================
echo.
echo [INFO] Verificando versao do PHP...
%PHP_CMD% -v
echo.
echo [INFO] Limpando cache da aplicacao...
%PHP_CMD% artisan config:clear >nul 2>nul
%PHP_CMD% artisan cache:clear >nul 2>nul
%PHP_CMD% artisan route:clear >nul 2>nul
echo.
echo ================================================
echo   SERVIDOR LOCAL INICIADO!
echo   
echo   Links de acesso:
echo   http://localhost:9015
echo   http://127.0.0.1:9015
echo   
echo   Paginas principais:
echo   - Login: http://localhost:9015/login
echo   - Dashboard: http://localhost:9015/dashboard
echo   
echo   Usuarios de teste:
echo   PF: pf@teste.com / 123456
echo   PJ: pj@teste.com / 123456
echo   Admin: admin@teste.com / 123456
echo ================================================
echo.
echo Para parar o servidor, pressione Ctrl+C
echo.
%PHP_CMD% artisan serve --host=127.0.0.1 --port=9015
echo.
echo Servidor parado.
pause
goto :menu

:external_server
cls
echo.
echo ================================================
echo   INICIANDO SERVIDOR EXTERNO
echo ================================================
echo.
echo [INFO] Verificando versao do PHP...
%PHP_CMD% -v
echo.
echo [INFO] Limpando cache da aplicacao...
%PHP_CMD% artisan config:clear >nul 2>nul
%PHP_CMD% artisan cache:clear >nul 2>nul
%PHP_CMD% artisan route:clear >nul 2>nul
echo.
echo ================================================
echo   SERVIDOR EXTERNO INICIADO!
echo   
echo   Links de acesso externo:
echo   http://189.46.172.45:9015
echo   
echo   Paginas principais:
echo   - Login: http://189.46.172.45:9015/login
echo   - Dashboard: http://189.46.172.45:9015/dashboard
echo   
echo   IMPORTANTE:
echo   - Porta 9015 deve estar liberada no firewall
echo   - Modem deve ter redirecionamento configurado
echo   
echo   Usuarios de teste:
echo   PF: pf@teste.com / 123456
echo   PJ: pj@teste.com / 123456
echo   Admin: admin@teste.com / 123456
echo ================================================
echo.
echo Para parar o servidor, pressione Ctrl+C
echo.
%PHP_CMD% artisan serve --host=0.0.0.0 --port=9015
echo.
echo Servidor parado.
pause
goto :menu

:exit
echo.
echo Saindo do servidor...
exit /b 0

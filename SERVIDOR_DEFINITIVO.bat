@echo off
title Servidor Laravel - Sistema Financeiro SaaS
color 0A
cls

echo.
echo ==========================================
echo      SERVIDOR LARAVEL - FINANCEIRO SAAS
echo      Solucao Definitiva e Robusta
echo ==========================================
echo.

REM Mudando para o diretorio do projeto
cd /d "c:\laragon\www\financeiro_saas"

echo [INFO] Verificando PHP...
set PHP_FOUND=0

REM Tenta encontrar PHP do Laragon
if exist "C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe" (
    set "PHP_CMD=C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe"
    set PHP_FOUND=1
    echo [OK] PHP 8.3.16 encontrado no Laragon
    goto :start_server
)

REM Tenta XAMPP
if exist "C:\xampp\php\php.exe" (
    set "PHP_CMD=C:\xampp\php\php.exe"
    set PHP_FOUND=1
    echo [OK] PHP encontrado no XAMPP
    goto :start_server
)

REM Tenta WAMP
if exist "C:\wamp64\bin\php\php8.3.0\php.exe" (
    set "PHP_CMD=C:\wamp64\bin\php\php8.3.0\php.exe"
    set PHP_FOUND=1
    echo [OK] PHP encontrado no WAMP
    goto :start_server
)

REM Tenta PHP no PATH
php -v >nul 2>&1
if %errorlevel% equ 0 (
    set "PHP_CMD=php"
    set PHP_FOUND=1
    echo [OK] PHP encontrado no PATH do sistema
    goto :start_server
)

REM Se nao encontrou PHP
if %PHP_FOUND% equ 0 (
    echo [ERRO] PHP nao encontrado!
    echo.
    echo Instale o Laragon em: https://laragon.org/download/
    echo.
    echo Pressione qualquer tecla para sair...
    pause >nul
    exit /b 1
)

:start_server
echo.
echo [INFO] Limpando cache...
"%PHP_CMD%" artisan config:clear >nul 2>&1
"%PHP_CMD%" artisan cache:clear >nul 2>&1
"%PHP_CMD%" artisan route:clear >nul 2>&1

echo [INFO] Verificando se a porta 9015 esta em uso...
netstat -an | find ":9015" >nul
if %errorlevel% equ 0 (
    echo [AVISO] Porta 9015 ja esta em uso. Parando processo...
    for /f "tokens=5" %%a in ('netstat -aon ^| find ":9015" ^| find "LISTENING"') do taskkill /f /pid %%a >nul 2>&1
    timeout /t 2 >nul
)

echo.
echo ==========================================
echo    INICIANDO SERVIDOR...
echo ==========================================
echo.
echo [INFO] Versao do PHP:
"%PHP_CMD%" -v
echo.
echo ==========================================
echo    SERVIDOR INICIADO COM SUCESSO!
echo ==========================================
echo.
echo LINKS DE ACESSO:
echo.
echo LOCAL:
echo   http://localhost:9015
echo   http://127.0.0.1:9015
echo.
echo REDE INTERNA:
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
echo  IMPORTANTE: Para PARAR o servidor,
echo  pressione Ctrl+C nesta janela
echo ==========================================
echo.

REM Inicia o servidor Laravel
"%PHP_CMD%" artisan serve --host=0.0.0.0 --port=9015

echo.
echo [INFO] Servidor parado.
echo Pressione qualquer tecla para sair...
pause >nul

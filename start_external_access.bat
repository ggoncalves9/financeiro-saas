@echo off
title Servidor Laravel - Acesso Externo (189.46.172.45:9015)
color 0A
echo.
echo ================================================
echo        SERVIDOR LARAVEL - ACESSO EXTERNO
echo        Projeto: Financeiro SaaS
echo        IP Publico: 189.46.172.45
echo        Porta: 9015
echo ================================================
echo.

cd /d "c:\laragon\www\financeiro_saas"

echo [INFO] Procurando instalacao do PHP...

REM Lista expandida de caminhos do PHP
set "PHP_FOUND=0"

REM Verificar Laragon (versoes mais comuns)
for %%v in (8.3.16 8.3.15 8.3.14 8.3.13 8.3.12 8.3.11 8.3.10 8.3.0 8.2.15 8.2.14 8.2.13 8.2.12 8.2.0 8.1.27 8.1.26 8.1.25 8.1.0 8.0.30 8.0.0) do (
    if exist "C:\laragon\bin\php\php-%%v-nts-Win32-vs16-x64\php.exe" (
        echo [OK] PHP encontrado: C:\laragon\bin\php\php-%%v-nts-Win32-vs16-x64\
        set "PHP_CMD=C:\laragon\bin\php\php-%%v-nts-Win32-vs16-x64\php.exe"
        set "PHP_FOUND=1"
        goto :start_server
    )
)

REM Verificar XAMPP
if exist "C:\xampp\php\php.exe" (
    echo [OK] PHP encontrado: C:\xampp\php\
    set "PHP_CMD=C:\xampp\php\php.exe"
    set "PHP_FOUND=1"
    goto :start_server
)

REM Verificar WAMP
for %%v in (8.3.0 8.2.0 8.1.0 8.0.0) do (
    if exist "C:\wamp64\bin\php\php%%v\php.exe" (
        echo [OK] PHP encontrado: C:\wamp64\bin\php\php%%v\
        set "PHP_CMD=C:\wamp64\bin\php\php%%v\php.exe"
        set "PHP_FOUND=1"
        goto :start_server
    )
)

REM Verificar se PHP esta no PATH
php -v >nul 2>nul
if %errorlevel% equ 0 (
    echo [OK] PHP encontrado no PATH do sistema
    set "PHP_CMD=php"
    set "PHP_FOUND=1"
    goto :start_server
)

if %PHP_FOUND%==0 (
    echo [ERRO] PHP nao encontrado em nenhum local!
    echo.
    echo Solucoes:
    echo 1. Instale o Laragon (recomendado): https://laragon.org/
    echo 2. Instale o XAMPP: https://www.apachefriends.org/
    echo 3. Baixe o PHP: https://www.php.net/downloads
    echo.
    echo Pastas verificadas:
    echo - C:\laragon\bin\php\
    echo - C:\xampp\php\
    echo - C:\wamp64\bin\php\
    echo - PATH do sistema
    echo.
    pause
    exit /b 1
)

:start_server
echo.
echo [INFO] Verificando versao do PHP...
%PHP_CMD% -v
echo.

echo [INFO] Limpando cache...
%PHP_CMD% artisan config:clear >nul 2>nul
%PHP_CMD% artisan cache:clear >nul 2>nul
%PHP_CMD% artisan route:clear >nul 2>nul

echo.
echo ================================================
echo   SERVIDOR INICIADO PARA ACESSO EXTERNO!
echo   
echo   Link de Acesso Externo:
echo   http://189.46.172.45:9015
echo   
echo   Login:
echo   http://189.46.172.45:9015/login
echo   
echo   Dashboard:
echo   http://189.46.172.45:9015/dashboard
echo   
echo   Usuarios de teste:
echo   PF: pf@teste.com / 123456
echo   PJ: pj@teste.com / 123456
echo   Admin: admin@teste.com / 123456
echo ================================================
echo.
echo [IMPORTANTE] Certifique-se que:
echo - O modem tem redirecionamento da porta 9015
echo - O firewall do Windows esta liberado
echo - O servidor esta rodando com --host=0.0.0.0
echo.
echo Para parar o servidor, pressione Ctrl+C
echo.

%PHP_CMD% artisan serve --host=0.0.0.0 --port=9015

echo.
echo Servidor parado.
pause

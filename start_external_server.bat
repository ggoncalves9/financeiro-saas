@echo off
title Servidor Laravel - Financeiro SaaS (Acesso Externo)
echo.
echo ================================================
echo        INICIANDO SERVIDOR LARAVEL
echo        Projeto: Financeiro SaaS
echo        Porta: 9009 (Acesso Externo Habilitado)
echo ================================================
echo.
cd /d "c:\laragon\www\financeiro_saas"

echo Verificando PHP...

REM Testar se PHP ja esta no PATH
php -v >nul 2>nul
if %errorlevel% equ 0 (
    echo PHP encontrado no PATH!
    goto :start_server
)

echo PHP nao encontrado no PATH. Procurando instalacoes...

REM Tentar diferentes caminhos do Laragon
set "PHP_PATHS=C:\laragon\bin\php\php-8.3.16-nts-Win32-vs16-x64;C:\laragon\bin\php\php-8.2.15-nts-Win32-vs16-x64;C:\laragon\bin\php\php-8.1.27-nts-Win32-vs16-x64;C:\laragon\bin\php\php-8.0.30-nts-Win32-vs16-x64;C:\xampp\php;C:\wamp64\bin\php\php8.3.0;C:\wamp64\bin\php\php8.2.0"

for %%p in (%PHP_PATHS%) do (
    if exist "%%p\php.exe" (
        echo PHP encontrado em: %%p
        set "PATH=%%p;%PATH%"
        goto :start_server
    )
)

echo ERRO: PHP nao encontrado!
echo.
echo Por favor, instale o PHP ou configure uma das seguintes opcoes:
echo 1. Adicione o PHP ao PATH do Windows
echo 2. Instale o Laragon (recomendado)
echo 3. Instale o XAMPP
echo.
pause
exit /b 1

:start_server
echo.
echo Verificando versao do PHP...
php -v

echo.
echo ================================================
echo   SERVIDOR INICIADO COM SUCESSO!
echo   
echo   Acesso Local: http://127.0.0.1:9009
echo   Acesso Externo: http://SEU_IP:9009
echo   
echo   Usuarios de teste:
echo   PF: pf@teste.com / 123456
echo   PJ: pj@teste.com / 123456
echo   Admin: admin@teste.com / 123456
echo ================================================
echo.
echo IMPORTANTE: Configure o firewall do Windows para permitir 
echo conexoes na porta 9009 para acesso externo.
echo.
echo Para parar o servidor, pressione Ctrl+C
echo.
php artisan serve --host=0.0.0.0 --port=9009
pause

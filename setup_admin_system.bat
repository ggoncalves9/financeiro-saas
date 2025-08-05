@echo off
title Setup Sistema Admin - Financeiro SaaS
echo.
echo ================================================
echo        CONFIGURACAO SISTEMA ADMIN
echo        Financeiro SaaS
echo ================================================
echo.

cd /d "c:\laragon\www\financeiro_saas"

echo Procurando PHP...

REM Tentar diferentes caminhos do Laragon
set "PHP_PATHS=C:\laragon\bin\php\php-8.3.16-nts-Win32-vs16-x64;C:\laragon\bin\php\php-8.3.1-nts-Win32-vs16-x64;C:\laragon\bin\php\php-8.2.15-nts-Win32-vs16-x64;C:\laragon\bin\php\php-8.1.27-nts-Win32-vs16-x64;C:\xampp\php"

for %%p in (%PHP_PATHS%) do (
    if exist "%%p\php.exe" (
        echo PHP encontrado em: %%p
        set "PHP_PATH=%%p\php.exe"
        goto :found_php
    )
)

echo ERRO: PHP nao encontrado!
echo Por favor, instale o Laragon ou configure o PHP no PATH
pause
exit /b 1

:found_php
echo.
echo Executando migrations...
"%PHP_PATH%" artisan migrate --force

if %errorlevel% neq 0 (
    echo.
    echo ERRO ao executar migrations!
    echo Tentando executar SQL diretamente...
    goto :sql_direct
)

echo.
echo Inserindo dados padrao...
"%PHP_PATH%" artisan db:seed

echo.
echo Sistema configurado com sucesso!
echo.
echo Acesse: http://localhost:9015/admin/plans
echo.
pause
exit /b 0

:sql_direct
echo.
echo Tentando executar SQL diretamente no MySQL...
echo Certifique-se de que o MySQL esta rodando!
echo.

REM Tentar encontrar mysql
set "MYSQL_PATHS=C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin;C:\xampp\mysql\bin;C:\Program Files\MySQL\MySQL Server 8.0\bin"

for %%m in (%MYSQL_PATHS%) do (
    if exist "%%m\mysql.exe" (
        echo MySQL encontrado em: %%m
        set "MYSQL_PATH=%%m\mysql.exe"
        goto :found_mysql
    )
)

echo MySQL nao encontrado automaticamente.
echo Execute manualmente o arquivo: insert_default_plans.sql
echo no seu cliente MySQL (phpMyAdmin, MySQL Workbench, etc.)
pause
exit /b 1

:found_mysql
echo.
echo Executando SQL...
"%MYSQL_PATH%" -u root -p financeiro_saas < insert_default_plans.sql

if %errorlevel% equ 0 (
    echo.
    echo SQL executado com sucesso!
    echo Sistema configurado!
    echo.
    echo Acesse: http://localhost:9015/admin/plans
) else (
    echo.
    echo Erro ao executar SQL.
    echo Execute manualmente o arquivo: insert_default_plans.sql
)

echo.
pause

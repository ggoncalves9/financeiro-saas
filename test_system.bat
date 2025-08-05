@echo off
title Teste de Conectividade - Financeiro SaaS
echo.
echo ================================================
echo        TESTANDO CONECTIVIDADE DO SISTEMA
echo ================================================
echo.

cd /d "c:\laragon\www\financeiro_saas"

echo [1/4] Testando PHP...
php -v
if %errorlevel% neq 0 (
    echo ERRO: PHP nao encontrado!
    exit /b 1
)
echo PHP: OK!

echo.
echo [2/4] Testando conexao com MySQL...
php -r "try { $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', ''); echo 'MySQL: OK!'; } catch(Exception $e) { echo 'ERRO MySQL: ' . $e->getMessage(); exit(1); }"

echo.
echo [3/4] Testando Laravel...
php artisan --version
if %errorlevel% neq 0 (
    echo ERRO: Laravel nao encontrado!
    exit /b 1
)
echo Laravel: OK!

echo.
echo [4/4] Verificando banco de dados...
php artisan migrate:status
if %errorlevel% neq 0 (
    echo Banco nao existe, sera criado automaticamente.
)

echo.
echo ================================================
echo   SISTEMA PRONTO PARA EXECUCAO!
echo ================================================
pause

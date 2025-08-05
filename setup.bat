@echo off
echo ================================
echo Sistema Financeiro SaaS - Setup
echo ================================
echo.

echo [1/8] Verificando requisitos...

REM Verificar PHP (Laragon)
if exist "C:\laragon\bin\php\php-8.2\php.exe" (
    set "PHP_PATH=C:\laragon\bin\php\php-8.2\php.exe"
    echo ✓ PHP encontrado no Laragon 8.2
    goto :checkcomposer
) 
if exist "C:\laragon\bin\php\php-8.1\php.exe" (
    set "PHP_PATH=C:\laragon\bin\php\php-8.1\php.exe"
    echo ✓ PHP encontrado no Laragon 8.1
    goto :checkcomposer
)
php --version >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ERRO: PHP nao encontrado. Instale o PHP 8.1+ primeiro.
    pause
    exit /b 1
)
set "PHP_PATH=php"
echo ✓ PHP encontrado no PATH

:checkcomposer

REM Verificar Composer (Laragon)
if exist "C:\laragon\bin\composer\composer.bat" (
    set "COMPOSER_PATH=C:\laragon\bin\composer\composer.bat"
    echo ✓ Composer encontrado no Laragon
    goto :checknodejs
)
composer --version >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ERRO: Composer nao encontrado. Instale o Composer primeiro.
    pause
    exit /b 1
)
set "COMPOSER_PATH=composer"
echo ✓ Composer encontrado no PATH

:checknodejs

node --version >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ERRO: Node.js nao encontrado. Instale o Node.js primeiro.
    pause
    exit /b 1
)

echo ✓ PHP, Composer e Node.js encontrados!
echo.

echo [2/8] Instalando dependencias PHP...
%COMPOSER_PATH% install --optimize-autoloader
if %ERRORLEVEL% neq 0 (
    echo ERRO: Falha ao instalar dependencias PHP
    pause
    exit /b 1
)

echo [3/8] Instalando dependencias JavaScript...
npm install
if %ERRORLEVEL% neq 0 (
    echo ERRO: Falha ao instalar dependencias JavaScript
    pause
    exit /b 1
)

echo [4/8] Configurando ambiente...
if not exist .env (
    copy .env.example .env
    echo ✓ Arquivo .env criado
) else (
    echo ✓ Arquivo .env ja existe
)

echo [5/8] Gerando chave da aplicacao...
%PHP_PATH% artisan key:generate
if %ERRORLEVEL% neq 0 (
    echo ERRO: Falha ao gerar chave da aplicacao
    pause
    exit /b 1
)

echo [6/8] Compilando assets...
npm run build
if %ERRORLEVEL% neq 0 (
    echo AVISO: Falha ao compilar assets, continuando...
)

echo [7/8] Criando link do storage...
%PHP_PATH% artisan storage:link
if %ERRORLEVEL% neq 0 (
    echo AVISO: Falha ao criar link do storage, continuando...
)

echo.
echo ================================
echo ✓ Setup concluido com sucesso!
echo ================================
echo.
echo PROXIMOS PASSOS:
echo.
echo 1. Configure o banco de dados no arquivo .env
echo    DB_DATABASE=financeiro_saas
echo    DB_USERNAME=seu_usuario
echo    DB_PASSWORD=sua_senha
echo.
echo 2. Configure o Stripe no arquivo .env
echo    STRIPE_KEY=pk_test_...
echo    STRIPE_SECRET=sk_test_...
echo.
echo 3. Execute as migracoes:
echo    %PHP_PATH% artisan migrate --seed
echo.
echo 4. Inicie o servidor:
echo    %PHP_PATH% artisan serve
echo.
echo 5. Acesse: http://localhost:8000
echo.
echo USUARIOS DE TESTE:
echo - Admin: admin@financeirosass.com / admin123
echo - PF: joao@exemplo.com / 123456
echo - PJ: maria@empresa.com / 123456
echo.
pause

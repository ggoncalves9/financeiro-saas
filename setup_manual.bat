cd /d "c:\laragon\www\financeiro_saas"

echo Copiando arquivo de ambiente...
copy .env.example .env

echo Instalando dependencias via Composer...
echo Aguarde...

REM Se voce tem o Laragon, descomente a linha abaixo:
REM C:\laragon\bin\composer\composer.bat install --optimize-autoloader

REM Se voce tem o Composer global, descomente a linha abaixo:
REM composer install --optimize-autoloader

echo.
echo =====================================
echo CONFIGURACAO MANUAL NECESSARIA
echo =====================================
echo.
echo 1. Instale as dependencias PHP:
echo    composer install --optimize-autoloader
echo.
echo 2. Instale as dependencias Node.js:
echo    npm install
echo.
echo 3. Gere a chave da aplicacao:
echo    php artisan key:generate
echo.
echo 4. Configure o banco de dados no .env:
echo    DB_DATABASE=financeiro_saas
echo    DB_USERNAME=root
echo    DB_PASSWORD=
echo.
echo 5. Execute as migracoes:
echo    php artisan migrate --seed
echo.
echo 6. Compile os assets:
echo    npm run build
echo.
echo 7. Inicie o servidor:
echo    php artisan serve
echo.
echo 8. Acesse: http://localhost:8000
echo.
echo USUARIOS DE TESTE:
echo - Admin: admin@financeirosass.com / admin123
echo - PF: joao@exemplo.com / 123456
echo - PJ: maria@empresa.com / 123456
echo.
pause

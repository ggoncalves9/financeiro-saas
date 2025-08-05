@echo off
echo.
echo ========================================
echo      SISTEMA FINANCEIRO SAAS
echo ========================================
echo.
echo Iniciando servidor Laravel...
echo URL: http://localhost:8000
echo URL Admin: http://localhost:8000/admin
echo.
echo CTRL+C para parar o servidor
echo.

cd /d "c:\laragon\www\financeiro_saas"
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe artisan serve --host=0.0.0.0 --port=8000

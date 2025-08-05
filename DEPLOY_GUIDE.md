# 🚀 GUIA DE DEPLOY - SISTEMA FINANCEIRO SAAS

## 📋 PRÉ-REQUISITOS
- PHP 8.1+
- MySQL 8.0+
- Composer
- Node.js 16+
- NPM/Yarn

## 🔧 CONFIGURAÇÃO DO AMBIENTE

### 1. Clonagem e Dependências
```bash
# Clonar repositório
git clone [URL_DO_REPOSITORIO]
cd financeiro_saas

# Instalar dependências PHP
composer install --optimize-autoloader --no-dev

# Instalar dependências JavaScript
npm install
npm run build
```

### 2. Configuração do Banco de Dados
```bash
# Copiar arquivo de configuração
cp .env.example .env

# Editar configurações no .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=financeiro_saas
DB_USERNAME=root
DB_PASSWORD=

# Gerar chave da aplicação
php artisan key:generate

# Executar migrações
php artisan migrate --force

# Criar usuário admin
php artisan make:admin admin@seudominio.com senha123 "Administrador"
```

### 3. Configurações de Produção
```bash
# Cache de configuração
php artisan config:cache

# Cache de rotas
php artisan route:cache

# Cache de views
php artisan view:cache

# Otimizar autoloader
composer dump-autoload --optimize
```

## 🌐 CONFIGURAÇÃO DO SERVIDOR

### Apache (.htaccess)
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Nginx
```nginx
server {
    listen 80;
    server_name seudominio.com;
    root /var/www/financeiro_saas/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🔐 CONFIGURAÇÕES DE SEGURANÇA

### SSL/HTTPS
```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx

# Gerar certificado SSL
sudo certbot --nginx -d seudominio.com

# Renovação automática
sudo crontab -e
0 12 * * * /usr/bin/certbot renew --quiet
```

### Firewall
```bash
# UFW (Ubuntu)
sudo ufw enable
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
```

## 📊 MONITORAMENTO

### Logs
```bash
# Configurar rotação de logs
sudo nano /etc/logrotate.d/laravel

/var/www/financeiro_saas/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 0644 www-data www-data
}
```

### Backup Automático
```bash
#!/bin/bash
# backup.sh

# Variáveis
DATE=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/backups/financeiro_saas"
DB_NAME="financeiro_saas"
DB_USER="root"
DB_PASS="senha"

# Criar diretório de backup
mkdir -p $BACKUP_DIR

# Backup do banco de dados
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Backup dos arquivos
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/financeiro_saas

# Remover backups antigos (manter últimos 7 dias)
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup concluído: $DATE"
```

### Cron para Backup
```bash
# Adicionar ao crontab
sudo crontab -e

# Backup diário às 2h da manhã
0 2 * * * /path/to/backup.sh
```

## 🚀 OTIMIZAÇÕES DE PERFORMANCE

### OPcache (PHP)
```ini
; php.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.revalidate_freq=0
opcache.validate_timestamps=0
```

### Redis Cache
```bash
# Instalar Redis
sudo apt install redis-server

# Configurar no .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Queue Workers
```bash
# Supervisor configuration
sudo nano /etc/supervisor/conf.d/laravel-worker.conf

[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/financeiro_saas/artisan queue:work redis --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/financeiro_saas/worker.log
```

## 📈 MONITORAMENTO E MÉTRICAS

### Health Check
```php
// routes/web.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'version' => config('app.version'),
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected'
    ]);
});
```

### Status Page
```html
<!-- resources/views/status.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Status do Sistema</title>
    <meta http-equiv="refresh" content="30">
</head>
<body>
    <h1>Status do Sistema Financeiro SaaS</h1>
    <div>
        <strong>Status:</strong> 
        <span style="color: green;">✅ Online</span>
    </div>
    <div>
        <strong>Última Atualização:</strong> {{ now()->format('d/m/Y H:i:s') }}
    </div>
    <div>
        <strong>Versão:</strong> {{ config('app.version', '1.0.0') }}
    </div>
</body>
</html>
```

## ⚙️ MANUTENÇÃO

### Comandos Úteis
```bash
# Limpar caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reiniciar queue workers
php artisan queue:restart

# Verificar logs
tail -f storage/logs/laravel.log

# Backup manual
php artisan backup:run

# Verificar saúde do sistema
php artisan health:check
```

## 🔔 ALERTAS E NOTIFICAÇÕES

### Email de Erro
```php
// config/logging.php
'channels' => [
    'mail' => [
        'driver' => 'monolog',
        'handler' => Monolog\Handler\NativeMailerHandler::class,
        'handler_with' => [
            'to' => 'admin@seudominio.com',
            'subject' => 'Erro no Sistema Financeiro SaaS',
        ],
        'level' => 'error',
    ],
],
```

### Slack Notifications
```bash
# Instalar pacote
composer require laravel/slack-notification-channel

# Configurar webhook no .env
SLACK_WEBHOOK_URL=https://hooks.slack.com/services/...
```

## 📞 SUPORTE

### Contatos
- **Email:** suporte@seudominio.com
- **Telefone:** (11) 99999-9999
- **Chat:** https://seudominio.com/chat

### Documentação
- **Manual do Usuário:** /docs/user
- **API Documentation:** /docs/api
- **Changelog:** /docs/changelog

---
*Deploy realizado com sucesso! 🎉*

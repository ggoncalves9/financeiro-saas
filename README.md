# Sistema Financeiro SaaS

Um sistema completo de controle financeiro desenvolvido em Laravel, oferecendo funcionalidades para pessoas físicas (PF) e jurídicas (PJ) com diferentes planos de assinatura.

## 🚀 Funcionalidades

### Para Pessoa Física (PF)
- ✅ Controle de receitas e despesas
- ✅ Metas financeiras com auto-save
- ✅ Múltiplas contas bancárias
- ✅ Relatórios e gráficos
- ✅ Categorização automática
- ✅ Notificações de vencimento

### Para Pessoa Jurídica (PJ)
- ✅ Todas as funcionalidades PF
- ✅ Gestão de equipe
- ✅ Aprovação de despesas
- ✅ Controle de limites por usuário
- ✅ Relatórios empresariais
- ✅ Múltiplos centros de custo

### Funcionalidades Administrativas
- ✅ Painel administrativo completo
- ✅ Gestão de usuários e assinaturas
- ✅ Métricas do sistema
- ✅ Relatórios de receita (MRR)
- ✅ Monitoramento de sistema

### Recursos Técnicos
- ✅ Autenticação com 2FA
- ✅ Integração com Stripe
- ✅ Multi-tenancy
- ✅ Logout global
- ✅ API RESTful
- ✅ Exportação de dados (CSV, Excel, PDF)

## 📋 Requisitos

- PHP 8.1 ou superior
- Composer
- Node.js e NPM
- MySQL 8.0 ou superior
- Extensões PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## 🛠️ Instalação

### 1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/financeiro-saas.git
cd financeiro-saas
```

### 2. Instale as dependências PHP
```bash
composer install
```

### 3. Instale as dependências JavaScript
```bash
npm install
npm run build
```

### 4. Configure o ambiente
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure o banco de dados
Edite o arquivo `.env` com suas configurações de banco:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=financeiro_saas
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 6. Configure o Stripe
Adicione suas chaves do Stripe no arquivo `.env`:
```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

### 7. Execute as migrações
```bash
php artisan migrate --seed
```

### 8. Configure o storage
```bash
php artisan storage:link
```

### 9. Inicie o servidor
```bash
php artisan serve
```

O sistema estará disponível em `http://localhost:8000`

## 👤 Usuários de Teste

Após executar o seeder, você terá os seguintes usuários:

### Administrador
- **Email:** admin@financeirosass.com
- **Senha:** admin123
- **Acesso:** Painel administrativo completo

### Pessoa Física
- **Email:** joao@exemplo.com
- **Senha:** 123456
- **Plano:** Básico

### Pessoa Jurídica
- **Email:** maria@empresa.com
- **Senha:** 123456
- **Plano:** Profissional

## 💳 Planos de Assinatura

### Gratuito
- 10 receitas/mês
- 10 despesas/mês
- 2 metas
- 1 conta bancária

### Básico - R$ 19,90/mês
- 100 receitas/mês
- 100 despesas/mês
- 10 metas
- 5 contas bancárias
- Relatórios básicos

### Profissional - R$ 49,90/mês
- Receitas ilimitadas
- Despesas ilimitadas
- Metas ilimitadas
- Contas ilimitadas
- Gestão de equipe (até 5 membros)
- Relatórios avançados
- Aprovação de despesas

### Enterprise - R$ 99,90/mês
- Todos os recursos do Profissional
- Equipe ilimitada
- API personalizada
- Suporte prioritário
- Integração personalizada

## 🔧 Configuração Avançada

### Configuração de E-mail
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu-email@gmail.com
MAIL_FROM_NAME="Sistema Financeiro"
```

### Configuração de Queue (Opcional)
Para processamento de tarefas em background:
```env
QUEUE_CONNECTION=database
```

Execute o worker:
```bash
php artisan queue:work
```

### Configuração de Cache (Opcional)
Para melhor performance:
```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

## 📱 Uso da API

O sistema oferece uma API RESTful completa. Documentação disponível em `/api/documentation`

### Autenticação
```bash
POST /api/auth/login
{
    "email": "usuario@exemplo.com",
    "password": "senha123"
}
```

### Recursos Disponíveis
- `/api/revenues` - Gestão de receitas
- `/api/expenses` - Gestão de despesas
- `/api/goals` - Gestão de metas
- `/api/accounts` - Gestão de contas
- `/api/reports` - Relatórios

## 🎨 Personalização

### Cores e Temas
Edite o arquivo `resources/sass/app.scss` para personalizar:
```scss
:root {
    --primary-color: #007bff;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
}
```

### Logo e Marca
Substitua os arquivos em `public/images/`:
- `logo.png` - Logo principal
- `logo-small.png` - Logo pequena
- `favicon.ico` - Favicon

## 🔒 Segurança

### Configurações Recomendadas
```env
# Produção
APP_ENV=production
APP_DEBUG=false

# Configurações de sessão
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true

# Configurações HTTPS
FORCE_HTTPS=true
```

### Backup
Configure backups automáticos:
```bash
# Instalar pacote de backup
composer require spatie/laravel-backup

# Configurar no .env
BACKUP_DISK=s3
AWS_ACCESS_KEY_ID=sua-chave
AWS_SECRET_ACCESS_KEY=sua-chave-secreta
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=seu-bucket
```

## 📊 Monitoramento

### Logs
Logs estão disponíveis em `storage/logs/`

### Métricas
Acesse `/admin/dashboard` para métricas detalhadas:
- Usuários ativos
- Receita recorrente
- Taxa de cancelamento
- Performance do sistema

## 🚀 Deploy

### Laravel Forge (Recomendado)
1. Conecte seu repositório
2. Configure domínio e SSL
3. Configure variáveis de ambiente
4. Execute deploy

### Servidor Manual
```bash
# Clone e configure o projeto
git clone https://github.com/seu-usuario/financeiro-saas.git
cd financeiro-saas

# Instale dependências
composer install --optimize-autoloader --no-dev
npm install && npm run production

# Configure permissões
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Configure nginx/apache
# Aponte document root para /public
```

### Docker
```yaml
# docker-compose.yml
version: '3.8'
services:
  app:
    build: .
    ports:
      - "8000:8000"
    environment:
      - APP_ENV=production
    volumes:
      - .:/var/www/html
  
  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: financeiro_saas
```

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## 📝 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE.md](LICENSE.md) para detalhes.

## 🆘 Suporte

### Documentação
- [Laravel](https://laravel.com/docs)
- [Stripe](https://stripe.com/docs)
- [Bootstrap](https://getbootstrap.com/docs)

### Contato
- **Email:** suporte@financeirosass.com
- **Website:** https://financeirosass.com
- **GitHub Issues:** https://github.com/seu-usuario/financeiro-saas/issues

## 🎯 Roadmap

### Versão 2.0
- [ ] Aplicativo mobile (React Native)
- [ ] Integração bancária via Open Banking
- [ ] IA para categorização automática
- [ ] Marketplace de integrações

### Versão 2.1
- [ ] Módulo de investimentos
- [ ] Controle de criptomoedas
- [ ] Planejamento financeiro avançado
- [ ] Simulador de empréstimos

---

**Desenvolvido com ❤️ para ajudar no controle financeiro pessoal e empresarial.**

#!/bin/bash

echo "================================"
echo "Sistema Financeiro SaaS - Setup"
echo "================================"
echo

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check requirements
echo "[1/8] Verificando requisitos..."

if ! command -v php &> /dev/null; then
    echo -e "${RED}ERRO: PHP não encontrado. Instale o PHP 8.1+ primeiro.${NC}"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    echo -e "${RED}ERRO: Composer não encontrado. Instale o Composer primeiro.${NC}"
    exit 1
fi

if ! command -v node &> /dev/null; then
    echo -e "${RED}ERRO: Node.js não encontrado. Instale o Node.js primeiro.${NC}"
    exit 1
fi

echo -e "${GREEN}✓ PHP, Composer e Node.js encontrados!${NC}"
echo

# Install PHP dependencies
echo "[2/8] Instalando dependências PHP..."
composer install --optimize-autoloader
if [ $? -ne 0 ]; then
    echo -e "${RED}ERRO: Falha ao instalar dependências PHP${NC}"
    exit 1
fi

# Install JavaScript dependencies
echo "[3/8] Instalando dependências JavaScript..."
npm install
if [ $? -ne 0 ]; then
    echo -e "${RED}ERRO: Falha ao instalar dependências JavaScript${NC}"
    exit 1
fi

# Setup environment
echo "[4/8] Configurando ambiente..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo -e "${GREEN}✓ Arquivo .env criado${NC}"
else
    echo -e "${GREEN}✓ Arquivo .env já existe${NC}"
fi

# Generate application key
echo "[5/8] Gerando chave da aplicação..."
php artisan key:generate
if [ $? -ne 0 ]; then
    echo -e "${RED}ERRO: Falha ao gerar chave da aplicação${NC}"
    exit 1
fi

# Build assets
echo "[6/8] Compilando assets..."
npm run build
if [ $? -ne 0 ]; then
    echo -e "${YELLOW}AVISO: Falha ao compilar assets, continuando...${NC}"
fi

# Create storage link
echo "[7/8] Criando link do storage..."
php artisan storage:link
if [ $? -ne 0 ]; then
    echo -e "${YELLOW}AVISO: Falha ao criar link do storage, continuando...${NC}"
fi

# Set permissions (Linux/Mac)
echo "[8/8] Configurando permissões..."
chmod -R 755 storage bootstrap/cache
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Permissões configuradas${NC}"
fi

echo
echo "================================"
echo -e "${GREEN}✓ Setup concluído com sucesso!${NC}"
echo "================================"
echo
echo "PRÓXIMOS PASSOS:"
echo
echo "1. Configure o banco de dados no arquivo .env"
echo "   DB_DATABASE=financeiro_saas"
echo "   DB_USERNAME=seu_usuario"
echo "   DB_PASSWORD=sua_senha"
echo
echo "2. Configure o Stripe no arquivo .env"
echo "   STRIPE_KEY=pk_test_..."
echo "   STRIPE_SECRET=sk_test_..."
echo
echo "3. Execute as migrações:"
echo "   php artisan migrate --seed"
echo
echo "4. Inicie o servidor:"
echo "   php artisan serve"
echo
echo "5. Acesse: http://localhost:8000"
echo
echo "USUÁRIOS DE TESTE:"
echo "- Admin: admin@financeirosass.com / admin123"
echo "- PF: joao@exemplo.com / 123456"
echo "- PJ: maria@empresa.com / 123456"
echo

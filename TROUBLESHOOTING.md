# 🔧 GUIA DE RESOLUÇÃO DE PROBLEMAS

## ❌ PROBLEMAS COMUNS E SOLUÇÕES

### 1. "PHP não é reconhecido como comando"

**SOLUÇÃO 1 - Instalar Laragon (Recomendado):**
1. Baixe o Laragon: https://laragon.org/download/
2. Instale em C:\laragon
3. Execute o start_server.bat novamente

**SOLUÇÃO 2 - Configurar PATH manualmente:**
1. Abra "Configurações avançadas do sistema"
2. Clique em "Variáveis de ambiente"
3. Em "Path", adicione: `C:\laragon\bin\php\php-8.3.16-nts-Win32-vs16-x64`
4. Reinicie o computador

### 2. "Erro de conexão com MySQL"

**SOLUÇÃO:**
1. Abra o Laragon
2. Clique em "Start All" para iniciar Apache e MySQL
3. Execute o start_server.bat novamente

### 3. "Tabela não encontrada" 

**SOLUÇÃO:**
1. Execute primeiro: `test_system.bat`
2. Se tudo estiver OK, execute: `start_server.bat`

### 4. Sistema não abre no navegador

**VERIFICAÇÕES:**
1. ✅ O terminal mostra "Laravel development server started"?
2. ✅ Não há erro de porta em uso?
3. ✅ Acesse: http://127.0.0.1:9000 (não 8000)

## 🚀 ORDEM CORRETA DE EXECUÇÃO

1. **Primeiro:** Execute `test_system.bat` para verificar
2. **Depois:** Execute `start_server.bat` para iniciar
3. **Acesse:** http://127.0.0.1:9000

## 👥 USUÁRIOS PARA TESTE

- PF: pf@teste.com / 123456
- PJ: pj@teste.com / 123456  
- Admin: admin@teste.com / 123456

## 📞 SE AINDA NÃO FUNCIONAR

Envie o erro completo que aparece no terminal para análise detalhada.

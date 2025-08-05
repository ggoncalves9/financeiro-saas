@extends('layouts.app')

@section('title', 'Política de Privacidade')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h1 class="h3 mb-0">Política de Privacidade</h1>
                    <p class="text-muted mb-0">Última atualização: {{ date('d/m/Y') }}</p>
                </div>
                <div class="card-body">
                    <div class="privacy-content">
                        <h2>1. Informações Gerais</h2>
                        <p>A presente Política de Privacidade contém informações sobre coleta, uso, armazenamento, tratamento e proteção dos dados pessoais dos usuários do Sistema Financeiro SaaS, com a finalidade de demonstrar absoluta transparência quanto ao assunto e esclarecer a todos interessados sobre os tipos de dados que são coletados, os motivos da coleta e a forma como os usuários podem gerenciar ou excluir as suas informações pessoais.</p>

                        <h2>2. Como recolhemos os dados pessoais do usuário</h2>
                        <p>Os dados pessoais do usuário são recolhidos pela plataforma da seguinte forma:</p>
                        <ul>
                            <li>Quando o usuário cria uma conta na plataforma: Nome, e-mail, telefone, tipo de usuário (PF/PJ)</li>
                            <li>Quando o usuário adiciona informações financeiras: Receitas, despesas, metas, contas bancárias</li>
                            <li>Quando o usuário navega no site: Dados de navegação, cookies, IP</li>
                            <li>Quando o usuário contrata uma assinatura: Dados de pagamento (processados pelo Stripe)</li>
                        </ul>

                        <h2>3. Quais dados pessoais recolhemos sobre o usuário</h2>
                        <p>Os dados pessoais do usuário recolhidos são os seguintes:</p>
                        
                        <h3>3.1. Dados de Identificação</h3>
                        <ul>
                            <li>Nome completo</li>
                            <li>E-mail</li>
                            <li>Telefone</li>
                            <li>CPF/CNPJ</li>
                            <li>Data de nascimento</li>
                            <li>Endereço</li>
                        </ul>

                        <h3>3.2. Dados Financeiros</h3>
                        <ul>
                            <li>Receitas e despesas</li>
                            <li>Metas financeiras</li>
                            <li>Contas bancárias (número da conta mascarado)</li>
                            <li>Transações financeiras</li>
                        </ul>

                        <h3>3.3. Dados de Navegação</h3>
                        <ul>
                            <li>Endereço IP</li>
                            <li>Localização geográfica</li>
                            <li>Fonte de referência</li>
                            <li>Páginas acessadas</li>
                            <li>Tempo de permanência</li>
                        </ul>

                        <h2>4. Para que finalidades utilizamos os dados pessoais do usuário</h2>
                        <p>Os dados pessoais do usuário coletados e armazenados pela plataforma têm por finalidade:</p>
                        <ul>
                            <li>Bem atender os usuários da plataforma</li>
                            <li>Realizar o controle financeiro solicitado</li>
                            <li>Melhorar a funcionalidade da plataforma</li>
                            <li>Cumprir exigências legais</li>
                            <li>Processar pagamentos de assinatura</li>
                            <li>Enviar comunicações importantes</li>
                            <li>Prevenir fraudes e garantir segurança</li>
                        </ul>

                        <h2>5. Por quanto tempo os dados pessoais ficam armazenados</h2>
                        <p>Os dados pessoais do usuário são armazenados pela plataforma durante o período necessário para a prestação do serviço ou o cumprimento das finalidades previstas no presente documento, conforme o disposto no inciso I do artigo 15 da Lei 13.709/18.</p>
                        
                        <p>Os dados podem ser removidos ou anonimizados a pedido do usuário, excetuando os casos em que a lei oferecer outro tratamento.</p>

                        <h2>6. Segurança dos dados pessoais armazenados</h2>
                        <p>A plataforma se compromete a aplicar as medidas técnicas e organizativas aptas a proteger os dados pessoais de acessos não autorizados e de situações de destruição, perda, alteração, comunicação ou difusão de tais dados.</p>
                        
                        <p>Medidas de segurança implementadas:</p>
                        <ul>
                            <li>Criptografia de dados sensíveis</li>
                            <li>Autenticação de dois fatores (2FA)</li>
                            <li>Controle de acesso baseado em funções</li>
                            <li>Logs de auditoria</li>
                            <li>Backups seguros</li>
                            <li>Monitoramento de segurança</li>
                        </ul>

                        <h2>7. Compartilhamento dos dados</h2>
                        <p>O titular dos dados pessoais possui direito de solicitar do controlador, a qualquer momento, mediante requisição:</p>
                        <ul>
                            <li>Confirmação da existência de tratamento</li>
                            <li>Acesso aos dados</li>
                            <li>Correção de dados incompletos, inexatos ou desatualizados</li>
                            <li>Anonimização, bloqueio ou eliminação de dados desnecessários, excessivos ou tratados em desconformidade com a LGPD</li>
                            <li>Portabilidade dos dados</li>
                            <li>Eliminação dos dados pessoais tratados com o consentimento do titular</li>
                            <li>Informação das entidades públicas e privadas com as quais o controlador realizou uso compartilhado de dados</li>
                            <li>Informação sobre a possibilidade de não fornecer consentimento e sobre as consequências da negativa</li>
                            <li>Revogação do consentimento</li>
                        </ul>

                        <h2>8. Dados de menores de idade</h2>
                        <p>O menor de 18 anos deve possuir consentimento expresso de ao menos um dos pais ou de seu responsável legal para utilização da plataforma, sendo de responsabilidade destes o controle sobre as atividades da criança ou adolescente.</p>

                        <h2>9. Cookies</h2>
                        <p>Cookies são pequenos arquivos de texto baixados automaticamente em seu computador quando você acessa e navega por um website. Eles servem, basicamente, para:</p>
                        <ul>
                            <li>Reconhecer o dispositivo do visitante</li>
                            <li>Armazenar suas preferências</li>
                            <li>Melhorar a experiência de navegação</li>
                            <li>Coletar informações estatísticas</li>
                        </ul>

                        <h2>10. Consentimento</h2>
                        <p>Ao utilizar os serviços e fornecer as informações pessoais na plataforma, o usuário está consentindo com a presente Política de Privacidade.</p>
                        
                        <p>O usuário, ao cadastrar-se, manifesta conhecer e pode exercitar seus direitos de cancelar seu cadastro, acessar e atualizar seus dados pessoais e garante a veracidade das informações por ele disponibilizadas.</p>

                        <h2>11. Alterações para essa política de privacidade</h2>
                        <p>Reservamos o direito de modificar essa Política de Privacidade a qualquer momento, então, é recomendável que o usuário revise-a com frequência.</p>
                        
                        <p>As alterações e esclarecimentos vão surtir efeito imediatamente após sua publicação na plataforma. Quando realizadas alterações relevantes nesta política, podemos informá-lo aqui que ela foi atualizada, para que possa ficar ciente sobre as informações que coletamos, como as utilizamos, e sob que circunstâncias, se alguma, utilizamos e/ou divulgamos elas.</p>

                        <h2>12. Encarregado de Proteção de Dados</h2>
                        <p>A plataforma disponibiliza os seguintes meios para que o usuário possa entrar em contato conosco para exercer os direitos previstos nesta Política, bem como, para enviar sugestões para a melhoria desta política:</p>
                        
                        <div class="contact-info bg-light p-3 rounded">
                            <strong>Encarregado de Proteção de Dados (DPO)</strong><br>
                            <strong>Nome:</strong> Sistema Financeiro SaaS<br>
                            <strong>E-mail:</strong> dpo@financeirosass.com<br>
                            <strong>Telefone:</strong> (11) 9999-9999<br>
                            <strong>Endereço:</strong> São Paulo, SP - Brasil
                        </div>

                        <div class="mt-4 text-center">
                            <a href="{{ route('privacy.data-processing') }}" class="btn btn-outline-primary me-2">
                                Gerenciar Dados
                            </a>
                            <a href="{{ route('privacy.consent') }}" class="btn btn-outline-secondary">
                                Consentimentos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.privacy-content h2 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
    border-bottom: 2px solid #3498db;
    padding-bottom: 0.5rem;
}

.privacy-content h3 {
    color: #34495e;
    margin-top: 1.5rem;
    margin-bottom: 0.8rem;
}

.privacy-content p {
    line-height: 1.6;
    margin-bottom: 1rem;
    text-align: justify;
}

.privacy-content ul {
    margin-bottom: 1rem;
}

.privacy-content li {
    margin-bottom: 0.5rem;
}

.contact-info {
    border-left: 4px solid #3498db;
}
</style>
@endpush

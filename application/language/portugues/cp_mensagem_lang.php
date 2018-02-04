<?php

$lang['login_conta_nao_ativada'] = 'Conta não ativada. Clique em "Esqueci minha senha" para que um novo e-mail com o link de ativação seja enviado.';
$lang['login_usuario_senha_nao_conferem'] = 'Usuário e senha não conferem.';

// Páginas com mensagem
$lang['cadastro_sucesso_tipo'] = 'success';
$lang['cadastro_sucesso_titulo'] = 'Cadastro efetuado com sucesso';
$lang['cadastro_sucesso_mensagem'] = 'Enviamos uma mensagem para o e-mail que você informou, nela há um link para ativar o seu cadastro. Por favor, acesse sua conta de e-mail e clique no link da mensagem que enviamos. Caso não encontre a mensagem, verifique sua pasta de Spam, pois ela pode ter sido direcionada para lá.';

$lang['cadastro_erro_email_confirmacao_tipo'] = 'danger';
$lang['cadastro_erro_email_confirmacao_titulo'] = 'Erro ao cadastrar';
$lang['cadastro_erro_email_confirmacao_mensagem'] = 'Ocorreu um erro ao tentar enviar o email de confirmação do cadastro. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-001"';

$lang['cadastro_erro_inserir_tipo'] = 'danger';
$lang['cadastro_erro_inserir_titulo'] = 'Erro ao cadastrar';
$lang['cadastro_erro_inserir_mensagem'] = 'Ocorreu um erro ao tentar realizar o cadastro. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-002"';

$lang['ativacao_sucesso_tipo'] = 'success';
$lang['ativacao_sucesso_titulo'] = 'Cadastro ativado com sucesso';
$lang['ativacao_sucesso_mensagem'] = 'A partir de agora, você pode acessar o site com seu e-mail e senha na área "Acessar" e poderá trocar seus pontos por diversos produtos e serviços.';

$lang['ativacao_erro_tipo'] = 'danger';
$lang['ativacao_erro_titulo'] = 'Erro ao ativar cadastro';
$lang['ativacao_erro_mensagem'] = 'Ocorreu um erro ao tentar ativar seu cadastro. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-003"';

$lang['reset_falha_cpf_tipo'] = 'warning';
$lang['reset_falha_cpf_titulo'] = 'Não foi possível efetuar o reset de senha';
$lang['reset_falha_cpf_mensagem'] = 'O CPF informado não consta em nosso cadastro ou não possui um e-mail válido cadastrado. Por favor, entre em contato com a equipe do site. Código do alerta: "WRN-004"';

$lang['reset_erro_email_tipo'] = 'danger';
$lang['reset_erro_email_titulo'] = 'Erro ao enviar e-mail de reset de senha';
$lang['reset_erro_email_mensagem'] = 'Ocorreu um erro ao tentar enviar e-mail de reset de senha. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-005"';

$lang['reset_sucesso_tipo'] = 'success';
$lang['reset_sucesso_titulo'] = 'E-mail de recuperação enviado com sucesso';
$lang['reset_sucesso_mensagem'] = 'Em breve, você receberá uma mensagem no endereço de e-mail cadastrado com instruções para definir uma nova senha de acesso.';

$lang['nova_senha_erro_tipo'] = 'danger';
$lang['nova_senha_erro_titulo'] = 'Erro ao definir senha';
$lang['nova_senha_erro_mensagem'] = 'Ocorreu um erro ao tentar alterar a senha. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-006"';

$lang['nova_senha_sucesso_tipo'] = 'success';
$lang['nova_senha_sucesso_titulo'] = 'Senha cadastrada com sucesso';
$lang['nova_senha_sucesso_mensagem'] = 'A partir de agora, você pode acessar o site com seu e-mail e nova senha na área "Acessar" e poderá trocar seus pontos por diversos produtos e serviços.';

$lang['altera_cadastro_erro_tipo'] = 'danger';
$lang['altera_cadastro_erro_titulo'] = 'Erro ao alterar cadastro';
$lang['altera_cadastro_erro_mensagem'] = 'Ocorreu um erro ao tentar alterar o cadastro. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-007"';

$lang['altera_cadastro_sucesso_tipo'] = 'success';
$lang['altera_cadastro_sucesso_titulo'] = 'Cadastro alterado com sucesso';
$lang['altera_cadastro_sucesso_mensagem'] = '';

$lang['conceder_pontos_erro_usuario_tipo'] = 'danger';
$lang['conceder_pontos_erro_usuario_titulo'] = 'Erro ao conceder pontos';
$lang['conceder_pontos_erro_usuario_mensagem'] = 'Ocorreu um erro ao tentar encontrar o consumidor que receberia os pontos. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-008"';

$lang['conceder_pontos_erro_tipo'] = 'danger';
$lang['conceder_pontos_erro_titulo'] = 'Erro ao conceder pontos';
$lang['conceder_pontos_erro_mensagem'] = 'Ocorreu um erro ao tentar conceder pontos para o cliente. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-009"';

$lang['conceder_pontos_erro_loja_inativa_tipo'] = 'danger';
$lang['conceder_pontos_erro_loja_inativa_titulo'] = 'Erro ao conceder pontos';
$lang['conceder_pontos_erro_loja_inativa_mensagem'] = 'A empresa que está concedendo os pontos não está com cadastro ativo no sistema. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-012"';

$lang['conceder_pontos_sucesso_tipo'] = 'success';
$lang['conceder_pontos_sucesso_titulo'] = 'Pontos concedidos com sucesso';
$lang['conceder_pontos_sucesso_mensagem'] = anchor('lojista/conceder_pontos', 'Conceder mais pontos');

$lang['extrato_sem_dados_tipo'] = 'info';
$lang['extrato_sem_dados_titulo'] = 'Extrato vazio';
$lang['extrato_sem_dados_mensagem'] = 'Não há informações de pontos dos últimos dias para serem exibidas. Lembre-se de acumular pontos comprando nos nossos parceiros. Conheça a '.anchor('ganhe_pontos', 'rede de parceiros').'.';

$lang['extrato_sem_dados_lojista_tipo'] = 'info';
$lang['extrato_sem_dados_lojista_titulo'] = 'Extrato vazio';
$lang['extrato_sem_dados_lojista_mensagem'] = 'Não há informações de pontos concedidos nos últimos dias para serem exibidas. Lembre-se de oferecer pontos para seus clientes.';

$lang['cupom_nao_existe_tipo'] = 'danger';
$lang['cupom_nao_existe_titulo'] = 'Cupom não encontrado';
$lang['cupom_nao_existe_mensagem'] = 'Ocorreu um erro ao tentar encontrar o cupom. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-0010"';

$lang['cupom_nao_encontrado_tipo'] = 'warning';
$lang['cupom_nao_encontrado_titulo'] = 'Cupom não encontrado';
$lang['cupom_nao_encontrado_mensagem'] = 'Não foi encontrado nenhum cupom com o número e código informados.';

$lang['cupom_utilizado_ou_expirado_tipo'] = 'warning';
$lang['cupom_utilizado_ou_expirado_titulo'] = 'Cupom não está ativo';
$lang['cupom_utilizado_ou_expirado_mensagem'] = 'O cupom que você tentou consultar não pôde ser exibido por um dos seguintes motivos: já foi utilizado, está expirado ou foi estornado. Confira na sua lista de cupons.';

$lang['cupom_ja_utilizado_tipo'] = 'warning';
$lang['cupom_ja_utilizado_titulo'] = 'Cupom já utilizado';
$lang['cupom_ja_utilizado_mensagem'] = 'O cupom que você tentou consultar não pôde ser exibido por já ter sido utilizado.';

$lang['cupom_expirado_tipo'] = 'warning';
$lang['cupom_expirado_titulo'] = 'Cupom expirado';
$lang['cupom_expirado_mensagem'] = 'O cupom que você tentou consultar não pôde ser exibido por já ter vencido seu prazo de validade.';

$lang['cupom_estornado_tipo'] = 'warning';
$lang['cupom_estornado_titulo'] = 'Cupom estornado';
$lang['cupom_estornado_mensagem'] = 'O cupom que você tentou consultar não pôde ser exibido por ter sido estornado/cancelado.';

$lang['cupom_de_outra_loja_tipo'] = 'warning';
$lang['cupom_de_outra_loja_titulo'] = 'Cupom de outro estabelecimento';
$lang['cupom_de_outra_loja_mensagem'] = 'O cupom que você tentou consultar não pôde ser exibido pois pertence a outro estabelecimento.';

$lang['pontos_insuficientes_tipo'] = 'warning';
$lang['pontos_insuficientes_titulo'] = 'Pontos insuficientes';
$lang['pontos_insuficientes_mensagem'] = 'Cupom não foi emitido. Você não possui pontos suficientes para emitir este cupom.';

$lang['erro_emitir_cupom_tipo'] = 'danger';
$lang['erro_emitir_cupom_titulo'] = 'Erro ao emitir cupom';
$lang['erro_emitir_cupom_mensagem'] = 'Ocorreu um erro ao tentar emitir o cupom. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-0011"';

$lang['consumo_cupom_erro_tipo'] = 'danger';
$lang['consumo_cupom_erro_titulo'] = 'Erro ao consumir cupom';
$lang['consumo_cupom_erro_mensagem'] = 'Ocorreu um erro ao tentar consumir o cupom. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-0012"';

$lang['fatura_nao_existe_tipo'] = 'danger';
$lang['fatura_nao_existe_titulo'] = 'Fatura não encontrada';
$lang['fatura_nao_existe_mensagem'] = 'Ocorreu um erro ao tentar encontrar a fatura. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-0013"';

$lang['consumo_cupom_ok_tipo'] = 'success';
$lang['consumo_cupom_ok_titulo'] = 'Cupom consumido com sucesso';
$lang['consumo_cupom_ok_mensagem'] = 'O cupom foi consumido com sucesso. Entregue ao cliente o produto/serviço/desconto conforme indicado no cupom da tela anterior. <br />Este cupom não poderá ser utilizado novamente. <br />Agradeça ao cliente por comprar nos estabelecimentos parceiros da Contaponto.';

// Vale-pontos
$lang['cadastrar_vale_pontos_sucesso_tipo'] = 'success';
$lang['cadastrar_vale_pontos_sucesso_titulo'] = 'Vale-pontos cadastrado com sucesso';
$lang['cadastrar_vale_pontos_sucesso_mensagem'] = anchor('usuario_logado/cadastrar_vale_pontos', 'Cadastrar outro vale-pontos');

$lang['cadastrar_vale_pontos_erro_usuario_tipo'] = 'danger';
$lang['cadastrar_vale_pontos_erro_usuario_titulo'] = 'Erro ao cadastrar vale-pontos';
$lang['cadastrar_vale_pontos_erro_usuario_mensagem'] = 'Ocorreu um erro ao tentar encontrar o consumidor que receberia os pontos. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-013"';

$lang['cadastrar_vale_pontos_erro_tipo'] = 'danger';
$lang['cadastrar_vale_pontos_erro_titulo'] = 'Erro ao cadastrar vale-pontos';
$lang['cadastrar_vale_pontos_erro_mensagem'] = 'Ocorreu um erro ao tentar cadastrar vale-pontos para o cliente. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-014"';

$lang['cadastrar_vale_pontos_erro_lote_tipo'] = 'danger';
$lang['cadastrar_vale_pontos_erro_lote_titulo'] = 'Erro ao cadastrar vale-pontos';
$lang['cadastrar_vale_pontos_erro_lote_mensagem'] = 'O lote do vale-pontos informado não foi encontrado. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-015"';




//Default
$lang['default_tipo'] = 'danger';
$lang['default_titulo'] = 'Erro';
$lang['default_mensagem'] = 'Ocorreu um erro no processo. Por favor, entre em contato com a equipe do site. Código do erro: "ERR-9999"';

/* End of file cp_mensagem_lang.php */
/* Location: ./system/language/english/cp_mensagem_lang.php */
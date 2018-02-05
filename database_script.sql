-- phpMyAdmin SQL Dump
-- version 4.0.10.20
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 05, 2018 at 12:23 AM
-- Server version: 5.5.58
-- PHP Version: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `conta_ponto_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ajuste_fatura`
--

CREATE TABLE IF NOT EXISTS `ajuste_fatura` (
  `id_ajuste_fatura` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(70) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `fk_fatura` int(11) NOT NULL,
  `ts_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_usuario_criacao` int(11) NOT NULL,
  `ts_cancelamento` timestamp NULL DEFAULT NULL,
  `fk_usuario_cancelamento` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_ajuste_fatura`),
  KEY `fk_ajuste_fatura_fatura1_idx` (`fk_fatura`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- --------------------------------------------------------

--
-- Table structure for table `banco`
--

CREATE TABLE IF NOT EXISTS `banco` (
  `id_banco` int(11) NOT NULL AUTO_INCREMENT,
  `nome_banco` varchar(45) NOT NULL,
  `codigo_banco` varchar(3) NOT NULL,
  PRIMARY KEY (`id_banco`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `banco`
--

INSERT INTO `banco` (`id_banco`, `nome_banco`, `codigo_banco`) VALUES
(1, 'Banco do Brasil S.A.', '001'),
(2, 'Banco Santander (Brasil) S.A.', '033'),
(3, 'Banco Bradesco BBI S.A.', '036'),
(4, 'Caixa Econômica Federal', '104'),
(5, 'Itaú Unibanco S.A.', '341'),
(6, 'HSBC Bank Brasil S.A.', '399'),
(7, 'Banco Safra S.A.', '422'),
(8, 'Citibank N.A.', '477'),
(9, 'BANCOOB - Banco Cooperativo do Brasil', '756');

-- --------------------------------------------------------

--
-- Table structure for table `categoria_faq`
--

CREATE TABLE IF NOT EXISTS `categoria_faq` (
  `id_categoria_faq` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(60) NOT NULL,
  `restricao_tipo_acesso` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_categoria_faq`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `categoria_faq`
--

INSERT INTO `categoria_faq` (`id_categoria_faq`, `categoria`, `restricao_tipo_acesso`) VALUES
(1, 'Dúvidas Gerais', '0'),
(2, 'Acumular pontos', '0'),
(3, 'Resgatar Pontos', '0'),
(4, 'Utilizar Cupom', '0'),
(5, 'Lojista', '4,5');

-- --------------------------------------------------------

--
-- Table structure for table `cidade`
--

CREATE TABLE IF NOT EXISTS `cidade` (
  `id_cidade` int(11) NOT NULL AUTO_INCREMENT,
  `nome_cidade` varchar(100) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `eh_ativa` varchar(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_cidade`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cidade`
--

INSERT INTO `cidade` (`id_cidade`, `nome_cidade`, `uf`, `eh_ativa`) VALUES
(1, 'São Paulo', 'SP', 'S');

-- --------------------------------------------------------

--
-- Table structure for table `consumo_pontuacao`
--

CREATE TABLE IF NOT EXISTS `consumo_pontuacao` (
  `id_consumo_pontuacao` int(11) NOT NULL AUTO_INCREMENT,
  `fk_usuario_cupom` int(11) DEFAULT NULL,
  `fk_pontuacao` int(11) DEFAULT NULL,
  `pontos_consumidos` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_consumo_pontuacao`),
  KEY `fk_consumo_pontuacao_usuario_cupom1_idx` (`fk_usuario_cupom`),
  KEY `fk_consumo_pontuacao_pontuacao1_idx` (`fk_pontuacao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `controle_erro`
--

CREATE TABLE IF NOT EXISTS `controle_erro` (
  `ts_acao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `acao` varchar(15) NOT NULL,
  `tipo_identificador` varchar(10) NOT NULL,
  `identificador` varchar(100) NOT NULL,
  KEY `idx_ts_acao` (`ts_acao`),
  KEY `idx_identificador` (`identificador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cupom`
--

CREATE TABLE IF NOT EXISTS `cupom` (
  `id_cupom` int(11) NOT NULL AUTO_INCREMENT,
  `fk_loja` int(11) NOT NULL,
  `titulo_cupom` varchar(45) NOT NULL,
  `tipo_cupom` varchar(1) NOT NULL COMMENT 'V=vale compras, P=produto ou serviço especifico.',
  `vale_compra` decimal(6,2) NOT NULL COMMENT 'valor que deve ser exibido ao usuario em caso de ',
  `breve_descricao` varchar(300) NOT NULL,
  `preco_pontos` int(11) DEFAULT NULL COMMENT 'indica quantos pontos o consumidor precisa gastar para comprar o cupom',
  `preco_moeda` decimal(6,2) DEFAULT NULL COMMENT 'caso o consumidor possa comprar o cupom com dinheiro real, informar o preco',
  `preco_lojista` decimal(7,2) NOT NULL COMMENT 'Valor que deve ser pago ao lojista para cada cupom recebido',
  `imagem_principal` varchar(60) DEFAULT NULL,
  `descricao_detalhada` varchar(1000) DEFAULT NULL COMMENT 'descrever em detalhe as regras de funcionamento do cupom, restricoes, etc.',
  `validade_dias` int(3) NOT NULL COMMENT 'Informar a quantidade de dias que deve ser somada a data de emissao do cupom para determinar seu prazo de validade.',
  `ts_inicio_veiculacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ts_fim_veiculacao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_cupom`),
  KEY `idx_veiculacao` (`ts_fim_veiculacao`,`ts_inicio_veiculacao`),
  KEY `fk_cupom_loja1_idx` (`fk_loja`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=210 ;

--
-- Dumping data for table `cupom`
--


--
-- Table structure for table `desconto`
--

CREATE TABLE IF NOT EXISTS `desconto` (
  `id_desconto` int(11) NOT NULL AUTO_INCREMENT,
  `dt_inicio_validade` date NOT NULL,
  `dt_fim_validade` date NOT NULL,
  `qtd_usuarios_de` int(11) NOT NULL,
  `qtd_usuarios_ate` int(11) NOT NULL,
  `percentual_desconto` decimal(5,3) NOT NULL COMMENT '10% de desconto, informar 10',
  `ts_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_usuario_criacao` int(11) NOT NULL,
  PRIMARY KEY (`id_desconto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `desconto`
--

INSERT INTO `desconto` (`id_desconto`, `dt_inicio_validade`, `dt_fim_validade`, `qtd_usuarios_de`, `qtd_usuarios_ate`, `percentual_desconto`, `ts_criacao`, `fk_usuario_criacao`) VALUES
(1, '2016-04-01', '2017-04-30', 26, 49, '1.000', '2016-04-28 15:54:12', 1),
(2, '2016-04-01', '2017-04-30', 50, 99, '3.000', '2016-04-28 15:55:05', 1),
(3, '2016-04-01', '2017-04-30', 100, 199, '6.000', '2016-04-28 15:55:47', 1),
(4, '2016-04-01', '2017-04-30', 200, 999999, '10.000', '2016-04-28 15:56:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `estado_civil`
--

CREATE TABLE IF NOT EXISTS `estado_civil` (
  `id_estado_civil` int(11) NOT NULL AUTO_INCREMENT,
  `estado_civil` varchar(45) NOT NULL,
  PRIMARY KEY (`id_estado_civil`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `estado_civil`
--

INSERT INTO `estado_civil` (`id_estado_civil`, `estado_civil`) VALUES
(0, '-'),
(1, 'Solteiro (a)'),
(2, 'Casado (a)'),
(3, 'Separado (a)'),
(4, 'Divorciado (a)'),
(5, 'Viúvo (a)');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id_faq` int(11) NOT NULL AUTO_INCREMENT,
  `fk_categoria_faq` int(11) NOT NULL,
  `pergunta` varchar(200) NOT NULL,
  `resposta` varchar(1000) NOT NULL,
  `ordem` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_faq`),
  KEY `fk_faq_categoria_faq_idx` (`fk_categoria_faq`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id_faq`, `fk_categoria_faq`, `pergunta`, `resposta`, `ordem`) VALUES
(1, 1, 'O que é a Contaponto?', '<p>\r\n	A Contaponto &eacute; uma Rede de fideliza&ccedil;&atilde;o a qual re&uacute;ne, em uma &uacute;nica conta, os benef&iacute;cios, produtos e servi&ccedil;os oferecidos por diferentes empresas e seus respectivos programas de relacionamento e/ou de incentivo &agrave; fidelidade comercial.</p>\r\n<p>\r\n	Com o objetivo de recompensar e valorizar os Participantes pelo relacionamento mantido com os Parceiros e com a Contaponto, a rede de fideliza&ccedil;&atilde;o permite que pessoas f&iacute;sicas se associem, tornem-se Participantes e possam escolher em quais Parceiros desejam acumular Pontos e em quais desejam resgatar benef&iacute;cios, produtos e servi&ccedil;os, conforme sua conveni&ecirc;ncia.</p>\r\n', 10),
(2, 1, 'Como participar da rede de fidelidade Contaponto?', '<div>\r\n	Basta fazer seu cadastro.</div>\r\n<div>\r\n	Voc&ecirc; pode se cadastrar diretamente aqui no site Contaponto, clicando no menu &quot;Cadastro&quot; no canto superior direito do site. Em seguida, voc&ecirc; receber&aacute; um e-mail com informa&ccedil;&otilde;es para confirmar seu cadastro.</div>\r\n', 20),
(4, 1, 'Fiz meu cadastro, mas não recebi o e-mail de confirmação. O que fazer?', '<p>\r\n	&Agrave;s vezes e-mails podem ser direcionados para uma pasta diferente da &quot;Caixa de entrada&quot;, por exemplo a pasta de &quot;Spam&quot;. Verifique essas pastas e, caso n&atilde;o encontre, envie uma mensagem por meio da p&aacute;gina &quot;Fale Conosco&quot; aqui no site Contaponto.</p>\r\n', 40),
(5, 2, 'Quais são as lojas parceiras da Contaponto Rede de Fidelidade?', '<p>Acesse a p&aacute;gina &quot;Ganhe pontos&quot; por meio do menu do site Contaponto para ver a lista completa das lojas parceiras e o percentual de desconto que cada uma concede aos membros da Contaponto Rede de Fidelidade.</p>', 10),
(6, 1, 'Existe um cartão fidelidade físico? Para que serve?', '<p>\r\n	Sim, existe.&nbsp;Sua finalidade &eacute; apenas facilitar sua identifica&ccedil;&atilde;o como membro da rede pelas lojas parceiras. Caso n&atilde;o esteja com ele no momento da compra, basta informar seu CPF ou o endere&ccedil;o de e-mail cadastrado e os pontos ser&atilde;o concedidos da mesma forma.</p>\r\n', 50),
(9, 1, 'Perdi meu cartão fidelidade, o que devo fazer?', '<div>\r\n	Se desejar, pode solicitar outro em qualquer uma das lojas parceiras. E n&atilde;o se preocupe, voc&ecirc; n&atilde;o perde os pontos que j&aacute; tinha pois toda concess&atilde;o de pontos &eacute; creditada no site Contaponto pela intenet, ou seja, os pontos n&atilde;o ficam armazenados no cart&atilde;o f&iacute;sico.</div>\r\n', 61),
(10, 1, 'Como faço para saber pelo que os pontos podem ser trocados?', '<p>\r\n	Acesse a p&aacute;gina &quot;Troque seus pontos&quot; aqui no site Contaponto para ver a lista completa de produtos, servi&ccedil;os, entre outros benef&iacute;cios, e quantos pontos s&atilde;o necess&aacute;rios para adquirir cada um deles.</p>\r\n', 70),
(11, 1, 'Por que tenho que definir uma senha? Posso contá-la para alguém?', '<div>\r\n	O conjunto formado por seu endere&ccedil;o de email e sua senha s&atilde;o a sua identifica&ccedil;&atilde;o para o site Contaponto. Ou seja, &eacute; necess&aacute;rio inform&aacute;-la para que o site Contaponto identifique voc&ecirc;, e permita trocar seus pontos por produtos e servi&ccedil;os, entre outras funcionalidades. Dessa forma, &eacute; contra as regras do site compartilhar sua senha com outras pessoas, pois qualquer pessoa que possua sua senha pode se passar por voc&ecirc; e trocar seus pontos.</div>\r\n', 80),
(12, 3, 'Como recebo os produtos e serviços que eu trocar pelos pontos?', '<p>\r\n	Quando voc&ecirc; trocar seus pontos por um produto ou servi&ccedil;o no site Contaponto, &eacute; gerado um cupom. Basta apresentar este cupom na loja especificada, juntamente com seu documento de identidade com foto e que contenha seu n&uacute;mero de CPF, e receber seu produto ou servi&ccedil;o.</p>\r\n', 90),
(13, 4, 'Preciso imprimir o cupom para apresentar na loja?', '<div>\r\n	Para adquirir o produto ou servi&ccedil;o voc&ecirc; pode escolher uma das tr&ecirc;s formas:</div>\r\n<ol>\r\n	<li>\r\n		pode apresentar o cupom impresso;</li>\r\n	<li>\r\n		pode apresentar o cupom na tela de seu celular;</li>\r\n	<li>\r\n		ou pode apresentar o n&uacute;mero e o c&oacute;digo do cupom.</li>\r\n</ol>\r\n<div>\r\n	Lembre-se tamb&eacute;m de apresentar seu documento com foto e n&uacute;mero do CPF.</div>\r\n', 40),
(14, 1, 'Quem pode participar da rede de fidelidade?', '<p>\r\n	Poder&aacute; aderir e participar da rede Contaponto toda pessoa f&iacute;sica que for inscrita no CPF (Cadastro de Pessoa F&iacute;sica) e tenha realizado o seu cadastro de forma correta e completa, aqui no site ou em qualquer loja parceira.</p>\r\n', 15),
(15, 2, 'Quantos pontos eu ganho por comprar nas lojas parceiras da Contaponto Rede de Fidelidade?', '<p>Você ganha 1 ponto a cada 5 centavos economizados. Em outras palavras, você recebe 20 pontos para cada R$ 1,00 de desconto que receber da loja.</p>\r\n<p>Todas as lojas parceiras da Contaponto Rede de Fidelidade concedem descontos para membros. A quantidade de pontos &eacute; calculada de acordo com o valor do desconto, ou seja com o valor que você economizar. Isso quer dizer que, para saber quantos pontos você ganha, basta multiplicar o valor que você economizou por 20.</p>', 20),
(16, 2, 'Um exemplo de acumulo de pontos:', '<p><b>Por exemplo:</b> você foi a uma loja parceira da Contaponto que oferece 15%&nbsp;de&nbsp;desconto. E comprou R$&nbsp;200,00 em produtos.<br>\r\n	Aplicando o desconto de 15%, você paga apenas R$&nbsp;170,00, pois recebe um desconto de R$&nbsp;30,00.<br>\r\n    Além do desconto, a loja vai creditar 600 pontos no seu saldo da Contaponto. Ou seja, você recebe 20 pontos para cada real economizado, como você economizou R$&nbsp;30,00, você recebe  600 pontos! (30 * 20 = 600 pontos)<br>\r\n	Um outro exemplo: se você gastar R$ 10,00 nessa mesma loja, você recebe R$ 1,50 de desconto e paga apenas R$ 8,50, e ainda recebe 30 pontos. (1,50 * 20 = 30 pontos).</p>', 30);

-- --------------------------------------------------------

--
-- Table structure for table `fatura`
--

CREATE TABLE IF NOT EXISTS `fatura` (
  `id_fatura` int(11) NOT NULL AUTO_INCREMENT,
  `fk_loja` int(11) DEFAULT NULL,
  `dt_vencimento` date DEFAULT NULL,
  `ts_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `valor_total_ref` decimal(8,2) DEFAULT NULL,
  `fk_faturamento` int(11) DEFAULT NULL,
  `data_pgto_boleto` date DEFAULT NULL,
  `data_estorno_fatura` date DEFAULT NULL,
  PRIMARY KEY (`id_fatura`),
  KEY `fk_faturamento` (`fk_faturamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=357 ;


--
-- Table structure for table `faturamento`
--

CREATE TABLE IF NOT EXISTS `faturamento` (
  `id_faturamento` int(11) NOT NULL AUTO_INCREMENT,
  `dt_inicio_faturamento` date NOT NULL,
  `dt_fim_faturamento` date NOT NULL,
  `dt_vencimento_padrao` date NOT NULL,
  `status` varchar(1) DEFAULT NULL COMMENT 'N = Novo, P = Em processamento, F = Finalizado, E = com Erro',
  `ts_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_usuario_criacao` int(11) NOT NULL,
  `ts_execucao_programada` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Indica o horario mínimo para execução do faturamento',
  `ts_execucao_inicio` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Horario real de inicio de execucao do faturamento',
  `ts_execucao_fim` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'horario real de termino de execucao do faturamento',
  `qtd_faturas_geradas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_faturamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;


-- --------------------------------------------------------

--
-- Table structure for table `funcao_tipo_acesso`
--

CREATE TABLE IF NOT EXISTS `funcao_tipo_acesso` (
  `ad_funcao` varchar(5) NOT NULL,
  `fk_tipo_acesso` int(11) NOT NULL,
  PRIMARY KEY (`ad_funcao`,`fk_tipo_acesso`),
  KEY `ad_funcao` (`ad_funcao`),
  KEY `fk_tipo_acesso_idx` (`fk_tipo_acesso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_fatura`
--

CREATE TABLE IF NOT EXISTS `item_fatura` (
  `id_item_fatura` int(11) NOT NULL AUTO_INCREMENT,
  `fk_fatura` int(11) DEFAULT NULL,
  `fk_mensalidade` int(11) DEFAULT NULL,
  `fk_pontuacao` int(11) DEFAULT NULL,
  `fk_usuario_cupom` int(11) DEFAULT NULL,
  `valor` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id_item_fatura`),
  KEY `fk_item_fatura_fatura1_idx` (`fk_fatura`),
  KEY `fk_item_fatura_pontuacao1_idx` (`fk_pontuacao`),
  KEY `fk_item_fatura_mensalidade1_idx` (`fk_mensalidade`),
  KEY `fk_item_fatura_usuario_cupom_idx` (`fk_usuario_cupom`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=616 ;

--
-- Table structure for table `limite_credito`
--

CREATE TABLE IF NOT EXISTS `limite_credito` (
  `fk_loja` int(11) NOT NULL,
  `limite_max` int(11) NOT NULL,
  `saldo_atual` int(11) NOT NULL,
  `ts_ult_atu_saldo` timestamp NULL DEFAULT NULL,
  `ts_inicio_validade` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ts_fim_validade` timestamp NULL DEFAULT '2030-12-31 23:59:59',
  PRIMARY KEY (`fk_loja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `loja`
--

CREATE TABLE IF NOT EXISTS `loja` (
  `id_loja` int(11) NOT NULL AUTO_INCREMENT,
  `cnpj` varchar(14) NOT NULL,
  `nome_fantasia` varchar(100) NOT NULL,
  `razao_social` varchar(100) NOT NULL,
  `fk_segmento` int(11) NOT NULL,
  `slogan` varchar(300) DEFAULT NULL,
  `link_mapa` varchar(300) DEFAULT NULL,
  `cpf_proprietario` varchar(11) DEFAULT NULL,
  `nome_proprietario` varchar(100) DEFAULT NULL,
  `telefone_proprietario` varchar(45) DEFAULT NULL,
  `logo` varchar(60) DEFAULT NULL,
  `endereco` varchar(100) NOT NULL,
  `complemento` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) NOT NULL,
  `fk_cidade` int(11) NOT NULL,
  `cep` int(11) NOT NULL,
  `telefone` varchar(45) DEFAULT NULL,
  `site` varchar(45) DEFAULT NULL,
  `horario_funcionamento` varchar(80) DEFAULT NULL,
  `fk_banco` int(11) DEFAULT NULL,
  `agencia` varchar(10) DEFAULT NULL,
  `digito_agencia` varchar(1) DEFAULT NULL,
  `tipo_conta` varchar(1) DEFAULT NULL COMMENT 'P=poupanca e C=conta corrente',
  `conta` varchar(10) DEFAULT NULL,
  `digito_conta` varchar(1) DEFAULT NULL,
  `status` varchar(1) NOT NULL COMMENT 'A=Ativa, I=Inativa',
  `valor_em_compras` decimal(6,2) DEFAULT NULL COMMENT 'A cada valor_em_compras gasto na loja, o cosumidor ganha qtd_pontos em pontos',
  `item_em_compras` varchar(50) DEFAULT NULL,
  `qtd_pontos` int(11) DEFAULT NULL COMMENT 'A cada valor_em_compras gasto na loja, o cosumidor ganha qtd_pontos em pontos',
  `percentual_desconto` tinyint(4) DEFAULT NULL COMMENT 'Percentual de desconto que o cliente recebe ao comprar na loja. 12 = 12%',
  `restricao` varchar(300) DEFAULT NULL,
  `observacao` varchar(1000) DEFAULT NULL,
  `email_contato` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_loja`),
  UNIQUE KEY `cnpj_UNIQUE` (`cnpj`),
  UNIQUE KEY `nome_fantasia_UNIQUE` (`nome_fantasia`),
  KEY `fk_loja_banco1_idx` (`fk_banco`),
  KEY `fk_loja_segmento_idx` (`fk_segmento`),
  KEY `fk_loja_cidade_idx` (`fk_cidade`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

--
-- Table structure for table `lote_vale_ponto`
--

CREATE TABLE IF NOT EXISTS `lote_vale_ponto` (
  `id_lote_vale_ponto` int(11) NOT NULL AUTO_INCREMENT,
  `fk_loja` int(11) NOT NULL,
  `nr_inicial` int(11) NOT NULL,
  `nr_final` int(11) NOT NULL,
  `qtd_pontos` int(11) NOT NULL,
  `dt_ini_validade` date NOT NULL,
  `dt_fim_validade` date NOT NULL,
  `dt_fim_validade_original` date NOT NULL COMMENT 'data de validade definida ao criar o lote, caso a dt_fim_validade seja alterada, esta deve identificar qual a data original de validade',
  `status` varchar(1) NOT NULL COMMENT 'A=ativo, I=inativo, C=cancelado, E=expirado',
  `ts_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_lote_vale_ponto`),
  KEY `idx_numeracao` (`nr_inicial`,`nr_final`),
  KEY `idx_fk_loja` (`fk_loja`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Table structure for table `mensalidade`
--

CREATE TABLE IF NOT EXISTS `mensalidade` (
  `id_mensalidade` int(11) NOT NULL AUTO_INCREMENT,
  `fk_loja` int(11) DEFAULT NULL,
  `dia_faturamento` int(11) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `ts_ini_validade` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ts_fim_validade` timestamp NOT NULL DEFAULT '2030-12-31 23:59:59',
  PRIMARY KEY (`id_mensalidade`),
  KEY `fk_mensalidade_loja1_idx` (`fk_loja`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- Table structure for table `parametro`
--

CREATE TABLE IF NOT EXISTS `parametro` (
  `id_parametro` int(11) NOT NULL AUTO_INCREMENT,
  `nome_parametro` varchar(45) NOT NULL,
  `valor1` varchar(200) NOT NULL,
  `valor2` varchar(200) DEFAULT NULL,
  `descricao` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id_parametro`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `parametro`
--

INSERT INTO `parametro` (`id_parametro`, `nome_parametro`, `valor1`, `valor2`, `descricao`) VALUES
(1, 'Limite de credito lojista padrão', '6000', NULL, 'V1 é a quantidade de pontos que os lojistas tem de crédito, por padrão, para ceder para os clientes'),
(2, 'Limite extrato pontos consumidor', '60', NULL, 'V1 é a quantidade de dias que o extrato de pontos do consumidor deve exibir'),
(3, 'Limite extrato cupons consumidor', '60', NULL, 'V1 é a quantidade de dias que o extrato de cupons do consumidor deve exibir'),
(4, 'Limite extrato pontos lojista', '60', NULL, 'V1 é a quantidade de dias que o extrato de pontos do lojista deve exibir'),
(5, 'Limite extrato cadastros lojista', '60', NULL, 'V1 é a quantidade de dias que o extrato de usuarios cadastrados deve exibir');

-- --------------------------------------------------------

--
-- Table structure for table `pontuacao`
--

CREATE TABLE IF NOT EXISTS `pontuacao` (
  `id_pontuacao` int(11) NOT NULL AUTO_INCREMENT,
  `ts_pontuacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_loja` int(11) DEFAULT NULL COMMENT 'loja que concedeu os pontos para o usuario. ',
  `fk_usuario_cupom` int(11) DEFAULT NULL COMMENT 'refere-se ao id_usuario_cupom que for estornado, o valor deve ser creditado novamente para o usuario. caso este campo seja informado, uma loja nao deve ser informada.',
  `nr_vale_ponto` int(11) DEFAULT NULL,
  `fk_lote_vale_ponto` int(11) DEFAULT NULL,
  `fk_usuario` int(11) NOT NULL,
  `qtd_pontos` int(11) NOT NULL,
  `validade_pontos` date NOT NULL,
  `fk_usuario_criacao` int(11) NOT NULL,
  `qtd_disponivel` int(11) NOT NULL COMMENT 'caso o usuario adquira algum cupom que consuma apenas parte dos pontos deste lancamento, a diferenca deve ser atualizada neste campo',
  `fk_fatura` int(11) DEFAULT NULL,
  `ts_estorno` timestamp NULL DEFAULT NULL,
  `fk_usuario_estorno` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pontuacao`),
  KEY `idx_fk_loja_ts_pontuacao` (`fk_loja`,`ts_pontuacao`),
  KEY `idx_fk_usuario_validade_pontos` (`fk_usuario`,`validade_pontos`),
  KEY `idx_validade_pontos` (`validade_pontos`),
  KEY `fk_pontuacao_usuario2_idx` (`fk_usuario_criacao`),
  KEY `fk_pontuacao_usuario3_idx` (`fk_usuario_estorno`),
  KEY `fk_pontuacao_usuario_cupom1_idx` (`fk_usuario_cupom`),
  KEY `idx_fk_usuario_ts_pontuacao` (`fk_usuario`,`ts_pontuacao`),
  KEY `idx_nr_vale_ponto` (`nr_vale_ponto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=200 ;

--
-- Table structure for table `preco`
--

CREATE TABLE IF NOT EXISTS `preco` (
  `id_preco` int(11) NOT NULL AUTO_INCREMENT,
  `dt_inicio_validade` date NOT NULL,
  `dt_fim_validade` date NOT NULL,
  `item` varchar(1) NOT NULL DEFAULT 'P' COMMENT 'P = Ponto',
  `qtd_item` int(11) DEFAULT NULL,
  `valor` decimal(10,4) DEFAULT NULL,
  `ts_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_usuario_criacao` int(11) NOT NULL,
  PRIMARY KEY (`id_preco`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `preco`
--

INSERT INTO `preco` (`id_preco`, `dt_inicio_validade`, `dt_fim_validade`, `item`, `qtd_item`, `valor`, `ts_criacao`, `fk_usuario_criacao`) VALUES
(1, '2016-04-01', '2017-05-10', 'P', 1, '0.0400', '2016-04-28 01:27:02', 1),
(2, '2016-04-01', '2030-12-31', 'C', 1, '0.0200', '2016-04-28 01:27:37', 1),
(3, '2017-05-11', '2030-12-31', 'P', 1, '0.0500', '2017-05-19 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `segmento`
--

CREATE TABLE IF NOT EXISTS `segmento` (
  `id_segmento` int(11) NOT NULL AUTO_INCREMENT,
  `nome_segmento` varchar(100) NOT NULL,
  PRIMARY KEY (`id_segmento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `segmento`
--

INSERT INTO `segmento` (`id_segmento`, `nome_segmento`) VALUES
(1, 'Academia e Esportes'),
(2, 'Alimentação'),
(3, 'Animais de estimação e agropecuária'),
(4, 'Autos e Motos'),
(5, 'Saúde e Beleza'),
(6, 'Casa, Construção e Decoração'),
(7, 'Educação'),
(8, 'Flores e Jardinagem'),
(9, 'Informática e suprimentos'),
(10, 'Lazer'),
(11, 'Moda e Acessórios'),
(12, 'Papelaria e Armarinho'),
(13, 'Profissional'),
(14, 'Publicidade'),
(15, 'Turismo (hotéis, agências, etc)'),
(16, 'Outros'),
(17, 'Comunicação visual e gráfica rápida'),
(18, 'Óticas e relojoarias'),
(19, 'Móveis e eletrodomésticos'),
(20, 'Perfumaria e cosméticos');

-- --------------------------------------------------------

--
-- Table structure for table `tipo_acesso`
--

CREATE TABLE IF NOT EXISTS `tipo_acesso` (
  `id_tipo_acesso` int(11) NOT NULL AUTO_INCREMENT,
  `nome_tipo_acesso` varchar(45) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  PRIMARY KEY (`id_tipo_acesso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tipo_acesso`
--

INSERT INTO `tipo_acesso` (`id_tipo_acesso`, `nome_tipo_acesso`, `descricao`) VALUES
(1, 'Administrador', 'Utilizado pela equipe ContaPonto, detem acesso completo ao sistema.'),
(2, 'Comercial CP', 'Utilizado pela equipe ContaPonto, permite gerar faturamento, boletos, cadastrar lojas, etc'),
(3, 'Consumidor', 'Utilizado pelos consumidores'),
(4, 'Gerente_loja', 'Utilizado pelos proprietarios dos comercios.'),
(5, 'Vendedor_loja', 'Utilizado pelos vendedores dos comercios, permite atribuir pontos aos consumidores'),
(6, 'Associação', 'Concede os acessos de lojista e ainda permite cadastrar valepontos para terceiros.');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `sobrenome` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cpf` varchar(11) NOT NULL,
  `sal` varchar(100) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `ativo` varchar(1) NOT NULL DEFAULT 'I' COMMENT 'I=inativo, A=ativo, F=acesso via facebook, G=acesso via googleplus, R=acesso por outras redes sociais',
  `fk_tipo_acesso` int(11) NOT NULL,
  `celular` varchar(50) DEFAULT NULL,
  `cep` int(8) DEFAULT NULL,
  `ultima_fk_cidade` int(11) DEFAULT NULL COMMENT 'indica o ID da ultima cidade selecionada para visualizar o conteudo',
  `data_nascimento` date DEFAULT NULL,
  `genero` varchar(1) DEFAULT NULL,
  `fk_estado_civil` int(11) DEFAULT NULL,
  `ts_ult_acesso` timestamp NOT NULL DEFAULT '2016-01-01 00:00:00',
  `saldo` int(11) NOT NULL DEFAULT '0',
  `ts_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_loja_cadastro` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `cpf_UNIQUE` (`cpf`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk1_idx` (`fk_estado_civil`),
  KEY `fk_usuario_tipo_acesso1_idx` (`fk_tipo_acesso`),
  KEY `fk_usuario_loja_idx` (`fk_loja_cadastro`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=622 ;


--
-- Table structure for table `usuario_cupom`
--

CREATE TABLE IF NOT EXISTS `usuario_cupom` (
  `id_usuario_cupom` int(11) NOT NULL AUTO_INCREMENT,
  `fk_usuario` int(11) NOT NULL,
  `fk_cupom` int(11) NOT NULL,
  `ts_emissao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `codigo_cupom` varchar(45) NOT NULL,
  `dt_validade` date NOT NULL,
  `ts_estorno` timestamp NULL DEFAULT NULL,
  `ts_utilizacao` timestamp NULL DEFAULT NULL,
  `fk_usuario_utilizacao` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_usuario_cupom`),
  KEY `fk_usuario_cupom_cupom1_idx` (`fk_cupom`),
  KEY `fk_usuario_cupom_usuario1_idx` (`fk_usuario`),
  KEY `fk_usuario_cupom_ts_utilizacao_idx` (`ts_utilizacao`),
  KEY `fk_usuario_cupom_ts_emissao_idx` (`ts_emissao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='			' AUTO_INCREMENT=24 ;

--
-- Table structure for table `usuario_loja`
--

CREATE TABLE IF NOT EXISTS `usuario_loja` (
  `id_usuario_loja` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_usuario` int(11) NOT NULL,
  `fk_id_loja` int(11) NOT NULL,
  `ts_ini_validade` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ts_fim_validade` timestamp NOT NULL DEFAULT '2030-12-31 23:59:59',
  PRIMARY KEY (`id_usuario_loja`),
  KEY `fk_usuario_has_loja_loja1_idx` (`fk_id_loja`),
  KEY `fk_usuario_has_loja_usuario1_idx` (`fk_id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ajuste_fatura`
--
ALTER TABLE `ajuste_fatura`
  ADD CONSTRAINT `fk_ajuste_fatura_fatura1` FOREIGN KEY (`fk_fatura`) REFERENCES `fatura` (`id_fatura`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `consumo_pontuacao`
--
ALTER TABLE `consumo_pontuacao`
  ADD CONSTRAINT `fk_consumo_pontuacao_pontuacao1` FOREIGN KEY (`fk_pontuacao`) REFERENCES `pontuacao` (`id_pontuacao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_consumo_pontuacao_usuario_cupom1` FOREIGN KEY (`fk_usuario_cupom`) REFERENCES `usuario_cupom` (`id_usuario_cupom`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cupom`
--
ALTER TABLE `cupom`
  ADD CONSTRAINT `fk_cupom_loja1` FOREIGN KEY (`fk_loja`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `faq`
--
ALTER TABLE `faq`
  ADD CONSTRAINT `fk_faq_categoria_faq` FOREIGN KEY (`fk_categoria_faq`) REFERENCES `categoria_faq` (`id_categoria_faq`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `fatura`
--
ALTER TABLE `fatura`
  ADD CONSTRAINT `fk_faturamento` FOREIGN KEY (`fk_faturamento`) REFERENCES `faturamento` (`id_faturamento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `funcao_tipo_acesso`
--
ALTER TABLE `funcao_tipo_acesso`
  ADD CONSTRAINT `fk_tipo_acesso` FOREIGN KEY (`fk_tipo_acesso`) REFERENCES `tipo_acesso` (`id_tipo_acesso`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `item_fatura`
--
ALTER TABLE `item_fatura`
  ADD CONSTRAINT `fk_item_fatura_fatura1` FOREIGN KEY (`fk_fatura`) REFERENCES `fatura` (`id_fatura`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_item_fatura_mensalidade1` FOREIGN KEY (`fk_mensalidade`) REFERENCES `mensalidade` (`id_mensalidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_item_fatura_pontuacao1` FOREIGN KEY (`fk_pontuacao`) REFERENCES `pontuacao` (`id_pontuacao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_item_fatura_usuario_cupom` FOREIGN KEY (`fk_usuario_cupom`) REFERENCES `usuario_cupom` (`id_usuario_cupom`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `limite_credito`
--
ALTER TABLE `limite_credito`
  ADD CONSTRAINT `fk_limite_credito_loja` FOREIGN KEY (`fk_loja`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `loja`
--
ALTER TABLE `loja`
  ADD CONSTRAINT `fk_loja_banco1` FOREIGN KEY (`fk_banco`) REFERENCES `banco` (`id_banco`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_loja_cidade` FOREIGN KEY (`fk_cidade`) REFERENCES `cidade` (`id_cidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_loja_segmento` FOREIGN KEY (`fk_segmento`) REFERENCES `segmento` (`id_segmento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `lote_vale_ponto`
--
ALTER TABLE `lote_vale_ponto`
  ADD CONSTRAINT `lote_vale_ponto_ibfk_1` FOREIGN KEY (`fk_loja`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `mensalidade`
--
ALTER TABLE `mensalidade`
  ADD CONSTRAINT `fk_mensalidade_loja1` FOREIGN KEY (`fk_loja`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pontuacao`
--
ALTER TABLE `pontuacao`
  ADD CONSTRAINT `fk_pontuacao_loja1` FOREIGN KEY (`fk_loja`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pontuacao_usuario1` FOREIGN KEY (`fk_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pontuacao_usuario2` FOREIGN KEY (`fk_usuario_criacao`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pontuacao_usuario3` FOREIGN KEY (`fk_usuario_estorno`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pontuacao_usuario_cupom1` FOREIGN KEY (`fk_usuario_cupom`) REFERENCES `usuario_cupom` (`id_usuario_cupom`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_estado_civil` FOREIGN KEY (`fk_estado_civil`) REFERENCES `estado_civil` (`id_estado_civil`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuario_loja` FOREIGN KEY (`fk_loja_cadastro`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuario_tipo_acesso` FOREIGN KEY (`fk_tipo_acesso`) REFERENCES `tipo_acesso` (`id_tipo_acesso`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `usuario_cupom`
--
ALTER TABLE `usuario_cupom`
  ADD CONSTRAINT `fk_usuario_cupom_cupom1` FOREIGN KEY (`fk_cupom`) REFERENCES `cupom` (`id_cupom`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuario_cupom_usuario1` FOREIGN KEY (`fk_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `usuario_loja`
--
ALTER TABLE `usuario_loja`
  ADD CONSTRAINT `fk_usuario_has_loja_loja1` FOREIGN KEY (`fk_id_loja`) REFERENCES `loja` (`id_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuario_has_loja_usuario1` FOREIGN KEY (`fk_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
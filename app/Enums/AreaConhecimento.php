<?php

namespace App\Enums;

enum AreaConhecimento: string
{
    // 000 - Generalidades
    case GENERALIDADES = '000';
    case CONHECIMENTO_TEORIA = '001';
    case SISTEMAS = '003';
    case CIENCIA_DA_COMPUTACAO = '004';
    case PROGRAMACAO_DE_COMPUTADORES = '005';
    case BIBLIOGRAFIAS = '010';
    case CIENCIA_DA_INFORMACAO_E_BIBLIOTECONOMIA = '020';
    case ENCICLOPEDIAS_GERAIS_E_ANUARIO = '030';
    case COLECOES_GERAIS_DE_ENSAIOS = '040';
    case REVISTAS_JORNAIS_E_SERIES = '050';
    case ASSOCIACOES_ORGANIZACOES_E_MUSEUS = '060';
    case MIDIA_INFORMATIVA_JORNALISMO_E_PUBLICACOES = '070';
    case CITACOES = '080';
    case MANUSCRITOS_E_LIVROS_RAROS = '090';

    // 100 - Filosofia
    case FILOSOFIA = '100';
    case METAFISICA = '110';
    case EPISTEMOLOGIA = '120';
    case FENOMENOS_PARANORMAIS = '130';
    case ESCOLAS_FILOSOFICAS_DE_PENSAMENTO = '140';
    case PSICOLOGIA = '150';
    case LOGICA_DIALETICA = '160';
    case ETICA_MORAL = '170';
    case FILOSOFIA_ANTIGA_MEDIEVAL_E_ORIENTAL = '180';
    case FILOSOFIA_OCIDENTAL_MODERNA = '190';

    // 200 - Religião
    case RELIGIAO = '200';
    case FILOSOFIA_E_TEORIA_DA_RELIGIAO = '210';
    case BIBLIA = '220';
    case CRISTIANISMO = '230';
    case PRATICAS_E_HABITOS_CRISTAOS = '240';
    case ORDENS_RELIGIOSAS_E_IGREJAS_LOCAIS = '250';
    case ORGANIZACAO_CRISTA_E_TEOLOGIA_SOCIAL = '260';
    case HISTORIA_DO_CRISTIANISMO = '270';
    case DENOMINACOES_DE_IGREJAS_CRISTAS = '280';
    case OUTRAS_RELIGIOES = '290';

    // 300 - Ciências sociais
    case CIENCIAS_SOCIAIS = '300';
    case ESTATISTICA = '310';
    case CIENCIA_POLITICA = '320';
    case ECONOMIA = '330';
    case DIREITO_JURISPRUDENCIA_JUSTICA = '340';
    case ADMINISTRACAO_PUBLICA_E_CIENCIA_MILITAR = '350';
    case SERVICO_SOCIAL_ASSISTENCIA_SOCIAL = '360';
    case EDUCACAO = '370';
    case COMERCIO_COMUNICACAO_E_TRANSPORTES = '380';
    case COSTUMES_TRAJES_ETIQUETA_FOLCLORE = '390';

    // 400 - Línguas
    case LINGUAS = '400';
    case LINGUISTICA = '410';
    case LINGUA_INGLESA_E_INGLES_ANTIGO = '420';
    case LINGUA_ALEMA_E_AFINS = '430';
    case LINGUA_FRANCESA_E_AFINS = '440';
    case LINGUA_ITALIANA_ROMENO_E_AFINS = '450';
    case LINGUA_ESPANHOLA = '460';
    case LINGUA_PORTUGUESA = '469';
    case LATIM = '470';
    case GREGO_CLASSICO_E_MODERNO = '480';
    case OUTRAS_LINGUAS = '490';

    // 500 - Ciências naturais
    case CIENCIAS_NATURAIS = '500';
    case MATEMATICA = '510';
    case ASTRONOMIA = '520';
    case FISICA = '530';
    case QUIMICA = '540';
    case GEOLOGIA = '550';
    case PALEONTOLOGIA = '560';
    case BIOLOGIA = '570';
    case PLANTAS_BOTANICA = '580';
    case ANIMAIS_ZOOLOGIA = '590';

    // 600 - Tecnologia
    case TECNOLOGIA = '600';
    case MEDICINA = '610';
    case ENGENHARIA = '620';
    case AGRICULTURA = '630';
    case MEDICINA_VETERINARIA = '636.089';
    case ECONOMIA_DOMESTICA_E_VIDA_FAMILIAR = '640';
    case ADMINISTRACAO_E_SERVICOS_AUXILIARES = '650';
    case CONTABILIDADE = '657';
    case ENGENHARIA_QUIMICA = '660';
    case INDUSTRIA_MANUFATURADOS_ENGENHARIA_DE_PRODUCAO = '670';
    case INDUSTRIAS_PARA_USOS_ESPECIFICOS = '680';
    case CONSTRUCAO = '690';

    // 700 - Artes
    case ARTES = '700';
    case JARDINAGEM_E_PAISAGISMO = '710';
    case ARQUITETURA = '720';
    case ESCULTURA_CERAMICA_E_METALURGIA = '730';
    case DESENHO_E_ARTES_DECORATIVAS = '740';
    case PINTURA = '750';
    case ARTES_GRAFICAS = '760';
    case FOTOGRAFIA = '770';
    case MUSICA = '780';
    case RECREACAO = '790';

    // 800 - Literatura
    case LITERATURA = '800';
    case LITERATURA_AMERICANA_E_CANADENSE_EM_INGLES = '810';
    case LITERATURA_INGLESA = '820';
    case LITERATURA_ALEMA = '830';
    case LITERATURA_FRANCESA_E_CANADENSE_EM_FRANCES = '840';
    case LITERATURA_ITALIANA = '850';
    case LITERATURA_ESPANHOLA_E_HISPANO_AMERICANA = '860';
    case LITERATURA_BRASILEIRA = 'B869';
    case LITERATURA_LATINA = '870';
    case LITERATURA_GREGA = '880';
    case OUTRAS_LITERATURAS = '890';

    // 900 - História
    case HISTORIA = '900';
    case GEOGRAFIA_GERAL_E_VIAGENS = '910';
    case BIOGRAFIA_E_GENEALOGIA = '920';
    case HISTORIA_ANTIGA = '930';
    case HISTORIA_MODERNA_CONTEMPORANEA_E_DA_EUROPA = '940';
    case HISTORIA_DA_ASIA = '950';
    case HISTORIA_DA_AFRICA = '960';
    case HISTORIA_DAS_AMERICAS = '970';
    case HISTORIA_DA_AMERICA_LATINA = '980';
    case HISTORIA_DO_BRASIL = '981';
    case HISTORIA_DE_OUTRAS_AREAS = '990';


    /*
    |--------------------------------------------------------------------------
    | Descrição
    |--------------------------------------------------------------------------
    */

    public function label(): string
    {
        return match ($this) {
            self::GENERALIDADES => 'Generalidades',
            self::CONHECIMENTO_TEORIA => 'Conhecimento / Teoria',
            self::SISTEMAS => 'Sistemas',
            self::CIENCIA_DA_COMPUTACAO => 'Ciência da computação',
            self::PROGRAMACAO_DE_COMPUTADORES => 'Programação de computadores',
            self::BIBLIOGRAFIAS => 'Bibliografias',
            self::CIENCIA_DA_INFORMACAO_E_BIBLIOTECONOMIA => 'Ciência da informação e bibliotecas / Biblioteconomia',
            self::ENCICLOPEDIAS_GERAIS_E_ANUARIO => 'Enciclopédias gerais e anuário',
            self::COLECOES_GERAIS_DE_ENSAIOS => 'Coleções gerais de ensaios',
            self::REVISTAS_JORNAIS_E_SERIES => 'Revistas, jornais e séries',
            self::ASSOCIACOES_ORGANIZACOES_E_MUSEUS => 'Associações, organizações e museus',
            self::MIDIA_INFORMATIVA_JORNALISMO_E_PUBLICACOES => 'Mídia informativa, jornalismo e publicações',
            self::CITACOES => 'Citações',
            self::MANUSCRITOS_E_LIVROS_RAROS => 'Manuscritos e livros raros',

            self::FILOSOFIA => 'Filosofia',
            self::METAFISICA => 'Metafísica',
            self::EPISTEMOLOGIA => 'Epistemologia',
            self::FENOMENOS_PARANORMAIS => 'Fenômenos paranormais',
            self::ESCOLAS_FILOSOFICAS_DE_PENSAMENTO => 'Escolas filosóficas de pensamento',
            self::PSICOLOGIA => 'Psicologia',
            self::LOGICA_DIALETICA => 'Lógica / Dialética',
            self::ETICA_MORAL => 'Ética / Moral',
            self::FILOSOFIA_ANTIGA_MEDIEVAL_E_ORIENTAL => 'Filosofia antiga, medieval e oriental',
            self::FILOSOFIA_OCIDENTAL_MODERNA => 'Filosofia ocidental moderna',

            self::RELIGIAO => 'Religião',
            self::FILOSOFIA_E_TEORIA_DA_RELIGIAO => 'Filosofia e teoria da religião',
            self::BIBLIA => 'Bíblia',
            self::CRISTIANISMO => 'Cristianismo',
            self::PRATICAS_E_HABITOS_CRISTAOS => 'Práticas e hábitos cristãos',
            self::ORDENS_RELIGIOSAS_E_IGREJAS_LOCAIS => 'Ordens religiosas e igrejas locais',
            self::ORGANIZACAO_CRISTA_E_TEOLOGIA_SOCIAL => 'Organização cristã e Teologia Social',
            self::HISTORIA_DO_CRISTIANISMO => 'História do cristianismo',
            self::DENOMINACOES_DE_IGREJAS_CRISTAS => 'Denominações de igrejas cristãs',
            self::OUTRAS_RELIGIOES => 'Outras religiões',

            self::CIENCIAS_SOCIAIS => 'Ciências sociais',
            self::ESTATISTICA => 'Estatística',
            self::CIENCIA_POLITICA => 'Ciência política',
            self::ECONOMIA => 'Economia',
            self::DIREITO_JURISPRUDENCIA_JUSTICA => 'Direito / Jurisprudência / Justiça',
            self::ADMINISTRACAO_PUBLICA_E_CIENCIA_MILITAR => 'Administração Pública e Ciência Militar',
            self::SERVICO_SOCIAL_ASSISTENCIA_SOCIAL => 'Serviço social / Assistência social',
            self::EDUCACAO => 'Educação',
            self::COMERCIO_COMUNICACAO_E_TRANSPORTES => 'Comércio, comunicação e transportes',
            self::COSTUMES_TRAJES_ETIQUETA_FOLCLORE => 'Costumes, trajes, etiqueta, folclore',

            self::LINGUAS => 'Línguas',
            self::LINGUISTICA => 'Linguística',
            self::LINGUA_INGLESA_E_INGLES_ANTIGO => 'Língua inglesa e inglês antigo',
            self::LINGUA_ALEMA_E_AFINS => 'Língua alemã e afins',
            self::LINGUA_FRANCESA_E_AFINS => 'Língua francesa e afins',
            self::LINGUA_ITALIANA_ROMENO_E_AFINS => 'Língua italiana, romeno e afins',
            self::LINGUA_ESPANHOLA => 'Língua espanhola',
            self::LINGUA_PORTUGUESA => 'Língua portuguesa',
            self::LATIM => 'Latim',
            self::GREGO_CLASSICO_E_MODERNO => 'Grego clássico e moderno',
            self::OUTRAS_LINGUAS => 'Outras línguas',

            self::CIENCIAS_NATURAIS => 'Ciências naturais',
            self::MATEMATICA => 'Matemática',
            self::ASTRONOMIA => 'Astronomia',
            self::FISICA => 'Física',
            self::QUIMICA => 'Química',
            self::GEOLOGIA => 'Geologia',
            self::PALEONTOLOGIA => 'Paleontologia',
            self::BIOLOGIA => 'Biologia',
            self::PLANTAS_BOTANICA => 'Plantas (Botânica)',
            self::ANIMAIS_ZOOLOGIA => 'Animais (Zoologia)',

            self::TECNOLOGIA => 'Tecnologia',
            self::MEDICINA => 'Medicina',
            self::ENGENHARIA => 'Engenharia',
            self::AGRICULTURA => 'Agricultura',
            self::MEDICINA_VETERINARIA => 'Medicina veterinária',
            self::ECONOMIA_DOMESTICA_E_VIDA_FAMILIAR => 'Economia doméstica e vida familiar',
            self::ADMINISTRACAO_E_SERVICOS_AUXILIARES => 'Administração e serviços auxiliares',
            self::CONTABILIDADE => 'Contabilidade',
            self::ENGENHARIA_QUIMICA => 'Engenharia química',
            self::INDUSTRIA_MANUFATURADOS_ENGENHARIA_DE_PRODUCAO => 'Indústria / Manufaturados / Engenharia de Produção',
            self::INDUSTRIAS_PARA_USOS_ESPECIFICOS => 'Indústrias para usos específicos',
            self::CONSTRUCAO => 'Construção',

            self::ARTES => 'Artes',
            self::JARDINAGEM_E_PAISAGISMO => 'Jardinagem e paisagismo',
            self::ARQUITETURA => 'Arquitetura',
            self::ESCULTURA_CERAMICA_E_METALURGIA => 'Escultura, cerâmica e metalurgia',
            self::DESENHO_E_ARTES_DECORATIVAS => 'Desenho e artes decorativas',
            self::PINTURA => 'Pintura',
            self::ARTES_GRAFICAS => 'Artes gráficas',
            self::FOTOGRAFIA => 'Fotografia',
            self::MUSICA => 'Música',
            self::RECREACAO => 'Recreação',

            self::LITERATURA => 'Literatura',
            self::LITERATURA_AMERICANA_E_CANADENSE_EM_INGLES => 'Literatura americana e canadense (em inglês)',
            self::LITERATURA_INGLESA => 'Literatura inglesa',
            self::LITERATURA_ALEMA => 'Literatura alemã',
            self::LITERATURA_FRANCESA_E_CANADENSE_EM_FRANCES => 'Literatura francesa e canadense (em francês)',
            self::LITERATURA_ITALIANA => 'Literatura italiana',
            self::LITERATURA_ESPANHOLA_E_HISPANO_AMERICANA => 'Literatura espanhola e Hispano-americana',
            self::LITERATURA_BRASILEIRA => 'Literatura brasileira',
            self::LITERATURA_LATINA => 'Literatura latina',
            self::LITERATURA_GREGA => 'Literatura grega',
            self::OUTRAS_LITERATURAS => 'Outras literaturas',

            self::HISTORIA => 'História',
            self::GEOGRAFIA_GERAL_E_VIAGENS => 'Geografia geral e viagens',
            self::BIOGRAFIA_E_GENEALOGIA => 'Biografia e genealogia',
            self::HISTORIA_ANTIGA => 'História antiga',
            self::HISTORIA_MODERNA_CONTEMPORANEA_E_DA_EUROPA => 'História Moderna e Contemporânea / História da Europa',
            self::HISTORIA_DA_ASIA => 'História da Ásia',
            self::HISTORIA_DA_AFRICA => 'História da África',
            self::HISTORIA_DAS_AMERICAS => 'História das Américas',
            self::HISTORIA_DA_AMERICA_LATINA => 'História da América Latina',
            self::HISTORIA_DO_BRASIL => 'História do Brasil',
            self::HISTORIA_DE_OUTRAS_AREAS => 'História de outras áreas',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Conversão de códigos CDD antigos
    |--------------------------------------------------------------------------
    */

    public static function fromCodigoCDD(?string $codigo): ?self
    {
        if ($codigo === null || trim($codigo) === '') {
            return null;
        }

        $codigo = self::normalizarCodigo($codigo);

        /*
         * Primeiro verificamos se já é exatamente um
         * dos códigos utilizados atualmente.
         */
        if ($area = self::tryFrom($codigo)) {
            return $area;
        }

        /*
         * Casos especiais que possuem códigos mais específicos
         * na nossa lista.
         *
         * Eles precisam ser verificados antes da classificação
         * geral.
         */
        foreach (self::codigosEspecificos() as $area) {
            if (str_starts_with($codigo, $area->value)) {
                return $area;
            }
        }

        /*
         * Literatura brasileira possui a notação B869.
         */
        if (str_starts_with($codigo, 'B869')) {
            return self::LITERATURA_BRASILEIRA;
        }

        /*
         * Neste ponto tratamos os códigos numéricos CDD.
         */
        if (!preg_match('/^\d{1,3}(?:\.\d+)?$/', $codigo)) {
            return null;
        }

        /*
         * Obtém os três primeiros dígitos da classificação.
         *
         * Ex.:
         *
         * 158.1   -> 158
         * 004.678 -> 004
         * 981.06  -> 981
         */
        [$inteiro] = explode('.', $codigo);

        $inteiro = str_pad($inteiro, 3, '0', STR_PAD_LEFT);

        if (strlen($inteiro) !== 3) {
            return null;
        }

        /*
         * Verifica novamente se o código de três dígitos
         * está explicitamente disponível.
         *
         * Ex.:
         * 981.06 -> 981
         * 469.5  -> 469
         * 657.4  -> 657
         */
        if ($area = self::tryFrom($inteiro)) {
            return $area;
        }

        /*
         * A lista fornecida trabalha, em sua maior parte,
         * com divisões por dezenas.
         *
         * Ex.:
         *
         * 158 -> 150
         * 153 -> 150
         * 616 -> 610
         * 658 -> 650
         * 636 -> 630
         */
        $dezena = substr($inteiro, 0, 2) . '0';

        if ($area = self::tryFrom($dezena)) {
            return $area;
        }

        /*
         * Caso não exista a divisão, sobe para a
         * classe principal.
         *
         * Ex.:
         *
         * 106 -> 100
         * 205 -> 200
         * 305 -> 300
         */
        $centena = substr($inteiro, 0, 1) . '00';

        return self::tryFrom($centena);
    }


    /*
    |--------------------------------------------------------------------------
    | Códigos específicos preservados
    |--------------------------------------------------------------------------
    */

    private static function codigosEspecificos(): array
    {
        return [
            self::MEDICINA_VETERINARIA, // 636.089
            self::LITERATURA_BRASILEIRA, // B869
            self::HISTORIA_DO_BRASIL, // 981
            self::LINGUA_PORTUGUESA, // 469
            self::CONTABILIDADE, // 657
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Normalização
    |--------------------------------------------------------------------------
    */

    private static function normalizarCodigo(string $codigo): string
    {
        return strtoupper(
            preg_replace('/\s+/', '', trim($codigo))
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Exibição
    |--------------------------------------------------------------------------
    */

    public function descricaoCompleta(): string
    {
        return "{$this->value} - {$this->label()}";
    }
}

# CONTRIBUTING-pt-BR.md

[Read in English](CONTRIBUTING.md)

# Contribuindo

Obrigado por considerar contribuir com o Advanced Logger! Este documento fornece algumas diretrizes básicas para tornar o processo de contribuição mais fácil e efetivo.

## Configuração do Ambiente de Desenvolvimento

1. Faça um fork do repositório
2. Clone seu fork:

```bash
git clone https://github.com/seu-usuario/advanced-log.git
```

3. Instale as dependências:

```bash
composer install
```

4. Crie uma branch para sua feature ou correção:

```bash
git checkout -b nome-da-feature
```

## Testes

Usamos PHPUnit para testes. Para executar os testes:

```bash
composer test
```

Por favor, certifique-se que:

- Todas as novas funcionalidades têm testes correspondentes
- Todos os testes passam antes de submeter um Pull Request
- A cobertura de testes permanece alta

## Padrões de Código

Este projeto segue os padrões de código PSR-12. Para verificar seu código:

```bash
composer check-style
```

Para corrigir problemas de estilo automaticamente:

```bash
composer fix-style
```

## Processo de Pull Request

1. Atualize o README.md com detalhes das mudanças, se necessário
2. Atualize o CHANGELOG.md seguindo o formato Keep a Changelog
3. Atualize qualquer documentação que possa ser afetada por suas mudanças
4. Certifique-se que todos os testes passam
5. Crie seu Pull Request com um título e descrição claros

### Diretrizes para Pull Request

- Use um título claro e descritivo
- Inclua números de issues relevantes na descrição
- Inclua screenshots ou saída do console se relevante
- Documente novo código baseado nos padrões PSR
- Atualize a documentação se necessário

## Criando Issues

Ao criar issues, por favor:

- Use um título claro e descritivo
- Forneça passos detalhados para reprodução
- Inclua informações do sistema se relevante
- Anexe arquivos de log ou screenshots se aplicável

## Código de Conduta

### Nosso Compromisso

Estamos comprometidos em fornecer um ambiente amigável, seguro e acolhedor para todos os contribuidores.

### Comportamento Esperado

- Seja respeitoso e inclusivo
- Seja colaborativo
- Aceite críticas construtivas graciosamente
- Foque no que é melhor para a comunidade

### Comportamento Inaceitável

- Assédio de qualquer tipo
- Piadas e linguagem discriminatórias
- Linguagem violenta ou ameaçadora
- Qualquer outra conduta que possa ser razoavelmente considerada inapropriada

## Obtendo Ajuda

Se você precisar de ajuda, você pode:

- Criar uma issue
- Enviar email para os mantenedores em seu@email.com
- Entrar em nossa comunidade Discord (se disponível)

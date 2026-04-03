# Fluxo de Submissoes

Diagramas de referencia para entender a navegacao, a organizacao do conteudo e o fluxo de submissao no tema.

## 1. Navegacao E Organizacao Do Conteudo

```mermaid
flowchart TD
    A["Usuario acessa /submeter"] --> B["Hub de submissao"]
    B --> C["Escolhe um caminho"]

    C --> D1["Conteudo"]
    C --> D2["Evento"]
    C --> D3["Perfil"]

    D1 --> E1{"Esta logado?"}
    D2 --> E2{"Esta logado?"}
    D3 --> E3{"Esta logado?"}

    E1 -->|"Nao"| F1["Tela com Entrar / Criar conta"]
    E2 -->|"Nao"| F2["Tela com Entrar / Criar conta"]
    E3 -->|"Nao"| F3["Tela com Entrar / Criar conta"]

    E1 -->|"Sim"| G1["Formulario de conteudo"]
    E2 -->|"Sim"| G2["Formulario de evento"]
    E3 -->|"Sim"| G3["Formulario de perfil"]

    G1 --> H1["JS mostra campos contextuais por tipo"]
    H1 --> H1A["Artigos"]
    H1 --> H1B["Livros e materiais"]
    H1 --> H1C["Ferramentas"]
    H1 --> H1D["Multimidia"]
    H1 --> H1E["Sites e sistemas"]

    G2 --> H2["JS mostra campos por modalidade"]
    H2 --> H2A["Presencial ou hibrido: CEP"]
    H2 --> H2B["Online ou hibrido: plataforma"]
    H2 --> H2C["Adicionar varias datas e horarios"]

    G3 --> H3["JS permite multiplos links sociais"]
    H3 --> H3A["Adicionar/remover itens"]

    H1A --> I["Secoes recolhiveis + navegacao lateral"]
    H1B --> I
    H1C --> I
    H1D --> I
    H1E --> I
    H2A --> I
    H2B --> I
    H2C --> I
    H3A --> I
```

## 2. Fluxo De Submissao

```mermaid
flowchart TD
    A["Usuario clica em Enviar para revisao"] --> B["POST para a propria pagina"]
    B --> C["Backend detecta o tipo pelo botao submit"]

    C --> D{"Usuario esta logado?"}
    D -->|"Nao"| E["Redireciona para login"]
    D -->|"Sim"| F["Valida nonce"]

    F --> G{"Nonce valido?"}
    G -->|"Nao"| H["Redireciona com status=error"]
    G -->|"Sim"| I["Executa antispam"]

    I --> I1["Honeypot"]
    I --> I2["Timestamp minimo"]
    I --> I3["Turnstile"]
    I --> I4["Rate limit"]

    I1 --> J["Sanitiza dados"]
    I2 --> J
    I3 --> J
    I4 --> J

    J --> K{"Tipo da submissao"}

    K -->|"Conteudo"| L1["Sanitiza campos gerais e contextuais"]
    K -->|"Evento"| L2["Sanitiza modalidade, CEP, plataforma e slots"]
    K -->|"Perfil"| L3["Sanitiza perfil, redes e imagem"]

    L1 --> M1["Valida obrigatorios e regras por tipo"]
    L2 --> M2["Valida obrigatorios e regras por modalidade"]
    L3 --> M3["Valida obrigatorios, email e website"]

    M1 --> N{"Dados validos?"}
    M2 --> N
    M3 --> N

    N -->|"Nao"| O["Redireciona com status=error"]
    N -->|"Sim"| P["Cria submissao pendente"]
```

## 3. Persistencia, Revisao E Saida

```mermaid
flowchart TD
    A["Cria submissao pendente"] --> B{"Tipo"}

    B -->|"Conteudo"| C1["Cria post type a11y_conteudo"]
    B -->|"Evento"| C2["Cria post type a11y_evento"]
    B -->|"Perfil"| C3["Cria post type a11y_perfil"]

    C1 --> D1["Status pending"]
    C2 --> D2["Status pending"]
    C3 --> D3["Status pending"]

    D1 --> E1["Grava taxonomias e metadados"]
    D2 --> E2["Grava metadados e slots em JSON"]
    D3 --> E3["Grava metadados, redes em JSON e upload de imagem"]

    E1 --> F["Envia email para admin"]
    E2 --> F
    E3 --> F

    F --> G["Redireciona de volta com status=success"]
    G --> H["Pagina mostra toast de sucesso"]

    H --> I["Equipe faz revisao editorial"]
    I --> J["Pode entrar em contato com o autor"]
    J --> K["Publicacao apos aprovacao"]
```

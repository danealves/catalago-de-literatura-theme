# Catálogo de Literatura

Tema filho do **Twenty Twenty-Five** desenvolvido para o portal de Literatura da FTD Educação. Gerencia o catálogo de obras infantis e juvenis com blocos Gutenberg customizados, shortcodes e templates dedicados.

## Requisitos

- WordPress 6.4+
- PHP 8.0+
- Tema pai: **Twenty Twenty-Five**
- Node.js 18+ (desenvolvimento)

## Instalação

1. Certifique-se que o tema **Twenty Twenty-Five** está instalado
2. Faça o upload da pasta `catalago-de-literatura` para `/wp-content/themes/`
3. Ative em **Aparência → Temas**
4. Para compilar os assets: `npm install && npm run build`

## Desenvolvimento

```bash
npm install        # instala dependências
npm run build      # compila SCSS e blocos
npm run watch      # watch SCSS + blocos em paralelo
npm run package    # gera o .zip para distribuição
```

## Estrutura

```
catalago-de-literatura/
├── assets/
│   ├── css/           # CSS compilado
│   ├── js/            # Scripts
│   └── scss/          # Fonte SCSS por componente
├── blocks/            # Código-fonte dos blocos Gutenberg
├── build/             # Blocos compilados (gerado via npm)
├── inc/
│   ├── blocks/        # Registro PHP dos blocos customizados
│   ├── filters/       # Filtros WordPress
│   └── shortcodes/    # Shortcodes customizados
├── parts/             # Template parts (header, footer, sidebar)
├── templates/         # Templates de página
├── functions.php
├── theme.json
└── style.css
```

## Blocos Gutenberg

| Bloco | Descrição |
|---|---|
| `cl/icon` | Icon Picker com Dashicons, Media Upload e colorização SVG via `mask-image` |

## Shortcodes

| Shortcode | Descrição |
|---|---|
| `[awards]` | Exibe premiações da obra |
| `[banner]` | Banner customizado |
| `[issuu]` | Embed do Issuu com estilo próprio |
| `[materials]` | Materiais de apoio |
| `[related-items]` | Itens relacionados |

## Filtros

| Arquivo | Descrição |
|---|---|
| `block-visibility.php` | Controla visibilidade de blocos por contexto |
| `obra-ordering.php` | Ordenação customizada do CPT Obras |
| `term-links.php` | Links de taxonomia customizados |
| `thumbnails.php` | Tamanhos de thumbnail registrados |

## Changelog

Veja [CHANGELOG.md](CHANGELOG.md).

## Licença

GPL2+

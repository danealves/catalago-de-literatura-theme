# Changelog

Todas as alterações relevantes do tema **Catálogo de Literatura** serão documentadas neste arquivo.

Formato baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.1.0/).

---

## [1.0.6] — 2026-05-19

### Corrigido
- Cor vermelha de background vazando no border-radius da seção do formulário RD Station — adicionado `border-radius: 20px` em `#rd-section-lzbc8tcl`
- Seletor `> div` ajustado para `>div` (sem espaço) em `#rd-column-mmyym26l` (desktop e mobile)
- Seletor `> div` ajustado para `>div` em `.form-contato .rd-column`
- Seletor `:where(.wp-site-blocks) > *` ajustado para `:where(.wp-site-blocks)>*`

### Adicionado
- Posicionamento do ícone de estrelas do formulário (`.formulario-icone-estrelas`) — `top: -63px`, `z-index: 99`, `right: 60px`
- Reset de espaçamento na topbar navigation (`.topbar-nav ul` — `padding-block: 0`, `margin-block: 0`)

---

## [1.0.5] — 2026-05-15

### Adicionado
- Bloco Gutenberg `cl/icon` — Icon Picker com Dashicons, Media Upload e colorização SVG via `mask-image`

---

## [1.0.4] — 2026-05-15

### Corrigido
- Ajustes nos posts em destaque e formulário de contato

---

## [1.0.3] — 2026-05-11

### Adicionado
- Query de relação (relation query) no template single de Obras

---

## [1.0.2] — 2026-05-07

### Alterado
- Redesign dos componentes Booktrailer e Issuu

---

## [1.0.1] — 2026-05-06

### Estilo
- Borda fina teal com sombra no embed do Issuu

---

## [1.0.0] — 2026-03-11

### Adicionado
- Commit inicial — tema filho do Twenty Twenty-Five
- Estrutura base com `inc/`, SCSS por componente, block-templates e blocks
- Configuração do `theme.json` e `functions.php`

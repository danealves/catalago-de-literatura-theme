---
name: Theme Structure
description: Estrutura do tema WordPress Catalago de Literatura após reorganização completa
type: project
---

Tema filho do Twenty Twenty-Five (block theme / FSE). Reorganizado para separação de responsabilidades.

**PHP — inc/ por tipo de responsabilidade:**
- `inc/enqueue.php` — scripts e estilos
- `inc/filters/term-links.php` — converte links de taxonomia em query params
- `inc/filters/thumbnails.php` — imagem fallback quando sem thumbnail
- `inc/filters/block-visibility.php` — oculta seções via ACF + tradução do filter-everything
- `inc/shortcodes/banner.php` — classe AutoBannerShortcode ([banner_obra_automatico])
- `inc/shortcodes/awards.php` — [ftd_premios]
- `inc/shortcodes/materials.php` — [materiais_complementares]
- `inc/shortcodes/related-items.php` — [itens_relacionados]
- `inc/shortcodes/issuu.php` — [degustacao_issuu]
- `inc/blocks/related-obras.php` — bloco Gutenberg meu-plugin/related-obras

**functions.php:** loader de ~30 linhas que faz require_once de todos os módulos.

**SCSS — assets/scss/components/ por componente:**
- `_banner.scss`, `_awards.scss`, `_materials.scss`, `_utilities.scss`, `_form-contact.scss`
- `pages/_home.scss`, `pages/_home-slider.scss`
- `style.css` raiz: apenas o header obrigatório do WordPress

**Build:** `npm run build:scss` compila para `assets/css/style.css`

**ACF fields usados:** segmento_escolar, cor_da_categoria, imagem_de_fundo, banner_hero_image, booktrailer, degustacao, projeto_de_leitura, suplemento_de_leitura_mestre, suplemento_de_leitura_aluno, item_relacionado

**Why:** Reorganizado para auditoria por engenheiro Google — separação de responsabilidades, sem arquivos monolíticos.

**How to apply:** Sempre adicionar novos shortcodes em inc/shortcodes/, filtros em inc/filters/, estilos em assets/scss/components/. Nunca colocar lógica diretamente no functions.php.

# UX Portfolio Bundle

A WordPress plugin of **51 Elementor widgets** for presenting UX and design case studies: personas, journey maps, affinity maps, user flows, storyboards, wireframes, brand systems, charts and the case study page template itself.

I built it for my own portfolio, [Made by Mystie](https://www.mystiechamberlin.com/design/work/), where it powers 15 published case studies.

## Why it exists

UX designers show their thinking through research artifacts, but Elementor has no native widgets for them, so most portfolios fall back on flat screenshots. I started by building one plugin per artifact. At around 25 plugins, together they hit my host's 256MB PHP memory limit.

So I rebuilt them as a single plugin:

- **One plugin, one stylesheet, one script.** Every widget shares `assets/css/ux-bundle.css` and `assets/js/ux-bundle.js`, loaded once, instead of carrying inline styles and scripts per widget.
- **Native Elementor controls.** Every artifact is edited in the Elementor panel, with repeaters for items and responsive style controls, so content stays editable instead of baked into images.
- **Global colors.** Widgets use Elementor's global color system, so a case study can follow its project's palette.

## Widgets

**Research** (9): User Persona (`ux-user-persona`) · Empathy Map (`ux-empathy-map`) · Affinity Map (`ux-affinity-map`) · User Statements (`ux-user-statements`) · Competitive Analysis (`ux-competitive-analysis`) · Visual Competitive Analysis (`ux-visual-competitive-analysis`) · Competitive Suite (`ux-competitive-suite`) · Competitive Differentiation (`ux-competitive-differentiation`) · SWOT Analysis (`ux-swot-analysis`)

**Strategy** (4): Value Proposition Canvas (`ux-uvp-canvas`) · Features & Benefits Matrix (`ux-features-benefits`) · Benefits Ladder (`ux-benefits-ladder`) · Value → Principles (`ux-value-principles`)

**Mapping & flow** (5): User Journey Map (`ux-journey-map`) · User Flow Diagram (`ux-user-flow-diagram`) · Sitemap (`ux-sitemap`) · Storyboard (`ux-storyboard`) · Mockup Flow (`ux-mockup-flow`)

**Ideation & visual design** (12): Sketch Ideation (`ux-sketch-ideation`) · Wireframe Showcase (`ux-wireframe-showcase`) · Moodboard (`ux-moodboard`) · Style Tile (`ux-style-tile`) · Logo Concepts (`ux-logo-concepts`) · Brand Assets (`ux-brand-assets`) · Brand Development Process (`ux-brand-development-process`) · UI Kit (`ux-ui-kit`) · Annotated Design (`ux-annotated-design`) · Visual Design Gallery (`ux-visual-design-gallery`) · Prototype Display (`ux-prototype-display`) · Logo Grid (`ux-logo-grid`)

**Data** (3): Bar Chart (`ux-bar-chart`) · Word Cloud (`ux-word-cloud`) · Number Box (`ux-number-box`)

**Content** (7): Header & Description (`ux-description`) · TL;DR Card (`ux-tldr`) · Icon Box (`ux-project-introduction`) · Icon List (`ux-icon-list`) · PDF Viewer (`ux-pdf-viewer`) · UX Case Study (`ux-case-study-overview`) · Accordion Opener Link (`ux-accordion-link`)

**Navigation** (4): Anchor Links (`ux-section-divider`) · Remote Navigation (`ux-remote-navigation`) · Table of Contents (`ux-table-of-contents`) · Lens Switch (`ux-lens-switch`)

**Case study template** (7): Case Study Hero (`ux-cs-hero`) · Case Study Rail (`ux-cs-rail`) · Case Study Panel (`ux-cs-panel`) · Case Study Section (`ux-cs-section`) · Case Study Section Header (`ux-cs-sechead`) · Case Study Keep Reading (`ux-cs-more`) · Case Study Footer (`ux-cs-footer`)

## Requirements

- WordPress 6.x
- Elementor 3.x (Elementor Pro is not required)
- PHP 7.4 or newer

## Installation

1. Download this repository as a ZIP (**Code → Download ZIP**).
2. In WordPress, go to **Plugins → Add New → Upload Plugin** and upload the ZIP.
3. Activate **UX Portfolio Bundle**. The widgets appear in the Elementor panel under their own category.

## Structure

```
ux-portfolio-bundle-optimized.php   Main plugin file: registers widgets and assets
includes/                           Shared base widget class and helpers
widgets/                            One file per widget (51)
assets/css/ux-bundle.css            Shared widget styles
assets/css/cs-layout.css            Case study template layout
assets/js/ux-bundle.js              Shared widget behavior (tabs, carousels, load more, flows)
assets/js/cs-layout.js              Case study template behavior (rail, views, filters)
assets/fonts/                       Bundled open-license web fonts
```

## Fonts

The case study template references two display fonts that are not included here for licensing reasons: **Bulletin Typewriter** and **Beautiful Bloom**. Without them the layout falls back to Courier New and a cursive system font. To use your own, add the `.woff2` files to `assets/fonts/` with those file names, or change the `@font-face` rules in `assets/css/cs-layout.css`.

The bundled fonts (Architects Daughter, Dosis, Economica and Londrina Solid) are from Google Fonts under the SIL Open Font License.

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).

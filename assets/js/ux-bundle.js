/**
 * UX Portfolio Bundle - Shared JavaScript
 * Interactive functionality for all widgets
 * Version: 2.15.72
 */

/**
 * Run cb once Elementor's frontend hooks exist. In the editor preview,
 * elementorFrontend is defined before .hooks is, so checking only
 * window.elementorFrontend on DOM ready throws and hangs the editor.
 */
window.uxWhenElementorReady = window.uxWhenElementorReady || function(cb) {
    if (window.elementorFrontend && window.elementorFrontend.hooks) {
        cb();
    } else {
        jQuery(window).one('elementor/frontend/init', cb);
    }
};

(function($) {
    'use strict';
    
    // ========================================
    // WIREFRAME CAROUSEL
    // ========================================
    
    function initWireframeCarousel() {
        $('.ux-wireframe-carousel').each(function() {
            const $carousel = $(this);
            const $slides = $carousel.find('.ux-wireframe-slides');
            const $dots = $carousel.find('.ux-wireframe-dot');
            let currentSlide = 0;
            const totalSlides = $carousel.find('.ux-wireframe-slide').length;
            
            function goToSlide(index) {
                currentSlide = index;
                $slides.css('transform', `translateX(-${currentSlide * 100}%)`);
                $dots.removeClass('active');
                $dots.eq(currentSlide).addClass('active');
            }
            
            $carousel.find('.ux-wireframe-prev').on('click', function() {
                const newIndex = (currentSlide - 1 + totalSlides) % totalSlides;
                goToSlide(newIndex);
            });
            
            $carousel.find('.ux-wireframe-next').on('click', function() {
                const newIndex = (currentSlide + 1) % totalSlides;
                goToSlide(newIndex);
            });
            
            $dots.on('click', function() {
                const index = $(this).index();
                goToSlide(index);
            });
        });
    }
    
    // ========================================
    // SMOOTH SCROLL FOR TOC LINKS
    // ========================================
    
    function initSmoothScroll() {
        $('.ux-divider-toc-link, .ux-accordion-link').on('click', function(e) {
            const href = $(this).attr('href');
            if (href && href.startsWith('#')) {
                e.preventDefault();
                const target = $(href);
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 600);
                }
            }
        });
    }
    
    // ========================================
    // LIGHTBOX FOR GALLERIES
    // ========================================
    
    function initLightbox() {
        $('.ux-gallery-item, .ux-media-item').on('click', function(e) {
            e.preventDefault();
            const $img = $(this).find('img');
            if ($img.length) {
                // Simple lightbox - can be enhanced with a library
                const src = $img.attr('src');
                const $lightbox = $('<div class="ux-lightbox"></div>');
                $lightbox.html(`
                    <div class="ux-lightbox-overlay"></div>
                    <div class="ux-lightbox-content">
                        <img src="${src}" alt="">
                        <button class="ux-lightbox-close">&times;</button>
                    </div>
                `);
                $('body').append($lightbox);
                
                $('.ux-lightbox-close, .ux-lightbox-overlay').on('click', function() {
                    $lightbox.fadeOut(300, function() {
                        $(this).remove();
                    });
                });
            }
        });
    }
    
    // ========================================
    // ACCORDION NAVIGATION
    // ========================================
    
    function initAccordionNav() {
        $('.ux-accordion-link').on('click', function(e) {
            const targetId = $(this).data('target');
            if (targetId) {
                e.preventDefault();
                const $target = $('#' + targetId);
                if ($target.length) {
                    // Toggle accordion
                    $target.slideToggle(300);
                    // Scroll to it
                    $('html, body').animate({
                        scrollTop: $target.offset().top - 100
                    }, 600);
                }
            }
        });
    }
    
    // ========================================
    // INITIALIZE ON READY & ELEMENTOR PREVIEW
    // ========================================
    
    function init() {
        initWireframeCarousel();
        initSmoothScroll();
        initLightbox();
        initAccordionNav();
    }
    
    // Standard initialization
    $(document).ready(init);
    
    // Elementor preview initialization
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/widget', function($scope) {
            init();
        });
    });
    
})(jQuery);

/* ========================================
   LIGHTBOX STYLES (Inline for convenience)
   ======================================== */

const lightboxStyles = `
<style>
.ux-lightbox {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ux-lightbox-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.9);
}
.ux-lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
    z-index: 1;
}
.ux-lightbox-content img {
    max-width: 100%;
    max-height: 90vh;
    display: block;
}
.ux-lightbox-close {
    position: absolute;
    top: -40px;
    right: 0;
    background: none;
    border: none;
    color: #fff;
    font-size: 40px;
    cursor: pointer;
    width: 40px;
    height: 40px;
    line-height: 1;
}
</style>
`;

// Inject lightbox styles
if (!document.getElementById('ux-lightbox-styles')) {
    const styleEl = document.createElement('div');
    styleEl.id = 'ux-lightbox-styles';
    styleEl.innerHTML = lightboxStyles;
    document.head.appendChild(styleEl);
}

/* ============================================
   UX REMOTE NAVIGATION
   Self-contained: handles accordion, tabs, toggle, custom selectors.
   No dependency on other widgets.
   ============================================ */
(function($) {
    'use strict';

    // Selector strategies for each target type, ordered by preference.
    // Each set covers Atomic V4, Nested V3.x, and Legacy markup.
    var SELECTORS = {
        accordion: [
            '.e-n-accordion-item-title',                    // nested accordion V3.x (on <summary>)
            '.elementor-accordion .elementor-tab-title',    // legacy accordion
            'details > summary',                            // generic <details>/<summary>
            '[role="button"][aria-expanded]'                // ARIA fallback
        ],
        tabs: [
            '.e-tabs-menu-base [role="tab"]',               // Atomic V4 tabs (.e-tab-base buttons)
            '.e-n-tabs-heading .e-n-tab-title',             // nested tabs V3.x
            '.elementor-tabs .elementor-tab-title',         // legacy tabs
            '[role="tab"]'                                  // ARIA fallback
        ],
        toggle: [
            '.elementor-toggle .elementor-tab-title',       // legacy toggle
            '.elementor-toggle-title'
        ]
    };

    // Selectors that identify the "container" of a target widget by type.
    // Used for fallback search when no ID match is found in scope.
    var TARGET_CONTAINERS = {
        accordion: [
            '.elementor-widget-n-accordion',     // nested accordion widget
            '.elementor-widget-accordion',        // legacy accordion widget
            '.e-n-accordion',                     // nested accordion inner
            '.elementor-accordion'                // legacy accordion inner
        ],
        tabs: [
            '.e-tabs-base',                       // Atomic V4 tabs
            '.elementor-widget-n-tabs',           // nested tabs widget
            '.elementor-widget-tabs',             // legacy tabs widget
            '.elementor-tabs'                     // legacy tabs inner
        ],
        toggle: [
            '.elementor-widget-toggle',
            '.elementor-toggle'
        ]
    };

    // Walk up from $nav to find the smallest reasonable shared scope.
    // Stops at the first .e-con, .elementor-section, .elementor-widget-wrap,
    // or single-post content wrapper.
    function findScope($nav) {
        var $scope = $nav.closest(
            '.elementor-element-populated, ' +    // legacy column wrap
            '.e-con-inner, ' +                    // V3 container inner
            '.e-con, ' +                          // V3 container
            '.elementor-section, ' +              // legacy section
            '.elementor-top-section, ' +
            'article, ' +                         // post article
            '.elementor'                          // outermost Elementor wrapper
        );
        return $scope.length ? $scope : $(document);
    }

    // Find the target widget container — first by ID in scope, then by ID globally,
    // then by widget-type heuristic in scope.
    function findTarget($nav, targetID, widgetType) {
        var $scope = findScope($nav);

        // 1. ID match within scope (preferred — handles repeated templates)
        if (targetID) {
            var $scoped = $scope.find('#' + cssEscape(targetID));
            if ($scoped.length) return $scoped.first();

            // 2. ID match anywhere on page
            var $global = $('#' + cssEscape(targetID));
            if ($global.length) return $global.first();
        }

        // 3. Heuristic fallback — find a widget of the right type within scope.
        // Walk outward from the nav until we find one.
        var containers = TARGET_CONTAINERS[widgetType] || [];
        var $current = $nav.parent();
        while ($current.length && !$current.is('body')) {
            for (var i = 0; i < containers.length; i++) {
                var $found = $current.find(containers[i]).not($nav.find(containers[i]));
                if ($found.length) return $found.first();
            }
            $current = $current.parent();
        }
        return $();
    }

    // Minimal CSS.escape polyfill for safety on older browsers
    function cssEscape(s) {
        if (window.CSS && CSS.escape) return CSS.escape(s);
        return String(s).replace(/[^a-zA-Z0-9_-]/g, '\\$&');
    }

    function findClickable($target, widgetType, customSelector, zeroBasedIndex) {
        if (widgetType === 'custom' && customSelector) {
            return $target.find(customSelector).eq(zeroBasedIndex);
        }
        var strategies = SELECTORS[widgetType];
        if (!strategies) return $();

        for (var i = 0; i < strategies.length; i++) {
            var $items = $target.find(strategies[i]);
            if ($items.length > zeroBasedIndex) {
                return $items.eq(zeroBasedIndex);
            }
        }
        return $();
    }

    function openItem($clickable) {
        if (!$clickable.length) return false;

        var el = $clickable[0];

        // Handle <summary> inside <details> (HTML-native accordion)
        if (el.tagName === 'SUMMARY') {
            var $details = $clickable.closest('details');
            if ($details.length) {
                $details[0].open = true;
                return true;
            }
        }

        // Standard click — works for Elementor accordions, tabs, toggles
        el.click();
        return true;
    }

    function initRemoteNav($nav) {
        if ($nav.data('ux-remote-nav-init')) return;
        $nav.data('ux-remote-nav-init', true);

        var targetID = $nav.data('target-id');
        var widgetType = $nav.data('widget-type');
        var customSelector = $nav.data('custom-selector');
        var globalScroll = String($nav.data('scroll-to-target')) === '1';
        var scrollOffset = parseInt($nav.data('scroll-offset'), 10) || 0;

        function scrollToEl(el) {
            if (!el) return;
            var rect = el.getBoundingClientRect();
            var top = rect.top + window.pageYOffset - scrollOffset;
            window.scrollTo({ top: top, behavior: 'smooth' });
        }

        $nav.on('click', '.ux-remote-nav-item', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var index = parseInt($btn.data('target-index'), 10) || 1;
            var bulletScroll = $btn.data('scroll') || 'inherit';

            $nav.find('.ux-remote-nav-item').removeClass('is-active');
            $btn.addClass('is-active');

            // Look up target fresh each click (scoped to nav's container first)
            var $target = findTarget($nav, targetID, widgetType);
            if (!$target.length) {
                if (window.console) {
                    console.warn(
                        'UX Remote Nav: no target found' +
                        (targetID ? ' (#' + targetID + ')' : '') +
                        ' — checked scope and page'
                    );
                }
                return;
            }

            var $clickable = findClickable($target, widgetType, customSelector, index - 1);
            if (!openItem($clickable)) {
                if (window.console) {
                    console.warn(
                        'UX Remote Nav: could not find item ' + index +
                        ' inside target #' + targetID + ' (type: ' + widgetType + ')'
                    );
                }
            }

            // Resolve scroll behavior: per-bullet override beats global
            var shouldScroll = bulletScroll === 'always' ||
                              (bulletScroll === 'inherit' && globalScroll);

            if (shouldScroll && $target.length) {
                // Slight delay so the click action (open accordion item etc.)
                // finishes before the scroll animation begins.
                setTimeout(function() {
                    scrollToEl($target[0]);
                }, 50);
            }
        });
    }

    $(function() {
        $('.ux-remote-nav').each(function() {
            initRemoteNav($(this));
        });

        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-remote-navigation.default',
                function($scope) {
                    initRemoteNav($scope.find('.ux-remote-nav'));
                }
            );
        });
    });

})(jQuery);

/* ============================================
   UX TABLE OF CONTENTS
   Auto-detect or manual mode, collapsible, sticky, smooth-scroll, active-section highlight.
   ============================================ */
(function($) {
    'use strict';

    // Slugify text into a valid HTML id (matching WordPress sanitize_title roughly)
    function slugify(text) {
        return String(text)
            .toLowerCase()
            .trim()
            .replace(/[\s\W-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    // Ensure unique ID — if a heading text already exists, append a counter
    function ensureUniqueId(slug, used) {
        var candidate = slug || 'section';
        var i = 1;
        while (used.has(candidate) || document.getElementById(candidate)) {
            i++;
            candidate = slug + '-' + i;
        }
        used.add(candidate);
        return candidate;
    }

    // Returns ALL elements matching any selector in the comma-separated list,
    // de-duplicated. This handles posts where content is split across multiple
    // wrappers (e.g. an Atomic container + a Theme Post Content widget).
    function findContentRoots($toc) {
        var scope = String($toc.data('scope') || 'selector');
        if (scope === 'tabpanel') {
            var $panel = $toc.closest('[role="tabpanel"]');
            if ($panel.length) return $panel;
        } else if (scope === 'page') {
            return $('body');
        }
        var selectorList = String($toc.data('content-selector') || '').split(',');
        var $roots = $();
        for (var i = 0; i < selectorList.length; i++) {
            var sel = selectorList[i].trim();
            // Never let a selector be parsed as HTML.
            if (!sel || sel.charAt(0) === '<') continue;
            var $matches;
            try { $matches = $(sel); } catch (err) { continue; }
            if ($matches.length) {
                $roots = $roots.add($matches);
            }
        }
        if ($roots.length) return $roots;

        // Fallbacks if nothing in the configured list matched
        var fallbacks = [
            'article',
            'main',
            '.elementor-section-wrap',
            '.elementor',
            'body'
        ];
        for (var j = 0; j < fallbacks.length; j++) {
            var $fb = $(fallbacks[j]).first();
            if ($fb.length) return $fb;
        }
        return $(document);
    }

    function buildAutoList($toc) {
        var $contentRoots = findContentRoots($toc);
        if (!$contentRoots.length) return [];

        var levels = String($toc.data('heading-levels') || 'h2,h3').split(',').map(function(s) { return s.trim(); });
        var selector = levels.join(',');

        // Collect headings from all matching roots, in document order
        var allHeadings = [];
        $contentRoots.each(function() {
            $(this).find(selector).each(function() {
                allHeadings.push(this);
            });
        });

        // De-duplicate (a heading might appear if root selectors are nested)
        // and sort by document order
        allHeadings = $.unique(allHeadings).sort(function(a, b) {
            if (a === b) return 0;
            // compareDocumentPosition returns 4 if b follows a, 2 if a follows b
            return a.compareDocumentPosition(b) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1;
        });

        if (!allHeadings.length) return [];

        var used = new Set();
        var items = [];
        var uxMode = String($toc.data('ux-headings') || 'all');
        var exclude = String($toc.data('exclude') || '').trim();
        var labelMap = normalizeLabelMap($toc.data('label-map'));

        $.each(allHeadings, function(_, headingEl) {
            var $h = $(headingEl);
            var tag = headingEl.tagName.toLowerCase();

            // Skip headings inside the TOC widget itself
            if ($h.closest('.ux-toc').length) return;

            // Skip headings hidden by the case study Lens Switch
            if ($h.closest('.ux-lens-off').length) return;

            // Skip excluded headings (selector match, or a data-toc-skip attribute)
            if ($h.is('[data-toc-skip]')) return;
            if (exclude) {
                try {
                    if ($h.is(exclude) || $h.closest(exclude).length) return;
                } catch (err) { /* invalid selector: ignore it */ }
            }

            // Headings that belong to other UX widgets
            var isUxTitle = false;
            if ($h.closest('[class*="elementor-widget-ux-"]').length) {
                if (uxMode === 'skip') return;
                if (uxMode === 'titles') {
                    if (!$h.hasClass('widget-title')) return;
                    isUxTitle = true;
                }
            }

            var level = levels.indexOf(tag) + 1; // 1-based level for indent
            if (isUxTitle) level = Math.min(Math.max(level, 1) + 1, 5);
            var text = headingText(headingEl);
            if (!text) return;
            var label = $h.attr('data-toc-label') || labelMap[normalizeKey(text)] || text;

            var id = $h.attr('id');
            if (!id) {
                id = ensureUniqueId(slugify(text), used);
                $h.attr('id', id);
            } else {
                used.add(id);
            }

            items.push({
                label: label,
                anchor: id,
                level: level,
                el: headingEl
            });
        });

        return items;
    }

    // Collapse whitespace (including <br> line breaks) into single spaces.
    function headingText(el) {
        var $c = $(el).clone();
        $c.find('br').replaceWith(' ');
        return $c.text().replace(/\s+/g, ' ').trim();
    }

    function normalizeKey(str) {
        return String(str || '').replace(/\s+/g, ' ').trim().toLowerCase();
    }

    function normalizeLabelMap(raw) {
        var map = {};
        if (typeof raw === 'string') {
            try { raw = JSON.parse(raw); } catch (err) { raw = null; }
        }
        if (raw && typeof raw === 'object') {
            Object.keys(raw).forEach(function(k) {
                if (typeof raw[k] === 'string' && raw[k] !== '') map[normalizeKey(k)] = raw[k];
            });
        }
        return map;
    }

    // Builds the list with DOM nodes + textContent, so heading text can
    // never be interpreted as HTML.
    function renderItems($toc, items) {
        var list = $toc.find('.ux-toc__list')[0];
        if (!list) return;
        list.textContent = '';

        if (!items.length) {
            var empty = document.createElement('li');
            empty.className = 'ux-toc__item ux-toc__item--empty';
            var em = document.createElement('em');
            em.textContent = 'No headings found.';
            empty.appendChild(em);
            list.appendChild(empty);
            return;
        }

        items.forEach(function(item, i) {
            var anchor = String(item.anchor);
            var level = Math.min(Math.max(parseInt(item.level, 10) || 1, 1), 5);
            var li = document.createElement('li');
            li.className = 'ux-toc__item ux-toc__item--level-' + level;
            li.setAttribute('data-index', String(i));
            var a = document.createElement('a');
            a.className = 'ux-toc__link';
            a.setAttribute('href', '#' + anchor);
            a.setAttribute('data-anchor', anchor);
            a.textContent = item.label;
            li.appendChild(a);
            list.appendChild(li);
        });
    }

    // Collapsible dropdown (only visible below the chosen breakpoint via CSS).
    function setupToggle($toc) {
        var $btn = $toc.find('.ux-toc__toggle');
        if (!$btn.length) return;
        function setOpen(open) {
            $toc.toggleClass('is-open', open);
            $btn.attr('aria-expanded', open ? 'true' : 'false');
        }
        $btn.on('click', function() {
            setOpen(!$toc.hasClass('is-open'));
        });
        $toc.on('click', '.ux-toc__link', function() {
            if ($btn.is(':visible')) setOpen(false);
        });
    }

    // Highlights the section currently in view.
    function setupScrollSpy($toc, offset) {
        if (String($toc.data('highlight')) !== 'yes') return;
        var ticking = false;
        var line = (offset || 0) + 24;

        function update() {
            ticking = false;
            var current = null;
            $toc.find('.ux-toc__link').each(function() {
                var el = document.getElementById(String($(this).attr('data-anchor') || ''));
                if (!el || !el.offsetParent) return; // missing, or in a hidden tab
                if (el.getBoundingClientRect().top - line <= 0) current = this;
            });
            $toc.find('.ux-toc__item.is-active').removeClass('is-active')
                .children('.ux-toc__link').removeAttr('aria-current');
            if (current) {
                $(current).attr('aria-current', 'location')
                    .closest('.ux-toc__item').addClass('is-active');
            }
        }

        function schedule() {
            if (ticking) return;
            ticking = true;
            window.requestAnimationFrame(update);
        }

        $(window).on('scroll resize', schedule);
        $(document).on('click', '[role="tab"]', function() { setTimeout(update, 400); });
        $(window).on('ux:lens', function() { setTimeout(update, 50); });
        setTimeout(update, 600);
    }
    function smoothScrollTo(targetEl, offset) {
        if (!targetEl) return;
        var rect = targetEl.getBoundingClientRect();
        var top = rect.top + window.pageYOffset - (offset || 0);
        window.scrollTo({ top: top, behavior: 'smooth' });
    }

    // If the target lives inside a hidden tab panel, find and click the
    // controlling tab button so the panel becomes visible. Returns whether
    // a tab switch was performed.
    function activateOwningTab(target) {
        if (!target) return false;

        // Walk up collecting the tab triggers of any hidden ancestor panel.
        // Supports Elementor (native/nested via aria-controls, legacy via data-tab),
        // Bootstrap (data-target / data-bs-target), and generic anchor-based tabs.
        var triggers = [];
        var node = target.parentNode;
        while (node && node.nodeType === 1) {
            var $node = $(node);
            if ($node.is(':hidden')) {
                var id = node.id;
                var $trigger = $();
                if (id) {
                    $trigger = $('[aria-controls="' + id + '"], a[href="#' + id + '"], [data-target="#' + id + '"], [data-bs-target="#' + id + '"], [data-tab-target="#' + id + '"]');
                }
                if (!$trigger.length && $node.hasClass('elementor-tab-content')) {
                    var dt = $node.attr('data-tab');
                    var $tabs = $node.closest('.elementor-tabs');
                    if (dt && $tabs.length) {
                        $trigger = $tabs.find('.elementor-tab-title[data-tab="' + dt + '"]');
                    }
                }
                if ($trigger.length) {
                    triggers.push($trigger[0]);
                }
            }
            node = node.parentNode;
        }

        if (!triggers.length) return false;

        // Click outermost first, so nested tabs open in the right order.
        triggers.reverse().forEach(function(t) { t.click(); });
        return true;
    }

    function initToc($toc) {
        if ($toc.data('ux-toc-init')) return;
        $toc.data('ux-toc-init', true);

        setupToggle($toc);

        var mode = $toc.data('source-mode') || 'auto';
        var scrollOffset = parseInt($toc.data('scroll-offset'), 10) || 0;

        var items;

        if (mode === 'auto') {
            items = buildAutoList($toc);
            renderItems($toc, items);

            // If nothing found on first pass, retry once after a short delay —
            // handles cases where Theme Post Content renders late.
            if (!items.length) {
                setTimeout(function() {
                    var retryItems = buildAutoList($toc);
                    if (retryItems.length) {
                        renderItems($toc, retryItems);
                        items = retryItems;
                    }
                }, 500);
            }
        } else {
            items = [];
            $toc.find('.ux-toc__link').each(function() {
                var anchor = $(this).data('anchor');
                var el = anchor ? document.getElementById(anchor) : null;
                items.push({
                    label: $(this).text().trim(),
                    anchor: anchor,
                    level: 1,
                    el: el
                });
            });
        }

        // Smooth scroll on link click — switches tabs first if the target
        // lives in a hidden tabpanel, then scrolls into view.
        $toc.on('click', '.ux-toc__link', function(e) {
            var anchor = $(this).data('anchor');
            if (!anchor) return;

            var target = document.getElementById(anchor);
            if (!target) return;

            e.preventDefault();

            var switched = activateOwningTab(target);

            // Update URL hash
            if (window.history && history.replaceState) {
                history.replaceState(null, '', '#' + anchor);
            }

            // If we just switched tabs, wait until the target actually becomes
            // visible (tab content may fade/animate in) before scrolling.
            if (switched) {
                var tries = 0;
                var poll = setInterval(function() {
                    tries++;
                    if ($(target).is(':visible') || tries > 20) {
                        clearInterval(poll);
                        smoothScrollTo(target, scrollOffset);
                    }
                }, 30);
            } else {
                smoothScrollTo(target, scrollOffset);
            }
        });

        // Rebuild the auto list when the case study lens changes.
        if (mode === 'auto') {
            $(window).on('ux:lens', function() {
                renderItems($toc, buildAutoList($toc));
            });
        }

        setupScrollSpy($toc, scrollOffset);
    }

    $(function() {
        $('.ux-toc').each(function() {
            initToc($(this));
        });

        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-table-of-contents.default',
                function($scope) {
                    initToc($scope.find('.ux-toc'));
                }
            );
        });
    });

})(jQuery);

/* ============================================
   UX PDF VIEWER — PDF.js-based viewer with zoom, pan, page navigation, fullscreen.
   Loads PDF.js from CDN on first use.
   ============================================ */
(function($) {
    'use strict';

    var PDFJS_VERSION = '3.11.174'; // pinned version known to work standalone
    var PDFJS_URL = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/' + PDFJS_VERSION + '/pdf.min.js';
    var PDFJS_WORKER = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/' + PDFJS_VERSION + '/pdf.worker.min.js';

    var pdfjsLoadPromise = null;

    function loadPdfJs() {
        if (window.pdfjsLib) return Promise.resolve(window.pdfjsLib);
        if (pdfjsLoadPromise) return pdfjsLoadPromise;

        pdfjsLoadPromise = new Promise(function(resolve, reject) {
            var s = document.createElement('script');
            s.src = PDFJS_URL;
            s.async = true;
            s.onload = function() {
                if (window.pdfjsLib) {
                    window.pdfjsLib.GlobalWorkerOptions.workerSrc = PDFJS_WORKER;
                    resolve(window.pdfjsLib);
                } else {
                    reject(new Error('PDF.js loaded but window.pdfjsLib not set'));
                }
            };
            s.onerror = function() { reject(new Error('Failed to load PDF.js')); };
            document.head.appendChild(s);
        });
        return pdfjsLoadPromise;
    }

    function initPdf($pdf) {
        if ($pdf.data('ux-pdf-init')) return;
        $pdf.data('ux-pdf-init', true);

        var url = $pdf.data('pdf-url');
        if (!url) return;

        var $viewport = $pdf.find('.ux-pdf__viewport');
        var $canvas = $pdf.find('.ux-pdf__canvas');
        var canvas = $canvas[0];
        var ctx = canvas.getContext('2d');

        var $prev = $pdf.find('.ux-pdf__btn--prev');
        var $next = $pdf.find('.ux-pdf__btn--next');
        var $currentPageEl = $pdf.find('.ux-pdf__current-page');
        var $totalPagesEl = $pdf.find('.ux-pdf__total-pages');
        var $zoomIn = $pdf.find('.ux-pdf__btn--zoom-in');
        var $zoomOut = $pdf.find('.ux-pdf__btn--zoom-out');
        var $zoomFit = $pdf.find('.ux-pdf__btn--zoom-fit');
        var $fullscreen = $pdf.find('.ux-pdf__btn--fullscreen');

        var state = {
            pdf: null,
            currentPage: 1,
            totalPages: 0,
            scale: 1,
            fitScale: 1, // scale that fits the page to viewport width
            rendering: false,
            pendingPage: null,
            spread: $pdf.attr('data-layout') === 'spread'
        };

        function computeFitScale(page) {
            var viewport = page.getViewport({ scale: 1 });
            var viewportEl = $viewport[0];
            var paddingX = 20; // breathing room
            var availableWidth = viewportEl.clientWidth - paddingX;
            return Math.max(0.1, availableWidth / viewport.width);
        }

        function renderPage(pageNum) {
            if (state.rendering) {
                state.pendingPage = pageNum;
                return;
            }
            state.rendering = true;

            var rightNum = (state.spread && pageNum + 1 <= state.totalPages) ? pageNum + 1 : null;
            var jobs = [state.pdf.getPage(pageNum)];
            if (rightNum) jobs.push(state.pdf.getPage(rightNum));

            Promise.all(jobs).then(function(pages) {
                var left = pages[0], right = pages[1] || null;
                var gap = right ? 14 : 0;

                // Compute a fit scale across both pages (plus gap) when in fit mode.
                var lw1 = left.getViewport({ scale: 1 }).width;
                var rw1 = right ? right.getViewport({ scale: 1 }).width : 0;
                var totalW1 = lw1 + rw1 + gap;

                var renderScale;
                if (state.scale === 'fit') {
                    var avail = $viewport[0].clientWidth - 20;
                    renderScale = Math.max(0.1, avail / totalW1);
                    state.fitScale = renderScale;
                } else {
                    renderScale = state.scale;
                }

                var lvp = left.getViewport({ scale: renderScale });
                var rvp = right ? right.getViewport({ scale: renderScale }) : null;
                var gapS = right ? gap * renderScale : 0;
                var cw = lvp.width + (rvp ? rvp.width + gapS : 0);
                var ch = Math.max(lvp.height, rvp ? rvp.height : 0);

                var dpr = window.devicePixelRatio || 1;
                canvas.width = cw * dpr;   // also clears the canvas
                canvas.height = ch * dpr;
                canvas.style.width = cw + 'px';
                canvas.style.height = ch + 'px';

                // Render each page to its own offscreen canvas, then composite them
                // onto the visible canvas. Rendering two pages onto one shared
                // context sequentially is unreliable (left page can come out blank);
                // compositing separate renders is not.
                function toOffscreen(page, vp) {
                    var oc = document.createElement('canvas');
                    oc.width = Math.max(1, Math.floor(vp.width * dpr));
                    oc.height = Math.max(1, Math.floor(vp.height * dpr));
                    return page.render({
                        canvasContext: oc.getContext('2d'),
                        viewport: vp,
                        transform: [dpr, 0, 0, dpr, 0, 0]
                    }).promise.then(function() { return oc; });
                }

                var renders = [toOffscreen(left, lvp)];
                if (right) renders.push(toOffscreen(right, rvp));

                return Promise.all(renders).then(function(cs) {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(cs[0], 0, 0);
                    if (right && cs[1]) {
                        ctx.drawImage(cs[1], Math.round((lvp.width + gapS) * dpr), 0);
                    }
                    state.rendering = false;
                    updateUI();
                    if (state.pendingPage !== null) {
                        var p = state.pendingPage;
                        state.pendingPage = null;
                        renderPage(p);
                    }
                });
            }).catch(function(err) {
                state.rendering = false;
                if (window.console) console.warn('UX PDF: render failed', err);
            });
        }

        function updateUI() {
            if (state.spread) {
                var rp = Math.min(state.currentPage + 1, state.totalPages);
                $currentPageEl.text(rp > state.currentPage ? (state.currentPage + '–' + rp) : String(state.currentPage));
                $next.prop('disabled', state.currentPage + 1 >= state.totalPages);
            } else {
                $currentPageEl.text(state.currentPage);
                $next.prop('disabled', state.currentPage >= state.totalPages);
            }
            $totalPagesEl.text(state.totalPages);
            $prev.prop('disabled', state.currentPage <= 1);

            // Toggle pannable state when canvas is wider than viewport
            var viewportW = $viewport[0].clientWidth;
            var canvasW = canvas.clientWidth;
            $viewport.toggleClass('is-pannable', canvasW > viewportW);
        }

        // ============= Event handlers =============
        $prev.on('click', function(e) {
            e.preventDefault();
            if (state.currentPage <= 1) return;
            var step = state.spread ? 2 : 1;
            state.currentPage = Math.max(1, state.currentPage - step);
            renderPage(state.currentPage);
        });

        $next.on('click', function(e) {
            e.preventDefault();
            var atEnd = state.spread ? (state.currentPage + 1 >= state.totalPages) : (state.currentPage >= state.totalPages);
            if (atEnd) return;
            var step = state.spread ? 2 : 1;
            state.currentPage = Math.min(state.totalPages, state.currentPage + step);
            renderPage(state.currentPage);
        });

        $zoomIn.on('click', function(e) {
            e.preventDefault();
            // If currently in fit mode, lock the scale to the fit value first
            var current = state.scale === 'fit' ? state.fitScale : state.scale;
            state.scale = Math.min(current * 1.25, 4);
            renderPage(state.currentPage);
        });

        $zoomOut.on('click', function(e) {
            e.preventDefault();
            var current = state.scale === 'fit' ? state.fitScale : state.scale;
            state.scale = Math.max(current / 1.25, 0.2);
            renderPage(state.currentPage);
        });

        $zoomFit.on('click', function(e) {
            e.preventDefault();
            state.scale = 'fit';
            renderPage(state.currentPage);
        });

        $fullscreen.on('click', function(e) {
            e.preventDefault();
            var el = $pdf[0];
            if (!document.fullscreenElement && !document.webkitFullscreenElement) {
                var req = el.requestFullscreen || el.webkitRequestFullscreen;
                if (req) req.call(el);
            } else {
                var exit = document.exitFullscreen || document.webkitExitFullscreen;
                if (exit) exit.call(document);
            }
        });

        // Re-render to fit on fullscreen change / window resize
        function handleResize() {
            if (state.pdf && state.scale === 'fit') {
                renderPage(state.currentPage);
            }
        }
        $(document).on('fullscreenchange webkitfullscreenchange', handleResize);
        $(window).on('resize', debounce(handleResize, 200));

        // Observe viewport size changes — handles the case where the widget
        // is inside a hidden tab when PDF.js loads (clientWidth=0), then
        // becomes visible later when the user activates the tab.
        if (typeof ResizeObserver !== 'undefined') {
            var lastWidth = 0;
            var ro = new ResizeObserver(function(entries) {
                var w = entries[0].contentRect.width;
                if (w > 0 && w !== lastWidth && state.pdf && state.scale === 'fit') {
                    lastWidth = w;
                    renderPage(state.currentPage);
                }
            });
            ro.observe($viewport[0]);
        }

        // ============= Pan (drag when zoomed in) =============
        var panState = { dragging: false, startX: 0, startY: 0, scrollX: 0, scrollY: 0 };
        $viewport.on('mousedown', function(e) {
            if (!$viewport.hasClass('is-pannable')) return;
            // Don't start pan on a button or interactive element
            if ($(e.target).closest('button, a').length) return;
            panState.dragging = true;
            panState.startX = e.pageX;
            panState.startY = e.pageY;
            panState.scrollX = $viewport[0].scrollLeft;
            panState.scrollY = $viewport[0].scrollTop;
            $viewport.addClass('is-panning');
            e.preventDefault();
        });
        $(document).on('mousemove', function(e) {
            if (!panState.dragging) return;
            $viewport[0].scrollLeft = panState.scrollX - (e.pageX - panState.startX);
            $viewport[0].scrollTop = panState.scrollY - (e.pageY - panState.startY);
        });
        $(document).on('mouseup', function() {
            if (!panState.dragging) return;
            panState.dragging = false;
            $viewport.removeClass('is-panning');
        });

        // Touch panning works natively via overflow:auto, no JS needed

        // ============= Load PDF =============
        loadPdfJs().then(function(pdfjsLib) {
            return pdfjsLib.getDocument(url).promise;
        }).then(function(pdf) {
            state.pdf = pdf;
            state.totalPages = pdf.numPages;
            state.scale = 'fit';
            $pdf.addClass('is-loaded');
            renderPage(1);
        }).catch(function(err) {
            if (window.console) console.warn('UX PDF: failed to load', url, err);
            $pdf.find('.ux-pdf__loading').text('Failed to load PDF.');
        });
    }

    function debounce(fn, wait) {
        var t;
        return function() {
            var args = arguments, ctx = this;
            clearTimeout(t);
            t = setTimeout(function() { fn.apply(ctx, args); }, wait);
        };
    }

    $(function() {
        $('.ux-pdf').each(function() {
            initPdf($(this));
        });

        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-pdf-viewer.default',
                function($scope) {
                    initPdf($scope.find('.ux-pdf'));
                }
            );
        });
    });

})(jQuery);

/* ============================================
   UX SKETCH IDEATION — Filterable galleries
   Tab clicks filter the grid by data-gallery and update
   each item's lightbox slideshow id/index to match.
   ============================================ */
(function($) {
    'use strict';

    function applyFilter($wrapper, filter) {
        var $items = $wrapper.find('.sketch-item');
        var $grid = $wrapper.find('.sketch-grid');

        $wrapper.removeData('ux-shown');
        $grid.addClass('is-fading');

        setTimeout(function() {
            $items.each(function() {
                var $item = $(this);
                var g = $item.attr('data-gallery') || '';
                var match = (filter === '__all__') || (g === filter);
                $item.toggleClass('is-hidden', !match);

                // Re-point the lightbox slideshow id/index based on the active filter
                var $link = $item.find('.sketch-image');
                if (!$link.length) return;
                var allId = $link.attr('data-lightbox-all-id') || '';
                var galleryId = $link.attr('data-lightbox-gallery-id') || '';
                var allIndex = $link.attr('data-lightbox-all-index') || '0';
                var galleryIndex = $link.attr('data-lightbox-gallery-index') || '';

                if (filter === '__all__' || !galleryId) {
                    $link.attr('data-elementor-lightbox-slideshow', allId);
                    $link.attr('data-elementor-lightbox-index', allIndex);
                } else {
                    $link.attr('data-elementor-lightbox-slideshow', galleryId);
                    $link.attr('data-elementor-lightbox-index', galleryIndex);
                }
            });

            $grid.removeClass('is-fading');
            applyLimit($wrapper);
            justifyWrapper($wrapper);
        }, 150);

        // Update active tab state
        $wrapper.find('.sketch-tab').each(function() {
            var $tab = $(this);
            var isActive = $tab.attr('data-filter') === filter;
            $tab.toggleClass('is-active', isActive);
            $tab.attr('aria-selected', isActive ? 'true' : 'false');
        });

        $wrapper.attr('data-active-tab', filter);
    }

    function applyLimit($wrapper) {
        var limit = parseInt($wrapper.attr('data-limit'), 10);
        var $btnWrap = $wrapper.find('.sketch-more-wrap');
        if (!limit || limit < 1) { $btnWrap.hide(); return; }

        var shownLimit = $wrapper.data('ux-shown');
        if (typeof shownLimit !== 'number') {
            shownLimit = limit;
            $wrapper.data('ux-shown', shownLimit);
        }

        var $visible = $wrapper.find('.sketch-item').not('.is-hidden');
        var idx = 0;
        $visible.each(function() {
            $(this).toggleClass('is-over-limit', idx >= shownLimit);
            idx++;
        });
        $wrapper.find('.sketch-item.is-hidden').removeClass('is-over-limit');

        if (shownLimit < $visible.length) {
            $btnWrap.show();
        } else {
            $btnWrap.hide();
        }
    }

    // True justified layout: size each row so images keep their aspect ratio
    // (no crop) and fill the container width. Uses server-provided data-w/data-h
    // so it works with lazy-loaded images (no wait for load).
    function justifyGrid(grid) {
        if (!grid || !grid.classList.contains('layout-justified')) return;
        var below = grid.classList.contains('caption-below');
        var cs = getComputedStyle(grid);
        var gap = parseFloat(cs.getPropertyValue('--sketch-gap')); if (isNaN(gap)) gap = 25;
        var target = parseFloat(cs.getPropertyValue('--sketch-row-h')); if (isNaN(target)) target = 240;
        var containerW = grid.clientWidth;
        if (!containerW) return;

        var items = Array.prototype.slice.call(grid.querySelectorAll('.sketch-item')).filter(function(it) {
            return !it.classList.contains('is-hidden') && !it.classList.contains('is-over-limit');
        });
        if (!items.length) return;

        var data = items.map(function(it) {
            var w = parseFloat(it.getAttribute('data-w'));
            var h = parseFloat(it.getAttribute('data-h'));
            var ar;
            if (w > 0 && h > 0) {
                ar = w / h;
            } else {
                var img = it.querySelector('img');
                ar = (img && img.naturalWidth && img.naturalHeight) ? (img.naturalWidth / img.naturalHeight) : 1.5;
            }
            return { el: it, ar: ar };
        });

        var row = [], rowAR = 0;
        function commit(isLast) {
            if (!row.length) return;
            var gaps = (row.length - 1) * gap;
            var h;
            if (isLast && (rowAR * target + gaps) <= containerW) {
                h = target; // last row: don't stretch beyond target
            } else {
                h = (containerW - gaps - 1) / rowAR;
            }
            row.forEach(function(d) {
                d.el.style.width = (d.ar * h) + 'px';
                if (below) {
                    d.el.style.height = 'auto';
                    var iw = d.el.querySelector('.sketch-image');
                    if (iw) iw.style.height = h + 'px';
                } else {
                    d.el.style.height = h + 'px';
                }
            });
            row = []; rowAR = 0;
        }
        data.forEach(function(d) {
            row.push(d);
            rowAR += d.ar;
            var gaps = (row.length - 1) * gap;
            if (rowAR * target + gaps >= containerW) commit(false);
        });
        commit(true);
    }

    function justifyWrapper($wrapper) {
        justifyGrid($wrapper.find('.sketch-grid')[0]);
    }

    function initSketch($wrapper) {
        if ($wrapper.data('ux-sketch-init')) return;
        $wrapper.data('ux-sketch-init', true);

        $wrapper.on('click', '.sketch-tab', function(e) {
            e.preventDefault();
            var filter = $(this).attr('data-filter');
            if (!filter) return;
            applyFilter($wrapper, filter);
        });

        $wrapper.on('click', '.sketch-show-more', function(e) {
            e.preventDefault();
            var batch = parseInt($wrapper.attr('data-batch'), 10);
            if (!batch || batch < 1) batch = parseInt($wrapper.attr('data-limit'), 10) || 1;
            var current = $wrapper.data('ux-shown');
            if (typeof current !== 'number') current = parseInt($wrapper.attr('data-limit'), 10) || 0;
            $wrapper.data('ux-shown', current + batch);
            applyLimit($wrapper);
            justifyWrapper($wrapper);
        });

        applyLimit($wrapper);
        justifyWrapper($wrapper);
    }

    $(function() {
        $('.sketch-wrapper').each(function() {
            initSketch($(this));
        });

        var srt;
        function reflowAll() {
            $('.sketch-wrapper').each(function() { justifyWrapper($(this)); });
        }
        window.addEventListener('resize', function() {
            clearTimeout(srt);
            srt = setTimeout(reflowAll, 200);
        });
        window.addEventListener('load', reflowAll);

        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-sketch-ideation.default',
                function($scope) {
                    $scope.find('.sketch-wrapper').each(function() {
                        initSketch($(this));
                    });
                }
            );
        });
    });

})(jQuery);

/* ============================================
   SITEMAP — moved from inline script in widget render.
   Same behavior, same class names.
   ============================================ */
(function($) {
    'use strict';

    function initSitemap() {
        // Node expand/collapse — all trees on the page.
        document.querySelectorAll('.ux-sitemap-tree .tree-node').forEach(function(node) {
            node.onclick = function(e) {
                e.stopPropagation();
                this.closest('.tree-item').classList.toggle('expanded');
            };
        });

        // Tab switching — scoped to each wrapper.
        document.querySelectorAll('.ux-sitemap-wrapper').forEach(function(wrapper) {
            wrapper.querySelectorAll('.sitemap-tab').forEach(function(tab) {
                tab.onclick = function() {
                    var target = tab.getAttribute('data-target');
                    if (!target) return;

                    wrapper.querySelectorAll('.sitemap-tab').forEach(function(t) {
                        var on = t.getAttribute('data-target') === target;
                        t.classList.toggle('is-active', on);
                        t.setAttribute('aria-selected', on ? 'true' : 'false');
                    });

                    wrapper.querySelectorAll('.ux-sitemap-scroll').forEach(function(sc) {
                        var show = sc.getAttribute('data-sitemap') === target;
                        sc.classList.toggle('is-hidden', !show);
                        // Recompute scroll affordance for the newly shown tree.
                        if (show && sc._updateScroll) {
                            setTimeout(sc._updateScroll, 0);
                        }
                    });

                    wrapper.setAttribute('data-active-sitemap', target);
                };
            });
        });

        // Scroll buttons + edge fades — each scroll wrapper on desktop.
        document.querySelectorAll('.ux-sitemap-scroll.layout-tree').forEach(function(scroll) {
            if (window.innerWidth < 1025) return;

            var tree = scroll.querySelector('.ux-sitemap-tree.layout-tree');
            if (!tree) return;

            var left = scroll.querySelector('.scroll-left');
            var right = scroll.querySelector('.scroll-right');

            function update() {
                var scrollLeft = tree.scrollLeft;
                var max = tree.scrollWidth - tree.clientWidth;
                var canLeft = scrollLeft > 10;
                var canRight = scrollLeft < max - 10;

                if (left) left.style.display = canLeft ? 'block' : 'none';
                if (right) right.style.display = canRight ? 'block' : 'none';

                scroll.classList.toggle('can-scroll-left', canLeft);
                scroll.classList.toggle('can-scroll-right', canRight);
            }

            // Expose so tab-switch can recompute when a hidden tree becomes visible.
            scroll._updateScroll = update;

            if (left) left.onclick = function() { tree.scrollBy({ left: -300, behavior: 'smooth' }); };
            if (right) right.onclick = function() { tree.scrollBy({ left: 300, behavior: 'smooth' }); };

            tree.addEventListener('scroll', update);
            update();
        });
    }

    $(function() {
        initSitemap();

        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-sitemap.default',
                function() { setTimeout(initSitemap, 100); }
            );
        });
    });

})(jQuery);

/* ============================================
   USER FLOW — tab switching (v2.14.14)
   ============================================ */
(function($) {
    'use strict';

    function initFlowTabs() {
        document.querySelectorAll('.flow-wrapper').forEach(function(wrapper) {
            wrapper.querySelectorAll('.flow-tab').forEach(function(tab) {
                tab.onclick = function() {
                    var target = tab.getAttribute('data-target');
                    if (!target) return;

                    wrapper.querySelectorAll('.flow-tab').forEach(function(t) {
                        var on = t.getAttribute('data-target') === target;
                        t.classList.toggle('is-active', on);
                        t.setAttribute('aria-selected', on ? 'true' : 'false');
                    });

                    wrapper.querySelectorAll('.flow-container').forEach(function(c) {
                        c.classList.toggle('is-hidden', c.getAttribute('data-flow') !== target);
                    });

                    wrapper.setAttribute('data-active-flow', target);
                };
            });

            // Per-flow view toggle (Flowchart / Task List).
            wrapper.querySelectorAll('.flow-container').forEach(function(container) {
                container.querySelectorAll('.flow-view-toggle .view-tab').forEach(function(viewTab) {
                    viewTab.onclick = function() {
                        var view = viewTab.getAttribute('data-view');
                        if (!view) return;

                        container.querySelectorAll('.flow-view-toggle .view-tab').forEach(function(t) {
                            var on = t.getAttribute('data-view') === view;
                            t.classList.toggle('is-active', on);
                            t.setAttribute('aria-selected', on ? 'true' : 'false');
                        });

                        container.querySelectorAll('.flow-view').forEach(function(v) {
                            v.classList.toggle('is-hidden', !v.classList.contains('flow-view-' + view));
                        });

                        container.setAttribute('data-active-view', view);
                    };
                });
            });
        });
    }

    $(function() {
        initFlowTabs();

        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-user-flow-diagram.default',
                function() { setTimeout(initFlowTabs, 100); }
            );
        });
    });

})(jQuery);

/* ============================================
   STORYBOARD — tab switching (v2.14.20)
   ============================================ */
(function($) {
    'use strict';

    function initStoryboardTabs() {
        document.querySelectorAll('.storyboard-wrapper').forEach(function(wrapper) {
            wrapper.querySelectorAll('.storyboard-tab').forEach(function(tab) {
                tab.onclick = function() {
                    var target = tab.getAttribute('data-target');
                    if (!target) return;

                    wrapper.querySelectorAll('.storyboard-tab').forEach(function(t) {
                        var on = t.getAttribute('data-target') === target;
                        t.classList.toggle('is-active', on);
                        t.setAttribute('aria-selected', on ? 'true' : 'false');
                    });

                    wrapper.querySelectorAll('.storyboard-container').forEach(function(c) {
                        c.classList.toggle('is-hidden', c.getAttribute('data-storyboard') !== target);
                    });

                    wrapper.setAttribute('data-active-storyboard', target);
                };
            });
        });
    }

    $(function() {
        initStoryboardTabs();

        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-storyboard.default',
                function() { setTimeout(initStoryboardTabs, 100); }
            );
        });
    });

})(jQuery);

/* ============================================
   WIREFRAME SHOWCASE — carousel + annotation tooltips (v2.14.27)
   ============================================ */
(function($) {
    'use strict';

    function initWireframeCarousel(carousel) {
        if (!carousel || carousel.dataset.uxInited === '1') return;
        carousel.dataset.uxInited = '1';

        var track = carousel.querySelector('.carousel-track');
        var slides = carousel.querySelectorAll('.carousel-slide');
        var dots = carousel.querySelectorAll('.carousel-dot');
        var prevBtn = carousel.querySelector('.carousel-prev');
        var nextBtn = carousel.querySelector('.carousel-next');
        var current = 0;
        var slidesPerView = 1;
        var maxIndex = 0;

        if (!track || slides.length === 0) return;

        function readSlidesPerView() {
            var raw = getComputedStyle(carousel).getPropertyValue('--slides-per-view');
            var n = parseInt((raw || '').trim(), 10);
            return (n && n > 0) ? n : 1;
        }

        function applyPosition() {
            if (!slides[current] || !slides[0]) { track.style.transform = 'translateX(0)'; return; }
            var offset = slides[current].offsetLeft - slides[0].offsetLeft;
            var maxOffset = track.scrollWidth - carousel.clientWidth;
            if (maxOffset < 0) maxOffset = 0;
            if (offset > maxOffset) offset = maxOffset;
            track.style.transform = 'translateX(' + (-offset) + 'px)';
        }

        function updateDotsAndNav() {
            var validCount = maxIndex + 1;
            var windowSize = 7;
            var windowed = validCount > windowSize;
            var start = 0, end = validCount - 1;
            if (windowed) {
                start = Math.min(Math.max(current - Math.floor(windowSize / 2), 0), validCount - windowSize);
                end = start + windowSize - 1;
            }

            dots.forEach(function(d, i) {
                d.classList.toggle('active', i === current);
                d.classList.remove('dot-hide', 'dot-sm', 'dot-md');

                // Beyond the last usable starting position -> fully removed.
                if (i > maxIndex) {
                    d.style.display = 'none';
                    return;
                }
                d.style.display = '';

                if (!windowed) return;

                if (i < start || i > end) {
                    d.classList.add('dot-hide');
                } else if (start > 0 && i === start) {
                    d.classList.add('dot-sm');
                } else if (start > 0 && i === start + 1) {
                    d.classList.add('dot-md');
                } else if (end < validCount - 1 && i === end) {
                    d.classList.add('dot-sm');
                } else if (end < validCount - 1 && i === end - 1) {
                    d.classList.add('dot-md');
                }
            });

            // Hide nav entirely when carousel fits all slides.
            var hideNav = maxIndex <= 0;
            if (prevBtn) prevBtn.style.display = hideNav ? 'none' : '';
            if (nextBtn) nextBtn.style.display = hideNav ? 'none' : '';
            // Disable prev/next at boundaries (visual cue, still clickable but does nothing).
            if (prevBtn) prevBtn.toggleAttribute('disabled', current <= 0);
            if (nextBtn) nextBtn.toggleAttribute('disabled', current >= maxIndex);
        }

        function goToSlide(index) {
            current = Math.max(0, Math.min(index, maxIndex));
            applyPosition();
            updateDotsAndNav();
        }

        function recompute() {
            slidesPerView = readSlidesPerView();
            maxIndex = Math.max(0, slides.length - slidesPerView);
            if (current > maxIndex) current = maxIndex;
            applyPosition();
            updateDotsAndNav();
        }

        if (prevBtn) prevBtn.onclick = function() { goToSlide(current - 1); };
        if (nextBtn) nextBtn.onclick = function() { goToSlide(current + 1); };

        dots.forEach(function(dot) {
            dot.onclick = function() {
                goToSlide(parseInt(this.dataset.index, 10) || 0);
            };
        });

        // Recompute on window resize (slide widths change → translate offset stale).
        var resizeT;
        window.addEventListener('resize', function() {
            clearTimeout(resizeT);
            resizeT = setTimeout(recompute, 120);
        });

        // Initial layout.
        recompute();

        // Tooltip trigger for annotation dots (hover or click).
        var trigger = carousel.dataset.trigger || 'hover';

        function buildTip(dot) {
            if (dot.querySelector('.annotation-tooltip')) return;
            var tip = document.createElement('div');
            tip.className = 'annotation-tooltip';
            var titleText = dot.dataset.title || '';
            var contentText = dot.dataset.content || '';
            if (titleText) {
                var titleEl = document.createElement('div');
                titleEl.className = 'tooltip-title';
                titleEl.textContent = titleText;
                tip.appendChild(titleEl);
            }
            if (contentText) {
                var contentEl = document.createElement('div');
                contentEl.className = 'tooltip-content';
                contentEl.textContent = contentText;
                tip.appendChild(contentEl);
            }
            dot.appendChild(tip);
            // A11y: if content overflows the capped height, mark it as a
            // keyboard-focusable scrollable region with a screen-reader hint.
            if (tip.scrollHeight > tip.clientHeight + 1) {
                tip.classList.add('is-scrollable');
                tip.setAttribute('tabindex', '0');
                tip.setAttribute('role', 'group');
                tip.setAttribute('aria-label', (titleText ? titleText + ' — ' : '') + 'scrollable, use arrow keys to read more');
            }
        }
        function removeTip(dot) {
            var tip = dot.querySelector('.annotation-tooltip');
            if (tip) tip.remove();
        }

        carousel.querySelectorAll('.annotation-dot').forEach(function(dot) {
            if (trigger === 'click') {
                dot.onclick = function(e) {
                    e.stopPropagation();
                    var isOpen = dot.querySelector('.annotation-tooltip');
                    carousel.querySelectorAll('.annotation-dot').forEach(removeTip);
                    if (!isOpen) buildTip(dot);
                };
            } else {
                dot.onmouseenter = function() { buildTip(dot); };
                dot.onmouseleave = function() { removeTip(dot); };
            }
        });

        // Close click-tooltips when clicking elsewhere (bound once globally).
        if (trigger === 'click' && !document.__uxWfClickClose) {
            document.__uxWfClickClose = true;
            document.addEventListener('click', function() {
                document.querySelectorAll('.annotation-dot .annotation-tooltip').forEach(function(t) { t.remove(); });
            });
        }
    }

    function initAllWireframes() {
        document.querySelectorAll('.wireframe-carousel').forEach(initWireframeCarousel);
    }

    $(function() {
        initAllWireframes();

        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-wireframe-showcase.default',
                function() { setTimeout(initAllWireframes, 100); }
            );
        });
    });

})(jQuery);

/* ============================================
   PROTOTYPE DISPLAY — tab switching (v2.14.31)
   Pauses any videos in hidden tabs.
   ============================================ */
(function($) {
    'use strict';

    function pauseHidden(wrapper) {
        wrapper.querySelectorAll('.prototype-container.is-hidden video').forEach(function(v) {
            try { v.pause(); } catch (e) {}
        });
    }

    function initPrototypeTabs() {
        document.querySelectorAll('.prototype-wrapper').forEach(function(wrapper) {
            wrapper.querySelectorAll('.prototype-tab').forEach(function(tab) {
                tab.onclick = function() {
                    var target = tab.getAttribute('data-target');
                    if (!target) return;

                    wrapper.querySelectorAll('.prototype-tab').forEach(function(t) {
                        var on = t.getAttribute('data-target') === target;
                        t.classList.toggle('is-active', on);
                        t.setAttribute('aria-selected', on ? 'true' : 'false');
                    });

                    wrapper.querySelectorAll('.prototype-container').forEach(function(c) {
                        c.classList.toggle('is-hidden', c.getAttribute('data-prototype') !== target);
                    });

                    wrapper.setAttribute('data-active-prototype', target);
                    pauseHidden(wrapper);
                };
            });
        });
    }

    $(function() {
        initPrototypeTabs();

        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-prototype-display.default',
                function() { setTimeout(initPrototypeTabs, 100); }
            );
        });
    });

})(jQuery);

/* ============================================
   MOODBOARD — tab switching (v2.14.38)
   ============================================ */
(function($) {
    'use strict';

    function initMoodboardTabs() {
        document.querySelectorAll('.moodboard-wrapper').forEach(function(wrapper) {
            wrapper.querySelectorAll('.moodboard-tab').forEach(function(tab) {
                tab.onclick = function() {
                    var target = tab.getAttribute('data-target');
                    if (!target) return;

                    wrapper.querySelectorAll('.moodboard-tab').forEach(function(t) {
                        var on = t.getAttribute('data-target') === target;
                        t.classList.toggle('is-active', on);
                        t.setAttribute('aria-selected', on ? 'true' : 'false');
                    });

                    // Mark active moodboard-group (CSS does the rest).
                    wrapper.querySelectorAll('.moodboard-group').forEach(function(group) {
                        var slug = group.getAttribute('data-moodboard') || '';
                        group.classList.toggle('is-active-group', slug === target);
                    });

                    // Swap lightbox slideshow ID + index per item based on filter.
                    wrapper.querySelectorAll('.moodboard-item').forEach(function(item) {
                        var link = item.querySelector('.moodboard-image-link');
                        if (!link) return;
                        var allId      = link.getAttribute('data-lightbox-all-id') || '';
                        var allIndex   = link.getAttribute('data-lightbox-all-index') || '0';
                        var groupId    = link.getAttribute('data-lightbox-gallery-id') || '';
                        var groupIndex = link.getAttribute('data-lightbox-gallery-index') || '0';

                        if (target === '__all__' || !groupId) {
                            link.setAttribute('data-elementor-lightbox-slideshow', allId);
                            link.setAttribute('data-elementor-lightbox-index', allIndex);
                        } else {
                            link.setAttribute('data-elementor-lightbox-slideshow', groupId);
                            link.setAttribute('data-elementor-lightbox-index', groupIndex);
                        }
                    });

                    wrapper.setAttribute('data-active-moodboard', target);
                };
            });
        });
    }

    $(function() {
        initMoodboardTabs();
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-moodboard.default',
                function() { setTimeout(initMoodboardTabs, 100); }
            );
        });
    });

})(jQuery);

/* ============================================
   STYLE TILE — tab switching (v2.14.39)
   ============================================ */
(function($) {
    'use strict';

    function initStileTabs() {
        document.querySelectorAll('.ux-stile-wrapper').forEach(function(wrapper) {
            wrapper.querySelectorAll('.ux-stile-tab').forEach(function(tab) {
                tab.onclick = function() {
                    var target = tab.getAttribute('data-target');
                    if (!target) return;

                    wrapper.querySelectorAll('.ux-stile-tab').forEach(function(t) {
                        var on = t.getAttribute('data-target') === target;
                        t.classList.toggle('is-active', on);
                        t.setAttribute('aria-selected', on ? 'true' : 'false');
                    });

                    wrapper.querySelectorAll('.ux-stile-container').forEach(function(c) {
                        c.classList.toggle('is-hidden', c.getAttribute('data-tile') !== target);
                    });

                    wrapper.setAttribute('data-active-tile', target);
                };
            });

            // Per-tile view toggle (Image / Detail).
            wrapper.querySelectorAll('.ux-stile-container').forEach(function(container) {
                container.querySelectorAll('.ux-stile__view-toggle .ux-stile__view-tab').forEach(function(viewTab) {
                    viewTab.onclick = function() {
                        var view = viewTab.getAttribute('data-view');
                        if (!view) return;

                        container.querySelectorAll('.ux-stile__view-toggle .ux-stile__view-tab').forEach(function(t) {
                            var on = t.getAttribute('data-view') === view;
                            t.classList.toggle('is-active', on);
                            t.setAttribute('aria-selected', on ? 'true' : 'false');
                        });

                        container.querySelectorAll('.ux-stile__view').forEach(function(v) {
                            v.classList.toggle('is-hidden', !v.classList.contains('ux-stile__view--' + view));
                        });
                    };
                });
            });
        });
    }

    $(function() {
        initStileTabs();
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-style-tile.default',
                function() { setTimeout(initStileTabs, 100); }
            );
        });
    });

})(jQuery);

/* ============================================
   MOCKUP FLOW — tab switching (v2.14.40)
   ============================================ */
(function($) {
    'use strict';

    function initMockupFlowTabs() {
        document.querySelectorAll('.mockup-flow-wrapper').forEach(function(wrapper) {
            // Tab switching (when in tabs mode)
            wrapper.querySelectorAll('.mockup-flow-tab').forEach(function(tab) {
                tab.onclick = function() {
                    var target = tab.getAttribute('data-target');
                    if (!target) return;

                    wrapper.querySelectorAll('.mockup-flow-tab').forEach(function(t) {
                        var on = t.getAttribute('data-target') === target;
                        t.classList.toggle('is-active', on);
                        t.setAttribute('aria-selected', on ? 'true' : 'false');
                    });

                    wrapper.querySelectorAll('.mockup-flow-container').forEach(function(c) {
                        c.classList.toggle('is-hidden', c.getAttribute('data-flow') !== target);
                    });

                    wrapper.setAttribute('data-active-flow', target);
                };
            });

            // Accordion expand/collapse (when in accordion mode)
            wrapper.querySelectorAll('.mockup-flow-accordion__header').forEach(function(header) {
                header.onclick = function() {
                    var item = header.closest('.mockup-flow-accordion__item');
                    if (!item) return;
                    var willExpand = !item.classList.contains('is-expanded');
                    item.classList.toggle('is-expanded', willExpand);
                    header.setAttribute('aria-expanded', willExpand ? 'true' : 'false');
                };
            });
        });
    }

    $(function() {
        initMockupFlowTabs();
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-mockup-flow.default',
                function() { setTimeout(initMockupFlowTabs, 100); }
            );
        });
    });

})(jQuery);

/* ============================================
   BAR CHART — scroll-in animation (v2.14.85)
   ============================================ */
(function($) {
    'use strict';

    function initBarChart(chart) {
        if (chart.dataset.uxBarInit === '1') return;
        chart.dataset.uxBarInit = '1';

        if (!chart.classList.contains('can-animate')) return;
        chart.classList.add('js-anim');

        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function(entries) {
                entries.forEach(function(e) {
                    if (e.isIntersecting) {
                        e.target.classList.add('is-visible');
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: 0.25 });
            io.observe(chart);
        } else {
            chart.classList.add('is-visible');
        }
    }

    function initAll(ctx) {
        var root = ctx || document;
        root.querySelectorAll('.ux-barchart').forEach(initBarChart);
    }

    $(function() {
        initAll();
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-bar-chart.default',
                function($scope) { initAll($scope[0]); }
            );
        });
    });
})(jQuery);

/* ============================================
   WORD CLOUD — packed spiral layout (v2.14.88)
   DOM-based: keeps words as real text (selectable, crisp, linkable).
   ============================================ */
(function($) {
    'use strict';

    function packCloud(cloud) {
        // Skip while hidden (e.g. an inactive tab) — measurements would all be 0
        // and words would pile at the origin. The ResizeObserver re-packs once
        // the container gets a real width (its tab becomes active).
        if (!cloud.clientWidth || cloud.offsetParent === null) return;

        // Gather words; unwrap any previous canvas so this is re-runnable on resize.
        var prev = cloud.querySelector('.ux-wordcloud__canvas');
        var words;
        if (prev) {
            words = Array.prototype.slice.call(prev.querySelectorAll('.ux-wordcloud__word'));
            words.forEach(function(w) { cloud.appendChild(w); });
            prev.parentNode.removeChild(prev);
        } else {
            words = Array.prototype.slice.call(cloud.querySelectorAll('.ux-wordcloud__word'));
        }
        if (!words.length) return;

        cloud.style.visibility = 'hidden';
        var allowRotate = cloud.getAttribute('data-rotate') !== 'off';

        // Reset + assign rotation (biggest word stays horizontal; ~1 in 3 turned).
        words.forEach(function(w, i) {
            w.style.position = 'absolute';
            w.style.left = '0px';
            w.style.top = '0px';
            w.style.margin = '0';
            w.style.whiteSpace = 'nowrap';
            w.style.transformOrigin = 'center';
            w.style.transform = 'none';
            var angleSet = [0, -90, 0, 45, 0, 90, -45, 0];
            var rot = (allowRotate && i > 0) ? angleSet[i % angleSet.length] : 0;
            w.setAttribute('data-rot', rot);
        });

        // Measure unrotated size; footprint swaps for rotated words.
        // offsetWidth/Height are used (not getBoundingClientRect) so an ancestor
        // transform/zoom — e.g. the Elementor editor preview — can't scale the
        // measurement and cause overlap.
        var boxes = words.map(function(w) {
            var uw = w.offsetWidth, uh = w.offsetHeight;
            var deg = parseInt(w.getAttribute('data-rot'), 10) || 0;
            var rad = deg * Math.PI / 180;
            var ca = Math.abs(Math.cos(rad)), sa = Math.abs(Math.sin(rad));
            var aw = uw * ca + uh * sa;   // rotated bounding-box footprint
            var ah = uw * sa + uh * ca;
            return { el: w, deg: deg, uw: uw, uh: uh, w: aw, h: ah };
        });

        // Spiral placement with rectangle collision (largest first = DOM order).
        // Padding comes from the Spacing control (--wc-gap), applied on all sides.
        var gapRaw = getComputedStyle(cloud).getPropertyValue('--wc-gap');
        var pad = parseFloat(gapRaw); if (isNaN(pad)) pad = 6;
        var placed = [];
        function hits(x, y, w, h) {
            for (var i = 0; i < placed.length; i++) {
                var p = placed[i];
                if (x - pad < p.x + p.w && x + w + pad > p.x &&
                    y - pad < p.y + p.h && y + h + pad > p.y) return true;
            }
            return false;
        }
        boxes.forEach(function(b) {
            var angle = 0, radius = 0, x = 0, y = 0;
            for (var t = 0; t < 6000; t++) {
                x = radius * Math.cos(angle) - b.w / 2;
                y = radius * Math.sin(angle) * 0.66 - b.h / 2; // squash -> wider oval
                if (!hits(x, y, b.w, b.h)) break;
                angle += 0.4;
                radius += 0.9;
            }
            b.x = x; b.y = y;
            placed.push({ x: x, y: y, w: b.w, h: b.h });
        });

        // Bounds
        var minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
        boxes.forEach(function(b) {
            minX = Math.min(minX, b.x); minY = Math.min(minY, b.y);
            maxX = Math.max(maxX, b.x + b.w); maxY = Math.max(maxY, b.y + b.h);
        });
        var totalW = Math.max(1, maxX - minX), totalH = Math.max(1, maxY - minY);

        // Canvas + final placement (coords relative to canvas top-left).
        var canvas = document.createElement('div');
        canvas.className = 'ux-wordcloud__canvas';
        canvas.style.position = 'absolute';
        canvas.style.left = '50%';
        canvas.style.top = '0';
        canvas.style.width = totalW + 'px';
        canvas.style.height = totalH + 'px';

        boxes.forEach(function(b) {
            var fx = b.x - minX, fy = b.y - minY;
            var left = fx + (b.w - b.uw) / 2;   // center element inside its AABB footprint
            var top = fy + (b.h - b.uh) / 2;
            b.el.style.transform = b.deg ? ('rotate(' + b.deg + 'deg)') : 'none';
            b.el.style.left = left + 'px';
            b.el.style.top = top + 'px';
            canvas.appendChild(b.el);
        });

        cloud.style.position = 'relative';
        cloud.appendChild(canvas);

        // Scale to fit the container width.
        var avail = cloud.clientWidth || totalW;
        var scale = Math.min(1, (avail - 2) / totalW);
        canvas.style.transformOrigin = 'top center';
        canvas.style.transform = 'translateX(-50%) scale(' + scale + ')';
        cloud.style.height = (totalH * scale) + 'px';
        cloud.style.visibility = 'visible';
    }

    function initCloud(cloud) {
        if (!cloud.classList.contains('layout-packed')) return;
        packCloud(cloud);

        // Re-pack when the container's WIDTH changes — covers becoming visible
        // (0 -> real width when its tab is activated) and responsive resizing.
        // Height-only changes (which packCloud itself causes) are ignored so
        // this can't loop.
        if (window.ResizeObserver && !cloud.__uxRO) {
            var lastW = -1, rid;
            cloud.__uxRO = new ResizeObserver(function(entries) {
                var w = entries[0].contentRect.width;
                if (Math.abs(w - lastW) < 1) return;
                lastW = w;
                clearTimeout(rid);
                rid = setTimeout(function() { packCloud(cloud); }, 150);
            });
            cloud.__uxRO.observe(cloud);
        }
    }
    function initAll(ctx) {
        (ctx || document).querySelectorAll('.ux-wordcloud').forEach(initCloud);
    }

    var rt;
    $(function() {
        initAll();
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(function() { initAll(document); });
        }
        window.addEventListener('resize', function() {
            clearTimeout(rt);
            rt = setTimeout(function() { initAll(document); }, 200);
        });
        window.addEventListener('load', function() { initAll(document); });
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-word-cloud.default',
                function($scope) { initAll($scope[0]); }
            );
        });

        // In the Elementor editor, Spacing and Typography are selector/CSS-var
        // controls that change computed styles without re-running this JS. Poll
        // and re-pack when the gap or word font changes so the preview stays live.
        if (window.elementorFrontend && elementorFrontend.isEditMode && elementorFrontend.isEditMode()) {
            setInterval(function() {
                document.querySelectorAll('.ux-wordcloud.layout-packed').forEach(function(cloud) {
                    var cs = getComputedStyle(cloud);
                    var word = cloud.querySelector('.ux-wordcloud__word');
                    var wcs = word ? getComputedStyle(word) : null;
                    var key = cs.getPropertyValue('--wc-gap') + '|' +
                        (wcs ? (wcs.fontFamily + wcs.fontWeight + wcs.letterSpacing + wcs.fontStyle) : '');
                    if (cloud.__uxKey !== key) {
                        cloud.__uxKey = key;
                        packCloud(cloud);
                    }
                });
            }, 400);
        }
    });
})(jQuery);

/* ============================================
   SWOT ANALYSIS — set tabs (v2.14.104)
   ============================================ */
(function($) {
    'use strict';

    // Module scope survives Elementor's editor re-renders, so the tab you were
    // working on is restored instead of snapping back to the server default.
    var activeTabs = {};

    function initSwot(wrapper) {
        if (wrapper.dataset.uxSwotInit === '1') return;
        wrapper.dataset.uxSwotInit = '1';

        var tabs = wrapper.querySelectorAll('.ux-swot-tab');
        if (!tabs.length) return;

        var wid = wrapper.getAttribute('data-widget-id') || '';

        function activate(idx) {
            wrapper.querySelectorAll('.ux-swot-tab').forEach(function(t) {
                var on = (t.getAttribute('data-swot-tab') === idx);
                t.classList.toggle('is-active', on);
                t.setAttribute('aria-selected', on ? 'true' : 'false');
            });
            wrapper.querySelectorAll('.ux-swot-panel').forEach(function(p) {
                p.classList.toggle('is-hidden', p.getAttribute('data-swot-panel') !== idx);
            });
        }

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var idx = tab.getAttribute('data-swot-tab');
                if (wid) activeTabs[wid] = idx;
                activate(idx);
            });
        });

        // Restore the previously-selected tab if that panel still exists.
        if (wid && typeof activeTabs[wid] !== 'undefined') {
            var saved = activeTabs[wid];
            if (wrapper.querySelector('.ux-swot-panel[data-swot-panel="' + saved + '"]')) {
                activate(saved);
            }
        }
    }

    function initAll(ctx) {
        (ctx || document).querySelectorAll('.ux-swot-wrapper').forEach(initSwot);
    }

    $(function() {
        initAll();
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-swot-analysis.default',
                function($scope) { initAll($scope[0]); }
            );
        });
    });
})(jQuery);

/* ============================================
   AFFINITY MAP — version tabs (v2.15.6)
   ============================================ */
(function($) {
    'use strict';

    // Module scope survives Elementor's editor re-renders, so the version you
    // were editing is restored instead of snapping back to the default.
    var activeVersions = {};

    function initAffinity(wrapper) {
        if (wrapper.dataset.uxAffinityInit === '1') return;
        wrapper.dataset.uxAffinityInit = '1';

        // Per-group "+N more" expanders (work with or without version tabs).
        wrapper.querySelectorAll('.affinity-more').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var group = btn.closest('.affinity-group');
                if (!group) return;
                var expanded = group.classList.toggle('is-expanded');
                btn.textContent = expanded
                    ? (btn.getAttribute('data-less') || 'Show less')
                    : (btn.getAttribute('data-more') || 'Show more');
                btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            });
        });

        var tabs = wrapper.querySelectorAll('.affinity-tab');
        if (!tabs.length) return;

        var wid = wrapper.getAttribute('data-widget-id') || '';

        function activate(idx) {
            wrapper.querySelectorAll('.affinity-tab').forEach(function(t) {
                var on = (t.getAttribute('data-affinity-tab') === idx);
                t.classList.toggle('is-active', on);
                t.setAttribute('aria-selected', on ? 'true' : 'false');
            });
            wrapper.querySelectorAll('.affinity-panel').forEach(function(p) {
                p.classList.toggle('is-hidden', p.getAttribute('data-affinity-panel') !== idx);
            });
        }

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var idx = tab.getAttribute('data-affinity-tab');
                if (wid) activeVersions[wid] = idx;
                activate(idx);
            });
        });

        // Restore the previously-selected version if that panel still exists.
        if (wid && typeof activeVersions[wid] !== 'undefined') {
            var saved = activeVersions[wid];
            if (wrapper.querySelector('.affinity-panel[data-affinity-panel="' + saved + '"]')) {
                activate(saved);
            }
        }
    }

    function initAll(ctx) {
        (ctx || document).querySelectorAll('.affinity-map-wrapper').forEach(initAffinity);
    }

    $(function() {
        initAll();
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-affinity-map.default',
                function($scope) { initAll($scope[0]); }
            );
        });
    });
})(jQuery);

/* ============================================
   EMPATHY MAP — map tabs (v2.15.13)
   ============================================ */
(function($) {
    'use strict';

    // Module scope survives Elementor's editor re-renders, so the map you're
    // editing stays open instead of snapping back to the default.
    var activeMaps = {};

    function initEmpathy(wrapper) {
        if (wrapper.dataset.uxEmpathyInit === '1') return;
        wrapper.dataset.uxEmpathyInit = '1';

        var tabs = wrapper.querySelectorAll('.empathy-tab');
        if (!tabs.length) return;

        var wid = wrapper.getAttribute('data-widget-id') || '';

        function activate(idx) {
            wrapper.querySelectorAll('.empathy-tab').forEach(function(t) {
                var on = (t.getAttribute('data-empathy-tab') === idx);
                t.classList.toggle('is-active', on);
                t.setAttribute('aria-selected', on ? 'true' : 'false');
            });
            wrapper.querySelectorAll('.empathy-panel').forEach(function(p) {
                p.classList.toggle('is-hidden', p.getAttribute('data-empathy-panel') !== idx);
            });
        }

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var idx = tab.getAttribute('data-empathy-tab');
                if (wid) activeMaps[wid] = idx;
                activate(idx);
            });
        });

        if (wid && typeof activeMaps[wid] !== 'undefined') {
            var saved = activeMaps[wid];
            if (wrapper.querySelector('.empathy-panel[data-empathy-panel="' + saved + '"]')) {
                activate(saved);
            }
        }
    }

    function initAll(ctx) {
        (ctx || document).querySelectorAll('.empathy-map-wrapper').forEach(initEmpathy);
    }

    $(function() {
        initAll();
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-empathy-map.default',
                function($scope) { initAll($scope[0]); }
            );
        });
    });
})(jQuery);

/* ============================================
   USER PERSONA — persona tabs (v2.15.18)
   ============================================ */
(function($) {
    'use strict';

    // Survives Elementor editor re-renders, so the persona you're editing
    // stays open instead of snapping back to the default.
    var activePersonas = {};

    function initPersona(wrapper) {
        if (wrapper.dataset.uxPersonaInit === '1') return;
        wrapper.dataset.uxPersonaInit = '1';

        var tabs = wrapper.querySelectorAll('.persona-tab');
        if (!tabs.length) return;

        var wid = wrapper.getAttribute('data-widget-id') || '';

        function activate(idx) {
            wrapper.querySelectorAll('.persona-tab').forEach(function(t) {
                var on = (t.getAttribute('data-persona-tab') === idx);
                t.classList.toggle('is-active', on);
                t.setAttribute('aria-selected', on ? 'true' : 'false');
            });
            wrapper.querySelectorAll('.persona-panel').forEach(function(p) {
                p.classList.toggle('is-hidden', p.getAttribute('data-persona-panel') !== idx);
            });
        }

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var idx = tab.getAttribute('data-persona-tab');
                if (wid) activePersonas[wid] = idx;
                activate(idx);
            });
        });

        if (wid && typeof activePersonas[wid] !== 'undefined') {
            var saved = activePersonas[wid];
            if (wrapper.querySelector('.persona-panel[data-persona-panel="' + saved + '"]')) {
                activate(saved);
            }
        }
    }

    function initAll(ctx) {
        (ctx || document).querySelectorAll('.ux-user-persona-wrapper').forEach(initPersona);
    }

    $(function() {
        initAll();
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-user-persona.default',
                function($scope) { initAll($scope[0]); }
            );
        });
    });
})(jQuery);

/* ============================================
   USER STATEMENTS — group tabs (v2.15.21)
   ============================================ */
(function($) {
    'use strict';

    // Survives Elementor editor re-renders, so the group you're editing stays open.
    var activeGroups = {};

    function initStatements(wrapper) {
        if (wrapper.dataset.uxStatementsInit === '1') return;
        wrapper.dataset.uxStatementsInit = '1';

        var tabs = wrapper.querySelectorAll('.statements-tab');
        if (!tabs.length) return;

        var wid = wrapper.getAttribute('data-widget-id') || '';

        function activate(idx) {
            wrapper.querySelectorAll('.statements-tab').forEach(function(t) {
                var on = (t.getAttribute('data-statements-tab') === idx);
                t.classList.toggle('is-active', on);
                t.setAttribute('aria-selected', on ? 'true' : 'false');
            });
            wrapper.querySelectorAll('.statements-panel').forEach(function(p) {
                p.classList.toggle('is-hidden', p.getAttribute('data-statements-panel') !== idx);
            });
        }

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var idx = tab.getAttribute('data-statements-tab');
                if (wid) activeGroups[wid] = idx;
                activate(idx);
            });
        });

        if (wid && typeof activeGroups[wid] !== 'undefined') {
            var saved = activeGroups[wid];
            if (wrapper.querySelector('.statements-panel[data-statements-panel="' + saved + '"]')) {
                activate(saved);
            }
        }
    }

    function initAll(ctx) {
        (ctx || document).querySelectorAll('.ux-statements-wrapper').forEach(initStatements);
    }

    $(function() {
        initAll();
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-user-statements.default',
                function($scope) { initAll($scope[0]); }
            );
        });
    });
})(jQuery);

/* ============================================
   FEATURES & BENEFITS — version tabs (v2.15.25)
   ============================================ */
(function($) {
    'use strict';

    var activeFb = {};

    function initFb(wrapper) {
        if (wrapper.dataset.uxFbInit === '1') return;
        wrapper.dataset.uxFbInit = '1';

        var tabs = wrapper.querySelectorAll('.ux-fb-tab');
        if (!tabs.length) return;

        var wid = wrapper.getAttribute('data-widget-id') || '';

        function activate(idx) {
            wrapper.querySelectorAll('.ux-fb-tab').forEach(function(t) {
                var on = (t.getAttribute('data-fb-tab') === idx);
                t.classList.toggle('is-active', on);
                t.setAttribute('aria-selected', on ? 'true' : 'false');
            });
            wrapper.querySelectorAll('.ux-fb-panel').forEach(function(p) {
                p.classList.toggle('is-hidden', p.getAttribute('data-fb-panel') !== idx);
            });
        }

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var idx = tab.getAttribute('data-fb-tab');
                if (wid) activeFb[wid] = idx;
                activate(idx);
            });
        });

        if (wid && typeof activeFb[wid] !== 'undefined') {
            var saved = activeFb[wid];
            if (wrapper.querySelector('.ux-fb-panel[data-fb-panel="' + saved + '"]')) {
                activate(saved);
            }
        }
    }

    function initAll(ctx) {
        (ctx || document).querySelectorAll('.ux-fb-wrapper').forEach(initFb);
    }

    $(function() {
        initAll();
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/ux-features-benefits.default',
                function($scope) { initAll($scope[0]); }
            );
        });
    });
})(jQuery);

/* ============================================
   GENERIC TAB HANDLER — v2.15.29
   --------------------------------------------
   Drop-in replacement for the per-widget tab IIFEs. A new tabbed widget needs
   NO new JS — just render the standard markup (see UX-SCAFFOLDING.md):
     wrapper:  <div class="X-wrapper" data-ux-tabs data-widget-id="…">
     tablist:  <button class="…" data-ux-tab="0"> … </button>
     panels:   <div class="…" data-ux-panel="0"> … </div>
   Works for any widget; keys editor-persistence state by widget id so the tab
   you're editing survives Elementor re-renders.
   ============================================ */
(function($) {
    'use strict';

    var uxTabState = {}; // widgetId -> active index string, survives re-renders

    function initTabHost(host) {
        if (host.dataset.uxTabsInit === '1') return;
        host.dataset.uxTabsInit = '1';

        var tabs = host.querySelectorAll('[data-ux-tab]');
        if (!tabs.length) return;

        var wid = host.getAttribute('data-widget-id') || '';

        function activate(idx) {
            host.querySelectorAll('[data-ux-tab]').forEach(function(t) {
                var on = (t.getAttribute('data-ux-tab') === idx);
                t.classList.toggle('is-active', on);
                t.setAttribute('aria-selected', on ? 'true' : 'false');
            });
            host.querySelectorAll('[data-ux-panel]').forEach(function(p) {
                p.classList.toggle('is-hidden', p.getAttribute('data-ux-panel') !== idx);
            });
        }

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var idx = tab.getAttribute('data-ux-tab');
                if (wid) uxTabState[wid] = idx;
                activate(idx);
            });
            // Keyboard: arrow keys move between tabs
            tab.addEventListener('keydown', function(e) {
                if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') return;
                e.preventDefault();
                var list = Array.prototype.slice.call(host.querySelectorAll('[data-ux-tab]'));
                var i = list.indexOf(tab);
                var next = e.key === 'ArrowRight' ? (i + 1) % list.length : (i - 1 + list.length) % list.length;
                list[next].focus();
                list[next].click();
            });
        });

        // Restore the tab this widget was on before an editor re-render.
        if (wid && typeof uxTabState[wid] !== 'undefined') {
            var saved = uxTabState[wid];
            if (host.querySelector('[data-ux-panel="' + saved + '"]')) {
                activate(saved);
            }
        }
    }

    function initAll(ctx) {
        (ctx || document).querySelectorAll('[data-ux-tabs]').forEach(initTabHost);
    }

    $(function() {
        initAll();
        uxWhenElementorReady(function() {
            // Fires for every widget; the [data-ux-tabs] filter keeps it cheap.
            elementorFrontend.hooks.addAction('frontend/element_ready/global', function($scope) {
                if ($scope && $scope[0]) initAll($scope[0]);
            });
        });
    });
})(jQuery);


/* ============================================
   ANNOTATED DESIGN — hotspots + sketch/render toggle (v2.15.41)
   ============================================ */
(function($) {
    'use strict';

    function initDesign(design) {
        if (design.dataset.uxAdInit === '1') return;
        design.dataset.uxAdInit = '1';
        var trigger = design.getAttribute('data-trigger') || 'click';

        design.querySelectorAll('.ux-ad-toggle-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var show = btn.getAttribute('data-show');
                design.querySelectorAll('.ux-ad-toggle-btn').forEach(function(b) {
                    b.classList.toggle('is-active', b === btn); b.setAttribute('aria-pressed', b === btn ? 'true' : 'false');
                });
                var r = design.querySelector('.ux-ad-img-render');
                var s = design.querySelector('.ux-ad-img-sketch');
                if (r) r.classList.toggle('is-active', show === 'render');
                if (s) s.classList.toggle('is-active', show === 'sketch');
                // Hotspots are positioned for the render, so hide them on the
                // sketch/reference view where they wouldn't line up. Also close
                // any open panel when switching away from the render.
                var stage = design.querySelector('.ux-ad-stage');
                if (stage) stage.classList.toggle('ux-ad-hide-spots', show !== 'render');
                if (show !== 'render') {
                    design.querySelectorAll('.ux-ad-hotspot.is-open').forEach(function(h) {
                        h.classList.remove('is-open');
                        var d = h.querySelector('.ux-ad-dot');
                        if (d) d.setAttribute('aria-expanded', 'false');
                    });
                }
            });
        });

        var hotspots = Array.prototype.slice.call(design.querySelectorAll('.ux-ad-hotspot'));

        function closeAll(except) {
            hotspots.forEach(function(h) {
                if (h !== except) {
                    h.classList.remove('is-open');
                    var d = h.querySelector('.ux-ad-dot');
                    if (d) d.setAttribute('aria-expanded', 'false');
                }
            });
        }

        function openHotspot(h) {
            var stage = design.querySelector('.ux-ad-stage');
            var panel = h.querySelector('.ux-ad-panel');
            if (stage) {
                var hRect = h.getBoundingClientRect();
                var sRect = stage.getBoundingClientRect();
                var below = hRect.top - sRect.top;
                h.classList.toggle('panel-up', below > sRect.height * 0.55);
                if (panel) {
                    panel.classList.remove('align-left', 'align-right');
                    var pW = panel.offsetWidth;
                    var dotCentre = hRect.left + hRect.width / 2;
                    var leftIfCentred = dotCentre - pW / 2;
                    var rightIfCentred = dotCentre + pW / 2;
                    if (leftIfCentred < sRect.left) {
                        panel.classList.add('align-left');
                    } else if (rightIfCentred > sRect.right) {
                        panel.classList.add('align-right');
                    }
                }
            }
            h.classList.add('is-open');
            var dot = h.querySelector('.ux-ad-dot');
            if (dot) dot.setAttribute('aria-expanded', 'true');
        }

        hotspots.forEach(function(h) {
            var dot = h.querySelector('.ux-ad-dot');
            if (!dot) return;
            if (trigger === 'hover') {
                h.addEventListener('mouseenter', function() { closeAll(h); openHotspot(h); });
                h.addEventListener('mouseleave', function() { h.classList.remove('is-open'); dot.setAttribute('aria-expanded', 'false'); });
                dot.addEventListener('focus', function() { closeAll(h); openHotspot(h); });
                dot.addEventListener('blur', function() { h.classList.remove('is-open'); dot.setAttribute('aria-expanded', 'false'); });
            } else {
                dot.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var open = h.classList.contains('is-open');
                    closeAll(h);
                    if (open) {
                        h.classList.remove('is-open');
                        dot.setAttribute('aria-expanded', 'false');
                    } else {
                        openHotspot(h);
                    }
                });
            }
        });

        if (trigger === 'click') {
            document.addEventListener('click', function(e) {
                if (!design.contains(e.target)) closeAll(null);
            });
        }
        design.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeAll(null);
        });
    }


    var uxAdTabState = {}; // widgetId -> active tab index, survives editor re-renders

    function initAdTabs(wrapper) {
        if (wrapper.dataset.uxAdTabsInit === '1') return;
        wrapper.dataset.uxAdTabsInit = '1';
        var tabs = wrapper.querySelectorAll('[data-ad-tab]');
        if (!tabs.length) return;

        var wid = wrapper.getAttribute('data-widget-id') || '';

        function activate(idx) {
            wrapper.querySelectorAll('[data-ad-tab]').forEach(function(t) {
                t.classList.toggle('is-active', t.getAttribute('data-ad-tab') === idx);
            });
            wrapper.querySelectorAll('[data-ad-panel]').forEach(function(p) {
                p.classList.toggle('is-hidden', p.getAttribute('data-ad-panel') !== idx);
            });
        }

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var idx = tab.getAttribute('data-ad-tab');
                if (wid) uxAdTabState[wid] = idx;
                activate(idx);
            });
        });

        // Restore the tab this widget was on before an editor re-render.
        if (wid && typeof uxAdTabState[wid] !== 'undefined') {
            var saved = uxAdTabState[wid];
            if (wrapper.querySelector('[data-ad-panel="' + saved + '"]')) {
                activate(saved);
            }
        }
    }

    function initAll(ctx) {
        (ctx || document).querySelectorAll('.ux-ad-design').forEach(initDesign);
        (ctx || document).querySelectorAll('[data-ux-adtabs]').forEach(initAdTabs);
    }

    $(function() {
        initAll();
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction('frontend/element_ready/ux-annotated-design.default', function($scope) {
                if ($scope && $scope[0]) initAll($scope[0]);
            });
        });
    });
})(jQuery);

/* ============================================
   BEFORE / AFTER SLIDER (inside Wireframe Showcase) — v2.15.50
   ============================================ */
(function($) {
    'use strict';

    function initBaFrame(frame) {
        if (frame.dataset.uxBaInit === '1') return;
        frame.dataset.uxBaInit = '1';

        var before = frame.querySelector('.ux-ba-before');
        var beforeInner = frame.querySelector('.ux-ba-before-inner');
        var divider = frame.querySelector('.ux-ba-divider');
        var range = frame.querySelector('.ux-ba-range');
        var beforeImg = frame.querySelector('.ux-ba-before-img');
        var afterImg = frame.querySelector('.ux-ba-after-img');
        if (!before || !divider || !range) return;

        function setPos(pct) {
            pct = Math.max(0, Math.min(100, pct));
            before.style.width = pct + '%';
            divider.style.left = pct + '%';
        }

        // Lock the before inner layer to the frame's exact pixel width so the
        // before image matches the after image's size and never resizes as the
        // outer wrapper is clipped by the drag. Guarded against zero width
        // (carousel slides can start unlaid-out) and re-applied on load/paint.
        function syncWidth() {
            var w = frame.offsetWidth;
            if (w > 0) {
                frame.style.setProperty('--ux-ba-fw', w + 'px');
            }
        }
        syncWidth();
        if (afterImg && !afterImg.complete) afterImg.addEventListener('load', syncWidth);
        if (beforeImg && !beforeImg.complete) beforeImg.addEventListener('load', syncWidth);
        window.requestAnimationFrame(syncWidth);
        setTimeout(syncWidth, 200);
        setTimeout(syncWidth, 600);

        if (window.ResizeObserver) {
            var ro = new ResizeObserver(syncWidth);
            ro.observe(frame);
        } else {
            window.addEventListener('resize', syncWidth);
        }

        range.addEventListener('input', function() {
            setPos(parseFloat(range.value));
        });

        // Optional hover-follow on desktop (frame has ux-ba-hover class).
        if (frame.classList.contains('ux-ba-hover')) {
            frame.addEventListener('mousemove', function(e) {
                var rect = frame.getBoundingClientRect();
                var pct = ((e.clientX - rect.left) / rect.width) * 100;
                range.value = pct;
                setPos(pct);
            });
        }

        // Initialize from data-start.
        var start = parseFloat(frame.getAttribute('data-start'));
        if (isNaN(start)) start = 50;
        range.value = start;
        setPos(start);
    }

    function initAll(ctx) {
        (ctx || document).querySelectorAll('.ux-ba-frame').forEach(initBaFrame);
    }

    $(function() {
        initAll();
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction('frontend/element_ready/ux-wireframe-showcase.default', function($scope) {
                if ($scope && $scope[0]) initAll($scope[0]);
            });
        });
    });
})(jQuery);

/* ============================================
   BRAND ASSETS — direction tabs (v2.15.66)
   ============================================ */
(function($) {
    'use strict';
    var uxBdTabState = {}; // widgetId -> active tab index, survives editor re-renders

    function initBrandTabs(wrapper) {
        if (wrapper.dataset.uxBdInit === '1') return;
        wrapper.dataset.uxBdInit = '1';
        var tabs = wrapper.querySelectorAll('[data-bd-tab]');
        if (!tabs.length) return;
        var wid = wrapper.getAttribute('data-widget-id') || '';

        function activate(idx) {
            wrapper.querySelectorAll('[data-bd-tab]').forEach(function(t) {
                t.classList.toggle('is-active', t.getAttribute('data-bd-tab') === idx);
            });
            wrapper.querySelectorAll('[data-bd-panel]').forEach(function(p) {
                p.classList.toggle('is-hidden', p.getAttribute('data-bd-panel') !== idx);
            });
        }

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var idx = tab.getAttribute('data-bd-tab');
                if (wid) uxBdTabState[wid] = idx;
                activate(idx);
            });
        });

        if (wid && typeof uxBdTabState[wid] !== 'undefined') {
            var saved = uxBdTabState[wid];
            if (wrapper.querySelector('[data-bd-panel="' + saved + '"]')) activate(saved);
        }
    }

    function initAll(ctx) {
        (ctx || document).querySelectorAll('[data-ux-brandtabs]').forEach(initBrandTabs);
    }

    $(function() {
        initAll();
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction('frontend/element_ready/ux-brand-assets.default', function($scope) {
                if ($scope && $scope[0]) initAll($scope[0]);
            });
        });
    });
})(jQuery);


/* ============================================
   UX LENS SWITCH — filter a case study by skill lens.
   Elements carry data-lens="ux brand" (UX widget Advanced > Case Study Lens,
   or an Elementor container attribute). Untagged elements always show.
   ============================================ */
(function($) {
    'use strict';

    function switches() { return $('.ux-lens'); }

    function tagged() {
        return $('[data-lens]').filter(function() {
            return !$(this).closest('.ux-lens').length && !$(this).is('.ux-lens');
        });
    }

    function keysOf(el) {
        return String(el.getAttribute('data-lens') || '').toLowerCase().split(/[\s,]+/).filter(Boolean);
    }

    function knownLenses() {
        var map = {};
        $('.ux-lens__btn[data-lens-key]').each(function() {
            var k = String(this.getAttribute('data-lens-key'));
            if (k !== 'all') map[k] = $.trim($(this).text());
        });
        return map;
    }

    function usedLenses() {
        var used = {};
        tagged().each(function() { keysOf(this).forEach(function(k) { used[k] = true; }); });
        return used;
    }

    function renderNote($sw, key, label, hidden, mode) {
        var note = $sw.find('.ux-lens__note')[0];
        if (!note) return;
        note.textContent = '';
        if (key === 'all' || String($sw.data('note')) !== 'yes') { note.hidden = true; return; }
        note.hidden = false;
        var verb = mode === 'dim' ? ' dimmed' : ' hidden';
        note.appendChild(document.createTextNode(
            'Showing ' + label + '. ' + hidden + (hidden === 1 ? ' section' : ' sections') + verb + '. '
        ));
        var b = document.createElement('button');
        b.type = 'button';
        b.className = 'ux-lens__reset';
        b.textContent = 'Show ' + String($sw.data('all-label') || 'everything').toLowerCase();
        note.appendChild(b);
    }

    function apply(key, opts) {
        opts = opts || {};
        var lenses = knownLenses();
        key = String(key || 'all').toLowerCase();
        if (key !== 'all' && !lenses[key]) key = 'all';

        var $sw = switches();
        var mode = String($sw.first().data('mode') || 'hide') === 'dim' ? 'dim' : 'hide';
        var hidden = 0;

        tagged().each(function() {
            var on = key === 'all' || keysOf(this).indexOf(key) !== -1;
            this.classList.remove('ux-lens-off', 'ux-lens-dim');
            if (!on) {
                this.classList.add(mode === 'dim' ? 'ux-lens-dim' : 'ux-lens-off');
                hidden++;
            }
        });

        document.documentElement.setAttribute('data-ux-lens', key);

        $sw.each(function() {
            var $s = $(this);
            $s.find('.ux-lens__btn').each(function() {
                var on = String(this.getAttribute('data-lens-key')) === key;
                this.setAttribute('aria-pressed', on ? 'true' : 'false');
                this.classList.toggle('is-active', on);
            });
            renderNote($s, key, lenses[key] || '', hidden, mode);
        });

        if (!opts.skipUrl && $sw.filter('[data-sync="yes"]').length && window.history && history.replaceState) {
            try {
                var url = new URL(window.location.href);
                if (key === 'all') url.searchParams.delete('lens'); else url.searchParams.set('lens', key);
                history.replaceState(history.state, '', url.toString());
            } catch (err) { /* old browser: skip URL sync */ }
        }

        $(window).trigger('ux:lens', [{ lens: key, hidden: hidden }]);
    }

    function setup() {
        var $sw = switches();
        if (!$sw.length) return;
        var used = usedLenses();
        var any = Object.keys(used).length > 0;
        var editing = document.body.classList.contains('elementor-editor-active');

        $sw.each(function() {
            var $s = $(this);
            if (String($s.data('which')) === 'auto') {
                $s.find('.ux-lens__btn').each(function() {
                    var k = String(this.getAttribute('data-lens-key'));
                    if (k !== 'all' && !used[k]) this.hidden = true;
                });
                if (!any && !editing) $s.closest('.elementor-widget').attr('hidden', 'hidden');
            }
        });

        var start = 'all';
        try { start = new URL(window.location.href).searchParams.get('lens') || 'all'; } catch (err) {}
        apply(start, { skipUrl: true });
    }

    $(document).on('click', '.ux-lens__btn', function(e) {
        e.preventDefault();
        apply(this.getAttribute('data-lens-key'));
    });
    $(document).on('click', '.ux-lens__reset', function(e) {
        e.preventDefault();
        apply('all');
    });

    $(setup);

    if (window.uxWhenElementorReady) {
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction('frontend/element_ready/ux-lens-switch.default', function() { setup(); });
        });
    }
})(jQuery);

/* ============================================
   UX TL;DR CARD — key moments + ?view=tldr short view.
   ============================================ */
(function($) {
    'use strict';

    function params() { try { return new URL(window.location.href).searchParams; } catch (e) { return new URLSearchParams(''); } }
    function currentLens() { return document.documentElement.getAttribute('data-ux-lens') || params().get('lens') || 'all'; }

    function slug(t) { return String(t).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 60) || 'moment'; }

    function lensKeys(el) {
        var n = el.closest('[data-lens]');
        return n ? String(n.getAttribute('data-lens')).toLowerCase().split(/[\s,]+/).filter(Boolean) : [];
    }

    function labelFor(el) {
        var a = String(el.getAttribute('data-key-moment') || '').trim();
        if (a && a !== '1' && a !== 'yes' && a !== 'true') return a;
        var h = el.querySelector('.widget-title, h1, h2, h3, h4');
        if (h) return h.textContent.replace(/\s+/g, ' ').trim();
        return 'Key moment';
    }

    function moments() {
        return Array.prototype.filter.call(document.querySelectorAll('[data-key-moment]'), function(el) {
            return !el.closest('.ux-tldr');
        });
    }

    function ensureId(el, label, used) {
        if (el.id) return el.id;
        var base = 'moment-' + slug(label), id = base, i = 2;
        while (document.getElementById(id) || used[id]) { id = base + '-' + i++; }
        used[id] = true; el.id = id; return id;
    }

    function openTabsFor(el) {
        var opened = false, node = el.parentElement, panels = [];
        while (node) { if (node.getAttribute && node.getAttribute('role') === 'tabpanel') panels.push(node); node = node.parentElement; }
        panels.reverse().forEach(function(p) {
            var hidden = p.hidden || window.getComputedStyle(p).display === 'none';
            if (hidden && p.id) {
                var t = document.querySelector('[role="tab"][aria-controls="' + p.id.replace(/"/g, '') + '"]');
                if (t) { t.click(); opened = true; }
            }
        });
        return opened;
    }

    function goTo(el) {
        var opened = openTabsFor(el);
        setTimeout(function() {
            var top = el.getBoundingClientRect().top + window.pageYOffset - 90;
            window.scrollTo({ top: top, behavior: 'smooth' });
        }, opened ? 450 : 0);
    }

    function fullUrl(hash) {
        var u; try { u = new URL(window.location.href); } catch (e) { return '#'; }
        u.searchParams.delete('view');
        var lens = currentLens();
        if (lens && lens !== 'all') u.searchParams.set('lens', lens); else u.searchParams.delete('lens');
        u.hash = hash ? '#' + hash : '';
        return u.toString();
    }

    function shortUrl() {
        var u; try { u = new URL(window.location.href); } catch (e) { return window.location.href; }
        u.searchParams.set('view', 'tldr'); u.hash = '';
        var lens = currentLens();
        if (lens && lens !== 'all') u.searchParams.set('lens', lens); else u.searchParams.delete('lens');
        return u.toString();
    }

    function build($card) {
        var nav = $card.find('.ux-tldr__moments')[0];
        if (!nav) return;
        var list = nav.querySelector('.ux-tldr__moments-list');
        var thumbs = nav.getAttribute('data-thumbs') === 'yes';
        var lens = currentLens(), used = {}, count = 0;
        list.textContent = '';
        moments().forEach(function(el) {
            if (el.closest('.ux-lens-off')) return;
            var keys = lensKeys(el);
            if (lens !== 'all' && keys.length && keys.indexOf(lens) === -1) return;
            var label = labelFor(el);
            var id = ensureId(el, label, used);
            var li = document.createElement('li');
            li.className = 'ux-tldr__moment';
            var a = document.createElement('a');
            a.className = 'ux-tldr__moment-link';
            a.setAttribute('href', isShort() ? fullUrl(id) : '#' + id);
            a.setAttribute('data-target', id);
            if (thumbs) {
                var img = el.querySelector('img');
                var src = img && (img.currentSrc || img.getAttribute('src'));
                if (src && /^(https?:)?\/\//.test(src) || (src && src.charAt(0) === '/')) {
                    var t = document.createElement('img');
                    t.className = 'ux-tldr__thumb'; t.alt = ''; t.loading = 'lazy'; t.src = src;
                    a.appendChild(t);
                }
            }
            var span = document.createElement('span');
            span.className = 'ux-tldr__moment-text';
            span.textContent = label;
            a.appendChild(span);
            li.appendChild(a);
            list.appendChild(li);
            count++;
        });
        nav.hidden = count === 0;
    }

    function isShort() { return params().get('view') === 'tldr'; }

    function shortView($card) {
        var root = $card.closest('[data-elementor-type="wp-post"]')[0] || document.body;
        var node = $card.closest('.elementor-element')[0];
        while (node && node !== root) {
            var parent = node.parentElement;
            if (!parent) break;
            Array.prototype.forEach.call(parent.children, function(sib) {
                if (sib !== node && !sib.matches('script, style, link')) sib.classList.add('ux-tldr-hidden');
            });
            node = parent;
        }
        document.documentElement.classList.add('ux-view-tldr');
        var full = $card.find('.ux-tldr__full')[0];
        if (full) { full.hidden = false; full.setAttribute('href', fullUrl('')); }
    }

    function setup() {
        var $cards = $('.ux-tldr');
        if (!$cards.length) return;
        var short = isShort();
        $cards.each(function() {
            var $c = $(this);
            if ($c.data('ux-tldr-init')) return;
            $c.data('ux-tldr-init', true);
            build($c);
            if (short) shortView($c);
        });
        if (!short && window.location.hash) {
            var target = document.getElementById(window.location.hash.slice(1).replace(/[^A-Za-z0-9_-]/g, ''));
            if (target && target.hasAttribute('data-key-moment')) setTimeout(function() { goTo(target); }, 500);
        }
    }

    $(document).on('click', '.ux-tldr__moment-link', function(e) {
        if (isShort()) return; // normal navigation to the full case study
        var el = document.getElementById(this.getAttribute('data-target'));
        if (!el) return;
        e.preventDefault();
        if (window.history && history.replaceState) history.replaceState(history.state, '', '#' + el.id);
        goTo(el);
    });

    $(document).on('click', '.ux-tldr__copy', function() {
        var btn = this, url = shortUrl(), orig = btn.textContent;
        var done = function() { btn.textContent = btn.getAttribute('data-done') || 'Link copied'; setTimeout(function() { btn.textContent = orig; }, 1800); };
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(done, function() { window.prompt('Copy this link:', url); });
        } else { window.prompt('Copy this link:', url); }
    });

    $(window).on('ux:lens', function() {
        $('.ux-tldr').each(function() { build($(this)); var f = $(this).find('.ux-tldr__full')[0]; if (f && !f.hidden) f.setAttribute('href', fullUrl('')); });
    });

    $(function() { setTimeout(setup, 0); });

    if (window.uxWhenElementorReady) {
        uxWhenElementorReady(function() {
            elementorFrontend.hooks.addAction('frontend/element_ready/ux-tldr.default', function($scope) {
                $scope.find('.ux-tldr').removeData('ux-tldr-init');
                setup();
            });
        });
    }
})(jQuery);
/* Visual Competitive Analysis — show more / fewer brands */
(function () {
  document.addEventListener('click', function (e) {
    var btn = e.target.closest ? e.target.closest('.vca-toggle') : null;
    if (!btn) return;
    var body = document.getElementById(btn.getAttribute('aria-controls'));
    if (!body) return;
    var open = btn.getAttribute('aria-expanded') !== 'true';
    Array.prototype.forEach.call(body.querySelectorAll('.vca-row-more'), function (r) { r.hidden = !open; });
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    btn.textContent = open ? btn.getAttribute('data-less') : btn.getAttribute('data-more');
  });
})();
/* Brand Assets — show more / fewer sections */
(function () {
  document.addEventListener('click', function (e) {
    var btn = e.target.closest ? e.target.closest('.ba-toggle') : null;
    if (!btn) return;
    var box = document.getElementById(btn.getAttribute('aria-controls'));
    if (!box) return;
    var open = btn.getAttribute('aria-expanded') !== 'true';
    Array.prototype.forEach.call(box.querySelectorAll('[data-ba-more]'), function (el) { el.hidden = !open; });
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    btn.textContent = open ? btn.getAttribute('data-less') : btn.getAttribute('data-more');
  });
})();

/* v2.19.1 visible tab captions: icon-only tab rows show the active tab's name as text */
(function () {
  var ROWS = '.flow-tabs, .storyboard-tabs, .prototype-tabs, .mockup-flow-tabs';
  function label(tab) { return tab ? (tab.getAttribute('aria-label') || tab.getAttribute('title') || '').trim() : ''; }
  function update(row) {
    var cap = row.nextElementSibling;
    if (!cap || !cap.classList || !cap.classList.contains('ux-tab-caption')) return;
    var active = row.querySelector('[aria-selected="true"], .is-active');
    var txt = label(active);
    cap.textContent = txt;
    cap.hidden = !txt;
  }
  function setup(root) {
    (root || document).querySelectorAll(ROWS).forEach(function (row) {
      if (row.dataset.uxCaption) return;
      var tabs = row.querySelectorAll('[role="tab"], button');
      var named = Array.prototype.some.call(tabs, function (t) { return label(t) !== ''; });
      if (!named) return;
      row.dataset.uxCaption = '1';
      var cap = document.createElement('p');
      cap.className = 'ux-tab-caption';
      cap.setAttribute('aria-live', 'polite');
      row.parentNode.insertBefore(cap, row.nextSibling);
      update(row);
      row.addEventListener('click', function () { setTimeout(function () { update(row); }, 0); });
      if (window.MutationObserver) {
        new MutationObserver(function () { update(row); }).observe(row, { subtree: true, attributes: true, attributeFilter: ['aria-selected', 'class'] });
      }
    });
  }
  if (document.readyState !== 'loading') setup(); else document.addEventListener('DOMContentLoaded', function () { setup(); });
  window.addEventListener('elementor/frontend/init', function () {
    if (window.elementorFrontend && elementorFrontend.hooks) {
      ['ux-user-flow-diagram', 'ux-storyboard', 'ux-prototype-display', 'ux-mockup-flow'].forEach(function (w) {
        elementorFrontend.hooks.addAction('frontend/element_ready/' + w + '.default', function ($el) { setup($el && $el[0] ? $el[0] : document); });
      });
    }
  });
})();
/* end visible tab captions */

/* ============================================
   VISUAL DESIGN GALLERY — scroll on hover (v2.20.0)
   Sets each tile's scroll time from the image height, so tall and short
   images move at the same pace. CSS does the motion; this only times it.
   ============================================ */
(function () {
  'use strict';
  function time(img, speed) {
    var m = img.closest('.gallery-media');
    if (!m || !img.naturalWidth) return;
    var full = img.naturalHeight * (m.clientWidth / img.naturalWidth);
    var dist = Math.max(0, full - m.clientHeight);
    var secs = Math.min(20, Math.max(1.5, dist / speed));
    img.style.setProperty('--ux-scroll-dur', secs.toFixed(2) + 's');
  }
  function init(root) {
    (root || document).querySelectorAll('.gallery-grid.is-hover-scroll').forEach(function (g) {
      if (g.dataset.uxScrollInit === '1') return;
      g.dataset.uxScrollInit = '1';
      var speed = parseFloat(g.getAttribute('data-scroll-speed')) || 400;
      g.querySelectorAll('.gallery-media img').forEach(function (img) {
        var run = function () { time(img, speed); };
        if (img.complete) run(); else img.addEventListener('load', run);
        var item = img.closest('.gallery-item');
        if (item) { item.addEventListener('mouseenter', run); item.addEventListener('focusin', run); }
      });
    });
  }
  if (document.readyState !== 'loading') init(); else document.addEventListener('DOMContentLoaded', function () { init(); });
  window.addEventListener('elementor/frontend/init', function () {
    if (window.elementorFrontend && elementorFrontend.hooks) {
      elementorFrontend.hooks.addAction('frontend/element_ready/ux-visual-design-gallery.default', function ($el) { init($el && $el[0] ? $el[0] : document); });
    }
  });
})();
/* end scroll on hover */

/* Logo concepts: variations carousel (2.20.6) */
(function () {
    function init(root) {
        if (root.dataset.uxLcReady) return;
        root.dataset.uxLcReady = '1';
        var track = root.querySelector('.ux-logo-track');
        var slides = track ? track.children : [];
        var dots = root.querySelectorAll('.ux-logo-dot');
        var prev = root.querySelector('.ux-logo-prev');
        var next = root.querySelector('.ux-logo-next');
        if (!track || slides.length < 2) return;
        function index() { return Math.round(track.scrollLeft / Math.max(1, track.clientWidth)); }
        function go(i) { i = Math.max(0, Math.min(slides.length - 1, i)); track.scrollTo({ left: i * track.clientWidth }); }
        function sync() {
            var i = index();
            dots.forEach(function (d, k) { d.classList.toggle('is-active', k === i); if (k === i) d.setAttribute('aria-current', 'true'); else d.removeAttribute('aria-current'); });
            if (prev) prev.hidden = i === 0;
            if (next) next.hidden = i === slides.length - 1;
        }
        dots.forEach(function (d, k) { d.addEventListener('click', function () { go(k); }); });
        if (prev) prev.addEventListener('click', function () { go(index() - 1); });
        if (next) next.addEventListener('click', function () { go(index() + 1); });
        track.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowRight') { e.preventDefault(); go(index() + 1); }
            if (e.key === 'ArrowLeft') { e.preventDefault(); go(index() - 1); }
        });
        var t; track.addEventListener('scroll', function () { clearTimeout(t); t = setTimeout(sync, 60); }, { passive: true });
        window.addEventListener('resize', function () { go(index()); });
        sync();
    }
    function run() { document.querySelectorAll('[data-ux-logo-carousel]').forEach(init); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run); else run();
    if (window.jQuery) jQuery(window).on('elementor/frontend/init', function () {
        if (window.elementorFrontend) elementorFrontend.hooks.addAction('frontend/element_ready/ux-logo-concepts.default', function ($s) { $s[0].querySelectorAll('[data-ux-logo-carousel]').forEach(init); });
    });
})();
/* v2.20.7 user flow: hide arrows that land at the end (or start) of a wrapped row */
(function () {
  function markRowEnds(flow) {
    var dir = window.getComputedStyle(flow).flexDirection || '';
    var kids = flow.children;
    for (var i = 0; i < kids.length; i++) {
      var a = kids[i];
      if (!a.classList.contains('flow-arrow')) continue;
      a.classList.remove('is-row-end');
      if (dir.indexOf('row') !== 0) continue;
      var r = a.getBoundingClientRect();
      if (!r.height) continue;
      var mid = r.top + r.height / 2;
      var off = function (el) {
        if (!el) return true;
        var b = el.getBoundingClientRect();
        return mid < b.top || mid > b.bottom;
      };
      if (off(a.nextElementSibling) || off(a.previousElementSibling)) a.classList.add('is-row-end');
    }
  }
  var ro = ('ResizeObserver' in window) ? new ResizeObserver(function (entries) {
    entries.forEach(function (e) { markRowEnds(e.target); });
  }) : null;
  function init() {
    var flows = document.querySelectorAll('.user-flow');
    for (var i = 0; i < flows.length; i++) {
      var f = flows[i];
      markRowEnds(f);
      if (f.getAttribute('data-uf-init')) continue;
      f.setAttribute('data-uf-init', '1');
      if (ro) ro.observe(f);
    }
  }
  if (!ro) window.addEventListener('resize', init);
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
  window.addEventListener('elementor/frontend/init', function () {
    if (window.elementorFrontend && window.elementorFrontend.hooks) {
      window.elementorFrontend.hooks.addAction('frontend/element_ready/ux-user-flow-diagram.default', init);
    }
  });
})();
/* end user flow */
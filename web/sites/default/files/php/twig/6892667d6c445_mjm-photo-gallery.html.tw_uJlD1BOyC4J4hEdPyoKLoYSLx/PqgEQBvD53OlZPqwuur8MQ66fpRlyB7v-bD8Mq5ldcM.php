<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* modules/custom/mjm_components/templates/mjm-photo-gallery.html.twig */
class __TwigTemplate_28f6dc90f97b02f796c57a82f4ff74bf extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 12
        yield "
<div class=\"mjm-photo-gallery mjm-photo-gallery--";
        // line 13
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ((CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "layout", [], "any", true, true, true, 13)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "layout", [], "any", false, false, true, 13), "grid")) : ("grid")), "html", null, true);
        yield "\" id=\"";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["gallery_id"] ?? null), "html", null, true);
        yield "\">
  ";
        // line 14
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "gallery_title", [], "any", false, false, true, 14)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 15
            yield "    <h3 class=\"mjm-photo-gallery__title\">";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "gallery_title", [], "any", false, false, true, 15), "html", null, true);
            yield "</h3>
  ";
        }
        // line 17
        yield "
  <div class=\"mjm-photo-gallery__container\" data-columns=\"";
        // line 18
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ((CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "columns", [], "any", true, true, true, 18)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "columns", [], "any", false, false, true, 18), 3)) : (3)), "html", null, true);
        yield "\">
    ";
        // line 19
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["images"] ?? null));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["image"]) {
            // line 20
            yield "      <div class=\"mjm-photo-gallery__item\" data-index=\"";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, true, 20), "html", null, true);
            yield "\">
        <div class=\"mjm-photo-gallery__image-wrapper\">
          <img src=\"";
            // line 22
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "thumbnail", [], "any", false, false, true, 22), "html", null, true);
            yield "\" 
               data-full=\"";
            // line 23
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "url", [], "any", false, false, true, 23), "html", null, true);
            yield "\"
               alt=\"";
            // line 24
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "title", [], "any", false, false, true, 24), "html", null, true);
            yield "\" 
               class=\"mjm-photo-gallery__image\"
               loading=\"lazy\">
          
          ";
            // line 28
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "enable_lightbox", [], "any", false, false, true, 28)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 29
                yield "            <div class=\"mjm-photo-gallery__overlay\">
              <button class=\"mjm-photo-gallery__view-btn\" title=\"View full size\">
                <i class=\"fas fa-expand\"></i>
              </button>
            </div>
          ";
            }
            // line 35
            yield "        </div>

        ";
            // line 37
            if ((CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "show_captions", [], "any", false, false, true, 37) && (CoreExtension::getAttribute($this->env, $this->source, $context["image"], "title", [], "any", false, false, true, 37) || CoreExtension::getAttribute($this->env, $this->source, $context["image"], "description", [], "any", false, false, true, 37)))) {
                // line 38
                yield "          <div class=\"mjm-photo-gallery__caption\">
            ";
                // line 39
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["image"], "title", [], "any", false, false, true, 39)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 40
                    yield "              <h4 class=\"mjm-photo-gallery__caption-title\">";
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "title", [], "any", false, false, true, 40), "html", null, true);
                    yield "</h4>
            ";
                }
                // line 42
                yield "            ";
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["image"], "description", [], "any", false, false, true, 42)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 43
                    yield "              <p class=\"mjm-photo-gallery__caption-description\">";
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "description", [], "any", false, false, true, 43), "html", null, true);
                    yield "</p>
            ";
                }
                // line 45
                yield "            ";
                if (((CoreExtension::getAttribute($this->env, $this->source, $context["image"], "photographer", [], "any", false, false, true, 45) || CoreExtension::getAttribute($this->env, $this->source, $context["image"], "date_taken", [], "any", false, false, true, 45)) || CoreExtension::getAttribute($this->env, $this->source, $context["image"], "location", [], "any", false, false, true, 45))) {
                    // line 46
                    yield "              <div class=\"mjm-photo-gallery__caption-meta\">
                ";
                    // line 47
                    if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["image"], "photographer", [], "any", false, false, true, 47)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                        // line 48
                        yield "                  <span class=\"mjm-photo-gallery__photographer\">📸 ";
                        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "photographer", [], "any", false, false, true, 48), "html", null, true);
                        yield "</span>
                ";
                    }
                    // line 50
                    yield "                ";
                    if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["image"], "date_taken", [], "any", false, false, true, 50)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                        // line 51
                        yield "                  <span class=\"mjm-photo-gallery__date\">📅 ";
                        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "date_taken", [], "any", false, false, true, 51), "html", null, true);
                        yield "</span>
                ";
                    }
                    // line 53
                    yield "                ";
                    if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["image"], "location", [], "any", false, false, true, 53)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                        // line 54
                        yield "                  <span class=\"mjm-photo-gallery__location\">📍 ";
                        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "location", [], "any", false, false, true, 54), "html", null, true);
                        yield "</span>
                ";
                    }
                    // line 56
                    yield "              </div>
            ";
                }
                // line 58
                yield "          </div>
        ";
            }
            // line 60
            yield "      </div>
    ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['image'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 62
        yield "  </div>

  ";
        // line 65
        yield "  ";
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "layout", [], "any", false, false, true, 65) == "carousel")) {
            // line 66
            yield "    <div class=\"mjm-photo-gallery__carousel-controls\">
      <button class=\"mjm-photo-gallery__carousel-btn mjm-photo-gallery__carousel-btn--prev\" title=\"Previous photo\">
        <i class=\"fas fa-chevron-left\"></i>
      </button>
      <button class=\"mjm-photo-gallery__carousel-btn mjm-photo-gallery__carousel-btn--next\" title=\"Next photo\">
        <i class=\"fas fa-chevron-right\"></i>
      </button>
    </div>
    
    <div class=\"mjm-photo-gallery__carousel-indicators\">
      ";
            // line 76
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["images"] ?? null));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["image"]) {
                // line 77
                yield "        <button class=\"mjm-photo-gallery__indicator";
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "first", [], "any", false, false, true, 77)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    yield " active";
                }
                yield "\" 
                data-index=\"";
                // line 78
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, true, 78), "html", null, true);
                yield "\" 
                title=\"Photo ";
                // line 79
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index", [], "any", false, false, true, 79), "html", null, true);
                yield "\"></button>
      ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['image'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 81
            yield "    </div>

    ";
            // line 83
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "auto_play", [], "any", false, false, true, 83)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 84
                yield "      <div class=\"mjm-photo-gallery__carousel-play\">
        <button class=\"mjm-photo-gallery__play-btn\" title=\"Play/Pause slideshow\">
          <i class=\"fas fa-play\"></i>
        </button>
      </div>
    ";
            }
            // line 90
            yield "  ";
        }
        // line 91
        yield "
  ";
        // line 93
        yield "  ";
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "enable_lightbox", [], "any", false, false, true, 93)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 94
            yield "    <div class=\"mjm-photo-gallery__lightbox\">
      <div class=\"mjm-photo-gallery__lightbox-overlay\"></div>
      <div class=\"mjm-photo-gallery__lightbox-content\">
        <button class=\"mjm-photo-gallery__lightbox-close\" title=\"Close lightbox\">
          <i class=\"fas fa-times\"></i>
        </button>
        
        <div class=\"mjm-photo-gallery__lightbox-image-container\">
          <img src=\"\" alt=\"\" class=\"mjm-photo-gallery__lightbox-image\">
          
          <button class=\"mjm-photo-gallery__lightbox-prev\" title=\"Previous photo\">
            <i class=\"fas fa-chevron-left\"></i>
          </button>
          <button class=\"mjm-photo-gallery__lightbox-next\" title=\"Next photo\">
            <i class=\"fas fa-chevron-right\"></i>
          </button>
        </div>

        <div class=\"mjm-photo-gallery__lightbox-info\">
          <h4 class=\"mjm-photo-gallery__lightbox-title\"></h4>
          <p class=\"mjm-photo-gallery__lightbox-description\"></p>
          <div class=\"mjm-photo-gallery__lightbox-meta\"></div>
        </div>

        ";
            // line 118
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "show_thumbnails", [], "any", false, false, true, 118)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 119
                yield "          <div class=\"mjm-photo-gallery__lightbox-thumbnails\">
            ";
                // line 120
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["images"] ?? null));
                $context['loop'] = [
                  'parent' => $context['_parent'],
                  'index0' => 0,
                  'index'  => 1,
                  'first'  => true,
                ];
                if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                    $length = count($context['_seq']);
                    $context['loop']['revindex0'] = $length - 1;
                    $context['loop']['revindex'] = $length;
                    $context['loop']['length'] = $length;
                    $context['loop']['last'] = 1 === $length;
                }
                foreach ($context['_seq'] as $context["_key"] => $context["image"]) {
                    // line 121
                    yield "              <button class=\"mjm-photo-gallery__lightbox-thumb";
                    if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "first", [], "any", false, false, true, 121)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                        yield " active";
                    }
                    yield "\" 
                      data-index=\"";
                    // line 122
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, true, 122), "html", null, true);
                    yield "\" 
                      title=\"";
                    // line 123
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "title", [], "any", false, false, true, 123), "html", null, true);
                    yield "\">
                <img src=\"";
                    // line 124
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "thumbnail", [], "any", false, false, true, 124), "html", null, true);
                    yield "\" alt=\"";
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "title", [], "any", false, false, true, 124), "html", null, true);
                    yield "\" loading=\"lazy\">
              </button>
            ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                    if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                        --$context['loop']['revindex0'];
                        --$context['loop']['revindex'];
                        $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['image'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 127
                yield "          </div>
        ";
            }
            // line 129
            yield "
        <div class=\"mjm-photo-gallery__lightbox-counter\">
          <span class=\"mjm-photo-gallery__lightbox-current\">1</span> / <span class=\"mjm-photo-gallery__lightbox-total\">";
            // line 131
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["images"] ?? null)), "html", null, true);
            yield "</span>
        </div>

        ";
            // line 134
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "auto_play", [], "any", false, false, true, 134)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 135
                yield "          <div class=\"mjm-photo-gallery__lightbox-controls\">
            <button class=\"mjm-photo-gallery__lightbox-play\" title=\"Play/Pause slideshow\">
              <i class=\"fas fa-play\"></i>
            </button>
          </div>
        ";
            }
            // line 141
            yield "      </div>
    </div>
  ";
        }
        // line 144
        yield "</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["config", "gallery_id", "images", "loop"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/custom/mjm_components/templates/mjm-photo-gallery.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  394 => 144,  389 => 141,  381 => 135,  379 => 134,  373 => 131,  369 => 129,  365 => 127,  346 => 124,  342 => 123,  338 => 122,  331 => 121,  314 => 120,  311 => 119,  309 => 118,  283 => 94,  280 => 93,  277 => 91,  274 => 90,  266 => 84,  264 => 83,  260 => 81,  244 => 79,  240 => 78,  233 => 77,  216 => 76,  204 => 66,  201 => 65,  197 => 62,  182 => 60,  178 => 58,  174 => 56,  168 => 54,  165 => 53,  159 => 51,  156 => 50,  150 => 48,  148 => 47,  145 => 46,  142 => 45,  136 => 43,  133 => 42,  127 => 40,  125 => 39,  122 => 38,  120 => 37,  116 => 35,  108 => 29,  106 => 28,  99 => 24,  95 => 23,  91 => 22,  85 => 20,  68 => 19,  64 => 18,  61 => 17,  55 => 15,  53 => 14,  47 => 13,  44 => 12,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/custom/mjm_components/templates/mjm-photo-gallery.html.twig", "/var/www/html/web/modules/custom/mjm_components/templates/mjm-photo-gallery.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 14, "for" => 19];
        static $filters = ["escape" => 13, "default" => 13, "length" => 131];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
                ['escape', 'default', 'length'],
                [],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}

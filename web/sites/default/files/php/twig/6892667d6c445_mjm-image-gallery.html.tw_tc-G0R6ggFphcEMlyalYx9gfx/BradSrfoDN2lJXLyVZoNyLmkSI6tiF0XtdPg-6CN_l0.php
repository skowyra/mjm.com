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

/* modules/custom/mjm_components/templates/mjm-image-gallery.html.twig */
class __TwigTemplate_66f12e4aa900a001ae29a033da9e28b2 extends Template
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
<div class=\"mjm-image-gallery\" id=\"";
        // line 13
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["gallery_id"] ?? null), "html", null, true);
        yield "\">
  <div class=\"mjm-image-gallery__container\">
    ";
        // line 15
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "gallery_title", [], "any", false, false, true, 15)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 16
            yield "      <h3 class=\"mjm-image-gallery__title\">";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "gallery_title", [], "any", false, false, true, 16), "html", null, true);
            yield "</h3>
    ";
        }
        // line 18
        yield "    
    ";
        // line 20
        yield "    <div class=\"mjm-image-gallery__loading\">
      <div class=\"mjm-image-gallery__spinner\"></div>
      <p>Loading gallery...</p>
    </div>

    ";
        // line 26
        yield "    <div class=\"mjm-image-gallery__viewer\" id=\"";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["gallery_id"] ?? null), "html", null, true);
        yield "-viewer\"></div>

    ";
        // line 29
        yield "    <div class=\"mjm-image-gallery__controls\">
      <div class=\"mjm-image-gallery__nav\">
        <button class=\"mjm-image-gallery__btn mjm-image-gallery__btn--prev\" title=\"Previous image\">
          <i class=\"fas fa-chevron-left\"></i>
        </button>
        <button class=\"mjm-image-gallery__btn mjm-image-gallery__btn--next\" title=\"Next image\">
          <i class=\"fas fa-chevron-right\"></i>
        </button>
      </div>

      ";
        // line 39
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "enable_zoom", [], "any", false, false, true, 39)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 40
            yield "        <div class=\"mjm-image-gallery__zoom\">
          <button class=\"mjm-image-gallery__btn mjm-image-gallery__btn--zoom-in\" title=\"Zoom in\">
            <i class=\"fas fa-search-plus\"></i>
          </button>
          <button class=\"mjm-image-gallery__btn mjm-image-gallery__btn--zoom-out\" title=\"Zoom out\">
            <i class=\"fas fa-search-minus\"></i>
          </button>
          <button class=\"mjm-image-gallery__btn mjm-image-gallery__btn--reset\" title=\"Reset zoom\">
            <i class=\"fas fa-expand-arrows-alt\"></i>
          </button>
        </div>
      ";
        }
        // line 52
        yield "
      <div class=\"mjm-image-gallery__actions\">
        ";
        // line 54
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "auto_play", [], "any", false, false, true, 54)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 55
            yield "          <button class=\"mjm-image-gallery__btn mjm-image-gallery__btn--play\" title=\"Play/Pause slideshow\">
            <i class=\"fas fa-play\"></i>
          </button>
        ";
        }
        // line 59
        yield "        <button class=\"mjm-image-gallery__btn mjm-image-gallery__btn--fullscreen\" title=\"Open in new tab\">
          <i class=\"fas fa-external-link-alt\"></i>
        </button>
      </div>
    </div>

    ";
        // line 66
        yield "    <div class=\"mjm-image-gallery__counter\">
      <span class=\"mjm-image-gallery__current\">1</span> / <span class=\"mjm-image-gallery__total\">";
        // line 67
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["images"] ?? null)), "html", null, true);
        yield "</span>
    </div>

    ";
        // line 71
        yield "    ";
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["config"] ?? null), "show_thumbnails", [], "any", false, false, true, 71) && (Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["images"] ?? null)) > 1))) {
            // line 72
            yield "      <div class=\"mjm-image-gallery__thumbnails\">
        ";
            // line 73
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
                // line 74
                yield "          <button class=\"mjm-image-gallery__thumbnail";
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "first", [], "any", false, false, true, 74)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    yield " active";
                }
                yield "\" 
                  data-image=\"";
                // line 75
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, true, 75), "html", null, true);
                yield "\" 
                  title=\"";
                // line 76
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "title", [], "any", false, false, true, 76), "html", null, true);
                yield "\">
            <img src=\"";
                // line 77
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "url", [], "any", false, false, true, 77), "html", null, true);
                yield "\" alt=\"";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "title", [], "any", false, false, true, 77), "html", null, true);
                yield "\" loading=\"lazy\">
            ";
                // line 78
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["image"], "title", [], "any", false, false, true, 78)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 79
                    yield "              <span class=\"mjm-image-gallery__thumbnail-title\">";
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["image"], "title", [], "any", false, false, true, 79), "html", null, true);
                    yield "</span>
            ";
                }
                // line 81
                yield "          </button>
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
            // line 83
            yield "      </div>
    ";
        }
        // line 85
        yield "
    ";
        // line 87
        yield "    <div class=\"mjm-image-gallery__info-panel\">
      <button class=\"mjm-image-gallery__info-close\">&times;</button>
      <div class=\"mjm-image-gallery__info-content\">
        <h4 class=\"mjm-image-gallery__info-title\"></h4>
        <p class=\"mjm-image-gallery__info-description\"></p>
      </div>
    </div>
  </div>
</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["gallery_id", "config", "images", "loop"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/custom/mjm_components/templates/mjm-image-gallery.html.twig";
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
        return array (  207 => 87,  204 => 85,  200 => 83,  185 => 81,  179 => 79,  177 => 78,  171 => 77,  167 => 76,  163 => 75,  156 => 74,  139 => 73,  136 => 72,  133 => 71,  127 => 67,  124 => 66,  116 => 59,  110 => 55,  108 => 54,  104 => 52,  90 => 40,  88 => 39,  76 => 29,  70 => 26,  63 => 20,  60 => 18,  54 => 16,  52 => 15,  47 => 13,  44 => 12,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/custom/mjm_components/templates/mjm-image-gallery.html.twig", "/var/www/html/web/modules/custom/mjm_components/templates/mjm-image-gallery.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 15, "for" => 73];
        static $filters = ["escape" => 13, "length" => 67];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
                ['escape', 'length'],
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

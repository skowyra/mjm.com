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

/* modules/custom/mjm_components/templates/mjm-virtual-tour.html.twig */
class __TwigTemplate_d27bbb7e8231404ed5853b5ee9936740 extends Template
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
        // line 17
        yield "
<div class=\"mjm-virtual-tour\" id=\"";
        // line 18
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["tour_id"] ?? null), "html", null, true);
        yield "\">
  ";
        // line 19
        if ((($tmp = ($context["title"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 20
            yield "    <h3 class=\"mjm-virtual-tour__title\">";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title"] ?? null), "html", null, true);
            yield "</h3>
  ";
        }
        // line 22
        yield "  
  ";
        // line 23
        if ((($tmp = ($context["description"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 24
            yield "    <div class=\"mjm-virtual-tour__description\">";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["description"] ?? null), "html", null, true);
            yield "</div>
  ";
        }
        // line 26
        yield "  
  <div class=\"mjm-virtual-tour__container\" style=\"width: ";
        // line 27
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["width"] ?? null), "html", null, true);
        yield "; height: ";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["height"] ?? null), "html", null, true);
        yield ";\">
    <div class=\"mjm-virtual-tour__viewer\" id=\"";
        // line 28
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["tour_id"] ?? null), "html", null, true);
        yield "-viewer\"></div>
    
    ";
        // line 30
        if ((($tmp = ($context["show_controls"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 31
            yield "      <div class=\"mjm-virtual-tour__controls\">
        <div class=\"mjm-virtual-tour__scenes\">
          ";
            // line 33
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["scenes"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["scene"]) {
                // line 34
                yield "            <button class=\"mjm-virtual-tour__scene-btn\" data-scene=\"";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["scene"], "id", [], "any", false, false, true, 34), "html", null, true);
                yield "\">
              ";
                // line 35
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["scene"], "name", [], "any", false, false, true, 35), "html", null, true);
                yield "
            </button>
          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['scene'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 38
            yield "        </div>
        
        <div class=\"mjm-virtual-tour__buttons\">
          <button class=\"mjm-virtual-tour__btn mjm-virtual-tour__btn--zoom-in\" title=\"Zoom In\">
            <i class=\"fas fa-search-plus\"></i>
          </button>
          <button class=\"mjm-virtual-tour__btn mjm-virtual-tour__btn--zoom-out\" title=\"Zoom Out\">
            <i class=\"fas fa-search-minus\"></i>
          </button>
          <button class=\"mjm-virtual-tour__btn mjm-virtual-tour__btn--fullscreen\" title=\"Fullscreen\">
            <i class=\"fas fa-expand\"></i>
          </button>
          <button class=\"mjm-virtual-tour__btn mjm-virtual-tour__btn--auto-rotate\" title=\"Auto Rotate\">
            <i class=\"fas fa-sync-alt\"></i>
          </button>
        </div>
      </div>
    ";
        }
        // line 56
        yield "    
    <div class=\"mjm-virtual-tour__loading\">
      <div class=\"mjm-virtual-tour__spinner\"></div>
      <p>Loading virtual tour...</p>
    </div>
  </div>
</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["tour_id", "title", "description", "width", "height", "show_controls", "scenes"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/custom/mjm_components/templates/mjm-virtual-tour.html.twig";
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
        return array (  128 => 56,  108 => 38,  99 => 35,  94 => 34,  90 => 33,  86 => 31,  84 => 30,  79 => 28,  73 => 27,  70 => 26,  64 => 24,  62 => 23,  59 => 22,  53 => 20,  51 => 19,  47 => 18,  44 => 17,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/custom/mjm_components/templates/mjm-virtual-tour.html.twig", "/var/www/html/web/modules/custom/mjm_components/templates/mjm-virtual-tour.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 19, "for" => 33];
        static $filters = ["escape" => 18];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
                ['escape'],
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

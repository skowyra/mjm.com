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

/* modules/custom/mjm_components/templates/mjm-card-block.html.twig */
class __TwigTemplate_d1d01fcecb41484746fcd4b6864bae3f extends Template
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
        // line 14
        yield "
<div class=\"mjm-card mjm-card--";
        // line 15
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["card_style"] ?? null), "html", null, true);
        yield "\">
  ";
        // line 16
        if ((($tmp = ($context["image"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 17
            yield "    <div class=\"mjm-card__image\">
      ";
            // line 18
            if ((($tmp = ($context["link"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 19
                yield "        <a href=\"";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["link"] ?? null), "toString", [], "method", false, false, true, 19), "html", null, true);
                yield "\" class=\"mjm-card__image-link\">
          ";
                // line 20
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["image"] ?? null), "html", null, true);
                yield "
        </a>
      ";
            } else {
                // line 23
                yield "        ";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["image"] ?? null), "html", null, true);
                yield "
      ";
            }
            // line 25
            yield "    </div>
  ";
        }
        // line 27
        yield "
  <div class=\"mjm-card__content\">
    ";
        // line 29
        if ((($tmp = ($context["title"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 30
            yield "      <h3 class=\"mjm-card__title\">
        ";
            // line 31
            if ((($tmp = ($context["link"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 32
                yield "          <a href=\"";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["link"] ?? null), "toString", [], "method", false, false, true, 32), "html", null, true);
                yield "\" class=\"mjm-card__title-link\">";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title"] ?? null), "html", null, true);
                yield "</a>
        ";
            } else {
                // line 34
                yield "          ";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title"] ?? null), "html", null, true);
                yield "
        ";
            }
            // line 36
            yield "      </h3>
    ";
        }
        // line 38
        yield "
    ";
        // line 39
        if ((($tmp = ($context["description"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 40
            yield "      <div class=\"mjm-card__description\">
        ";
            // line 41
            yield Twig\Extension\CoreExtension::nl2br($this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["description"] ?? null), "html", null, true));
            yield "
      </div>
    ";
        }
        // line 44
        yield "
    ";
        // line 45
        if ((($tmp = ($context["link"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 46
            yield "      <div class=\"mjm-card__action\">
        <a href=\"";
            // line 47
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["link"] ?? null), "toString", [], "method", false, false, true, 47), "html", null, true);
            yield "\" class=\"mjm-card__link btn btn--primary\">
          Learn More
        </a>
      </div>
    ";
        }
        // line 52
        yield "  </div>
</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["card_style", "image", "link", "title", "description"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/custom/mjm_components/templates/mjm-card-block.html.twig";
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
        return array (  138 => 52,  130 => 47,  127 => 46,  125 => 45,  122 => 44,  116 => 41,  113 => 40,  111 => 39,  108 => 38,  104 => 36,  98 => 34,  90 => 32,  88 => 31,  85 => 30,  83 => 29,  79 => 27,  75 => 25,  69 => 23,  63 => 20,  58 => 19,  56 => 18,  53 => 17,  51 => 16,  47 => 15,  44 => 14,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/custom/mjm_components/templates/mjm-card-block.html.twig", "/var/www/html/web/modules/custom/mjm_components/templates/mjm-card-block.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 16];
        static $filters = ["escape" => 15, "nl2br" => 41];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape', 'nl2br'],
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

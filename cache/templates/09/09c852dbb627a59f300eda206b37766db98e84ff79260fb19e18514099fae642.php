<?php

/* verifyemail.html */
class __TwigTemplate_60515cf496217db9124c17852f93f7eabec13fa0f9ad34a17caa590d5b0d1bd8 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Roboto\" />
<link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css\" integrity=\"sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO\" crossorigin=\"anonymous\">

<style>
body {
  font-family: \"Roboto\", sans-serif;
  text-align: center;
}
</style>

<h2>Welcome, ";
        // line 11
        echo twig_escape_filter($this->env, ($context["username"] ?? null), "html", null, true);
        echo "</h2>
<p>Please verify your email by clicking this link: <a href=\"https://yasfu.net/blogs/verify/";
        // line 12
        echo twig_escape_filter($this->env, ($context["vkey"] ?? null), "html", null, true);
        echo "\">Verify</a></p>
<p>If that link does not work please copy and paste this into your browser: https://yasfu.net/blogs/verify/";
        // line 13
        echo twig_escape_filter($this->env, ($context["vkey"] ?? null), "html", null, true);
        echo "</p>
";
    }

    public function getTemplateName()
    {
        return "verifyemail.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  43 => 13,  39 => 12,  35 => 11,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "verifyemail.html", "/usr/www/yasfu/yasfu.net/blogs/templates/verifyemail.html");
    }
}

<?php

/* header.html */
class __TwigTemplate_6d055f93ddbf236d5fc5224423931b721093bf1b42aa74112d75d4914c136409 extends Twig_Template
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
        echo "<!DOCTYPE html>
<html>
  <head>
    <title>";
        // line 4
        echo twig_escape_filter($this->env, ($context["pagetitle"] ?? null), "html", null, true);
        echo " Yasfusys</title>

    <link rel=\"stylesheet\" type=\"text/css\" href=\"/css/global.css\" />
    <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Roboto\" />
    <link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css\" integrity=\"sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO\" crossorigin=\"anonymous\">

    <meta charset=\"utf-8\">
    <meta name=\"description\" content=\"Blog system\">
    <meta name=\"author\" content=\"Ethan Jones\">
    <meta name=\"keywords\" content=\"blog,warren,tech,warrentech,wt,game,dev,development,programming\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
  </head>
  <body>
";
    }

    public function getTemplateName()
    {
        return "header.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  28 => 4,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "header.html", "/var/www/yasfu.net/public_html/blogs/templates/header.html");
    }
}

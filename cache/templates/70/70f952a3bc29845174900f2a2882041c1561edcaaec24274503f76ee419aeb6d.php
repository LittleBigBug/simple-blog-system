<?php

/* login.html */
class __TwigTemplate_2047a62826a60751b5efa2383338b4176e5f67940f1e976b6928f44217fb9ffa extends Twig_Template
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
        echo "<div class=\"container\">
  <div class=\"d-flex justify-content-center\">
    <form action=\"login/\" method=\"POST\">
      <h3>Login</h3>
      <p>Need an account? <a href=\"register/\">Register here!</a></p>

      <p class=\"text-error\">
        ";
        // line 8
        echo twig_escape_filter($this->env, ($context["error"] ?? null), "html", null, true);
        echo "
      </p>

      <div class=\"form-group\">
        <label for=\"exampleInputEmail1\">Username or Email</label>
        <input type=\"login\" class=\"form-control\" id=\"email\" aria-describedby=\"emailHelp\" placeholder=\"Email or Username\" value=\"";
        // line 13
        echo twig_escape_filter($this->env, ($context["userem"] ?? null), "html", null, true);
        echo "\">
      </div>
      <div class=\"form-group\">
        <label for=\"exampleInputPassword1\">Password</label>
        <input type=\"password\" class=\"form-control\" id=\"password\" placeholder=\"Password\">
      </div>
      <div class=\"form-check\">
        <input type=\"checkbox\" class=\"form-check-input\" id=\"exampleCheck1\">
        <label class=\"form-check-label\" for=\"remember\">Remember Me</label>
      </div>

      <p class=\"text-error\">
        ";
        // line 25
        echo twig_escape_filter($this->env, ($context["error"] ?? null), "html", null, true);
        echo "
      </p>

      <button type=\"submit\" class=\"btn btn-primary\">Login</button>
    </form>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "login.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  55 => 25,  40 => 13,  32 => 8,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "login.html", "/var/www/yasfu.net/public_html/blogs/templates/login.html");
    }
}

<?php

/* register.html */
class __TwigTemplate_6c2a752e9a98b0d938d55a6861c307db9809f7ab14a9361145747fbca05d6145 extends Twig_Template
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
    <form action=\"register/\" method=\"POST\">
      <h3>Register</h3>
      <p>Already have an account? <a href=\"login/\">Login here!</a></p>

      <p class=\"text-error\">
        ";
        // line 8
        echo twig_escape_filter($this->env, ($context["error"] ?? null), "html", null, true);
        echo "
      </p>

      <div class=\"form-group\">
        <label for=\"exampleInputEmail1\">Username</label>
        <input type=\"login\" class=\"form-control\" id=\"username\" aria-describedby=\"usernameHelp\" placeholder=\"Username\" value=\"";
        // line 13
        echo twig_escape_filter($this->env, ($context["username"] ?? null), "html", null, true);
        echo "\">
      </div>
      <div class=\"form-group\">
        <label for=\"exampleInputEmail1\">Email Address</label>
        <input type=\"login\" class=\"form-control\" id=\"email\" aria-describedby=\"emailHelp\" placeholder=\"Email Address\" value=\"";
        // line 17
        echo twig_escape_filter($this->env, ($context["email"] ?? null), "html", null, true);
        echo "\">
      </div>
      <div class=\"form-group\">
        <label for=\"exampleInputPassword1\">Password</label>
        <input type=\"password\" class=\"form-control\" id=\"password\" placeholder=\"Password\">
      </div>
      <div class=\"form-group\">
        <label for=\"exampleInputPassword1\">Confirm Password</label>
        <input type=\"cpassword\" class=\"form-control\" id=\"cpassword\" placeholder=\"Confirm Password\">
      </div>

      <button type=\"submit\" class=\"btn btn-primary\">Register</button>
    </form>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "register.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 17,  40 => 13,  32 => 8,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "register.html", "/var/www/yasfu.net/public_html/blogs/templates/register.html");
    }
}

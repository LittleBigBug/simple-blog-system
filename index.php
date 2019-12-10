<?php

session_start();

define("IN_APP", true);
define("APP_ROOT", "/blogs");

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

require(__DIR__ . "/vendor/autoload.php");
require("inc/dbsetup.php");
require("inc/ranks.php");
require("inc/util.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$r = new \Bramus\Router\Router();

function isLoggedIn() {
  return isset($_SESSION["user"]);
}

function renderPage($title, $page, $arr = array(), $haf = true) {
  $loader = new Twig_Loader_Filesystem('templates/');
  $tw = new Twig_Environment($loader);
  $resa = array_merge($arr, array(
    "pagetitle" => $title, 
    "app_root" => APP_ROOT, 
    "time" => time(), 
    "loggedin" => isLoggedIn(),
    "current_user" => $_SESSION["user"],
    "current_user_rank" => $_SESSION["rank"],
  ));

  if ($haf) {
    echo $tw->render("header.html", $resa);
  }

  echo $tw->render($page . ".html", $resa);

  if ($haf) {
    echo $tw->render("footer.html", $resa);
  }
}

function p404() {
  renderPage("404", "404");
}

$r->get("/", function() {
  header("Location: " . APP_ROOT . "/feed?notloggedin");
});

$r->get("/logout", function() {
  $_SESSION = array();
  session_destroy();
  header("Location: " . APP_ROOT . "/?frd");
});

$r->get("/login", function() {
  if (isLoggedIn()) { header("Location: " . APP_ROOT); }
  renderPage("Login", "login");
});

$r->post("/login", function() {
  if (isLoggedIn()) { header("Location: " . APP_ROOT); }

  $userem = $password = $error = "";

  $userem = trim($_POST["userem"]);
  $password = trim($_POST["password"]);

  if (empty($userem) && empty($error)) {
    $error = "Please enter your Username or Email.";
  }

  if (empty($password) && empty($error)) {
    $error = "Please enter your password.";
  }

  if (empty($error)) {
    $urow = DB::queryFirstRow("SELECT * FROM yasfu_users WHERE username=%s OR email=%s", $userem, $userem);

    if ($urow) {
      if (password_verify($password, $urow["password"])) {
        $_SESSION["user"] = $urow["ID"];
        $_SESSION["username"] = $urow["username"];
        $_SESSION["rank"] = $urow["rank"];

        header("Location: " . APP_ROOT . "/?frl");
      } else {
        $error = "Username and Password do not match.";
      }
    } else {
      $error = "No account with that Username or Email exists!";
    }
  }

  renderPage("Login", "login", array(
    "userem" => $userem,
    "error" => $error
  ));
});

$r->get("/register", function() {
  if (isLoggedIn()) { header("Location: " . APP_ROOT); }
  renderPage("Register", "register");
});

$r->post("/register", function() {
  if (isLoggedIn()) { header("Location: " . APP_ROOT); }

  $username = $email = $password = $cpassword = $error = "";

  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $cpassword = trim($_POST["cpassword"]);

  if (empty($username) && empty($error)) {
    $error = "Please enter a Username.";
  }

  $uc = DB::queryFirstField("SELECT COUNT(*) FROM yasfu_users WHERE username=%s", $username);
  $ucv = DB::queryFirstField("SELECT COUNT(*) FROM yasfu_unverified_users WHERE username=%s", $username);
  if (($uc > 0 || $ucv > 0) && empty($error)) {
    $error = "That username is already in use!";

    if ($ecv > 0) {
      $error = $error . " This username is being used by an unverified account. Please check your email and confirm your existing account. The unverified record will be deleted in 24hrs or less.";
    }
  }

  if (strlen($username) < 3 && empty($error)) {
    $error = "That username is too short. (Must not be less than 2 characters).";
  }

  if (empty($email) && empty($error)) {
    $error = "Please enter an Email.";
  }

  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)  && empty($error)) {
    $error = "Please enter a valid email address";
  }

  $ec = DB::queryFirstField("SELECT COUNT(*) FROM yasfu_users WHERE email=%s", $email);
  $ecv = DB::queryFirstField("SELECT COUNT(*) FROM yasfu_unverified_users WHERE email=%s", $email);
  if (($uc > 0 || $ucv > 0) && empty($error)) {
    $error = "That email is already in use!";

    if ($ecv > 0) {
      $error = $error . " This email is being used by an unverified account. Please check your email and confirm your existing account. The unverified record will be deleted in 24hrs or less.";
    }
  }

  if (empty($password) && empty($error)) {
    $error = "Please enter a Password.";
  }

  if (empty($cpassword) && empty($error)) {
    $error = "Please confirm your Password.";
  }

  if ($cpassword !== $password && empty($error)) {
    $error = "Your passwords don't match!";
  }

  if (empty($error)) {
    $hpw = password_hash($password, PASSWORD_DEFAULT);
    $vkey = md5($email . $username);

    DB::insert("yasfu_unverified_users", array(
      "username" => $username,
      "email" => $email,
      "password" => $hpw,
      "vkey" => $vkey
    ));

    $m = new PHPMailer(true);
    try {
      $m->setFrom("noreply@yasfu.net");
      $m->addAddress($email, $username);
      $m->addReplyTo("admin@yasfu.net", "Administrator");

      $loader = new Twig_Loader_Filesystem('templates/');
      $tw = new Twig_Environment($loader, array(
          'cache' => 'cache/templates',
      ));

      $m->Subject = "Verify your account";
      $m->Body = $tw->render("verifyemail.html", array(
        "vkey" => $vkey,
        "username" => $username
      ), false);
      $m->AltBody = "Verify your yasfu account: https://yasfu.net/blogs/verify/" . $vkey;

      $m->send();
      header("Location: " . APP_ROOT . "/verify/sent");
    } catch (Exception $e) {
      $error = "There was an issue sending an email. Please email admin@yasfu.net with information.";
    }
  }

  renderPage("Register", "register", array(
    "username" => $username,
    "email" => $email,
    "error" => $error
  ));
});

$r->get("/verify/(\w+)", function($vkey) {
  if (!$vkey) {
    header("Location: " . APP_ROOT);
    return;
  }

  if ($vkey == "sent") {
    renderPage("Verification Email Sent", "emailsent");
    return;
  }

  $uvrow = DB::queryFirstRow("SELECT * FROM yasfu_unverified_users WHERE vkey=%s LIMIT 1", $vkey);

  if ($uvrow) {
    DB::insert("yasfu_users", array(
      "username" => $uvrow["username"],
      "email" => $uvrow["email"],
      "password" => $uvrow["password"],
      "joined" => $uvrow["joined"],
    ));

    DB::delete("yasfu_unverified_users", "vkey=%s", $vkey);
    renderPage("Account Verified", "verified", array(
      "username" => $uvrow["username"],
      "success" => true,
    ));
    return;
  }

  renderPage("Account Verified", "verified", array(
    "username" => "",
    "success" => false,
  ));
});

$r->mount("/feed", function() use($r) {

  $r->get("/", function() {
    header("Location: " . APP_ROOT . "/feed/1");
  });

  $r->get("/(\w+)?", function($page) {
	if (!isLoggedIn()) {
		renderPage("Yasfusys Blogs", "land", array(), false);
	}

    $ppp = 5;

    $offset = ($page - 1) * $ppp;
    $bposts = DB::query("SELECT * FROM yasfu_posts ORDER BY time DESC LIMIT %i, %i", $offset, $ppp);
    $numposts = DB::queryFirstField("SELECT COUNT(*) FROM yasfu_posts");

    foreach ($bposts as $key => $post) {
      $author = DB::queryFirstRow("SELECT * FROM yasfu_users WHERE ID=%i", $post["posterID"]);

      $rank = $author["rank"];
      $rarr = array(
        ["rankID"] => $rank,
        ["rank"] => $ranks_str[$rank],
        ["rankCol"] => $ranks_col[$rank],
      );
      $author["rank"] = $rarr;

      $bposts[$key]["author"] = $author;

      $cont = substr(strip_tags($post["content"]), 0, 400) . "...";
      $bposts[$key]["contentcut"] = $cont;

      $bposts[$key]["timeago"] = time_elapsed_string($post["time"]);
    }

    renderPage("Feed", "feed", array(
      "bposts" => $bposts,
      "numposts" => $numposts,
    ));
  });

});

$r->mount("/post", function() use ($r) {

  $r->get("/", function() {
    header("Location: " . APP_ROOT);
    die();
  });

  $r->post("/new", function() {
    if (!isLoggedIn()) {
      header("Location: " . APP_ROOT);
      die();
    }

    // Probably bad
    $cont = $_POST["cont"];
    $cont = str_replace("/<script(.*?)?\>(.|\s)*?\<\/script\>/im", "<!--DELETED SCRIPT-->", $cont);
    $cont = str_replace("/<style(.*?)?\>(.|\s)*?\<\/style\>/im", "<!--DELETED STYLE-->", $cont);
    $cont = str_replace("/<link(.*?)?\>/im", "<!--DELETED STYLE-->", $cont);

    $title = trim($_POST["title"]);

    if (isset($_SESSION["rank"]) && intval($_SESSION["rank"]) > intval(RANK_BLOGGER)) {
      if (!empty($title) && strlen($title) > 3 && strlen($title < 25)) {
        $lastpost = DB::queryFirstField("SELECT lastpost FROM yasfu_users WHERE ID=%i", $_SESSION["user"]);

        $ult = 0;

        if ($lastpost) {
          $ult = strtotime($lastpost);
        }

        if (($ult + 300) < time()) {
          DB::insert("yasfu_posts", array(
            "posterID" => $_SESSION["user"],
            "content" => $cont,
            "title" => $title,
          ));

          $id = DB::insertID();

          header("Location: " . APP_ROOT . "/post/view/" . $id . "/?new");
        } else {
          $error = "Please wait at least 5 minutes before making another blog post.";
        }
      } else {
        $error = "You must have a title that is between 4 and 24 characters.";
      }
    } else {
      $error = "You aren't authorized to make blog posts at this time.";
    }

    renderPage("Create New Post", "editpost", array(
      "newpost" => true,
      "error" => $error,
      "blogcontent" => $cont,
      "ptitle" => $title,
    ));
  });

  $r->get("/new", function() {
    if (!isLoggedIn() || !(isset($_SESSION["rank"]) && intval($_SESSION["rank"]) > intval(RANK_BLOGGER))) {
      header("Location: " . APP_ROOT);
      die();
    }

    renderPage("Create New Post", "editpost", array(
      "newpost" => true,
    ));
  });

  $r->mount("/view", function() use ($r) {

    $r->get("/(\w+)", function($postid) {
      $post = DB::queryFirstRow("SELECT * FROM yasfu_posts WHERE ID=%i", $postid);

      if ($post) {
        $author = DB::queryFirstRow("SELECT * FROM yasfu_users WHERE ID=%i", $post["posterID"]);

        renderPage($post["title"], "post", array(
          "post" => $post,
          "author" => $author,
          "date" => $post["time"],
        ));
      } else {
        p404();
      }
    });

    $r->get("/(\w+)/edit", function($postid) {
      if (!isLoggedIn()) {
        header("Location: " . APP_ROOT);
        die();
      }

      $exPost = DB::queryFirstRow("SELECT * FROM yasfu_posts WHERE posterID=%i AND ID=%i", $_SESSION["user"], $postid);

      // does not own the post
      if ($_SESSION["user"] != $exPost["posterID"] && $_SESSION["rank"] != 2) {
        header("Location: " . APP_ROOT);
        die();
      }

      $exPost = DB::queryFirstRow("SELECT * FROM yasfu_posts WHERE ID=%i", $postid);

      renderPage("Edit Post", "editpost", array(
        "newpost" => false,
        "blogcontent" => $exPost["content"],
        "ptitle" => $exPost["title"],
        "postlocation" => "view/" . $postid . "/edit",
      ));
    });

    $r->post("/(\w+)/edit", function($postid) {
      if (!isLoggedIn()) {
        header("Location: " . APP_ROOT);
        die();
      }

      $exPost = DB::queryFirstRow("SELECT * FROM yasfu_posts WHERE posterID=%i AND ID=%i", $_SESSION["user"], $postid);

      // does not own the post
      if ($_SESSION["user"] != $exPost["posterID"] && $_SESSION["rank"] != 2) {
        header("Location: " . APP_ROOT);
        die();
      }

      // Probably bad
      $cont = $_POST["cont"];
      
      $cont = str_replace("/<script(.*?)?\>(.|\s)*?\<\/script\>/im", "<!--DELETED SCRIPT-->", $cont);
      $cont = str_replace("/<style(.*?)?\>(.|\s)*?\<\/style\>/im", "<!--DELETED STYLE-->", $cont);
      $cont = str_replace("/<link(.*?)?\>/im", "<!--DELETED STYLE-->", $cont);

      $title = trim($_POST["title"]);

      if (isset($_SESSION["rank"]) && intval($_SESSION["rank"]) > intval(RANK_BLOGGER)) {
        if (!empty($title) && strlen($title) > 3 && strlen($title < 25)) {
        $lastpost = DB::queryFirstField("SELECT lastpost FROM yasfu_users WHERE ID=%i", $_SESSION["user"]);

        $ult = 0;

        if ($lastpost) {
          $ult = strtotime($lastpost);
        }

        if (($ult + 20) < time()) {
          DB::update("yasfu_posts", array(
            "content" => $cont,
            "title" => $title,
          ), "ID = %i", $postid);

          header("Location: " . APP_ROOT . "/post/view/" . $postid);
        } else {
          $error = "Please wait at least 20 seconds before editing a blog post.";
        }
        } else {
        $error = "You must have a title that is between 4 and 24 characters.";
        }
      } else {
        $error = "You aren't authorized to make/edit blog posts at this time.";
      }

      renderPage("Edit Post", "editpost", array(
        "newpost" => false,
        "error" => $error,
        "blogcontent" => $cont,
        "ptitle" => $title,
        "postlocation" => "view/" . $postid . "/edit",
      ));
    });

    $r->get("/(\w+)/delete", function($postid) {
      if (!isLoggedIn()) {
        header("Location: " . APP_ROOT);
        die();
      }
  
      $exPost = DB::queryFirstRow("SELECT * FROM yasfu_posts WHERE posterID=%i AND ID=%i", $_SESSION["user"], $postid);
  
      // does not own the post
      if ($_SESSION["user"] != $exPost["posterID"] && $_SESSION["rank"] != 2) {
        header("Location: " . APP_ROOT);
        die();
      }

      renderPage("Edit Post", "deletepost", array(
        "ptitle" => $exPost["title"],
      ));
    });

    $r->post("/(\w+)/delete", function($postid) {
      if (!isLoggedIn()) {
        header("Location: " . APP_ROOT);
        die();
      }
  
      $exPost = DB::queryFirstRow("SELECT * FROM yasfu_posts WHERE posterID=%i AND ID=%i", $_SESSION["user"], $postid);
  
      // does not own the post
      if ($_SESSION["user"] != $exPost["posterID"] && $_SESSION["rank"] != 2) {
        header("Location: " . APP_ROOT);
        die();
      }
  
      DB::delete("yasfu_posts", "posterID = %i AND ID = %i", $_SESSION["user"], $postid);
      header("Location: " . APP_ROOT . "/user/" . $_SESSION["user"]);
    });

  });

});

$r->get("/members", function() {
	$bmembers = DB::query("SELECT * FROM yasfu_users");
	renderPage("Members", "members", array(
		"bmembers" => $bmembers
	));
});

$r->mount("/user", function() use ($r) {

  $r->get("/", function() {
    header("Location: " . APP_ROOT);
    die();
  });

  $r->get("/(\w+)", function($userid) {
    if ($userid == "me") {
      if (isLoggedIn()) {
        $userid = $_SESSION["user"];
      } else {
        header("Location: " . APP_ROOT);
        die();
      }
    }

    $user = DB::queryFirstRow("SELECT * FROM yasfu_users WHERE ID=%i", $userid);

    if ($user) {
      //require(APP_ROOT . "/inc/ranks.php");
      $rank = intval($user["rank"]);
      $rarr = array(
        ["rankID"] => $rank,
        ["rank"] => $ranks_str[$rank],
        ["rankCol"] => $ranks_col[$rank],
      );

      $user["rank"] = $rarr;

	  $posts = DB::query("SELECT * FROM yasfu_posts WHERE posterID=%i ORDER BY time DESC", $userid);

	  $user["posts"] = $posts;

      renderPage($user["username"] . "'s profile", "user", array(
        "user" => $user,
      ));
    } else {
      p404();
    }
  });

});

$r->mount("/api", function() use ($r) {

  $r->get("/", function() {
    die("Yasfusys blogs api");
  });

  $r->get("/userposts/(\w+)/(\w+)/(\w+)", function($userid, $limit, $page) {
    $posts = DB::query("SELECT * FROM yasfu_posts WHERE posterID=%i LIMIT %i OFFSET %i", $userid, $limit, (($page - 1) * $limit));
    echo(json_encode($posts, JSON_PRETTY_PRINT));
  });

});

$r->mount("/speedrun", function() use ($r) {

  $r->get("/", function() {
    $bgames = DB::query("SELECT * FROM yasfu_games");

    foreach ($bgames as $key => $game) {
      $auth = $game["authors"];
      $authors = explode(";", $auth);
      $bgames[$key]["authors"] = $authors;
    }

    renderPage("Speedrun Games", "speedrun/index", array(
      "games" => $bgames,
    ));
  });

  $r->get("/game/(\w+)", function($gid) {
    $game = DB::queryFirstField("SELECT COUNT(*) FROM yasfu_games WHERE id=%i", $gid);

    if ($game > 0) {
      $runs = DB::query("SELECT * FROM yasfu_speedruns WHERE gameid=%i ORDER BY time ASC", $gid);
    } else {

    }
  });

});

$r->set404(function() {
  header("HTTP/1.1 404 Not Found");
  p404();
});

$r->run();

?>

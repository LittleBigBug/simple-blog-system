$(document).ready(function() {

  var replinks = $("a:regex(id, reply-(\\S+))");
  replinks.click(function() {
    $(".replybox").insertAfter($(this));
    $(".replybox.div.form.div.textarea").focus();

    replinks.each(function(e) {
      e.style.display = "block";
    });

    $(this).style.display = "none";
    $("#replytocomnt").val($(this).attr("id"));
  });

});

<?php
namespace Jmgraphic\Adrecord;

function clean_link_script($user_id)
{
  return '<script type="text/javascript">
  (function() {
    var cl = document.createElement("script");
    cl.type = "text/javascript";
    cl.async = true;
    cl.src =
      document.location.protocol +
      "//www.adrecord.com/cl.php?u=' . $user_id . '&ref=" +
      escape(document.location.href);
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(cl, s);
  })();
  </script>';
}
/*global $ */
$(window).bind("load", function () {
  "use strict";
  var e = $("#footer"), t = e.position(), n = $(window).height();
  n = n - t.top;
  n = n - e.height();
  if (n > 0) {
    e.css(
      {"margin-top" : n + "px"}
    );
  }
});
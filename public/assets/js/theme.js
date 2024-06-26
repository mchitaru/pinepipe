/*!
 * Pipeline Bootstrap Theme
 * Copyright 2018-2019 Medium Rare (undefined)
 */
! function(t, e) {
  "object" == typeof exports && "undefined" != typeof module ? e(exports, require("jquery"), require("autosize"), require("@shopify/draggable/lib/draggable"), require("@shopify/draggable/lib/plugins"), require("list.js"), require("flatpickr"), require("prismjs")) : "function" == typeof define && define.amd ? define(["exports", "jquery", "autosize", "@shopify/draggable/lib/draggable", "@shopify/draggable/lib/plugins", "list.js", "flatpickr", "prismjs"], e) : e((t = t || self).theme = {}, t.jQuery, t.autosize, t.Draggable, t.SwapAnimation, t.List, t.flatpickr, t.Prism)
}(this, function(t, e, n, a, r, p, o, i) {
  "use strict";
  e = e && e.hasOwnProperty("default") ? e.default : e, n = n && n.hasOwnProperty("default") ? n.default : n, a = a && a.hasOwnProperty("default") ? a.default : a, r = r && r.hasOwnProperty("default") ? r.default : r, p = p && p.hasOwnProperty("default") ? p.default : p, o = o && o.hasOwnProperty("default") ? o.default : o, i = i && i.hasOwnProperty("default") ? i.default : i;
  var s, l, h = (l = "script", (s = e)("body").tooltip({
      selector: '[data-toggle="tooltip"]',
      container: "body"
  }), s("body").popover({
      selector: '[data-toggle="popover"]',
      container: "body"
  }), s(".toast").toast(), {
      version: "1.2.0",
      selector: {
          RECAPTCHA: "[data-recaptcha]"
      },
      activateIframeSrc: function(t) {
          var e = s(t);
          e.attr("data-src") && e.attr("src", e.attr("data-src"))
      },
      idleIframeSrc: function(t) {
          var e = s(t);
          e.attr("data-src", e.attr("src")).attr("src", "")
      },
      forEach: function(t, e, n) {
          if (t)
              if (t.length)
                  for (var a = 0; a < t.length; a += 1) e.call(n, a, t[a]);
              else(t[0] || h.isElement(t)) && e.call(n, 0, t)
      },
      dedupArray: function(t) {
          return t.reduce(function(t, e) {
              var n = JSON.stringify(e);
              return -1 === t.temp.indexOf(n) && (t.out.push(e), t.temp.push(n)), t
          }, {
              temp: [],
              out: []
          }).out
      },
      isElement: function(t) {
          return !(!t || 1 !== t.nodeType)
      },
      getFuncFromString: function(t, e) {
          var n = t || null;
          if ("function" == typeof n) return t;
          if ("string" == typeof n) {
              if (!n.length) return null;
              for (var a = e || window, r = n.split("."); r.length;) {
                  var i = r.shift();
                  if (void 0 === a[i]) return null;
                  a = a[i]
              }
              if ("function" == typeof a) return a
          }
          return null
      },
      getScript: function(t, n) {
          var a = document.createElement(l),
              e = document.getElementsByTagName(l)[0];
          a.async = 1, a.defer = 1, a.onreadystatechange = function(t, e) {
              (e || !a.readyState || /loaded|complete/.test(a.readyState)) && (a.onload = null, a.onreadystatechange = null, a = void 0, !e && n && "function" == typeof n && n())
          }, a.onload = a.onreadystatechange, a.src = t, e.parentNode.insertBefore(a, e)
      }
  });
  n(document.querySelectorAll(".chat-module-bottom textarea")), e(window).on("load", function() {
      var t = document.querySelectorAll(".media.chat-item:last-child");
      t && h.forEach(t, function(t, e) {
          e.scrollIntoView()
      })
  });
  var d, c, u;
  d = e, c = function() {
      function r(t, e) {
          this.element = t;
          var n = window.getComputedStyle(this.element);
          this.elementCssText = "box-sizing:" + n.boxSizing + "\n                          ;border-left:" + n.borderLeftWidth + " solid red           \n                          ;border-right:" + n.borderRightWidth + " solid red\n                          ;font-family:" + n.fontFamily + "\n                          ;font-feature-settings:" + n.fontFeatureSettings + "\n                          ;font-kerning:" + n.fontKerning + "\n                          ;font-size:" + n.fontSize + "\n                          ;font-stretch:" + n.fontStretch + "\n                          ;font-style:" + n.fontStyle + "\n                          ;font-variant:" + n.fontVariant + "\n                          ;font-variant-caps:" + n.fontVariantCaps + "\n                          ;font-variant-ligatures:" + n.fontVariantLigatures + "\n                          ;font-variant-numeric:" + n.fontVariantNumeric + "\n                          ;font-weight:" + n.fontWeight + "\n                          ;letter-spacing:" + n.letterSpacing + "\n                          ;margin-left:" + n.marginLeft + "\n                          ;margin-right:" + n.marginRight + "\n                          ;padding-left:" + n.paddingLeft + "\n                          ;padding-right:" + n.paddingRight + "\n                          ;text-indent:" + n.textIndent + "\n                          ;text-transform:" + n.textTransform + ";", this.GHOST_ELEMENT_ID = "__autosizeInputGhost", t.addEventListener("input", r.passWidth), t.addEventListener("keydown", r.passWidth), t.addEventListener("cut", r.passWidth), t.addEventListener("paste", r.passWidth), this.extraPixels = e && e.extraPixels ? parseInt(e.extraPixels, 10) : 0, this.width = r.setWidth(this), e && e.minWidth && "0px" !== this.width && (this.element.style.minWidth = this.width)
      }
      return r.setWidth = function(t) {
          var e = t.element.value || t.element.getAttribute("placeholder") || "",
              n = document.getElementById(t.GHOST_ELEMENT_ID) || t.createGhostElement();
          n.style.cssText += t.elementCssText, n.innerHTML = r.escapeSpecialCharacters(e);
          var a = window.getComputedStyle(n).width;
          return a = Math.ceil(a.replace("px", "")) + t.extraPixels, t.element.style.width = a + "px", a
      }, r.passWidth = function(t) {
          var e = d(t.target).data("autoWidth");
          r.setWidth(e)
      }, r.mapSpecialCharacterToCharacterEntity = function(t) {
          return "&" + {
              " ": "nbsp",
              "<": "lt",
              ">": "gt"
          }[t] + ";"
      }, r.escapeSpecialCharacters = function(t) {
          return t.replace(/\s/g, "&nbsp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
      }, r.prototype.createGhostElement = function() {
          var t = document.createElement("div");
          return t.id = this.GHOST_ELEMENT_ID, t.style.cssText = "display:inline-block;height:0;overflow:hidden;position:absolute;top:0;visibility:hidden;white-space:nowrap;", document.body.appendChild(t), t
      }, r
  }(), d(document).ready(function() {
      var t = document.querySelectorAll("form.checklist .custom-checkbox div input");
      t && h.forEach(t, function(t, e) {
          d(e).data("autoWidth", new c(e, {
              extraPixels: 3
          })), e.addEventListener("keypress", function(t) {
              13 === t.which && t.preventDefault()
          })
      })
  }), new a.Sortable(document.querySelectorAll("form.checklist, .drop-to-delete"), {
      plugins: [r],
      draggable: ".checklist > .row",
      handle: ".form-group > span > i"
  });

  function f(t, e) {
      for (var n = 0; n < e.length; n++) {
          var a = e[n];
          a.enumerable = a.enumerable || !1, a.configurable = !0, "value" in a && (a.writable = !0), Object.defineProperty(t, a.key, a)
      }
  }

  function m(t, e, n) {
      return e && f(t.prototype, e), n && f(t, n), t
  }
  window.Dropzone.autoDiscover = !1, (u = e)(function() {
      var t = '<li class="list-group-item dz-preview dz-file-preview">\n      <div class="media align-items-center dz-details">\n        <ul class="avatars">\n          <li>\n            <div class="avatar bg-primary dz-file-representation">\n              <i class="material-icons">attach_file</i>\n            </div>\n          </li>\n        </ul>\n        <div class="media-body d-flex justify-content-between align-items-center">\n          <div class="dz-file-details">\n            <span class="dz-filename"><span data-dz-name></span></span<br>\n            <span class="text-small dz-size" data-dz-size></span>\n          </div>\n          <img alt="Loader" src="assets/img/loader.svg" class="dz-loading" />\n          <div class="dropdown">\n            <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\n              <i class="material-icons">more_vert</i>\n            </button>\n            <div class="dropdown-menu dropdown-menu-right">\n              <a class="dropdown-item text-danger" href="#" data-dz-remove>Delete</a>\n            </div>\n          </div>\n          <button class="btn btn-danger btn-sm dz-remove" data-dz-remove>\n            Cancel\n          </button>\n        </div>\n      </div>\n      <div class="progress dz-progress">\n        <div class="progress-bar dz-upload" data-dz-uploadprogress></div>\n      </div>\n    </li>';
      t = document.querySelector(".dz-template") ? document.querySelector(".dz-template").innerHTML : t, u(".dropzone").dropzone({
          previewTemplate: t,
          thumbnailWidth: 320,
          thumbnailHeight: 320,
          thumbnailMethod: "contain",
          previewsContainer: ".dropzone-previews",
      })
  });
  var g = function(s) {
          if (void 0 === p) throw new Error("mrFilterList requires list.js (http://listjs.com)");
          var t = "mrFilterList",
              a = "mr.filterList",
              e = s.fn[t],
              n = {
                  LOAD_DATA_API: "load.mr.filterList.data-api"
              },
              r = "[data-filter-list]",
              o = "filter-list",
              i = "filterList",
              l = "data-filter-by",
              d = "filterBy",
              c = "filter-list-input",
              u = "filter-by-text",
              f = function() {
                  function n(t) {
                      var e = (this.element = t).dataset[i];
                      this.valueNames = [], this.lists = [], this.initAllLists(e), this.bindInputEvents()
                  }
                  var t = n.prototype;
                  return t.initAllLists = function(t) {
                      var n = this;
                      h.forEach(this.element.querySelectorAll("." + t), function(t, e) {
                          n.initList(n.element, e)
                      })
                  }, t.initList = function(t, n) {
                      var a = this,
                          e = o + "-" + (new Date).getTime(),
                          r = n.querySelectorAll("*:first-child [" + l + "]");
                      h.forEach(r, function(t, e) {
                          a.parseFilters(n, e, e.dataset[d])
                      }), this.valueNames = h.dedupArray(this.valueNames), n.classList.add(e);
                      var i = new p(t, {
                          valueNames: this.valueNames,
                          listClass: e
                      });
                      this.lists.push(i)
                  }, t.parseFilters = function(t, e, n) {
                      var a = this,
                          r = s(t),
                          i = [];
                      try {
                          i = n.split(",")
                      } catch (t) {
                          throw new Error('Cannot read comma separated data-filter-by attribute: "\n          ' + n + '" on element: \n          ' + this.element)
                      }
                      i.forEach(function(t) {
                          "text" === t ? (e.className !== e.nodeName + "-" + u && a.valueNames.push(e.className + " " + e.nodeName + "-" + u), r.find(e.nodeName.toLowerCase() + "[" + l + '*="text"]').addClass(e.nodeName + "-" + u)) : 0 === t.indexOf("data-") ? (r.find("[" + l + '*="' + t + '"]').addClass("filter-by-" + t), a.valueNames.push({
                              name: "filter-by-" + t,
                              data: t.replace("data-", "")
                          })) : e.getAttribute(t) && (r.find("[" + l + '*="' + t + '"]').addClass("filter-by-" + t), a.valueNames.push({
                              name: "filter-by-" + t,
                              attr: t
                          }))
                      })
                  }, t.bindInputEvents = function() {
                      var t = this.element.querySelector("." + c);
                      s(t).data(a, this), t.addEventListener("keyup", this.searchLists, !1), t.addEventListener("paste", this.searchLists, !1), t.closest("form").addEventListener("submit", function(t) {
                          t.preventDefault
                      })
                  }, t.searchLists = function(n) {
                      var t = s(this).data(a);
                      h.forEach(t.lists, function(t, e) {
                          e.search(n.target.value)
                      })
                  }, n.jQueryInterface = function() {
                      return this.each(function() {
                          var t = s(this),
                              e = t.data(a);
                          e || (e = new n(this), t.data(a, e))
                      })
                  }, m(n, null, [{
                      key: "VERSION",
                      get: function() {
                          return "1.0.0"
                      }
                  }]), n
              }();
          return s(window).on(n.LOAD_DATA_API, function() {
              for (var t = s.makeArray(s(r)), e = t.length; e--;) {
                  var n = s(t[e]);
                  f.jQueryInterface.call(n, n.data())
              }
          }), s.fn[t] = f.jQueryInterface, s.fn[t].Constructor = f, s.fn[t].noConflict = function() {
              return s.fn[t] = e, f.jQueryInterface
          }, f
      }(e),
      v = function(a) {
          if (void 0 === o) throw new Error("mrFlatpickr requires flatpickr.js (https://github.com/flatpickr/flatpickr)");
          var t = "mrFlatpickr",
              r = "mr.flatpickr",
              e = a.fn[t],
              n = {
                  LOAD_DATA_API: "load.mr.flatpickr.data-api"
              },
              i = "[data-flatpickr]",
              s = function() {
                  function n(t) {
                      this.element = t, this.initflatpickr()
                  }
                  return n.prototype.initflatpickr = function() {
                      var t = a(this.element).data();
                      this.instance = o(this.element, t)
                  }, n.jQueryInterface = function() {
                      return this.each(function() {
                          var t = a(this),
                              e = t.data(r);
                          e || (e = new n(this), t.data(r, e))
                      })
                  }, m(n, null, [{
                      key: "VERSION",
                      get: function() {
                          return "1.0.0"
                      }
                  }]), n
              }();
          return a(window).on(n.LOAD_DATA_API, function() {
              for (var t = a.makeArray(a(i)), e = t.length; e--;) {
                  var n = a(t[e]);
                  s.jQueryInterface.call(n, n.data())
              }
          }), a.fn[t] = s.jQueryInterface, a.fn[t].Constructor = s, a.fn[t].noConflict = function() {
              return a.fn[t] = e, s.jQueryInterface
          }, s
      }(e),
      y = {
          sortableKanbanLists: new a.Sortable(document.querySelectorAll("div.kanban-board"), {
              draggable: ".kanban-col:not(:last-child)",
              handle: ".card-list-header"
          }),
          sortableKanbanCards: new a.Sortable(document.querySelectorAll(".kanban-col .card-list-body"), {
              plugins: [r],
              draggable: ".card-kanban",
              handle: ".card-kanban",
              appendTo: "body"
          })
      };
  i.highlightAll(),
      function() {
          if ("undefined" == typeof $) throw new TypeError("Medium Rare JavaScript requires jQuery. jQuery must be included before theme.js.")
      }(), t.mrFilterList = g, t.mrFlatpickr = v, t.mrKanban = y, t.mrUtil = h, Object.defineProperty(t, "__esModule", {
          value: !0
      })
});
//# sourceMappingURL=theme.js.map
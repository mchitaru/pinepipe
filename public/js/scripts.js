function PreviewAvatarImage(a,t,e){if(a.files&&a.files[0]){var r=new FileReader;r.onload=function(r){$(a).closest(".avatar-container").children(".avatar-preview").html('<img class="'+e+'" src="'+r.target.result+'" width="'+t+'" height="'+t+'"/>')},r.readAsDataURL(a.files[0])}}function updateFilters(a,t,e,r){var n=new URL(window.location.href);n.searchParams.has("sort")&&(a=n.searchParams.get("sort")),n.searchParams.has("dir")&&(t=n.searchParams.get("dir")),n.searchParams.has("filter")&&(e=n.searchParams.get("filter")),n.searchParams.has("tag")&&(r=n.searchParams.get("tag")),$(".filter-controls a").each(function(e){$(this).removeClass("asc desc"),$(this).data("sort")==a&&$(this).addClass(t)}),$(".filter-tags div").each(function(a){$(this).removeClass("active"),$(this).data("filter")==r&&$(this).addClass("active")}),$(".filter-input").each(function(a){$(this).val(e)})}$(document).on("ajax:success",function(a,t,e,r){r.responseText&&($("#modal").length||$("body").append($('<div class="modal show" id="modal"></div>')),$("#modal").html(r.responseText).modal("show"),$("select").select2(),$("[data-flatpickr]").mrFlatpickr(),LetterAvatar.transform())}),$(document).on("ajax:error",function(a,t,e,r){toastrs(r,"error")}),function(a,t){function e(e,r){e=e||"",r=r||60;var n,o,i,s,c,l=String(e).toUpperCase().split(" ");return n=1==l.length?l[0]?l[0].charAt(0):"?":l[0].charAt(0)+l[1].charAt(0),a.devicePixelRatio&&(r*=a.devicePixelRatio),o=(("?"==n?72:n.charCodeAt(0))-64)%20,(i=t.createElement("canvas")).width=r,i.height=r,(s=i.getContext("2d")).fillStyle=["#92dacb","#e7afa9","#acd6f1","#e4c695","#728191","#a3e4d7","#93d6af","#7fb2d4","#dab7e9","#7c9cbd","#dfce8c","#dfb999","#9fdfb9","#ecf0f1","#95a5a6","#dcb5eb","#e0b699","#e4a9a1","#bdc3c7","#90a0a1","#92dacb"][o],s.fillRect(0,0,i.width,i.height),s.font=Math.round(i.width/2)+"px Arial",s.textAlign="center",s.fillStyle="#FFF",s.fillText(n,r/2,r/1.5),c=i.toDataURL(),i=null,c}e.transform=function(){Array.prototype.forEach.call(t.querySelectorAll("img[avatar]"),function(a,t){t=a.getAttribute("avatar"),a.src=e(t,a.getAttribute("width")),a.removeAttribute("avatar"),a.setAttribute("alt",t)})},"function"==typeof define&&define.amd?define(function(){return e}):"undefined"!=typeof exports?("undefined"!=typeof module&&module.exports&&(exports=module.exports=e),exports.LetterAvatar=e):(window.LetterAvatar=e,t.addEventListener("DOMContentLoaded",function(a){e.transform()}))}(window,document),$(function(){$("body").on("click",".pagination a",function(a){a.preventDefault(),$(".paginate-container a").not(".pagination a").css("color","#dfecf6");var t=new URL(window.location.href);sort=t.searchParams.get("sort"),dir=t.searchParams.get("dir"),filter=t.searchParams.get("filter");var e=new URL($(this).attr("href"));sort&&(e.searchParams.set("sort",sort),e.searchParams.set("dir",dir)),filter&&e.searchParams.set("filter",filter),$.ajax({url:e.href}).done(function(a){$(".paginate-container").html(a),LetterAvatar.transform()}).fail(function(){toastrs("Data could not be loaded!","error")}),window.history.replaceState(null,null,e.href)}),$(".filter-controls a").on("click",function(a){a.preventDefault(),$(".paginate-container a").not(".pagination a").css("color","#dfecf6");var t=$(this).data("sort"),e=$(this).hasClass("asc")?"desc":"asc",r=new URL(window.location.href);r.searchParams.set("sort",t),r.searchParams.set("dir",e),$.ajax({url:r.href}).done(function(a){$(".paginate-container").html(a),LetterAvatar.transform()}).fail(function(){toastrs("Data could not be loaded!","error")}),window.history.replaceState(null,null,r.href),updateFilters()}),$(".filter-tags div").on("click",function(a){a.preventDefault(),$(".paginate-container a").not(".pagination a").css("color","#dfecf6");var t=$(this).data("filter"),e=new URL(window.location.href);e.searchParams.get("tag")==t?e.searchParams.delete("tag"):e.searchParams.set("tag",t),$.ajax({url:e.href}).done(function(a){$(".paginate-container").html(a),LetterAvatar.transform()}).fail(function(){toastrs("Data could not be loaded!","error")}),window.history.replaceState(null,null,e.href),updateFilters()}),$(".filter-input").on("change",function(a){a.preventDefault(),$(".paginate-container a").not(".pagination a").css("color","#dfecf6");var t=$(this).val(),e=new URL(window.location.href);t?e.searchParams.set("filter",t):e.searchParams.delete("filter"),$.ajax({url:e.href}).done(function(a){$(".paginate-container").html(a),LetterAvatar.transform()}).fail(function(){toastrs("Data could not be loaded!","error")}),window.history.replaceState(null,null,e.href)})});

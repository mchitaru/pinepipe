async function attachPlugins(){$("select").each(function(){$(this).select2({tags:$(this).hasClass("tags")})}),$("[data-flatpickr]").mrFlatpickr(),LetterAvatar.transform(),$("[data-refresh]").each(function(){$(this).on("change",function(t){t.preventDefault(),url=$(this).data("refresh"),$.ajax({url:url,type:"GET",dataType:"text",method:"GET",data:$("form").serialize(),success:function(t,e,a){$(document).trigger("ajax:success",[t,e,a])},complete:function(t,e){$(document).trigger("ajax:complete",[t,e])},error:function(t,e,a){$(document).trigger("ajax:error",[t,e,a])}})})})}function PreviewAvatarImage(t,e,a){if(t.files&&t.files[0]){var r=new FileReader;r.onload=function(r){$(t).closest(".avatar-container").children(".avatar-preview").html('<img class="'+a+'" src="'+r.target.result+'" width="'+e+'" height="'+e+'"/>')},r.readAsDataURL(t.files[0])}}function updateFilters(t,e,a,r){t=localStorage.getItem("sort"),e=localStorage.getItem("dir"),a=localStorage.getItem("filter"),r=localStorage.getItem("tag");var n=new URL(window.location.href);n.searchParams.has("sort")&&(t=n.searchParams.get("sort")),n.searchParams.has("dir")&&(e=n.searchParams.get("dir")),n.searchParams.has("filter")&&(a=n.searchParams.get("filter")),n.searchParams.has("tag")&&(r=n.searchParams.get("tag")),$(".filter-controls a").each(function(a){$(this).removeClass("asc desc"),$(this).data("sort")==t&&$(this).addClass(e)}),$(".filter-tags div").each(function(t){$(this).removeClass("active"),$(this).data("filter")==r&&$(this).addClass("active")}),a&&$(".filter-input").each(function(t){$(this).val(a)})}function initDropzoneLinks(t,e){$(".dropzone-file",$(".dz-preview").last()).each(function(){$(this).attr("href",e.download)}),$(".dropzone-delete",$(".dz-preview").last()).each(function(){$(this).attr("href",e.delete),$(this).removeClass("disabled")})}async function initDropzone(t,e,a,r){dropzone=$(t).dropzone({previewTemplate:document.querySelector(".dz-template").innerHTML,createImageThumbnails:!1,previewsContainer:t+"-previews",maxFiles:20,maxFilesize:2,parallelUploads:1,acceptedFiles:".jpeg,.jpg,.png,.gif,.svg,.pdf,.txt,.doc,.docx,.zip,.rar",url:e,success:function(t,e){e.is_success?(toastrs("File uploaded","success"),initDropzoneLinks(t,e),LetterAvatar.transform()):(this.removeFile(t),toastrs(e.error,"error"))},error:function(t,e){this.removeFile(t),e.error,toastrs(e.error,"error")},sending:function(t,e,a){a.append("_token",$('meta[name="csrf-token"]').attr("content"))},init:function(){if(r)for(var t in r){var e=r[t],a={name:e.file_name,size:e.size};this.options.addedfile.call(this,a),this.options.processing.call(this,a),this.options.complete.call(this,a),initDropzoneLinks(a,{download:e.download,delete:e.delete})}}})[0]}function timer(t,e){t.stop(),t.start({precision:"seconds",startValues:{seconds:e}}),t.addEventListener("secondsUpdated",function(e){$("#active-timer").html(t.getTimeValues().toString())})}$(document).on("ajax:success",function(t,e,a,r){207==r.status?($("#modal").modal("hide"),e.url?window.location=e.url:window.location.reload()):r.responseText&&($("#modal").length||$("body").append($('<div class="modal show" id="modal" data-keyboard="false" data-backdrop="static"></div>')),$("#modal").html(r.responseText).modal("show"),attachPlugins())}),$(document).on("ajax:error",function(t,e,a,r){toastrs(r,"error")}),function(t,e){function a(a,r){a=a||"",r=r||60;var n,o,i,s,c,l=String(a).toUpperCase().split(" ");return n=1==l.length?l[0]?l[0].charAt(0):"?":l[0].charAt(0)+l[1].charAt(0),t.devicePixelRatio&&(r*=t.devicePixelRatio),o=(("?"==n?72:n.charCodeAt(0))-64)%20,(i=e.createElement("canvas")).width=r,i.height=r,(s=i.getContext("2d")).fillStyle=["#92dacb","#e7afa9","#acd6f1","#e4c695","#728191","#a3e4d7","#93d6af","#7fb2d4","#dab7e9","#7c9cbd","#dfce8c","#dfb999","#9fdfb9","#ecf0f1","#95a5a6","#dcb5eb","#e0b699","#e4a9a1","#bdc3c7","#90a0a1","#92dacb"][o],s.fillRect(0,0,i.width,i.height),s.font=Math.round(i.width/2)+"px Arial",s.textAlign="center",s.fillStyle="#FFF",s.fillText(n,r/2,r/1.5),c=i.toDataURL(),i=null,c}a.transform=function(){Array.prototype.forEach.call(e.querySelectorAll("img[avatar]"),function(t,e){e=t.getAttribute("avatar"),t.src=a(e,t.getAttribute("width")),t.removeAttribute("avatar"),t.setAttribute("alt",e)})},"function"==typeof define&&define.amd?define(function(){return a}):"undefined"!=typeof exports?("undefined"!=typeof module&&module.exports&&(exports=module.exports=a),exports.LetterAvatar=a):(window.LetterAvatar=a,e.addEventListener("DOMContentLoaded",function(t){a.transform()}))}(window,document),$(function(){$("body").on("click",".pagination a",function(t){t.preventDefault(),$(".paginate-container a").not(".pagination a").css("color","#dfecf6");var e=new URL(window.location.href);sort=e.searchParams.get("sort"),dir=e.searchParams.get("dir"),filter=e.searchParams.get("filter");var a=new URL($(this).attr("href"));sort&&(a.searchParams.set("sort",sort),a.searchParams.set("dir",dir)),filter&&a.searchParams.set("filter",filter),$.ajax({url:a.href}).done(function(t){$(".paginate-container").html(t),LetterAvatar.transform();var e=new CustomEvent("paginate-click");document.dispatchEvent(e)}).fail(function(){toastrs("Data could not be loaded!","error")}),window.history.replaceState(null,null,a.href)}),$(".filter-controls a").on("click",function(t){t.preventDefault(),$(".paginate-container a").not(".pagination a").css("color","#dfecf6");var e=$(this).data("sort"),a=$(this).hasClass("asc")?"desc":"asc",r=new URL(window.location.href);r.searchParams.set("sort",e),r.searchParams.set("dir",a),$.ajax({url:r.href}).done(function(t){$(".paginate-container").html(t),LetterAvatar.transform();var e=new CustomEvent("paginate-sort");document.dispatchEvent(e)}).fail(function(){toastrs("Data could not be loaded!","error")}),window.history.replaceState(null,null,r.href),updateFilters()}),$(".filter-tags div").on("click",function(t){t.preventDefault(),$(".paginate-container a").not(".pagination a").css("color","#dfecf6");var e=$(this).data("filter"),a=new URL(window.location.href);a.searchParams.get("tag")==e?a.searchParams.delete("tag"):a.searchParams.set("tag",e),$.ajax({url:a.href}).done(function(t){$(".paginate-container").html(t),LetterAvatar.transform();var e=new CustomEvent("paginate-tag");document.dispatchEvent(e)}).fail(function(){toastrs("Data could not be loaded!","error")}),window.history.replaceState(null,null,a.href),updateFilters()});let t=null;$(".filter-input").on("input",function(e){e.preventDefault(),$(".paginate-container a").not(".pagination a").css("color","#dfecf6"),clearTimeout(t),textInput=$(this),t=setTimeout(function(){var t=textInput.val(),e=new URL(window.location.href);t?e.searchParams.set("filter",t):e.searchParams.delete("filter"),$.ajax({url:e.href}).done(function(t){$(".paginate-container").html(t),LetterAvatar.transform();var e=new CustomEvent("paginate-filter");document.dispatchEvent(e)}).fail(function(){toastrs("Data could not be loaded!","error")}),window.history.replaceState(null,null,e.href)},500)})}),$.getScript("../assets/js/easytimer.min.js",function(){window.timerInstance=new easytimer.Timer}),$(function(){$(document).on("click",".timer-entry",function(t){t.preventDefault();var e=$(this).attr("data-id");$.ajax({url:$(this).attr("href"),type:"POST",data:{timesheet_id:e,_token:$('meta[name="csrf-token"]').attr("content")},success:function(t){t.start?timer(window.timerInstance,t.offset):(window.timerInstance.stop(),$.ajax({url:t.url,type:"get",dataType:"text",success:function(t,e,a){$(document).trigger("ajax:success",[t,e,a])},complete:function(t,e){$(document).trigger("ajax:complete",[t,e])},error:function(t,e,a){$(document).trigger("ajax:error",[t,e,a])}})),$(".timer-popup").replaceWith(t.html)},error:function(t){}})})});

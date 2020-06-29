async function attachPlugins(){$("select").each(function(){$(this).select2({tags:$(this).hasClass("tags"),createTag:function(t){var e=$.trim(t.term);return""===e?null:{id:e,text:"✚ "+e,newTag:!0}}})}),$("[data-flatpickr]").mrFlatpickr(),$(".start[data-flatpickr]").each(function(){$(this).flatpickr()&&$(this).flatpickr().config.onChange.push(function(t,e,a){var n=$(".end[data-flatpickr]").flatpickr();n&&(n.selectedDates.length&&Date.parse(n.selectedDates[0])<Date.parse(e)&&n.setDate(e),n.config.minDate=e)})}),LetterAvatar.transform(),$("[data-refresh]").each(function(){$(this).on("change",function(t){t.preventDefault(),url=$(this).data("refresh"),$.ajax({url:url,type:"GET",dataType:"text",method:"GET",data:$("form").serialize(),success:function(t,e,a){$(document).trigger("ajax:success",[t,e,a])},complete:function(t,e){$(document).trigger("ajax:complete",[t,e])},error:function(t,e,a){$(document).trigger("ajax:error",[t,e,a])}})})}),$(".summernote").each(function(){$(this).summernote({height:400})})}function PreviewAvatarImage(t,e,a){if(t.files&&t.files[0]){var n=new FileReader;n.onload=function(n){$(t).closest(".avatar-container").children(".avatar-preview").html('<img class="'+a+'" src="'+n.target.result+'" style="max-width:'+e+"px; max-height:"+e+'px;"/>')},n.readAsDataURL(t.files[0])}}function updateFilters(t,e,a,n){t=localStorage.getItem("sort"),e=localStorage.getItem("dir"),a=localStorage.getItem("filter"),n=localStorage.getItem("tag");var s=new URL(window.location.href);s.searchParams.has("sort")&&(t=s.searchParams.get("sort")),s.searchParams.has("dir")&&(e=s.searchParams.get("dir")),s.searchParams.has("filter")&&(a=s.searchParams.get("filter")),s.searchParams.has("tag")&&(n=s.searchParams.get("tag")),$(".filter-controls a").each(function(a){$(this).removeClass("asc desc"),$(this).data("sort")==t&&$(this).addClass(e)}),$(".filter-tags div").each(function(t){$(this).removeClass("active"),$(this).data("filter")==n&&$(this).addClass("active")}),a&&$(".filter-input").each(function(t){$(this).val(a)})}function initDropzoneLinks(t,e){$(".dropzone-file",$(".dz-preview").last()).each(function(){$(this).attr("href",e.download)}),$(".dropzone-delete",$(".dz-preview").last()).each(function(){$(this).attr("href",e.delete),$(this).removeClass("disabled")})}async function initDropzone(t,e,a,n){dropzone=$(t).dropzone({previewTemplate:document.querySelector(".dz-template").innerHTML,createImageThumbnails:!1,previewsContainer:t+"-previews",maxFiles:20,maxFilesize:10,parallelUploads:1,acceptedFiles:".jpeg,.jpg,.png,.gif,.svg,.pdf,.txt,.doc,.docx,.zip,.rar,.xls,.xlsx",url:e,success:function(t,e){e.is_success?(toastrs("File uploaded","success"),initDropzoneLinks(t,e),LetterAvatar.transform()):(this.removeFile(t),toastrs("You can only upload images, documents and archives that are less than 10MB in size.","danger"))},error:function(t,e){this.removeFile(t),toastrs("You can only upload images, documents and archives that are less than 10MB in size.","danger")},sending:function(t,e,a){a.append("_token",$('meta[name="csrf-token"]').attr("content"))},init:function(){if(n)for(var t in n){var e=n[t],a={name:e.file_name,size:e.size};this.options.addedfile.call(this,a),this.options.processing.call(this,a),this.options.complete.call(this,a),initDropzoneLinks(a,{download:e.download,delete:e.delete})}}})[0]}function timer(t,e){t&&(t.stop(),t.start({precision:"seconds",startValues:{seconds:e}}),t.addEventListener("secondsUpdated",function(e){$(".active-timer").each(function(){$(this).text(t.getTimeValues().toString())})}))}function updateTimerUI(t){$(".timer-popup").each(function(){$(this).replaceWith(t.popup)}),$(".timer-control").each(function(){$(this).replaceWith(t.control)}),$(".task").each(function(){$(this).removeClass("glow-animation")}),t.start&&t.task_id&&$(".task[data-id="+t.task_id+"]").addClass("glow-animation")}$(document).on("ajax:success",function(t,e,a,n){207==n.status?($("#modal").modal("hide"),e.url?window.location=e.url:window.location.reload()):n.responseText&&($("#modal").length||$("body").append($('<div class="modal show" id="modal" data-keyboard="false" data-backdrop="static"></div>')),$("#modal").html(n.responseText).modal("show"),attachPlugins())}),$(document).on("ajax:error",function(t,e,a,n){toastrs(n,"danger")}),function(t,e){function a(a,n){a=a||"",n=n||60;var s,r,i,o,c,l=String(a).toUpperCase().split(" ");return s=1==l.length?l[0]?l[0].charAt(0):"?":l[0].charAt(0)+l[1].charAt(0),t.devicePixelRatio&&(n*=t.devicePixelRatio),r=(("?"==s?72:s.charCodeAt(0))-64)%20,(i=e.createElement("canvas")).width=n,i.height=n,(o=i.getContext("2d")).fillStyle=["#92dacb","#e7afa9","#acd6f1","#e4c695","#93b4d8","#a3e4d7","#93d6af","#7fb2d4","#dab7e9","#a2cbf3","#dfce8c","#dfb999","#9fdfb9","#90c8db","#7db1b4","#dcb5eb","#e0b699","#e4a9a1","#83c1ee","#66b6db","#92dacb"][r],o.fillRect(0,0,i.width,i.height),o.font=Math.round(i.width/2)+"px Arial",o.textAlign="center",o.fillStyle="#FFF",o.fillText(s,n/2,n/1.5),c=i.toDataURL(),i=null,c}a.transform=function(){Array.prototype.forEach.call(e.querySelectorAll("img[avatar]"),function(t,e){e=t.getAttribute("avatar"),t.src=a(e,t.getAttribute("width")),t.removeAttribute("avatar"),t.setAttribute("alt",e)})},"function"==typeof define&&define.amd?define(function(){return a}):"undefined"!=typeof exports?("undefined"!=typeof module&&module.exports&&(exports=module.exports=a),exports.LetterAvatar=a):(window.LetterAvatar=a,e.addEventListener("DOMContentLoaded",function(t){a.transform()}))}(window,document),$(function(){let t=null;$("body").on("click",".pagination a",function(e){e.preventDefault(),$(".paginate-container").html('<div class="h-100 w-100 row align-items-center justify-content-center">\n            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>\n            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>\n            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>\n            </div>'),clearTimeout(t),btn=$(this),t=setTimeout(function(){var t=new URL(window.location.href);sort=t.searchParams.get("sort"),dir=t.searchParams.get("dir"),filter=t.searchParams.get("filter");var e=new URL(btn.attr("href"));sort&&(e.searchParams.set("sort",sort),e.searchParams.set("dir",dir)),filter&&e.searchParams.set("filter",filter),$.ajax({url:e.href,type:"get",dataType:"text"}).done(function(t){$(".paginate-container").html(t),LetterAvatar.transform();var e=new CustomEvent("paginate-click");document.dispatchEvent(e)}).fail(function(){toastrs("Data could not be loaded!","danger")}),window.history.replaceState(null,null,e.href)},100)}),$(".filter-controls a").on("click",function(e){e.preventDefault(),$(".paginate-container").html('<div class="h-100 w-100 row align-items-center justify-content-center">\n            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>\n            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>\n            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>\n            </div>'),clearTimeout(t),btn=$(this),t=setTimeout(function(){var t=btn.data("sort"),e=btn.hasClass("asc")?"desc":"asc",a=new URL(window.location.href);a.searchParams.set("sort",t),a.searchParams.set("dir",e),$.ajax({url:a.href,type:"get",dataType:"text"}).done(function(t){$(".paginate-container").html(t),LetterAvatar.transform();var e=new CustomEvent("paginate-sort");document.dispatchEvent(e)}).fail(function(){toastrs("Data could not be loaded!","danger")}),window.history.replaceState(null,null,a.href),updateFilters()},100)}),$(".filter-tags div").on("click",function(e){e.preventDefault(),$(".paginate-container").html('<div class="h-100 w-100 row align-items-center justify-content-center">\n            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>\n            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>\n            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>\n            </div>'),clearTimeout(t),btn=$(this),t=setTimeout(function(){var t=btn.data("filter"),e=new URL(window.location.href);e.searchParams.get("tag")==t?e.searchParams.delete("tag"):e.searchParams.set("tag",t),$.ajax({url:e.href,type:"get",dataType:"text"}).done(function(t){$(".paginate-container").html(t),LetterAvatar.transform();var e=new CustomEvent("paginate-tag");document.dispatchEvent(e)}).fail(function(){toastrs("Data could not be loaded!","danger")}),window.history.replaceState(null,null,e.href),updateFilters()},100)}),$(".filter-input").on("input",function(e){e.preventDefault(),$(".paginate-container").html('<div class="h-100 w-100 row align-items-center justify-content-center">\n            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>\n            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>\n            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>\n            </div>'),clearTimeout(t),textInput=$(this),t=setTimeout(function(){var t=textInput.val(),e=new URL(window.location.href);t?e.searchParams.set("filter",t):e.searchParams.delete("filter"),$.ajax({url:e.href,type:"get",dataType:"text"}).done(function(t){$(".paginate-container").html(t),LetterAvatar.transform();var e=new CustomEvent("paginate-filter");document.dispatchEvent(e)}).fail(function(){toastrs("Data could not be loaded!","danger")}),window.history.replaceState(null,null,e.href)},500)}),$(".paginate-container").each(function(t){var e;return $(this),e=new URL(window.location.href),$.ajax({url:e.href,type:"get",dataType:"text"}).done(function(t){$(".paginate-container").html(t),LetterAvatar.transform();var e=new CustomEvent("paginate-load");document.dispatchEvent(e)}).fail(function(){}),!1})}),$.getScript("../assets/js/easytimer.min.js",function(){window.timerInstance=new easytimer.Timer}),$(function(){$(document).on("click",".timer-entry",function(t){t.preventDefault();var e=$(this).attr("data-task"),a=$(this).attr("data-timesheet");$.ajax({url:$(this).attr("href"),type:"POST",data:{task_id:e,timesheet_id:a,_token:$('meta[name="csrf-token"]').attr("content")},success:function(t){t.start?timer(window.timerInstance,t.offset):(window.timerInstance.stop(),t.url&&$.ajax({url:t.url,type:"get",dataType:"text",success:function(t,e,a){$(document).trigger("ajax:success",[t,e,a])},complete:function(t,e){$(document).trigger("ajax:complete",[t,e])},error:function(t,e,a){$(document).trigger("ajax:error",[t,e,a])}})),updateTimerUI(t)},error:function(t){}})})});

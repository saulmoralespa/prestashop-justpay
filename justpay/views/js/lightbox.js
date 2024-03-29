window.addEventListener('DOMContentLoaded', function() {
    let iframeJustPay =  document.getElementById("iframe-just-pay");
    if(iframeJustPay){
        !function(t,e){"use strict";var i=e.body||"",n="classList",o=function(t,i){var n=i||{};this.trigger=t,this.el=e.getElementsByClassName("iframe-lightbox")[0]||"",this.body=this.el?this.el.getElementsByClassName("body")[0]:"",this.content=this.el?this.el.getElementsByClassName("content")[0]:"",this.src=t.dataset.src||"",this.href=t.getAttribute("href")||"",this.dataPaddingBottom=t.dataset.paddingBottom||"",this.dataScrolling=t.dataset.scrolling||"",this.rate=n.rate||500,this.scrolling=n.scrolling,this.onOpened=n.onOpened,this.onIframeLoaded=n.onIframeLoaded,this.onLoaded=n.onLoaded,this.onCreated=n.onCreated,this.onClosed=n.onClosed,this.init()};o.prototype.init=function(){var t=this;this.el||this.create();var e=function(t,e){var i,n,o,s;return function(){o=this,n=[].slice.call(arguments,0),s=new Date;var a=function(){var l=new Date-s;l<e?i=setTimeout(a,e-l):(i=null,t.apply(o,n))};i||(i=setTimeout(a,e))}},i=function(){t.open()};this.trigger[n].contains("iframe-lightbox-link--is-binded")||(this.trigger[n].add("iframe-lightbox-link--is-binded"),this.trigger.addEventListener("click",function(t){t.stopPropagation(),t.preventDefault(),e(i,this.rate).call()}))},o.prototype.create=function(){var o=this,s=e.createElement("div");this.el=e.createElement("div"),this.content=e.createElement("div"),this.body=e.createElement("div"),this.el[n].add("iframe-lightbox"),s[n].add("backdrop"),this.content[n].add("content"),this.body[n].add("body"),this.el.appendChild(s),this.content.appendChild(this.body),this.contentHolder=e.createElement("div"),this.contentHolder[n].add("content-holder"),this.contentHolder.appendChild(this.content),this.el.appendChild(this.contentHolder),this.btnClose=e.createElement("a"),this.btnClose[n].add("btn-close"),this.btnClose.setAttribute("href","javascript:void(0);"),this.el.appendChild(this.btnClose),i.appendChild(this.el),s.addEventListener("click",function(){o.close()}),this.btnClose.addEventListener("click",function(){o.close()}),t.addEventListener("keyup",function(t){27===(t.which||t.keyCode)&&o.close()});var a=function(){o.isOpen()||(o.el[n].remove("is-showing"),o.body.innerHTML="")};this.el.addEventListener("transitionend",a,!1),this.el.addEventListener("webkitTransitionEnd",a,!1),this.el.addEventListener("mozTransitionEnd",a,!1),this.el.addEventListener("msTransitionEnd",a,!1),this.callCallback(this.onCreated,this)},o.prototype.loadIframe=function(){var t=this;this.iframeId="iframe-lightbox"+Date.now(),this.iframeSrc=this.src||this.href||"";var i=[];i.push('<iframe src="'+this.iframeSrc+'" name="'+this.iframeId+'" id="'+this.iframeId+'" onload="this.style.opacity=1;" style="opacity:0;border:none;" webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true" height="166" frameborder="no"></iframe>'),i.push('<div class="half-circle-spinner"><div class="circle circle-1"></div><div class="circle circle-2"></div></div>'),this.body.innerHTML=i.join(""),function(i,o){var s=e.getElementById(i);s.onload=function(){this.style.opacity=1,o[n].add("is-loaded"),t.scrolling||t.dataScrolling?(s.removeAttribute("scrolling"),s.style.overflow="scroll"):(s.setAttribute("scrolling","no"),s.style.overflow="hidden"),t.callCallback(t.onIframeLoaded,t),t.callCallback(t.onLoaded,t)}}(this.iframeId,this.body)},o.prototype.open=function(){this.loadIframe(),this.dataPaddingBottom?this.content.style.paddingBottom=this.dataPaddingBottom:this.content.removeAttribute("style"),this.el[n].add("is-showing"),this.el[n].add("is-opened"),i[n].add("iframe-lightbox--open"),this.callCallback(this.onOpened,this)},o.prototype.close=function(){this.el[n].remove("is-opened"),this.body[n].remove("is-loaded"),i[n].remove("iframe-lightbox--open"),this.callCallback(this.onClosed,this)},o.prototype.isOpen=function(){return this.el[n].contains("is-opened")},o.prototype.callCallback=function(t,e){"function"==typeof t&&t.bind(this)(e)},t.IframeLightbox=o}("undefined"!=typeof window?window:this,document);

        (function(root, document) {
            "use strict";
            [].forEach.call(document.getElementsByClassName("iframe-lightbox-link"), function(el) {
                el.lightbox = new IframeLightbox(el, {
                    onCreated: function() {
                        /* show your preloader */
                    },
                    onLoaded: function() {
                        document.getElementsByClassName("content-holder")[0].style.backgroundColor = "#fff";
                    },
                    onError: function() {
                        /* hide your preloader */
                    },
                    onClosed: function() {
                        document.getElementsByClassName("content-holder")[0].style.backgroundColor = "#4c4c4c";
                        document.getElementsByClassName("content-holder")[0].style.opacity = "1";
                    },
                    scrolling: true,
                    /* default: false */
                    rate: 500 /* default: 500 */,
                    touch: false /* default: false - use with care for responsive images in links on vertical mobile screens */
                });
            });

            iframeJustPay.click();

        })("undefined" !== typeof window ? window : this, document);
    }
});


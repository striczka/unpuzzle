/**
 * Created by Nastya Dragich on 24.04.2016.
 */
function transformFont(e, a){
    e.each(function(){
        var fontSize = parseInt($(this).css("font-size")),
            newFontSize = fontSize*a + "px";
        $(this).css("font-size", newFontSize);
    });
}
function setHeight(e, items){
    var img = new Image();
    img.onload = function() {
        var iH = this.height, iW = this.width, p = items.width(), l = $(".que-link").height();
        var height = (p/iW)*iH + l + 4;
        e.css("height", height + "px");
    };
    var imageElem = $(".activator");
    img.src = imageElem.attr("src");
}
$(window).resize(function(){
    if($(window).width() > 500){
        var items = $(".item"), single = $(".que-img");
        setHeight(single, items);
    }
});
$(function(){
    if($(window).width() > 800 && $(window).width() < 1200){
        var a = $(window).width()/1200;
    }
    else if($(window).width() < 800){
        a = 2/3;
    }
    else{
        a = 1;
    }
    var items = $(".item"), single = $(".que-img");
    transformFont($(".banner-main-text span"), a);
    if($(window).width() > 500) {
        setHeight(single, items);
    }
    items.each(function(){
        var optHeight = $(this).find(".main").outerHeight(),
            fullHeight = optHeight + $(this).find(".additional").outerHeight();
        $(this).find(".item-info").height(optHeight);
        $(this).hover(function(){
            $(this).find(".item-info").height(fullHeight);
        });
        $(this).mouseleave(function(){
            $(this).find(".item-info").height(optHeight);
        });
    });
});



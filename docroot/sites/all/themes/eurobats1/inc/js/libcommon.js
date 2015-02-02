var isIE = false;
var IEVersion = -1;
// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function getTop( obj ) {
    var res = obj.offsetTop;
    var p = obj.offsetParent;
    //menuLayer.innerHTML = res + "<br />";
    while ( p != null ) {
        res += p.offsetTop;
        //menuLayer.innerHTML += res + "<br />";
        p = p.offsetParent;
    }
    return res;
}

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function getLeft( obj ) {
    var res = obj.offsetLeft;
    //menuLayer.innerHTML += res + "<br />";
    var p = obj.offsetParent;
    while ( p != null ) {
        res += p.offsetLeft;
        //menuLayer.innerHTML += res + "<br />";
        p = p.offsetParent;
    }
    return res;
}

// ------------------------------------------------------------------------
//  getBodyScrollTop
// ------------------------------------------------------------------------
function getBodyScrollTop() {
    /*if ( document.compatMode == "CSS1Compat" )
        return document.documentElement.scrollTop;
    else
        return document.body.scrollTop;*/

    if ( document.documentElement.scrollTop > document.body.scrollTop )
        return document.documentElement.scrollTop
    else
        return document.body.scrollTop
}


// ------------------------------------------------------------------------
//  setCookie
// ------------------------------------------------------------------------
function setCookie ( name, value, secure ) {
      document.cookie = name + "=" + escape( value ) +
        ((secure) ? "; secure" : "" + "; path=/" );
}

// ------------------------------------------------------------------------
//  getCookie
// ------------------------------------------------------------------------
function getCookie( name ) {
    var cookie = " " + document.cookie;
    var search = " " + name + "=";
    var setStr = null;
    var offset = 0;
    var end = 0;
    if (cookie.length > 0) {
        offset = cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = cookie.indexOf(";", offset)
            if (end == -1) {
                    end = cookie.length;
            }
            setStr = unescape(cookie.substring(offset, end));
        }
    }
    return(setStr);
}

// ------------------------------------------------------------------------
//  deleteCookie
// ------------------------------------------------------------------------
function deleteCookie( name ) {
    if (getCookie(name)) {
        document.cookie = name + "=" + 
        "; expires=Thu, 01-Jan-70 00:00:01 GMT"
    }
}


function VectorMap( params ) {
var Self = this;

var fMapDoc             = params[ "mapDoc" ];
var isVML               = params[ "isVML" ];
var fIEVer              = params[ "IEVer" ];
var fScaleObj           = fMapDoc.getElementById( params[ "scaleObjId" ] );
var fIncScaleBtnId      = document.getElementById( params[ "incScaleBtnId" ] );
var fDecScaleBtnId      = document.getElementById( params[ "decScaleBtnId" ] );
var fScaleStep          = 100;
var fMaxScale           = 1000;
var fMinScale           = 160;
var fMapContainer       = document.getElementById( params[ "mapContainer" ] );
var fMapScroll          = document.getElementById( "id1" );
var fCountries          = params[ "countries" ];
var fCountriesObjCont   = fMapDoc.getElementById( params[ "countriesObjCont" ] );
var fMapMessage         = document.getElementById( "mapMessage1" );
var fMapMessageTmpl     = "<table><tr><td><img src=\"@CC@\" alt=\"\" />&nbsp;&nbsp;&nbsp;</td><td>@CN@</td></tr></table>";
var fMapMessageTmpl1    = "<tr><td style=\"font-weight : bold; text-align : right;\">Date of Signing:&nbsp;&nbsp;&nbsp;</td><td style=\"text-align : left;\">@SD@</td></tr>";
var fMapMessageTmpl2    = "<tr><td style=\"font-weight : bold; text-align : right;\">Party since:&nbsp;</td><td style=\"text-align : left;\">@SD@</td></tr>";


var fCurScale = 100;
var fPathName;
var fGroupName;
var fDocWidth;
var fDocHeight;


// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function setMapDimension( Width, Height ) {
    if ( isVML ) {
        fMapContainer.style.width = Width + "px";
        fMapContainer.style.height = Height + "px";
    }
    else {
        obj = fMapDoc.documentElement;
        obj.setAttribute( "width", Width );
        obj.setAttribute( "height", Height );

        if ( fMapContainer.offsetWidth != Width ) {
            fMapContainer.width     = Width;
            fMapContainer.height    = Height;
        }

        //fMapContainer.width     = Math.round( Width + 1 );
        //fMapContainer.height    = Math.round( Height + 1 );
    }
}

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function scale( dir ) {
    fCurScale += dir * fScaleStep;

    if ( fCurScale < fMinScale ) {
        fCurScale = fMinScale;//fScaleStep;
        return;
    }
    if ( fCurScale == ( fMaxScale + fScaleStep) ) {
        fCurScale -= fScaleStep;
        return;
    }
    if ( fCurScale > fMaxScale ) {
        fCurScale = fMaxScale;
    }

    if ( isVML ) {
        fScaleObj.style.width    = fCurScale + "%";
        fScaleObj.style.height   = fCurScale + "%";
        setMapDimension( fScaleObj.offsetWidth, fScaleObj.offsetHeight );
        //alert( "Width : " + fScaleObj.offsetWidth + "; Height : " + fScaleObj.offsetHeight );
    }
    else {
        st = parseFloat(fCurScale) / 100.0;
        fScaleObj.setAttribute( "transform", "scale(" + st + "," + st + ")" );
        setMapDimension( fDocWidth * st, fDocHeight * st );
    }
}
// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
this.setScale = function ( newScale ) {
    fCurScale = newScale;

    if ( fCurScale < fMinScale ) {
        fCurScale = fMinScale;//fScaleStep;
        return;
    }
    if ( fCurScale == ( fMaxScale + fScaleStep) ) {
        fCurScale -= fScaleStep;
        return;
    }
    if ( fCurScale > fMaxScale ) {
        fCurScale = fMaxScale;
    }

    if ( isVML ) {
        fScaleObj.style.width    = fCurScale + "%";
        fScaleObj.style.height   = fCurScale + "%";
        setMapDimension( fScaleObj.offsetWidth, fScaleObj.offsetHeight );
        //alert( "Width : " + fScaleObj.offsetWidth + "; Height : " + fScaleObj.offsetHeight );
    }
    else {
        st = parseFloat(fCurScale) / 100.0;
        fScaleObj.setAttribute( "transform", "scale(" + st + "," + st + ")" );
        setMapDimension( fDocWidth * st, fDocHeight * st );
    }
}
this.setPosition = function ( posX, posY ) {
    fMapScroll.scrollLeft = posX;
    fMapScroll.scrollTop = posY;
}
// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
fMapMessage.onmouseover = function () {
    fMapMessage.style.display = "none";
}
// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function setObjColor( obj, color ) {
    if ( isVML )
        obj.fillcolor   = color;
    else
        obj.style.fill  = color;
}

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function onOver( Action, obj, cobj ) {
    //console.log( obj.id + cobj.color1 );
    if ( Action == 1 ) { 
        color = cobj.color2;
        fMapMessage.style.display = "block";
        //console.log( fMapMessageTmpl );
        str = fMapMessageTmpl.replace( "@CC@", cobj.flaguri ).replace( "@CN@", cobj.name );
        var ainfo = ( (cobj.sdate != "") || (cobj.ddate != "") )
        if ( ainfo ) str += "<table>";
        //if ( cobj.sdate != "" )
        //    str += fMapMessageTmpl1.replace( "@SD@", cobj.sdate );
        if ( cobj.ddate != "" )
            str += fMapMessageTmpl2.replace( "@SD@", cobj.ddate );
        if ( ainfo ) str += "</table>"
        fMapMessage.innerHTML = str;
        //console.log( fMapMessage.innerHTML );
    }
    else {
        fMapMessage.style.display = "none";
        color = cobj.color1;
    }

    if ( obj.nodeName.toLowerCase() == fPathName )
        setObjColor( obj, color );
    var nodes = obj.childNodes;
    //console.log( obj.id + "  " + nodes.length );
    for ( var i = 0; i < nodes.length; i++ ) {
        obj1 = nodes[ i ];
        //if ( typeof obj1 == "undefined" ) continue;
        //console.log( "    " + obj1.id + "   " + obj1.nodeName );
        if ( obj1.nodeName.toLowerCase() == fPathName )
            setObjColor( obj1, color );
        else
        if ( (obj1.nodeName.toLowerCase() == fGroupName) && (typeof obj1.childNodes != "undefined") ) {
            onOver( Action, obj1, cobj );
        }
        /*else
            console.log( obj1.innerHTML );*/
    }
}

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function setOnOver( Action, obj, cobj ) {
    //if( typeof cobj != "object" )
    //console.log( obj.id + cobj.color1 );
    return function () {
        onOver( Action, obj, cobj );
    }
}

function setOnClick( cobj ) {
    return function () { location.href = cobj[ "href" ]; }
}

// ------------------------------------------------------------------------
// Constructor
// ------------------------------------------------------------------------
if ( fIncScaleBtnId != null )
    fIncScaleBtnId.onclick = function () {
        scale( 1 );
    }

if ( fDecScaleBtnId != null )
    fDecScaleBtnId.onclick = function () {
        scale( -1 );
    }
var prevX;
var prevY;
var mouseCapture = false;
fScaleObj.onmousedown = function ( e ) {
    var btn = -1;
    var btns = [ -1, 0, 2, 3, 1, -1, -1, -1, -1, -1, -1 ];

    if ( (fIEVer == -1) || ( fIEVer > 8 ) )
        btn = e.button;
    else {
        e = event;
        btn = btns[ e.button ];
    }
    if ( btn == 0 ) {
        mouseCapture = true;
        prevX = e.screenX;
        prevY = e.screenY;
    }
    //alert( btn );
    //alert( event.button );
    //alert( e.button );
    return false;
}

fScaleObj.onmouseup = function ( ) {
    mouseCapture = false;
    //alert( "onmouseup" );
}

fScaleObj.onmouseleave = function () {
    mouseCapture = false;
    //alert( "onmouseleave" );
}

fScaleObj.onmousemove =
//window.onmousemove =
function ( e ) {

//mapMessage
    if ( (fIEVer > 0) && ( fIEVer <= 8 ) )
        e = event;

    var cx = e.screenX;
    var cy = e.screenY;
    var cx1 = e.clientX;
    var cy1 = e.clientY;
    var topm;
    var bst;
    
    //fMapMessage.style.display = "block";
    
    //fMapMessage.style.left = getLeft( fMapScroll ) + ( cx1 - fMapScroll.scrollLeft ) - parseInt( fMapMessage.offsetWidth / 2 ) + "px";
    //fMapMessage.style.top = getTop( fMapScroll ) + ( cy1 - fMapScroll.scrollTop ) - ( fMapMessage.offsetHeight + 5 ) + "px";
    //fMapMessage.style.left = ( cx1 - fMapScroll.scrollLeft ) + 145 - parseInt( fMapMessage.offsetWidth / 2 )  + "px";
    //fMapMessage.style.top = ( cy1 - fMapScroll.scrollTop ) + 50 - ( fMapMessage.offsetHeight + 5 ) + "px";
    //console.log( "x = " + cx + "; fMapScroll.scrollLeft = " + fMapScroll.scrollLeft  + "; " );
    fMapMessage.style.left = cx - parseInt( fMapMessage.offsetWidth / 2 ) + "px";
    bst = getBodyScrollTop();
    topm = cy + bst - 120 - fMapMessage.offsetHeight;// + "px";

    //console.log( topm + " # " + bst );
    //console.log( bst + " # " + cy );
    //console.log( dump( e, 1 ) );
    if ( topm < bst ) {
        //console.log( "!!!!!!" );
        topm = cy + bst - 60;// + "px";
    }
    //console.log( topm + " # " + bst );
    fMapMessage.style.top = topm  + "px";
    if ( !mouseCapture ) return;
    
    if ( ( prevX == cx ) && ( prevY == cy ) ) return;
    return; //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    fMapScroll.scrollLeft += ( prevX - cx );
    fMapScroll.scrollTop += ( prevY - cy );
    prevX = cx; prevY = cy;
    
}

function onMouseWheel( event  ) {
    return; //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    if (!event) event = window.event; 
    if ( 'wheelDelta' in event )
        d = event.wheelDelta;
    else
        d = -1 * event.detail;

     //alert( d );
     scale( d / Math.abs( d ) );

    if (event.stopPropagation) event.stopPropagation();
    else event.cancelBubble = true;
    if(event.preventDefault) event.preventDefault();
    else event.returnValue = false;

}



if ( fScaleObj.addEventListener ) {
    fScaleObj.addEventListener ("mousewheel", onMouseWheel, false);
    fScaleObj.addEventListener ("DOMMouseScroll", onMouseWheel, false);
}
else
    fScaleObj.attachEvent ( "onmousewheel", onMouseWheel );


function onMouseWheel1( event  ) {

    if (event.stopPropagation) event.stopPropagation();
    else event.cancelBubble = true;
    if(event.preventDefault) event.preventDefault();
    else event.returnValue = false;

}


if ( fMapMessage.addEventListener ) {
    fMapMessage.addEventListener ("mousewheel", onMouseWheel1, false);
    fMapMessage.addEventListener ("DOMMouseScroll", onMouseWheel1, false);
}
else
    fMapMessage.attachEvent ( "onmousewheel", onMouseWheel1 );

fMapScroll.onmouseleave = function () {
    mouseCapture = false;
    //alert( "onmouseleave" );
}

//alert( isVML + " # " + fIEVer );
if ( isVML ) {
    fPathName   = "shape";
    fGroupName  = "group";
}
else {
    fPathName   = "path";
    fGroupName  = "g";
    //fMapContainer = document.getElementById( params[ "SVGContainer" ] );
    //if ( fIEVer == -1 ) {
        obj = fMapDoc.documentElement;
        fDocWidth   = obj.width.baseVal.value;
        fDocHeight  = obj.height.baseVal.value
    /*}
    else 
        if ( fIEVer > 8) {
            //alert( "IE9" );
            //fMapDoc.
            alert( fMapDoc );
        }*/
    setMapDimension( fDocWidth, fDocHeight );
    
    //fMapContainer.style.display = "block";
}

for ( i = 0; i < fCountries.length; i++ ) {
    obj = fMapDoc.getElementById( fCountries[ i ].alpha2 );
    if ( obj == null )
        obj = fMapDoc.getElementById( fCountries[ i ].alpha2.toLowerCase() );
    if( obj != null ) {
        if ( typeof obj != "undefined" ) {
            obj.onmouseover = setOnOver( 1, obj, fCountries[ i ] );
            obj.onmouseout = setOnOver( 0, obj, fCountries[ i ] );
            //if ( obj.id == "fr" )
                onOver( 0, obj, fCountries[ i ] );
            obj.ondblclick = setOnClick( fCountries[ i ] );// function() { alert( "onclick" ); }
        }
    }
    /*else
        console.log( fCountries[ i ].alpha2 ); */
}
//obj = fMapDoc.documentElement;
//alert( "Width : " + Math.round( obj.width.baseVal.value ) + "; Height : " + obj.height.baseVal.value );
//alert( "Width : " + fMapDoc.documentElement.width.baseVal.value + "; Height : " + fMapDoc.documentElement.height.baseVal.value );

}
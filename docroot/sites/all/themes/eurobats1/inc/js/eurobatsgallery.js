


function euroBatsGallery( params ) {
var Self = this;
var fMainCont           = document.getElementById( params[ "mainCont" ] );
var fPathToImg          = params[ "pathToImg" ];
var fImgFldr            = params[ "imgFolder" ];
var imgs                = params[ "imgs" ];
var picDivContClass     = params[ "picDivContClass" ];
var fPicDivMsg          = params[ "picDivMsgClass" ];
var fPicDivHover        = params[ "picDivHoverClass" ];
var picDivContPID       = picDivContClass;
var fResize             = params[ "picResize" ];

var fLeftBtn;
var fRightBtn;
var fMainContHeight     = 0;
var fMainContWidth      = 0;
var fMainContMiddle     = 0;
var fMainContMarginTop  = 0;
var fFirst;
var fMiddle;


var IIMGOBJ             = 3;
var ICONTOBJ            = 4;
var IIMGWIDTH           = 5;
var ICONTWIDTH          = 6;
var ICONTLEFT           = 7;
var IMSGOBJ             = 8;
var IHOVEROBJ           = 9;

var fReady              = false;
var fMoving             = false;
var fMovingTimer;
var fScrollTimer;

var fStepTimeout        = params[ "stepScrollTimeout" ];
var fStepScrollDx       = params[ "stepScrollDx" ];
var fStepBtnDx          = params[ "stepBtnDx" ];
var fStepDx;
var fIsScroll           = params[ "isScroll" ];
var fScrollTimout       = params[ "scrollTimeout" ];

var fCanScroll          = true;
var fPicPadding         = 1;
var fMaxPicOpacity      = params[ "maxOpacity" ];
var fMinPicOpacity      = params[ "minOpacity" ];

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function setOpacity( obj, Opacity ) {
    if ( isIE ) {
        obj.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity = " + parseInt( Opacity* 100 ) + ")";
    }
    else
        obj.style.opacity = Opacity;
}
// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function setOnImgClick( ref ) {

    return function () { location.href = ref; }
}
// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
this.init = function() {
    var str = "";
    var imgObj;
    var divObj;
    var msgObj;
    var hovObj;
    for ( i = 0; i < imgs.length; i++ ) {
        str += "<div class=\"" + picDivContClass + "\" id=\"" + picDivContPID + "_" + i + "\">" +
            //"<img id=\"" + picDivContPID + "_img" + i + "\" src=\"" + fPathToImg + fImgFldr + imgs[ i ][ 0 ] + "\" alt=\"\" />" +
            "<img id=\"" + picDivContPID + "_img" + i + "\" src=\"" +"/"+ imgs[ i ][ 0 ] + "\" alt=\"\" />" +
            "<div class=\"" + fPicDivMsg + "\" id = \"" + fPicDivMsg + "_" + i + "\">" + imgs[ i ][ 2 ] + "</div>" +
            "<div class=\"" + fPicDivHover + "\" id = \"" + fPicDivHover + "_" + i + "\"></div>" +
            "</div>";
    }
    // alert( str );
    fMainCont.innerHTML += str;
    // ------------------------------------------------------------------------
    //
    // ------------------------------------------------------------------------
    function imgsReady() {
        var l = 0;
        var dl = 0;
        var h = imgs[ 0 ][ ICONTOBJ ].offsetHeight;
        var w;
        var ci = 0;
        fMainContHeight = parseInt( h * fResize );
        fMainContMarginTop = parseInt( (fMainContHeight - h) / 2 );
        fMainContWidth  = fMainCont.offsetWidth;
        fMainContMiddle = parseInt( (fMainContWidth - 1) / 2 ) + 1;
        for ( i = 0; i < imgs.length; i++ ) {
            divObj = imgs[ i ][ ICONTOBJ ];
            imgObj = imgs[ i ][ IIMGOBJ ];
            divObj.onclick = setOnImgClick( imgs[ i ][ 1 ] );
            imgs[ i ][ ICONTWIDTH ] = divObj.offsetWidth;
            imgs[ i ][ IIMGWIDTH ]  = imgObj.offsetWidth;
            divObj.style.top = fMainContMarginTop + "px";
           // divObj.style.left = l + "px";
            //l += divObj.offsetWidth;
        }
        for ( i = 0; i < imgs.length; i++ ) {
            w = imgs[ i ][ ICONTWIDTH ];
            if ( (l + ( w * fResize ) / 2) >= fMainContMiddle ) {
                dl = fMainContMiddle - (l + ( w * fResize ) / 2);
                ci = i;
                break;
            }
            l += w + fPicPadding;
        }
        l = dl;
        for ( i = 0; i < imgs.length; i++ ) {
            divObj = imgs[ i ][ ICONTOBJ ];
            hovObj = imgs[ i ][ IHOVEROBJ ];

            divObj.style.left = l + "px";
            setOpacity( hovObj, fMaxPicOpacity );
            if ( (divObj.offsetLeft <= 0) && ( (divObj.offsetLeft + imgs[ i ][ ICONTWIDTH ]) >= 0 ) )
                fFirst = i;
            imgs[ i ][ ICONTLEFT ] = l;
            l += parseInt( imgs[ i ][ ICONTWIDTH ] * ((ci == i)?fResize:1) ) + fPicPadding;
            if ( ci == i ) {
                imgObj = imgs[ i ][ IIMGOBJ ];
                divObj.style.top = "0px";
                //imgObj.style.height = parseInt( fResize * 100 ) + "%";
                imgObj.style.width = parseInt( imgObj.offsetWidth * fResize ) + "px";
                //alert( imgObj.offsetWidth + " # " + fResize + " # " + parseInt( fResize * 100 ) );
                setOpacity( hovObj, fMinPicOpacity );
                fMiddle = i;
            }
        }

        fMainCont.style.height = fMainContHeight + "px";
        fLeftBtn = document.getElementById( "galleryLeftBtn" );
        fRightBtn = document.getElementById( "galleryRightBtn" );
        //fLeftBtn.onmousedown = function( e ) { }
        fLeftBtn.onclick = function( e ) { fStepDx = fStepBtnDx; stepLeft() };
        fRightBtn.onclick = function( e ) { fStepDx = fStepBtnDx; stepRight() };

        fLeftBtn.onmouseover = scrollOnOver; //function() { clearTimeout( fScrollTimer ); fCanScroll = false; }
        fLeftBtn.onmouseout = scrollOnOut;
        fRightBtn.onmouseover = scrollOnOver;
        fRightBtn.onmouseout = scrollOnOut;
        fMainCont.onmouseover = scrollOnOver;
        fMainCont.onmouseout = scrollOnOut;

        fReady = true;
        scrollOnOut();
    }
    var onLoadCounter = 0;
    var onLoadCount = imgs.length;
    // ------------------------------------------------------------------------
    //
    // ------------------------------------------------------------------------
    function imgOnLoad() {
        onLoadCounter++;
        if ( onLoadCounter == onLoadCount )
            imgsReady();
    }
    for ( i = 0; i < imgs.length; i++ ) {
        imgObj = document.getElementById( picDivContPID + "_img" + i );
        divObj = document.getElementById( picDivContPID + "_" + i );
        msgObj = document.getElementById( fPicDivMsg + "_" + i );
        hovObj = document.getElementById( fPicDivHover + "_" + i );
        imgs[ i ][ IIMGOBJ ]    = imgObj;
        imgs[ i ][ ICONTOBJ ]   = divObj;
        imgs[ i ][ IMSGOBJ ]    = msgObj;
        imgs[ i ][ IHOVEROBJ ]  = hovObj;
        hovObj.onmouseover = setOnMouseHover( i );
        hovObj.onmouseout = setOnMouseOut( i );
        if ( !isIE || IEVersion > 7 )
            imgObj.onload = imgOnLoad;
    }
    if ( isIE && IEVersion < 8 )
        imgsReady();

}

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function scrollOnOver() {
    //clearTimeout( fScrollTimer );
    fCanScroll = false;
}
// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function scrollOnOut() {
    if ( fIsScroll ) {
        fCanScroll = true;
        clearTimeout( fScrollTimer );
        fScrollTimer = setTimeout( stepLeft1, fScrollTimout );
    }
}
// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function setOnMouseHover( Index ) {
    var i = Index;
    function func() {
        //console.debug( "hovObj.onover" );
        //clearTimeout( fScrollTimer );
        //fCanScroll = false;
        scrollOnOver();
        if ( i ==  fMiddle )
            imgs[ i ][ IMSGOBJ ].style.display = "block";
    }
    return func;
}
// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function setOnMouseOut( Index ) {
    var i = Index;
    function func() {
        //fCanScroll = true;
        //fScrollTimer = setTimeout( stepLeft1, fScrollTimout );
        //console.debug( "hovObj.onout " );
        scrollOnOut();
        imgs[ i ][ IMSGOBJ ].style.display = "none";
    }
    return func;
}

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------

var fStepWidth;
var fStepProgress;
var fChangeMiddle;
var fPrevMiddle;
var fCoeff1;
var fCoeff12;

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function stepLeft1() {
    if ( fMoving ) {
        if ( fCanScroll )
            scrollOnOut();
        return;
    }
    if ( !fCanScroll )
        return;
    fStepDx = fStepScrollDx;
    fMoving = true;
    i = fMiddle + 1;
    i = ((i < imgs.length)?i:0);
    fStepWidth = imgs[ fMiddle ][ ICONTWIDTH ] + parseInt( imgs[ i ][ ICONTWIDTH ] * fResize / 2 - imgs[ fMiddle ][ ICONTWIDTH ] * fResize / 2 ) + fPicPadding;
    //fStepWidth = fMainContMiddle - imgs[ fMiddle ][ ICONTLEFT ] - parseInt( imgs[ fMiddle ][ ICONTWIDTH ] * fResize / 2 ) - fPicPadding;
    fCoeff1 = imgs[ fMiddle ][ ICONTWIDTH ] * fResize / 2;
    fCoeff2 = imgs[ fMiddle ][ ICONTWIDTH ] - fCoeff1;
    fStepProgress = 0;
    fChangeMiddle = false;
    fMovingTimer = setTimeout( doStep, fStepTimeout );
}

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function stepLeft() {
    if ( fMoving ) return;
    fMoving = true;
    i = fMiddle + 1;
    i = ((i < imgs.length)?i:0);
    fStepWidth = imgs[ fMiddle ][ ICONTWIDTH ] + parseInt( imgs[ i ][ ICONTWIDTH ] * fResize / 2 - imgs[ fMiddle ][ ICONTWIDTH ] * fResize / 2 ) + fPicPadding;
    //fStepWidth = fMainContMiddle - imgs[ fMiddle ][ ICONTLEFT ] - parseInt( imgs[ fMiddle ][ ICONTWIDTH ] * fResize / 2 ) - fPicPadding;
    fCoeff1 = imgs[ fMiddle ][ ICONTWIDTH ] * fResize / 2;
    fCoeff2 = imgs[ fMiddle ][ ICONTWIDTH ] - fCoeff1;
    fStepProgress = 0;
    fChangeMiddle = false;
    fMovingTimer = setTimeout( doStep, fStepTimeout );
}

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function stepRight() {
    if ( fMoving ) return;
    fMoving = true;
    i = fMiddle - 1;
    i = ((i < 0 )?(imgs.length - 1):i);
    fStepWidth = fMainContMiddle - imgs[ i ][ ICONTLEFT ] - parseInt( imgs[ i ][ ICONTWIDTH ] * fResize / 2 ) - fPicPadding;
    fCoeff1 = imgs[ fMiddle ][ ICONTWIDTH ] * fResize / 2;
    fStepProgress = 0;
    fChangeMiddle = false;
    fMovingTimer = setTimeout( doStep1, fStepTimeout );
}

function doStep1() {
    var ss = fStepDx;
    var f = false;
    var p = 0;
    var p1 = 0;
    var p2 = 0;
    var dl = 0;
    var i;
    var e;

    fStepProgress += ss;
    if ( fStepProgress >= fStepWidth ) { ss = fStepProgress - fStepWidth; fStepProgress = fStepWidth; f = true; }
    /*if ( fChangeMiddle )
        if ( ss )*/
    e = i = fFirst;
    while ( true )
    {
        // alert( i );
        //console.debug( i );
        divObj = imgs[ i ][ ICONTOBJ ];
        imgs[ i ][ ICONTLEFT ] += ss - dl;
        if ( imgs[ i ][ ICONTLEFT ] < fMainContWidth )
            divObj.style.left = imgs[ i ][ ICONTLEFT ] + "px";
        if ( fMiddle == i ) {
            p1 = imgs[ i ][ IIMGOBJ ].offsetWidth;
            if ( fChangeMiddle )
                p = 1 - ((fMainContMiddle - imgs[ i ][ ICONTLEFT ]) - fCoeff1) / fCoeff2;
            else
                p = (fMainContMiddle - imgs[ i ][ ICONTLEFT ]) / fCoeff1;
            if ( p < 0 ) p = 0; if ( p > 1 ) p = 1;
            p2 = parseInt( imgs[ i ][ IIMGWIDTH ] * ( 1  + (fResize - 1) * p ) );

            imgs[ i ][ IIMGOBJ ].style.width = p2 + "px";
            divObj.style.top = parseInt(fMainContMarginTop - fMainContMarginTop * p) + "px";
            //imgs[ i ][ IHOVEROBJ ].style.opacity = fMaxPicOpacity * (1 - p);
            setOpacity( imgs[ i ][ IHOVEROBJ ], fMaxPicOpacity * (1 - p) );
            dl = p1 - p2;
        }
// ----------------------------------------------------------------------------------------------------------------------------
        if ( !(((imgs[ i ][ ICONTLEFT ]) <= 0) && ( (imgs[ i ][ ICONTLEFT ] + imgs[ i ][ ICONTWIDTH ]) >= 0 )) && ( fFirst == i ) ) {
            p = fFirst - 1;
            p = ((p < 0 )?(imgs.length - 1):p);
            imgs[ p ][ ICONTLEFT ] = imgs[ i ][ ICONTLEFT ] - imgs[ p ][ ICONTWIDTH ] - fPicPadding - 2;
            //imgs[ p ][ ICONTOBJ ].style.left = imgs[ p ][ ICONTLEFT ] + "px";
            //fFirst = p;
            //console.debug( p, imgs[ i ][ ICONTLEFT ], imgs[ i ][ ICONTLEFT ] + imgs[ i ][ ICONTWIDTH ], i, fFirst );
        }
        if ( (imgs[ i ][ ICONTLEFT ] <= 0) && ( (imgs[ i ][ ICONTLEFT ] + imgs[ i ][ ICONTWIDTH ]) >= 0 ) && ( fFirst != i ) )
            fFirst = i;
        if ( (imgs[ i ][ ICONTLEFT ] <= fMainContMiddle) &&  ( (imgs[ i ][ ICONTLEFT ] + divObj.offsetWidth) >= fMainContMiddle ) && (fMiddle != i) )  {
            if ( !fChangeMiddle ) {
                //imgs[ fMiddle ][ IHOVEROBJ ].style.opacity = fMaxPicOpacity;
                setOpacity( imgs[ fMiddle ][ IHOVEROBJ ], fMaxPicOpacity );
                fPrevMiddle = fMiddle;
                fMiddle = i;

                //console.debug( "fMiddle = " + fMiddle, "; fPrevMiddle = " + fPrevMiddle );

                fCoeff1 = imgs[ fMiddle ][ ICONTWIDTH ] * fResize / 2;
                fCoeff2 = imgs[ fMiddle ][ ICONTWIDTH ] - fCoeff1;
                fChangeMiddle = true;
            }
        }
        //i--; if ( i < 0 ) i = imgs.length - 1; if ( i == e ) break;
        i++; if ( i >= imgs.length ) i = 0; if ( i == e ) break;
    }
    if ( f ) {
        //clearTimeout( fMovingTimer );
        imgObj = imgs[ fMiddle ][ IIMGOBJ ];
        divObj = imgs[ fMiddle ][ ICONTOBJ ];
        divObj.style.top = "0px";
        imgObj.style.width = parseInt( imgs[ fMiddle ][ IIMGWIDTH ] * fResize ) + "px";
        //imgs[ fMiddle ][ IHOVEROBJ ].style.opacity = fMinPicOpacity;
        setOpacity( imgs[ fMiddle ][ IHOVEROBJ ], fMinPicOpacity );
        imgs[ fPrevMiddle ][ ICONTOBJ ].style.top   = fMainContMarginTop + "px";
        imgs[ fPrevMiddle ][ IIMGOBJ ].style.width  = imgs[ fPrevMiddle ][ IIMGWIDTH ] + "px";

        e = i = fFirst;
        l = imgs[ i ][ ICONTOBJ ].offsetLeft;
        while ( true ) {
            l += imgs[ i ][ ICONTOBJ ].offsetWidth + fPicPadding;
            i++; if ( i >= imgs.length ) i = 0; if ( i == e ) break;
            if ( i == fMiddle ) break;
        }

        e = i = fFirst;
        //console.debug( fMainContMiddle - parseInt(l + divObj.offsetWidth / 2) );
        l = imgs[ i ][ ICONTOBJ ].offsetLeft + fMainContMiddle - parseInt(l + divObj.offsetWidth / 2);
        while ( true ) {
            imgs[ i ][ ICONTOBJ ].style.left = l + "px";
            imgs[ i ][ ICONTLEFT ] = l;
            l += imgs[ i ][ ICONTOBJ ].offsetWidth + fPicPadding;
            i++; if ( i >= imgs.length ) i = 0; if ( i == e ) break;
        }

        fMoving = false;
        //console.debug( fMainContMiddle + " # " + (divObj.offsetLeft + divObj.offsetWidth / 2) );
    }
    else fMovingTimer = setTimeout( doStep1, fStepTimeout );
}


// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function doStep() {
    var ss = fStepDx;
    var f = false;
    var p = 0;
    var p1 = 0;
    var p2 = 0;
    var dl = 0;
    var i;
    var e;

    fStepProgress += ss;
    if ( fStepProgress >= fStepWidth ) { ss = fStepProgress - fStepWidth; fStepProgress = fStepWidth; f = true; }
    e = i = fFirst;
    while ( true )
    {
        divObj = imgs[ i ][ ICONTOBJ ];
        imgs[ i ][ ICONTLEFT ] -= ss + dl;
        if ( imgs[ i ][ ICONTLEFT ] < fMainContWidth )
            divObj.style.left = imgs[ i ][ ICONTLEFT ] + "px";
        if ( fMiddle == i ) {
            p1 = imgs[ i ][ IIMGOBJ ].offsetWidth;
            if ( !fChangeMiddle )
                p = 1 - ((fMainContMiddle - imgs[ i ][ ICONTLEFT ]) - fCoeff1) / fCoeff2;
            else
                p = (fMainContMiddle - imgs[ i ][ ICONTLEFT ]) / fCoeff1;
            if ( p < 0 ) p = 0; if ( p > 1 ) p = 1;
            p2 = parseInt( imgs[ i ][ IIMGWIDTH ] * ( 1  + (fResize - 1) * p ) );

            imgs[ i ][ IIMGOBJ ].style.width = p2 + "px";
            divObj.style.top = parseInt(fMainContMarginTop - fMainContMarginTop * p) + "px";
            //imgs[ i ][ IHOVEROBJ ].style.opacity = fMaxPicOpacity * (1 - p);
            setOpacity( imgs[ i ][ IHOVEROBJ ], fMaxPicOpacity * (1 - p) );
            dl = p1 - p2;
        }
// ----------------------------------------------------------------------------------------------------------------------------
        if ( (imgs[ i ][ ICONTLEFT ] <= 0) && ( (imgs[ i ][ ICONTLEFT ] + imgs[ i ][ ICONTWIDTH ]) >= 0 ) && ( fFirst != i ) )
            fFirst = i;
        if ( (imgs[ i ][ ICONTLEFT ] <= fMainContMiddle) &&  ( (imgs[ i ][ ICONTLEFT ] + parseInt(imgs[ i ][ ICONTWIDTH ] / 2) >= fMainContMiddle ) ) ) {
            if ( !fChangeMiddle ) {
                //imgs[ fMiddle ][ IHOVEROBJ ].style.opacity = fMaxPicOpacity;
                setOpacity( imgs[ fMiddle ][ IHOVEROBJ ], fMaxPicOpacity );
                fPrevMiddle = fMiddle;
                fMiddle = i;
                fCoeff1 = imgs[ fMiddle ][ ICONTWIDTH ] * fResize / 2;
                fChangeMiddle = true;
            }
        }
        i++; if ( i >= imgs.length ) i = 0; if ( i == e ) break;
    }
    if ( f ) {
        //clearTimeout( fMovingTimer );
        imgObj = imgs[ fMiddle ][ IIMGOBJ ];
        divObj = imgs[ fMiddle ][ ICONTOBJ ];
        divObj.style.top = "0px";
        imgObj.style.width = parseInt( imgs[ fMiddle ][ IIMGWIDTH ] * fResize ) + "px";
        //imgs[ fMiddle ][ IHOVEROBJ ].style.opacity = fMinPicOpacity;
        setOpacity( imgs[ fMiddle ][ IHOVEROBJ ], fMinPicOpacity );
        imgs[ fPrevMiddle ][ ICONTOBJ ].style.top   = fMainContMarginTop + "px";
        imgs[ fPrevMiddle ][ IIMGOBJ ].style.width  = imgs[ fPrevMiddle ][ IIMGWIDTH ] + "px";

        e = i = fFirst;
        l = imgs[ i ][ ICONTOBJ ].offsetLeft;
        while ( true ) {
            l += imgs[ i ][ ICONTOBJ ].offsetWidth + fPicPadding;
            i++; if ( i >= imgs.length ) i = 0; if ( i == e ) break;
            if ( i == fMiddle ) break;
        }

        e = i = fFirst;
        l = imgs[ i ][ ICONTOBJ ].offsetLeft + fMainContMiddle - parseInt(l + divObj.offsetWidth / 2);
        while ( true ) {
            imgs[ i ][ ICONTOBJ ].style.left = l + "px";
            imgs[ i ][ ICONTLEFT ] = l;
            l += imgs[ i ][ ICONTOBJ ].offsetWidth + fPicPadding;
            i++; if ( i >= imgs.length ) i = 0; if ( i == e ) break;
        }

        fMoving = false;
        if ( fCanScroll )
            scrollOnOut();
        //console.debug( fMainContMiddle + " # " + (divObj.offsetLeft + divObj.offsetWidth / 2) );
        //alert( fMainContMiddle + " # " + (divObj.offsetLeft + divObj.offsetWidth / 2) + " # " + (fMainContMiddle - parseInt(divObj.offsetLeft + divObj.offsetWidth / 2)) + " # fFirst = " + fFirst + " # p1 = " + p1 );
    }
    else fMovingTimer = setTimeout( doStep, fStepTimeout );
}

}
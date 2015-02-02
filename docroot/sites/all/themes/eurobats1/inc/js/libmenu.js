/**
* Menu class definition
*/
function Menus( params ) {
var Self = this;

//var fZItems		= params[ "zItems" ];
var fName           = params[ "Name" ];
var fItems          = params[ "Items" ];
var fimsLayerSample = params[ "imsLayerSample" ];
var fimSample       = params[ "imSample" ];
var fdy             = parseInt( params[ "dy" ] );

var fNesting        = 1; //getNesting( fItems );
var fHTimer;
//alert( fNesting );

var dropLayers = Array();// = document.getElementById( "menuDrop1" );

//var Str = fimsLayerSample.replace( "@IMS_ID@", "123qwe");
//alert ( Str + " # " + fimsLayerSample);

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function getNesting( Items, Nesting ) {
	var maxNesting = 0;
	var tmpNesting;
	if ( typeof Nesting == "undefined" )
		Nesting = 0;
	Nesting++;
	maxNesting = Nesting;
	for ( i = 0; i < Items.length; i++ ) {
		if ( Items[ i ][ 2 ] != null ) {
			tmpNesting = getNesting( Items[ i ][ 2 ], Nesting );
			maxNesting = ((tmpNesting > maxNesting)?tmpNesting:maxNesting);
		}
	}
	return maxNesting;
}
// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
this.writeLayers = function () {
    //LName = fName + "_Drop" +"1";
    //document.write( fimsLayerSample.replace( "@IMS_ID@", LName ) );
    //dropLayer = document.getElementById( LName );
    for ( i = 0; i < fNesting; i++ ) {
        //fimsLayerSample
        LName = fName + "_Drop" + ( i + 1 );
        LName1 = fName + "_Drop1_" + ( i + 1 );
        document.write( fimsLayerSample.replace( "@IMS_ID@", LName ).replace( "@IMSCONT_ID@", LName1 ) );
        dropLayers[ i ] = Array( document.getElementById( LName ), document.getElementById( LName1 ) );
        dropLayers[ i ][ 1 ].onmouseout     = function () { hide(); }
        dropLayers[ i ][ 1 ].onmouseover    = function () { clearTimeout( fHTimer ); }
    }
}

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function setOnMouseOverShow( obj, level, subItemList ) {
    return function () {
        dropLayers[ 0 ][ 1 ].style.display = "none";
        show( obj, level, subItemList );
    }
}

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function setOnClick( page ) {
    return function () {
        location.href = page;
    }
}

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
this.init = function () {
    var lObj;
    var page;
    var subItemList;
    for ( i = 0; i < fItems.length; i++ ) {
        lObj = document.getElementById( fItems[ i ][ 0 ] );
        page = fItems[ i ][ 1 ];
        subItemList = fItems[ i ][ 2 ];

        lObj.onmouseout     = function () {
            hide();
        }

        if ( page != "" )
            lObj.onclick = setOnClick( page );
        else
            lObj.onmouseover = setOnMouseOverShow( lObj, 0, subItemList );
    }
}

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
//this.show = function ( owner, level, index ) {
function show( owner, level, subItemList ) {
    var cl_ims  = dropLayers[ level ][ 0 ];
    var cl_cont = dropLayers[ level ][ 1 ];
    var iName;
    var iObj;

    cl_cont.style.top    = parseInt( getTop( owner ) ) +  owner.offsetHeight + fdy + "px";
    cl_cont.style.left   = parseInt( getLeft( owner ) ) - 2 + "px";
    cl_cont.style.display = "block";

    cl_ims.innerHTML = "";
    for ( i = 0; i < subItemList.length; i++ ) {
        iName = fName + "_Drop_Item_" + i;
        cl_ims.innerHTML += fimSample.replace( "@ITEM_TITLE@", subItemList[ i ][ 0 ] ).replace( "@ITEM_HREF@", subItemList[ i ][ 1 ] ).replace( "@ITEM_ID@",
         iName );
    }
    for ( i = 0; i < subItemList.length; i++ ) {
        iName = fName + "_Drop_Item_" + i;
        iObj = document.getElementById( iName );
        iObj.onmouseover = function () { clearTimeout( fHTimer ); }
    }
    clearTimeout( fHTimer );
}

// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------
function hide() {
    clearTimeout( fHTimer );
    function updateTimer() {
        //Self.hideOnTimer();
        dropLayers[ 0 ][ 1 ].style.display = "none";
    }
    fHTimer = setTimeout( updateTimer, 500 );
}
// ------------------------------------------------------------------------
//
// ------------------------------------------------------------------------


}
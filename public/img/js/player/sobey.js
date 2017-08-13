/**
 * 索贝视频控制
 * source http://113.142.30.196:8060/E-WebTVyszd/media/player/9b155ccf57a24d0db1c30258964d6aa0.js
 */

/*!	SWFObject v2.2 <http://code.google.com/p/swfobject/>
 is released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
 */
window.$S = jQuery;

var swfobject = function () {

    var UNDEF = "undefined",
        OBJECT = "object",
        SHOCKWAVE_FLASH = "Shockwave Flash",
        SHOCKWAVE_FLASH_AX = "ShockwaveFlash.ShockwaveFlash",
        FLASH_MIME_TYPE = "application/x-shockwave-flash",
        EXPRESS_INSTALL_ID = "SWFObjectExprInst",
        ON_READY_STATE_CHANGE = "onreadystatechange",

        win = window,
        doc = document,
        nav = navigator,

        plugin = false,
        domLoadFnArr = [main],
        regObjArr = [],
        objIdArr = [],
        listenersArr = [],
        storedAltContent,
        storedAltContentId,
        storedCallbackFn,
        storedCallbackObj,
        isDomLoaded = false,
        isExpressInstallActive = false,
        dynamicStylesheet,
        dynamicStylesheetMedia,
        autoHideShow = true,

    /* Centralized function for browser feature detection
     - User agent string detection is only used when no good alternative is possible
     - Is executed directly for optimal performance
     */
        ua = function () {
            var w3cdom = typeof doc.getElementById != UNDEF && typeof doc.getElementsByTagName != UNDEF && typeof doc.createElement != UNDEF,
                u = nav.userAgent.toLowerCase(),
                p = nav.platform.toLowerCase(),
                windows = p ? /win/.test(p) : /win/.test(u),
                mac = p ? /mac/.test(p) : /mac/.test(u),
                webkit = /webkit/.test(u) ? parseFloat(u.replace(/^.*webkit\/(\d+(\.\d+)?).*$/, "$1")) : false, // returns either the webkit version or false if not webkit
                ie = ! +"\v1", // feature detection based on Andrea Giammarchi's solution: http://webreflection.blogspot.com/2009/01/32-bytes-to-know-if-your-browser-is-ie.html
                playerVersion = [0, 0, 0],
                d = null;
            if (typeof nav.plugins != UNDEF && typeof nav.plugins[SHOCKWAVE_FLASH] == OBJECT) {
                d = nav.plugins[SHOCKWAVE_FLASH].description;
                if (d && !(typeof nav.mimeTypes != UNDEF && nav.mimeTypes[FLASH_MIME_TYPE] && !nav.mimeTypes[FLASH_MIME_TYPE].enabledPlugin)) { // navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin indicates whether plug-ins are enabled or disabled in Safari 3+
                    plugin = true;
                    ie = false; // cascaded feature detection for Internet Explorer
                    d = d.replace(/^.*\s+(\S+\s+\S+$)/, "$1");
                    playerVersion[0] = parseInt(d.replace(/^(.*)\..*$/, "$1"), 10);
                    playerVersion[1] = parseInt(d.replace(/^.*\.(.*)\s.*$/, "$1"), 10);
                    playerVersion[2] = /[a-zA-Z]/.test(d) ? parseInt(d.replace(/^.*[a-zA-Z]+(.*)$/, "$1"), 10) : 0;
                }
            }
            else if (typeof win.ActiveXObject != UNDEF) {
                try {
                    var a = new ActiveXObject(SHOCKWAVE_FLASH_AX);
                    if (a) { // a will return null when ActiveX is disabled
                        d = a.GetVariable("$version");
                        if (d) {
                            ie = true; // cascaded feature detection for Internet Explorer
                            d = d.split(" ")[1].split(",");
                            playerVersion = [parseInt(d[0], 10), parseInt(d[1], 10), parseInt(d[2], 10)];
                        }
                    }
                }
                catch (e) { }
            }
            return { w3: w3cdom, pv: playerVersion, wk: webkit, ie: ie, win: windows, mac: mac };
        } (),

    /* Cross-browser onDomLoad
     - Will fire an event as soon as the DOM of a web page is loaded
     - Internet Explorer workaround based on Diego Perini's solution: http://javascript.nwbox.com/IEContentLoaded/
     - Regular onload serves as fallback
     */
        onDomLoad = function () {
            if (!ua.w3) { return; }
            if ((typeof doc.readyState != UNDEF && doc.readyState == "complete") || (typeof doc.readyState == UNDEF && (doc.getElementsByTagName("body")[0] || doc.body))) { // function is fired after onload, e.g. when script is inserted dynamically
                callDomLoadFunctions();
            }
            if (!isDomLoaded) {
                if (typeof doc.addEventListener != UNDEF) {
                    doc.addEventListener("DOMContentLoaded", callDomLoadFunctions, false);
                }
                if (ua.ie && ua.win) {
                    doc.attachEvent(ON_READY_STATE_CHANGE, function () {
                        if (doc.readyState == "complete") {
                            doc.detachEvent(ON_READY_STATE_CHANGE, arguments.callee);
                            callDomLoadFunctions();
                        }
                    });
                    if (win == top) { // if not inside an iframe
                        (function () {
                            if (isDomLoaded) { return; }
                            try {
                                doc.documentElement.doScroll("left");
                            }
                            catch (e) {
                                setTimeout(arguments.callee, 0);
                                return;
                            }
                            callDomLoadFunctions();
                        })();
                    }
                }
                if (ua.wk) {
                    (function () {
                        if (isDomLoaded) { return; }
                        if (!/loaded|complete/.test(doc.readyState)) {
                            setTimeout(arguments.callee, 0);
                            return;
                        }
                        callDomLoadFunctions();
                    })();
                }
                addLoadEvent(callDomLoadFunctions);
            }
        } ();

    function callDomLoadFunctions() {
        if (isDomLoaded) { return; }
        try { // test if we can really add/remove elements to/from the DOM; we don't want to fire it too early
            var t = doc.getElementsByTagName("body")[0].appendChild(createElement("span"));
            t.parentNode.removeChild(t);
        }
        catch (e) { return; }
        isDomLoaded = true;
        var dl = domLoadFnArr.length;
        for (var i = 0; i < dl; i++) {
            domLoadFnArr[i]();
        }
    }

    function addDomLoadEvent(fn) {
        if (isDomLoaded) {
            fn();
        }
        else {
            domLoadFnArr[domLoadFnArr.length] = fn; // Array.push() is only available in IE5.5+
        }
    }

    /* Cross-browser onload
     - Based on James Edwards' solution: http://brothercake.com/site/resources/scripts/onload/
     - Will fire an event as soon as a web page including all of its assets are loaded
     */
    function addLoadEvent(fn) {
        if (typeof win.addEventListener != UNDEF) {
            win.addEventListener("load", fn, false);
        }
        else if (typeof doc.addEventListener != UNDEF) {
            doc.addEventListener("load", fn, false);
        }
        else if (typeof win.attachEvent != UNDEF) {
            addListener(win, "onload", fn);
        }
        else if (typeof win.onload == "function") {
            var fnOld = win.onload;
            win.onload = function () {
                fnOld();
                fn();
            };
        }
        else {
            win.onload = fn;
        }
    }

    /* Main function
     - Will preferably execute onDomLoad, otherwise onload (as a fallback)
     */
    function main() {
        if (plugin) {
            testPlayerVersion();
        }
        else {
            matchVersions();
        }
    }

    /* Detect the Flash Player version for non-Internet Explorer browsers
     - Detecting the plug-in version via the object element is more precise than using the plugins collection item's description:
     a. Both release and build numbers can be detected
     b. Avoid wrong descriptions by corrupt installers provided by Adobe
     c. Avoid wrong descriptions by multiple Flash Player entries in the plugin Array, caused by incorrect browser imports
     - Disadvantage of this method is that it depends on the availability of the DOM, while the plugins collection is immediately available
     */
    function testPlayerVersion() {
        var b = doc.getElementsByTagName("body")[0];
        var o = createElement(OBJECT);
        o.setAttribute("type", FLASH_MIME_TYPE);
        var t = b.appendChild(o);
        if (t) {
            var counter = 0;
            (function () {
                if (typeof t.GetVariable != UNDEF) {
                    var d = t.GetVariable("$version");
                    if (d) {
                        d = d.split(" ")[1].split(",");
                        ua.pv = [parseInt(d[0], 10), parseInt(d[1], 10), parseInt(d[2], 10)];
                    }
                }
                else if (counter < 10) {
                    counter++;
                    setTimeout(arguments.callee, 10);
                    return;
                }
                b.removeChild(o);
                t = null;
                matchVersions();
            })();
        }
        else {
            matchVersions();
        }
    }

    /* Perform Flash Player and SWF version matching; static publishing only
     */
    function matchVersions() {
        var rl = regObjArr.length;
        if (rl > 0) {
            for (var i = 0; i < rl; i++) { // for each registered object element
                var id = regObjArr[i].id;
                var cb = regObjArr[i].callbackFn;
                var cbObj = { success: false, id: id };
                if (ua.pv[0] > 0) {
                    var obj = getElementById(id);
                    if (obj) {
                        if (hasPlayerVersion(regObjArr[i].swfVersion) && !(ua.wk && ua.wk < 312)) { // Flash Player version >= published SWF version: Houston, we have a match!
                            setVisibility(id, true);
                            if (cb) {
                                cbObj.success = true;
                                cbObj.ref = getObjectById(id);
                                cb(cbObj);
                            }
                        }
                        else if (regObjArr[i].expressInstall && canExpressInstall()) { // show the Adobe Express Install dialog if set by the web page author and if supported
                            var att = {};
                            att.data = regObjArr[i].expressInstall;
                            att.width = obj.getAttribute("width") || "0";
                            att.height = obj.getAttribute("height") || "0";
                            att.wmode = obj.getAttribute("wmode") || "Opaque";//by zzj
                            if (obj.getAttribute("class")) { att.styleclass = obj.getAttribute("class"); }
                            if (obj.getAttribute("align")) { att.align = obj.getAttribute("align"); }
                            // parse HTML object param element's name-value pairs
                            var par = {};
                            var p = obj.getElementsByTagName("param");
                            var pl = p.length;
                            for (var j = 0; j < pl; j++) {
                                if (p[j].getAttribute("name").toLowerCase() != "movie") {
                                    par[p[j].getAttribute("name")] = p[j].getAttribute("value");
                                }
                            }
                            showExpressInstall(att, par, id, cb);
                        }
                        else { // Flash Player and SWF version mismatch or an older Webkit engine that ignores the HTML object element's nested param elements: display alternative content instead of SWF
                            displayAltContent(obj);
                            if (cb) { cb(cbObj); }
                        }
                    }
                }
                else {	// if no Flash Player is installed or the fp version cannot be detected we let the HTML object element do its job (either show a SWF or alternative content)
                    setVisibility(id, true);
                    if (cb) {
                        var o = getObjectById(id); // test whether there is an HTML object element or not
                        if (o && typeof o.SetVariable != UNDEF) {
                            cbObj.success = true;
                            cbObj.ref = o;
                        }
                        cb(cbObj);
                    }
                }
            }
        }
    }

    function getObjectById(objectIdStr) {
        var r = null;
        var o = getElementById(objectIdStr);
        if (o && o.nodeName == "OBJECT") {
            if (typeof o.SetVariable != UNDEF) {
                r = o;
            }
            else {
                var n = o.getElementsByTagName(OBJECT)[0];
                if (n) {
                    r = n;
                }
            }
        }
        return r;
    }

    /* Requirements for Adobe Express Install
     - only one instance can be active at a time
     - fp 6.0.65 or higher
     - Win/Mac OS only
     - no Webkit engines older than version 312
     */
    function canExpressInstall() {
        return !isExpressInstallActive && hasPlayerVersion("6.0.65") && (ua.win || ua.mac) && !(ua.wk && ua.wk < 312);
    }

    /* Show the Adobe Express Install dialog
     - Reference: http://www.adobe.com/cfusion/knowledgebase/index.cfm?id=6a253b75
     */
    function showExpressInstall(att, par, replaceElemIdStr, callbackFn) {
        isExpressInstallActive = true;
        storedCallbackFn = callbackFn || null;
        storedCallbackObj = { success: false, id: replaceElemIdStr };
        var obj = getElementById(replaceElemIdStr);
        if (obj) {
            if (obj.nodeName == "OBJECT") { // static publishing
                storedAltContent = abstractAltContent(obj);
                storedAltContentId = null;
            }
            else { // dynamic publishing
                storedAltContent = obj;
                storedAltContentId = replaceElemIdStr;
            }
            att.id = EXPRESS_INSTALL_ID;
            if (typeof att.width == UNDEF || (!/%$/.test(att.width) && parseInt(att.width, 10) < 310)) { att.width = "310"; }
            if (typeof att.height == UNDEF || (!/%$/.test(att.height) && parseInt(att.height, 10) < 137)) { att.height = "137"; }
            doc.title = doc.title.slice(0, 47) + " - Flash Player Installation";
            var pt = ua.ie && ua.win ? "ActiveX" : "PlugIn",
                fv = "MMredirectURL=" + encodeURI(window.location).toString().replace(/&/g, "%26") + "&MMplayerType=" + pt + "&MMdoctitle=" + doc.title;
            if (typeof par.flashvars != UNDEF) {
                par.flashvars += "&" + fv;
            }
            else {
                par.flashvars = fv;
            }
            // IE only: when a SWF is loading (AND: not available in cache) wait for the readyState of the object element to become 4 before removing it,
            // because you cannot properly cancel a loading SWF file without breaking browser load references, also obj.onreadystatechange doesn't work
            if (ua.ie && ua.win && obj.readyState != 4) {
                var newObj = createElement("div");
                replaceElemIdStr += "SWFObjectNew";
                newObj.setAttribute("id", replaceElemIdStr);
                obj.parentNode.insertBefore(newObj, obj); // insert placeholder div that will be replaced by the object element that loads expressinstall.swf
                obj.style.display = "none";
                (function () {
                    if (obj.readyState == 4) {
                        obj.parentNode.removeChild(obj);
                    }
                    else {
                        setTimeout(arguments.callee, 10);
                    }
                })();
            }
            createSWF(att, par, replaceElemIdStr);
        }
    }

    /* Functions to abstract and display alternative content
     */
    function displayAltContent(obj) {
        if (ua.ie && ua.win && obj.readyState != 4) {
            // IE only: when a SWF is loading (AND: not available in cache) wait for the readyState of the object element to become 4 before removing it,
            // because you cannot properly cancel a loading SWF file without breaking browser load references, also obj.onreadystatechange doesn't work
            var el = createElement("div");
            obj.parentNode.insertBefore(el, obj); // insert placeholder div that will be replaced by the alternative content
            el.parentNode.replaceChild(abstractAltContent(obj), el);
            obj.style.display = "none";
            (function () {
                if (obj.readyState == 4) {
                    obj.parentNode.removeChild(obj);
                }
                else {
                    setTimeout(arguments.callee, 10);
                }
            })();
        }
        else {
            obj.parentNode.replaceChild(abstractAltContent(obj), obj);
        }
    }

    function abstractAltContent(obj) {
        var ac = createElement("div");
        if (ua.win && ua.ie) {
            ac.innerHTML = obj.innerHTML;
        }
        else {
            var nestedObj = obj.getElementsByTagName(OBJECT)[0];
            if (nestedObj) {
                var c = nestedObj.childNodes;
                if (c) {
                    var cl = c.length;
                    for (var i = 0; i < cl; i++) {
                        if (!(c[i].nodeType == 1 && c[i].nodeName == "PARAM") && !(c[i].nodeType == 8)) {
                            ac.appendChild(c[i].cloneNode(true));
                        }
                    }
                }
            }
        }
        return ac;
    }

    /* Cross-browser dynamic SWF creation
     */
    function createSWF(attObj, parObj, id) {
        var r, el = getElementById(id);
        if (ua.wk && ua.wk < 312) { return r; }
        if (el) {
            if (typeof attObj.id == UNDEF) { // if no 'id' is defined for the object element, it will inherit the 'id' from the alternative content
                attObj.id = id;
            }
            if (ua.ie && ua.win) { // Internet Explorer + the HTML object element + W3C DOM methods do not combine: fall back to outerHTML
                var att = "";
                for (var i in attObj) {
                    if (attObj[i] != Object.prototype[i]) { // filter out prototype additions from other potential libraries
                        if (i.toLowerCase() == "data") {
                            parObj.movie = attObj[i];
                        }
                        else if (i.toLowerCase() == "styleclass") { // 'class' is an ECMA4 reserved keyword
                            att += ' class="' + attObj[i] + '"';
                        }
                        else if (i.toLowerCase() != "classid") {
                            att += ' ' + i + '="' + attObj[i] + '"';
                        }
                    }
                }
                var par = "";
                for (var j in parObj) {
                    if (parObj[j] != Object.prototype[j]) { // filter out prototype additions from other potential libraries
                        par += '<param name="' + j + '" value="' + parObj[j] + '" />';
                    }
                }
                el.outerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"' + att + '>' + par + '</object>';
                objIdArr[objIdArr.length] = attObj.id; // stored to fix object 'leaks' on unload (dynamic publishing only)
                r = getElementById(attObj.id);
            }
            else { // well-behaving browsers
                var o = createElement(OBJECT);
                o.setAttribute("type", FLASH_MIME_TYPE);
                for (var m in attObj) {
                    if (attObj[m] != Object.prototype[m]) { // filter out prototype additions from other potential libraries
                        if (m.toLowerCase() == "styleclass") { // 'class' is an ECMA4 reserved keyword
                            o.setAttribute("class", attObj[m]);
                        }
                        else if (m.toLowerCase() != "classid") { // filter out IE specific attribute
                            o.setAttribute(m, attObj[m]);
                        }
                    }
                }
                for (var n in parObj) {
                    if (parObj[n] != Object.prototype[n] && n.toLowerCase() != "movie") { // filter out prototype additions from other potential libraries and IE specific param element
                        createObjParam(o, n, parObj[n]);
                    }
                }
                el.parentNode.replaceChild(o, el);
                r = o;
            }
        }
        return r;
    }

    function createObjParam(el, pName, pValue) {
        var p = createElement("param");
        p.setAttribute("name", pName);
        p.setAttribute("value", pValue);
        el.appendChild(p);
    }

    /* Cross-browser SWF removal
     - Especially needed to safely and completely remove a SWF in Internet Explorer
     */
    function removeSWF(id) {
        var obj = getElementById(id);
        if (obj && obj.nodeName == "OBJECT") {
            if (ua.ie && ua.win) {
                obj.style.display = "none";
                (function () {
                    if (obj.readyState == 4) {
                        removeObjectInIE(id);
                    }
                    else {
                        setTimeout(arguments.callee, 10);
                    }
                })();
            }
            else {
                obj.parentNode.removeChild(obj);
            }
        }
    }

    function removeObjectInIE(id) {
        var obj = getElementById(id);
        if (obj) {
            for (var i in obj) {
                if (typeof obj[i] == "function") {
                    obj[i] = null;
                }
            }
            obj.parentNode.removeChild(obj);
        }
    }

    /* Functions to optimize JavaScript compression
     */
    function getElementById(id) {
        var el = null;
        try {
            el = doc.getElementById(id);
        }
        catch (e) { }
        return el;
    }

    function createElement(el) {
        return doc.createElement(el);
    }

    /* Updated attachEvent function for Internet Explorer
     - Stores attachEvent information in an Array, so on unload the detachEvent functions can be called to avoid memory leaks
     */
    function addListener(target, eventType, fn) {
        target.attachEvent(eventType, fn);
        listenersArr[listenersArr.length] = [target, eventType, fn];
    }

    /* Flash Player and SWF content version matching
     */
    function hasPlayerVersion(rv) {
        var pv = ua.pv, v = rv.split(".");
        v[0] = parseInt(v[0], 10);
        v[1] = parseInt(v[1], 10) || 0; // supports short notation, e.g. "9" instead of "9.0.0"
        v[2] = parseInt(v[2], 10) || 0;
        return (pv[0] > v[0] || (pv[0] == v[0] && pv[1] > v[1]) || (pv[0] == v[0] && pv[1] == v[1] && pv[2] >= v[2])) ? true : false;
    }

    /* Cross-browser dynamic CSS creation
     - Based on Bobby van der Sluis' solution: http://www.bobbyvandersluis.com/articles/dynamicCSS.php
     */
    function createCSS(sel, decl, media, newStyle) {
        if (ua.ie && ua.mac) { return; }
        var h = doc.getElementsByTagName("head")[0];
        if (!h) { return; } // to also support badly authored HTML pages that lack a head element
        var m = (media && typeof media == "string") ? media : "screen";
        if (newStyle) {
            dynamicStylesheet = null;
            dynamicStylesheetMedia = null;
        }
        if (!dynamicStylesheet || dynamicStylesheetMedia != m) {
            // create dynamic stylesheet + get a global reference to it
            var s = createElement("style");
            s.setAttribute("type", "text/css");
            s.setAttribute("media", m);
            dynamicStylesheet = h.appendChild(s);
            if (ua.ie && ua.win && typeof doc.styleSheets != UNDEF && doc.styleSheets.length > 0) {
                dynamicStylesheet = doc.styleSheets[doc.styleSheets.length - 1];
            }
            dynamicStylesheetMedia = m;
        }
        // add style rule
        if (ua.ie && ua.win) {
            if (dynamicStylesheet && typeof dynamicStylesheet.addRule == OBJECT) {
                //dynamicStylesheet.addRule(sel, decl);
                $S(sel).attr("style",$(this).attr("style")+decl);
            }
        }
        else {
            if (dynamicStylesheet && typeof doc.createTextNode != UNDEF) {
                dynamicStylesheet.appendChild(doc.createTextNode(sel + " {" + decl + "}"));
            }
        }
    }

    function setVisibility(id, isVisible) {
        if (!autoHideShow) { return; }
        var v = isVisible ? "visible" : "hidden";
        if (isDomLoaded && getElementById(id)) {
            getElementById(id).style.visibility = v;
        }
        else {
            createCSS("#" + id, "visibility:" + v);
        }
    }

    /* Filter to avoid XSS attacks
     */
    function urlEncodeIfNecessary(s) {
        var regex = /[\\\"<>\.;]/;
        var hasBadChars = regex.exec(s) != null;
        return hasBadChars && typeof encodeURIComponent != UNDEF ? encodeURIComponent(s) : s;
    }

    /* Release memory to avoid memory leaks caused by closures, fix hanging audio/video threads and force open sockets/NetConnections to disconnect (Internet Explorer only)
     */
    var cleanup = function () {
        if (ua.ie && ua.win) {
            window.attachEvent("onunload", function () {
                // remove listeners to avoid memory leaks
                var ll = listenersArr.length;
                for (var i = 0; i < ll; i++) {
                    listenersArr[i][0].detachEvent(listenersArr[i][1], listenersArr[i][2]);
                }
                // cleanup dynamically embedded objects to fix audio/video threads and force open sockets and NetConnections to disconnect
                var il = objIdArr.length;
                for (var j = 0; j < il; j++) {
                    removeSWF(objIdArr[j]);
                }
                // cleanup library's main closures to avoid memory leaks
                for (var k in ua) {
                    ua[k] = null;
                }
                ua = null;
                for (var l in swfobject) {
                    swfobject[l] = null;
                }
                swfobject = null;
            });
        }
    } ();

    return {
        /* Public API
         - Reference: http://code.google.com/p/swfobject/wiki/documentation
         */
        registerObject: function (objectIdStr, swfVersionStr, xiSwfUrlStr, callbackFn) {
            if (ua.w3 && objectIdStr && swfVersionStr) {
                var regObj = {};
                regObj.id = objectIdStr;
                regObj.swfVersion = swfVersionStr;
                regObj.expressInstall = xiSwfUrlStr;
                regObj.callbackFn = callbackFn;
                regObjArr[regObjArr.length] = regObj;
                setVisibility(objectIdStr, false);
            }
            else if (callbackFn) {
                callbackFn({ success: false, id: objectIdStr });
            }
        },

        getObjectById: function (objectIdStr) {
            if (ua.w3) {
                return getObjectById(objectIdStr);
            }
        },

        embedSWF: function (swfUrlStr, replaceElemIdStr, widthStr, heightStr, wmodeStr/*by zzj*/, swfVersionStr, xiSwfUrlStr, flashvarsObj, parObj, attObj, callbackFn) {
            var callbackObj = { success: false, id: replaceElemIdStr };
            if (ua.w3 && !(ua.wk && ua.wk < 312) && swfUrlStr && replaceElemIdStr && widthStr && heightStr && swfVersionStr) {
                setVisibility(replaceElemIdStr, false);
                addDomLoadEvent(function () {
                    widthStr += ""; // auto-convert to string
                    heightStr += "";
                    var att = {};
                    if (attObj && typeof attObj === OBJECT) {
                        for (var i in attObj) { // copy object to avoid the use of references, because web authors often reuse attObj for multiple SWFs
                            att[i] = attObj[i];
                        }
                    }
                    att.data = swfUrlStr;
                    att.width = widthStr;
                    att.height = heightStr;
                    att.wmode = wmodeStr; //by zzj
                    var par = {};
                    if (parObj && typeof parObj === OBJECT) {
                        for (var j in parObj) { // copy object to avoid the use of references, because web authors often reuse parObj for multiple SWFs
                            par[j] = parObj[j];
                        }
                    }
                    if (flashvarsObj && typeof flashvarsObj === OBJECT) {
                        for (var k in flashvarsObj) { // copy object to avoid the use of references, because web authors often reuse flashvarsObj for multiple SWFs
                            if (typeof par.flashvars != UNDEF) {
                                par.flashvars += "&" + k + "=" + flashvarsObj[k];
                            }
                            else {
                                par.flashvars = k + "=" + flashvarsObj[k];
                            }
                        }
                    }
                    if (hasPlayerVersion(swfVersionStr)) { // create SWF
                        var obj = createSWF(att, par, replaceElemIdStr);
                        if (att.id == replaceElemIdStr) {
                            setVisibility(replaceElemIdStr, true);
                        }
                        callbackObj.success = true;
                        callbackObj.ref = obj;
                    }
                    else if (xiSwfUrlStr && canExpressInstall()) { // show Adobe Express Install
                        att.data = xiSwfUrlStr;
                        showExpressInstall(att, par, replaceElemIdStr, callbackFn);
                        return;
                    }
                    else { // show alternative content
                        setVisibility(replaceElemIdStr, true);
                    }
                    if (callbackFn) { callbackFn(callbackObj); }
                });
            }
            else if (callbackFn) { callbackFn(callbackObj); }
        },

        switchOffAutoHideShow: function () {
            autoHideShow = false;
        },

        ua: ua,

        getFlashPlayerVersion: function () {
            return { major: ua.pv[0], minor: ua.pv[1], release: ua.pv[2] };
        },

        hasFlashPlayerVersion: hasPlayerVersion,

        createSWF: function (attObj, parObj, replaceElemIdStr) {
            if (ua.w3) {
                return createSWF(attObj, parObj, replaceElemIdStr);
            }
            else {
                return undefined;
            }
        },

        showExpressInstall: function (att, par, replaceElemIdStr, callbackFn) {
            if (ua.w3 && canExpressInstall()) {
                showExpressInstall(att, par, replaceElemIdStr, callbackFn);
            }
        },

        removeSWF: function (objElemIdStr) {
            if (ua.w3) {
                removeSWF(objElemIdStr);
            }
        },

        createCSS: function (selStr, declStr, mediaStr, newStyleBoolean) {
            if (ua.w3) {
                createCSS(selStr, declStr, mediaStr, newStyleBoolean);
            }
        },

        addDomLoadEvent: addDomLoadEvent,

        addLoadEvent: addLoadEvent,

        getQueryParamValue: function (param) {
            var q = doc.location.search || doc.location.hash;
            if (q) {
                if (/\?/.test(q)) { q = q.split("?")[1]; } // strip question mark
                if (param == null) {
                    return urlEncodeIfNecessary(q);
                }
                var pairs = q.split("&");
                for (var i = 0; i < pairs.length; i++) {
                    if (pairs[i].substring(0, pairs[i].indexOf("=")) == param) {
                        return urlEncodeIfNecessary(pairs[i].substring((pairs[i].indexOf("=") + 1)));
                    }
                }
            }
            return "";
        },

        // For internal usage only
        expressInstallCallback: function () {
            if (isExpressInstallActive) {
                var obj = getElementById(EXPRESS_INSTALL_ID);
                if (obj && storedAltContent) {
                    obj.parentNode.replaceChild(storedAltContent, obj);
                    if (storedAltContentId) {
                        setVisibility(storedAltContentId, true);
                        if (ua.ie && ua.win) { storedAltContent.style.display = "block"; }
                    }
                    if (storedCallbackFn) { storedCallbackFn(storedCallbackObj); }
                }
                isExpressInstallActive = false;
            }
        }
    };
} ();

var title="document.title";
var site="document.location.href";
function picShare(target,pic,w,h){
    var url=createShareUrl2(target,pic,w,h,title,site);
    if(url){
        window.open(url,"_blank");
    }
}

function createShareUrl2(target,pic,w,h,title,site){
    var funName="createShareUrl_"+target;
    try{
        var fun=eval(funName);
        if(typeof (fun) == "function"){
            return fun(pic,w,h,title,site);
        }
        else{
            return null;
        }
    }
    catch(e){
        return null;
    }
};
function createShareUrl_weibo(pic,w,h,title,site){
    var param = {
        url:location.href,
        type:'3',
        count:'', /**是否显示分享数，1显示(可选)*/
        title:title, /**分享的文字内容(可选，默认为所在页面的title)*/
        pic:pic, /**分享图片的路径(可选)*/
        rnd:new Date().valueOf()
    }
    var temp = [];
    for( var p in param ){
        temp.push(p + '=' + encodeURIComponent( param[p] || '' ) )
    }
    return "http://service.weibo.com/share/share.php?"+temp.join("&");
}
function createShareUrl_pengyou(pic,w,h,title,site){
    var p = {
        url:location.href,
        to:'pengyou',
        desc:'',/*默认分享理由(可选)*/
        summary:'',/*摘要(可选)*/
        title:title,/*分享标题(可选)*/
        site:site,/*分享来源 如：腾讯网(可选)*/
        pics:pic /*分享图片的路径(可选)*/
    };
    var s = [];
    for(var i in p){
        s.push(i + '=' + encodeURIComponent(p[i]||''));
    }
    return "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?"+s.join("&");
}
function createShareUrl_qzone(pic,w,h,title,site){
    var p = {
        url:location.href,
        showcount:'1',/*是否显示分享总数,显示：'1'，不显示：'0' */
        desc:'',/*默认分享理由(可选)*/
        summary:'',/*分享摘要(可选)*/
        title:title,/*分享标题(可选)*/
        site:site,/*分享来源 如：腾讯网(可选)*/
        pics:pic, /*分享图片的路径(可选)*/
        style:'203',
        width:w,
        height:h
    };
    var s = [];
    for(var i in p){
        s.push(i + '=' + encodeURIComponent(p[i]||''));
    }
    return "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?"+s.join("&");
}
function getOffsideboxData(){
    var rem="{'iconBase':'http://113.142.30.196:8060/E-WebTVyszd/media/player/skins/yellow','items':[";
    rem+="{'id':'r2','title':'画质','type':'picture','icon':'huazhi1.png','hoverIcon':'huazhi2.png'},";
    rem+="{'id':'r1','title':'宽屏','type':'callback','content':'widdenwindow','icon':'kuanping1.png','hoverIcon':'kuanping2.png'},";
    rem+="{'id':'r3','title':'关灯','type':'callback','content':'closewindow','icon':'kaideng1.png','hoverIcon':'kaideng2.png'},";
    rem+="{'id':'r4','title':'分享','type':'share','content':'getSharingData','icon':'fenxiang1.png','hoverIcon':'fenxiang2.png'}";
    rem+="]}";
    return rem;
}

var videoUrl = '';

function getSharingData(){
    var url = 'http://113.142.30.196:8060/E-WebTVyszd/media/player/SoPlayer.swf?url='+ videoUrl +'&host=http://vmsbus.sobeycache.com:8080/vmsbus2/JSONReceiver&plugin=true&logging=true&encrypt=false&configable=true&streamType=slicedMedia&skin=http://113.142.30.196:8060/E-WebTVyszd/media/player/skins/yellow.swf&isLive=1';

    var html = '<embed width="660" height="600" align="middle" type="application/x-shockwave-flash" allowscriptaccess="always" quality="high"  allowfullscreen="true" src="http://113.142.30.196:8060/E-WebTVyszd/media/player/SoPlayer.swf?url='+videoUrl+'&host=http://vmsbus.sobeycache.com:8080/vmsbus2/JSONReceiver&streamType=slicedMedia&autoLoad=true&allowScriptAccess=always&mode=transparent&logging=true" id="player1">';
    var sh="[";
    sh+="{'title':'页面分享','content':'"+document.URL+"'},";
    sh+="{'title':'视频分享','content':'"+url+"'},";
    sh+="{'title':'html分享','content':'"+html+"'}";
    sh+="]";
    return sh;
}
//(1)分享图标设置
function getshareIcon()
{
    var sh="[";
    sh+="{'icon':'http://113.142.30.196:8060/E-WebTVyszd/media/player/skins/icon1.png','id':'sh01'},";
    sh+="{'icon':'http://113.142.30.196:8060/E-WebTVyszd/media/player/skins/icon2.png','id':'sh02'},";
    sh+="{'icon':'http://113.142.30.196:8060/E-WebTVyszd/media/player/skins/icon3.png','id':'sh03'},";
    sh+="{'icon':'http://113.142.30.196:8060/E-WebTVyszd/media/player/skins/icon4.png','id':'sh04'},";
    sh+="{'icon':'http://113.142.30.196:8060/E-WebTVyszd/media/player/skins/icon5.png','id':'sh05'},";
    sh+="{'icon':'http://113.142.30.196:8060/E-WebTVyszd/media/player/skins/icon6.png','id':'sh06'}";
    sh+="]";
    return sh;
}
function postToWb(){
    var _t = encodeURI(document.title);
    var _url = encodeURIComponent(document.location);
    var _appkey = '';//encodeURI("9a2f3619c68e4cf9b7a4449ffa990e53");//你从腾讯获得的appkey
    var _pic = encodeURI('');//（例如：var _pic='图片url1|图片url2|图片url3....）
    var _site = 'http://www.iqilu.com';
    var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic;
    window.open( _u,'转播到腾讯微博', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );
}
//(2)分享图标点击事件
function onshareclick(id)
{
    if(id=="sh01"){
        window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+encodeURIComponent(document.location.href));
    }else if(id=="sh02"){
        (function(s,d,e){var f='http://share.renren.com/share/buttonshare?link=',u=location.href,l='',p=[e(u),'&title=',e(l)].join('');function a(){if(!window.open([f,p].join(''),'xnshare',['toolbar=0,status=0,resizable=1,width=626,height=436,left=',(s.width-626)/2,',top=',(s.height-436)/2].join('')))u.href=[f,p].join('')};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else a()})(screen,document,encodeURIComponent);
    }else if(id=="sh03"){
        window.open('http://service.t.sina.com.cn/share/share.php?url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)+'sobeycloud','_blank','width=615,height=505');
    }else if(id=="sh04"){
        window.open('http://www.kaixin001.com/repaste/bshare.php?rtitle='+encodeURIComponent(document.title)+'&rurl='+encodeURIComponent(location.href)+'','_blank','scrollbars=yes,status=yes,resizable=yes,width=960,height=630');
    }else if(id=="sh05"){
        window.open("http://www.tianya.cn/new/share/compose.asp?strFlashPageURL="+encodeURIComponent(location.href)+"&strTitle="+encodeURIComponent(document.title)+"&item=402");
    }else if(id=="sh06"){
        postToWb();
    }
}

function onInteractEvent(id,data){
    if(id=="r4")
        return getSharingData();
    else
        setTimeout(alertin,10,id);
}

function getPlugins(){
    return '[' +
        '{"source":"http://113.142.30.196:8060/E-WebTVyszd/media/player/plugins/EpisodeCmsTopPlugin.swf"},' +
        '{"source":"http://113.142.30.196:8060/E-WebTVyszd/media/player/plugins/AdCmsTopPlugin.swf","host":"'+APP_URL+'?app=video&controller=video&action=output_ads&apitype=sobey","rc":"1","blockLoading":"true","blockPlaying":"true"},' +
        '{"source":"http://113.142.30.196:8060/E-WebTVyszd/media/player/plugins/RecCmsTopPlugin.swf","host":"'+APP_URL+'?app=video&controller=video&action=output_recommend&contentid='+contentid+'&apitype=sobey"},' +
        '{"source":"http://113.142.30.196:8060/E-WebTVyszd/media/player/plugins/SharePlugin.swf"},' +
        '{"source":"http://113.142.30.196:8060/E-WebTVyszd/media/player/plugins/QualitySettingPlugin.swf"},' +
        '{"source":"http://113.142.30.196:8060/E-WebTVyszd/media/player/plugins/yellowplugins/ScreenshotPlugin.swf"},' +
        '{"source":"http://113.142.30.196:8060/E-WebTVyszd/media/player/plugins/yellowplugins/MultiratePlugin.swf"},' +
        '{"source":"http://113.142.30.196:8060/E-WebTVyszd/media/player/plugins/InteractingPlugin.swf"},' +
        '{"source":"http://113.142.30.196:8060/E-WebTVyszd/media/player/UrlEncryptorProvider.swf"}' +
        ']';
}

//判断浏览器
function userBrowser(){
    var browserName=navigator.userAgent.toLowerCase();

    if(/msie/i.test(browserName) && !/opera/.test(browserName)){
        return "IE";
    }else if(/firefox/i.test(browserName)){
        return "Firefox";
    }else if(/chrome/i.test(browserName) && /webkit/i.test(browserName) && /mozilla/i.test(browserName)){
        return "Chrome";
    }else if(/opera/i.test(browserName)){
        return "Opera";
    }else if(/iphone|ipad|ipod/i.test(browserName)){
        return "iphone";
    }else if(/webkit/i.test(browserName) &&!(/chrome/i.test(browserName) && /webkit/i.test(browserName) && /mozilla/i.test(browserName))){
        return "Safari";
    }else if(browserName.match(/android/i) == "android" ){
        return "android";
    }else{
        return "unKnow";
    }
}


//html5播放器输出
function createVodPlayer4IOS(url,width,height){
    width = width || 530;
    height = height || 400;
    url = url.substring(12);
    var data = {
        'parameter':'{"videoId":"'+url+'"}',
        'method':'getVideoUrl'
    };

    $S.ajax({
        type:"POST",
        dataType: 'jsonp',
        jsonp: 'jsoncallback', //默认callback
        data:data,
        url:"http://vmsbus.sobeycache.com:8080/vmsbus2/JSONReceiver",

        success:function(data) {

            var videoUrl = '' ;
            //多码率分片格式包含duration
            if(typeof(data.length) == 'undefined') {
                videoUrl = data.host + data.clips[0].urls[0];
            } else {
                videoUrl = data[0].url;
            }

            var videoHtml = '<video preload="preload" id="videoPlayer" controls="controls" width="'+width+'" height="'+height+'"><source id="videoPlayerSource" src="'+videoUrl+'" type="video/mp4"></video>';

            try {
                document.getElementById("9b155ccf57a24d0db1c30258964d6aa0").innerHTML = videoHtml;
            }
            catch(err){
                if(document.getElementById("iphone")){
                    document.getElementById("iphone").innerHTML = videoHtml;
                }
            }

        },
        error:function(msg) {
            alert(msg);
        }
    });
}

function changeVideoSrc(url) {
    $S("#videoPlayerSource").attr("src",url);
}

function createPlayer(url,width,height) {
    if (userBrowser()=='iphone' || userBrowser()== 'unKnow' ){
        $S(document).ready(function(){
            createVodPlayer4IOS(url,width,height);
        });
    } else {
        createVodPlayer(url,width,height);
    }
}

function createVodPlayer(url,width,height){
    videoUrl = url;
    width = width || 530;
    height = height || 400;
    var swfVersionStr = "10.2.0";

    var xiSwfUrlStr = "playerProductInstall.swf";
    var flashvars = {};

    flashvars.url= url;
    flashvars.host= "http://vmsbus.sobeycache.com:8080/vmsbus2/JSONReceiver";
    flashvars.mode= "letterbox";
    flashvars.autoPlay=false;
    flashvars.plugin=true;
    flashvars.logging=true;
    flashvars.encrypt=false;
    flashvars.configable=true;//是否可配置(画面显示等配置)
    flashvars.streamType="slicedMedia";
    flashvars.skin="http://113.142.30.196:8060/E-WebTVyszd/media/player/skins/yellow.swf";
    flashvars.seekParam="timecode=ms";
    flashvars.isLive = 1;
    var params = {};
    params.quality = "high";
    params.bgcolor = "#7d7d7d";
    params.allowscriptaccess = "always";
    params.allowfullscreen = "true";
    params.wmode = "Opaque";
    var attributes = {};
    attributes.id = "MyVideoPlayer";
    attributes.name = "MyVideoPlayer";
    attributes.style="position:relative;z-index:2000;"
    attributes.align = "middle";
    swfobject.embedSWF(
        "http://113.142.30.196:8060/E-WebTVyszd/media/player/SoPlayer.swf", "9b155ccf57a24d0db1c30258964d6aa0",
        width, height,  "Opaque",
        swfVersionStr, xiSwfUrlStr,
        flashvars, params, attributes);

    swfobject.createCSS("#9b155ccf57a24d0db1c30258964d6aa0", "display:block;text-align:left;");

}

function createLivePlayer(siteid,dimension){

    if (userBrowser()=='iphone' || userBrowser()== 'unKnow' ){
        $S(document).ready(function(){
            dimension='iphone';
            createLiveMainPlayer4IOS(dimension,siteid);
            createLivePanel4IOS(dimension,siteid);
        });
    } else if(userBrowser()=='android') {
        dimension = "android";
        $S(document).ready(function(){
            createLiveMainPlayer4IOS(dimension,siteid);
            createLivePanel4IOS(dimension,siteid);
        });
    } else {

        var data = {
            'parameter':'{"Dimension":"'+dimension+'","SiteId":"'+siteid+'"}',
            'method':'ListChannels'
        };

        $S.ajax({
            type:"POST",
            dataType: 'jsonp',
            jsonp: 'jsoncallback', //默认callback
            data:data,
            url:"http://vmsbus.sobeycache.com:8080/vmsbus2/JSONReceiver",
            success:function(data) {

                var address = data[0].C_Address;

                createLiveMainPlayer4PC("mr://j:" +address);
                createLivePanel4PC(siteid,dimension);

            },
            error:function(msg) {
                alert(msg);
            }
        });
    }
}

function createLiveMainPlayer4IOS(dimension,siteid){
    dimension='iphone';
    var data = {
        'parameter':'{"Dimension":"'+dimension+'","SiteId":"'+siteid+'"}',
        'method':'ListChannels'
    };


    $S.ajax({
        type:"POST",
        dataType: 'jsonp',
        jsonp: 'jsoncallback', //默认callback
        data:data,
        url:"http://vmsbus.sobeycache.com:8080/vmsbus2/JSONReceiver",
        success:function(data) {

            //data = eval("(" +data+ ")");
            var address = '' ;
            if(data[0]){
                address = data[0].C_Address;
            }
            var videoHtml = '<video preload="preload" id="videoPlayer" controls="controls" width="650" height="485"><source id="videoPlayerSource" src="'+address+'" type="video/mp4"></video>';

            try {
                document.getElementById("9b155ccf57a24d0db1c30258964d6aa0").innerHTML = videoHtml;
            }
            catch(err){
                if(document.getElementById("iphone")){
                    document.getElementById("iphone").innerHTML = videoHtml;
                }
            }

        },
        error:function(msg) {
            alert(msg);
        }
    });



}

function createLiveMainPlayer4PC(url){
    var swfVersionStr = "10.2.0";

    var xiSwfUrlStr = "playerProductInstall.swf";
    var flashvars = {};

    flashvars.url= url;
    flashvars.host= "http://vmsbus.sobeycache.com:8080/vmsbus2/JSONReceiver";
    flashvars.mode= "letterbox";
    flashvars.autoPlay=false;
    flashvars.plugin=true;
    flashvars.logging=true;
    flashvars.encrypt=false;
    flashvars.configable=true;//是否可配置(画面显示等配置)
    flashvars.streamType="p2pLiveS";
    flashvars.skin="http://113.142.30.196:8060/E-WebTVyszd/media/player/skins/yellow.swf";
    flashvars.seekParam="shifttime=ms";
    flashvars.isLive = 1;
    var params = {};
    params.quality = "high";
    params.bgcolor = "#7d7d7d";
    params.allowscriptaccess = "always";
    params.allowfullscreen = "true";
    params.wmode = "Opaque";
    var attributes = {};
    attributes.id = "MyVideoPlayer";
    attributes.name = "MyVideoPlayer";
    attributes.style="position:relative;z-index:2000;"
    attributes.align = "middle";
    swfobject.embedSWF(
        "http://113.142.30.196:8060/E-WebTVyszd/media/player/SoPlayer.swf", "9b155ccf57a24d0db1c30258964d6aa0",
        "650", "485",  "Opaque",
        swfVersionStr, xiSwfUrlStr,
        flashvars, params, attributes);

    swfobject.createCSS("#9b155ccf57a24d0db1c30258964d6aa0", "display:block;text-align:left;");
}



$S.fn.tab=function(){
    var $Sthis=$S(this);
    var tabtop=$Sthis.children(".tabtop");
    var tabbottom=$Sthis.children(".tabbottom");
    tabtop.find("li").click(function(){
        var url = $S(this).attr("url").replace(/\@/g,"'");
        url = url.substring(7);
        var urlObj = eval("(" + url + ")");
        $S("#videoPlayerSource").attr("src",urlObj[0].url);
        var index=$S(this).index();
        $S(this).addClass("on").siblings().removeClass("on");
        tabbottom.children().eq(index).show().siblings().hide();
    });
}

/**
 * 显示日历的对象DateUtil
 */
var DateUtil = {};

DateUtil.date = new Date();
DateUtil.currentYear = DateUtil.date.getFullYear();
DateUtil.currentMonth = DateUtil.date.getMonth() + 1;
DateUtil.currentDay = DateUtil.date.getDay();
DateUtil.currentDate = DateUtil.date.getDate();


DateUtil.getDaysInMonth = function(year,month){
    month = parseInt(month,10)+1;
    var temp = new Date(year+"/"+month+"/0");
    return temp.getDate();
}

DateUtil.getWeekInfo = function(){
    if (DateUtil.currentDay == 0){
        DateUtil.currentDay = 7;
    }

    DateUtil.getPreviousDaysInfo();
    DateUtil.getCurrentDayInfo();
    DateUtil.getNextDaysInfo();
}

DateUtil.getPreviousDaysInfo = function(){
    var previousDate = DateUtil.currentDate;
    var year = DateUtil.currentYear;
    var month = DateUtil.currentMonth;

    for (var i = DateUtil.currentDay - 1; i > 0; i-- )
    {
        if (previousDate - 1 < 1)
        {
            if (DateUtil.currentMonth > 1)
            {
                month = month - 1;
                previousDate = DateUtil.getDaysInMonth(year, month);
            } else {
                year = year - 1;
                month = 12
                previousDate = DateUtil.getDaysInMonth(year, month);
            }
        } else {
            previousDate = previousDate - 1;
        }

        var date = year + "-";
        if (month < 10) {
            date += "0";
        }
        date += month + "-" + previousDate;

        var input = "<input type='hidden' value='"+date+"'>"
        var dateDiv = "<div class='date'>"+previousDate+"</div>";
        $S(".tabtop > li:eq(" + (i-1) + ")").append(dateDiv);
        $S(".tabtop > li:eq(" + (i-1) + ")").append(input);
    }
}

DateUtil.getCurrentDayInfo = function(){
    var date = DateUtil.currentYear + "-";
    if (DateUtil.currentMonth < 10) {
        date += "0";
    }
    date += DateUtil.currentMonth + "-" + DateUtil.currentDate;

    var input = "<input type='hidden' value='"+date+"'>"
    var dateDiv = "<div class='date'>"+DateUtil.currentDate+"</div>";
    $S(".tabtop > li:eq(" + (DateUtil.currentDay - 1) + ")")
        .addClass("on")
        .append(dateDiv)
        .siblings().removeClass("on");
    $S(".tabtop > li:eq(" + (DateUtil.currentDay - 1) + ")").append(input);

}

DateUtil.getNextDaysInfo = function() {
    currentMonthDays = DateUtil.getDaysInMonth(DateUtil.currentYear,DateUtil.currentMonth);
    var nextDate = DateUtil.currentDate;
    var year = DateUtil.currentYear;
    var month = DateUtil.currentMonth;

    for (var i = DateUtil.currentDay + 1; i <= 7; i++)
    {
        if (nextDate + 1 > currentMonthDays)
        {
            if (DateUtil.currentMonth < 12)
            {
                month = month + 1;
                nextDate = DateUtil.getDaysInMonth(DateUtil.currentYear, month);
            } else {
                year = year + 1;
                month = 1;
                nextDate = DateUtil.getDaysInMonth(year, 1);
            }
        } else {
            nextDate = nextDate + 1;
        }

        var date = year + "-";
        if (month < 10) {
            date += "0";
        }
        date += month + "-" + nextDate;

        var input = "<input type='hidden' value='"+date+"'>"
        var dateDiv = "<div class='date'>"+nextDate+"</div>";
        $S(".tabtop > li:eq(" + (i-1) + ")").append(dateDiv);
        $S(".tabtop > li:eq(" + (i-1) + ")").append(input);
    }
}

DateUtil.createWeekElement = function(){

    return $S("<ul class='tabtop'>" +
        "	<li class='on'>"+
        "		<div class='week'>一</div>"+
        "	</li>"+
        "	<li>"+
        "		<div class='week'>二</div>"+
        "	</li>"+
        "	<li>"+
        "		<div class='week'>三</div>"+
        "	</li>"+
        "	<li>"+
        "		<div class='week'>四</div>"+
        "	</li>"+
        "	<li>"+
        "		<div class='week'>五</div>"+
        "	</li>"+
        "	<li>"+
        "		<div class='week'>六</div>"+
        "	</li>"+
        "	<li class='last'>"+
        "		<div class='week'>日</div>"+
        "	</li>"+
        "</ul>");
}
DateUtil.bindClickEvent = function(){
    $S("ul.tabtop").find("li").each(function(){
        var _this = $S(this);
        _this.click(function(){
            var _channelOn = $S(".libox").find("li[class=on]");
            var date = _this.find("input").val();
            var weekDay = _this.find("div.week").html();
            var cid = _channelOn.attr("cid");
            var channel = _channelOn.html();
            var parameter = "{'cId':'" + cid + "','Date':'" + date + "'}";

            $S("span.fl").html(channel + "节目单");
            $S("span.fr").html(date + " 星期" + weekDay);
            _this.addClass("on").siblings().removeClass("on");
            PlayBills.getPlayBills(parameter);

        });
    });
}


function changeChannels(address){
    //address = address.replace(/\@/g,"'");
    //var urlObj = eval("("+address+")");
    //var url = urlObj[0].url;
    changeVideoSrc(address);

}
/**
 * 显示频道信息的对象Channels
 */
var Channels = {};
Channels.getChannelInfo = function(dimension,siteid,func) {

    var data = {
        'parameter':'{"Dimension":"'+dimension+'","SiteId":"'+siteid+'"}',
        'method':'ListChannels'
    };
    $S.ajax({
        type:"POST",
        dataType: 'jsonp',
        jsonp: 'jsoncallback', //默认callback
        data:data,
        url:"http://vmsbus.sobeycache.com:8080/vmsbus2/JSONReceiver",
        success:function(json) {
            var jsonObj = eval(json);
            if (jsonObj) {
                var _libox = "";
                for (var i = 0; i < jsonObj.length; i++) {
                    //var address = jsonObj[i].C_Address.replace(/\'/g,"@");
                    var address = jsonObj[i].C_Address;
                    _libox += "<li onclick='changeChannels(\""+address+"\");' url='"+address+"' cid='"+jsonObj[i].C_Id+"'>" + jsonObj[i].C_Name + "</li>";
                }
                $S(".libox > ul").html(_libox).find("li:eq(0)").addClass("on");
                if (func) {
                    func();
                }
            }

        },
        error:function(msg) {
            alert(msg);
        }
    });


}

Channels.createChannelsElement = function(){

    return $S("<div class='tabtop tvlist'>"+
        "	  <img src='http://113.142.30.196:8060/E-WebTVyszd/media/player/images/leftmove.jpg' id='leftmove' alt='向左移动列表' />"+
        "    <div class='libox'>"+
        "	      <ul>"+
        "	      </ul>"+
        "    </div>"+
        "    <img src='http://113.142.30.196:8060/E-WebTVyszd/media/player/images/rightmove.jpg' id='rightmove' alt='向右移动列表' />"+
        "</div>");
}

Channels.bindClickEvent = function(){
    $S(".libox > ul").find("li").each(function(){
        var _this = $S(this);
        _this.click(function(){
            var _dateOn = $S("ul.tabtop").find("li[class=on]");
            var cid = _this.attr("cid");
            var date = _dateOn.find("input").val();
            var parameter = "{'cId':'" + cid + "','Date':'" + date + "'}";
            var channel = _this.html();
            var weekDay = _dateOn.find("div.week").html();

            $S("span.fl").html(channel + "节目单");
            $S("span.fr").html(date + " 星期" + weekDay);
            _this.addClass("on").siblings().removeClass("on");
            PlayBills.getPlayBills(parameter);
        })
    });
}

/* 支持HTML5的直播节目单回看 */
function lookBack(li) {


    var startTime = $S(li).attr("starttime");
    var liboxes = $S(".libox").find("ul li");
    var url = "" ;
    for(var i=0 ; i < liboxes.length ; i++) {
        var clazz = $S(liboxes[i]).attr("class");
        if(clazz == "on") {
            url = $S(liboxes[i]).attr("url");

        }
    }

    var time = new Date(startTime.replace(/-/g,"/")).getTime();
    var address = url + "&shifttime=" + time;

    changeVideoSrc(address);
    /*
     var data = {
     'parameter':'{"ActscId":"'+actId+'"}',
     'method':'getActChannelInfo'
     };

     $S.ajax({
     type:"POST",
     data:data,
     dataType: 'jsonp',
     jsonp: 'jsoncallback', //默认callback
     url:"http://vmsbus.sobeycache.com:8080/vmsbus2/JSONReceiver",
     success:function(json) {
     var url = json[0].url;
     var urlObj = eval("(" + url + ")") ;
     var startTime = obj[0].starttime;
     var time = new Date(startTime.replace(/-/g,"/")).getTime();
     var address = urlObj[0].url + "&shifttime=" + time;
     changeVideoSrc(address);
     },
     error:function(msg) {
     alert(msg);
     }
     });
     */
}

/**
 * 显示节目单的对象PlayBills
 */
var PlayBills = {};

PlayBills.getPlayBills = function(param,func){

    var data = {
        'parameter':param,
        'method':'ListChActWDate'
    };
    $S.ajax({
        type:"POST",
        dataType: 'jsonp',
        data:data,
        jsonp: 'jsoncallback', //默认callback
        url:"http://vmsbus.sobeycache.com:8080/vmsbus2/JSONReceiver",
        success:function(json) {
            var jsonObj = eval(json);

            if (jsonObj) {
                var _bill = "";
                for (var i = 0; i < jsonObj.length; i++) {
                    var playTime = jsonObj[i].PlayTime;
                    var startTime = playTime;
                    var isPlaying = jsonObj[i].IsPlaying;
                    var name = jsonObj[i].Name;
                    var actId = jsonObj[i].Id;
                    var idx = playTime.indexOf(" ");
                    var liClass = "";

                    if (isPlaying == 0) {
                        isPlaying = "回看";
                    } else if (isPlaying == 1) {
                        isPlaying = "正在观看";
                        liClass = "on";
                    }
                    if (idx != -1) {
                        playTime = playTime.substring(idx);
                    }
                    _bill += "<li onclick='lookBack(this);' starttime='"+startTime+"' class='"+liClass+"'><em></em><a href='#'>" + playTime + " " + name + "</a><span>" + isPlaying + "</span></li>";

                }
                $S("#playbill").html(_bill);
                if (func) {
                    func();
                }
            }

        },
        error:function(msg) {
            alert(msg);
        }
    });
}

PlayBills.createBillsElement = function(){

    return $S("<div class='tabbottom'>"+
        "	<div> "+
        "		<div class='tvinfo'>"+
        "			<span class='fl'></span><span class='fr'></span>"+
        "		</div>"+
        "		<ul id='playbill'>"+
        "		</ul>"+
        "	</div>"+
        "</div>");
}

/**
 * 最终呈现Pannel对象PlayerPannel
 */
var PlayerPannel = {};

PlayerPannel.initHtml = function(){
    var _weeklist = $S("<div class='weeklist'></div>");
    var _tabbottom = $S("<div class='tabbottom'></div>");
    var _zblist = $S("<div class='zblist'></div>");

    _weeklist
        .append(DateUtil.createWeekElement())
        .append(PlayBills.createBillsElement());
    _tabbottom.append(_weeklist);
    _zblist
        .append(_tabbottom)
        .append(Channels.createChannelsElement());
    $S("#livePanel2").append(_zblist);
}

PlayerPannel.initPannel = function(dimension,siteid,func){
    PlayerPannel.initHtml();
    DateUtil.getWeekInfo();
    Channels.getChannelInfo(dimension,siteid,function(){
        var lm=$S("#leftmove"),rm=$S("#rightmove"),ulbox=$S(".libox").children("ul"),ulboxw=$S(".libox").width(),lw=ulbox.find("li:first").outerWidth(true),
            moveTime=200;
        var _channelOn = $S(".libox").find("li[class=on]");
        var _dateOn = $S("ul.tabtop").find("li[class=on]");
        var date = _dateOn.find("input").val();
        var weekDay = _dateOn.find("div.week").html();
        var cid = _channelOn.attr("cid");
        var channel = _channelOn.html();
        var parameter = "{'cId':'" + cid + "','Date':'" + date + "'}";

        $S("span.fl").html(channel + "节目单");
        $S("span.fr").html(date + " 星期" + weekDay);
        PlayBills.getPlayBills(parameter);
        lm.click(function(){
            var ml=parseInt(ulbox.css("margin-left"));
            if(ml >= 0)return;
            var m=ml+lw > 0 ? 0 : ml+lw;
            ulbox.stop().animate({
                marginLeft:m
            },moveTime);
        });
        rm.click(function(){
            var ml=parseInt(ulbox.css("margin-left"));
            var w=ulboxw-ulbox.find("li").length*lw;
            if(ml <= w)return;
            var m=ml-lw < w ? w : ml-lw;
            ulbox.stop().animate({
                marginLeft:m
            },moveTime);
        });
        DateUtil.bindClickEvent();
        Channels.bindClickEvent();
        $S(".zblist").tab();
        $S(".weeklist").tab();

    });

    if (func) {
        func();
    }

}

function createLivePanel4IOS(dimension,siteid) {
    dimension = 'iphone';
    $S(function(){
        PlayerPannel.initPannel(dimension,siteid);
    });
}

function createLivePanel4PC(siteid,dimension){

    var swfVersionStr = "10.2.0";
    var xiSwfUrlStr = "playerProductInstall.swf";
    var fvar={};

    fvar.host="http://vmsbus.sobeycache.com:8080/vmsbus2/JSONReceiver";
    fvar.autoPlay=false;
    fvar.clp="{'SiteId':'"+siteid+"','Dimension':'"+dimension+"'}";
    fvar.channel='';
    fvar.skin="http://113.142.30.196:8060/E-WebTVyszd/media/player/LivePanelSkins/black.swf";//配置要使用的节目单皮肤,默认无则配置与播放器皮肤skin="blue"相匹配的节目单皮肤）
    var params1 = {};

    params1.quality = "high";
    params1.bgcolor = "#7d7d7d";
    params1.allowscriptaccess = "always";
    params1.allowfullscreen = "true";
    params1.wmode = "Opaque";
    var attributes1 = {};
    attributes1.id = "LivePanel";
    attributes1.name = "LivePanel";
    attributes1.align = "middle";
    swfobject.embedSWF(
        "http://113.142.30.196:8060/E-WebTVyszd/media/player/LivePanel.swf", "livePanel2",
        "100%","100%","Opaque",
        swfVersionStr, xiSwfUrlStr,
        fvar, params1, attributes1);

    swfobject.createCSS("#livePanel2", "display:block;text-align:left;");

}

/**
 交互函数
 */

var playerReady=false;
var initUrl;
function onPlayerStateChanged(state){
    console.log(state);
    if(state == "playerLoaded"){
        playerReady=true;
        if(initUrl){
            setTimeout(playInitUrl,10);
        }
    }
    if(state=="playbackError")document.getElementById("LivePanel").lvNext();
}
function playInitUrl(){
    var player=document.getElementById('MyVideoPlayer');
    player.PlayNew(initUrl);
}
function onSelectedProgramChanged(id,manually){

    if(manually){
        var player=document.getElementById('MyVideoPlayer');

        if(id.indexOf(":") == -1) {

            var url="live://pid:"+id;

            player.PlayNew(url);

        } else {

            player.PlayNew(id);

        }

    }
}
function onSelectedChannelChanged(id,address){

    if(playerReady){
        var player=document.getElementById('MyVideoPlayer');
        player.PlayNew(address);
    }
    else{
        initUrl=address;
    }

}
var lightOnInfo=["开灯","kaideng1.png","kaideng2.png"];
var lightOffInfo=["关灯","guandeng1.png","guandeng2.png"];
var wideScreenInfo=["宽屏","kuanping1.png","kuanping2.png"];
var narrowScreenInfo=["窄屏","zaiping1.png","zaiping2.png"];

function widdenwindow(id,data)
{
    widenPlayer(id);
}

function closewindow(id,data){
    switchLight(id);
}
var wideScreen=true;
function widenPlayer(id){
    if(wideScreen){
        //narrow the player
        //$(".player").width(narrowScreenWidth);
        $S("#MyVideoPlayer").css({
            "width":"897",
            "margin-left":"-100px"
        });
        //$("#listArea").show();
        //$("#listAreaNew").hide();
        //$("#dengright").css("margin-top","0");
        setInteractPluginData(id,narrowScreenInfo[0],narrowScreenInfo[1],narrowScreenInfo[2]);
    }
    else{
        //widen the player
        //$(".player").width(wideScreenWidth);
        $S("#MyVideoPlayer").css({
            "width":"660",
            "margin-left":"0px"
        });
        //$("#listArea").hide();
        //$("#listAreaNew").show();
        //$("#dengright").css("margin-top","550px");
        setInteractPluginData(id,wideScreenInfo[0],wideScreenInfo[1],wideScreenInfo[2]);
    }
    wideScreen=!wideScreen;

    //setInteractPluginData(id,narrowScreenInfo[0],narrowScreenInfo[1],narrowScreenInfo[2]);
    //return wideScreen;
};

function setInteractPluginData(id,title,icon,hoverIcon){
    try{
        document.getElementById("MyVideoPlayer").setInteractPluginData(id,title,icon,hoverIcon);
        //document.getElementById("MyVideoPlayer").PlayNew("http://www.baidu.com");
    }catch(e){
        alert(e);
    }
};
var lightFlag=true;
function switchLight(id){
    if(lightFlag){
        $S(".mask_page").fadeIn(500);
        setInteractPluginData(id,lightOnInfo[0],lightOnInfo[1],lightOnInfo[2]);
    }
    else{
        $S(".mask_page").fadeOut(500);
        setInteractPluginData(id,lightOffInfo[0],lightOffInfo[1],lightOffInfo[2]);
    }
    lightFlag=!lightFlag;
};
$S(document).ready(function(){
    $S("<div/>").attr("class","mask_page").css({
        position: "fixed",
        backgroundColor: "#101010",
        display: "none",
        height: "100%",
        left: 0,
        opacity: 1,
        top: 0,
        width: "100%",
        zIndex: "1000"
    }).prependTo("body");
    $S("#video_player").css({
        position:"relative",
        zIndex:"2000"
    })
});

/**
 * Implementation of Ajax-Core with JSON-Interface
 */
function Ajax(url,callBack) {
    var here= this;
    this.url= url;
    this.method= "POST";
    this.callback= callBack;
    this.request= this.getHTTPRequest();	  // Objekt generieren
    if (this.request === null) {
        throw "cant create XMLHttpRequest-Object";
    }
    this.request.onreadystatechange = function(){
        here.processChange.call(here);
    };
};
Ajax.prototype.disconnect= function() {
    this.request= null;
};
Ajax.prototype.STATE_UNINITIALIZED = 0;
Ajax.prototype.STATE_LOADING = 1;
Ajax.prototype.STATE_LOADED = 2;
Ajax.prototype.STATE_INTERACTIVE = 3;
Ajax.prototype.STATE_COMPLETE = 4;   //"Konstanten" definieren

Ajax.prototype.getHTTPRequest= function() {
    var req = null;
    if (typeof XMLHttpRequest !== "undefined") {
        req = new XMLHttpRequest(); // Mozilla und Co.
    } else {
        if (typeof ActiveXObject !== "undefined") {
            req = new ActiveXObject("Microsoft.XMLHTTP");
            if (!req) {
                req = new ActiveXObject("Msxml2.XMLHTTP");
            }
        }
    }
    return req;
};
Ajax.prototype.crackParams= function(params) {
    var str= "";
    var amper= false;
    for(var p in params) {
        if(amper) {
            str+= "&";
        }
        str+= encodeURI(p)+"="+encodeURI(params[p]);
        amper= true;
    }
    //c.write("CrackParams: "+str);
    return str;
};
Ajax.prototype.send= function(msg) {
    this.sendRequest(this.url, msg, this.method,false);
};
Ajax.prototype.onError= function(err) {
    var errMsg= "";
    if((arguments.length===1)||(err!==null)) {
        errMsg= ""+err;
    }
    var
    str  = "Ajax communication error '"+errMsg+"'\n\n";
    str += "URL="+this.url+"\n";
    str += "XHR-state="+this.request.readyState+"\n";
    str += "HTTP-status code="+this.request.status+"\n";
    str += "Headers='"+this.request.getAllResponseHeaders()+"'";
    alert(str);
};
Ajax.prototype.processChange= function() {
    if (this.request.readyState === this.STATE_COMPLETE) {
        if ((this.request.status===200)||(this.request.status===0)) {
            this.callback(this.request.responseText);
        } else {
            if(this.request.status===404) {
                var str= "404 on "+this.url+"\n";
                alert(str);
            } else {
                this.onError();
            }
        }
    }
};
Ajax.prototype.sendRequest= function(url, params, method, async){
    var state= this.request.readyState;
    if (arguments.length < 3) {
        method = "POST";
    }
    if (arguments.length < 4) {
        async= true;
    }
    try {
        if ((state!==this.STATE_COMPLETE)&&(state!==this.STATE_UNINITIALIZED)){
            throw "XMLHttpRequest-Object isnt ready for new request";
        }
        this.request.open(method,url,async);
        this.request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        this.request.setRequestHeader("x_requested_with","42");
        if(typeof params==="object") {
            this.request.send(this.crackParams(params));
        } else {
            this.request.send();
        }
    } catch (err) {
        this.onError.call(this,err);
    }
};  
/**
 * Receiving part
 * receive() will be called back by the xmlhttpreq.-Object
 * Checks the received JSON-Object according the specification in the global
 * variable receivedObj and copy only the specified object components, so that
 * receivedObj has the expected values.
 * 
 */
var receivedObj= null;
function receive(text) {
    try {
        var PHPabort= (text[0]!=='{')||(text[text.length-1]!=='}');
        if(PHPabort) {
            PHPabort= (text.indexOf("Stack trace")!==-1);
            if (!PHPabort) {
                var first= text.indexOf('{');
                var last= text.lastIndexOf('}');
                var prefix= text.substring(0,first);
                var postfix= ''+text.substring(last+1);
                if(prefix!=='') {
                    write2console(prefix);
                }
                if(postfix!=='') {
                    write2console(postfix);
                }
                text= text.substring(first,last+1);
            }
        }
        if((text==='')||(text===null)||PHPabort) {
            if(text!=='') {
                write2console(text);
            }
            receivedObj= null;
            throw "received empty message - abort";
        }
//        write2console('*'+text+'*');
        var obj= JSON.parse(text);
        for (var elem in receivedObj) {
            if(obj[elem]===undefined) {
                throw "wrong received value:"+text;
            }
            receivedObj[elem]= obj[elem];
        }
    } catch (err) {
        write2console(err);
        throw err;
    }
}




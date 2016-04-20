function Logger() {
    this.allText= "";
    this.tail= document.getElementsByTagName("body")[0];
    this.con= document.createElement("div");
    this.con.style.fontSize= "10pt";
    this.con.style.left= "500px";
    this.con.style.top= "40px";
    this.con.style.width= "500px";
    this.con.style.border= "dashed red 2px";
    this.con.style.position = "absolute";
    this.con.style.backgroundColor= "whitesmoke";
    this.con.style.overflow= "auto";
    this.tail.appendChild(this.con);
}
Logger.prototype.writeOut= function(text) {
    this.allText+= text+"<br>";
    this.con.innerHTML= this.allText;
};
function write2console(text) {
    this.wrt;
    if(this.wrt === undefined) {
        this.wrt= new Logger();
    }
    this.wrt.writeOut(text);
}


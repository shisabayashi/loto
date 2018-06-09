
var casper = require("casper").create();
var url = casper.cli.args[0];

casper.start(url, function(){
    this.echo(this.getHTML()) ;
});

casper.run(function() {
    this.exit();
});


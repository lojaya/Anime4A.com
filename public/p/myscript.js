var webPage = require('webpage');
var page = webPage.create();

page.open('http://onecloud.media/embed/ZXlETGxxMkNYMTc1NDE', function (status) {
    var content = page.content;
    console.log('Content: ' + content);
    phantom.exit();
});
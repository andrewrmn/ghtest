const fs = require('fs');

module.exports = function(){

    const cssFile = 'style.css';

    fs.readFile( cssFile, 'utf8', function (err,data) {
        if (err) {
            return console.log(err);
        }

        var result = data.replace(/(Version: )(\d+)/g, '$1'+Date.now());
        fs.writeFile(cssFile, result, 'utf8', function (err) {
            if (err) return console.log(err);
        });
    });

}

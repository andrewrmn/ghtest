const fs = require("fs");
const sources  = require( "./sources.js");

const module_template = '.{module_name}{\n\n}';

function checkFileDoesntExist( file ) {
    if( fs.existsSync( file )) {
        console.error('\x1b[31m%s\x1b[0m', 'ERROR: file ' + file + ' already exists');
        return false;
    }
    return true;
}

module.exports = function(done){

    const args  = process.argv;

    const module_name = args.pop().substr(2);

    const style_scss_file = sources.css.modules_index;
    const output_module_file = sources.css.modules_dir + '_' + module_name + '.scss';

    if (arguments.length === 0) {
        console.error('\x1b[31m%s\x1b[0m', 'ERROR: You must specify the module name. Example: gulp module --siteHeader');
    } else {

        if( checkFileDoesntExist( output_module_file ) ){

            fs.readFile(style_scss_file, 'utf8', function(err, style_scss_contents){
                style_scss_contents += "\n@import 'modules/" + module_name + "';";
                fs.writeFileSync(style_scss_file, style_scss_contents);

                fs.writeFileSync(output_module_file, module_template.replaceAll('{module_name}', module_name ));
                console.log('\x1b[32m%s\x1b[0m', 'File created successfully: ' + output_module_file.replaceAll('./', '') );

            });
        }

        done();
    }

}


'use strict';

var path = require('path');
var fs = require('fs');
var logger = require('../logger')(__filename);
var promzard = require('promzard');

var generatePath = '../../generate/component/';

function generate(component) {
    if (!component) {
        return false;
    }
    var file = path.resolve(__dirname, generatePath + component + '.js');
    if (fs.existsSync(file)) {
        var ctx = { basename: path.basename(path.dirname(file)) };
        promzard(file, ctx, function (er, res) {
            if (er) {
                throw er;
            }
            logger.error(res);
        });
    }
    else {
        logger.info('Not supported');
        return false;
    }
}

function getComponentList() {
    var folder = path.resolve(__dirname, generatePath);
    var files = fs.readdirSync(folder);
    var components = [];
    files.forEach(function (name) {
        components.push(path.basename(name, '.js'));
    });
    return components;
}

module.exports.getComponentList = getComponentList;
module.exports.generate = generate;